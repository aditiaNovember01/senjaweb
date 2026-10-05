@extends('layouts.app')

@section('title', 'Beranda')

@section('content')

{{-- ========== HERO ========== --}}
<section class="bg-[#f0f2f8] min-h-[90vh] flex items-center relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 w-full">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">

            {{-- Left --}}
            <div>
                {{-- Badge periode --}}
                @if($periodeAktif)
                    <div class="inline-flex items-center gap-2 bg-white border border-primary-200 text-xs font-semibold text-secondary-500 px-4 py-1.5 rounded-full mb-8 shadow-sm">
                        <span class="w-2 h-2 rounded-full bg-primary-500 animate-pulse"></span>
                        PERIODE KEPENGURUSAN {{ strtoupper($periodeAktif->nama) }}
                        <span class="text-primary-500 font-bold">• SEDANG AKTIF</span>
                    </div>
                @endif

                <h1 class="font-headline font-extrabold text-4xl sm:text-5xl lg:text-6xl leading-tight text-secondary-500 mb-6">
                    {{ $settings['hero_title_line1'] ?? 'Mewadahi Inspirasi,' }}<br>
                    {{ $settings['hero_title_line2'] ?? 'Mengembangkan Potensi' }}<br>
                    <span class="text-primary-500">{{ $settings['hero_title_highlight'] ?? 'Tanpa Batas' }}</span>
                    {{ $settings['hero_title_suffix'] ?? 'di UKM SENJA' }}
                </h1>

                <p class="text-neutral-500 text-lg leading-relaxed mb-8 max-w-lg">
                    {{ $settings['org_description'] ?? 'Unit Kegiatan Mahasiswa SENJA adalah wadah kolaboratif mahasiswa dalam mengeksplorasi minat bakat di bidang kreativitas, kepemimpinan, dan karya inovatif mahasiswa.' }}
                </p>

                <div class="flex flex-wrap gap-4 mb-10">
                    <a href="{{ route('pendaftaran.create') }}" class="btn-primary">
                        Daftar Anggota Baru
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                    <a href="{{ route('kegiatan.index') }}" class="btn-outline">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Jelajahi Kegiatan & Proker
                    </a>
                </div>

                <div class="flex items-center gap-3">
                    {{-- Avatar stack --}}
                    <div class="flex -space-x-2">
                        @foreach(['SN','JK','AL','RD'] as $ini)
                            <div class="w-8 h-8 rounded-full bg-primary-{{ $loop->index % 2 == 0 ? '500' : '300' }} border-2 border-white flex items-center justify-center">
                                <span class="text-white text-xs font-bold">{{ $ini }}</span>
                            </div>
                        @endforeach
                    </div>
                    <span class="text-sm text-neutral-500">Terbuka untuk seluruh mahasiswa aktif lintas fakultas dan angkatan.</span>
                </div>
            </div>

            {{-- Right — Hero Image + Floating Card --}}
            <div class="relative hidden lg:block">
                <div class="rounded-2xl overflow-hidden shadow-2xl bg-secondary-200 aspect-[4/3]">
                    <div class="w-full h-full bg-gradient-to-br from-primary-100 to-secondary-100 flex items-center justify-center">
                        <div class="text-center">
                            <img src="{{ asset('assets/logo/logosenja.png') }}" alt="UKM SENJA" class="w-20 h-20 object-contain mx-auto mb-3 opacity-60">
                            <p class="text-secondary-400 font-medium">Kreativitas Kolaboratif</p>
                            <p class="text-secondary-300 text-sm">Bersama Mahasiswa Kampus</p>
                        </div>
                    </div>
                </div>

                {{-- Floating badge --}}
                <div class="absolute -bottom-4 -left-6 bg-white rounded-2xl shadow-xl px-5 py-3 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-primary-50 flex items-center justify-center">
                        <svg class="w-5 h-5 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-semibold text-secondary-500 text-sm">{{ $stats['divisi'] }} Klaster Divisi</p>
                        <p class="text-xs text-neutral-400">Eksplorasi Minat & Talenta</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ========== STATS ========== --}}
