@extends('layouts.app')

@section('title', $programKerja->nama)

@section('content')

<section class="bg-[#f0f2f8] py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <a href="{{ route('proker.index') }}" class="btn-ghost text-sm mb-6 inline-flex">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Program Kerja
        </a>

        <div class="flex flex-wrap items-center gap-3 mb-4">
            <span class="text-xs font-semibold px-3 py-1 rounded-full
                {{ $programKerja->status === 'Sedang Berjalan' ? 'bg-primary-100 text-primary-600' : 'bg-blue-100 text-blue-600' }}">
                {{ $programKerja->status }}
            </span>
            <span class="text-xs text-neutral-400">{{ $programKerja->divisi->nama }}</span>
            <span class="text-xs text-neutral-400">Periode {{ $programKerja->periode->nama }}</span>
        </div>

        <h1 class="font-headline font-bold text-3xl sm:text-4xl text-secondary-500 max-w-3xl">
            {{ $programKerja->nama }}
        </h1>
    </div>
</section>

<section class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

            <div class="lg:col-span-2">
                <h2 class="font-headline font-bold text-xl text-secondary-500 mb-4">Deskripsi Program Kerja</h2>
                <p class="text-neutral-500 leading-relaxed whitespace-pre-line">{{ $programKerja->deskripsi }}</p>
            </div>

            <div class="lg:col-span-1">
                <div class="bg-[#f0f2f8] rounded-2xl p-6 sticky top-24 space-y-4 text-sm">
                    <h3 class="font-headline font-semibold text-secondary-500 mb-2">Detail Program Kerja</h3>

                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-primary-100 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-neutral-400">Estimasi Mulai</p>
                            <p class="font-medium text-secondary-500">{{ $programKerja->tanggal_mulai_estimasi->format('d M Y') }}</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-primary-100 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-neutral-400">Estimasi Selesai</p>
                            <p class="font-medium text-secondary-500">{{ $programKerja->tanggal_selesai_estimasi->format('d M Y') }}</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-primary-100 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-neutral-400">Divisi Penanggung Jawab</p>
                            <p class="font-medium text-secondary-500">{{ $programKerja->divisi->nama }}</p>
                        </div>
                    </div>

                    @if($programKerja->target_peserta)
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-primary-100 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-neutral-400">Target Peserta</p>
                            <p class="font-medium text-secondary-500">{{ number_format($programKerja->target_peserta) }} orang</p>
                        </div>
                    </div>
                    @endif

                    @if($programKerja->estimasi_anggaran)
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-tertiary-100 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-tertiary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs text-neutral-400">Estimasi Anggaran</p>
                            <p class="font-medium text-secondary-500">Rp {{ number_format($programKerja->estimasi_anggaran, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
