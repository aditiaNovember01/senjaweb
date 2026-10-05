@extends('layouts.app')

@section('title', 'Daftar Anggota')

@section('content')

<section class="bg-[#f0f2f8] py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <p class="section-label">Pendaftaran Anggota Baru</p>
        <h1 class="font-headline font-bold text-4xl sm:text-5xl text-secondary-500 mt-1">Daftar Anggota UKM SENJA</h1>
        <p class="text-neutral-500 mt-4 max-w-xl text-lg">
            Isi formulir berikut untuk mendaftarkan diri. Pendaftaran gratis dan terbuka untuk seluruh mahasiswa aktif.
        </p>
    </div>
</section>

<section class="py-16 bg-white">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

        @if($errors->any())
        <div class="mb-8 bg-red-50 border border-red-200 rounded-2xl p-5">
            <p class="font-semibold text-red-600 mb-2">Terdapat kesalahan pada formulir:</p>
            <ul class="list-disc list-inside text-sm text-red-500 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('pendaftaran.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            {{-- Nama Lengkap --}}
            <div>
                <label class="block text-sm font-semibold text-secondary-500 mb-2">
                    Nama Lengkap <span class="text-primary-500">*</span>
                </label>
                <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}"
                       placeholder="Masukkan nama lengkap sesuai KTM"
                       class="w-full px-4 py-3 rounded-xl border @error('nama_lengkap') border-red-400 bg-red-50 @else border-gray-200 @enderror focus:outline-none focus:ring-2 focus:ring-primary-300 focus:border-primary-500 transition text-secondary-500 placeholder-neutral-400">
                @error('nama_lengkap')<p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                {{-- NIM --}}
                <div>
                    <label class="block text-sm font-semibold text-secondary-500 mb-2">
                        NIM <span class="text-primary-500">*</span>
                    </label>
                    <input type="text" name="nim" value="{{ old('nim') }}"
                           placeholder="Nomor Induk Mahasiswa"
                           class="w-full px-4 py-3 rounded-xl border @error('nim') border-red-400 bg-red-50 @else border-gray-200 @enderror focus:outline-none focus:ring-2 focus:ring-primary-300 focus:border-primary-500 transition text-secondary-500 placeholder-neutral-400">
                    @error('nim')<p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-sm font-semibold text-secondary-500 mb-2">
                        Email <span class="text-primary-500">*</span>
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           placeholder="nama@email.com"
                           class="w-full px-4 py-3 rounded-xl border @error('email') border-red-400 bg-red-50 @else border-gray-200 @enderror focus:outline-none focus:ring-2 focus:ring-primary-300 focus:border-primary-500 transition text-secondary-500 placeholder-neutral-400">
                    @error('email')<p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                {{-- No Telepon --}}
                <div>
                    <label class="block text-sm font-semibold text-secondary-500 mb-2">
                        Nomor Telepon <span class="text-primary-500">*</span>
                    </label>
                    <input type="tel" name="nomor_telepon" value="{{ old('nomor_telepon') }}"
                           placeholder="08xxxxxxxxxx"
                           class="w-full px-4 py-3 rounded-xl border @error('nomor_telepon') border-red-400 bg-red-50 @else border-gray-200 @enderror focus:outline-none focus:ring-2 focus:ring-primary-300 focus:border-primary-500 transition text-secondary-500 placeholder-neutral-400">
                    @error('nomor_telepon')<p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>

                {{-- Divisi --}}
                <div>
                    <label class="block text-sm font-semibold text-secondary-500 mb-2">
                        Divisi Pilihan <span class="text-primary-500">*</span>
                    </label>
                    <select name="divisi_id"
                            class="w-full px-4 py-3 rounded-xl border @error('divisi_id') border-red-400 bg-red-50 @else border-gray-200 @enderror focus:outline-none focus:ring-2 focus:ring-primary-300 focus:border-primary-500 transition text-secondary-500 bg-white">
                        <option value="">-- Pilih Divisi --</option>
                        @foreach($divisis as $divisi)
                            <option value="{{ $divisi->id }}" {{ old('divisi_id') == $divisi->id ? 'selected' : '' }}>
                                Divisi {{ $divisi->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('divisi_id')<p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- Prestasi --}}
            <div>
                <label class="block text-sm font-semibold text-secondary-500 mb-2">
                    Prestasi yang Pernah Diraih
                    <span class="font-normal text-neutral-400">(opsional)</span>
                </label>
                <textarea name="prestasi" rows="4"
                          placeholder="Contoh: Juara 1 Tari Kreasi Daerah Tingkat Provinsi 2023, Finalis Festival Musik Kampus 2024..."
                          class="w-full px-4 py-3 rounded-xl border @error('prestasi') border-red-400 bg-red-50 @else border-gray-200 @enderror focus:outline-none focus:ring-2 focus:ring-primary-300 focus:border-primary-500 transition text-secondary-500 placeholder-neutral-400 resize-none">{{ old('prestasi') }}</textarea>
                @error('prestasi')<p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            {{-- Berkas Pendukung --}}
            <div>
                <label class="block text-sm font-semibold text-secondary-500 mb-2">
                    Berkas Pendukung
                    <span class="font-normal text-neutral-400">(opsional — KTM, sertifikat, dll)</span>
                </label>
                <div class="relative">
                    <input type="file" name="berkas" id="berkas" accept=".pdf,.jpg,.jpeg,.png"
                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                    <div class="border-2 border-dashed @error('berkas') border-red-400 bg-red-50 @else border-gray-200 hover:border-primary-300 @enderror rounded-xl p-8 text-center transition-colors">
                        <div class="w-12 h-12 rounded-xl bg-primary-50 flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                        </div>
                        <p class="text-sm font-medium text-secondary-500 mb-1">Klik atau seret file ke sini</p>
                        <p class="text-xs text-neutral-400">Format: PDF, JPG, PNG — Maksimal 5 MB</p>
                    </div>
                </div>
                <p id="file-name" class="mt-2 text-xs text-primary-500 font-medium hidden"></p>
                @error('berkas')<p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            {{-- Info Box --}}
            <div class="bg-primary-50 border border-primary-100 rounded-2xl p-5 text-sm text-primary-700">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <p class="font-semibold mb-1">Informasi Pendaftaran</p>
                        <p class="leading-relaxed">Data pendaftaranmu akan diverifikasi oleh pengurus SENJA. Proses verifikasi berlangsung 1–3 hari kerja. Penetapan angkatan dilakukan melalui perundingan antar anggota. Hasil akan dihubungi via email atau nomor telepon yang didaftarkan.</p>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-4 pt-2">
                <button type="submit" class="btn-primary px-8 py-4">
                    Kirim Pendaftaran
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </button>
                <a href="{{ route('beranda') }}" class="btn-ghost">Batal</a>
            </div>
        </form>
    </div>
</section>

<script>
document.getElementById('berkas').addEventListener('change', function() {
    const nameEl = document.getElementById('file-name');
    if (this.files.length > 0) {
        nameEl.textContent = '✓ File dipilih: ' + this.files[0].name;
        nameEl.classList.remove('hidden');
    }
});
</script>

@endsection
