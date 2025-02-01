<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lembar Disposisi</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset('images/logo.png') }}" />
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 16px;
            margin: 0;
            padding: 7px;
        }
        .container {
            width: 100%;
            max-width: 750px;
            margin: 0 auto;
            border: 1px solid #000;
            padding: 7px;
        }
        .header {
            display: flex;
            align-items: center; /* Membuat elemen sejajar secara vertikal */
            justify-content: flex-start; /* Membuat elemen dimulai dari kiri */
            width: 100%; /* Menjamin lebar header penuh */
            border: 1px solid #000; /* Opsional: menambahkan garis pada header */
            padding: 11px; /* Memberikan jarak dalam header */
            box-sizing: border-box; /* Menghitung padding dalam lebar total */
        }
        .header img {
            height: 71px;
            margin-right: 10px; /* Memberikan jarak antara logo dan teks */
        }
        .header-text {
            width:100%;
            text-align: center;
        }
        .table, .table th, .table td {
            border: 1px solid #000;
            border-collapse: collapse;
        }
        .table {
            width: 100%;
            margin-top: 5px;
        }
        .table th, .table td {
            padding: 3px;
            text-align: left;
        }
        .table th {
            text-align: center;
        }
        .checkbox {
            display: inline-block;
            margin-right: 5px;
        }

        .footer {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-top: 10px;
        }

        .footer .catatan {
            width: 85%;
        }

        .footer .qrcode {
            width: 15%;
            text-align: right;
        }

        .footer .table {
            width: 100%;
            margin-top: 10px;
        }

        @media print {
            @page {
                size: A5 portrait; /* Mengatur orientasi landscape */
                margin: 5mm; /* Margin sesuai kebutuhan */
            }
            body {
                zoom: 65%;
                margin: 0;
                padding: 0;
                display: flex;
                justify-content: center; /* Pusatkan konten secara horizontal */
                align-items: center; /* Pusatkan konten secara vertikal */
            }
            .container {
                width: 100%;
                max-width: 750px;
                border: 1px solid #000; /* Opsional: Garis batas */
                transform: scale(1); /* Tidak ada skala tambahan */
                transform-origin: center center; /* Pastikan transformasi dari tengah */
            }
        }
    </style>
    <script>
        const vBaseUrl = "{{ url('/') }}";
    </script>
</head>
<body>
    <div class="container">
        <!-- Header dengan Flexbox -->
        <div class="header">
            <!-- Logo -->
            <img src="{{ asset('images/logo.png') }}" alt="Logo">
            
            <!-- Teks Judul -->
            <div class="header-text">
                <strong>KEMENTERIAN AGAMA REPUBLIK INDONESIA</strong><br>
                INSTITUT AGAMA ISLAM NEGERI IAIN KENDARI<br>
                Jln Sultan Qaimuddin, no 17 Baruga, Kota Kendari, Sulawesi Tenggara<br>
                Email: iainkendari@yahoo.co.id | Website: iainkendari.ac.id<br>
            </div>
        </div>

        <div style="text-align: center;">
            <strong>LEMBAR DISPOSISI</strong><br>
            <strong>PERHATIAN:</strong> Dilarang memisahkan sehelai surat pun yang digabung dalam surat ini
        </div>
        
        <table class="table">
            <tr>
                <td>Nomor Surat</td>
                <td colspan="3" id="no_surat"></td>
            </tr>
            <tr>
                <td>Tanggal Surat</td>
                <td id="tanggal"></td>
                <td>Status Surat</td>
                <td>
                    <div class="checkbox"><input type="checkbox"> Asli</div>
                    <div class="checkbox"><input type="checkbox"> Tembusan</div>
                </td>
            </tr>
            <tr>
                <td>Lampiran</td>
                <td></td>
                <td>Sifat</td>
                <td>
                    <div class="checkbox"><input type="checkbox"> Sangat Segera / Kilat</div>
                    <div class="checkbox"><input type="checkbox"> Penting</div>
                    <div class="checkbox"><input type="checkbox"> Biasa</div>
                </td>
            </tr>
            <tr>
                <td>Diterima Tanggal</td>
                <td id="tanggal_diterima"></td>
                <td></td>
                <td>
                    <div class="checkbox"><input type="checkbox"> Sangat Rahasia</div>
                    <div class="checkbox"><input type="checkbox"> Rahasia</div>
                    <div class="checkbox"><input type="checkbox"> Biasa</div>
                </td>
            </tr>
            <tr>
                <td>No. Agenda</td>
                <td colspan="3" id="no_agenda"></td>
            </tr>
            <tr>
                <td>Dari</td>
                <td colspan="3" id="asal"></td>
            </tr>
            <tr>
                <td>Perihal</td>
                <td colspan="3" id="perihal"></td>
            </tr>
        </table>

        <table class="table">
            <thead>
                <tr>
                    <th>Pejabat Mandatori</th>
                    <th>Pengelola Keuangan</th>
                    <th>Petunjuk</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="text-align: left; vertical-align: top;">
                        <ul>
                            <li>Warek I</li>
                            <li>Warek II</li>
                            <li>Warek III</li>
                            <li>Kepala Biro AUK</li>
                            <li>Dekan Fakultas Tarbiyah</li>
                            <li>Dekan Fakultas Syariah</li>
                            <li>Dekan FEBI</li>
                            <li>Dekan Fakultas FUAD</li>
                            <li>Direktur Pascasarjana</li>
                            <li>Ketua LPM</li>
                            <li>Ketua LP2M</li>
                            <li>Ketua SPI</li>
                            <li>Ketua UPT Perpustakaan</li>
                            <li>Ketua TIPD</li>
                            <li>Ketua UPT Ma'had</li>
                            <li>Ketua UPT Pengembangan Bahasa</li>
                        </ul>
                    </td>
                    <td style="text-align: left; vertical-align: top;">
                        <ul>
                            <li>PPK RM</li>
                            <li>PPK PNBP</li>
                            <li>PPK Gaji</li>
                            <li>PPSPM</li>
                            <li>Bendahara penerimaan</li>
                            <li>Bendahara pengeluaran</li>
                            <li>PPABP</li>
                        </ul>
                    </td>
                    <td style="text-align: left; vertical-align: top;">
                        <ul>
                            <li>Setuju</li>
                            <li>Tolak</li>
                            <li>Untuk diketahui</li>
                            <li>Selesaikan</li>
                            <li>Sesuai catatan</li>
                            <li>Edarkan</li>
                            <li>Jawab</li>
                            <li>Dipertimbangkan</li>
                            <li>Perbaiki</li>
                            <li>Ingatkan</li>
                            <li>Simpan</li>
                            <li>Diarsipkan</li>
                            <li>Harap hadir / diwakili</li>
                            <li>Tindak lanjuti</li>
                        </ul>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="footer">
            <table style="width:100%">
                <tr>
                    <td style="width:90%;" valign="top"><b>Catatan Rektor:</b></td>
                    <td style="width:10%" valign="top">
                        <div class="qrcode" id="qrcode_label"></div>
                        <div class="token" style="margin-top: 10px;" id="token"></div>
                    </td>
                </tr>
            </table>
        </div>

        <table class="table">
            <tr>
                <td>Tindak lanjut pejabat</td>
                <td>Penyelesaian</td>
            </tr>
            <tr>
                <td style="height:50px;"></td>
                <td></td>
            </tr>
            <tr>
                <td>Tindak lanjut pejabat</td>
                <td>Penyelesaian</td>
            </tr>
            <tr>
                <td style="height:50px;"></td>
                <td></td>
            </tr>
            <tr>
                <td>Tindak lanjut pejabat</td>
                <td>Penyelesaian</td>
            </tr>
            <tr>
                <td style="height:50px;"></td>
                <td></td>
            </tr>
        </table>
        
    </div>
