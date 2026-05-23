<?php

namespace Database\Seeders;

use App\Models\Level;
use App\Models\Poin;
use App\Models\ResetLevel;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'freelancer']);

        $admin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('sayaAdmin'),
            'status_akun' => 'aktif',
            'no_telp' => 'admin',
        ]);

        $admin->assignRole('admin');

        $dataLevel = [
            [
                'nama_level' => 1,
                'min_poin' => '0',
                'delay_notifikasi' => 5,
            ],
            [
                'nama_level' => 2,
                'min_poin' => '500',
                'delay_notifikasi' => 3,
            ],
            [
                'nama_level' => 3,
                'min_poin' => '1500',
                'delay_notifikasi' => 2,
            ],
            [
                'nama_level' => 4,
                'min_poin' => '3500',
                'delay_notifikasi' => 1,
            ],
            [
                'nama_level' => 5,
                'min_poin' => '7000',
                'delay_notifikasi' => 0,
            ]
        ];

        foreach ($dataLevel as $itemLevel) {
            Level::create([
                'nama_level' => $itemLevel['nama_level'],
                'slug' => Str::slug('level ' . $itemLevel['nama_level']),
                'min_poin' => $itemLevel['min_poin'],
                'delay_notifikasi' => $itemLevel['delay_notifikasi'],
            ]);
        }

        $data = [
            [
                'aspek' => 'ketepatan',
                'bobot' => 0.4,
            ],
            [
                'aspek' => 'tepat waktu',
                'bobot' => 0.35,
            ],
            [
                'aspek' => 'kualitas',
                'bobot' => 0.25,
            ]
        ];

        foreach ($data as $item) {

            Poin::create([
                'aspek' => $item['aspek'],
                'slug' => Str::slug($item['aspek']),
                'bobot' => $item['bobot'],
                'status' => 'aktif'
            ]);
        }

        ResetLevel::create([
            'status' => 'nonaktif',
            'lama_hari' => 30,
            'jam_reset' => '00:00:00',
            'last_reset_at' => Carbon::now()
        ]);
    }
}
