@php
    use App\Models\SiteSetting;
    $s = SiteSetting::all_settings();
    $orgName        = $s['org_name']           ?? 'UKM SENJA';
    $orgTagline     = $s['org_tagline']         ?? 'Wadah kolaboratif mahasiswa dalam mengeksplorasi minat bakat di bidang kreativitas dan seni.';
    $address        = $s['contact_address']     ?? 'Gedung UKM, Lt. 2, Universitas Jayanusa';
    $email          = $s['contact_email']       ?? 'senja@jayanusa.ac.id';
    $phone          = $s['contact_phone']       ?? '';
    $instagram      = $s['social_instagram']    ?? '@ukm.senja';
    $instagramUrl   = $s['social_instagram_url'] ?? '#';
    $youtube        = $s['social_youtube']      ?? '';
    $tiktok         = $s['social_tiktok']       ?? '';
    $commitment     = $s['footer_commitment']   ?? 'Mendorong kreasi mahasiswa yang berkolaborasi, inovatif, dan berdampak bagi komunitas kampus.';
@endphp

<footer class="bg-secondary-500 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-10">

            {{-- Brand --}}
            <div class="md:col-span-1">
                <div class="flex items-center gap-2 mb-4">
                    <img src="{{ asset('assets/logo/logosenja.png') }}"
                         alt="{{ $orgName }}"
                         class="h-10 w-auto object-contain brightness-0 invert">
                </div>
                <p class="text-secondary-200 text-sm leading-relaxed">
                    {{ $orgTagline }}
                </p>
            </div>

            {{-- Tautan Publik --}}
            <div>
                <h4 class="font-headline font-semibold text-sm uppercase tracking-wider text-secondary-300 mb-4">Tautan Publik</h4>
                <ul class="space-y-2 text-sm text-secondary-200">
                    <li><a href="{{ route('beranda') }}" class="hover:text-white transition-colors">Beranda</a></li>
                    <li><a href="{{ route('profil') }}" class="hover:text-white transition-colors">Profil & Visi</a></li>
                    <li><a href="{{ route('keanggotaan.index') }}" class="hover:text-white transition-colors">Keanggotaan</a></li>
                    <li><a href="{{ route('kegiatan.index') }}" class="hover:text-white transition-colors">Kegiatan</a></li>
                    <li><a href="{{ route('proker.index') }}" class="hover:text-white transition-colors">Program Kerja</a></li>
                    <li><a href="{{ route('pendaftaran.create') }}" class="hover:text-white transition-colors">Daftar Anggota</a></li>
                </ul>
            </div>

            {{-- Sekretariat --}}
            <div>
                <h4 class="font-headline font-semibold text-sm uppercase tracking-wider text-secondary-300 mb-4">Sekretariat & Kontak</h4>
                <ul class="space-y-3 text-sm text-secondary-200">
                    @if($address)
                    <li class="flex items-start gap-2">
                        <svg class="w-4 h-4 mt-0.5 shrink-0 text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        {{ $address }}
                    </li>
                    @endif

                    @if($email)
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 shrink-0 text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <a href="mailto:{{ $email }}" class="hover:text-white transition-colors">{{ $email }}</a>
                    </li>
                    @endif

                    @if($phone)
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 shrink-0 text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        <a href="tel:{{ $phone }}" class="hover:text-white transition-colors">{{ $phone }}</a>
                    </li>
                    @endif

                    @if($instagram)
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 shrink-0 text-primary-400" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                        </svg>
                        <a href="{{ $instagramUrl }}" target="_blank" class="hover:text-white transition-colors">{{ $instagram }}</a>
                    </li>
                    @endif

                    @if($tiktok)
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 shrink-0 text-primary-400" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1V9.01a6.33 6.33 0 00-.79-.05 6.34 6.34 0 00-6.34 6.34 6.34 6.34 0 006.34 6.34 6.34 6.34 0 006.33-6.34V8.67a8.16 8.16 0 004.77 1.52V6.71a4.85 4.85 0 01-1-.02z"/>
                        </svg>
                        {{ $tiktok }}
                    </li>
                    @endif
                </ul>
            </div>

            {{-- Komitmen --}}
            <div>
                <h4 class="font-headline font-semibold text-sm uppercase tracking-wider text-secondary-300 mb-4">Komitmen Kolektif</h4>
                <p class="text-sm text-secondary-200 leading-relaxed">
                    {{ $commitment }}
                </p>
                <a href="{{ route('pendaftaran.create') }}"
                   class="inline-flex items-center gap-2 mt-4 text-sm font-semibold text-primary-400 hover:text-primary-300 transition-colors">
                    Bergabung sekarang
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>
        </div>

        <div class="border-t border-secondary-700 mt-10 pt-6 flex flex-col md:flex-row items-center justify-between gap-3">
            <p class="text-xs text-secondary-400">&copy; {{ date('Y') }} {{ $orgName }}. Seluruh hak cipta dilindungi.</p>
            <p class="text-xs text-secondary-400 uppercase tracking-wider">PERIODE KEPENGURUSAN AKTIF — {{ $orgName }}</p>
        </div>
    </div>
</footer>
