<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Seed the admin accounts.
     */
    public function run(): void
    {
        // Super Admin
        User::updateOrCreate(
            ['email' => 'aditianovirman@senja.ac.id'],
            [
                'name'      => 'Aditiano Virman',
                'password'  => bcrypt('aditganteng'),
                'role'      => 'super_admin',
                'is_active' => true,
            ]
        );

        // Ketua (can manage users, cannot assign super_admin role)
        User::updateOrCreate(
            ['email' => 'ketua@senja.ac.id'],
            [
                'name'      => 'Ketua SENJA',
                'password'  => bcrypt('ketua123'),
                'role'      => 'ketua',
                'is_active' => true,
            ]
        );
    }
}
