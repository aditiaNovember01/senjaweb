<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\Anggota;
use App\Models\User;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-shield-check';

    protected static ?string $navigationGroup = 'Manajemen';

    protected static ?string $navigationLabel = 'Manajemen Akun';

    protected static ?string $modelLabel = 'Akun';

    protected static ?string $pluralModelLabel = 'Manajemen Akun';

    protected static ?int $navigationSort = 1;

    // ── Visibility & Authorization ─────────────────────────────────────────────

    public static function canViewAny(): bool
    {
        return auth()->user()?->canManageUsers() ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->canManageUsers() ?? false;
    }

    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        $user = auth()->user();

        if (! $user?->canManageUsers()) {
            return false;
        }

        // Ketua (non-super-admin) cannot edit a super_admin account
        if (! $user->isSuperAdmin() && $record->role === 'super_admin') {
            return false;
        }

        return true;
    }

    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        $user = auth()->user();

        if (! $user?->canManageUsers()) {
            return false;
        }

        // Cannot delete your own account
        if ($record->id === $user->id) {
            return false;
        }

        // Ketua cannot delete super_admin accounts
        if (! $user->isSuperAdmin() && $record->role === 'super_admin') {
            return false;
        }

        return true;
    }

    // ── Form ──────────────────────────────────────────────────────────────────

    public static function form(Form $form): Form
    {
        return $form->schema([

            Section::make('Informasi Akun')
                ->columns(2)
                ->schema([
                    TextInput::make('name')
                        ->label('Nama Lengkap')
                        ->required()
                        ->maxLength(255),

                    TextInput::make('email')
                        ->label('Alamat Email')
                        ->email()
                        ->required()
                        ->unique(User::class, 'email', ignoreRecord: true)
                        ->maxLength(255),

                    Select::make('role')
                        ->label('Peran (Role)')
                        ->options(function (): array {
                            // super_admin cannot be created/assigned from the UI ever
                            $options = User::$roleOptions;
                            unset($options['super_admin']);
                            return $options;
                        })
                        ->required()
                        ->default('admin')
                        ->native(false),

                    Toggle::make('is_active')
                        ->label('Akun Aktif')
                        ->default(true)
                        ->helperText('Akun yang tidak aktif tidak bisa login ke panel.'),

                    Select::make('anggota_id')
                        ->label('Tautkan ke Anggota')
                        ->placeholder('— Tidak ditautkan —')
                        ->helperText('Wajib diisi untuk role Anggota.')
                        ->options(
                            Anggota::orderBy('nama_lengkap')
                                ->get()
                                ->mapWithKeys(fn ($a) => [$a->id => "{$a->nama_lengkap} ({$a->nim})"])
                        )
                        ->searchable()
                        ->nullable()
                        ->columnSpanFull(),
                ]),

            Section::make('Kata Sandi')
                ->columns(2)
                ->description(fn ($record) => $record ? 'Kosongkan jika tidak ingin mengubah kata sandi.' : null)
                ->schema([
                    TextInput::make('password')
                        ->label('Kata Sandi')
                        ->password()
                        ->revealable()
                        ->required(fn ($record) => $record === null) // required on create
                        ->minLength(8)
                        ->dehydrateStateUsing(fn ($state) => filled($state) ? bcrypt($state) : null)
                        ->dehydrated(fn ($state) => filled($state))
                        ->helperText('Minimal 8 karakter.'),

                    TextInput::make('password_confirmation')
                        ->label('Konfirmasi Kata Sandi')
                        ->password()
                        ->revealable()
                        ->required(fn ($record) => $record === null)
                        ->same('password')
                        ->dehydrated(false),
                ]),
        ]);
    }

    // ── Table ─────────────────────────────────────────────────────────────────

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('role')
                    ->label('Peran')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => User::$roleOptions[$state] ?? $state)
                    ->color(fn (string $state): string => match ($state) {
                        'super_admin' => 'danger',
                        'ketua'       => 'warning',
                        'sekretaris'  => 'info',
                        'admin'       => 'primary',
                        'anggota'     => 'success',
                        default       => 'gray',
                    })
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('role')
                    ->label('Peran')
                    ->options(User::$roleOptions),

                TernaryFilter::make('is_active')
                    ->label('Status Akun')
                    ->trueLabel('Aktif')
                    ->falseLabel('Nonaktif'),
            ])
            ->actions([
                EditAction::make(),

                // Quick toggle active/inactive
                Action::make('toggle_active')
                    ->label(fn (User $record) => $record->is_active ? 'Nonaktifkan' : 'Aktifkan')
                    ->icon(fn (User $record) => $record->is_active ? 'heroicon-o-lock-closed' : 'heroicon-o-lock-open')
                    ->color(fn (User $record) => $record->is_active ? 'warning' : 'success')
                    ->requiresConfirmation()
                    ->modalHeading(fn (User $record) => $record->is_active ? 'Nonaktifkan Akun?' : 'Aktifkan Akun?')
                    ->modalDescription(fn (User $record) => $record->is_active
                        ? "Akun {$record->name} tidak akan bisa login ke panel."
                        : "Akun {$record->name} akan dapat login kembali ke panel."
                    )
                    ->visible(fn (User $record): bool => $record->id !== auth()->id())
                    ->action(function (User $record) {
                        $record->update(['is_active' => ! $record->is_active]);
                        Notification::make()
                            ->title($record->is_active ? 'Akun diaktifkan' : 'Akun dinonaktifkan')
                            ->success()
                            ->send();
                    }),

                DeleteAction::make()
                    ->visible(fn (User $record): bool => static::canDelete($record)),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->visible(fn (): bool => auth()->user()?->canManageUsers() ?? false),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    // ── Pages ─────────────────────────────────────────────────────────────────

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit'   => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
