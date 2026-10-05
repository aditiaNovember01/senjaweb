<?php

namespace App\Console\Commands;

use App\Models\SiteSetting;
use Illuminate\Console\Command;

class SeedSiteSettings extends Command
{
    protected $signature = 'senja:seed-settings';
    protected $description = 'Seed default site settings';

    public function handle(): void
    {
        $defaults = [
            'org_name'              => 'UKM SENJA',
            'org_tagline'           => 'Wadah kolaboratif mahasiswa dalam mengeksplorasi minat bakat di bidang kreativitas dan seni.',
            'org_description'       => 'Unit Kegiatan Mahasiswa SENJA (Seni, Edukasi, dan Kreasi Nyata) adalah wadah kolaboratif mahasiswa dalam mengeksplorasi minat bakat di bidang kreativitas, kepemimpinan, dan karya inovatif mahasiswa.',
            'org_vision'            => 'Menjadi wadah seni dan kreativitas mahasiswa yang unggul, inovatif, dan berdampak positif bagi komunitas kampus serta masyarakat luas.',
            'org_mission'           => "1. Mengembangkan potensi seni mahasiswa melalui program kerja terstruktur.\n2. Membangun kolaborasi aktif antar divisi dan antar UKM.\n3. Menjadi representasi mahasiswa dalam kegiatan seni budaya.\n4. Mendampingi pertumbuhan soft-skill dan kepemimpinan anggota.",
            'contact_address'       => 'Gedung UKM, Lt. 2, Universitas Jayanusa',
            'contact_email'         => 'senja@jayanusa.ac.id',
            'contact_phone'         => '',
            'social_instagram'      => '@ukm.senja',
            'social_instagram_url'  => 'https://instagram.com/ukm.senja',
            'social_youtube'        => '',
            'social_tiktok'         => '',
            'footer_commitment'     => 'Mendorong kreasi mahasiswa yang berkolaborasi, inovatif, dan berdampak bagi komunitas kampus.',
            'hero_title_line1'      => 'Mewadahi Inspirasi,',
            'hero_title_line2'      => 'Mengembangkan Potensi',
            'hero_title_highlight'  => 'Tanpa Batas',
            'hero_title_suffix'     => 'di UKM SENJA',
        ];

        foreach ($defaults as $key => $value) {
            SiteSetting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        SiteSetting::clearAllCache();

        $this->info('Site settings seeded: ' . count($defaults) . ' records.');
    }
}
