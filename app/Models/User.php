<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'anggota_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_active'         => 'boolean',
        ];
    }

    // ── Role Options ───────────────────────────────────────────────────────────

    /** Roles for dropdowns */
    public static array $roleOptions = [
        'super_admin' => 'Super Admin',
        'ketua'       => 'Ketua',
        'sekretaris'  => 'Sekretaris',
        'admin'       => 'Pengurus',
        'anggota'     => 'Anggota',
    ];

    // ── Relationships ──────────────────────────────────────────────────────────

    public function anggota(): BelongsTo
    {
        return $this->belongsTo(Anggota::class);
    }

    // ── Panel Access ───────────────────────────────────────────────────────────

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->is_active && in_array($this->role, array_keys(self::$roleOptions));
    }

    // ── Role Checks ────────────────────────────────────────────────────────────

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isKetua(): bool
    {
        return in_array($this->role, ['super_admin', 'ketua']);
    }

    public function isSekretaris(): bool
    {
        return in_array($this->role, ['super_admin', 'ketua', 'sekretaris']);
    }

    /**
     * Can manage (create/edit/delete) most data.
     * Covers: super_admin, ketua, sekretaris, admin (pengurus).
     */
    public function isAdmin(): bool
    {
        return in_array($this->role, ['super_admin', 'ketua', 'sekretaris', 'admin']);
    }

    /**
     * Is a regular anggota (limited access).
     */
    public function isAnggota(): bool
    {
        return $this->role === 'anggota';
    }

    /**
     * Can manage piket schedules (create/edit/delete jadwal piket).
     * Admin + ketua + sekretaris + super_admin.
     */
    public function canManagePiket(): bool
    {
        return $this->isAdmin();
    }

    /**
     * Can manage user accounts (create/edit/delete users).
     * Only super_admin and ketua.
     */
    public function canManageUsers(): bool
    {
        return $this->isKetua();
    }

    /**
     * Can manage website settings & gallery.
     * Only super_admin and ketua.
     */
    public function canManageSiteSettings(): bool
    {
        return $this->isKetua();
    }

    /**
     * Can upload laporan piket.
     * Everyone except super_admin, ketua, and role without anggota_id link.
     * Practically: sekretaris, admin (pengurus), anggota — all who have a duty.
     */
    public function canUploadPiket(): bool
    {
        return in_array($this->role, ['sekretaris', 'admin', 'anggota']) && $this->anggota_id !== null;
    }
}