<section class="py-10 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <div class="bg-[#f0f2f8] rounded-2xl p-6 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-primary-100 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="font-headline font-bold text-3xl text-secondary-500">{{ $stats['anggota_aktif'] }}+</p>
                    <p class="text-sm text-neutral-500">Anggota Aktif Kampus</p>
                </div>
            </div>

            <div class="bg-[#f0f2f8] rounded-2xl p-6 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-tertiary-100 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6 text-tertiary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <div>
                    <p class="font-headline font-bold text-3xl text-secondary-500">{{ $stats['divisi'] }}</p>
                    <p class="text-sm text-neutral-500">Divisi Spesialisasi</p>
                </div>
            </div>

            <div class="bg-[#f0f2f8] rounded-2xl p-6 flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-green-100 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <p class="font-headline font-bold text-3xl text-secondary-500">{{ $stats['kegiatan'] }}+</p>
                    <p class="text-sm text-neutral-500">Kegiatan & Program Tahunan</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ========== SLIDER ANGGOTA ========== --}}
@if($anggotaSlider->isNotEmpty())
<section class="py-14 bg-white overflow-hidden" x-data="berandaAnggota()">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
        <div class="flex items-end justify-between">
            <div>
                <p class="section-label">Direktori Anggota</p>
                <h2 class="font-headline font-bold text-3xl sm:text-4xl text-secondary-500">
                    Anggota Aktif SENJA
                </h2>
                <p class="text-neutral-500 mt-2">Bergabung bersama {{ $stats['anggota_aktif'] }}+ anggota aktif dari berbagai divisi.</p>
            </div>
            <a href="{{ route('keanggotaan.index') }}" class="hidden sm:flex btn-ghost shrink-0">
                Lihat Semua →
            </a>
        </div>
    </div>

    {{-- Track 1 — left to right --}}
    <div class="relative">
        <div class="flex gap-4 animate-scroll-left" style="width: max-content">
            @foreach(array_merge($anggotaSlider->toArray(), $anggotaSlider->toArray()) as $anggota)
            @php
                $jabatanBeranda = collect($anggota['pengurus'] ?? [])->sortByDesc('id')->first()['jabatan'] ?? '';
                $biodata = json_encode([
                    'nama'      => $anggota['nama_lengkap'],
                    'nim'       => $anggota['nim'],
                    'email'     => $anggota['email'] ?? '',
                    'telepon'   => $anggota['nomor_telepon'] ?? '',
                    'divisi'    => $anggota['divisi']['nama'] ?? '',
                    'angkatan'  => $anggota['angkatan']['nama'] ?? '',
                    'status'    => $anggota['status'],
                    'jabatan'   => $jabatanBeranda,
                    'bergabung' => '',
                    'foto'      => !empty($anggota['foto_profil']) ? asset('storage/'.$anggota['foto_profil']) : '',
                ], JSON_HEX_QUOT | JSON_HEX_APOS);
            @endphp
            <div class="flex-shrink-0 w-44 bg-[#f0f2f8] rounded-2xl p-4 text-center cursor-pointer hover:shadow-md hover:scale-[1.03] transition-all duration-200"
                 @click="openModal({{ $biodata }})">
                <div class="w-14 h-14 rounded-full mx-auto mb-3 overflow-hidden bg-primary-100 flex items-center justify-center">
                    @if(!empty($anggota['foto_profil']))
                        <img src="{{ asset('storage/'.$anggota['foto_profil']) }}"
                             alt="{{ $anggota['nama_lengkap'] }}"
                             class="w-full h-full object-cover">
                    @else
                        <svg class="w-8 h-8 text-primary-400" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/>
                        </svg>
                    @endif
                </div>
                <p class="font-headline font-semibold text-secondary-500 text-sm leading-tight line-clamp-2">
                    {{ $anggota['nama_lengkap'] }}
                </p>
                <p class="text-xs text-neutral-400 mt-0.5">{{ $anggota['nim'] }}</p>
                @if(!empty($anggota['divisi']))
                    <span class="inline-block mt-2 text-xs px-2 py-0.5 rounded-full bg-white text-neutral-500 border border-gray-200">
                        {{ $anggota['divisi']['nama'] ?? '' }}
                    </span>
                @endif
            </div>
            @endforeach
        </div>
    </div>

    {{-- Track 2 — right to left --}}
    <div class="relative mt-4">
        <div class="flex gap-4 animate-scroll-right" style="width: max-content">
            @foreach(array_merge($anggotaSlider->reverse()->toArray(), $anggotaSlider->reverse()->toArray()) as $anggota)
            @php
                $jabatanBeranda2 = collect($anggota['pengurus'] ?? [])->sortByDesc('id')->first()['jabatan'] ?? '';
                $biodata2 = json_encode([
                    'nama'      => $anggota['nama_lengkap'],
                    'nim'       => $anggota['nim'],
                    'email'     => $anggota['email'] ?? '',
                    'telepon'   => $anggota['nomor_telepon'] ?? '',
                    'divisi'    => $anggota['divisi']['nama'] ?? '',
                    'angkatan'  => $anggota['angkatan']['nama'] ?? '',
                    'status'    => $anggota['status'],
                    'jabatan'   => $jabatanBeranda2,
                    'bergabung' => '',
                    'foto'      => !empty($anggota['foto_profil']) ? asset('storage/'.$anggota['foto_profil']) : '',
                ], JSON_HEX_QUOT | JSON_HEX_APOS);
            @endphp
            <div class="flex-shrink-0 w-44 bg-[#f0f2f8] rounded-2xl p-4 text-center cursor-pointer hover:shadow-md hover:scale-[1.03] transition-all duration-200"
                 @click="openModal({{ $biodata2 }})">
                <div class="w-14 h-14 rounded-full mx-auto mb-3 overflow-hidden bg-primary-100 flex items-center justify-center">
                    @if(!empty($anggota['foto_profil']))
                        <img src="{{ asset('storage/'.$anggota['foto_profil']) }}"
                             alt="{{ $anggota['nama_lengkap'] }}"
                             class="w-full h-full object-cover">
                    @else
                        <svg class="w-8 h-8 text-primary-400" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/>
                        </svg>
                    @endif
                </div>
                <p class="font-headline font-semibold text-secondary-500 text-sm leading-tight line-clamp-2">
                    {{ $anggota['nama_lengkap'] }}
                </p>
                <p class="text-xs text-neutral-400 mt-0.5">{{ $anggota['nim'] }}</p>
                @if(!empty($anggota['divisi']))
                    <span class="inline-block mt-2 text-xs px-2 py-0.5 rounded-full bg-white text-neutral-500 border border-gray-200">
                        {{ $anggota['divisi']['nama'] ?? '' }}
                    </span>
                @endif
            </div>
            @endforeach
        </div>
    </div>

    <div class="mt-6 text-center sm:hidden">
        <a href="{{ route('keanggotaan.index') }}" class="btn-ghost">Lihat Semua Anggota →</a>
    </div>

    {{-- ===== MODAL BIODATA BERANDA ===== --}}
    <div x-show="modalOpen"
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="closeModal()"></div>
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden z-10"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             @click.stop>
            <div class="bg-gradient-to-br from-primary-500 to-secondary-500 px-6 pt-8 pb-16 text-center relative">
                <button @click="closeModal()" class="absolute top-4 right-4 w-8 h-8 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center transition-colors text-white">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
                <p class="text-white/70 text-xs font-medium uppercase tracking-widest">Biodata Anggota</p>
                <p class="text-white font-headline font-bold text-lg mt-1" x-text="selected.nama"></p>
            </div>
            <div class="flex justify-center -mt-10 mb-4 relative z-10">
                <div class="w-20 h-20 rounded-full border-4 border-white shadow-lg overflow-hidden bg-primary-100 flex items-center justify-center">
                    <template x-if="selected.foto"><img :src="selected.foto" :alt="selected.nama" class="w-full h-full object-cover"></template>
                    <template x-if="!selected.foto"><svg class="w-10 h-10 text-primary-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/></svg></template>
                </div>
            </div>
            <div class="text-center mb-5 px-6">
                <span class="inline-block text-xs font-semibold px-3 py-1 rounded-full"
                      :class="{'bg-gray-100 text-gray-600': selected.status==='Pembina','bg-green-100 text-green-600': selected.status==='Anggota Aktif','bg-yellow-100 text-yellow-700': selected.status==='Anggota Pasif','bg-blue-100 text-blue-600': selected.status==='Anggota Kehormatan'}"
                      x-text="selected.status"></span>
            </div>
            <div class="px-6 pb-6 space-y-3">
                <template x-if="selected.jabatan">
                    <div class="flex items-center gap-3 bg-primary-50 rounded-xl px-4 py-3">
                        <svg class="w-4 h-4 text-primary-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                        <div><p class="text-xs text-primary-400">Jabatan</p><p class="text-sm font-semibold text-primary-600" x-text="selected.jabatan"></p></div>
                    </div>
                </template>
                <template x-if="selected.nim">
                    <div class="flex items-center gap-3 bg-[#f0f2f8] rounded-xl px-4 py-3">
                        <svg class="w-4 h-4 text-primary-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0"/></svg>
                        <div><p class="text-xs text-neutral-400">NIM</p><p class="text-sm font-semibold text-secondary-500" x-text="selected.nim"></p></div>
                    </div>
                </template>
                <template x-if="selected.divisi">
                    <div class="flex items-center gap-3 bg-[#f0f2f8] rounded-xl px-4 py-3">
                        <svg class="w-4 h-4 text-primary-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        <div><p class="text-xs text-neutral-400">Divisi</p><p class="text-sm font-semibold text-secondary-500" x-text="selected.divisi"></p></div>
                    </div>
                </template>
                <template x-if="selected.angkatan">
                    <div class="flex items-center gap-3 bg-[#f0f2f8] rounded-xl px-4 py-3">
                        <svg class="w-4 h-4 text-primary-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <div><p class="text-xs text-neutral-400">Angkatan</p><p class="text-sm font-semibold text-secondary-500" x-text="selected.angkatan"></p></div>
                    </div>
                </template>
                <template x-if="selected.email">
                    <div class="flex items-center gap-3 bg-[#f0f2f8] rounded-xl px-4 py-3">
                        <svg class="w-4 h-4 text-primary-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <div><p class="text-xs text-neutral-400">Email</p><p class="text-sm font-semibold text-secondary-500 break-all" x-text="selected.email"></p></div>
                    </div>
                </template>
                <template x-if="selected.telepon">
                    <div class="flex items-center gap-3 bg-[#f0f2f8] rounded-xl px-4 py-3">
                        <svg class="w-4 h-4 text-primary-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        <div><p class="text-xs text-neutral-400">No. Telepon</p><p class="text-sm font-semibold text-secondary-500" x-text="selected.telepon"></p></div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