</body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

<script src="{{ asset('js/app.js?version=2') }}"></script>
<script>
    const id = {{ $id }};
    const access_token = localStorage.getItem('access_token');
    // console.log(authToken);

    function prosesPerihal(perihal) {
        const words = perihal.split(" ");   
        if (words.length > 4) {
            return words.slice(0, 4).join(" ") + " ...";
        } else {
            return perihal;
        }
    }    

    $.ajaxSetup({
        beforeSend: function(xhr) {
            xhr.setRequestHeader('Authorization', 'Bearer ' + localStorage.getItem('access_token'));
        },          
        complete: function(xhr) {
            let responHeader = xhr.getResponseHeader('Authorization');
            if (responHeader) {
                let newToken = responHeader.replace('Bearer ', '').trim();
                access_token = newToken;
                localStorage.setItem('access_token', newToken);
            }
            if (xhr.status === 401) {
                localStorage.clear();
    			window.location.replace(vBaseUrl+'/akun-keluar');
            }
        }
    });

    $(document).ready(function () {        

        cekStatusToken();
        cekAkses('admin');

        // Kirim request AJAX
        $.ajax({
            url: `${vBaseUrl}/api/cetak-lembar-disposisi/${id}`,
            type: "GET",
            success: function(response) {
                data=response.data;

                const createdAt = data.created_at;
                const formattedDate = new Date(createdAt).toISOString().split('T')[0]; // Mengambil format YYYY-MM-DD
                const perihal = (data.ringkasan!='')?`${data.perihal} (${data.ringkasan})`:data.perihal;

                $('#no_surat').text(data.no_surat);
                $('#tanggal').text(data.tanggal);
                $('#tanggal_diterima').text(formattedDate);                
                $('#no_agenda').text(data.no_agenda);
                $('#token').text(data.token);
                $('#asal').text(`${data.asal} (${data.tempat})`);
                $('#perihal').text(`${perihal}`);


                const qr_text = JSON.stringify({
                    id: data.id, 
                    api: "disposisi", 
                });     
                // const qr_ttd = `${data.no_surat} ${data.perihal} ${qr_link}`;
                new QRCode(document.getElementById('qrcode_label'), {
                    text: qr_text,
                    width: 100,
                    height: 100
                });                

            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('terjadi kesalahan');
                // forceLogout();
                // console.error("Error:", textStatus, errorThrown);
                // Jika perlu, tambahkan fungsi logout atau error handling lain
            }
        });
    });


</script>
</html>
