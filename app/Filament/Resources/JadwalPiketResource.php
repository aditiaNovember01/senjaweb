<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JadwalPiketResource\Pages;
use App\Models\Anggota;
use App\Models\JadwalPiket;
use App\Models\LaporanPiket;
use Carbon\Carbon;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section as InfoSection;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;

class JadwalPiketResource extends Resource
{
    protected static ?string $model = JadwalPiket::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Piket';

    protected static ?string $navigationLabel = 'Jadwal Piket';

    protected static ?string $modelLabel = 'Jadwal Piket';

    protected static ?string $pluralModelLabel = 'Jadwal Piket';

    protected static ?int $navigationSort = 1;

    // ── Authorization ──────────────────────────────────────────────────────────

    public static function canViewAny(): bool
    {
        return auth()->check();
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->canManagePiket() ?? false;
    }

    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return auth()->user()?->canManagePiket() ?? false;
    }

    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return auth()->user()?->canManagePiket() ?? false;
    }

    public static function canDeleteAny(): bool
    {
        return auth()->user()?->canManagePiket() ?? false;
    }

    // ── Form ──────────────────────────────────────────────────────────────────

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Pengaturan Hari & Jam')
                ->columns(2)
                ->schema([
                    Select::make('hari_ke')
                        ->label('Hari Piket')
                        ->options(JadwalPiket::$hariOptions)
                        ->required()
                        ->native(false)
                        ->unique(ignoreRecord: true)
                        ->validationMessages([
                            'unique' => 'Hari ini sudah ada jadwal piket. Satu hari hanya boleh satu jadwal.',
                        ])
                        ->helperText('Berlaku setiap minggu pada hari yang dipilih.'),

                    Toggle::make('is_aktif')
                        ->label('Jadwal Aktif')
                        ->default(true)
                        ->helperText('Nonaktifkan untuk meliburkan tanpa menghapus data.'),

                    TimePicker::make('jam_mulai')
                        ->label('Jam Mulai Piket')
                        ->required()
                        ->default('07:00')
                        ->seconds(false),

                    TimePicker::make('batas_upload')
                        ->label('Batas Upload Bukti')
                        ->required()
                        ->default('10:00')
                        ->seconds(false)
                        ->helperText('Upload setelah jam ini → status Terlambat.'),

                    Textarea::make('keterangan')
                        ->label('Keterangan')
                        ->rows(2)
                        ->nullable()
                        ->columnSpanFull(),
                ]),

            Section::make('Anggota Bertugas')
                ->description('Pilih anggota yang rutin bertugas setiap hari ini. Ketua & Pembina tidak perlu ditambahkan.')
                ->schema([
                    CheckboxList::make('anggotas')
                        ->label('')
                        ->relationship('anggotas', 'nama_lengkap')
                        ->options(
                            Anggota::whereIn('status', ['Anggota Aktif', 'Anggota Pasif', 'Anggota Kehormatan'])
                                ->orderBy('nama_lengkap')
                                ->get()
                                ->mapWithKeys(fn ($a) => [
                                    $a->id => "{$a->nama_lengkap} ({$a->nim})",
                                ])
                        )
                        ->searchable()
                        ->bulkToggleable()
                        ->columns(2)
                        ->gridDirection('row'),
                ]),
        ]);
    }

    // ── Infolist ──────────────────────────────────────────────────────────────

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            InfoSection::make('Detail Jadwal')
                ->columns(2)
                ->schema([
                    TextEntry::make('nama_hari')
                        ->label('Hari'),
                    TextEntry::make('jam_mulai')
                        ->label('Jam Mulai'),
                    TextEntry::make('batas_upload')
                        ->label('Batas Upload'),
                    TextEntry::make('is_aktif')
                        ->label('Status')
                        ->formatStateUsing(fn ($state) => $state ? 'Aktif' : 'Nonaktif')
                        ->badge()
                        ->color(fn ($state) => $state ? 'success' : 'danger'),
                    TextEntry::make('keterangan')
                        ->label('Keterangan')
                        ->placeholder('—')
                        ->columnSpanFull(),
                ]),

            InfoSection::make('Anggota Bertugas')
                ->schema([
                    RepeatableEntry::make('anggotas')
                        ->label('')
                        ->schema([
                            TextEntry::make('nama_lengkap')->label('Nama'),
                            TextEntry::make('nim')->label('NIM'),
                            TextEntry::make('divisi.nama')->label('Divisi'),
                        ])
                        ->columns(3),
                ]),
        ]);
    }

    // ── Table ─────────────────────────────────────────────────────────────────

    public static function table(Table $table): Table
    {
        $isAdmin   = auth()->user()?->canManagePiket() ?? false;
        $isAnggota = auth()->user()?->isAnggota() ?? false;
        $anggotaId = auth()->user()?->anggota_id;
        $hariIni   = now()->dayOfWeek;
        $today     = now()->toDateString();

        return $table
            ->modifyQueryUsing(function ($query) use ($isAnggota, $anggotaId) {
                // Anggota hanya lihat jadwal yang ditugaskan ke mereka
                if ($isAnggota && $anggotaId) {
                    $query->whereHas('anggotas', fn ($q) => $q->where('anggotas.id', $anggotaId));
                }
            })
            ->columns([
                TextColumn::make('nama_hari')
                    ->label('Hari')
                    ->badge()
                    ->color(fn (JadwalPiket $record): string =>
                        $record->hari_ke === $hariIni ? 'warning' : 'gray'
                    )
                    ->sortable(query: fn ($query, $direction) => $query->orderBy('hari_ke', $direction)),

                TextColumn::make('jam_mulai')
                    ->label('Jam Mulai'),

                TextColumn::make('batas_upload')
                    ->label('Batas Upload'),

                TextColumn::make('anggotas_count')
                    ->label('Peserta')
                    ->counts('anggotas'),

                // Untuk admin: berapa sudah upload hari ini
                TextColumn::make('laporan_hari_ini')
                    ->label('Upload Hari Ini')
                    ->visible(fn () => $isAdmin)
                    ->getStateUsing(fn (JadwalPiket $record): string =>
                        $record->laporanPikets()->where('tanggal_laporan', $today)->count()
                        . ' / ' . $record->anggotas()->count()
                    ),

                // Untuk anggota: status diri sendiri hari ini
                TextColumn::make('status_saya')
                    ->label('Status Hari Ini')
                    ->visible(fn () => $isAnggota)
                    ->badge()
                    ->getStateUsing(function (JadwalPiket $record) use ($anggotaId, $today, $hariIni): string {
                        if (! $anggotaId) return '—';
                        // Hanya tampilkan status kalau hari ini adalah hari jadwal ini
                        if ($record->hari_ke !== $hariIni) return 'Bukan Hari Ini';
                        return $record->statusAnggota($anggotaId, $today);
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'Tepat Waktu'    => 'success',
                        'Terlambat'      => 'warning',
                        'Tidak Hadir'    => 'danger',
                        'Belum Upload'   => 'info',
                        default          => 'gray',
                    }),

                IconColumn::make('is_aktif')
                    ->label('Aktif')
                    ->boolean()
                    ->visible(fn () => $isAdmin),
            ])
            ->defaultSort('hari_ke')
            ->actions([
                ViewAction::make(),

                // Upload bukti — hanya anggota, hanya hari ini, belum upload
                Action::make('upload_bukti')
                    ->label('Upload Bukti')
                    ->icon('heroicon-o-camera')
                    ->color('primary')
                    ->visible(function (JadwalPiket $record) use ($isAnggota, $anggotaId, $hariIni, $today): bool {
                        if (! $isAnggota || ! $anggotaId) return false;
                        if (! $record->is_aktif) return false;
                        // Hanya hari ini
                        if ($record->hari_ke !== $hariIni) return false;
                        // Harus terdaftar di jadwal ini
                        if (! $record->anggotas->contains($anggotaId)) return false;
                        // Belum upload hari ini
                        return ! $record->laporanPikets()
                            ->where('anggota_id', $anggotaId)
                            ->where('tanggal_laporan', $today)
                            ->exists();
                    })
                    ->url(fn (JadwalPiket $record): string =>
                        LaporanPiketResource::getUrl('create', ['jadwal_piket_id' => $record->id])
                    ),

                EditAction::make()->visible(fn () => $isAdmin),
                DeleteAction::make()->visible(fn () => $isAdmin),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->visible(fn () => $isAdmin),
                ]),
            ]);
    }

    // ── Pages ─────────────────────────────────────────────────────────────────

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListJadwalPikets::route('/'),
            'create' => Pages\CreateJadwalPiket::route('/create'),
            'edit'   => Pages\EditJadwalPiket::route('/{record}/edit'),
            'view'   => Pages\ViewJadwalPiket::route('/{record}'),
        ];
    }
}