function berandaAnggota() {
    return {
        modalOpen: false,
        selected: {},
        openModal(data) {
            this.selected = data;
            this.modalOpen = true;
            document.body.style.overflow = 'hidden';
        },
        closeModal() {
            this.modalOpen = false;
            document.body.style.overflow = '';
        },
        init() {
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') this.closeModal();
            });
        }
    }
}
</script>
@endpush
@endif

{{-- ========== PROFIL ORGANISASI ========== --}}
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl">
            <p class="section-label">Profil & Fondasi Organisasi</p>
            <h2 class="font-headline font-bold text-3xl sm:text-4xl text-secondary-500 mb-4">Mengenal UKM SENJA</h2>
            <p class="text-neutral-500 leading-relaxed mb-8">
                Berdiri sebagai ruang independen mahasiswa untuk berekspresi, UKM SENJA berkomitmen secara konsisten mendampingi mahasiswa dalam mengasah soft-skill kepemimpinan, hard-skill kreatif, serta kepekaan sosial terhadap tantangan zaman.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 mt-8">
            <div class="bg-[#f0f2f8] rounded-2xl p-6">
                <div class="w-12 h-12 rounded-xl bg-primary-100 flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </div>
                <h3 class="font-headline font-semibold text-secondary-500 mb-2">Visi</h3>
                <p class="text-sm text-neutral-500 leading-relaxed">
                    Menjadi wadah seni mahasiswa yang unggul, inovatif, dan berdampak positif bagi komunitas kampus dan masyarakat.
                </p>
            </div>

            <div class="bg-[#f0f2f8] rounded-2xl p-6">
                <div class="w-12 h-12 rounded-xl bg-tertiary-100 flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-tertiary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <h3 class="font-headline font-semibold text-secondary-500 mb-2">Misi</h3>
                <p class="text-sm text-neutral-500 leading-relaxed">
                    Mengembangkan potensi seni mahasiswa melalui program kerja terstruktur, kolaborasi antar divisi, dan partisipasi aktif dalam kegiatan kampus.
                </p>
            </div>

            <div class="bg-[#f0f2f8] rounded-2xl p-6">
                <div class="w-12 h-12 rounded-xl bg-green-100 flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <h3 class="font-headline font-semibold text-secondary-500 mb-2">Keanggotaan</h3>
                <p class="text-sm text-neutral-500 leading-relaxed">
                    Terbuka untuk seluruh mahasiswa aktif Universitas Jayanusa dari semua fakultas dan angkatan, tanpa biaya pendaftaran.
                </p>
            </div>
        </div>

        <div class="mt-6 text-center">
            <a href="{{ route('profil') }}" class="btn-ghost">
                Lihat lebih lanjut
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>
    </div>
