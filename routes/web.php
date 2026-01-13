<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AlokasiDonasiController;
use App\Http\Controllers\ContactDonasiOfflineController;
use App\Http\Controllers\BeritaDanKegiatanController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DonasiController;
use App\Http\Controllers\DonasiOfflineController;
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
Route::get('/tentangkami', [HomeController::class, 'tentangkami'])->name('tentangkami');
Route::get('/laporan', [HomeController::class, 'laporan'])->name('laporan.index');

Route::get('/kontak', [ContactController::class, 'index'])->name('kontak');
Route::post('/kontak', [ContactController::class, 'send'])->name('kontak.send');

/*
|--------------------------------------------------------------------------
| PROFILE USER
|--------------------------------------------------------------------------
*/

Route::prefix('profile')->group(function () {
    Route::get('/', [HomeController::class, 'profile'])->name('profile');
    Route::get('/edit', [HomeController::class, 'editProfile'])->name('profile.edit');
    Route::put('/', [HomeController::class, 'updateProfile'])->name('profile.update');
});

/*
|--------------------------------------------------------------------------
| PROGRAM DONASI (FRONTEND)
|--------------------------------------------------------------------------
*/
Route::get('/program', [ProgramController::class, 'programIndex'])
    ->name('pages.program.index');

Route::post('/program', [ProgramController::class, 'byKategoriProgram'])
    ->name('pages.program');
Route::prefix('program')->group(function () {
    Route::get('{kategori}', [ProgramController::class, 'byKategori'])->name('program.index');
    Route::get('{kategori}/detail/{slug}', [ProgramController::class, 'detail'])->name('program.detail');

    Route::get('{kategori}/detail/{slug}/donate-now', [DonasiController::class, 'donateNow'])
        ->name('donasi.form');

    Route::post('{kategori}/detail/{slug}/donate-now', [DonasiController::class, 'donateNowPost'])
        ->name('donasi.form.post');

    Route::post('{kategori}/detail/{slug}/donate-now/store', [DonasiController::class, 'store'])
        ->name('donasi.store');
});

/*
|--------------------------------------------------------------------------
| KALKULATOR
|--------------------------------------------------------------------------
*/

Route::get('/kalkulatorzakat', [HomeController::class, 'kalkulator'])->name('kalkulator.zakat');

/*
|--------------------------------------------------------------------------
| PAYMENT
|--------------------------------------------------------------------------
*/

Route::get('/payment/{donasi}/pay', [TransactionController::class, 'pay'])
    ->name('transaction.pay');

Route::post('/midtrans/callback', [TransactionController::class, 'callback']);

Route::get('/payment/success/{reference}', [HomeController::class, 'paymentSuccess'])
    ->name('payment.success');

Route::get('/payment/pending/{reference}', [HomeController::class, 'paymentPending'])
    ->name('payment.pending');

Route::get('/payment/{transaction}/back', [TransactionController::class, 'back'])
    ->name('payment.back');

Route::get('/payment/failed/{reference}', [HomeController::class, 'paymentFailed'])
    ->name('payment.failed');
/*
|--------------------------------------------------------------------------
| BERITA & KEGIATAN
|--------------------------------------------------------------------------
*/

Route::get('/berita', [BeritaDanKegiatanController::class, 'showPublic'])->name('berita');
Route::get('/beritadankegiatan', [BeritaDanKegiatanController::class, 'showPublic'])
    ->name('beritadankegiatan.public');

