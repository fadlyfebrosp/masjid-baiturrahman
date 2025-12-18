<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\BeritaDanKegiatan;
use App\Models\Donasi;
use App\Models\Program;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;


class AdminController extends Controller
{
    public function index()
    {
        // 🔐 Cek login
        if (!Auth::check()) {
            return redirect('/login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();
        session(['user' => $user]);

        // 📊 Statistik
        $totalAkun    = User::count();
        $totalProgram = Program::count();
        $totalBerita  = BeritaDanKegiatan::count();

        // 📝 Activity Log (5 terakhir)
        $logs = ActivityLog::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // 📤 Kirim ke view
        return view('admin.dashboard', compact(
            'user',
            'totalAkun',
            'totalProgram',
            'totalBerita',
            'logs',
        ))->with([
            'pageTitle' => 'Dashboard'
        ]);
    }

    public function account()
    {
        $accounts = User::orderBy('created_at', 'desc')->get();
        return view('admin.account', compact('accounts'));
    }

    public function storeAccount(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone' => 'nullable|string|max:20',
            'gender' => 'nullable|in:Laki-laki,Perempuan',
            'password' => 'required|string|min:6|confirmed',
            'role' => ['required', Rule::in(['jamaah','admin','finance'])],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'gender' => $request->gender,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('admin.account')->with('success', 'Akun berhasil ditambahkan!');
    }

    public function updateAccount(Request $request, $id)
    {
        $account = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required','email', Rule::unique('users','email')->ignore($account->id)],
            'phone' => 'nullable|string|max:20',
            'gender' => 'nullable|in:Laki-laki,Perempuan',
            'password' => 'nullable|string|min:6|confirmed',
            'role' => ['required', Rule::in(['jamaah','admin','finance'])],
        ]);

        $account->name = $request->name;
        $account->email = $request->email;
        $account->phone = $request->phone;
        $account->gender = $request->gender;
        $account->role = $request->role;

        if ($request->filled('password')) {
            $account->password = Hash::make($request->password);
        }

        $account->save();

        return redirect()->route('admin.account')->with('success', 'Akun berhasil diperbarui!');
    }

    /**
     * Update role — supports both AJAX (expects JSON) and normal form (redirect).
     */
    public function updateRole(Request $request, $id)
    {
        $account = User::findOrFail($id);

        $request->validate([
            'role' => ['required', Rule::in(['jamaah','admin','finance'])],
        ]);

        $account->role = $request->role;
        $account->save();

        // Jika request AJAX (expects JSON), kirim JSON response
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Role berhasil diubah!',
                'role' => $account->role,
            ]);
        }

        // Fallback: redirect dengan flash message
        return redirect()->route('admin.account')->with('success', 'Role berhasil diubah!');
    }
    public function settings()
    {
        return view('admin.settings', [
            'sessionLifetime' => env('SESSION_LIFETIME'),
            'cookieSecure'    => env('SESSION_SECURE_COOKIE'),
        ]);
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'session_lifetime' => 'required|integer|min:1',
        ]);

        // Update .env value
        $this->setEnvValue([
            'SESSION_LIFETIME'      => $request->session_lifetime,
            'SESSION_SECURE_COOKIE' => $request->cookie_secure === 'true' ? 'true' : 'false',
        ]);

        return back()->with('success', 'Pengaturan berhasil diperbarui!');
    }

    private function setEnvValue(array $values)
    {
        $path = base_path('.env');
        $content = file_get_contents($path);

        foreach ($values as $key => $value) {
            $pattern = "/^{$key}=.*/m";
            $replace = "{$key}={$value}";
            $content = preg_replace($pattern, $replace, $content);
        }

        file_put_contents($path, $content);
    }
    public function destroyAccount($id)
    {
        $account = User::findOrFail($id);
        $account->delete();

        return redirect()->route('admin.account')->with('success', 'Akun berhasil dihapus!');
    }
}