</section>

{{-- ========== DIVISI ========== --}}
<section class="py-20 bg-[#f0f2f8]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <p class="section-label justify-center">Daftar Divisi UKM SENJA</p>
            <h2 class="font-headline font-bold text-3xl sm:text-4xl text-secondary-500">
                Temukan Divisimu
            </h2>
            <p class="text-neutral-500 mt-3 max-w-xl mx-auto">
                Bergabung dan eksplorasi bakat di divisi-divisi aktif yang ada di UKM SENJA.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @forelse($divisis as $i => $divisi)
                <div class="bg-white rounded-2xl p-6 card-hover border border-transparent hover:border-primary-100">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-primary-50 flex items-center justify-center">
                            <img src="{{ asset('assets/logo/logosenja.png') }}" alt="SENJA" class="w-8 h-8 object-contain">
                        </div>
                        <span class="text-xs bg-green-50 text-green-600 font-medium px-3 py-1 rounded-full">
                            {{ $divisi->anggotas_count }} Anggota
                        </span>
                    </div>
                    <h3 class="font-headline font-semibold text-secondary-500 text-lg mb-2">
                        Divisi {{ $divisi->nama }}
                    </h3>
                    <p class="text-sm text-neutral-500 leading-relaxed">
                        {{ $divisi->deskripsi ?: 'Divisi '.$divisi->nama.' UKM SENJA.' }}
                    </p>
                </div>
            @empty
                <div class="col-span-3 text-center py-12 text-neutral-400">
                    Belum ada divisi terdaftar.
                </div>
            @endforelse
        </div>
    </div>
