<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Peserta;
use Illuminate\Support\Facades\Hash;

class PesertaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat 4 peserta sample
        $pesertaData = [
            [
                'nama_lengkap' => 'Ahmad Rizki',
                'tempat_lahir' => 'Jakarta',
                'tanggal_lahir' => '1990-05-15',
                'jenis_kelamin' => 'L',
                'alamat_rumah' => 'Jl. Sudirman No. 123, Jakarta Pusat',
                'nomor_telepon' => '081234567890',
                'email' => 'ahmad.rizki@email.com',
                'instansi' => 'PT Maju Bersama',
                'jabatan_saat_ini' => 'Manager Marketing',
                'grade' => 'IV',
                'pin' => '123456'
            ],
            [
                'nama_lengkap' => 'Siti Nurhaliza',
                'tempat_lahir' => 'Bandung',
                'tanggal_lahir' => '1988-12-20',
                'jenis_kelamin' => 'P',
                'alamat_rumah' => 'Jl. Asia Afrika No. 45, Bandung',
                'nomor_telepon' => '081234567891',
                'email' => 'siti.nurhaliza@email.com',
                'instansi' => 'PT Sukses Mandiri',
                'jabatan_saat_ini' => 'Supervisor HR',
                'grade' => 'III',
                'pin' => '234567'
            ],
            [
                'nama_lengkap' => 'Budi Santoso',
                'tempat_lahir' => 'Surabaya',
                'tanggal_lahir' => '1992-08-10',
                'jenis_kelamin' => 'L',
                'alamat_rumah' => 'Jl. Tunjungan No. 67, Surabaya',
                'nomor_telepon' => '081234567892',
                'email' => 'budi.santoso@email.com',
                'instansi' => 'PT Jaya Abadi',
                'jabatan_saat_ini' => 'Team Leader IT',
                'grade' => 'III',
                'pin' => '345678'
            ],
            [
                'nama_lengkap' => 'Dewi Sartika',
                'tempat_lahir' => 'Semarang',
                'tanggal_lahir' => '1989-03-25',
                'jenis_kelamin' => 'P',
                'alamat_rumah' => 'Jl. Pandanaran No. 89, Semarang',
                'nomor_telepon' => '081234567893',
                'email' => 'dewi.sartika@email.com',
                'instansi' => 'PT Berkah Sejahtera',
                'jabatan_saat_ini' => 'Senior Analyst',
                'grade' => 'IV',
                'pin' => '456789'
            ]
        ];

        foreach ($pesertaData as $data) {
            // Buat user
            $user = User::create([
                'name' => $data['nama_lengkap'],
                'email' => $data['email'],
                'password' => Hash::make('password123'),
                'role' => 'peserta'
            ]);

            // Buat peserta
            Peserta::create([
                'user_id' => $user->id,
                'nama_lengkap' => $data['nama_lengkap'],
                'tempat_lahir' => $data['tempat_lahir'],
                'tanggal_lahir' => $data['tanggal_lahir'],
                'jenis_kelamin' => $data['jenis_kelamin'],
                'alamat_rumah' => $data['alamat_rumah'],
                'nomor_telepon' => $data['nomor_telepon'],
                'email' => $data['email'],
                'instansi' => $data['instansi'],
                'jabatan_saat_ini' => $data['jabatan_saat_ini'],
                'grade' => $data['grade'],
                'pin' => $data['pin'],
                'aktif' => true
            ]);
        }
    }
}
