@extends('layouts.app')

@section('title', 'Profil & Visi')

@section('content')

{{-- Page Header --}}
<section class="bg-[#f0f2f8] py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <p class="section-label">Profil & Fondasi Organisasi</p>
        <h1 class="font-headline font-bold text-4xl sm:text-5xl text-secondary-500 mt-1">
            Mengenal UKM SENJA
        </h1>
        <p class="text-neutral-500 mt-4 max-w-2xl text-lg">
            Berdiri sebagai ruang independen mahasiswa untuk berekspresi dan berkolaborasi dalam bidang seni dan kreativitas.
        </p>
    </div>
</section>

{{-- Visi & Misi --}}
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
            <div class="bg-[#f0f2f8] rounded-2xl p-8">
                <div class="w-14 h-14 rounded-2xl bg-primary-100 flex items-center justify-center mb-6">
                    <svg class="w-7 h-7 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </div>
                <h2 class="font-headline font-bold text-2xl text-secondary-500 mb-4">Visi</h2>
                <p class="text-neutral-500 leading-relaxed">
                    {{ $settings['org_vision'] ?? 'Menjadi wadah seni dan kreativitas mahasiswa yang unggul, inovatif, dan berdampak positif bagi komunitas kampus serta masyarakat luas.' }}
                </p>
            </div>

            <div class="bg-[#f0f2f8] rounded-2xl p-8">
                <div class="w-14 h-14 rounded-2xl bg-tertiary-100 flex items-center justify-center mb-6">
                    <svg class="w-7 h-7 text-tertiary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </div>
                <h2 class="font-headline font-bold text-2xl text-secondary-500 mb-4">Misi</h2>
                <ul class="space-y-3 text-neutral-500">
                    @foreach(array_filter(explode("\n", $settings['org_mission'] ?? '')) as $misi)
                    <li class="flex items-start gap-3">
                        <span class="mt-1 w-5 h-5 rounded-full bg-primary-100 text-primary-500 flex items-center justify-center shrink-0 text-xs font-bold">→</span>
                        {{ trim(preg_replace('/^\d+\.\s*/', '', $misi)) }}
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</section>

{{-- Divisi --}}
<section class="py-16 bg-[#f0f2f8]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <p class="section-label justify-center">Struktur Divisi</p>
            <h2 class="font-headline font-bold text-3xl text-secondary-500">Daftar Divisi UKM SENJA</h2>
            <p class="text-neutral-500 mt-2">Rangkai dan temukan divisimu di salah satu bidang aktif berikut.</p>
        </div>

        @php $icons = ['🎨','🎵','🎭','✏️','📚','🎬','📷','🎤']; @endphp

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @forelse($divisis as $i => $divisi)
            <div class="bg-white rounded-2xl p-6 border border-transparent hover:border-primary-100 hover:shadow-md transition-all duration-200">
                <div class="flex items-start gap-4 mb-4">
                    <div class="w-12 h-12 rounded-xl bg-primary-50 flex items-center justify-center shrink-0">
                        <img src="{{ asset('assets/logo/logosenja.png') }}" alt="SENJA" class="w-8 h-8 object-contain">
                    </div>
                    <div>
                        <h3 class="font-headline font-semibold text-secondary-500">Divisi {{ $divisi->nama }}</h3>
                        <span class="text-xs text-neutral-400">{{ $divisi->anggotas_count }} Anggota Aktif</span>
                    </div>
                </div>
                <p class="text-sm text-neutral-500 leading-relaxed">
                    {{ $divisi->deskripsi ?: 'Divisi '.$divisi->nama.' UKM SENJA — aktif dalam kegiatan kreatif dan program kerja tahunan.' }}
                </p>
            </div>
            @empty
            <div class="col-span-3 text-center py-12 text-neutral-400">Belum ada divisi terdaftar.</div>
            @endforelse
        </div>
    </div>
</section>

{{-- Pengurus --}}
@if($pengurus->isNotEmpty())
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <p class="section-label justify-center">Badan Pengurus</p>
            <h2 class="font-headline font-bold text-3xl text-secondary-500">
                Pengurus Harian
                @if($periodeAktif) {{ $periodeAktif->nama }} @endif
            </h2>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-5">
            @foreach($pengurus as $p)
            <div class="bg-[#f0f2f8] rounded-2xl p-5 text-center hover:shadow-md transition-shadow">
                {{-- Avatar --}}
                <div class="w-16 h-16 rounded-full mx-auto mb-3 overflow-hidden bg-primary-100 flex items-center justify-center">
                    @if($p->anggota->foto_profil)
                        <img src="{{ asset('storage/'.$p->anggota->foto_profil) }}"
                             alt="{{ $p->anggota->nama_lengkap }}"
                             class="w-full h-full object-cover">
                    @else
                        <svg class="w-8 h-8 text-primary-400" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/>
                        </svg>
                    @endif
                </div>

                <p class="font-headline font-semibold text-secondary-500 text-sm leading-tight">
                    {{ $p->anggota->nama_lengkap }}
                </p>
                <p class="text-xs text-neutral-400 mt-0.5">{{ $p->anggota->nim }}</p>

                <span class="inline-block mt-2 text-xs font-medium px-2.5 py-1 rounded-full
                    @if(in_array($p->jabatan, ['Ketua','Wakil Ketua'])) bg-primary-100 text-primary-600
                    @elseif($p->jabatan === 'Pembina') bg-secondary-100 text-secondary-600
                    @else bg-white text-neutral-500 border border-gray-200 @endif">
                    {{ $p->jabatan }}
                </span>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- CTA --}}
<section class="py-16 bg-[#f0f2f8]">
    <div class="max-w-3xl mx-auto px-4 text-center">
        <h2 class="font-headline font-bold text-3xl text-secondary-500 mb-4">Tertarik Bergabung?</h2>
        <p class="text-neutral-500 mb-8">Daftarkan dirimu sebagai anggota UKM SENJA dan mulai perjalanan kreatifmu bersama kami.</p>
        <a href="{{ route('pendaftaran.create') }}" class="btn-primary text-base px-8 py-4">
            Daftar Sekarang
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
            </svg>
        </a>
    </div>
</section>

@endsection