</section>

{{-- ========== KEGIATAN ========== --}}
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between mb-10">
            <div>
                <p class="section-label">Kalender Kegiatan</p>
                <h2 class="font-headline font-bold text-3xl sm:text-4xl text-secondary-500">
                    Agenda & Kegiatan Mahasiswa
                </h2>
                <p class="text-neutral-500 mt-2">
                    Program yang sedang <span class="text-green-500 font-medium">Berlangsung</span> dan
                    <span class="text-primary-500 font-medium">Akan Datang</span> dalam waktu dekat.
                </p>
            </div>
            <a href="{{ route('kegiatan.index') }}" class="hidden sm:flex btn-ghost shrink-0">
                Lihat Semua Kegiatan →
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($kegiatanMendatang as $kegiatan)
                <a href="{{ route('kegiatan.show', $kegiatan) }}"
                   class="group bg-white border border-gray-100 rounded-2xl overflow-hidden card-hover shadow-sm">

                    {{-- Poster / placeholder --}}
                    <div class="aspect-video bg-gradient-to-br from-primary-50 to-secondary-50 relative overflow-hidden">
                        @if($kegiatan->gambar_poster)
                            <img src="{{ asset('storage/'.$kegiatan->gambar_poster) }}"
                                 alt="{{ $kegiatan->nama }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <img src="{{ asset('assets/logo/logosenja.png') }}" alt="UKM SENJA" class="w-14 h-14 object-contain opacity-40">
                            </div>
                        @endif

                        {{-- Status badge --}}
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
                            {{ $kegiatan->tanggal_mulai->translatedFormat('d F Y') }}
                        </div>

                        <h3 class="font-headline font-semibold text-secondary-500 text-base mb-1 line-clamp-2 group-hover:text-primary-500 transition-colors">
                            {{ $kegiatan->nama }}
                        </h3>

                        <p class="text-xs text-neutral-400 line-clamp-2 mb-3">
                            {{ $kegiatan->deskripsi }}
                        </p>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-1.5 text-xs text-neutral-400">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                </svg>
                                {{ Str::limit($kegiatan->lokasi, 25) }}
                            </div>
                            <span class="text-xs text-primary-500 font-medium">{{ $kegiatan->divisi->nama }}</span>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-3 py-16 text-center">
                    <p class="text-neutral-400">Belum ada kegiatan mendatang.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-8 text-center sm:hidden">
            <a href="{{ route('kegiatan.index') }}" class="btn-outline">Lihat Semua Kegiatan</a>
        </div>
    </div>
