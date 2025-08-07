<?php

namespace Database\Seeders;

use App\Models\Siswa;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        Siswa::create([
            'nama' => 'Dimas',
            'kelas' => 'XI PPLG 1',
            'jenis_kelamin' => 'Laki-laki',
            'alamat' => 'Taman Kopo Indah 1',
        ]);
        Siswa::create([
            'nama' => 'Sari',
            'kelas' => 'XI PPLG 2',
            'jenis_kelamin' => 'Perempuan',
            'alamat' => 'Jl. Padasuka',
        ]);
        Siswa::create([
            'nama' => 'Asrul',
            'kelas' => 'XI PPLG 3',
            'jenis_kelamin' => 'Laki-laki',
            'alamat' => 'Jl. Merdeka',
        ]);
    }
}
