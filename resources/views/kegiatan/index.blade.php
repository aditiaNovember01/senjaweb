@extends('layouts.app')

@section('title', 'Kegiatan')

@section('content')

{{-- Header --}}
<section class="bg-[#f0f2f8] py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <p class="section-label">Agenda & Kegiatan</p>
        <h1 class="font-headline font-bold text-4xl sm:text-5xl text-secondary-500 mt-1">Kegiatan Mahasiswa</h1>
        <p class="text-neutral-500 mt-4 max-w-xl text-lg">
            Program yang sedang berlangsung dan akan datang dalam waktu dekat.
        </p>
    </div>
</section>

{{-- Kegiatan Mendatang --}}
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="font-headline font-bold text-2xl text-secondary-500 mb-8">Akan Datang & Sedang Berlangsung</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($kegiatanMendatang as $kegiatan)
            <a href="{{ route('kegiatan.show', $kegiatan) }}"
               class="group bg-white border border-gray-100 rounded-2xl overflow-hidden hover:-translate-y-1 hover:shadow-lg transition-all duration-200 shadow-sm">
                <div class="aspect-video bg-gradient-to-br from-primary-50 to-secondary-50 relative overflow-hidden">
                    @if($kegiatan->gambar_poster)
                        <img src="{{ asset('storage/'.$kegiatan->gambar_poster) }}"
                             alt="{{ $kegiatan->nama }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-4xl">📅</div>
                    @endif
                    <span class="absolute top-3 left-3 text-xs font-semibold px-3 py-1 rounded-full
                        {{ $kegiatan->status === 'Sedang Berlangsung' ? 'bg-green-500 text-white' : 'bg-primary-500 text-white' }}">
                        {{ $kegiatan->status }}
                    </span>
                </div>
                <div class="p-5">
                    <div class="flex items-center gap-2 text-xs text-neutral-400 mb-2">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        {{ $kegiatan->tanggal_mulai->format('d M Y • H:i') }} WIB
                    </div>
                    <h3 class="font-headline font-semibold text-secondary-500 mb-1 line-clamp-2 group-hover:text-primary-500 transition-colors">
                        {{ $kegiatan->nama }}
                    </h3>
                    <p class="text-xs text-neutral-400 line-clamp-2 mb-3">{{ $kegiatan->deskripsi }}</p>
                    <div class="flex items-center justify-between text-xs text-neutral-400">
                        <span class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            </svg>
                            {{ Str::limit($kegiatan->lokasi, 25) }}
                        </span>
                        <span class="font-medium text-primary-500">{{ $kegiatan->divisi->nama }}</span>
                    </div>
                </div>
            </a>
            @empty
            <div class="col-span-3 py-16 text-center text-neutral-400">
                <p class="text-5xl mb-4">📭</p>
                <p>Belum ada kegiatan mendatang saat ini.</p>
            </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($kegiatanMendatang->hasPages())
        <div class="mt-10">{{ $kegiatanMendatang->links() }}</div>
        @endif
    </div>
</section>

{{-- Kegiatan Selesai --}}
@if($kegiatanSelesai->isNotEmpty())
<section class="py-16 bg-[#f0f2f8]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="font-headline font-bold text-2xl text-secondary-500 mb-8">Kegiatan Terdahulu</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($kegiatanSelesai as $kegiatan)
            <a href="{{ route('kegiatan.show', $kegiatan) }}"
               class="group bg-white rounded-2xl overflow-hidden hover:shadow-md transition-all duration-200">
                <div class="aspect-video bg-gray-100 relative overflow-hidden">
                    @if($kegiatan->gambar_poster)
                        <img src="{{ asset('storage/'.$kegiatan->gambar_poster) }}"
                             alt="{{ $kegiatan->nama }}"
                             class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-300">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-3xl opacity-40">📅</div>
                    @endif
                    <span class="absolute top-3 left-3 text-xs font-medium px-3 py-1 rounded-full bg-gray-500 text-white">Selesai</span>
                </div>
                <div class="p-4">
                    <p class="text-xs text-neutral-400 mb-1">{{ $kegiatan->tanggal_mulai->format('d M Y') }}</p>
                    <h3 class="font-headline font-semibold text-secondary-500 text-sm line-clamp-2 group-hover:text-primary-500 transition-colors">
                        {{ $kegiatan->nama }}
                    </h3>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection
