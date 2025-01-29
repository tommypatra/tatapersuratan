<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Surat Masuk</title>
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
            width: 95%;
            max-width: 95%;
            margin: 0 auto;
            /* border: 1px solid #000; */
            padding: 7px;
        }
        .header {
            display: flex;
            align-items: center; /* Membuat elemen sejajar secara vertikal */
            justify-content: flex-start; /* Membuat elemen dimulai dari kiri */
            width: 100%; /* Menjamin lebar header penuh */
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
                size: A4 portrait; /* Mengatur orientasi landscape */
                margin: 5mm; /* Margin sesuai kebutuhan */
            }
            body {
                zoom: 100%;
                margin: 0;
                padding: 0;
                display: flex;
                justify-content: center; /* Pusatkan konten secara horizontal */
                align-items: center; /* Pusatkan konten secara vertikal */
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
        <hr>

        <div style="text-align: center; padding-top:10px">
            <strong>DAFTAR SURAT MASUK</strong><br>
        </div>
        

        
    </div>
</body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

<script src="{{ asset('js/app.js') }}"></script>
<script>
    const authToken = localStorage.getItem('access_token');
    function getQueryParam(name) {
        const params = new URLSearchParams(window.location.search);
        return params.get(name);
    }

    const keyword = getQueryParam('keyword'); 
    const filter = getQueryParam('filter');


    $(document).ready(function () {        

        // Atur header default untuk semua request AJAX
        $.ajaxSetup({
            headers: {
                'Authorization': 'Bearer ' + authToken
            }
        });

        // Kirim request AJAX
        $.ajax({
            url: `${vBaseUrl}/api/surat-masuk?page=all&keyword=${keyword}&filter=${filter}`,
            type: "GET",
            success: function(response) {
                data=response.data;
                console.log(response);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('terjadi kesalahan');
            }
        });
    });


</script>
</html>
