<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionTimeout
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $lastActivity = session('last_activity_time');
            $timeoutMinutes = cache('session_lifetime', config('session.lifetime'));
            $timeout = $timeoutMinutes * 60;

            if ($lastActivity && (time() - $lastActivity > $timeout)) {
                $user = Auth::user();

                // Log aktivitas sesi berakhir
                \App\Models\ActivityLog::create([
                    'user_id' => $user->id,
                    'session_id' => session()->getId(),
                    'action' => 'Sesi berakhir otomatis setelah tidak aktif',
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->header('User-Agent'),
                ]);

                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect('/')
                    ->with('error', 'Sesi kamu telah berakhir. Silakan login kembali.');
            }

            session(['last_activity_time' => time()]);
        }

        return $next($request);
    }
}
