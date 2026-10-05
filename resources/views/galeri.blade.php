@extends('layouts.app')

@section('title', 'Galeri')
@section('meta_description', 'Galeri dokumentasi foto UKM SENJA — perjalanan kebersamaan mahasiswa sejak 2008.')

@section('content')

{{-- ========== HERO ========== --}}
<section class="bg-[#f0f2f8] py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="inline-flex items-center gap-2 bg-white border border-primary-200 text-xs font-semibold text-primary-500 px-4 py-1.5 rounded-full mb-6 shadow-sm">
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            Dokumentasi UKM SENJA
        </div>
        <h1 class="font-headline font-extrabold text-4xl sm:text-5xl text-secondary-500 mb-4">
            Galeri UKM SENJA
        </h1>
        <p class="text-neutral-500 text-lg max-w-2xl mx-auto">
            Perjalanan kebersamaan kami sejak 2008 — momen, kegiatan, dan kenangan bersama anggota UKM SENJA.
        </p>
    </div>
</section>

{{-- ========== GRID GALERI ========== --}}
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        @if($galeri->isNotEmpty())
            <div class="columns-2 sm:columns-3 lg:columns-4 gap-3 space-y-3">
                @foreach($galeri as $foto)
                    <div class="break-inside-avoid rounded-2xl overflow-hidden group relative cursor-pointer"
                         onclick="openLightbox('{{ $foto->url_foto }}', '{{ addslashes($foto->judul ?? '') }}', '{{ addslashes($foto->keterangan ?? '') }}')">
                        <img src="{{ $foto->url_foto }}"
                             alt="{{ $foto->judul ?? 'Foto UKM SENJA' }}"
                             class="w-full object-cover group-hover:scale-105 transition-transform duration-500 rounded-2xl">
                        {{-- Overlay --}}
                        <div class="absolute inset-0 bg-secondary-500/0 group-hover:bg-secondary-500/40 transition-all duration-300 rounded-2xl flex items-end p-3">
                            @if($foto->judul)
                                <span class="text-white text-xs font-medium opacity-0 group-hover:opacity-100 transition-opacity duration-300 bg-black/40 px-2 py-1 rounded-lg backdrop-blur-sm line-clamp-2">
                                    {{ $foto->judul }}
                                </span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="py-24 text-center">
                <div class="w-20 h-20 rounded-full bg-[#f0f2f8] flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-neutral-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <p class="text-neutral-400 font-medium">Belum ada foto di galeri.</p>
                <p class="text-neutral-300 text-sm mt-1">Foto akan tampil setelah ditambahkan oleh admin.</p>
            </div>
        @endif
    </div>
</section>

{{-- ========== LIGHTBOX ========== --}}
<div id="lightbox"
     class="fixed inset-0 bg-black/90 z-50 hidden items-center justify-center p-4"
     onclick="closeLightbox()">
    <div class="relative max-w-5xl w-full max-h-full" onclick="event.stopPropagation()">
        <button onclick="closeLightbox()"
                class="absolute -top-10 right-0 text-white/70 hover:text-white text-sm flex items-center gap-1.5 transition-colors">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
            Tutup
        </button>
        <img id="lightbox-img"
             src="" alt=""
             class="max-h-[85vh] max-w-full mx-auto rounded-2xl shadow-2xl object-contain block">
        <div class="mt-3 text-center">
            <p id="lightbox-title" class="text-white font-semibold text-sm"></p>
            <p id="lightbox-caption" class="text-white/60 text-xs mt-1"></p>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function openLightbox(src, title, caption) {
    document.getElementById('lightbox-img').src     = src;
    document.getElementById('lightbox-title').textContent   = title;
    document.getElementById('lightbox-caption').textContent = caption;
    const lb = document.getElementById('lightbox');
    lb.classList.remove('hidden');
    lb.classList.add('flex');
    document.body.style.overflow = 'hidden';
}
function closeLightbox() {
    const lb = document.getElementById('lightbox');
    lb.classList.add('hidden');
    lb.classList.remove('flex');
    document.body.style.overflow = '';
}
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeLightbox(); });
</script>
@endpush
