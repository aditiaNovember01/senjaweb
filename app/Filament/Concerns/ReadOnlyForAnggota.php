<?php

namespace App\Filament\Concerns;

/**
 * Mixin untuk Filament Resources yang bisa dilihat semua role
 * tapi hanya bisa dikelola oleh admin ke atas.
 * Anggota hanya bisa lihat (viewAny + view), tidak bisa create/edit/delete.
 */
trait ReadOnlyForAnggota
{
    public static function canViewAny(): bool
    {
        return auth()->check();
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }

    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }

    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }

    public static function canDeleteAny(): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }
}
