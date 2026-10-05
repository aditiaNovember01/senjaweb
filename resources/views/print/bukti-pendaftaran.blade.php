<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Pendaftaran — {{ $pendaftaran->nama_lengkap }}</title>
    <style>
        /* ── Reset ── */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Arial', 'Helvetica', sans-serif;
            font-size: 11pt;
            color: #1a1a1a;
            background: #f5f5f5;
            padding: 24px;
        }

        /* ── Kertas A4 ── */
        .page {
            background: #fff;
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            padding: 16mm 18mm;
            box-shadow: 0 4px 24px rgba(0,0,0,0.12);
            position: relative;
        }

        /* ── Kop Surat ── */
        .kop {
            display: flex;
            align-items: center;
            gap: 16px;
            padding-bottom: 12px;
            border-bottom: 3px solid #EA580C;
            margin-bottom: 6px;
        }
        .kop img {
            width: 64px;
            height: 64px;
            object-fit: contain;
            flex-shrink: 0;
        }
        .kop-text { flex: 1; }
        .kop-org {
            font-size: 16pt;
            font-weight: 700;
            color: #EA580C;
            letter-spacing: 0.5px;
            line-height: 1.2;
        }
        .kop-tagline {
            font-size: 8.5pt;
            color: #555;
            margin-top: 2px;
        }
        .kop-kontak {
            font-size: 8pt;
            color: #777;
            margin-top: 4px;
        }

        /* ── Sub header garis ── */
        .sub-header {
            text-align: center;
            margin: 10px 0 18px;
        }
        .sub-header-line {
            border-top: 1px solid #d1d5db;
            margin-bottom: 8px;
        }
        .sub-header h2 {
            font-size: 13pt;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #1a1a1a;
        }
        .sub-header p {
            font-size: 9pt;
            color: #6b7280;
            margin-top: 2px;
        }

        /* ── Nomor pendaftaran badge ── */
        .no-daftar {
            display: inline-block;
            background: #fff7ed;
            border: 1.5px solid #EA580C;
            border-radius: 6px;
            padding: 5px 14px;
            font-size: 9.5pt;
            font-weight: 700;
            color: #EA580C;
            margin-bottom: 18px;
            letter-spacing: 0.5px;
        }

        /* ── Tabel data ── */
        .data-section {
            margin-bottom: 16px;
        }
        .section-title {
            font-size: 9pt;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #EA580C;
            background: #fff7ed;
            padding: 4px 10px;
            border-left: 3px solid #EA580C;
            margin-bottom: 8px;
        }
        table.detail {
            width: 100%;
            border-collapse: collapse;
        }
        table.detail tr td {
            padding: 5px 8px;
            vertical-align: top;
            font-size: 10.5pt;
            border-bottom: 1px solid #f3f4f6;
        }
        table.detail tr td:first-child {
            width: 38%;
            color: #6b7280;
            font-size: 10pt;
        }
        table.detail tr td:nth-child(2) {
            width: 4%;
            color: #9ca3af;
        }
        table.detail tr td:last-child {
            font-weight: 600;
            color: #111827;
        }
        table.detail tr:last-child td { border-bottom: none; }

        /* ── Berkas foto ── */
        .berkas-box {
            margin-top: 14px;
        }
        .berkas-box img {
            max-width: 160px;
            max-height: 200px;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            object-fit: cover;
            display: block;
            margin-top: 6px;
        }
        .berkas-pdf-note {
            font-size: 9pt;
            color: #6b7280;
            font-style: italic;
            margin-top: 4px;
        }

        /* ── Kolom tanda tangan ── */
        .ttd-row {
            display: flex;
            justify-content: space-between;
            margin-top: 28px;
            gap: 16px;
        }
        .ttd-box {
            flex: 1;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 10px 14px;
            text-align: center;
        }
        .ttd-label {
            font-size: 9pt;
            color: #6b7280;
            margin-bottom: 50px;
        }
        .ttd-line {
            border-top: 1px solid #374151;
            padding-top: 4px;
            font-size: 9pt;
            font-weight: 700;
            color: #111827;
        }
        .ttd-jabatan {
            font-size: 8.5pt;
            color: #6b7280;
        }

        /* ── Footer ── */
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #e5e7eb;
            font-size: 8pt;
            color: #9ca3af;
            text-align: center;
        }

        /* ── Status badge ── */
        .status-badge {
            display: inline-block;
            padding: 2px 10px;
            border-radius: 99px;
            font-size: 9pt;
            font-weight: 700;
        }
        .badge-pending  { background: #fef3c7; color: #92400e; }
        .badge-approved { background: #d1fae5; color: #065f46; }
        .badge-rejected { background: #fee2e2; color: #991b1b; }

        /* ── Watermark ── */
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%,-50%) rotate(-35deg);
            font-size: 72pt;
            font-weight: 900;
            color: rgba(234, 88, 12, 0.04);
            pointer-events: none;
            letter-spacing: 4px;
            text-transform: uppercase;
            white-space: nowrap;
            z-index: 0;
        }

        /* ── Tombol print (tidak dicetak) ── */
        .no-print {
            text-align: center;
            margin-bottom: 20px;
        }
        .btn-print {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #EA580C;
            color: white;
            border: none;
            padding: 10px 28px;
            border-radius: 8px;
            font-size: 11pt;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn-print:hover { background: #c94a09; }
        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #f3f4f6;
            color: #374151;
            border: 1px solid #e5e7eb;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 11pt;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            margin-right: 10px;
        }

        /* ── Print media ── */
        @media print {
            body { background: white; padding: 0; }
            .page { box-shadow: none; margin: 0; padding: 12mm 15mm; width: 100%; }
            .no-print { display: none !important; }
            .watermark { position: fixed; }
            @page {
                size: A4;
                margin: 0;
            }
        }
    </style>
</head>
<body>

{{-- Tombol Print & Kembali (tidak muncul saat cetak) --}}
<div class="no-print">
    <a href="javascript:history.back()" class="btn-back">← Kembali</a>
    <button class="btn-print" onclick="window.print()">
        🖨️ Cetak / Simpan PDF
    </button>
</div>

<div class="page">
    <div class="watermark">SENJA</div>

    {{-- KOP SURAT --}}
    <div class="kop">
        <img src="{{ asset('assets/logo/logosenja.png') }}" alt="Logo {{ $settings['org_name'] }}">
        <div class="kop-text">
            <div class="kop-org">{{ strtoupper($settings['org_name']) }}</div>
            <div class="kop-tagline">Unit Kegiatan Mahasiswa &mdash; Seni &amp; Kreativitas</div>
            @if($settings['contact_address'] || $settings['contact_email'])
            <div class="kop-kontak">
                @if($settings['contact_address']) 📍 {{ $settings['contact_address'] }} @endif
                @if($settings['contact_email']) &nbsp;|&nbsp; ✉️ {{ $settings['contact_email'] }} @endif
                @if($settings['contact_phone']) &nbsp;|&nbsp; 📞 {{ $settings['contact_phone'] }} @endif
                @if($settings['social_instagram']) &nbsp;|&nbsp; 📸 {{ $settings['social_instagram'] }} @endif
            </div>
            @endif
        </div>
    </div>

    {{-- JUDUL DOKUMEN --}}
    <div class="sub-header">
        <div class="sub-header-line"></div>
        <h2>Bukti Formulir Pendaftaran Anggota</h2>
        <p>Dokumen ini merupakan bukti penerimaan formulir pendaftaran calon anggota</p>
    </div>

    {{-- NOMOR PENDAFTARAN --}}
    <div>
        <span class="no-daftar">
            No. Daftar: PDT-{{ str_pad($pendaftaran->id, 5, '0', STR_PAD_LEFT) }}
            &nbsp;|&nbsp;
            Tanggal: {{ $pendaftaran->created_at->translatedFormat('d F Y, H:i') }} WIB
        </span>
    </div>

    {{-- DATA PENDAFTAR --}}
    <div class="data-section">
        <div class="section-title">Data Pendaftar</div>
        <table class="detail">
            <tr>
                <td>Nama Lengkap</td><td>:</td>
                <td>{{ $pendaftaran->nama_lengkap }}</td>
            </tr>
            <tr>
                <td>NIM / Nomor Induk</td><td>:</td>
                <td>{{ $pendaftaran->nim }}</td>
            </tr>
            <tr>
                <td>Email</td><td>:</td>
                <td>{{ $pendaftaran->email }}</td>
            </tr>
            <tr>
                <td>Nomor Telepon</td><td>:</td>
                <td>{{ $pendaftaran->nomor_telepon }}</td>
            </tr>
        </table>
    </div>

    {{-- PILIHAN DIVISI --}}
    <div class="data-section">
        <div class="section-title">Divisi yang Dipilih</div>
        <table class="detail">
            <tr>
                <td>Divisi</td><td>:</td>
                <td>{{ $pendaftaran->divisi->nama ?? '—' }}</td>
            </tr>
        </table>
    </div>

    {{-- PRESTASI --}}
    @if($pendaftaran->prestasi)
    <div class="data-section">
        <div class="section-title">Prestasi / Pengalaman</div>
        <div style="padding: 8px 10px; font-size: 10.5pt; line-height: 1.6; color: #111827; border: 1px solid #f3f4f6; border-radius: 6px; background: #fafafa;">
            {{ $pendaftaran->prestasi }}
        </div>
    </div>
    @endif

    {{-- BERKAS PENDUKUNG --}}
    @if($pendaftaran->berkas)
    <div class="data-section berkas-box">
        <div class="section-title">Berkas Pendukung</div>
        @php
            $berkasUrl  = \Storage::url($pendaftaran->berkas);
            $isPdf      = str_ends_with(strtolower($pendaftaran->berkas), '.pdf');
        @endphp
        @if($isPdf)
            <p class="berkas-pdf-note">📄 File PDF terlampir — lihat di: <strong>{{ $berkasUrl }}</strong></p>
        @else
            <img src="{{ $berkasUrl }}" alt="Berkas Pendukung">
        @endif
    </div>
    @endif

    {{-- STATUS --}}
    <div class="data-section" style="margin-top:10px">
        <div class="section-title">Status Pendaftaran</div>
        <div style="padding: 8px 10px;">
            @php
                $status = $pendaftaran->status ?? 'Menunggu';
                $badgeClass = match(strtolower($status)) {
                    'disetujui', 'approved' => 'badge-approved',
                    'ditolak',  'rejected'  => 'badge-rejected',
                    default                  => 'badge-pending',
                };
            @endphp
            <span class="status-badge {{ $badgeClass }}">{{ ucfirst($status) }}</span>
        </div>
    </div>

    {{-- TANDA TANGAN --}}
    <div class="ttd-row">
        <div class="ttd-box">
            <div class="ttd-label">Pendaftar</div>
            <div class="ttd-line">{{ $pendaftaran->nama_lengkap }}</div>
            <div class="ttd-jabatan">Calon Anggota</div>
        </div>
        <div class="ttd-box">
            <div class="ttd-label">Mengetahui,</div>
            <div class="ttd-line">Nalarasati Usman</div>
            <div class="ttd-jabatan">Sekretaris {{ $settings['org_name'] }}</div>
        </div>
        <div class="ttd-box">
            <div class="ttd-label">Menyetujui,</div>
            <div class="ttd-line">Tri Valdo Putra</div>
            <div class="ttd-jabatan">Ketua {{ $settings['org_name'] }}</div>
        </div>
    </div>

    {{-- FOOTER --}}
    <div class="footer">
        Dokumen ini diterbitkan oleh sistem administrasi {{ $settings['org_name'] }} &mdash;
        Dicetak pada {{ now()->translatedFormat('d F Y, H:i') }} WIB &mdash;
        No. Ref: PDT-{{ str_pad($pendaftaran->id, 5, '0', STR_PAD_LEFT) }}
    </div>
</div>

<script>
    // Auto-trigger print dialog hanya jika URL mengandung ?print=1
    if (new URLSearchParams(window.location.search).get('print') === '1') {
        window.addEventListener('load', () => setTimeout(() => window.print(), 400));
    }
</script>
</body>
</html>
