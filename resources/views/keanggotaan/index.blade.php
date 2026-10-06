@extends('layouts.app')

@section('title', 'Keanggotaan')
@section('meta_description', 'Direktori anggota UKM SENJA — Pembina, Anggota Aktif, Anggota Pasif, dan Anggota Kehormatan.')

@section('content')

{{-- Header --}}
<section class="bg-[#f0f2f8] py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <p class="section-label">Direktori Anggota</p>
        <h1 class="font-headline font-bold text-4xl sm:text-5xl text-secondary-500 mt-1">
            Keanggotaan UKM SENJA
        </h1>
        <p class="text-neutral-500 mt-4 max-w-xl text-lg">
            Kenali para anggota aktif, pembina, dan anggota kehormatan UKM SENJA.
        </p>
    </div>
</section>

{{-- Stats --}}
<section class="py-10 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            @php
                $statusCards = [
                    ['label' => 'Pembina',            'count' => $stats['pembina']],
                    ['label' => 'Anggota Aktif',       'count' => $stats['anggota_aktif']],
                    ['label' => 'Anggota Pasif',       'count' => $stats['anggota_pasif']],
                    ['label' => 'Anggota Kehormatan',  'count' => $stats['anggota_kehormatan']],
                ];
            @endphp

            @foreach($statusCards as $card)
            <div class="bg-[#f0f2f8] rounded-2xl p-5 flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center shrink-0 shadow-sm">
                    <img src="{{ asset('assets/logo/logosenja.png') }}" alt="SENJA" class="w-7 h-7 object-contain">
                </div>
                <div>
                    <p class="font-headline font-bold text-2xl text-secondary-500">{{ $card['count'] }}</p>
                    <p class="text-xs text-neutral-500">{{ $card['label'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Filter + Daftar --}}
<section class="py-10 bg-white" x-data="keanggotaan()">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Filter by STATUS --}}
        <div class="flex flex-wrap items-center gap-3 mb-6">
            @php
                $statusFilters = [
                    ''                    => 'Semua',
                    'Pembina'             => 'Pembina',
                    'Anggota Aktif'       => 'Anggota Aktif',
                    'Anggota Pasif'       => 'Anggota Pasif',
                    'Anggota Kehormatan'  => 'Anggota Kehormatan',
                ];
            @endphp

            @foreach($statusFilters as $val => $label)
            <button @click="filterStatus = '{{ $val }}'"
                    :class="filterStatus === '{{ $val }}'
                        ? 'bg-primary-500 text-white shadow-sm'
                        : 'bg-[#f0f2f8] text-secondary-500 hover:bg-primary-50 hover:text-primary-500'"
                    class="px-4 py-2 rounded-full text-sm font-medium transition-all duration-150">
                {{ $label }}
                @if($val !== '')
                    <span class="ml-1 opacity-60 text-xs">({{ $stats[str_replace(' ', '_', strtolower($val))] ?? 0 }})</span>
                @endif
            </button>
            @endforeach
        </div>

        {{-- Search --}}
        <div class="relative mb-8 max-w-md">
            <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-neutral-400"
                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" x-model="search" placeholder="Cari nama atau NIM..."
                   class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200
                          focus:outline-none focus:ring-2 focus:ring-primary-300 focus:border-primary-500
                          text-sm text-secondary-500 placeholder-neutral-400 transition">
        </div>

        {{-- Grid Anggota — foto diperbesar --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-5">
            @forelse($semuaAnggota as $anggota)
            @php
                $jabatanAnggota = $anggota->pengurus->sortByDesc('id')->first()?->jabatan ?? '';
                $biodataAnggota = json_encode([
                    'nama'             => $anggota->nama_lengkap,
                    'nim'              => $anggota->nim,
                    'email'            => $anggota->email ?? '',
                    'telepon'          => $anggota->nomor_telepon ?? '',
                    'divisi'           => $anggota->divisi->nama ?? '',
                    'angkatan'         => $anggota->angkatan->nama ?? '',
                    'status'           => $anggota->status,
                    'jabatan'          => $jabatanAnggota,
                    'bergabung'        => $anggota->tanggal_bergabung ? $anggota->tanggal_bergabung->translatedFormat('d F Y') : '',
                    'foto'             => $anggota->foto_profil ? asset('storage/'.$anggota->foto_profil) : '',
                ], JSON_HEX_QUOT | JSON_HEX_APOS);
            @endphp
            <div class="anggota-card bg-white rounded-2xl overflow-hidden hover:shadow-lg hover:-translate-y-1 transition-all duration-200 cursor-pointer border border-gray-100 group"
                 x-show="shouldShow(
                     '{{ strtolower($anggota->nama_lengkap) }}',
                     '{{ strtolower($anggota->nim) }}',
                     '{{ $anggota->status }}'
                 )"
                 x-transition
                 @click="openModal({{ $biodataAnggota }})">

                {{-- Foto dengan overlay --}}
                <div class="w-full aspect-[3/4] overflow-hidden relative
                            @if($anggota->status === 'Pembina') bg-gray-200
                            @elseif($anggota->status === 'Anggota Kehormatan') bg-blue-100
                            @else bg-primary-50 @endif">
                    @if($anggota->foto_profil)
                        <img src="{{ asset('storage/'.$anggota->foto_profil) }}"
                             alt="{{ $anggota->nama_lengkap }}"
                             class="w-full h-full object-cover object-top group-hover:scale-105 transition-transform duration-300">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <svg class="w-20 h-20
                                @if($anggota->status === 'Pembina') text-gray-300
                                @elseif($anggota->status === 'Anggota Kehormatan') text-blue-200
                                @else text-primary-200 @endif"
                                 fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/>
                            </svg>
                        </div>
                    @endif

                    {{-- Gradient overlay nama --}}
                    <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/75 via-black/30 to-transparent pt-10 pb-3 px-3">
                        <p class="text-white font-semibold text-sm leading-tight line-clamp-2 drop-shadow">
                            {{ $anggota->nama_lengkap }}
                        </p>
                        @if($anggota->divisi)
                        <p class="text-white/70 text-xs mt-0.5">{{ $anggota->divisi->nama }}</p>
                        @endif
                    </div>

                    {{-- Status badge --}}
                    <div class="absolute top-2 right-2">
                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full shadow-sm
                            @if($anggota->status === 'Pembina') bg-gray-700 text-white
                            @elseif($anggota->status === 'Anggota Aktif') bg-green-500 text-white
                            @elseif($anggota->status === 'Anggota Pasif') bg-yellow-400 text-white
                            @elseif($anggota->status === 'Anggota Kehormatan') bg-blue-500 text-white
                            @endif">
                            {{ $anggota->status === 'Anggota Aktif' ? 'Aktif'
                                : ($anggota->status === 'Anggota Pasif' ? 'Pasif'
                                : ($anggota->status === 'Anggota Kehormatan' ? 'Kehormatan'
                                : $anggota->status)) }}
                        </span>
                    </div>
                </div>

                <div class="px-3 py-2.5">
                    <p class="text-xs text-neutral-400">{{ $anggota->nim }}</p>
                    @if($anggota->angkatan)
                    <p class="text-xs text-neutral-300 mt-0.5">{{ $anggota->angkatan->nama }}</p>
                    @endif
                </div>
            </div>
            @empty
            <div class="col-span-5 py-16 text-center text-neutral-400">
                <img src="{{ asset('assets/logo/logosenja.png') }}" alt="SENJA" class="w-12 h-12 object-contain mx-auto mb-3 opacity-30">
                <p>Belum ada anggota terdaftar.</p>
            </div>
            @endforelse
        </div>

        {{-- Empty state --}}
        <div x-show="checkEmpty()" x-cloak class="py-16 text-center text-neutral-400">
            <img src="{{ asset('assets/logo/logosenja.png') }}" alt="SENJA" class="w-12 h-12 object-contain mx-auto mb-3 opacity-30">
            <p class="text-sm">Tidak ada anggota yang sesuai filter.</p>
        </div>

    </div>

    {{-- ===== MODAL BIODATA ===== --}}
    <div x-show="modalOpen"
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">

        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="closeModal()"></div>

        {{-- Modal Box --}}
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden z-10"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-4"
             @click.stop>

            {{-- Header banner --}}
            <div class="bg-gradient-to-br from-primary-500 to-secondary-500 px-6 pt-8 pb-16 text-center relative">
                <button @click="closeModal()"
                        class="absolute top-4 right-4 w-8 h-8 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center transition-colors text-white">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
                <p class="text-white/70 text-xs font-medium uppercase tracking-widest">Biodata Anggota</p>
                <p class="text-white font-headline font-bold text-lg mt-1" x-text="selected.nama"></p>
            </div>

            {{-- Avatar (overlap) --}}
            <div class="flex justify-center -mt-10 mb-4 relative z-10">
                <div class="w-20 h-20 rounded-full border-4 border-white shadow-lg overflow-hidden bg-primary-100 flex items-center justify-center">
                    <template x-if="selected.foto">
                        <img :src="selected.foto" :alt="selected.nama" class="w-full h-full object-cover">
                    </template>
                    <template x-if="!selected.foto">
                        <svg class="w-10 h-10 text-primary-400" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/>
                        </svg>
                    </template>
                </div>
            </div>

            {{-- Status badge --}}
            <div class="text-center mb-5 px-6">
                <span class="inline-block text-xs font-semibold px-3 py-1 rounded-full"
                      :class="{
                          'bg-gray-100 text-gray-600'    : selected.status === 'Pembina',
                          'bg-green-100 text-green-600'  : selected.status === 'Anggota Aktif',
                          'bg-yellow-100 text-yellow-700': selected.status === 'Anggota Pasif',
                          'bg-blue-100 text-blue-600'    : selected.status === 'Anggota Kehormatan',
                      }"
                      x-text="selected.status">
                </span>
            </div>

            {{-- Detail rows --}}
            <div class="px-6 pb-6 space-y-3">
                <template x-if="selected.nim">
                    <div class="flex items-center gap-3 bg-[#f0f2f8] rounded-xl px-4 py-3">
                        <svg class="w-4 h-4 text-primary-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0"/>
                        </svg>
                        <div>
                            <p class="text-xs text-neutral-400">NIM</p>
                            <p class="text-sm font-semibold text-secondary-500" x-text="selected.nim"></p>
                        </div>
                    </div>
                </template>

                <template x-if="selected.jabatan">
                    <div class="flex items-center gap-3 bg-primary-50 rounded-xl px-4 py-3">
                        <svg class="w-4 h-4 text-primary-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <div>
                            <p class="text-xs text-neutral-400">Jabatan Kepengurusan</p>
                            <p class="text-sm font-semibold text-primary-600" x-text="selected.jabatan"></p>
                        </div>
                    </div>
                </template>

                <template x-if="selected.divisi">
                    <div class="flex items-center gap-3 bg-[#f0f2f8] rounded-xl px-4 py-3">
                        <svg class="w-4 h-4 text-primary-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        <div>
                            <p class="text-xs text-neutral-400">Divisi</p>
                            <p class="text-sm font-semibold text-secondary-500" x-text="selected.divisi"></p>
                        </div>
                    </div>
                </template>

                <template x-if="selected.angkatan">
                    <div class="flex items-center gap-3 bg-[#f0f2f8] rounded-xl px-4 py-3">
                        <svg class="w-4 h-4 text-primary-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <div>
                            <p class="text-xs text-neutral-400">Angkatan</p>
                            <p class="text-sm font-semibold text-secondary-500" x-text="selected.angkatan"></p>
                        </div>
                    </div>
                </template>

                <template x-if="selected.bergabung">
                    <div class="flex items-center gap-3 bg-[#f0f2f8] rounded-xl px-4 py-3">
                        <svg class="w-4 h-4 text-primary-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <p class="text-xs text-neutral-400">Bergabung</p>
                            <p class="text-sm font-semibold text-secondary-500" x-text="selected.bergabung"></p>
                        </div>
                    </div>
                </template>

                <template x-if="selected.email">
                    <div class="flex items-center gap-3 bg-[#f0f2f8] rounded-xl px-4 py-3">
                        <svg class="w-4 h-4 text-primary-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <div>
                            <p class="text-xs text-neutral-400">Email</p>
                            <p class="text-sm font-semibold text-secondary-500 break-all" x-text="selected.email"></p>
                        </div>
                    </div>
                </template>

                <template x-if="selected.telepon">
                    <div class="flex items-center gap-3 bg-[#f0f2f8] rounded-xl px-4 py-3">
                        <svg class="w-4 h-4 text-primary-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        <div>
                            <p class="text-xs text-neutral-400">No. Telepon</p>
                            <p class="text-sm font-semibold text-secondary-500" x-text="selected.telepon"></p>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</section>

