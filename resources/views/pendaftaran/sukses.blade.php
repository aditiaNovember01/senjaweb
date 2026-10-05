@extends('layouts.app')

@section('title', 'Pendaftaran Berhasil')

@section('content')

<section class="min-h-[70vh] flex items-center bg-[#f0f2f8]">
    <div class="max-w-2xl mx-auto px-4 text-center py-20">
        <div class="w-20 h-20 rounded-full bg-green-100 flex items-center justify-center mx-auto mb-8">
            <svg class="w-10 h-10 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
            </svg>
        </div>

        <h1 class="font-headline font-bold text-3xl sm:text-4xl text-secondary-500 mb-4">
            Pendaftaran Berhasil Dikirim!
        </h1>
        <p class="text-neutral-500 text-lg mb-4">
            Terima kasih telah mendaftarkan diri di UKM SENJA. Data pendaftaranmu sudah kami terima.
        </p>
        <p class="text-neutral-400 text-sm mb-10">
            Pengurus SENJA akan segera memverifikasi data pendaftaranmu dalam 1–3 hari kerja. Pantau email atau nomor telepon yang kamu daftarkan untuk informasi selanjutnya.
        </p>

        <div class="flex flex-wrap items-center justify-center gap-4">
            <a href="{{ route('beranda') }}" class="btn-primary px-8 py-4">
                Kembali ke Beranda
            </a>
            <a href="{{ route('kegiatan.index') }}" class="btn-outline px-8 py-4">
                Lihat Kegiatan
            </a>
        </div>
    </div>
</section>

@endsection
