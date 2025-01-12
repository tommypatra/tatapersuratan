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
use App\Models\PolaSpesimen;
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
            "Admin",
            "Pengguna",
        ];

        foreach ($dtdef as $dt) {
            Grup::create([
                'grup' => $dt,
            ]);
        }

        //untuk admin
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@app.com', //email login
            'password' => Hash::make('00000000'), // password default login 
        ]);

        //untuk pengguna
        for ($i = 1; $i < 5; $i++) {
            User::create([
                'name' => 'Pengguna ' . $i,
                'email' => 'pengguna' . $i . '@app.com', //email login
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
        for ($i = 1; $i < 5; $i++) {
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
            "Sangat Penting",
            "Penting",
        ];

        foreach ($dtdef as $dt) {
            KategoriSuratMasuk::create([
                'kategori' => $dt,
                'user_id' => 1,
            ]);
        }

        //untuk surat masuk
        // for ($i = 1; $i <= 10; $i++) {
        //     $diajukan = rand(0, 1);
        //     $diterima = null;
        //     $catatan = null;
        //     $verifikator = null;
        //     if ($diajukan) {
        //         $diterima = rand(0, 1);
        //         $catatan = (!$diterima) ? "ada yang kurang ini " . $i : null;
        //         $verifikator = ($diterima >= 0) ? "Administrator" : null;
        //     }

        //     SuratMasuk::create([
        //         'user_id' => rand(1, 2),
        //         'no_agenda' => $i,
        //         'no_surat' => '00' . $i . '/R1/HM.00/' . date("m") . '/' . date("Y"),
        //         'tanggal' => date('Y-m-') . $i,
        //         'kategori_surat_masuk_id' => rand(1, 2),
        //         'asal' => 'asal surat ' . $i,
        //         'tempat' => 'tempat surat ' . $i,
        //         'perihal' => 'Surat Tentang Data ' . $i, // password default login admin
        //         "is_diajukan" => $diajukan,
        //         "is_diterima" => $diterima,
        //         "catatan" => $catatan,
        //         "verifikator" => $verifikator,
        //     ]);
        // }


        //nilai default spesimen jabatan
        $dtdef = [
            ["kode" => "", "jabatan" => "Rektor", "keterangan" => "Rektor IAIN Kendari", "is_aktif" => 1, "user_id" => 1, "user_pejabat_id" => 1],
            ["kode" => "/R1", "jabatan" => "Wakil Rektor I", "keterangan" => "Wakil Rektor Bidang Pengembangan Lembaga dan Akademik (Warek 1)", "is_aktif" => 1, "user_id" => 1, "user_pejabat_id" => 2],
            ["kode" => "/R2", "jabatan" => "Wakil Rektor II", "keterangan" => "Wakil Rektor Bidang Administrasi dan Keuangan (Warek 2)", "is_aktif" => 1, "user_id" => 1, "user_pejabat_id" => 3],
            ["kode" => "/R3", "jabatan" => "Wakil Rektor III", "keterangan" => "Wakil Rektor Bidang Kemahasiswaan dan Kerjasama (Warek 3)", "is_aktif" => 1, "user_id" => 1, "user_pejabat_id" => 4],
            ["kode" => "/B", "jabatan" => "Kepala Biro AUAK", "keterangan" => "Kepala Biro Administrasi Umum Akademik dan Kemahasiswaan (Karo)", "is_aktif" => 1, "user_id" => 1, "user_pejabat_id" => 5],

            ["kode" => "/B.II", "jabatan" => "Kabag. Umum dan Layanan Akademik", "keterangan" => "Kepala Bagian Umum dan Layanan Akademik", "is_aktif" => 1, "user_id" => null, "user_pejabat_id" => null],
            ["kode" => "/B.II.2", "jabatan" => "Kasubag. Akademik", "keterangan" => "Kepala Sub  Bagian Akademik", "is_aktif" => 1, "user_id" => null, "user_pejabat_id" => null],
            ["kode" => "/B.II.3", "jabatan" => "Kasubag. Umum", "keterangan" => "Kepala Sub Umum", "is_aktif" => 1, "user_id" => null, "user_pejabat_id" => null],

            ["kode" => "/FE", "jabatan" => "Dekan Febi", "keterangan" => "Dekan Fakultas Ekonomi & Bisnis Islam", "is_aktif" => 1, "user_id" => null, "user_pejabat_id" => null],
            ["kode" => "/FE.1", "jabatan" => "Wadek I Febi", "keterangan" => "Wakil Dekan I Fakultas Ekonomi & Bisnis Islam", "is_aktif" => 1, "user_id" => null, "user_pejabat_id" => null],
            ["kode" => "/FE.2", "jabatan" => "Wadek II Febi", "keterangan" => "Wakil Dekan II Fakultas Ekonomi & Bisnis Islam", "is_aktif" => 1, "user_id" => null, "user_pejabat_id" => null],
            ["kode" => "/FE.3", "jabatan" => "Wadek III Febi", "keterangan" => "Wakil Dekan III Fakultas Ekonomi & Bisnis Islam", "is_aktif" => 1, "user_id" => null, "user_pejabat_id" => null],

            ["kode" => "/FS", "jabatan" => "Dekan Faksyar", "keterangan" => "Dekan Fakultas Syariah", "is_aktif" => 1, "user_id" => null, "user_pejabat_id" => null],
            ["kode" => "/FS.1", "jabatan" => "Wadek I Faksyar", "keterangan" => "Wakil Dekan I Fakultas Syariah", "is_aktif" => 1, "user_id" => null, "user_pejabat_id" => null],
            ["kode" => "/FS.2", "jabatan" => "Wadek II Faksyar", "keterangan" => "Wakil Dekan II Fakultas Syariah", "is_aktif" => 1, "user_id" => null, "user_pejabat_id" => null],
            ["kode" => "/FS.3", "jabatan" => "Wadek III Faksyar", "keterangan" => "Wakil Dekan III Fakultas Syariah", "is_aktif" => 1, "user_id" => null, "user_pejabat_id" => null],

            ["kode" => "/FT", "jabatan" => "Dekan Fatik", "keterangan" => "Dekan Fakultas Tarbiyah dan Ilmu Keguruan", "is_aktif" => 1, "user_id" => null, "user_pejabat_id" => null],
            ["kode" => "/FT.1", "jabatan" => "Wadek I Fatik", "keterangan" => "Wakil Dekan I Fakultas Tarbiyah dan Ilmu Keguruan", "is_aktif" => 1, "user_id" => null, "user_pejabat_id" => null],
            ["kode" => "/FT.2", "jabatan" => "Wadek II Fatik", "keterangan" => "Wakil Dekan II Fakultas Tarbiyah dan Ilmu Keguruan", "is_aktif" => 1, "user_id" => null, "user_pejabat_id" => null],
            ["kode" => "/FT.3", "jabatan" => "Wadek III Fatik", "keterangan" => "Wakil Dekan III Fakultas Tarbiyah dan Ilmu Keguruan", "is_aktif" => 1, "user_id" => null, "user_pejabat_id" => null],

            ["kode" => "/FU", "jabatan" => "Dekan Fuad", "keterangan" => "Dekan Fakultas Ushuluddin Adab dan Dakwah", "is_aktif" => 1, "user_id" => null, "user_pejabat_id" => null],
            ["kode" => "/FU.1", "jabatan" => "Wadek I Fuad", "keterangan" => "Wakil Dekan I Fakultas Ushuluddin Adab dan Dakwah", "is_aktif" => 1, "user_id" => null, "user_pejabat_id" => null],
            ["kode" => "/FU.2", "jabatan" => "Wadek II Fuad", "keterangan" => "Wakil Dekan II Fakultas Ushuluddin Adab dan Dakwah", "is_aktif" => 1, "user_id" => null, "user_pejabat_id" => null],
            ["kode" => "/FU.3", "jabatan" => "Wadek III Fuad", "keterangan" => "Wakil Dekan III Fakultas Ushuluddin Adab dan Dakwah", "is_aktif" => 1, "user_id" => null, "user_pejabat_id" => null],

            ["kode" => "/KUI", "jabatan" => "Ketua KUI", "keterangan" => "Ketua KUI (Kantor Urusan Internasional)", "is_aktif" => 1, "user_id" => null, "user_pejabat_id" => null],

            ["kode" => "/L.I", "jabatan" => "Ketua LPPM", "keterangan" => "Ketua Lembaga Penelitian dan Pengabdian kepada Masyarakat (LPPM)", "is_aktif" => 1, "user_id" => null, "user_pejabat_id" => null],
            ["kode" => "/L.I.1", "jabatan" => "Kapus Penelitian dan Penerbitan", "keterangan" => "Kepala Pusat Penelitian dan Penerbitan - LPPM", "is_aktif" => 1, "user_id" => null, "user_pejabat_id" => null],
            ["kode" => "/L.I.2", "jabatan" => "Kapus Pengabdian Kepada Masyarakat", "keterangan" => "Kepala Pusat Pengabdian Kepada Masyarakat - LPPM", "is_aktif" => 1, "user_id" => null, "user_pejabat_id" => null],
            ["kode" => "/L.I.3", "jabatan" => "Kapus Studi Gender dan Anak", "keterangan" => "Kepala Pusat Studi Gender dan Anak - LPPM", "is_aktif" => 1, "user_id" => null, "user_pejabat_id" => null],
            ["kode" => "/L.I.4", "jabatan" => "Ketua Sentra HAKI", "keterangan" => "Ketua Sentra Hak Kekayaan Intelektual - LPPM", "is_aktif" => 1, "user_id" => null, "user_pejabat_id" => null],

            ["kode" => "/L.II", "jabatan" => "Ketua LPM", "keterangan" => "Ketua Lembaga Penjamin Mutu (LPM)", "is_aktif" => 1, "user_id" => null, "user_pejabat_id" => null],
            ["kode" => "/L.II.1", "jabatan" => "Kapus Pengembangan Standar Mutu", "keterangan" => "Kepala Pusat Pengembangan Standar Mutu - LPM", "is_aktif" => 1, "user_id" => null, "user_pejabat_id" => null],
            ["kode" => "/L.II.2", "jabatan" => "Kapus Audit dan Pengendalian Mutu", "keterangan" => "Kepala Pusat Audit dan Pengendalian Mutu - LPM", "is_aktif" => 1, "user_id" => null, "user_pejabat_id" => null],

            ["kode" => "/P", "jabatan" => "Direktur Pascasarjana", "keterangan" => "Direktur Pascasarjana", "is_aktif" => 1, "user_id" => null, "user_pejabat_id" => null],
            ["kode" => "/T.Tu", "jabatan" => "Kasubag Tata Usaha Pascasarjana", "keterangan" => "Kepala Sub Bagian Tata Usaha Pascasarjana", "is_aktif" => 1, "user_id" => null, "user_pejabat_id" => null],

            // Pengelola Barang dan Jasa	/PBJ	49sqk	20
            // Panitia Pemeriksa dan Penerima Barang	/PPB	49sqk	63
            // Pejabat Pembuat Komitmen	/PPK	49sqk	60

            ["kode" => "/Set.L.I", "jabatan" => "Sekretaris LPPM", "keterangan" => "Sekretaris Penelitian dan Pengabdian kepada Masyarakat (LPPM)", "is_aktif" => 1, "user_id" => null, "user_pejabat_id" => null],
            ["kode" => "/Set.L.II", "jabatan" => "Sekretaris LPM", "keterangan" => "Sekretaris Penjamin Mutu (LPM)", "is_aktif" => 1, "user_id" => null, "user_pejabat_id" => null],
            ["kode" => "/SPI", "jabatan" => "Ketua SPI", "keterangan" => "Ketua Satuan Pengawas Internal", "is_aktif" => 1, "user_id" => null, "user_pejabat_id" => null],

            ["kode" => "/TU.T", "jabatan" => "Kabag. TU Fatik", "keterangan" => "Kepala Bagian Fakultas Tarbiyah dan Ilmu Keguruan", "is_aktif" => 1, "user_id" => null, "user_pejabat_id" => null],
            ["kode" => "/TU.S", "jabatan" => "Kabag. TU Faksyar", "keterangan" => "Kepala Bagian Fakultas Syariah", "is_aktif" => 1, "user_id" => null, "user_pejabat_id" => null],
            ["kode" => "/TU.U", "jabatan" => "Kabag. TU Fuad", "keterangan" => "Kepala Bagian Fakultas Ushuluddin Adab dan Dakwah", "is_aktif" => 1, "user_id" => null, "user_pejabat_id" => null],
            ["kode" => "/TU.E", "jabatan" => "Kabag. TU Febi", "keterangan" => "Kepala Bagian Fakultas Ekonomi Islam & Bisinis Islam", "is_aktif" => 1, "user_id" => null, "user_pejabat_id" => null],

            ["kode" => "/UPT.I", "jabatan" => "Kapus Perpustakaan", "keterangan" => "Kepala UPT Perpustakaan", "is_aktif" => 1, "user_id" => null, "user_pejabat_id" => null],
            ["kode" => "/UPT.II", "jabatan" => "Kapus TIPD", "keterangan" => "Kepala UPT Teknologi, Informasi dan Pangkalan Data", "is_aktif" => 1, "user_id" => null, "user_pejabat_id" => null],
            ["kode" => "/UPT.III", "jabatan" => "Kapus Pengembangan Bahasa", "keterangan" => "Kepala UPT Pengembangan Bahasa", "is_aktif" => 1, "user_id" => null, "user_pejabat_id" => null],
            ["kode" => "/UPT.IV", "jabatan" => "Kapus Mahad", "keterangan" => "Kepala UPT Mahad Al Jamiah", "is_aktif" => 1, "user_id" => null, "user_pejabat_id" => null],
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
            ['kode' => 'BA.00', 'klasifikasi' => 'PENYULUHAN - Surat-surat yang berkenaan dengan seluruh proses yang berhubungan dengan penerangan agama kepada masyarakat dan lingkungan khusus (transmigrasi, suku terasing inrehab dan narapidana), termasuk sarananya seperti: film, drama, MTQ (Musabaqah Tilawatil Quran), pagelaran seni budaya, perayaan hari-hari besar agama, sekaten, pesparawi, utsawa dharma gita, orientasi seni budaya, siaran RRI/TVRI', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'BA.01.1', 'klasifikasi' => 'LEMBAGA KEAGAMAAN - Surat-surat yang berkenaan dengan bimbingan-bimbingan kepada lembaga-lembaga keagamaan yang ada dalam masyarakat, meliputi : Dai / juru penerang agama, Organisasi-organisasi keagamaan, Kepengurusan rumah ibadah, Organisasi remaja keagamaan dan sarana bimbingannya, Rekomendasi DPKK (Dana Pengembangan Kehalian dan Keterampilan), Rekomendasi izin impor terhadap barang bantuan / hibah dari luar negeri, Rekomendasi pembebasan pajak pertambahan nilai terhadap buku kitab suci dan buku pelajaran agama .', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'BA.01.2', 'klasifikasi' => 'ALIRAN KEROHANIAN  / KEAGAMAAN - Surat-surat yang berkenaan dengan aliran kerohanian / keagamaan yang timbul dalam masyarakat', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'BA.02', 'klasifikasi' => 'KERUKUNAN HIDUP BERAGAMA - Surat-surat yang berkenaan dengan bimbingan kerukunan hidup yang beragama, termasuk surat-surat yang berkenaan dengan hal-hal yang menyinggung perasaan umat beragama', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'BA.03.1', 'klasifikasi' => 'IBADAH - Surat-surat yang berkenaan dengan seluruh proses kegiatan pembinaan ibadah seperti : Shalt Ied, Eka Dhasa Rudra, Kebaktian, Natal, Galungan, Waisak, Nyepi.', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'BA.03.2', 'klasifikasi' => 'IBADAH SOSIAL - Surat-surat yang berkenaan dengan seluruh proses kegiatan ibadah social, seperti : Baitul maal termasuk (zakat, hibah, infak, wakaf dan bondo masjid), Dana punia, Dana paramita, Kolekta, Diskonia dan lain-lain termasuk bantuan rumah ibadah.', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'BA.04', 'klasifikasi' => 'PENGEMBANGAN KEAGAMAAN - Surat-surat yang berkenaan dengan statistik keagamaan, pemeluk agama, tokokh agama dan rumah ibadah', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'BA.05', 'klasifikasi' => 'ROHANIAWAN - Surat-surat yang berkenaan dengan rohaniawan, termasuk: urusan perizinan, naturalisasi, paskim (buku kesehatan), visa, perpanjangan izin, dan pengambilan sumpah', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'DL.01', 'klasifikasi' => 'PERJALANAN DINAS - Surat-surat yang berkenaan dengan seluruh proses yang berhubungan dengan perjalanan dinas', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'HJ.00', 'klasifikasi' => 'CALON HAJI - Surat-surat yang berkenaan dengan pendaftaran calon haji, termasuk kelengkapan dokumen, seperti:  daftar nominative STPH (Surat Tanda Pergi Haji) paspor paskim (buku kesehatan) visa dan lain-lain yang sehubungan', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'HJ.01', 'klasifikasi' => 'BIMBINGAN - Surat-surat yang berkenaan dengan bimbingan jamaah haji dan petugas haji termasuk: pameran penataran dan peragaan', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'HJ.02', 'klasifikasi' => 'PETUGAS HAJI - Surat-surat yang berkenaan dengan petugas haji: TPHI (Tim Petugas Haji Indonesia), TKHI (Tim Kesehatan Haji Indonesia), PPIH (Panitia Penyelenggara Haji) Pusat, PPIH (Panitia Penyelenggara Haji) Embarkasi, PPIH (Panitia Penyelenggara Haji) Arab Saudi, tenaga musiman, P3H (Panitia Pemberangkatan dan Pemulangan Haji), sekretariat boyongan, Amirul Haj dan Naib Amirul Haj, PPIH non kloter termasuk laporan kegiatan', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'HJ.03', 'klasifikasi' => 'ONGKOS NAIK HAJI - Surat-surat yang berkenaan dengan : penentuan bedarnya ONH, restitusu dan asuransi living cost', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'HJ.04', 'klasifikasi' => 'JEMAAH HAJI - Surat-surat yang berkenaan dengan jemaah haji, meliputi : sejkh/muzawwir  sakit meninggal melahirkan dan hilang', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'HJ.05', 'klasifikasi' => 'ANGKUTAN - Surat-surat yang berkenaan dengan transportasi haji dalam dan luar negeri, jadwal pemberangkatan dan pemulangan jamah haji dan daftar jamaah (manifest)', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'HJ.06', 'klasifikasi' => 'PENGASRAMAAN - Surat-surat yang berkenaan dengan pengasramaan calon haji di dalam/ luar negeri, pengembalian biaya perumahan di Arab Saudi dan Qurah', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'HJ.07', 'klasifikasi' => 'PEMBEKALAN - Surat-surat yang berhubungan dengan pembekalan jemaah haji termasuk pengadaan, penyimpanan, pendistribusian, antara lain : kemas haji, obat-obatan, buku manasik haji, buku kesehatan jamaah haji, petunjuk perjalanan haji, barang-barang bawaan dan dalam/luar negeri serta kelengkapan lainnya yang sehubungan', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'HJ.08', 'klasifikasi' => 'DISPENSASI/REKOMENDASI KHUSUS - Surat-surat yang berkenaan dengan dispensasi dan rekomendasi masuk Arab Saudi pada masa-masa musim haji baik bagi WNI dalam maupun luar negeri', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'HJ.09', 'klasifikasi' => 'UMROH - Surat-surat yang berkenaan dengan masalah-masalah umroh, termasuk perizinan, Pelaksanaan penyelenggara/organisasi-organisasi, yayasan-yayasan, travel biro dan pengawasan penyelenggaraannya', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'HK.00', 'klasifikasi' => 'PERATURAN PERUNDANG-UNDANGAN - Surat-surat yang berkenaan dengan pemrosesan suatu peraturan perundang-undangan produk Kementerian Agama dan konsep/draf sampai selesai, merupakan produk peraturan-peraturan perundang-undangan yang terima baik intern Kementertian maupun dari instansi lain.', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'HK.00.1', 'klasifikasi' => 'Undang-undang termasuk perpu - Undang-undang termasuk perpu', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'HK.00.2', 'klasifikasi' => 'Peraturan Pemerintah - Peraturan Pemerintah', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'HK.00.3', 'klasifikasi' => 'Keputusan Presiden, Instruksi Presiden - Keputusan Presiden, Instruksi Presiden', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'HK.00.4', 'klasifikasi' => 'Peraturan Menteri, Instruksi Menteri - Peraturan Menteri, Instruksi Menteri', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'HK.00.5', 'klasifikasi' => 'Keputusan Menteri, Pimpinan Unit Eselon I - Keputusan Menteri, Pimpinan Unit Eselon I', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'HK.00.6', 'klasifikasi' => 'SKB Menteri-menteri, Pimpinan Unit Eselon I/II - SKB Menteri-menteri, Pimpinan Unit Eselon I/II', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'HK.00.7', 'klasifikasi' => 'Edaran Menteri/Pimpinan Unit Eselon I/II Agama Provinsi dan Kantor Kementerian Agama Kab./Kota - Edaran Menteri/Pimpinan Unit Eselon I/II Agama Provinsi dan Kantor Kementerian Agama Kab./Kota', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'HK.00.8', 'klasifikasi' => 'Peraturan Kantor Wilayah Kementerian Agama Provinsi dan Kantor Kementerian Agama Kab./Kota - Peraturan Kantor Wilayah Kementerian Agama Provinsi dan Kantor Kementerian Agama Kab./Kota', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'HK.00.9', 'klasifikasi' => 'Peraturan PEMDA Tk.I/PEMDA Tk.II - Peraturan PEMDA Tk.I/PEMDA Tk.II', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'HK.01.1', 'klasifikasi' => 'PENCURIAN - Surat surat yang berkenaan dengan pencurian yang terjadi di dalam lingkungan kantor Kementerian Agama baik pusat maupun daerah', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'HK.01.2', 'klasifikasi' => 'KORUPSI - Surat surat yang berkenaan dengan korupsi, penyelewengan dan penyelewengan dan penyalahgunaan wewenang/jabatan.', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'HK.02', 'klasifikasi' => 'PERDATA - surat surat yang berhubungan dengan perdata', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'HK.02.1', 'klasifikasi' => 'PERIKATAN - Surat surat yang berhubungan dengan perikatan yang meliputi : Hak pakai, peminjaman, sewa menyewa, dan lain-lain sebagainya.', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'HK.03', 'klasifikasi' => 'HUKUM AGAMA - surat surat yang berhubungan dengan hukum agama', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'HK.03.1', 'klasifikasi' => 'FATWA - Surat surat yang berkenaan dengan pendapat hukum dan penetapan status hukum mengenai sesuatu hal yang belum jelas hukumnya seperti : Bedah mayat, Maslah waris (di Jawa dan Madura), Maslah hibah/shodaqoh (di jawa dan Madura), dan lain-lain sebagainya.', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'HK.03.2', 'klasifikasi' => 'RUKYAT / HISAB - Surat surat yang berkenaan dengan PENENTUAN : Arah kiblat, awal / akhir Ramadhan, Hari besar Islam, Jadwal waktu Sholat, Kalender.', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'HK.03.3', 'klasifikasi' => 'HARI BESAR AGAMA - Surat surat yang berkenaan dengan besar agama : Islam, Kristen, Katholik, Hindu, Budha dan Kong Hu Cu (Imlek).', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'HK.04', 'klasifikasi' => 'BANTUAN HUKUM - surat surat yang berkenaan dengan bantuan hukum', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'HK.04.1', 'klasifikasi' => 'KASUS HUKUM PIDANA - Surat surat yang berkenaan dengan bantuan hukum kepada pejabat / pegawai Kementerian Agama dalam kasus pidana yang berhubungan dengan pelaksanaan tugas.', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'HK.04.2', 'klasifikasi' => 'KASUS HUKUM PERDATA - Surat surat yang meliputi /berhubungan dengan bantuan hukum kepada pejabat/pegawai Kementerian Agama dalam kasus perdata yang berhubungan dengan pelaksanaan tugas.', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'HK.04.3', 'klasifikasi' => 'KASUS HUKUM TATA USAHA NEGARA (TUN) - Surat surat yang berkenaan dengan pemberian bantuan hukum kepada Menteri Agama atau pejabat Kementerian Agama dalam kasus Tata Usaha Negara (TUN)', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'HK.04.4', 'klasifikasi' => 'PENELAAHAN HUKUM - Surat surat yang meliputi/berhubungan dengan penelaahan hukum yang berkaitan dengan masalah agama, selian agama Islam.', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'HM.00', 'klasifikasi' => 'PENERANGAN - Surat-surat yang berhubungan dengan kegiatan yang berkenaan dengan penerangan terhadap masyarakat antara lain : konferensi pers, pameran, wawancara penerangan dalam media massa, penyampaian, undangan, permohonan.', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'HM.01', 'klasifikasi' => 'HUBUNGAN - Surat-surat yang berhubungan dengan kerjasama dalam dan luar negeri dan koordinasi intern dan ekstrn antar pemerintahan umum antara lain : Bakohamas, Hearning DPR, AMd, PKP., Kelompok Kerja (POKJA), dan organisasi-organisasi mass media termasuk didalamnya pengarahan/sambutan yang bersifat umum.', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'HM.02', 'klasifikasi' => 'DOKUMENTASI DAN KEPUSTAKAAN - Surat surat yang berhubungan dengan dokumentasi dan kepusatakaan', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'HM.02.1', 'klasifikasi' => 'DOKUMENTASI - Surat surat yang berkenaan dengan kegiatan yang berhubungan dengan penyediaan/pengumpulan bahan/dokumnetasi termasuk penyebarannya.', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'HM.02.2', 'klasifikasi' => 'KEPUSTAKAAN - Surat surat yang berkenaan dengan kegiatan yang berhubungan dengan penyediaan pengumpulan bahan-bahan kepustakaan.', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'HM.03', 'klasifikasi' => 'KEPROTOKOLAN - Surat-surat yang berkenaan dengan masalah keprotokalan, seperti: tamu-tamu pimpinan kementerian dalam maupun luar negeri), kunjungan kerja, upacara hari nasional dan HUT Kementerian Agama', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'KP.00.1', 'klasifikasi' => 'FORMASI - Surat  - surat yang berkenaan dengan perencanaan pengadaan pegawai, nota usul, formasi sampai dengan persetujuan termasuk didalamnya berzetting.', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'KP.00.2', 'klasifikasi' => 'PENERIMAAN - Surat  - surat yang berkenaan dengan perencanaan pengadaan pegawai baru mulai dari pengumuman penerimaan, panggilan testing/psyhotest/clearace test sampai pengumuman yang diterima, termasuk didalamnya: GAH (Guru Agama Honorarium), GTT (Guru Tidak Tetap), P3-NTCR (Pegawai Pembantu Pencatat Nikah Talak Rujuk) / Pembantu PPN dan tenaga honorarium lainnya, termasuk pengangkatan dan pemberhentiannya.', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'KP.00.3', 'klasifikasi' => 'PENGANGKATAN - Surat -surat yang berkenaan dengan seluruh proses pengangkatan calon pegawai dan menempatkan calon pegawai sampai dengan menjadi pegawai negeri, mulai dari pemeriksaan kesehatan sampai dengan pengangkatan, termasuk pelimpahan/penempatan.', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'KP.01.1', 'klasifikasi' => 'IZIN / DISPENSASI - Surat -surat yang berkenaan dengan izin tidak masuk kerja atas permintaan yang diajukan oleh pegawai yang bersangkutan, maupun dispensasi yang diajukan oleh instansi lain dan tugas keluar negeri bagi pegawai Kementerian Agama serta tugas belajar yang diberikan oleh instansi Kementerian Agama atau atas permintaan pegawai yang bersangkutan', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'KP.01.2', 'klasifikasi' => 'KETERANGAN - Surat -surat yang berkenaan dengan keterangan pegawai keluarganya, termasuk surat - surat mengenai NIP / KARPEG penunjukan penghubung ke instansi lain dan data pegawai/pejabat.', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'KP.02.1', 'klasifikasi' => 'DIKLAT PRAJABATAN - Surat - surat yang berkenan dengan : Diklat prajabatan Golongan I sebagai syarat untuk menjadi PNS golongan I, Diklat Prajabatan Golongan II sebagai syarat untuk menjadi PNS Golongan II, Diklat Prajabatan Golongan III sebagai syarat untuk menjadi PNS Golongan III                                                                                                                                  Mulai dari perencanaan (training need survei kurikulum, silabus dan lainnya), pelaksanaan dan evaluasi.', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'KP.02.2', 'klasifikasi' => 'DIKLAT DALAM JABATAN - Surat - surat yang berkenaan dengan semua jenis  pendidikan dan pelatihan (Diklat). Mulai dari Perencanaan (training need survey kurikulum, silabus dan lainnya), persiapan, pelaksanaan dan evaluasi.', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'KP.02.3', 'klasifikasi' => 'LATIHAN KURSUS - Surat - surat yang berkenan dengan kursus baik yang deselenggarakan dalam Negeri maupun diluar Negeri, misalnya :  LEMHANAS (Lemaga Pertahanan Nasional), (Workshop), (Lokakarya), (Orientasi), (Konsultasi), (Sosialisasi), (Seminar), dan lain- lain. Mulai dari perencanaa, persiapan, pelaksanaan dan evaluasi. (Surat Tigas)', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'KP.03', 'klasifikasi' => 'KORPRI - Surat-surat yang berkenaan dengan organisasi KORPRI termasuk didalamnya: Dharma Wanita dan lain-lain yang sejenis', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'KP.04.1', 'klasifikasi' => 'PENILAIAN - Surat - surat yang berkenaan dengan penilaian dengan penilaian pekerjaan, disiplin pegawai, pemalsuan administrasi kepegawaian, rehabilitasi dan pemutihan.', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'KP.04.2', 'klasifikasi' => 'HUKUMAN - Surat - surat yang berkenaan dengan hukuman pegawai yang meliputi : Teguran tertulis, Pernyataan tidak puas secara tertulis, Penundaan kenaikan gaji berkala untuk paling lama 1 (satu) tahun, Penundaan kenaikan pangkat untuk paling lama 1 (satu) tahun, Penurunan pangkat pada pangkat yang setingkat lebih rendah untuk paling lama 1 (satu) tahun, Pembebasan dari jabatan, Pemberhentian dengan hormat tidak atas permintaan sendiri sebagai Pegawai Negeri Sipil, Pemberhentian tidak dengan hormat sebagai Pegawai Negeri Sipil', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'KP.05', 'klasifikasi' => 'SCREENING - Surat-surat yang berhubungan dengan sreening bagi pegawai dalam hal kegiatan politik', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'KP.06', 'klasifikasi' => 'PEMBINAAN MENTAL - Surat-surat yang berkenaan dengan pembinaan mental pegawai termasuk didalamnya kerohanian dan P4', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'KP.07.1', 'klasifikasi' => 'KEPANGKATAN - Surat surat yang berkenaan dengan kenaikan pangkat / golongan termasuk didalamnya ujian dinas, penyesuaina ijazah dan daftar unit kepangkatan.', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'KP.07.2', 'klasifikasi' => 'KENAIKAN GAJI BERKALA - Surat surat yang berkenaan dengan kenaikan gaji berkala', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'KP.07.3', 'klasifikasi' => 'PENYESUAIAN MASA KERJA - Surat surat yang berkenaan dengan penyesuaian masa kerja untuk perubahan ruang gaji dan impassing.', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'KP.07.4', 'klasifikasi' => 'PENYESUAIAN TUNJANGAN KELUARGA - Surat surat yang berkenaan dengan penyesuaian tunjangan keluarga', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'KP.07.5', 'klasifikasi' => 'ALIH TUGAS - Surat surat yang berkenaan dengan alih tugas bagi para pelaksana /staf, perpindahan dalam rangka pemantapan tugas perkejaan termasuk mengenai fasilitasnya.', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'KP.07.6', 'klasifikasi' => 'JABATAN STRUKTURAL / FUNGSIONAL - Surat surat yang berkenaan dengan pengangkatan dan pemberhentian dalam jabatan struktural / fungsional termasuk jawaban sewaktu penugasan atau pemberian kuasa untuk menjabat sementara, termasuk fasilitasnya.', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'KP.08.1', 'klasifikasi' => 'KESEHATAN - Surat surat yang berkenaan dengan penyelenggaraan kesehatan bagi pegawai meliputi : Asuransi Kesehatan (ASKES), General chek up pejabat, General chek up karyawan/i', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'KP.08.2', 'klasifikasi' => 'CUTI - Surat surat yang berkenaan dengan cuti pegawai meliputi : Cuti Tahunan, Cuti Karena Alasan Penting, Cuti Sakit, Cuti Bersalin/Hamil, danCuti diluar Tanggungan Negara', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'KP.08.3', 'klasifikasi' => 'REKREASI - Surat surat yang berkenaan dengan rekreasi dan olahraga', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'KP.08.4', 'klasifikasi' => 'BANTUAN /SANTUNAN SOSIAL - Surat surat yang berkenaan dengan bantuan/tunjangan sosial kepada pegawai dan keluarga yang mengalami musibah, termasuk ucapan duka cita.', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'KP.08.5', 'klasifikasi' => 'KOPERASI - Surat surat yang berkenaan dengan organisasi koperasi termasuk didalamnya masalah pengurusan kebutuhan bahan pokok.', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'KP.08.6', 'klasifikasi' => 'PERUMAHAN - Surat surat yang berkenaan dengan perumahan pegawai.', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'KP.08.7', 'klasifikasi' => 'ANTAR JEMPUT/TRANSPORTASI - Surat surat yang berkenaan dengan transportasi pegawai.', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'KP.08.8', 'klasifikasi' => 'PENGHARGAAN - Surat Surat yang berkenaan dengan penghargaa, tanda jasa, piagam, satya lencana, penghargaan anumerta dan sebagainya', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'KP.09', 'klasifikasi' => 'PEMUTUSAN HUBUNGAN KERJA - Surat-surat yang berkenaan dengan pemberian pensiun pegawai, termasuk jaminan-jaminan asuransi, berhenti atas permintaan sendiri, berhenti dengan hormat bukan karena hukuman, pindah keluar dari kementerian dan meninggal dunia.', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'KS.00', 'klasifikasi' => 'KERUMAHTANGGAAN - penggunaan fasilitas, contoh : pinjam untuk dapat menggunakan ruang rapat, kendaraan dsb. keamanan dan ketertiban, konsumsi, pakaian dinas kerja, papan nama, lambang, alamat pejabat dan telekomunikasi/listrik/air (langganan)', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'KS.01.1', 'klasifikasi' => 'GEDUNG - Surat surat yang berkenaan dengan : Asrama, Pos Penjagaan, Rumah dinas termasuk (tanah, mulai dari perencanaa, pengadaan, pendistribusian, pemeliharaan sampai dengan penghapusannya).', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'KS.01.2', 'klasifikasi' => 'ALAT KANTOR - Surat-surat yang berkenaan dengan alat kantor seperti : ATK (Alat Tulis Kantor), Formulir/faktor mulai dari perencanaan, pengadaan dan pendistribusian.', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'KS.01.3', 'klasifikasi' => 'MESIN KANTOR/ALAT-ALAT ELEKTRONIK - Surat-surat yang berkenaan dengan mesin kantor (barang-barang/alat-alat elektronik meliputi : AC, Amplifier, Fan/Kipas Angin, Kamera, Mesin ketik/hitung, Overhead proyektor, Proyek film, Radio, Roneo, Slide, Mesin stensil, Tape recorder, Teleks, Video tape, dan lain-lain yang sejenisnya, mulai dari perencanaa, pendistribusian, pemeliharaan sampai dengan penghapusan.', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'KS.01.4', 'klasifikasi' => 'PERABOT KANTOR - Surat-surat yang berkenaan dengan pengelolaan perabot kantor, meliputi : Kursi, Meja, Lemari, Filing cabinet/card rak, mulai dari perencanaa, pendistribusian, pemeliharaan sampai dengan penghapusan.', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'KS.01.5', 'klasifikasi' => 'KENDARAAN - Surat-Surat yang berkenaan dengan masalah kendaraan mulai dari perencanaan, pendistribusian, pemeliharaan sampai dengan penghapusan.', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'KS.01.6', 'klasifikasi' => 'INVENTARIS PERLENGKAPAN - Surat-Surat yang berkenaan dengan surat-surat yang berkenaan dengan intentaris perlengkapan, laporan inventaris perlengkapan pusat dan daerah.', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'KS.01.7', 'klasifikasi' => 'PENAWARAN UMUM - Surat-Surat yang berkenaan dengan penyelenggaraan prakualifikasi calon rekanan dan penawaran umum termasuk persyaratannya.', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'KS.02', 'klasifikasi' => 'KETATAUSAHAAN - Surat-surat yang berkenaan dengan korespondensi dak kearsipan, penandatanganan surat yang wewenangnya serta cap dinas.', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'KU.00.1', 'klasifikasi' => 'RENCANA ANGGARAN - Surat - surat atau naskah yang berkenaan dengan rencana anggaran meliputi Rencana Anggaran Kerja Insitansi (RAKIP). RKA-KL, dan RASKIP, usulan RAPBN ke DPR RI termasuk usulan Anggaran Belanja dari Unit Organisasi.', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'KU.00.2', 'klasifikasi' => 'PENYUSUNAN ANGGARAN - Surat -surat yang berkenaan dengan anggaran belanja mulai dari pengumpulan bahan, pemprosesan penetapan Pagu Indikatif, Pagu Definitif, Rencana Kerja Anggaran (RKA), DIPA, Petunjuk Operasional Kegiatan (POK) Revisi Anggaran dan target penerimaan bukan pajak.', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'KU.00.3', 'klasifikasi' => 'NON BUDGETER - Surat -surat yang berkenaan dengan penyusunan Anggaran non Budgeter meliputi: NTCR (Nikah, Talak, Cerai, Rujuk), Biaya Petugas Haji, BKM (Badan Kesejahteraan Masjid, BP-4 (Badan Perkawinan dan Penyelesaian Perceraian)', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'KU.01.1', 'klasifikasi' => 'SURAT PERMINTAAN PEMBAYARAN - Surat -surat yang berkenan dengan pengajuan dan pengeluaran Surat Permintaan Pembayaran (SPP) meliputi SPP-GU, SPP/TU, SPP-LS, ABT rutin, termasuK gaji pegawai, Surat Pernyataan Permintaan Dispensasi Tambahan Uang Persediaan, Surat pernyataan Permintaan Dispensasi Tambahan Uang Persediaan, Penambahan Anggaran/Anggaran Pendapatan Belanja Negara Perubahan. (SPM)', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'KU.01.2', 'klasifikasi' => 'SPJ - Surat - surat yang berkenaan dengan pengajuan dan pengeluaran Surat Permintaan Pembayaran (SPP) beban tetap dan sementara/ UUDP (Uang Untuk Dipertanggungjawabkan) pembangunan.', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'KU.02.1', 'klasifikasi' => 'SPJ APBN - Surat surat yang berkenaan dengan pertanggungjawaban keuangan anggaran belanjarutin, seperti: Laporan Realisasi Keuangan, Surat Keterangan Tanggungjawab Mutlak, Laporan Relisasi Anggaran, Laporan Realisasi Keuangan, Surat Keterangan Tanggujawab Mutlak, Laporan Realisasi Anggaran', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'KU.02.2', 'klasifikasi' => 'SPJ NON BUDGERET - Surat - surat yang berkenaan dengan pertanggungjawaban keunagan: NTCR (Nikah, Talak, Cerai, Rujuk), Biaya Petugas Haji, BKM (Badan Kesejahteraan Masjid), BP-4 (Badan Penasehat Perkawinan dan Penyelesaian Perceraian), MTQ (Musabaqoh Tilatil Quran)', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'KU.03.1', 'klasifikasi' => 'PAJAK - Surat -surat yang berkenan dengan pendapatan Negara dari hasil pajak yang meliputi : MPO (Menghitung Pajak Orang), Pajak Jasa, PPH (Pajak Pendapatan Penghasilan), Dan pajak lainnya.', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'KU.03.2', 'klasifikasi' => 'BUKAN PAJAK - Surat -surat yang berkenaan dengan pendapatan Negara dan hasil bukan pajak (nontax) yang meliputi penerimaan dari : Biaya penelitian hasil penerimaan Negara, Biaya NTCR, Biaya perkara dan hasil penjualan barang - barang inventaris yang dihapus', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'KU.04.1', 'klasifikasi' => 'VALUTA ASING/TRANSFER - Surat -surat yang berkenaan dengan pembelian Valuta Asing', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'KU.04.2', 'klasifikasi' => 'SURAT - SURAT YANG BERKENAAN DENGAN SALSO REKENING KORAN YANG ADA PADA BANK. - Ralat rekening, Surat pernyataan rekenig', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'KU.05.', 'klasifikasi' => 'SUMBANGAN /BANTUAN - Surat - surat yang berkenaan dengan permintaan, pemberian sumbangan/bantuan khusus di luar tugas pokok Kementerian Agama seperti : Becana alam, Kebakaran, Pekan olahraga, dan lain sebagainya.', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'LM.01', 'klasifikasi' => 'ADMINISTRASI - Permohonan Lembur', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'OT.00', 'klasifikasi' => 'ORGANISASI - Surat-surat yang berhubungan dengan pembentukan dan pengembangan organisasi serta analisis jabatan.', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'OT.01', 'klasifikasi' => 'TATALAKSANA - -', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'OT.01.1', 'klasifikasi' => 'PERENCANAAN - Surat surat yang berhubungan dengan perencanaan / program kerja, pengembangan organisasi dan kebijakan di bidang perencanaan.', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'OT.01.2', 'klasifikasi' => 'LAPORAN - Surat surat yang berhubungan dengan monitoring, evaluasi dan laporan antara lain : AKIP, Kinerja Menteri, Mingguan, Bulanan, Triwulan, Sementara.', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'OT.01.3', 'klasifikasi' => 'PENYUSUNAN PROSEDUR KERJA - Surat surat yang berhubungan dengan penyusunan sistem, pedoman, petunjuk pelaksanaan, petunjuk teknis dan pembakuan sarana kerja.', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'OT.01.4', 'klasifikasi' => 'PELAYANAN MASYARAKAT - Surat surat yang berhubungan dengan peningkatan pelayanan masyarakat antara lain : Penilaian kinerja unit pelayanan masyarakat, Penilaian kinerja Sumber Daya Manusia, Indek kepuasan masyarakat, Standar Pelayanan Minimal (SPM), Standar Pelayanan Prosedur (SPP), Standar Operasional Prosedur (SOP)', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'PP.00.1', 'klasifikasi' => 'SEKOLAH UMUM TINGKAT TK & SD - Surat-surat yang berkenaan dengan maslah-masalah kurikulum, tenaga edukatif, sarana pendidikan dan pengajaran termasuk subsidi dan bantuan pada TK dan SD serta masalah-masalah yang menyangkut siswa.', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'PP.00.10', 'klasifikasi' => 'PERGURUAN TINGKAT UMUM - Surat-surat yang berkenaan dengan maslah-masalah kurikulum, tenaga edukatif, sarana pendidikan dan pengajaran termasuk subsidi dan bantuan pada Perguruan Tinggi Umum termasuk Pasca purna sarjana.', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'PP.00.11', 'klasifikasi' => 'PENGEMBANGAN PENDIDIKAN - Surat-surat yang berkenaan dengan maslah-masalah kurikulum, tenaga edukatif, sarana pendidikan di lingkungan Kementerian Agama. Ruang ini juga untuk menampung maslah PP 00.1  s/d PP 00.11. yang termuat secara kolektif dalam suati surat', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'PP.00.2', 'klasifikasi' => 'SEKOLAH LANJUT TINGKAT PERTAMA (SLTP) - Surat-surat yang berkenaan dengan maslah-masalah kurikulum, tenaga edukatif, sarana pendidikan dan pengajaran termasuk subsidi dan bantuan pada SLTP serta masalah-masalah yang menyangkut siswa.', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'PP.00.3', 'klasifikasi' => 'SEKOLAH LANJUTAN TINGKAT ATAS (SLTA) - Surat-surat yang berkenaan dengan maslah-masalah kurikulum, tenaga edukatif, sarana pendidikan dan pengajaran termasuk subsidi dan bantuan pada SLTA serta masalah-masalah yang menyangkut siswa.', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'PP.00.4', 'klasifikasi' => 'RAUDATUL ATHFAL & MADRASAH IBTIDAIYAH - Surat-surat yang berkenaan dengan maslah-masalah kurikulum, tenaga edukatif, sarana pendidikan dan pengajaran termasuk subsidi dan bantuan pada Perguruan Agama Tinggi RA dan Madrasah Ibtidaiyah (prasekolah dan pratama).', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'PP.00.5', 'klasifikasi' => 'MADRASAH TSANAWIYAH - Surat-surat yang berkenaan dengan maslah-masalah kurikulum, tenaga edukatif, sarana pendidikan dan pengajaran termasuk subsidi dan bantuan pada Madrasah Tsanawiyah (menengah pertama).', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'PP.00.6', 'klasifikasi' => 'MADRASAH ALIYAH - Surat-surat yang berkenaan dengan maslah-masalah kurikulum, tenaga edukatif, sarana pendidikan dan pengajaran termasuk subsidi dan bantuan pada Madrasah Aliyah baik Madrasah maupun PGA.', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'PP.00.7', 'klasifikasi' => 'PONDOK PESANTREN - Surat-surat yang berkenaan dengan maslah-masalah kurikulum, tenaga edukatif, sarana pendidikan dan pengajaran termasuk subsidi dan bantuan pada Pondok Pesantren', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'PP.00.8', 'klasifikasi' => 'MADRASAH DINIYAH - Surat-surat yang berkenaan dengan maslah-masalah kurikulum, tenaga edukatif, sarana pendidikan dan pengajaran termasuk subsidi dan bantuan pada Madrasah Diniyah', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'PP.00.9', 'klasifikasi' => 'PERGURUAN TINGGI AGAMA - Surat-surat yang berkenaan dengan maslah-masalah yang menyangkut mahasiswa, kurikulum, tenaga edukatif, sarana pendidikan dan pengajaran termasuk subsidi dan bantuan pada Perguruan Tinggi Agama termasuk Pasca purna sarjana, rekomendasi', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'PP.01.1', 'klasifikasi' => 'PENDIDIKAN AGAMA - Surat-surat yang berkenaan dengan masalah-masalah yang menyangkut soal evaluasi / ujian dan ijazah dari tingkat TK/RA, MI, MTsN, Diniyah, Pondok Pesantren sampai Perguruan Tinggi Agama.', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'PP.01.2', 'klasifikasi' => 'PENDIDIKAN UMUM - Surat-surat yang berkenaan dengan masalah-masalah yang menyangkut soal evaluasi / ujian dan ijazah dari tingkat TK, SD, SLTP, SLTA, dan Perguruan Tinggi Umum.', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'PP.02.1', 'klasifikasi' => 'KEPENILIKAN - Surat-surat yang berkenaan dengan kegiatan kepenilikan pada TK/RA, SD/Ibtidaiyah dan Diniyah Waliyah', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'PP.02.2', 'klasifikasi' => 'KEPENGAWASAN - Surat-surat yang berkenaan dengan kegiatan kepenilikan pada SLTP/Tsanawiyah, SLTA/Aliyah, Pondok Pesantren dan Diniyah Wustho', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'PP.02.3', 'klasifikasi' => 'PEMBINAAN - Surat-surat yang berkenaan dengan kegiatan pembinaan pada Perguruan Tinggi Agama dan Perguruan Tinggi Umum di bidang keagamaan.', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'PP.03.1', 'klasifikasi' => 'ORGANISASI - Surat-surat yang menyangkut masalah intra maupun ekstra sekolah/mahasiswa/mahasiswi/guru maupun orang tua murid. Contoh (OSIS, MENWA, POMD, PGRI, Musyawarah guru mata pelajaran (MGMP) PAK, Kelompok Kerja Guru (KKG) dan sebagainyan', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'PP.03.2', 'klasifikasi' => 'PENGEMBANGAN - Surat-surat yang menyangkut masalah pengembangan, relokasi, fisial/kelas jauh, perubahan/persamaan/penyesuaian status swasta-negeri pada perguruan agama.', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'PP.04', 'klasifikasi' => 'BEASISWA - Surat-surat yang berkenaan dengan pemberian beasiswa baik dari pemerintah, swasta maupun dan luar negeri, termasuk anak asuh.', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'PP.05', 'klasifikasi' => 'SUMBANGAN - Surat-surat yang berkenaan dengan : Uang Sekolah, Uang Ujian, dan lain-lain yang sejenis.', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'PP.06', 'klasifikasi' => 'PENGABDIAN - Surat-surat yang berkenaan dengan pengabdian terhadap masyarakat seperti : KKN (Kerja Kuliah Nyata), Butsi (Badan Usaha Tenaga Sukarela Indonesia) dan kegiatan-kegiatan ekstra kurikuler lainnya.', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'PP.07', 'klasifikasi' => 'PERIZINAN - Surat - surat yang menyangkut masalah perizinan belajar/mengajar bagi lembaga/instansi/orang Indonesia ke luar negeri', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'PS.00', 'klasifikasi' => 'ADMINISTRASI UMUM - Surat-surat yang berkenaan dengan pengawasan adminstrasi umum yang terdiri dari: pengawasan tugas pokok, pengawasan kepegawaian, pengawasan keuangan, pengawasan perlengkapan, Sarana Tindak Lanjut (STL) dan Laporan Hasil Audit (LHA) serta Tindak Lanjut Hasil Audit (TLHA) nya.', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'PS.01', 'klasifikasi' => 'TUGAS UMUM - Surat-surat yang berkenaan dengan pengawasan tugas umum, yang meliputi bidang bidang: pendidikan agama, penerangan agama, urusan agama, bimbingan masyarakat beragama, peradilan agama, haji, penelitian dan pengembangan keagamaan', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'PS.02.1', 'klasifikasi' => 'FISIK - Surat-surat yang berkenaan dengan pengawasan proyek-proyek pembangunan fisik, termasuk Lpaoran hasil Pemeriksaan (LHP) maupun Tindak Lanjut Hasil Pemeriksaan (TLHP) nya.', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'PS.02.2', 'klasifikasi' => 'NON FISIK - Surat-surat yang berkenaan dengan pengawasan proyek-proyek pembangunan non fisik, termasuk Laporan Hasil Pemeriksaan (LHP) maupun Tindak Lanjut Hasil Pemeriksaan (TLHP) nya.', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'PS.03.1', 'klasifikasi' => 'BPK RI - Surat-surat yang berkenaan dengan pengawasan BPK RI, termasuk Laporan Hasil Pemeriksaan Semester (HASPEM) maupun Tindak Lanjut Hasil Pemeriksaan (TLHP) nya.', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'PS.03.2', 'klasifikasi' => 'BADAN PENGAWASAN KEUANGAN DAN PEMBANGUNAN (BPKP) - Surat-surat yang berkenaan dengan pengawasan BBPKP, termasuk Lpaoran Hasil Audit (LHA) maupun Tindak Lanjut Hasil Audit (TLHA) nya.', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'PS.03.3', 'klasifikasi' => 'PENGADUAN MASYARAKAT - Surat-surat yang berkenaan dengan pengaduan atau pengawasan dari masyarakat yang disampaikan melalui Tromol,Pos 5000 (TP 5000) termasuk tindak lanjutnya.', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'PS.04', 'klasifikasi' => 'PENGADUAN MASYARAKAT (NON TP 5000) - Surat-surat yang berkenaan dengan pengaduan atau pengawasan yang disampaikan secara langsung oleh masyarakat (non PT 5000), termasuk tindak lanjutnya.', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'PW. 00', 'klasifikasi' => 'PENYULAHAN - Surat-surat yang berkenaan dengan : penyuluhan perkawinan, KB (Keluarga Berencana) dan KKB (Keluarga Kecil Bahagia), BP 4 (Badan Penasehat Perkawinan dan Penyelesaian Perceraian) dan UPGK (Usaha Peningkatan Gizi Keluarga)', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'PW.01', 'klasifikasi' => 'PERKAWINAN - Surat-surat yang berkenaan dengan seluruh proses : nikah, talak, cerai, rujuk termasuk akte dan sarananya.', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'PW.02', 'klasifikasi' => 'CAMPURAN - Surat-surat yang berkenaan dengan seluruh proses perkawinan campuran antara agama dan bangsa', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'TL.00', 'klasifikasi' => 'PENELITIAN PENDIDIKAN - Surat-surat yang berhubungan dengan penelitian pendidikan, sejak dari perizinan, pelaksanaan sampai laporan hasilnya.', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'TL.01', 'klasifikasi' => 'PENELITIAN KEAGAMAAN - Surat-surat yang berhubungan dengan penelitian keagamaan, sejak dari perizinan, pelaksanaan sampai dengan laporan hasilnya.', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'TL.02.1', 'klasifikasi' => 'PENELITIAN LEKTUR AGAMA - Surat-surat yang berhubungan dengan penelitian atas penerbitan, impor dan penyebaran kitab-kitab suci agama.', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'TL.02.2', 'klasifikasi' => 'PENELITIAN BUKU-BUKU AGAMA - Surat-surat yang berhubungan dengan penelitian buku-buku agama yang diterbitkan, diimport dan penyebaran buku-buku agama.', 'is_aktif' => 1, 'user_id' => 1],
            ['kode' => 'TL.03', 'klasifikasi' => 'PENGEMBANGAN PENELITIAN - Surat-surat yang berhubungan dengan masalah-masalah pengembangan penelitian sejak dari Perencanaan, pelaksanaannya sampai dengan pelaporannya (proposal penelitian)', 'is_aktif' => 1, 'user_id' => 1],
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
            ["pola_surat_id" => 1, "user_id" => 1, "spesimen_jabatan_id" => 1],
            ["pola_surat_id" => 2, "user_id" => 1, "spesimen_jabatan_id" => 1],
            ["pola_surat_id" => 2, "user_id" => 1, "spesimen_jabatan_id" => 2],
            ["pola_surat_id" => 2, "user_id" => 1, "spesimen_jabatan_id" => 3],
            ["pola_surat_id" => 2, "user_id" => 1, "spesimen_jabatan_id" => 4],
            ["pola_surat_id" => 2, "user_id" => 1, "spesimen_jabatan_id" => 5],
            ["pola_surat_id" => 2, "user_id" => 1, "spesimen_jabatan_id" => 6],
            ["pola_surat_id" => 2, "user_id" => 1, "spesimen_jabatan_id" => 7],
            ["pola_surat_id" => 2, "user_id" => 1, "spesimen_jabatan_id" => 8],
            ["pola_surat_id" => 2, "user_id" => 1, "spesimen_jabatan_id" => 9],
            ["pola_surat_id" => 2, "user_id" => 1, "spesimen_jabatan_id" => 10],
            ["pola_surat_id" => 2, "user_id" => 1, "spesimen_jabatan_id" => 11],
            ["pola_surat_id" => 2, "user_id" => 1, "spesimen_jabatan_id" => 12],
            ["pola_surat_id" => 2, "user_id" => 1, "spesimen_jabatan_id" => 13],
            ["pola_surat_id" => 2, "user_id" => 1, "spesimen_jabatan_id" => 14],
            ["pola_surat_id" => 2, "user_id" => 1, "spesimen_jabatan_id" => 15],
            ["pola_surat_id" => 2, "user_id" => 1, "spesimen_jabatan_id" => 16],
            ["pola_surat_id" => 2, "user_id" => 1, "spesimen_jabatan_id" => 17],
            ["pola_surat_id" => 2, "user_id" => 1, "spesimen_jabatan_id" => 18],
            ["pola_surat_id" => 2, "user_id" => 1, "spesimen_jabatan_id" => 19],
            ["pola_surat_id" => 2, "user_id" => 1, "spesimen_jabatan_id" => 20],
            ["pola_surat_id" => 2, "user_id" => 1, "spesimen_jabatan_id" => 21],
            ["pola_surat_id" => 2, "user_id" => 1, "spesimen_jabatan_id" => 22],
            ["pola_surat_id" => 2, "user_id" => 1, "spesimen_jabatan_id" => 23],
            ["pola_surat_id" => 2, "user_id" => 1, "spesimen_jabatan_id" => 24],
            ["pola_surat_id" => 2, "user_id" => 1, "spesimen_jabatan_id" => 25],
            ["pola_surat_id" => 2, "user_id" => 1, "spesimen_jabatan_id" => 26],
            ["pola_surat_id" => 2, "user_id" => 1, "spesimen_jabatan_id" => 27],
            ["pola_surat_id" => 2, "user_id" => 1, "spesimen_jabatan_id" => 28],
            ["pola_surat_id" => 2, "user_id" => 1, "spesimen_jabatan_id" => 29],
            ["pola_surat_id" => 2, "user_id" => 1, "spesimen_jabatan_id" => 30],
            ["pola_surat_id" => 2, "user_id" => 1, "spesimen_jabatan_id" => 31],
            ["pola_surat_id" => 2, "user_id" => 1, "spesimen_jabatan_id" => 32],
            ["pola_surat_id" => 2, "user_id" => 1, "spesimen_jabatan_id" => 33],
            ["pola_surat_id" => 2, "user_id" => 1, "spesimen_jabatan_id" => 34],
            ["pola_surat_id" => 2, "user_id" => 1, "spesimen_jabatan_id" => 35],
            ["pola_surat_id" => 2, "user_id" => 1, "spesimen_jabatan_id" => 36],
            ["pola_surat_id" => 2, "user_id" => 1, "spesimen_jabatan_id" => 37],
            ["pola_surat_id" => 2, "user_id" => 1, "spesimen_jabatan_id" => 38],
            ["pola_surat_id" => 2, "user_id" => 1, "spesimen_jabatan_id" => 39],
            ["pola_surat_id" => 2, "user_id" => 1, "spesimen_jabatan_id" => 40],
            ["pola_surat_id" => 2, "user_id" => 1, "spesimen_jabatan_id" => 41],
            ["pola_surat_id" => 2, "user_id" => 1, "spesimen_jabatan_id" => 42],
            ["pola_surat_id" => 2, "user_id" => 1, "spesimen_jabatan_id" => 43],
            ["pola_surat_id" => 2, "user_id" => 1, "spesimen_jabatan_id" => 44],
            ["pola_surat_id" => 2, "user_id" => 1, "spesimen_jabatan_id" => 45],
            ["pola_surat_id" => 2, "user_id" => 1, "spesimen_jabatan_id" => 46],

            ["pola_surat_id" => 1, "user_id" => 1, "spesimen_jabatan_id" => 5],
            ["pola_surat_id" => 1, "user_id" => 1, "spesimen_jabatan_id" => 9],
            ["pola_surat_id" => 1, "user_id" => 1, "spesimen_jabatan_id" => 13],
            ["pola_surat_id" => 1, "user_id" => 1, "spesimen_jabatan_id" => 17],
            ["pola_surat_id" => 1, "user_id" => 1, "spesimen_jabatan_id" => 21],
            ["pola_surat_id" => 1, "user_id" => 1, "spesimen_jabatan_id" => 34],
        ];

        foreach ($dtdef as $dt) {
            PolaSpesimen::create([
                'pola_surat_id' => $dt['pola_surat_id'],
                'spesimen_jabatan_id' => $dt['spesimen_jabatan_id'],
                'user_id' => $dt['user_id'],
            ]);
        }

        //nilai default akses pola
        $dtdef = [
            ["tahun" => date('Y'), "pola_spesimen_id" => 1, "user_id" => 1],
            ["tahun" => date('Y'), "pola_spesimen_id" => 2, "user_id" => 1],
            ["tahun" => date('Y'), "pola_spesimen_id" => 3, "user_id" => 1],
            ["tahun" => date('Y'), "pola_spesimen_id" => 4, "user_id" => 1],
            ["tahun" => date('Y'), "pola_spesimen_id" => 5, "user_id" => 1],
            ["tahun" => date('Y'), "pola_spesimen_id" => 6, "user_id" => 1],
            ["tahun" => date('Y'), "pola_spesimen_id" => 3, "user_id" => 2],
            ["tahun" => date('Y'), "pola_spesimen_id" => 4, "user_id" => 2],
            ["tahun" => date('Y'), "pola_spesimen_id" => 5, "user_id" => 2],
            ["tahun" => date('Y'), "pola_spesimen_id" => 3, "user_id" => 3],
        ];

        foreach ($dtdef as $dt) {
            AksesPola::create([
                'tahun' => $dt['tahun'],
                'pola_spesimen_id' => $dt['pola_spesimen_id'],
                'user_id' => $dt['user_id'],
            ]);
        }

        // //nilai default qrcode
        // $dtdef = [
        //     [
        //         "no_surat" => "002/In.23/HM.00/I/2023",
        //         "kode" => generateKode(1),
        //         "jabatan" => "Rektor",
        //         "pejabat" => "Prof. Dr. Husain Insawan, M.Ag",
        //         "perihal" => "Permintaan data mahasiswa terbaik",
        //         "tanggal" => date('Y-m-d'),
        //         "file" => "https://surat.co.id",
        //         "is_diterima" => null,
        //         "is_diajukan" => 0,
        //         "user_ttd_id" => 1,
        //         "user_id" => 3,
        //     ],
        //     [
        //         "no_surat" => "001/In.23/HM.00/I/2023",
        //         "kode" => generateKode(2),
        //         "jabatan" => "Rektor",
        //         "pejabat" => "Prof. Dr. Husain Insawan, M.Ag",
        //         "perihal" => "Data Beassiwa Bank Indonesia",
        //         "tanggal" => date('Y-m-d'),
        //         "file" => "https://sia.iainkendari.ac.id",
        //         "is_diterima" => null,
        //         "is_diajukan" => 1,
        //         "user_ttd_id" => 1,
        //         "user_id" => 2,
        //     ],
        //     [
        //         "no_surat" => "003/In.23/HM.00/I/2023",
        //         "kode" => generateKode(3),
        //         "jabatan" => "Rektor",
        //         "pejabat" => "Prof. Dr. Husain Insawan, M.Ag",
        //         "perihal" => "Data Pegawai",
        //         "tanggal" => date('Y-m-d'),
        //         "file" => "https://google.co.id",
        //         "user_ttd_id" => 1,
        //         "is_diajukan" => 0,
        //         "is_diterima" => null,
        //         "user_id" => 1,
        //     ],
        // ];

        // foreach ($dtdef as $dt) {
        //     TtdQrcode::create([
        //         "no_surat" => $dt["no_surat"],
        //         "perihal" => $dt["perihal"],
        //         "pejabat" => $dt["pejabat"],
        //         "jabatan" => $dt["jabatan"],
        //         "kode" => $dt["kode"],
        //         "tanggal" => $dt["tanggal"],
        //         "file" => $dt["file"],
        //         "is_diterima" => $dt["is_diterima"],
        //         "is_diajukan" => $dt["is_diajukan"],
        //         "user_ttd_id" => $dt["user_ttd_id"],
        //         "user_id" => $dt["user_id"],
        //         "qrcode" => null,
        //     ]);
        // }


        // //nilai default surat keluar
        // $dtdef = [
        //     [
        //         "no_surat" => null,
        //         "no_indeks" => null,
        //         "asal" => "AKMA",
        //         "pola" => null,
        //         "tujuan" => "Para Dekan",
        //         "perihal" => "Data Kuota Maba",
        //         "ringkasan" => "",
        //         "tanggal" => date("Y-m-d"),
        //         "klasifikasi_surat_id" => "1",
        //         "pola_spesimen_id" => "2",
        //         "user_id" => 2,
        //         "catatan" => null,
        //         "is_diajukan" => 0,
        //         "is_diterima" => null,
        //     ],
        //     [
        //         "no_surat" => "001/In.23/HM.00/I/" . date("Y"),
        //         "no_indeks" => "1",
        //         "asal" => "AKMA",
        //         "pola" => "001/In.23/HM.00/I/" . date("Y"),
        //         "tujuan" => "Para Dekan",
        //         "perihal" => "Permintaan data mahasiswa terbaik",
        //         "ringkasan" => "",
        //         "tanggal" => date("Y-m-d"),
        //         "klasifikasi_surat_id" => "1",
        //         "pola_spesimen_id" => "2",
        //         "user_id" => 1,
        //         "is_diajukan" => 1,
        //         "is_diterima" => 1,
        //         "catatan" => null,
        //     ],
        //     [
        //         "no_surat" => null,
        //         "no_indeks" => null,
        //         "asal" => "AKMA",
        //         "pola" => "002/In.23/HM.00/I/" . date("Y"),
        //         "tujuan" => "Para Dekan",
        //         "perihal" => "Data Beasiswa",
        //         "ringkasan" => "",
        //         "tanggal" => date("Y-m-d"),
        //         "klasifikasi_surat_id" => "1",
        //         "pola_spesimen_id" => "2",
        //         "user_id" => 1,
        //         "is_diajukan" => 1,
        //         "is_diterima" => 0,
        //         "catatan" => null,
        //     ],
        // ];

        // foreach ($dtdef as $dt) {
        //     SuratKeluar::create([
        //         "no_surat" => $dt["no_surat"],
        //         "no_indeks" => $dt["no_indeks"],
        //         "asal" => $dt["asal"],
        //         "pola" => $dt["pola"],
        //         "perihal" => $dt["perihal"],
        //         "tujuan" => $dt["tujuan"],
        //         "ringkasan" => $dt["ringkasan"],
        //         "tanggal" => $dt["tanggal"],
        //         "klasifikasi_surat_id" => $dt["klasifikasi_surat_id"],
        //         "pola_spesimen_id" => $dt["pola_spesimen_id"],
        //         "user_id" => $dt["user_id"],
        //         "is_diterima" => $dt["is_diterima"],
        //         "is_diajukan" => $dt["is_diajukan"],
        //         "catatan" => $dt["catatan"],
        //     ]);
        // }
    }
}
