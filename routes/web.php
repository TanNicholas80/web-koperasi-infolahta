<?php

use App\Http\Controllers\BukuBesarKasKeluarController;
use App\Http\Controllers\BukuBesarKasKeluarUsipaController;
use App\Http\Controllers\BukuBesarKasMasukController;
use App\Http\Controllers\BukuBesarKasMasukUsipaController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InfoUserController;
use App\Http\Controllers\KasIndukController;
use App\Http\Controllers\KasInduksController;
use App\Http\Controllers\KasKeluarController;
use App\Http\Controllers\KasKeluarsController;
use App\Http\Controllers\KasKeluarUsipaController;
use App\Http\Controllers\KasMasukController;
use App\Http\Controllers\KasMasuksController;
use App\Http\Controllers\KasMasukUsipaController;
use App\Http\Controllers\KasUsipaController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ResetController;
use App\Http\Controllers\SaldoController;
use App\Http\Controllers\SessionsController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\DataBarangController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::group(['middleware' => 'auth'], function () {

	Route::get('/', [HomeController::class, 'home']);

	Route::get('profile', function () {
		return view('profile');
	})->name('profile');

	Route::get('user-management', function () {
		return view('laravel-examples/user-management');
	})->name('user-management');

	Route::get('/kas-masuk', [KasMasuksController::class, 'index'])->name('kas-masuk');
	Route::get('/kas-keluar', [KasKeluarsController::class, 'index'])->name('kas-keluar');

	Route::get('/kas-masuk/download/excel', [KasMasuksController::class, 'exportKasMasuk'])->name('kas-masuk.export');
	Route::get('/kas-keluar/download/excel', [KasKeluarsController::class, 'exportKasKeluar'])->name('kas-keluar.export');

	Route::get('/kas-masuk-usipa', [KasMasukUsipaController::class, 'index'])->name('kas-masuk-usipa');
	Route::get('/kas-keluar-usipa', [KasKeluarUsipaController::class, 'index'])->name('kas-keluar-usipa');

	Route::get('/kas-masuk-usipa/download/excel', [KasMasukUsipaController::class, 'exportKasMasukUsipa'])->name('kas-masuk-usipa.export');
	Route::get('/kas-keluar-usipa/download/excel', [KasKeluarUsipaController::class, 'exportKasKeluarUsipa'])->name('kas-keluar-usipa.export');

	Route::get('/buku-masuk', [BukuBesarKasMasukController::class, 'index'])->name('buku-masuk');
	Route::get('/buku-keluar', [BukuBesarKasKeluarController::class, 'index'])->name('buku-keluar');

	Route::get('/buku-masuk-usipa', [BukuBesarKasMasukUsipaController::class, 'index'])->name('buku-masuk-usipa');
	Route::get('/buku-keluar-usipa', [BukuBesarKasKeluarUsipaController::class, 'index'])->name('buku-keluar-usipa');

	Route::get('/buku-masuk/download/excel', [BukuBesarKasMasukController::class, 'exportBukuBesarKasMasuk'])->name('buku-masuk.export');
	Route::get('/buku-keluar/download/excel', [BukuBesarKasKeluarController::class, 'exportBukuBesarKasKeluar'])->name('buku-keluar.export');

	Route::get('/buku-masuk-usipa/download/excel', [BukuBesarKasMasukUsipaController::class, 'exportBukuBesarKasMasukUsipa'])->name('buku-masuk-usipa.export');
	Route::get('/buku-keluar-usipa/download/excel', [BukuBesarKasKeluarUsipaController::class, 'exportBukuBesarKasKeluarUsipa'])->name('buku-keluar-usipa.export');

	Route::resource('kasInduk', KasInduksController::class);
	Route::resource('kasUsipa', KasUsipaController::class);

	Route::post('/saldo/create', [SaldoController::class, 'create'])->name('saldo.create');
	Route::post('/saldo-usipa/create', [SaldoController::class, 'create_usipa'])->name('saldo-usipa.create');

	Route::get('/logout', [SessionsController::class, 'destroy']);
	Route::get('/user-profile', [InfoUserController::class, 'create']);
	Route::post('/user-profile', [InfoUserController::class, 'store']);
	Route::get('/login', function () {
		return view('user-management');
	})->name('sign-up');
});



Route::group(['middleware' => 'guest'], function () {
	Route::get('/register', [RegisterController::class, 'create']);
	Route::post('/register', [RegisterController::class, 'store']);
	Route::get('/login', [SessionsController::class, 'create']);
	Route::post('/session', [SessionsController::class, 'store']);
	Route::get('/login/forgot-password', [ResetController::class, 'create']);
	Route::post('/forgot-password', [ResetController::class, 'sendEmail']);
	Route::get('/reset-password/{token}', [ResetController::class, 'resetPass'])->name('password.reset');
	Route::post('/reset-password', [ChangePasswordController::class, 'changePassword'])->name('password.update');
});




Route::resource('data_barang', DataBarangController::class);


Route::resource('transaksi', TransaksiController::class);



Route::get('/login', function () {
	return view('session/login-session');
})->name('login');
