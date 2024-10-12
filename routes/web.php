<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Http\Controllers\AuthController;

// Route untuk halaman utama
Route::get('/', function () {
    return view('welcome');
});

// Route untuk halaman index
Route::get('/index', [Controller::class, 'index']);

// Route untuk halaman home
Route::get('/home', [Controller::class, 'home']);
Route::get('/home/dashboard', [Controller::class, 'dashboard'])->name('home.dashboard');

// Route untuk manajemen kelas
Route::get('/home/kelas', [Controller::class, 'kelas'])->name('home.kelas');
Route::post('/home/aksi_tambah_kelas', [Controller::class, 'aksi_tambah_kelas'])->name('home.aksi_tambah_kelas');
Route::post('/home/aksi_edit_kelas', [Controller::class, 'aksi_edit_kelas'])->name('home.aksi_edit_kelas');
Route::post('/home/aksi_unedit_kelas', [Controller::class, 'aksi_unedit_kelas'])->name('home.aksi_unedit_kelas');
Route::get('/home/hapuskelas/{id}', [Controller::class, 'hapusKelas'])->name('home.hapuskelas');


Route::get('/home/user', [Controller::class, 'user'])->name('home.user');
Route::get('/home/hapususer/{id}', [Controller::class, 'hapususer'])->name('home.hapususer');
Route::get('/home/aksi_reset/{id}', [Controller::class, 'aksi_reset'])->name('home.aksi_reset');
Route::post('/home/aksi_tambah_user', [Controller::class, 'aksi_tambah_user'])->name('home.aksi_tambah_user');
Route::post('/home/aksi_edit_user', [Controller::class, 'aksi_edit_user'])->name('home.aksi_edit_user');
Route::post('/home/aksi_unedit_user', [Controller::class, 'aksi_unedit_user'])->name('home.aksi_unedit_user');


Route::get('/home/tugas', [Controller::class, 'tugas'])->name('home.tugas');
Route::post('/home/aksi_tambah_tugas', [Controller::class, 'aksi_tambah_tugas'])->name('home.aksi_tambah_tugas');
Route::post('/home/aksi_edit_tugas', [Controller::class, 'aksi_edit_tugas'])->name('home.aksi_edit_tugas');
Route::post('/home/aksi_unedit_tugas', [Controller::class, 'aksi_unedit_tugas'])->name('home.aksi_unedit_tugas');
Route::get('/home/hapustugas/{id}', [Controller::class, 'hapustugas'])->name('home.hapustugas');


Route::get('/home/profile/{id}', [Controller::class, 'profile'])->name('home.profile');
Route::post('/home/aksieprofile', [Controller::class, 'aksieprofile'])->name('home.aksieprofile');
Route::post('/home/aksi_changepass', [Controller::class, 'aksi_changepass'])->name('home.aksi_changepass');


Route::get('/home/nilai', [Controller::class, 'nilai'])->name('home.nilai');
Route::get('/home/getUsersByClass/{id_kelas}', [Controller::class, 'getUsersByClass'])->name('home.getUsersByClass');
Route::post('/home/savenilai', [Controller::class, 'savenilai'])->name('home.savenilai');
// Route untuk login
Route::get('/home/login', [Controller::class, 'login'])->name('home.login');
Route::post('/home/aksilogin', [Controller::class, 'aksilogin'])->name('home.aksilogin');
Route::get('/home/logout', [Controller::class, 'logout'])->name('home.logout');

Route::get('/home/setting', [Controller::class, 'setting'])->name('home.setting');
Route::post('/home/aksisetting', [Controller::class, 'aksisetting'])->name('home.aksisetting');

Route::get('/home/log', [Controller::class, 'log'])->name('home.log');

Route::get('/home/restore_user', [Controller::class, 'restore_user'])->name('home.restore_user');
Route::get('/home/aksi_restore_user/{id}', [Controller::class, 'aksi_restore_user'])->name('home.aksi_restore_user');
Route::get('/home/restore_kelas', [Controller::class, 'restore_kelas'])->name('home.restore_kelas');
Route::get('/home/aksi_restore_kelas/{id}', [Controller::class, 'aksi_restore_kelas'])->name('home.aksi_restore_kelas');
Route::get('/home/restore_tugas', [Controller::class, 'restore_tugas'])->name('home.restore_tugas');
Route::get('/home/aksi_restore_tugas/{id}', [Controller::class, 'aksi_restore_tugas'])->name('home.aksi_restore_tugas');

Route::get('/home/nilaisiswa', [Controller::class, 'nilaisiswa'])->name('home.nilaisiswa');
Route::get('/home/word', [Controller::class, 'word'])->name('home.word');
Route::get('/home/pdf', [Controller::class, 'pdf'])->name('home.pdf');
Route::get('/home/excel', [Controller::class, 'excel'])->name('home.excel');

// Route untuk CAPTCHA
Route::get('/captcha', [Controller::class, 'generateCaptcha'])->name('captcha.generate');

// Route tambahan jika diperlukan
Route::get('/kelas', [Controller::class, 'kelas']);
