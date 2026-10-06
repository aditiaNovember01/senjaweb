<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LaporanPiketResource\Pages;
use App\Models\Anggota;
use App\Models\JadwalPiket;
use App\Models\LaporanPiket;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section as InfoSection;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Illuminate\Database\Eloquent\Builder;

class LaporanPiketResource extends Resource
{
    protected static ?string $model = LaporanPiket::class;

    protected static ?string $navigationIcon = 'heroicon-o-camera';

    protected static ?string $navigationGroup = 'Piket';

    protected static ?string $navigationLabel = 'Laporan Piket';

    protected static ?string $modelLabel = 'Laporan Piket';

    protected static ?string $pluralModelLabel = 'Laporan Piket';

    protected static ?int $navigationSort = 2;

    // ── Authorization ──────────────────────────────────────────────────────────

    public static function canViewAny(): bool
    {
        return auth()->check();
    }

    public static function canCreate(): bool
    {
        // Semua yang punya jadwal piket bisa submit (anggota, pengurus, sekretaris)
        return auth()->user()?->canUploadPiket() ?? false;
    }

    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        $user = auth()->user();
        if (! $user) return false;
        if ($user->isAdmin()) return true;
        return $user->canUploadPiket() && $record->anggota_id === $user->anggota_id;
    }

    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }

    public static function canDeleteAny(): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }

    // ── Form ──────────────────────────────────────────────────────────────────

    public static function form(Form $form): Form
    {
        $user      = auth()->user();
        $isAdmin   = $user?->isAdmin() ?? false;
        $canUpload = $user?->canUploadPiket() ?? false;
        $anggotaId = $user?->anggota_id;
        $hariIni   = now()->dayOfWeek;
        $today     = now()->toDateString();

        return $form->schema([
            Section::make('Piket Hari Ini')
                ->columns(2)
                ->schema([
                    Select::make('jadwal_piket_id')
                        ->label('Jadwal Piket')
                        ->options(function () use ($canUpload, $isAdmin, $anggotaId, $hariIni, $today): array {
                            if ($canUpload && $anggotaId && ! $isAdmin) {
                                // Hanya jadwal hari ini yang ditugaskan & belum laporan hari ini
                                return JadwalPiket::where('hari_ke', $hariIni)
                                    ->where('is_aktif', true)
                                    ->whereHas('anggotas', fn ($q) => $q->where('anggotas.id', $anggotaId))
                                    ->whereDoesntHave('laporanPikets', fn ($q) =>
                                        $q->where('anggota_id', $anggotaId)
                                          ->where('tanggal_laporan', $today)
                                    )
                                    ->get()
                                    ->mapWithKeys(fn ($j) => [
                                        $j->id => "Piket {$j->nama_hari} — Batas {$j->batas_upload}",
                                    ])
                                    ->toArray();
                            }
                            // Admin: semua jadwal aktif
                            return JadwalPiket::where('is_aktif', true)
                                ->orderBy('hari_ke')
                                ->get()
                                ->mapWithKeys(fn ($j) => [
                                    $j->id => "Piket {$j->nama_hari} (Batas {$j->batas_upload})",
                                ])
                                ->toArray();
                        })
                        ->required()
                        ->default(fn () => request()->query('jadwal_piket_id')
                            ? (int) request()->query('jadwal_piket_id')
                            : null
                        )
                        ->disabled(fn ($record) => $record !== null)
                        ->dehydrated(true)
                        ->helperText(fn () => ($canUpload && ! $isAdmin)
                            ? 'Hanya jadwal hari ini yang terdaftar untuk kamu.'
                            : null
                        )
                        ->columnSpanFull(),

                    Select::make('anggota_id')
                        ->label('Anggota')
                        ->options(
                            Anggota::whereIn('status', ['Anggota Aktif', 'Anggota Pasif', 'Anggota Kehormatan'])
                                ->orderBy('nama_lengkap')
                                ->get()
                                ->mapWithKeys(fn ($a) => [$a->id => "{$a->nama_lengkap} ({$a->nim})"])
                        )
                        ->required()
                        ->default(($canUpload && ! $isAdmin) ? $anggotaId : null)
                        ->disabled($canUpload && ! $isAdmin)
                        ->dehydrated(true)
                        ->searchable(),
                ]),

            Section::make('Bukti Foto')
                ->schema([
                    FileUpload::make('foto_bukti')
                        ->label('Foto Bukti Piket')
                        ->helperText('Gunakan tombol kamera di bawah untuk mengambil foto dengan watermark otomatis.')
                        ->image()
                        ->imageResizeMode('contain')
                        ->maxSize(10240)
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                        ->directory('bukti-piket')
                        ->required()
                        ->columnSpanFull()
                        ->id('foto_bukti_input'),

                    Textarea::make('catatan')
                        ->label('Catatan (opsional)')
                        ->rows(2)
                        ->nullable()
                        ->columnSpanFull(),

                    // Hidden fields — diisi oleh JS geolocation di custom view
                    Hidden::make('latitude'),
                    Hidden::make('longitude'),
                ]),
        ]);
    }

    // ── Infolist ──────────────────────────────────────────────────────────────

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            InfoSection::make('Data Laporan')
                ->columns(2)
                ->schema([
                    TextEntry::make('jadwalPiket.nama_hari')->label('Hari Piket'),
                    TextEntry::make('tanggal_laporan')->label('Tanggal')->date('l, d F Y'),
                    TextEntry::make('jadwalPiket.batas_upload')->label('Batas Upload'),
                    TextEntry::make('uploaded_at')
                        ->label('Waktu Upload')
                        ->dateTime('d M Y, H:i:s')
                        ->timezone(config('app.timezone', 'Asia/Jakarta')),
                    TextEntry::make('anggota.nama_lengkap')->label('Anggota'),
                    TextEntry::make('anggota.nim')->label('NIM'),
                    TextEntry::make('status_kehadiran')
                        ->label('Status')
                        ->badge()
                        ->color(fn (string $state): string => match ($state) {
                            'Tepat Waktu' => 'success',
                            'Terlambat'   => 'warning',
                            'Tidak Hadir' => 'danger',
                            default       => 'gray',
                        }),
                    TextEntry::make('label_keterlambatan')->label('Detail Keterlambatan'),
                    TextEntry::make('catatan')->label('Catatan')->placeholder('—')->columnSpanFull(),
                    TextEntry::make('label_lokasi')
                        ->label('Lokasi Upload')
                        ->badge()
                        ->color(fn ($record) => $record->lokasi_valid ? 'success' : 'danger')
                        ->placeholder('Tidak ada data GPS'),
                ]),

            InfoSection::make('Foto Bukti')
                ->schema([
                    ImageEntry::make('foto_bukti')
                        ->label('')
                        ->height(320)
                        ->columnSpanFull(),
                ]),
        ]);
    }

    // ── Table ─────────────────────────────────────────────────────────────────

    public static function table(Table $table): Table
    {
        $isAdmin      = auth()->user()?->isAdmin() ?? false;
        $canUpload    = auth()->user()?->canUploadPiket() ?? false;
        $anggotaId    = auth()->user()?->anggota_id;
        // Non-admin uploaders see only their own laporan
        $ownOnly      = $canUpload && ! $isAdmin;

        return $table
            ->modifyQueryUsing(function (Builder $query) use ($ownOnly, $anggotaId) {
                if ($ownOnly && $anggotaId) {
                    $query->where('anggota_id', $anggotaId);
                }
            })
            ->columns([
                TextColumn::make('tanggal_laporan')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('jadwalPiket.nama_hari')
                    ->label('Hari')
                    ->badge()
                    ->color('gray'),

                TextColumn::make('anggota.nama_lengkap')
                    ->label('Anggota')
                    ->searchable()
                    ->visible(fn () => ! $ownOnly),

                TextColumn::make('anggota.nim')
                    ->label('NIM')
                    ->visible(fn () => ! $ownOnly),

                ImageColumn::make('foto_bukti')
                    ->label('Foto')
                    ->height(44)
                    ->width(60),

                TextColumn::make('uploaded_at')
                    ->label('Upload Jam')
                    ->dateTime('H:i:s')
                    ->sortable(),

                TextColumn::make('status_kehadiran')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Tepat Waktu' => 'success',
                        'Terlambat'   => 'warning',
                        'Tidak Hadir' => 'danger',
                        default       => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('label_keterlambatan')
                    ->label('Keterlambatan')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('tanggal_laporan', 'desc')
            ->filters([
                SelectFilter::make('status_kehadiran')
                    ->label('Status')
                    ->options([
                        'Tepat Waktu' => 'Tepat Waktu',
                        'Terlambat'   => 'Terlambat',
                        'Tidak Hadir' => 'Tidak Hadir',
                    ]),
            ])
            ->actions([
                ViewAction::make(),
                DeleteAction::make()
                    ->visible(fn () => $isAdmin),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->visible(fn () => $isAdmin),
                ]),
            ]);
    }

    // ── Pages ─────────────────────────────────────────────────────────────────

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListLaporanPikets::route('/'),
            'create' => Pages\CreateLaporanPiket::route('/create'),
            'view'   => Pages\ViewLaporanPiket::route('/{record}'),
        ];
    }
}
