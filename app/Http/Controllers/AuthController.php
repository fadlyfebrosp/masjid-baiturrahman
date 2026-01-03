<?php

namespace App\Http\Controllers;

use App\Models\KontakInformasi;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Database\QueryException;
use Exception;
use App\Traits\LogActivity;
use Illuminate\Support\Facades\Storage;


class AuthController extends Controller
{
    use LogActivity;
    private const DEFAULT_LOGO = 'assets/img/Image-not-found.png';
    private const STORAGE_PATH = 'storage/';

    /* ===============================
     | VIEW
     =============================== */
    public function showRegister()
    {
        $logo = $this->getLogo();
        return view('auth.register', compact('logo'));
    }

    public function showLogin()
    {
        $logo = $this->getLogo();
        return view('auth.login', compact('logo'));
    }

    public function showForgotPasswordForm()
    {
        $logo = $this->getLogo();
        return view('auth.forgot-password',compact('logo'));
    }
    public function showResetForm(Request $request, $token = null)
    {
        $logo = $this->getLogo();

        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email,
            'logo'  => $logo,
        ]);
    }

    private function getLogo(): string
    {
        $kontak = KontakInformasi::first();

        if (
            $kontak &&
            $kontak->logo &&
            Storage::disk('public')->exists($kontak->logo)
        ) {
            return asset(self::STORAGE_PATH . $kontak->logo);
        }

        return asset(self::DEFAULT_LOGO);
    }

    /* ===============================
     | REGISTER
     =============================== */
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email:rfc,dns|unique:users',
            'phone'    => 'required|digits_between:10,15|unique:users,phone',
            'gender'   => 'required|in:Laki-laki,Perempuan',
            'password' => [
                'required','string','min:8','confirmed',
                'regex:/[A-Z]/','regex:/[a-z]/','regex:/[0-9]/',
            ],
        ], [
            'password.regex' => 'Password harus berisi huruf besar, huruf kecil, dan angka.',
        ]);

        try {
            User::create([
                'name'     => strip_tags($request->name),
                'email'    => strtolower($request->email),
                'phone'    => preg_replace('/\D/', '', $request->phone),
                'gender'   => $request->gender,
                'password' => Hash::make($request->password),
                'role'     => 'jamaah',
            ]);

            $this->logActivity($request, 'Registrasi akun baru');

            return redirect()->route('login')
                ->with('success', 'Registrasi berhasil! Silakan login.');
        } catch (QueryException $e) {
            Log::error('DB error register: ' . $e->getMessage());
            return back()->withInput()->withErrors(['error' => 'Terjadi kesalahan server.']);
        } catch (Exception $e) {
            Log::error('Register error: ' . $e->getMessage());
            return back()->withInput()->withErrors(['error' => 'Gagal memproses pendaftaran.']);
        }
    }

    /* ===============================
     | LOGIN (✔ single-exit style)
     =============================== */
    public function login(Request $request)
    {
        $request->validate([
            'login'    => 'required|string',
            'password' => 'required|string|min:8',
        ]);

        $ip  = $request->ip();
        $key = Str::lower('login:' . $request->login . '|' . $ip);
        $response = null;

        try {
            if (RateLimiter::tooManyAttempts($key, 5)) {
                $seconds = RateLimiter::availableIn($key);
                $response = back()->withErrors([
                    'login' => "Terlalu banyak percobaan login. Coba lagi dalam {$seconds} detik.",
                ]);
            } else {
                $loginType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
                $user = User::where($loginType, $request->login)->first();

                if (!$user || !Hash::check($request->password, $user->password)) {
                    RateLimiter::hit($key, 60);
                    $this->logActivity($request, 'Login gagal');

                    $response = back()
                        ->withInput()
                        ->withErrors(['login' => 'Email/No HP atau password salah']);
                } elseif (isset($user->status) && $user->status === 'nonaktif') {
                    $response = back()->withErrors(['login' => 'Akun Anda dinonaktifkan.']);
                } else {
                    Auth::login($user);
                    $request->session()->regenerate();
                    RateLimiter::clear($key);

                    $this->logActivity($request, 'Login berhasil');

                    $redirects = [
                        'admin'   => '/admin/dashboard',
                        'finance' => '/finance/dashboard',
                    ];

                    $response = redirect()
                        ->intended($redirects[$user->role] ?? '/')
                        ->with('success', 'Selamat datang kembali, ' . e($user->name) . '!');
                }
            }
        } catch (Exception $e) {
            Log::error('Login error: ' . $e->getMessage());
            $response = back()->withErrors(['error' => 'Terjadi kesalahan sistem.']);
        }

        return $response;
    }

    /* ===============================
     | LOGOUT
     =============================== */
    public function logout(Request $request)
    {
        try {
            if (Auth::check()) {
                $this->logActivity($request, 'Logout');
            }

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('home')->with('success', 'Anda telah logout.');
        } catch (Exception $e) {
            Log::error('Logout error: ' . $e->getMessage());
            return redirect()->route('home')->withErrors(['error' => 'Gagal logout.']);
        }
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email:rfc,dns']);
        $response = null;

        try {
            $user = User::where('email', $request->email)->first();

            if (!$user) {
                $response = back()->withErrors(['email' => 'Email tidak ditemukan.']);
            } else {
                $status = Password::sendResetLink($request->only('email'));

                $this->logActivity(
                    $request,
                    $status === Password::RESET_LINK_SENT
                        ? 'Kirim link reset password berhasil'
                        : 'Gagal kirim link reset password'
                );

                $response = $status === Password::RESET_LINK_SENT
                    ? back()->with('success', 'Link reset password telah dikirim.')
                    : back()->withErrors(['email' => 'Gagal mengirim link reset password.']);
            }
        } catch (Exception $e) {
            Log::error('Reset link error: ' . $e->getMessage());
            $response = back()->withErrors(['error' => 'Terjadi kesalahan server.']);
        }

        return $response;
    }
    public function resetPassword(Request $request)
    {
        $logo = $this->getLogo();

        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => [
                'required',
                'confirmed',
                'min:8',
                'regex:/[A-Z]/',
                'regex:/[a-z]/',
                'regex:/[0-9]/',
            ],
        ], [
            'password.regex' => 'Password harus mengandung huruf besar, kecil, dan angka.',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()
                ->route('login')
                ->with('success', 'Password berhasil direset. Silakan login.');
        }

        // ❗️Jika gagal → kembali ke form reset dengan logo
        return back()
            ->withErrors(['email' => __($status)])
            ->withInput()
            ->with('logo', $logo);
    }
}
