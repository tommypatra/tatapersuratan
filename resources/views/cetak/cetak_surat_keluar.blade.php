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
                size: Legal landscape; /* Mengatur orientasi landscape */
                margin: 5mm; /* Margin sesuai kebutuhan */
            }
            body {
                zoom: 80%;
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
            <div id="loading" style="display:none;">
                <img height="30" src="{{ url('images/loading2.gif') }}" alt="Loading..." > Loading...
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="table mt-3">
                <thead>
                    <tr>
                        <th>No</th>
                        <th style="width:10%">Kategori</th>
                        <th style="width:10%">Pejabat Berwenang</th>
                        <th style="width:30%">Nomor/ Perihal/ Tanggal Surat</th>
                        <th style="width:10%">Tujuan</th>
                        <th style="width:10%">Ringkasan</th>
                        <th style="text-align: center; width:20%">Lampiran</th>
                        <th style="width:20%">Sumber</th>
                    </tr>
                </thead>
                <tbody id="suratTableBody">
                </tbody>
            </table>
        
            <a href="javascript:;" id="pagination-next" data-halaman="1" style="display:none;">Berikutnya</a>


        </div>
        
    </div>
</body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

<script src="{{ asset('js/app.js') }}"></script>
<script>
    const base_url = "{{ url('/') }}";
    const tableBody = $('#suratTableBody');
    const authToken = localStorage.getItem('access_token');
    function getQueryParam(name) {
        const params = new URLSearchParams(window.location.search);
        return params.get(name);
    }

    const keyword = getQueryParam('keyword'); 
    const filter = getQueryParam('filter');

    $(document).ready(function () {        
        $.ajaxSetup({
            headers: {
                'Authorization': 'Bearer ' + authToken
            }
        });

        $(document).ajaxStart(function() {
			$('#loading').show();
		}).ajaxStop(function() {
			$('#loading').hide();
		});


        tableBody.empty();
        loadData(1);

        function loadData(page=1){
            $.ajax({
                url: `${vBaseUrl}/api/surat-keluar?per_page=75&page=${page}&keyword=${keyword}&filter=${filter}`,
                type: "GET",
                success: function(response) {
                    displayData(response);
                    console.log(response);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('terjadi kesalahan');
                }
            });
        }

        $('#pagination-next').click(function(){
            loadData($(this).data('halaman'));
        });


        function displayData(response) {
            var data = response.data;
            var meta = response.meta;
            var nomor = meta.from;

            if(meta.current_page<meta.last_page){
                $('#pagination-next').show();
                $('#pagination-next').data("halaman",meta.current_page+1);
            }else{
                $('#pagination-next').hide();
                alert('semua data telah ditampilkan');
            }
            if(data.length>0)
                $.each(data, function(index, dataSurat) {
                    var lampiran=`Belum terupload`;
                    var dt = dataSurat;
                    
                    if(dataSurat.jumlah_lampiran>0){
                        lampiran =`<ul style="margin: 0;padding-left:20px;">`;
                        $.each(dataSurat.lampiran_surat_keluar, function(i, dt) {
                            const dokumen=`${base_url}/${dt.upload.path}`;
                            lampiran +=`<li>`;
                            lampiran +=`<a href="${dokumen}" target="_blank">Lampiran Dokumen ${i+1}</a>`;
                            lampiran +=`</li>`;
                        });
                        lampiran +=`</ul>`;

                    }else{
                        lampiran="belum terupload";
                    }
                    const tujuan=(dataSurat.tujuan!=null)?dataSurat.tujuan:"";
                    const ringkasan=(dataSurat.ringkasan!=null)?dataSurat.ringkasan:"";
                    var row = `
                        <tr>
                            <td>${nomor++}</td>
                            <td>${dataSurat.pola_surat.kategori} </td>
                            <td>${dataSurat.spesimen_jabatan.jabatan}</td>
                            <td>
                                <div>Nomor : ${dataSurat.no_surat}</div> 
                                <div style="font-weight:bold;">${dataSurat.perihal}</div> 
                                <div style="font-style:italic;font-size:12px;">Tanggal : ${dataSurat.tanggal}</div>
                            </td>
                            <td>${tujuan}</td>
                            <td>${ringkasan}</td>
                            <td>
                                ${lampiran}                        
                            </td>
                            <td>${dataSurat.user.name}<div style="text-align:center;font-size:10px;">${dataSurat.created_at}</div></td>
                        </tr>
                    `;
                    tableBody.append(row);
                });
            else{
                var row = `
                        <tr>
                            <td colspan="10">Tidak ditemukan</td>
                        </tr>
                    `;
                tableBody.append(row);            
            }
        }        
    });


</script>
</html>
