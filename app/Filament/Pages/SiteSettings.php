<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class SiteSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string $view = 'filament.pages.site-settings';

    protected static ?string $navigationLabel = 'Pengaturan Website';

    protected static ?string $title = 'Pengaturan Website';

    protected static ?string $navigationGroup = 'Pengaturan';

    protected static ?int $navigationSort = 99;

    public static function canAccess(): bool
    {
        return auth()->user()?->canManageSiteSettings() ?? false;
    }

    // State array untuk form — wajib pakai array + statePath('data')
    public array $data = [];

    public function mount(): void
    {
        $settings = SiteSetting::all_settings();

        $this->data = [
            'org_name'              => $settings['org_name']              ?? 'UKM SENJA',
            'org_tagline'           => $settings['org_tagline']           ?? 'Wadah kolaboratif mahasiswa dalam mengeksplorasi minat bakat di bidang kreativitas dan seni.',
            'org_description'       => $settings['org_description']       ?? 'Unit Kegiatan Mahasiswa SENJA adalah wadah kolaboratif mahasiswa dalam mengeksplorasi minat bakat di bidang kreativitas, kepemimpinan, dan karya inovatif mahasiswa.',
            'org_vision'            => $settings['org_vision']            ?? 'Menjadi wadah seni dan kreativitas mahasiswa yang unggul, inovatif, dan berdampak positif bagi komunitas kampus serta masyarakat luas.',
            'org_mission'           => $settings['org_mission']           ?? "1. Mengembangkan potensi seni mahasiswa melalui program kerja terstruktur.\n2. Membangun kolaborasi aktif antar divisi dan antar UKM.\n3. Menjadi representasi mahasiswa dalam kegiatan seni budaya.\n4. Mendampingi pertumbuhan soft-skill dan kepemimpinan anggota.",
            'contact_address'       => $settings['contact_address']       ?? 'Gedung UKM, Lt. 2, Universitas Jayanusa',
            'contact_email'         => $settings['contact_email']         ?? 'senja@jayanusa.ac.id',
            'contact_phone'         => $settings['contact_phone']         ?? '',
            'social_instagram'      => $settings['social_instagram']      ?? '@ukm.senja',
            'social_instagram_url'  => $settings['social_instagram_url']  ?? 'https://instagram.com/ukm.senja',
            'social_youtube'        => $settings['social_youtube']        ?? '',
            'social_tiktok'         => $settings['social_tiktok']         ?? '',
            'footer_commitment'     => $settings['footer_commitment']     ?? 'Mendorong kreasi mahasiswa yang berkolaborasi, inovatif, dan berdampak bagi komunitas kampus.',
            'hero_title_line1'      => $settings['hero_title_line1']      ?? 'Mewadahi Inspirasi,',
            'hero_title_line2'      => $settings['hero_title_line2']      ?? 'Mengembangkan Potensi',
            'hero_title_highlight'  => $settings['hero_title_highlight']  ?? 'Tanpa Batas',
            'hero_title_suffix'     => $settings['hero_title_suffix']     ?? 'di UKM SENJA',
            'foto_pengurus'         => $settings['foto_pengurus']         ?? '',
            'tag_foto_pengurus'     => $settings['tag_foto_pengurus']     ?? 'Pengurus UKM SENJA 2026-2027',
            // Piket geolocation
            'piket_lat'             => $settings['piket_lat']             ?? '',
            'piket_lng'             => $settings['piket_lng']             ?? '',
            'piket_radius_meter'    => $settings['piket_radius_meter']    ?? '50',
        ];

        $this->form->fill($this->data);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Identitas Organisasi')
                    ->description('Nama, tagline, dan deskripsi singkat organisasi.')
                    ->icon('heroicon-o-building-library')
                    ->schema([
                        TextInput::make('org_name')
                            ->label('Nama Organisasi')
                            ->required()
                            ->maxLength(100),

                        TextInput::make('org_tagline')
                            ->label('Tagline / Slogan')
                            ->maxLength(200)
                            ->columnSpanFull(),

                        Textarea::make('org_description')
                            ->label('Deskripsi Singkat')
                            ->rows(3)
                            ->maxLength(500)
                            ->columnSpanFull()
                            ->helperText('Maksimal 500 karakter. Tampil di hero section beranda.'),

                        Textarea::make('org_vision')
                            ->label('Visi')
                            ->rows(3)
                            ->columnSpanFull(),

                        Textarea::make('org_mission')
                            ->label('Misi')
                            ->rows(5)
                            ->columnSpanFull()
                            ->helperText('Pisahkan setiap poin misi dengan baris baru dimulai angka (1. 2. dst).'),
                    ])
                    ->columns(2),

                Section::make('Kontak & Lokasi')
                    ->description('Informasi kontak yang tampil di footer website.')
                    ->icon('heroicon-o-map-pin')
                    ->schema([
                        TextInput::make('contact_address')
                            ->label('Alamat Sekretariat')
                            ->maxLength(255)
                            ->columnSpanFull(),

                        TextInput::make('contact_email')
                            ->label('Email')
                            ->email()
                            ->maxLength(100),

                        TextInput::make('contact_phone')
                            ->label('Nomor Telepon / WhatsApp')
                            ->tel()
                            ->maxLength(20),
                    ])
                    ->columns(2),

                Section::make('Media Sosial')
                    ->description('Akun media sosial organisasi.')
                    ->icon('heroicon-o-globe-alt')
                    ->schema([
                        TextInput::make('social_instagram')
                            ->label('Username Instagram')
                            ->placeholder('@ukm.senja')
                            ->maxLength(50),

                        TextInput::make('social_instagram_url')
                            ->label('URL Instagram')
                            ->url()
                            ->placeholder('https://instagram.com/ukm.senja')
                            ->maxLength(200),

                        TextInput::make('social_youtube')
                            ->label('Channel YouTube')
                            ->maxLength(100),

                        TextInput::make('social_tiktok')
                            ->label('Username TikTok')
                            ->maxLength(50),
                    ])
                    ->columns(2),

                Section::make('Foto Pengurus Beranda')
                    ->description('Foto pengurus yang tampil di beranda dengan tag periode.')
                    ->icon('heroicon-o-user-group')
                    ->schema([
                        FileUpload::make('foto_pengurus')
                            ->label('Foto Pengurus')
                            ->image()
                            ->maxSize(5120)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->directory('beranda')
                            ->columnSpanFull()
                            ->helperText('Upload foto pengurus yang akan tampil di beranda. Maksimal 5MB.'),

                        TextInput::make('tag_foto_pengurus')
                            ->label('Tag / Keterangan Foto')
                            ->placeholder('Pengurus UKM SENJA 2026-2027')
                            ->maxLength(100)
                            ->columnSpanFull()
                            ->helperText('Teks yang tampil sebagai tag di bawah foto.'),
                    ])
                    ->columns(1),

                Section::make('Teks Hero & Footer')
                    ->description('Teks yang tampil di bagian hero beranda dan footer.')
                    ->icon('heroicon-o-pencil-square')
                    ->schema([
                        TextInput::make('hero_title_line1')
                            ->label('Judul Hero Baris 1')
                            ->placeholder('Mewadahi Inspirasi,'),

                        TextInput::make('hero_title_line2')
                            ->label('Judul Hero Baris 2')
                            ->placeholder('Mengembangkan Potensi'),

                        TextInput::make('hero_title_highlight')
                            ->label('Teks Highlight (Warna Orange)')
                            ->placeholder('Tanpa Batas'),

                        TextInput::make('hero_title_suffix')
                            ->label('Sambungan Setelah Highlight')
                            ->placeholder('di UKM SENJA'),

                        Textarea::make('footer_commitment')
                            ->label('Teks Komitmen Footer')
                            ->rows(3)
                            ->columnSpanFull()
                            ->maxLength(300),
                    ])
                    ->columns(2),

                Section::make('Lokasi Piket (Geolocation)')
                    ->description('Koordinat sekretariat sebagai titik acuan validasi lokasi upload bukti piket.')
                    ->icon('heroicon-o-map-pin')
                    ->schema([
                        TextInput::make('piket_lat')
                            ->label('Latitude Sekretariat')
                            ->placeholder('-0.9035697')
                            ->helperText('Contoh: -0.9035697  (negatif = selatan khatulistiwa)')
                            ->numeric(),

                        TextInput::make('piket_lng')
                            ->label('Longitude Sekretariat')
                            ->placeholder('100.4502360')
                            ->helperText('Contoh: 100.4502360')
                            ->numeric(),

                        TextInput::make('piket_radius_meter')
                            ->label('Radius Maksimal (meter)')
                            ->numeric()
                            ->default(50)
                            ->minValue(10)
                            ->maxValue(5000)
                            ->suffix('meter')
                            ->helperText('Anggota harus berada dalam radius ini dari sekretariat saat upload. Default: 50 meter.'),

                        \Filament\Forms\Components\Placeholder::make('petunjuk_koordinat')
                            ->label('Cara mendapatkan koordinat')
                            ->content('Buka Google Maps → klik kanan pada titik sekretariat → pilih koordinat yang muncul. Format: -0.9035697, 100.4502360')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        foreach ($data as $key => $value) {
            // FileUpload bisa mengembalikan array — ambil elemen pertama jika ada
            if (is_array($value)) {
                $value = !empty($value) ? reset($value) : null;
            }
            SiteSetting::set($key, $value);
        }

        SiteSetting::clearAllCache();

        Notification::make()
            ->title('Pengaturan berhasil disimpan.')
            ->success()
            ->send();
    }
}
