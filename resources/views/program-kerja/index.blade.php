@extends('layouts.app')

@section('title', 'Program Kerja')

@section('content')

<section class="bg-[#f0f2f8] py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <p class="section-label">Program Kerja Unggulan</p>
        <h1 class="font-headline font-bold text-4xl sm:text-5xl text-secondary-500 mt-1">Program Kerja</h1>
        @if($periodeAktif)
        <p class="text-neutral-500 mt-4 text-lg">Periode Kepengurusan <strong>{{ $periodeAktif->nama }}</strong></p>
        @endif
    </div>
</section>

<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($prokers as $proker)
            <a href="{{ route('proker.show', $proker) }}"
               class="group bg-white border border-gray-100 rounded-2xl p-6 hover:-translate-y-1 hover:shadow-lg transition-all duration-200 shadow-sm">
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

                <p class="text-sm text-neutral-500 line-clamp-3 mb-5">{{ $proker->deskripsi }}</p>

                <div class="border-t border-gray-100 pt-4 space-y-2 text-xs text-neutral-400">
                    <div class="flex items-center gap-2">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        {{ $proker->tanggal_mulai_estimasi->format('d M') }} — {{ $proker->tanggal_selesai_estimasi->format('d M Y') }}
                    </div>
                    @if($proker->target_peserta)
                    <div class="flex items-center gap-2">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Target: {{ number_format($proker->target_peserta) }} peserta
                    </div>
                    @endif
                </div>
            </a>
            @empty
            <div class="col-span-3 py-16 text-center text-neutral-400">
                <p class="text-5xl mb-4">📋</p>
                <p>Belum ada program kerja aktif saat ini.</p>
            </div>
            @endforelse
        </div>

        @if($prokers->hasPages())
        <div class="mt-10">{{ $prokers->links() }}</div>
        @endif
    </div>
</section>

@endsection
