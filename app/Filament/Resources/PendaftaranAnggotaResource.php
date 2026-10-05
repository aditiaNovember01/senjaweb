<?php

namespace App\Filament\Resources;

use App\Filament\Concerns\HasRoleBasedAccess;
use App\Filament\Resources\PendaftaranAnggotaResource\Pages;
use App\Models\Anggota;
use App\Models\Divisi;
use App\Models\PendaftaranAnggota;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Infolists\Components\Actions\Action as InfolistAction;
use Filament\Infolists\Components\Actions;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;

class PendaftaranAnggotaResource extends Resource
{
    use HasRoleBasedAccess;
    protected static ?string $model = PendaftaranAnggota::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Keanggotaan';

    protected static ?string $navigationLabel = 'Pendaftaran Masuk';

    protected static ?string $modelLabel = 'Pendaftaran';

    protected static ?string $pluralModelLabel = 'Pendaftaran Masuk';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('nama_lengkap')->label('Nama Lengkap')->required()->maxLength(100),
            TextInput::make('nim')->label('NIM')->required()->unique(ignoreRecord: true),
            TextInput::make('email')->label('Email')->email()->required()->unique(ignoreRecord: true),
            TextInput::make('nomor_telepon')->label('Nomor Telepon')->required()->maxLength(20),
            Select::make('divisi_id')->label('Divisi')->options(Divisi::pluck('nama', 'id'))->required(),
            Textarea::make('prestasi')->label('Prestasi')->rows(3)->nullable()->columnSpanFull(),
            FileUpload::make('berkas')
                ->label('Berkas Pendukung')
                ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png'])
                ->maxSize(5120)
                ->directory('berkas-pendaftaran')
                ->nullable()
                ->columnSpanFull(),
        ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            // ── Tombol cetak di bagian atas ──────────────────────────────────
            Actions::make([
                InfolistAction::make('cetak')
                    ->label('🖨️ Cetak Bukti Pendaftaran')
                    ->color('primary')
                    ->icon('heroicon-o-printer')
                    ->url(fn ($record): string => route('print.pendaftaran', $record->id))
                    ->openUrlInNewTab(),
            ])->columnSpanFull(),

            TextEntry::make('nama_lengkap')->label('Nama Lengkap'),
            TextEntry::make('nim')->label('NIM'),
            TextEntry::make('email')->label('Email'),
            TextEntry::make('nomor_telepon')->label('Nomor Telepon'),
            TextEntry::make('divisi.nama')->label('Divisi'),
            TextEntry::make('prestasi')->label('Prestasi')->placeholder('—')->columnSpanFull(),

            // Berkas — tampilkan sebagai gambar jika image, link jika PDF
            ImageEntry::make('berkas')
                ->label('Berkas Pendukung (Gambar)')
                ->height(300)
                ->columnSpanFull()
                ->visible(fn ($record): bool =>
                    filled($record->berkas) &&
                    ! str_ends_with(strtolower($record->berkas), '.pdf')
                ),

            TextEntry::make('berkas')
                ->label('Berkas Pendukung (PDF)')
                ->columnSpanFull()
                ->visible(fn ($record): bool =>
                    filled($record->berkas) &&
                    str_ends_with(strtolower($record->berkas), '.pdf')
                )
                ->formatStateUsing(fn ($state): string =>
                    '<a href="' . \Storage::url($state) . '" target="_blank" '
                    . 'class="text-primary-600 underline font-medium flex items-center gap-1">'
                    . '📄 Buka / Download PDF</a>'
                )
                ->html(),

            TextEntry::make('berkas')
                ->label('Berkas Pendukung')
                ->placeholder('Tidak ada berkas')
                ->columnSpanFull()
                ->visible(fn ($record): bool => ! filled($record->berkas)),

            TextEntry::make('created_at')->label('Tanggal Daftar')->dateTime('d M Y, H:i'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_lengkap')->label('Nama')->searchable()->sortable(),
                TextColumn::make('nim')->label('NIM')->searchable(),
                TextColumn::make('email')->label('Email')->searchable()->toggleable(),
                TextColumn::make('divisi.nama')->label('Divisi')->sortable(),
                TextColumn::make('prestasi')->label('Prestasi')->limit(40)->toggleable(),
                TextColumn::make('created_at')->label('Daftar')->dateTime('d M Y')->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                ViewAction::make(),

                Action::make('approve')
                    ->label('Setujui')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Setujui Pendaftaran')
                    ->modalDescription('Anggota baru akan dibuat dari data pendaftaran ini dengan status Anggota Aktif.')
                    ->action(function (PendaftaranAnggota $record): void {
                        Anggota::create([
                            'nama_lengkap'      => $record->nama_lengkap,
                            'nim'               => $record->nim,
                            'email'             => $record->email,
                            'nomor_telepon'     => $record->nomor_telepon,
                            'divisi_id'         => $record->divisi_id,
                            'angkatan_id'       => null,
                            'tanggal_bergabung' => now(),
                            'status'            => 'Anggota Aktif',
                        ]);
                        $record->delete();
                        Notification::make()->title('Pendaftaran disetujui — anggota baru berhasil dibuat.')->success()->send();
                    }),

                Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Tolak Pendaftaran')
                    ->modalDescription('Data pendaftaran akan dihapus permanen dan tidak dapat dikembalikan.')
                    ->action(function (PendaftaranAnggota $record): void {
                        $record->delete();
                        Notification::make()->title('Pendaftaran ditolak.')->danger()->send();
                    }),
            ])
            ->bulkActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPendaftaranAnggotas::route('/'),
        ];
    }
}
