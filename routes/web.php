<?php

use App\Http\Controllers\FinanceController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\BeritadankegiatanController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DonasiController;
use App\Http\Controllers\KontakInformasiController;
use App\Http\Controllers\PemasukkanController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\ProfiladminController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TinyMceController;

/*
|--------------------------------------------------------------------------
| HALAMAN UMUM (FRONTEND)
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::view('/tentangkami', 'pages.about')->name('tentangkami');
Route::get('/kontak', [ContactController::class, 'index'])
    ->name('kontak');

Route::post('/kontak', [ContactController::class, 'send'])
    ->name('kontak.send');
Route::prefix('profile')->group(function () {

    Route::get('/', [HomeController::class, 'profile'])
        ->name('profile');

    Route::get('/edit', [HomeController::class, 'editProfile'])
        ->name('profile.edit');

    Route::put('/', [HomeController::class, 'updateProfile'])
        ->name('profile.update');

});

/*
|--------------------------------------------------------------------------
| PROGRAM DONASI (FRONTEND)
|--------------------------------------------------------------------------
| ⚠️ INI MEMANG BUTUH {kategori}
*/
Route::prefix('program')->group(function () {

    Route::get('{kategori}', [ProgramController::class, 'byKategori'])
        ->name('program.index');

    Route::get('{kategori}/detail/{slug}', [ProgramController::class, 'detail'])
        ->name('program.detail');

    // DONASI
    Route::get('{kategori}/detail/{slug}/donate-now',
        [DonasiController::class, 'donateNow']
    )->name('donasi.form');

    Route::post('{kategori}/detail/{slug}/donate-now',
        [DonasiController::class, 'donateNowPost']
    )->name('donasi.form.post');

    Route::post('{kategori}/detail/{slug}/donate-now/store',
        [DonasiController::class, 'store']
    )->name('donasi.store');
});

/*
|--------------------------------------------------------------------------
| PAYMENT
|--------------------------------------------------------------------------
*/
Route::get('/payment/{donasi}/pay',
    [TransactionController::class, 'pay']
)->name('transaction.pay');

Route::post('/midtrans/callback',
    [TransactionController::class, 'callback']
);

/*
|--------------------------------------------------------------------------
| BERITA & KEGIATAN (FRONTEND)
|--------------------------------------------------------------------------
*/
Route::get('/berita', [BeritadankegiatanController::class, 'showPublic'])
    ->name('berita');

Route::get('/beritadankegiatan', [BeritadankegiatanController::class, 'showPublic'])
    ->name('beritadankegiatan.public');

Route::get('/berita/{judul}', [BeritadankegiatanController::class, 'detail'])
    ->name('beritadankegiatan.detail');

