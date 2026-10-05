<?php

namespace App\Filament\Concerns;

/**
 * Mixin untuk Filament Resources.
 * Batasi aksi create/edit/delete ke role admin ke atas.
 * Role 'anggota' hanya bisa viewAny (baca saja).
 */
trait HasRoleBasedAccess
{
    public static function canViewAny(): bool
    {
        return auth()->user()?->isAdmin() ?? false;
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
