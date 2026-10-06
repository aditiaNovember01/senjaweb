<?php

use App\Http\Controllers\BerandaController;
use App\Http\Controllers\GaleriController;
use App\Http\Controllers\KeanggotaanController;
use App\Http\Controllers\KegiatanPublikController;
use App\Http\Controllers\PendaftaranPublikController;
use App\Http\Controllers\ProgramKerjaPublikController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\PrintPendaftaranController;
use Illuminate\Support\Facades\Route;

// Beranda
Route::get('/', [BerandaController::class, 'index'])->name('beranda');

// Profil & Visi
Route::get('/profil', [ProfilController::class, 'index'])->name('profil');

// Galeri
Route::get('/galeri', [GaleriController::class, 'index'])->name('galeri.index');

// Keanggotaan
Route::get('/keanggotaan', [KeanggotaanController::class, 'index'])->name('keanggotaan.index');
Route::get('/keanggotaan/divisi/{divisi}', [KeanggotaanController::class, 'divisi'])->name('keanggotaan.divisi');

// Kegiatan publik
Route::get('/kegiatan', [KegiatanPublikController::class, 'index'])->name('kegiatan.index');
Route::get('/kegiatan/{kegiatan}', [KegiatanPublikController::class, 'show'])->name('kegiatan.show');

// Program Kerja publik
Route::get('/program-kerja', [ProgramKerjaPublikController::class, 'index'])->name('proker.index');
Route::get('/program-kerja/{programKerja}', [ProgramKerjaPublikController::class, 'show'])->name('proker.show');

// Pendaftaran anggota
Route::get('/daftar', [PendaftaranPublikController::class, 'create'])->name('pendaftaran.create');
Route::post('/daftar', [PendaftaranPublikController::class, 'store'])->name('pendaftaran.store');
Route::get('/daftar/sukses', [PendaftaranPublikController::class, 'sukses'])->name('pendaftaran.sukses');

// Print bukti pendaftaran — hanya untuk admin yang sudah login
Route::middleware(['auth'])->group(function () {
    Route::get('/admin-print/pendaftaran/{id}', [PrintPendaftaranController::class, 'cetak'])
        ->name('print.pendaftaran');
});
