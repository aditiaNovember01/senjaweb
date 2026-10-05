@extends('layouts.app')

@section('title', $kegiatan->nama)

@section('content')

{{-- Header --}}
<section class="bg-[#f0f2f8] py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <a href="{{ route('kegiatan.index') }}" class="btn-ghost text-sm mb-6 inline-flex">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Kegiatan
        </a>

        <div class="flex flex-wrap items-center gap-3 mb-4">
            <span class="text-xs font-semibold px-3 py-1 rounded-full
                {{ $kegiatan->status === 'Sedang Berlangsung' ? 'bg-green-100 text-green-600' :
                   ($kegiatan->status === 'Akan Datang' ? 'bg-primary-100 text-primary-600' : 'bg-gray-100 text-gray-500') }}">
                {{ $kegiatan->status }}
            </span>
            <span class="text-xs text-neutral-400">{{ $kegiatan->divisi->nama }}</span>
        </div>

        <h1 class="font-headline font-bold text-3xl sm:text-4xl text-secondary-500 max-w-3xl">
            {{ $kegiatan->nama }}
        </h1>
    </div>
</section>

{{-- Content --}}
<section class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

            {{-- Main --}}
            <div class="lg:col-span-2">
                {{-- Poster --}}
                @if($kegiatan->gambar_poster)
                <div class="rounded-2xl overflow-hidden mb-8 aspect-video">
                    <img src="{{ asset('storage/'.$kegiatan->gambar_poster) }}"
                         alt="{{ $kegiatan->nama }}"
                         class="w-full h-full object-cover">
                </div>
                @endif

                {{-- Deskripsi --}}
                <div class="prose prose-neutral max-w-none">
                    <h2 class="font-headline font-bold text-xl text-secondary-500 mb-4">Tentang Kegiatan</h2>
                    <p class="text-neutral-500 leading-relaxed whitespace-pre-line">{{ $kegiatan->deskripsi }}</p>
                </div>

                {{-- Galeri --}}
                @if($kegiatan->galeriFotos->isNotEmpty())
                <div class="mt-12">
                    <h2 class="font-headline font-bold text-xl text-secondary-500 mb-6">Galeri Foto</h2>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                        @foreach($kegiatan->galeriFotos as $foto)
                        <div class="aspect-square rounded-xl overflow-hidden bg-gray-100 cursor-pointer hover:opacity-90 transition-opacity"
                             onclick="openLightbox('{{ asset('storage/'.$foto->path_foto) }}')">
                            <img src="{{ asset('storage/'.$foto->path_foto) }}"
                                 alt="Foto kegiatan"
                                 class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                        </div>
                        @endforeach
                    </div>
                </div>
                @else
                <div class="mt-10 py-8 bg-[#f0f2f8] rounded-2xl text-center text-neutral-400">
                    <p class="text-3xl mb-2">📷</p>
                    <p class="text-sm">Belum ada foto galeri untuk kegiatan ini.</p>
                </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="lg:col-span-1">
                <div class="bg-[#f0f2f8] rounded-2xl p-6 sticky top-24">
                    <h3 class="font-headline font-semibold text-secondary-500 mb-5">Detail Kegiatan</h3>

                    <div class="space-y-4 text-sm">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg bg-primary-100 flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-4 h-4 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-neutral-400 mb-0.5">Mulai</p>
                                <p class="font-medium text-secondary-500">{{ $kegiatan->tanggal_mulai->format('d M Y, H:i') }} WIB</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg bg-primary-100 flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-4 h-4 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-neutral-400 mb-0.5">Selesai</p>
                                <p class="font-medium text-secondary-500">{{ $kegiatan->tanggal_selesai->format('d M Y, H:i') }} WIB</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg bg-primary-100 flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-4 h-4 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-neutral-400 mb-0.5">Lokasi</p>
                                <p class="font-medium text-secondary-500">{{ $kegiatan->lokasi }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-lg bg-primary-100 flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-4 h-4 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs text-neutral-400 mb-0.5">Penyelenggara</p>
                                <p class="font-medium text-secondary-500">Divisi {{ $kegiatan->divisi->nama }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 pt-5 border-t border-gray-200">
                        <a href="{{ route('pendaftaran.create') }}" class="btn-primary w-full justify-center text-sm">
                            Daftar Jadi Anggota
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Lightbox --}}
<div id="lightbox" class="fixed inset-0 bg-black/90 z-50 hidden items-center justify-center p-4"
     onclick="closeLightbox()">
    <img id="lightbox-img" src="" alt="" class="max-w-full max-h-full object-contain rounded-xl">
    <button onclick="closeLightbox()" class="absolute top-4 right-4 text-white hover:text-primary-400">
        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>
</div>

<script>
function openLightbox(src) {
    document.getElementById('lightbox-img').src = src;
    document.getElementById('lightbox').classList.remove('hidden');
    document.getElementById('lightbox').classList.add('flex');
}
function closeLightbox() {
    document.getElementById('lightbox').classList.add('hidden');
    document.getElementById('lightbox').classList.remove('flex');
}
document.addEventListener('keydown', (e) => { if(e.key === 'Escape') closeLightbox(); });
</script>

@endsection
