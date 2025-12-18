<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Database\QueryException;
use Exception;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email:rfc,dns|unique:users',
            'phone'    => 'required|digits_between:10,15|unique:users,phone',
            'gender'   => 'required|in:Laki-laki,Perempuan',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/[A-Z]/',
                'regex:/[a-z]/',
                'regex:/[0-9]/',
            ],
        ], [
            'password.regex' => 'Password harus berisi huruf besar, huruf kecil, dan angka.',
        ]);

        try {
            $user = User::create([
                'name'     => strip_tags($request->name),
                'email'    => strtolower($request->email),
                'phone'    => preg_replace('/\D/', '', $request->phone),
                'gender'   => $request->gender,
                'password' => Hash::make($request->password),
                'role'     => 'jamaah',
            ]);

            ActivityLog::create([
                'user_id'    => $user->id,
                'session_id' => session()->getId(),
                'action'     => 'Registrasi akun baru',
                'ip_address'  => $request->ip(),
                'user_agent'  => substr($request->header('User-Agent') ?? '', 0, 255),
            ]);

            return redirect()->route('login')
                ->with('success', 'Registrasi berhasil! Silakan login.');
        } catch (QueryException $e) {
            Log::error('Database error saat registrasi: ' . $e->getMessage());
            return back()->withInput()->withErrors(['error' => 'Terjadi kesalahan pada server.']);
        } catch (Exception $e) {
            Log::error('Error registrasi: ' . $e->getMessage());
            return back()->withInput()->withErrors(['error' => 'Gagal memproses pendaftaran.']);
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'login'    => 'required|string',
            'password' => 'required|string|min:8',
        ]);

        $ip  = $request->ip();
        $key = Str::lower('login:' . $request->input('login') . '|' . $ip);

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->withErrors([
                'login' => 'Terlalu banyak percobaan login. Coba lagi dalam ' . $seconds . ' detik.',
            ]);
        }

        try {
            $loginType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
            $user = User::where($loginType, $request->login)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                RateLimiter::hit($key, 60);
                ActivityLog::create([
                    'user_id'    => $user->id ?? null,
                    'session_id' => session()->getId(),
                    'action'     => 'Login gagal',
                    'ip_address'  => $ip,
                    'user_agent'  => substr($request->header('User-Agent') ?? '', 0, 255),
                ]);
                return back()->withInput()->withErrors(['login' => 'Password Anda Salah']);
            }

            if (isset($user->status) && $user->status === 'nonaktif') {
                return back()->withErrors(['login' => 'Akun Anda dinonaktifkan.']);
            }

            Auth::login($user);
            $request->session()->regenerate();
            RateLimiter::clear($key);

            ActivityLog::create([
                'user_id'    => $user->id,
                'session_id' => session()->getId(),
                'action'     => 'Login berhasil',
                'ip_address'  => $ip,
                'user_agent'  => substr($request->header('User-Agent') ?? '', 0, 255),
            ]);

            $redirects = [
                'admin'   => '/admin/dashboard',
                'finance' => '/finance/dashboard',
            ];

            return redirect()->intended($redirects[$user->role] ?? '/')
                ->with('success', 'Selamat datang kembali, ' . e($user->name) . '!');
        } catch (Exception $e) {
            Log::error('Error login: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan sistem.']);
        }
    }

    public function logout(Request $request)
    {
        try {
            $user = Auth::user();

            if ($user) {
                ActivityLog::create([
                    'user_id'    => $user->id,
                    'session_id' => session()->getId(),
                    'action'     => 'Logout',
                    'ip_address'  => $request->ip(),
                    'user_agent'  => substr($request->header('User-Agent') ?? '', 0, 255),
                ]);
            }

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('home')->with('success', 'Anda telah logout dengan aman.');
        } catch (Exception $e) {
            Log::error('Logout error: ' . $e->getMessage());
            return redirect()->route('home')->withErrors(['error' => 'Gagal logout.']);
        }
    }

    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email:rfc,dns']);

        try {
            $user = User::where('email', $request->email)->first();

            if (!$user) {
                return back()->withErrors(['email' => 'Email tidak ditemukan.']);
            }

            $status = Password::sendResetLink($request->only('email'));

            ActivityLog::create([
                'user_id'    => $user->id,
                'session_id' => session()->getId(),
                'action'     => $status === Password::RESET_LINK_SENT
                    ? 'Kirim link reset password berhasil'
                    : 'Gagal mengirim link reset password',
                'ip_address'  => $request->ip(),
                'user_agent'  => substr($request->header('User-Agent') ?? '', 0, 255),
            ]);

            return $status === Password::RESET_LINK_SENT
                ? back()->with('success', 'Link reset password telah dikirim ke email Anda.')
                : back()->withErrors(['email' => 'Gagal mengirim link reset password.']);
        } catch (Exception $e) {
            Log::error('Error kirim reset link: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan server.']);
        }
    }

    public function showResetForm($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email:rfc,dns',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/[A-Z]/',
                'regex:/[a-z]/',
                'regex:/[0-9]/',
            ],
        ], [
            'password.regex' => 'Password harus berisi huruf besar, huruf kecil, dan angka.',
        ]);

        try {
            // gunakan helper request() di dalam closure agar Intelephense tidak error
            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function ($user) {
                    // ambil data dari helper global, bukan dari $request closure
                    $pw = request()->input('password');
                    $ip = request()->ip();
                    $ua = substr(request()->header('User-Agent') ?? '', 0, 255);

                    $user->forceFill([
                        'password' => Hash::make($pw),
                        'remember_token' => Str::random(60),
                    ])->save();

                    ActivityLog::create([
                        'user_id'    => $user->id,
                        'session_id' => session()->getId(),
                        'action'     => 'Reset password berhasil',
                        'ip_address'  => $ip,
                        'user_agent'  => $ua,
                    ]);
                }
            );

            return $status === Password::PASSWORD_RESET
                ? redirect()->route('login')->with('success', 'Password berhasil diubah. Silakan login.')
                : back()->withErrors(['email' => 'Token tidak valid atau sudah kedaluwarsa.']);
        } catch (Exception $e) {
            Log::error('Error reset password: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Terjadi kesalahan sistem.']);
        }
    }
}