{{-- Per Divisi --}}
@if($anggotaPerDivisi->isNotEmpty())
<section class="py-16 bg-[#f0f2f8]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10">
            <p class="section-label justify-center">Distribusi Keanggotaan</p>
            <h2 class="font-headline font-bold text-3xl text-secondary-500">Distribusi Anggota per Divisi</h2>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($anggotaPerDivisi as $divisi)
            <a href="{{ route('keanggotaan.divisi', $divisi) }}"
               class="bg-white rounded-2xl p-6 flex items-center gap-5 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 group">
                <div class="w-14 h-14 rounded-xl bg-primary-50 group-hover:bg-primary-100 flex items-center justify-center shrink-0 transition-colors">
                    <img src="{{ asset('assets/logo/logosenja.png') }}" alt="SENJA" class="w-9 h-9 object-contain">
                </div>
                <div class="flex-1">
                    <h3 class="font-headline font-semibold text-secondary-500 group-hover:text-primary-500 transition-colors">
                        Divisi {{ $divisi->nama }}
                    </h3>
                    <div class="flex items-center gap-2 mt-1">
                        <div class="flex-1 bg-gray-100 rounded-full h-1.5">
                            @php
                                $maxAnggota = $anggotaPerDivisi->max('anggotas_count') ?: 1;
                                $persen = ($divisi->anggotas_count / $maxAnggota) * 100;
                            @endphp
                            <div class="bg-primary-500 h-1.5 rounded-full transition-all" style="width: {{ $persen }}%"></div>
                        </div>
                        <span class="text-sm font-semibold text-primary-500 shrink-0">{{ $divisi->anggotas_count }}</span>
                    </div>
                    <p class="text-xs text-neutral-400 mt-0.5">total anggota</p>
                </div>
                <svg class="w-4 h-4 text-neutral-300 group-hover:text-primary-400 transition-colors shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- CTA --}}
<section class="py-16 bg-white">
    <div class="max-w-3xl mx-auto px-4 text-center">
        <h2 class="font-headline font-bold text-3xl text-secondary-500 mb-4">Ingin Bergabung?</h2>
        <p class="text-neutral-500 mb-8">Daftarkan dirimu dan jadilah bagian dari keluarga besar UKM SENJA.</p>
        <a href="{{ route('pendaftaran.create') }}" class="btn-primary text-base px-8 py-4">
            Daftar Sekarang
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
            </svg>
        </a>
    </div>
</section>

@push('scripts')
<script>
function keanggotaan() {
    return {
        search: '',
        filterStatus: '',
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

        shouldShow(nama, nim, status) {
            const matchSearch = this.search === ''
                || nama.includes(this.search.toLowerCase())
                || nim.includes(this.search.toLowerCase());
            const matchStatus = this.filterStatus === ''
                || status === this.filterStatus;
            return matchSearch && matchStatus;
        },

        checkEmpty() {
            return false;
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

@endsection
