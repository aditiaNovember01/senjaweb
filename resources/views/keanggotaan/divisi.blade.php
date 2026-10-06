@extends('layouts.app')

@section('title', 'Divisi ' . $divisi->nama . ' — UKM SENJA')
@section('meta_description', 'Data anggota Divisi ' . $divisi->nama . ' UKM SENJA.')

@section('content')

{{-- Header --}}
<section class="bg-gradient-to-br from-secondary-500 to-primary-500 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <a href="{{ route('keanggotaan.index') }}"
           class="inline-flex items-center gap-2 text-white/70 hover:text-white text-sm mb-6 transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Keanggotaan
        </a>

        <div class="flex items-center gap-5">
            <div class="w-16 h-16 rounded-2xl bg-white/20 flex items-center justify-center flex-shrink-0">
                <img src="{{ asset('assets/logo/logosenja.png') }}" alt="SENJA" class="w-10 h-10 object-contain">
            </div>
            <div>
                <p class="text-white/60 text-xs font-semibold uppercase tracking-[0.2em] mb-1">Divisi UKM SENJA</p>
                <h1 class="font-headline font-extrabold text-3xl sm:text-4xl leading-tight"
                    style="color:#ffffff; text-shadow: 0 2px 8px rgba(0,0,0,0.3)">
                    Divisi {{ $divisi->nama }}
                </h1>
                @if($divisi->deskripsi)
                <p class="mt-2 text-sm max-w-xl" style="color:rgba(255,255,255,0.75)">{{ $divisi->deskripsi }}</p>
                @else
                <p class="mt-2 text-sm" style="color:rgba(255,255,255,0.75)">
                    Divisi Seni {{ $divisi->nama }} UKM SENJA
                </p>
                @endif
            </div>
        </div>
    </div>
</section>

{{-- Stats --}}
<section class="py-8 bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-wrap gap-4">
            <div class="flex items-center gap-3 bg-[#f0f2f8] rounded-xl px-5 py-3">
                <span class="text-2xl font-bold text-secondary-500">{{ $stats['total'] }}</span>
                <span class="text-sm text-neutral-500">Total Anggota</span>
            </div>
            @if($stats['aktif'] > 0)
            <div class="flex items-center gap-3 bg-green-50 rounded-xl px-5 py-3">
                <span class="text-2xl font-bold text-green-600">{{ $stats['aktif'] }}</span>
                <span class="text-sm text-neutral-500">Anggota Aktif</span>
            </div>
            @endif
            @if($stats['pasif'] > 0)
            <div class="flex items-center gap-3 bg-yellow-50 rounded-xl px-5 py-3">
                <span class="text-2xl font-bold text-yellow-600">{{ $stats['pasif'] }}</span>
                <span class="text-sm text-neutral-500">Anggota Pasif</span>
            </div>
            @endif
            @if($stats['kehormatan'] > 0)
            <div class="flex items-center gap-3 bg-blue-50 rounded-xl px-5 py-3">
                <span class="text-2xl font-bold text-blue-600">{{ $stats['kehormatan'] }}</span>
                <span class="text-sm text-neutral-500">Anggota Kehormatan</span>
            </div>
            @endif
            @if($stats['pembina'] > 0)
            <div class="flex items-center gap-3 bg-gray-50 rounded-xl px-5 py-3">
                <span class="text-2xl font-bold text-gray-600">{{ $stats['pembina'] }}</span>
                <span class="text-sm text-neutral-500">Pembina</span>
            </div>
            @endif
        </div>
    </div>
</section>

