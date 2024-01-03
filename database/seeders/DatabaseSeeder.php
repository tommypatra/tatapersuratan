<?php

use App\Models\Grup;
use App\Models\User;
use App\Models\Profil;
use App\Models\GrupUser;
use App\Models\AksesPola;
use App\Models\PolaSurat;
use App\Models\TtdQrcode;
use App\Models\SuratMasuk;
use App\Models\SuratKeluar;
use App\Models\SpesimenJabatan;
use Illuminate\Database\Seeder;

use App\Models\KlasifikasiSurat;
use App\Models\KategoriSuratMasuk;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        //nilai default grup
        $dtdef = [
            "Admin", "Pengguna",
        ];

        foreach ($dtdef as $dt) {
            Grup::create([
                'grup' => $dt,
            ]);
        }

        //untuk admin
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@thisapp.com', //email login
            'password' => Hash::make('00000000'), // password default login 
        ]);

        //untuk pengguna
        for ($i = 1; $i <= 20; $i++) {
            User::create([
                'name' => 'Pengguna ' . $i,
                'email' => 'pengguna' . $i . '@thisapp.com', //email login
                'password' => Hash::make('00000000'), // password default login 
            ]);
        }

        //dibuatkan admin
        GrupUser::create([
            'user_id' => 1,
            'grup_id' => 1,
        ]);
        //dibuatkan pengguna
        GrupUser::create([
            'user_id' => 1,
            'grup_id' => 2,
        ]);

        //untuk pengguna
        for ($i = 1; $i <= 20; $i++) {
            GrupUser::create([
                'user_id' => $i + 1,
                'grup_id' => 2,
            ]);
        }

        //untuk profil
        $dtdef = [
            ['user_id' => 1, 'hp' => '085331019999', 'nip' => '19840521200911007', 'alamat' => 'BTN Anggooya Kendari', 'jenis_kelamin' => 'L'],
            ['user_id' => 2, 'hp' => '085310298765', 'nip' => '19830701200811008', 'alamat' => 'BTN Kendari Permai', 'jenis_kelamin' => 'L'],
            ['user_id' => 3, 'hp' => '085251439000', 'nip' => '19821210200712001', 'alamat' => 'BTN DPR', 'jenis_kelamin' => 'P'],
            ['user_id' => 4, 'hp' => '085241728181', 'nip' => '19811116200912003', 'alamat' => 'BTN Rizky Blok B/II Ranomeeto Konsel', 'jenis_kelamin' => 'P'],
            ['user_id' => 5, 'hp' => '085309019300', 'nip' => '19891019201011009', 'alamat' => 'BTN Girya Kencana', 'jenis_kelamin' => 'L'],
        ];

        // dd($dtdef);
        foreach ($dtdef as $dt) {
            Profil::create([
                'user_id' => $dt['user_id'],
                'nip' => $dt['nip'],
                'hp' => $dt['hp'],
                'alamat' => $dt['alamat'],
                'jenis_kelamin' => $dt['jenis_kelamin'],
            ]);
        }


        //nilai default kategori surat
        $dtdef = [
            "Sangat Penting", "Penting",
        ];

        foreach ($dtdef as $dt) {
            KategoriSuratMasuk::create([
                'kategori' => $dt,
                'user_id' => 1,
            ]);
        }

        //untuk surat masuk
        for ($i = 1; $i <= 10; $i++) {
            SuratMasuk::create([
                'user_id' => rand(1, 2),
                'no_agenda' => $i,
                'no_surat' => '00' . $i . '/R1/HM.00/' . date("m") . '/' . date("Y"),
                'tanggal' => date('Y-m-') . $i,
                'kategori_surat_masuk_id' => rand(1, 2),
                'asal' => 'asal surat ' . $i,
                'tempat' => 'tempat surat ' . $i,
                'perihal' => 'Surat Tentang Data ' . $i, // password default login admin
            ]);
        }


        //nilai default spesimen jabatan
        $dtdef = [
            ["kode" => "", "jabatan" => "Rektor", "keterangan" => "Rektor IAIN Kendari", "is_aktif" => 1, "user_id" => 1, "user_pejabat_id" => 1],
            ["kode" => "/R1", "jabatan" => "Wakil Rektor I", "keterangan" => "Wakil Rektor Bidang Pengembangan Lembaga dan Akademik (Warek 1)", "is_aktif" => 1, "user_id" => 1, "user_pejabat_id" => 2],
            ["kode" => "/R2", "jabatan" => "Wakil Rektor II", "keterangan" => "Wakil Rektor Bidang Administrasi dan Keuangan (Warek 2)", "is_aktif" => 1, "user_id" => 1, "user_pejabat_id" => 3],
            ["kode" => "/R3", "jabatan" => "Wakil Rektor III", "keterangan" => "Wakil Rektor Bidang Kemahasiswaan dan Kerjasama (Warek 3)", "is_aktif" => 1, "user_id" => 1, "user_pejabat_id" => 4],
            ["kode" => "/B", "jabatan" => "Kepala Biro AUAK", "keterangan" => "Kepala Biro Administrasi Umum Akademik dan Kemahasiswaan (Karo)", "is_aktif" => 1, "user_id" => 1, "user_pejabat_id" => 5],
        ];
        // dd($dtdef);
        foreach ($dtdef as $dt) {
            SpesimenJabatan::create([
                'kode' => $dt['kode'],
                'jabatan' => $dt['jabatan'],
                'keterangan' => $dt['keterangan'],
                'is_aktif' => $dt['is_aktif'],
                'user_id' => 1,
                'user_pejabat_id' => $dt['user_pejabat_id'],
            ]);
        }

        //nilai default pola
        $dtdef = [
            ["kategori" => "Surat Keputusan (SK)", "needs_klasifikasi" => 0, "pola" => '$index$spesimen Tahun $thn', "is_aktif" => 1, "user_id" => 1],
            ["kategori" => "Surat Keluar", "needs_klasifikasi" => 1, "pola" => '$index/In.23$spesimen/$klasifikasi/$bln/$thn', "is_aktif" => 1, "user_id" => 1],
        ];

        foreach ($dtdef as $dt) {
            PolaSurat::create([
                'kategori' => $dt['kategori'],
                'pola' => $dt['pola'],
                'needs_klasifikasi' => $dt['needs_klasifikasi'],
                'is_aktif' => $dt['is_aktif'],
                'user_id' => 1,
            ]);
        }

        //nilai default klasifikasi
        $dtdef = [
            ["kode" => "HM.00", "klasifikasi" => "Surat umum", "is_aktif" => 1, "user_id" => 1],
            ["kode" => "PP.00.9", "klasifikasi" => "Surat terkait bidang akademik", "is_aktif" => 1, "user_id" => 1],
        ];

        foreach ($dtdef as $dt) {
            KlasifikasiSurat::create([
                'kode' => $dt['kode'],
                'klasifikasi' => $dt['klasifikasi'],
                'is_aktif' => $dt['is_aktif'],
                'user_id' => 1,
            ]);
        }




        //nilai default akses pola
        $dtdef = [
            ["tahun" => date('Y'), "pola_surat_id" => 1, "user_id" => 1, "spesimen_jabatan_id" => 1],
            ["tahun" => date('Y'), "pola_surat_id" => 2, "user_id" => 1, "spesimen_jabatan_id" => 1],
            ["tahun" => date('Y'), "pola_surat_id" => 2, "user_id" => 1, "spesimen_jabatan_id" => 2],
            ["tahun" => date('Y'), "pola_surat_id" => 2, "user_id" => 1, "spesimen_jabatan_id" => 3],
            ["tahun" => date('Y'), "pola_surat_id" => 2, "user_id" => 1, "spesimen_jabatan_id" => 4],
            ["tahun" => date('Y'), "pola_surat_id" => 2, "user_id" => 1, "spesimen_jabatan_id" => 5],
            ["tahun" => date('Y'), "pola_surat_id" => 2, "user_id" => 2, "spesimen_jabatan_id" => 2],
            ["tahun" => date('Y'), "pola_surat_id" => 2, "user_id" => 2, "spesimen_jabatan_id" => 3],
            ["tahun" => date('Y'), "pola_surat_id" => 2, "user_id" => 2, "spesimen_jabatan_id" => 4],
            ["tahun" => date('Y'), "pola_surat_id" => 2, "user_id" => 2, "spesimen_jabatan_id" => 5],
        ];

        foreach ($dtdef as $dt) {
            AksesPola::create([
                'tahun' => $dt['tahun'],
                'pola_surat_id' => $dt['pola_surat_id'],
                'user_id' => $dt['user_id'],
                'spesimen_jabatan_id' => $dt['spesimen_jabatan_id'],
            ]);
        }

        //nilai default surat keluar
        $dtdef = [
            [
                "no_surat" => "001/In.23/HM.00/I/2023",
                "no_indeks" => "1",
                "asal" => "AKMA",
                "pola" => "001/In.23/HM.00/I/2023",
                "tujuan" => "Para Dekan",
                "perihal" => "Permintaan data mahasiswa terbaik",
                "ringkasan" => "",
                "tanggal" => "2023-01-01",
                "klasifikasi_surat_id" => "1",
                "akses_pola_id" => "2",
                "user_id" => 1
            ],
        ];

        foreach ($dtdef as $dt) {
            SuratKeluar::create([
                "no_surat" => $dt["no_surat"],
                "no_indeks" => $dt["no_indeks"],
                "asal" => $dt["asal"],
                "pola" => $dt["pola"],
                "perihal" => $dt["perihal"],
                "tujuan" => $dt["tujuan"],
                "ringkasan" => $dt["ringkasan"],
                "tanggal" => $dt["tanggal"],
                "klasifikasi_surat_id" => $dt["klasifikasi_surat_id"],
                "akses_pola_id" => $dt["akses_pola_id"],
                "user_id" => $dt["user_id"],
            ]);
        }

        //nilai default surat keluar
        $dtdef = [
            [
                "no_surat" => "002/In.23/HM.00/I/2023",
                "kode" => generateKode(1),
                "jabatan" => "Rektor",
                "pejabat" => "Prof. Dr. Husain Insawan, M.Ag",
                "perihal" => "Permintaan data mahasiswa terbaik",
                "tanggal" => "2023-01-01",
                "file" => "https://surat.co.id",
                "is_diterima" => 0,
                "user_ttd_id" => 1,
                "user_id" => 3,
            ],
            [
                "no_surat" => "001/In.23/HM.00/I/2023",
                "kode" => generateKode(2),
                "jabatan" => "Rektor",
                "pejabat" => "Prof. Dr. Husain Insawan, M.Ag",
                "perihal" => "Data Beassiwa Bank Indonesia",
                "tanggal" => "2023-01-01",
                "file" => "https://sia.iainkendari.ac.id",
                "is_diterima" => 1,
                "user_ttd_id" => 1,
                "user_id" => 2,
            ],
            [
                "no_surat" => "003/In.23/HM.00/I/2023",
                "kode" => generateKode(3),
                "jabatan" => "Rektor",
                "pejabat" => "Prof. Dr. Husain Insawan, M.Ag",
                "perihal" => "Data Pegawai",
                "tanggal" => "2023-01-01",
                "file" => "https://google.co.id",
                "user_ttd_id" => 1,
                "is_diterima" => null,
                "user_id" => 1,
            ],
        ];

        foreach ($dtdef as $dt) {
            TtdQrcode::create([
                "no_surat" => $dt["no_surat"],
                "perihal" => $dt["perihal"],
                "pejabat" => $dt["pejabat"],
                "jabatan" => $dt["jabatan"],
                "kode" => $dt["kode"],
                "tanggal" => $dt["tanggal"],
                "file" => $dt["file"],
                "is_diterima" => $dt["is_diterima"],
                "user_ttd_id" => $dt["user_ttd_id"],
                "user_id" => $dt["user_id"],
                "qrcode" => null,
            ]);
        }
    }
}