/*
|--------------------------------------------------------------------------
| AUTENTIKASI
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])
    ->name('password.request');

Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])
    ->name('password.email');

Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])
    ->name('password.reset');

Route::post('/reset-password', [AuthController::class, 'resetPassword'])
    ->name('password.update');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| ADMIN PANEL
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
    ->name('admin.')
    ->middleware([App\Http\Middleware\CheckRole::class . ':admin'])
    ->group(function () {

        Route::get('/dashboard', [AdminController::class, 'index'])
            ->name('dashboard');

        // TinyMCE
        Route::post('/tinymce/upload', [TinyMceController::class, 'upload'])
            ->name('tinymce.upload');

        // PROGRAM (ADMIN)
        Route::resource('program', ProgramController::class)->except(['show']);

        Route::get('program/{program}/show',
            [ProgramController::class, 'show']
        )->name('program.show');

        // BERITA & KEGIATAN (ADMIN)
        Route::resource('beritadankegiatan', BeritadankegiatanController::class);

        // ACCOUNT
        Route::get('/account', [AdminController::class, 'account'])->name('account');
        Route::post('/account', [AdminController::class, 'storeAccount'])->name('account.store');
        Route::put('/account/{id}', [AdminController::class, 'updateAccount'])->name('account.update');
        Route::delete('/account/{id}', [AdminController::class, 'destroyAccount'])->name('account.destroy');
        Route::patch('/account/{id}/role', [AdminController::class, 'updateRole'])->name('account.updateRole');

        // PROFILE
        Route::get('/profile', [ProfiladminController::class, 'index'])->name('profile');
        Route::post('/profile/update', [ProfiladminController::class, 'update'])->name('profile.update');
        Route::post('/logout', [ProfiladminController::class, 'logout'])->name('logout');

        // ACTIVITY LOG
        Route::get('/activity-log', [ActivityLogController::class, 'index'])
            ->name('activitylog');

        // SETTINGS (ADMIN)
        Route::prefix('pengaturan')
            ->name('pengaturan.')
            ->group(function () {

                // GENERAL (IDENTITAS APLIKASI)
                Route::get('/general', [KontakInformasiController::class, 'index'])
                    ->name('general');

                Route::put('/general', [KontakInformasiController::class, 'update'])
                    ->name('general.update');

                // SECURITY
                Route::get('/security', [SettingController::class, 'security'])
                    ->name('security');

                Route::post('/security', [SettingController::class, 'saveSecurity'])
                    ->name('security.save');

                // MIDTRANS
                Route::get('/midtrans', [SettingController::class, 'midtrans'])
                    ->name('midtrans');

                Route::post('/midtrans', [SettingController::class, 'saveMidtrans'])
                    ->name('midtrans.save');
            });
    });

/*
|--------------------------------------------------------------------------
| FINANCE
|--------------------------------------------------------------------------
*/
Route::prefix('finance')
    ->name('finance.')
    ->middleware([App\Http\Middleware\CheckRole::class . ':finance'])
    ->group(function () {

        Route::get('/dashboard', [FinanceController::class, 'index'])
            ->name('dashboard');

        Route::get('/transaction', [FinanceController::class, 'transaction'])
            ->name('transaction');

        Route::get('/pemasukkan', [PemasukkanController::class, 'index'])
            ->name('pemasukkan.index');

        Route::post('/pemasukkan', [PemasukkanController::class, 'store'])
            ->name('pemasukkan.store');

        Route::put('/pemasukkan/{id}', [PemasukkanController::class, 'update'])
            ->name('pemasukkan.update');

        Route::delete('/pemasukkan/{id}', [PemasukkanController::class, 'destroy'])
            ->name('pemasukkan.destroy');

        Route::get('/pengeluaran', [PengeluaranController::class, 'index'])
            ->name('pengeluaran.index');

        Route::post('/pengeluaran', [PengeluaranController::class, 'store'])
            ->name('pengeluaran.store');

        Route::put('/pengeluaran/{id}', [PengeluaranController::class, 'update'])
            ->name('pengeluaran.update');

        Route::delete('/pengeluaran/{id}', [PengeluaranController::class, 'destroy'])
            ->name('pengeluaran.destroy');

        Route::get('/pemasukkan/laporan', [PemasukkanController::class, 'laporan'])
            ->name('pemasukkan.laporan');

        Route::get('/pengeluaran/laporan', [PengeluaranController::class, 'laporan'])
            ->name('pengeluaran.laporan');
    });
/*
|--------------------------------------------------------------------------
| KALKULATOR
|--------------------------------------------------------------------------
*/
Route::view('/kalkulatorzakat', 'program.kalkulatorzakat')->name('kalkulator.zakat');
Route::view('/kalkulatorinfaq', 'program.kalkulatorinfaq')->name('kalkulator.infaq');
Route::view('/kalkulatorshodaqah', 'program.kalkulatorshodaqah')->name('kalkulator.shodaqah');
Route::view('/kalkulatorwakaf', 'program.kalkulatorwakaf')->name('kalkulator.wakaf');

/*
|--------------------------------------------------------------------------
| TEST ERROR
|--------------------------------------------------------------------------
*/
Route::get('/test-error/{code}', fn ($code) => abort($code));
