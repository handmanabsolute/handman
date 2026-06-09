<?php

namespace Database\Seeders;

use App\Models\Departemen;
use Illuminate\Database\Seeder;

class DepartemenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departemens = [
            [
                'nama_departemen' => 'Departemen Medis',
                'deskripsi_departemen' => 'Bertanggung jawab atas seluruh pelayanan kesehatan pasien, dokter, dan penunjang medis.',
            ],
            [
                'nama_departemen' => 'Departemen Keuangan',
                'deskripsi_departemen' => 'Mengelola seluruh transaksi keuangan, penggajian pegawai, dan anggaran operasional perusahaan.',
            ],
            [
                'nama_departemen' => 'Departemen SDM dan Umum',
                'deskripsi_departemen' => 'Mengurus administrasi kepegawaian, perekrutan karyawan, dan pengelolaan aset kantor.',
            ],
            [
                'nama_departemen' => 'Departemen Pemasaran',
                'deskripsi_departemen' => 'Menangani promosi layanan rumah sakit, kerja sama instansi, dan hubungan masyarakat.',
            ],
            [
                'nama_departemen' => 'Departemen IT',
                'deskripsi_departemen' => 'Mengelola jaringan komputer, perbaikan perangkat keras, dan pemeliharaan sistem aplikasi.',
            ],
        ];

        foreach ($departemens as $departemen) {
            Departemen::updateOrCreate(
                ['nama_departemen' => $departemen['nama_departemen']],
                ['deskripsi_departemen' => $departemen['deskripsi_departemen']]
            );
        }
    }
}
