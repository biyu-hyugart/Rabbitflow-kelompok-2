<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $roles = Role::pluck('id', 'nama_role');

        $accounts = [
            [
                'username' => 'hendra_owner',
                'nama_lengkap' => 'Hendra Prasetya',
                'role' => Role::OWNER,
                'password' => 'rahasia123',
                'status_aktif' => true,
            ],
            [
                'username' => 'budi_kandang',
                'nama_lengkap' => 'Budi Santoso',
                'role' => Role::KARYAWAN,
                'password' => 'karyawan123',
                'status_aktif' => true,
            ],
            [
                'username' => 'siti_logistik',
                'nama_lengkap' => 'Siti Rahmawati',
                'role' => Role::KARYAWAN,
                'password' => 'karyawan123',
                'status_aktif' => true,
            ],
            [
                'username' => 'dedi_aris',
                'nama_lengkap' => 'Dedi Arisandi',
                'role' => Role::KARYAWAN,
                'password' => 'karyawan123',
                'status_aktif' => false,
            ],
        ];

        foreach ($accounts as $account) {
            User::updateOrCreate(
                ['username' => $account['username']],
                [
                    'role_id' => $roles[$account['role']],
                    'nama_lengkap' => $account['nama_lengkap'],
                    'password' => $account['password'],
                    'status_aktif' => $account['status_aktif'],
                ]
            );
        }
    }
}