Route::get('/berita/{judul}', [BeritaDanKegiatanController::class, 'detail'])
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
    ->middleware(['auth', App\Http\Middleware\CheckRole::class . ':admin'])
    ->group(function () {

        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

        Route::post('/tinymce/upload', [TinyMceController::class, 'upload'])
            ->name('tinymce.upload');

        Route::resource('program', ProgramController::class)->except(['show']);
        Route::get('program/{program}/show', [ProgramController::class, 'show'])
            ->name('program.show');

        Route::resource('beritadankegiatan', BeritaDanKegiatanController::class);

        Route::get('/account', [AdminController::class, 'account'])->name('account');
        Route::post('/account', [AdminController::class, 'storeAccount'])->name('account.store');
        Route::put('/account/{id}', [AdminController::class, 'updateAccount'])->name('account.update');
        Route::delete('/account/{id}', [AdminController::class, 'destroyAccount'])->name('account.destroy');
        Route::patch('/account/{id}/role', [AdminController::class, 'updateRole'])
            ->name('account.updateRole');

        Route::get('/profile', [ProfiladminController::class, 'index'])->name('profile');
        Route::post('/profile/update', [ProfiladminController::class, 'update'])
            ->name('profile.update');

        Route::get('/activity-log', [ActivityLogController::class, 'index'])
            ->name('activitylog');

        Route::prefix('pengaturan')->name('pengaturan.')->group(function () {
            Route::get('/general', [KontakInformasiController::class, 'index'])->name('general');
            Route::put('/general', [KontakInformasiController::class, 'update'])->name('general.update');

            Route::get('/security', [SettingController::class, 'security'])->name('security');
            Route::post('/security', [SettingController::class, 'saveSecurity'])->name('security.save');

            Route::get('/midtrans', [SettingController::class, 'midtrans'])->name('midtrans');
            Route::post('/midtrans', [SettingController::class, 'saveMidtrans'])->name('midtrans.save');
        });
        Route::prefix('contact-donasi-offline')
            ->name('contactdonasioffline.')
            ->controller(ContactDonasiOfflineController::class)
            ->group(function () {

                Route::get('/', 'index')->name('index');
                Route::get('/tambah', 'create')->name('tambah');
                Route::post('/', 'store')->name('store');
                Route::get('/{contactdonasioffline}', 'show')->name('show');
                Route::get('/{contactdonasioffline}/edit', 'edit')->name('edit');
                Route::put('/{contactdonasioffline}', 'update')->name('update');
                Route::delete('/{contactdonasioffline}', 'destroy')->name('destroy');
            });
        Route::prefix('donasi-offline')
            ->name('donasioffline.')
            ->controller(DonasiOfflineController::class)
            ->group(function () {
                // INDEX
                Route::get('/', 'index')->name('index');
                // CREATE
                Route::get('/tambah', 'create')->name('tambah');
                Route::post('/', 'store')->name('store');
            });
    });

Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

/*
|--------------------------------------------------------------------------
| FINANCE
|--------------------------------------------------------------------------
*/

Route::prefix('finance')
    ->name('finance.')
    ->middleware(['auth', App\Http\Middleware\CheckRole::class . ':finance'])
    ->group(function () {

        Route::get('/dashboard', [FinanceController::class, 'index'])->name('dashboard');
        Route::get('/transaction/{kategori?}', [FinanceController::class, 'transaction'])
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
        Route::resource('alokasidonasi', AlokasiDonasiController::class)
            ->except(['create', 'edit']);

        Route::get('/laporangabungan/laporan', [PemasukkanController::class, 'laporanGabungan'])
            ->name('laporan.laporankeuangan');
        Route::get('/laporangabungan/laporan/pdf', [PemasukkanController::class, 'exportPdfGabungan'])
            ->name('laporan.laporankeuangan.pdf');
        Route::get('/laporangabungan/laporan/excel', [PemasukkanController::class, 'exportExcelGabungan'])
            ->name('laporan.laporankeuangan.excel');

        Route::get('/pemasukkan/laporan', [PemasukkanController::class, 'laporan'])
            ->name('laporan.pemasukkan');
        Route::get('/pemasukkan/laporan/pdf', [PemasukkanController::class, 'exportPdf'])
            ->name('laporan.pemasukkan.pdf');

        Route::get('/pengeluaran/laporan', [PengeluaranController::class, 'laporan'])
            ->name('laporan.pengeluaran');
        Route::get('/pengeluaran/laporan/pdf', [PengeluaranController::class, 'exportPdf'])
            ->name('laporan.pengeluaran.pdf');
    });
