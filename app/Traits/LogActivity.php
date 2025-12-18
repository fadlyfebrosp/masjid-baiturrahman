<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

trait LogActivity
{
    protected function logActivity(
        Request $request,
        string $action
    ): void {
        ActivityLog::create([
            'user_id'    => Auth::id(),
            'session_id' => session()->getId(),
            'action'     => $action,
            'method'     => $request->method(),
            'url'        => $request->fullUrl(),
            'ip_address' => $request->ip(),
            'user_agent' => substr($request->userAgent() ?? '', 0, 255),
        ]);
    }
}