</section>

{{-- ========== FOTO PENGURUS BERANDA ========== --}}
@php
    $tagPengurus = $fotoBeranda?->tag_beranda ?: ($settings['tag_foto_pengurus'] ?? 'Pengurus UKM SENJA 2026-2027');
    $fotoUrl     = $fotoBeranda?->url_foto;
@endphp
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row items-center gap-12">

            {{-- Kiri: teks --}}
            <div class="lg:w-1/2">
                <div class="inline-flex items-center gap-2 bg-primary-50 border border-primary-200 text-xs font-semibold text-primary-500 px-4 py-1.5 rounded-full mb-4">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    {{ $tagPengurus }}
                </div>
                <p class="section-label">Dokumentasi</p>
                <h2 class="font-headline font-bold text-3xl sm:text-4xl text-secondary-500 mb-4">
                    Galeri UKM SENJA
                </h2>
                <p class="text-neutral-500 leading-relaxed mb-6">
                    UKM SENJA telah hadir sejak 2008, mendampingi ratusan mahasiswa dalam berkarya dan berkolaborasi.
                    Lihat seluruh dokumentasi perjalanan kami dari tahun ke tahun.
                </p>
                <a href="{{ route('galeri.index') }}"
                   class="btn-primary inline-flex items-center gap-2">
                    Lihat Semua Galeri
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>

            {{-- Kanan: foto pengurus --}}
            <div class="lg:w-1/2 flex justify-center">
                <div class="relative w-full max-w-lg">
                    @if($fotoUrl)
                        <div class="rounded-2xl overflow-hidden shadow-2xl bg-[#f0f2f8]">
                            <img src="{{ $fotoUrl }}"
                                 alt="{{ $tagPengurus }}"
                                 class="w-full h-auto block">
                        </div>
                    @else
                        <div class="rounded-2xl bg-[#f0f2f8] aspect-[4/3] flex flex-col items-center justify-center text-neutral-300 shadow-sm">
                            <svg class="w-16 h-16 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p class="text-sm text-neutral-400">Foto belum diatur</p>
                            <p class="text-xs text-neutral-300 mt-1">Admin → Galeri Website → centang "Tampilkan di Beranda"</p>
                        </div>
                    @endif

                    {{-- Tag badge --}}
                    <div class="mt-4 flex justify-center">
                        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 px-5 py-3 flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-primary-100 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <p class="font-semibold text-secondary-500 text-sm">{{ $tagPengurus }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ========== PROGRAM KERJA ========== --}}
