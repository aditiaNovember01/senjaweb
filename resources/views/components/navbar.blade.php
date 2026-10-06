<nav class="sticky top-0 z-50 bg-white/95 backdrop-blur border-b border-gray-100 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">

            {{-- Logo --}}
            <a href="{{ route('beranda') }}" class="flex items-center gap-2">
                <img src="{{ asset('assets/logo/logosenja.png') }}"
                     alt="UKM SENJA"
                     class="h-10 w-auto object-contain">
                <span class="text-base font-bold text-secondary-500 tracking-wide leading-tight hidden sm:block">UKM SENJA</span>
            </a>

            {{-- Nav Links Desktop --}}
            <div class="hidden md:flex items-center gap-1">
                @php
                    $navLinks = [
                        ['route' => 'beranda',           'label' => 'Beranda'],
                        ['route' => 'profil',             'label' => 'Profil & Visi'],
                        ['route' => 'keanggotaan.index',  'label' => 'Keanggotaan'],
                        ['route' => 'kegiatan.index',     'label' => 'Kegiatan'],
                        ['route' => 'proker.index',       'label' => 'Program Kerja'],
                        ['route' => 'galeri.index',       'label' => 'Galeri'],
                    ];
                @endphp

                @foreach($navLinks as $link)
                    <a href="{{ route($link['route']) }}"
                       class="px-4 py-2 text-sm font-medium rounded-full transition-colors duration-150
                              {{ request()->routeIs($link['route']) || request()->routeIs($link['route'].'*')
                                 ? 'text-primary-500 font-semibold'
                                 : 'text-neutral-600 hover:text-secondary-500' }}">
                        {{ $link['label'] }}
                        @if(request()->routeIs($link['route']) || request()->routeIs($link['route'].'*'))
                            <span class="block h-0.5 bg-primary-500 rounded-full mt-0.5"></span>
                        @endif
                    </a>
                @endforeach
            </div>

            {{-- CTA --}}
            <div class="flex items-center gap-3">
                <a href="{{ url('/admin') }}"
                   class="w-9 h-9 rounded-full bg-gray-100 hover:bg-primary-50 flex items-center justify-center transition-colors"
                   title="Admin">
                    <svg class="w-5 h-5 text-neutral-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </a>

                {{-- Mobile toggle --}}
                <button id="mobile-menu-btn" class="md:hidden w-9 h-9 flex items-center justify-center rounded-full hover:bg-gray-100">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile Menu --}}
    <div id="mobile-menu" class="hidden md:hidden border-t border-gray-100 bg-white px-4 py-3 space-y-1">
        @foreach($navLinks as $link)
            <a href="{{ route($link['route']) }}"
               class="block px-4 py-2.5 rounded-xl text-sm font-medium
                      {{ request()->routeIs($link['route']) ? 'bg-primary-50 text-primary-500' : 'text-neutral-600 hover:bg-gray-50' }}">
                {{ $link['label'] }}
            </a>
        @endforeach
    </div>
</nav>

<script>
    document.getElementById('mobile-menu-btn').addEventListener('click', () => {
        document.getElementById('mobile-menu').classList.toggle('hidden');
    });
</script>
