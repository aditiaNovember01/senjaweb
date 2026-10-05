<x-filament-panels::page>
@php $info = $this->getStorageInfo(); @endphp

<div class="space-y-6">

    {{-- Status kartu --}}
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:16px">

        <div style="background:#fff;border:1px solid #f1f5f9;border-radius:12px;padding:18px 20px">
            <div style="font-size:0.75rem;color:#64748b;margin-bottom:6px;text-transform:uppercase;letter-spacing:0.05em">Symlink Status</div>
            @if($info['symlink_exists'])
                <div style="font-size:1.1rem;font-weight:700;color:#16a34a">✅ Aktif</div>
                <div style="font-size:0.75rem;color:#94a3b8;margin-top:4px;word-break:break-all">→ {{ $info['symlink_target'] }}</div>
            @else
                <div style="font-size:1.1rem;font-weight:700;color:#dc2626">❌ Belum dibuat</div>
                <div style="font-size:0.75rem;color:#dc2626;margin-top:4px">Klik "Fix Storage Symlink" di atas</div>
            @endif
        </div>

        <div style="background:#fff;border:1px solid #f1f5f9;border-radius:12px;padding:18px 20px">
            <div style="font-size:0.75rem;color:#64748b;margin-bottom:6px;text-transform:uppercase;letter-spacing:0.05em">Storage Folder</div>
            @if($info['storage_exists'])
                <div style="font-size:1.1rem;font-weight:700;color:#16a34a">✅ Ada</div>
            @else
                <div style="font-size:1.1rem;font-weight:700;color:#dc2626">❌ Tidak ada</div>
            @endif
            <div style="font-size:0.75rem;color:#94a3b8;margin-top:4px;word-break:break-all">storage/app/public</div>
        </div>

        <div style="background:#fff;border:1px solid #f1f5f9;border-radius:12px;padding:18px 20px">
            <div style="font-size:0.75rem;color:#64748b;margin-bottom:6px;text-transform:uppercase;letter-spacing:0.05em">Public URL</div>
            <div style="font-size:0.875rem;font-weight:600;color:#0f172a;word-break:break-all">{{ $info['public_url'] }}</div>
        </div>

        <div style="background:#fff;border:1px solid #f1f5f9;border-radius:12px;padding:18px 20px">
            <div style="font-size:0.75rem;color:#64748b;margin-bottom:6px;text-transform:uppercase;letter-spacing:0.05em">Environment</div>
            <div style="font-size:0.875rem;font-weight:600;color:#0f172a">{{ strtoupper($info['app_env']) }}</div>
            <div style="font-size:0.75rem;color:#94a3b8;margin-top:4px">PHP {{ $info['php_version'] }} &bull; TZ: {{ $info['app_timezone'] }}</div>
        </div>

    </div>

    {{-- Isi storage --}}
    @if(count($info['subdirs']) > 0)
    <div style="background:#fff;border:1px solid #f1f5f9;border-radius:12px;overflow:hidden">
        <div style="padding:14px 20px;border-bottom:1px solid #f1f5f9;font-size:0.875rem;font-weight:600;color:#0f172a">
            📁 Isi storage/app/public
        </div>
        @foreach($info['subdirs'] as $dir)
        <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 20px;border-bottom:1px solid #f8fafc;font-size:0.875rem">
            <span style="font-weight:500;color:#0f172a">{{ $dir['name'] }}/</span>
            <span style="color:#64748b">{{ $dir['count'] }} file &bull; {{ $dir['size'] }}</span>
        </div>
        @endforeach
    </div>
    @else
    <div style="background:#fff;border:1px solid #f1f5f9;border-radius:12px;padding:40px 20px;text-align:center;color:#94a3b8;font-size:0.875rem">
        Folder storage/app/public kosong atau belum ada.
    </div>
    @endif

    {{-- Panduan manual --}}
    <div style="background:#fff;border:1px solid #f1f5f9;border-radius:12px;padding:20px">
        <div style="font-size:0.875rem;font-weight:600;color:#0f172a;margin-bottom:12px">📋 Panduan Manual (SSH / Terminal aaPanel)</div>
        <div style="background:#1e293b;border-radius:8px;padding:16px;font-family:monospace;font-size:0.8rem;color:#e2e8f0;line-height:1.8">
            <div style="color:#94a3b8"># 1. Masuk ke direktori project</div>
            <div>cd /www/wwwroot/senjaweb.gauld.my.id</div>
            <br>
            <div style="color:#94a3b8"># 2. Buat symlink storage</div>
            <div>php artisan storage:link</div>
            <br>
            <div style="color:#94a3b8"># 3. Atau manual kalau artisan gagal:</div>
            <div>ln -s /www/wwwroot/senjaweb.gauld.my.id/storage/app/public /www/wwwroot/senjaweb.gauld.my.id/public/storage</div>
            <br>
            <div style="color:#94a3b8"># 4. Fix permission</div>
            <div>chmod -R 755 storage bootstrap/cache</div>
            <div>chown -R www:www storage bootstrap/cache</div>
            <br>
            <div style="color:#94a3b8"># 5. Clear cache</div>
            <div>php artisan optimize:clear</div>
        </div>
    </div>

    {{-- Upload file panduan --}}
    <div style="background:#fffbeb;border:1px solid #fde68a;border-radius:12px;padding:16px 20px">
        <div style="font-size:0.875rem;font-weight:700;color:#92400e;margin-bottom:8px">⚠️ File foto/upload tidak muncul di server?</div>
        <div style="font-size:0.8125rem;color:#78350f;line-height:1.6">
            File yang diupload di <strong>lokal</strong> tidak otomatis ada di server. Ada 2 cara:
            <ol style="margin:8px 0 0 16px;space-y:4px">
                <li><strong>Manual:</strong> Upload folder <code>storage/app/public</code> dari lokal ke server via FTP/aaPanel File Manager ke path: <code>/www/wwwroot/senjaweb.gauld.my.id/storage/app/public/</code></li>
                <li><strong>Otomatis:</strong> Gunakan cloud storage (S3/Cloudflare R2) agar file langsung tersimpan di cloud — tidak perlu sync manual.</li>
            </ol>
        </div>
    </div>

</div>
</x-filament-panels::page>