@if($prokerAktif->isNotEmpty())
<section class="py-20 bg-[#f0f2f8]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between mb-10">
            <div>
                <p class="section-label">Program Kerja Unggulan</p>
                <h2 class="font-headline font-bold text-3xl sm:text-4xl text-secondary-500">
                    Program Kerja Aktif
                </h2>
                @if($periodeAktif)
                    <p class="text-neutral-500 mt-2">Periode {{ $periodeAktif->nama }}</p>
                @endif
            </div>
            <a href="{{ route('proker.index') }}" class="hidden sm:flex btn-ghost shrink-0">
                Lihat Semua Proker →
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($prokerAktif as $proker)
                <a href="{{ route('proker.show', $proker) }}"
                   class="group bg-white rounded-2xl p-6 card-hover border border-transparent hover:border-primary-100">
                    <div class="flex items-start justify-between mb-4">
                        <span class="text-xs font-medium px-3 py-1 rounded-full
                            {{ $proker->status === 'Sedang Berjalan' ? 'bg-primary-50 text-primary-500' : 'bg-blue-50 text-blue-500' }}">
                            {{ $proker->status }}
                        </span>
                        <span class="text-xs text-neutral-400">{{ $proker->divisi->nama }}</span>
                    </div>

                    <h3 class="font-headline font-semibold text-secondary-500 text-base mb-2 group-hover:text-primary-500 transition-colors line-clamp-2">
                        {{ $proker->nama }}
                    </h3>

                    <p class="text-sm text-neutral-500 line-clamp-2 mb-4">
                        {{ $proker->deskripsi }}
                    </p>

                    <div class="flex items-center gap-2 text-xs text-neutral-400">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        {{ $proker->tanggal_mulai_estimasi->format('d M') }} — {{ $proker->tanggal_selesai_estimasi->format('d M Y') }}
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@else
<section class="py-16 bg-[#f0f2f8]">
    <div class="max-w-7xl mx-auto px-4 text-center text-neutral-400">
        <p>Belum ada program kerja yang tersedia saat ini.</p>
    </div>
</section>
@endif

{{-- ========== CTA DAFTAR ========== --}}
<section class="py-20 bg-secondary-500">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <p class="section-label justify-center text-primary-400">Pendaftaran Anggota Baru</p>
        <h2 class="font-headline font-bold text-3xl sm:text-4xl text-white mb-4">
            Siap Bertumbuh dan Berkarya Bersama Kami?
        </h2>
        <p class="text-secondary-200 text-lg mb-10 max-w-xl mx-auto">
            Pendaftaran anggota baru UKM SENJA periode ganjil telah dibuka. Semua mahasiswa aktif berhak bergabung tanpa dipungut biaya pendaftaran. Mari eksplorasi semua hal!
        </p>

        <div class="flex flex-wrap items-center justify-center gap-4 mb-10">
            <a href="{{ route('pendaftaran.create') }}" class="btn-primary text-base px-8 py-4">
                Isi Formulir Pendaftaran Sekarang
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
            <a href="{{ route('kegiatan.index') }}" class="inline-flex items-center gap-2 text-white border border-white/30 hover:bg-white/10 font-semibold px-8 py-4 rounded-full transition">
                Tengok Kegiatannya
            </a>
        </div>

        <div class="flex flex-wrap justify-center gap-8 text-sm text-secondary-300">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                100% Bebas Biaya Pendaftaran
            </div>
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Sertifikasi & UKT-Based
            </div>
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Buka untuk lintas angkatan & fakultas
            </div>
        </div>
    </div>
</section>

@endsection