{{-- Daftar Anggota --}}
<section class="py-12 bg-white" x-data="divisiPage()">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- Filter & Search --}}
        <div class="flex flex-wrap items-center gap-3 mb-8">
            {{-- Search --}}
            <div class="relative flex-1 min-w-[200px] max-w-xs">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-neutral-400"
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" x-model="search" placeholder="Cari nama..."
                       class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200
                              focus:outline-none focus:ring-2 focus:ring-primary-300
                              text-sm placeholder-neutral-400 transition">
            </div>

            {{-- Filter status --}}
            @php
                $statusList = $anggotas->pluck('status')->unique()->values();
            @endphp
            <button @click="filterStatus = ''"
                    :class="filterStatus === '' ? 'bg-primary-500 text-white' : 'bg-[#f0f2f8] text-secondary-500 hover:bg-primary-50'"
                    class="px-4 py-2.5 rounded-xl text-sm font-medium transition">
                Semua
            </button>
            @foreach($statusList as $st)
            <button @click="filterStatus = '{{ $st }}'"
                    :class="filterStatus === '{{ $st }}' ? 'bg-primary-500 text-white' : 'bg-[#f0f2f8] text-secondary-500 hover:bg-primary-50'"
                    class="px-4 py-2.5 rounded-xl text-sm font-medium transition">
                {{ $st }}
            </button>
            @endforeach
        </div>

        {{-- Grid Anggota — foto lebih besar --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-5">
            @forelse($anggotas as $anggota)
            @php
                $jabatan = $anggota->pengurus->sortByDesc('id')->first()?->jabatan ?? '';
                $biodata = json_encode([
                    'nama'      => $anggota->nama_lengkap,
                    'nim'       => $anggota->nim,
                    'email'     => $anggota->email ?? '',
                    'telepon'   => $anggota->nomor_telepon ?? '',
                    'divisi'    => $divisi->nama,
                    'angkatan'  => $anggota->angkatan->nama ?? '',
                    'status'    => $anggota->status,
                    'jabatan'   => $jabatan,
                    'bergabung' => $anggota->tanggal_bergabung
                        ? $anggota->tanggal_bergabung->translatedFormat('d F Y')
                        : '',
                    'foto'      => $anggota->foto_profil
                        ? asset('storage/'.$anggota->foto_profil)
                        : '',
                ], JSON_HEX_QUOT | JSON_HEX_APOS);
            @endphp
            <div
                class="bg-white rounded-2xl overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-200 cursor-pointer border border-gray-100 group"
                x-show="shouldShow('{{ strtolower($anggota->nama_lengkap) }}', '{{ $anggota->status }}')"
                x-transition
                @click="openModal({{ $biodata }})">

                {{-- Foto dengan overlay keterangan --}}
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

                    {{-- Gradient overlay dengan nama --}}
                    <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/75 via-black/30 to-transparent pt-10 pb-3 px-3">
                        <p class="text-white font-semibold text-sm leading-tight line-clamp-2 drop-shadow">
                            {{ $anggota->nama_lengkap }}
                        </p>
                        @if($jabatan)
                        <p class="text-white/80 text-xs mt-0.5 drop-shadow">{{ $jabatan }}</p>
                        @endif
                    </div>

                    {{-- Status badge top-right --}}
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

                {{-- Info bawah card --}}
                <div class="px-3 py-2.5">
                    <p class="text-xs text-neutral-400">{{ $anggota->nim }}</p>
                    @if($anggota->angkatan)
                    <p class="text-xs text-neutral-300 mt-0.5">{{ $anggota->angkatan->nama }}</p>
                    @endif
                </div>
            </div>
            @empty
            <div class="col-span-5 py-20 text-center text-neutral-400">
                <img src="{{ asset('assets/logo/logosenja.png') }}" alt="SENJA"
                     class="w-12 h-12 object-contain mx-auto mb-3 opacity-30">
                <p>Belum ada anggota di divisi ini.</p>
            </div>
            @endforelse
        </div>

        {{-- Empty state filter --}}
        <div x-show="checkEmpty()" x-cloak class="py-16 text-center text-neutral-400">
            <p class="text-sm">Tidak ada anggota yang sesuai filter.</p>
        </div>

    </div>

    {{-- Modal Biodata (sama dengan halaman keanggotaan) --}}
    <div x-show="modalOpen" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">

        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="closeModal()"></div>

        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden z-10 max-h-[90vh] overflow-y-auto"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             @click.stop>

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

            <div class="px-6 pb-6 space-y-3">
                <template x-if="selected.nim">
                    <div class="flex items-center gap-3 bg-[#f0f2f8] rounded-xl px-4 py-3">
                        <svg class="w-4 h-4 text-primary-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0"/></svg>
                        <div><p class="text-xs text-neutral-400">NIM</p><p class="text-sm font-semibold text-secondary-500" x-text="selected.nim"></p></div>
                    </div>
                </template>
                <template x-if="selected.jabatan">
                    <div class="flex items-center gap-3 bg-primary-50 rounded-xl px-4 py-3">
                        <svg class="w-4 h-4 text-primary-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        <div><p class="text-xs text-neutral-400">Jabatan Kepengurusan</p><p class="text-sm font-semibold text-primary-600" x-text="selected.jabatan"></p></div>
                    </div>
                </template>
                <template x-if="selected.angkatan">
                    <div class="flex items-center gap-3 bg-[#f0f2f8] rounded-xl px-4 py-3">
                        <svg class="w-4 h-4 text-primary-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <div><p class="text-xs text-neutral-400">Angkatan</p><p class="text-sm font-semibold text-secondary-500" x-text="selected.angkatan"></p></div>
                    </div>
                </template>
                <template x-if="selected.bergabung">
                    <div class="flex items-center gap-3 bg-[#f0f2f8] rounded-xl px-4 py-3">
                        <svg class="w-4 h-4 text-primary-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <div><p class="text-xs text-neutral-400">Bergabung</p><p class="text-sm font-semibold text-secondary-500" x-text="selected.bergabung"></p></div>
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
function divisiPage() {
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
        shouldShow(nama, status) {
            const matchSearch = this.search === '' || nama.includes(this.search.toLowerCase());
            const matchStatus = this.filterStatus === '' || status === this.filterStatus;
            return matchSearch && matchStatus;
        },
        checkEmpty() { return false; },
        init() {
            document.addEventListener('keydown', e => {
                if (e.key === 'Escape') this.closeModal();
            });
        }
    }
}
</script>
@endpush

@endsection
