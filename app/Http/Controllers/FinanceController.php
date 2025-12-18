<?php

namespace App\Http\Controllers;

use App\Models\Donasi;
use Illuminate\Support\Facades\Auth;

class FinanceController extends Controller
{
    /* ===============================
     | DASHBOARD
     =============================== */
    public function index()
    {
        $transactions = Donasi::with(['user', 'program', 'transaction'])
            ->latest()
            ->limit(5) // contoh ringkas dashboard
            ->get();

        return view('finance.dashboard', compact('transactions'));
    }

    /* ===============================
     | TRANSACTION PAGE
     =============================== */
    public function transaction()
    {
        $transactions = Donasi::with(['user', 'program', 'transaction'])
            ->latest()
            ->paginate(10);

        return view('finance.transaction', compact('transactions'));
    }
}
