@extends('admin.template')

@section('scriptHead')
<title>Surat Masuk</title>
<link href="{{ asset('js/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') }}" rel="stylesheet">
<link href="{{ asset('js/select2/dist/css/select2.min.css') }}" rel="stylesheet">
<link href="{{ asset('js/select2/dist/css/select2.custom.css') }}" rel="stylesheet">
<link href="{{ asset('js/img-viewer/viewer.min.css') }}" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
<style>

    #preview-container {
        width: 100%;
        height: auto; /* Sesuaikan tinggi otomatis */
        max-height: 80vh; 
        overflow: hidden;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto; /* Pusatkan preview */
    }

    #preview-container img {
        width: 100%;
        height: auto;
        max-height: 100%;
        object-fit: contain; /* Pastikan gambar tidak terdistorsi */
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    }
</style>
@endsection

@section('container')
<div class="container-fluid p-0">
    <h1 class="h3 mb-3">Surat Masuk</h1>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">

                    <header class="p-2 border-bottom">
                        <div class="container">
                            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
                        
                                <ul class="nav col-12 col-sm-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                                    <li><a href="javascript:;" id="btnRefresh" class="nav-link px-2 link-dark"><i class="align-middle" data-feather="refresh-cw"></i> Refresh</a></li>
                                    <li><a href="javascript:;" id="btnTambah" class="nav-link px-2 link-dark"><i class="align-middle" data-feather="plus-circle"></i> Tambah</a></li>
                                    <li><a href="javascript:;" id="btnFilter" class="nav-link px-2 link-dark" onclick="setfilter()"><i class="align-middle" data-feather="filter"></i> Filter</a></li>
                                </ul>                    

                                <form class="col-12 col-sm-auto mb-3 mb-lg-0 me-lg-3">                                        
                                    <input type="search" id="search-data" class="form-control" placeholder="Search..." aria-label="Search">
                                </form>                        
                            </div>
                        </div>
                    </header>

                </div>
                <div class="card-body">
                    
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a class="nav-link active" href="javascript:;" id="tabKonsep" onclick="setActiveTab('tabKonsep')">Arsip</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="javascript:;" id="tabAjukan" onclick="setActiveTab('tabAjukan')">Diajukan</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="javascript:;" id="tabTerima" onclick="setActiveTab('tabTerima')">Diterima</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="javascript:;" id="tabTolak" onclick="setActiveTab('tabTolak')">Ditolak</a>
                        </li>
                    </ul>

                    <select class="form-control mt-2" id="filter_kategori_surat" >
                        <option value="SEMUA">- SEMUA -</option>
                        @php
                            $kategori = explode(',', env('KATEGORI_SURAT_MASUK'));
                        @endphp
                        @foreach ($kategori as $item)
                            <option value="{{ trim($item) }}">{{ trim($item) }}</option>
                        @endforeach
                    </select>
                    
                    <div class="table-responsive">
                        <table class="table mt-1">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th width="40%">Surat Masuk</th>
                                    <th width="20%" style="text-align: center">Lampiran</th>
                                    <th width="25%">Disposisi</th>
                                    <th>Pengelola</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="suratMasukTableBody">
                                <!-- Data will be dynamically populated here -->
                            </tbody>
                        </table>
                    
                        <nav aria-label="Page navigation">
                            <ul class="pagination" id="pagination">
                                <!-- Pagination links will be dynamically populated here -->
                            </ul>
                            <div id="pagination-jumlah-data"></div>
                        </nav>


                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- MULAI MODAL -->
<div class="modal fade modal-lg" id="modal-form" role="dialog">
    <div class="modal-dialog">
        <form id="myForm">
            <input type="hidden" name="id" id="id" >
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-label">Form Surat Masuk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body ">
                    <div class="row">
						<div class="col-sm-4 mb-3">
                            <label class="form-label">Kategori</label>
                            <select class="form-control" id="kategori_surat" name="kategori_surat" required>
                                <option value="">- Pilih -</option>
                                @php
                                    $kategori = explode(',', env('KATEGORI_SURAT_MASUK'));
                                @endphp
                                @foreach ($kategori as $item)
                                    <option value="{{ trim($item) }}">{{ trim($item) }}</option>
                                @endforeach
                            </select>
                        </div>
						<div class="col-sm-4 mb-3">
                            <label class="form-label">Tanggal Surat</label>
                            <input name="tanggal" id="tanggal" type="text" class="form-control datepicker" value="{{ date("Y-m-d") }}" placeholder="" required>
                        </div>

                        <div class="col-sm-4 mb-3" id="div_no_agenda" style="display:none;">
                            <label class="form-label">Nomor Agenda</label>
                            <input name="no_agenda" id="no_agenda" type="text" class="form-control" placeholder="" required>
                        </div>
						<div class="col-sm-8 mb-3">
                            <label class="form-label">Nomor Surat</label>
                            <input name="no_surat" id="no_surat" type="text" class="form-control" placeholder="" required>
                        </div>
						<div class="col-sm-4 mb-3">
                            <label class="form-label">Sifat Surat</label>
                            <select class="form-control" id="kategori_surat_masuk_id" name="kategori_surat_masuk_id" required></select>
                        </div>
						<div class="col-sm-6 mb-3">
                            <label class="form-label">Daerah Asal Surat</label>
                            <input name="asal" id="asal" type="text" class="form-control" placeholder="ex : kendari atau jakarta, dst" required>
                        </div>
						<div class="col-sm-6 mb-3">
                            <label class="form-label">Nama Institusi</label>
                            <input name="tempat" id="tempat" type="text" class="form-control" placeholder="ex : Kemenag RI, Dirjen Pendis, Gubernur Sultra" required>
                        </div>
						<div class="col-sm-12 mb-3">
                            <label class="form-label">Perihal</label>
                            <textarea name="perihal" id="perihal" class="form-control" rows="4" required></textarea>
                        </div>
						<div class="col-sm-12 mb-3">
                            <label class="form-label">Ringkasan</label>
                            <textarea name="ringkasan" id="ringkasan" class="form-control" rows="4" ></textarea>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary ">Simpan</button>
                    <button type="button" class="btn btn-outline-primary " data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Modal Upload --}}
<div class="modal fade modal-lg" id="modal-upload" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-upload-label">Ambil Gambar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="area-video">
                    <video id="video" autoplay playsinline class="img-fluid"></video>
                </div>
                <div id="preview-container"></div>                   
            </div>
            <div class="modal-footer">
                <button id="capture-btn" class="btn btn-success">Ambil Foto</button>
                <button type="submit" id="crop-btn" class="btn btn-primary" style="display:none;">Simpan Gambar</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>

        </div>
    </div>
</div>
<!-- AKHIR MODAL -->

{{-- Modal Upload --}}
<div class="modal fade" id="modal-disposisi" role="dialog">
    <div class="modal-dialog">
        <form id="form-disposisi">
            <input type="hidden" name="surat_masuk_id" id="surat_masuk_id">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-disposisi-label">Proses Disposisi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="col-sm-12 mb-3">
                        <label class="form-label">Disposisi Pertama</label>
                        {{-- <select class="form-control" id="select_user_id" name="user_id[]" required multiple="multiple"></select> --}}
                        <select class="form-control" id="select_user_id" name="user_id" required></select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- AKHIR MODAL -->

@endsection

@section('scriptJs')

<script src="{{ asset('js/bootstrap-material-moment/moment.js') }}"></script>
<script src="{{ asset('js/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js') }}"></script>
<script src="{{ asset('js/select2/dist/js/select2.min.js') }}"></script>
<script src="{{ asset('js/select2lib.js') }}"></script>
<script src="{{ asset('js/crud.js') }}"></script>
<script src="{{ asset('js/img-viewer/viewer.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script src="{{ url('js/foto-dokumen.js')}}"></script>

<script type="text/javascript">
    cekAkses('pengguna');
    var vApi='/api/surat-masuk';
    var vJudul='Surat Masuk';
    var vPage = 1;
    var vsurat_masuk_id;

    var hakAkses = vAksesId;
    var tahunFilter = '{{ date("Y") }}';
    const fotoDokumen = new FotoDokumen('video', 'preview-container', 'capture-btn', 'crop-btn');


    function setfilter(){
        tahunFilter = prompt('Masukkan tahun:');
        if (!isValidTahun(tahunFilter)) {
            appShowNotification(false,['Tahun yang dimasukkan tidak valid. Mohon masukkan tahun yang benar.']);
        }else{
            refresh();
        }
    }

    function isValidTahun(input) {
        const regex = /^\d{4}$/;
        return regex.test(input);
    }   


    // Fungsi untuk mengatur kelas active
    function setActiveTab(tabId) {
        $('.nav-link').removeClass('active'); 
        $('#' + tabId).addClass('active');
        console.log(tabId);
        switch (tabId) {
            case 'tabKonsep':
                loadDataKonsep();
                break;
            case 'tabAjukan':
                loadDataMasuk();
                break;
            case 'tabTerima':
                loadDataDiterima();
                break;
            case 'tabTolak':
                loadDataDitolak();
                break;
            default:
                loadDataMasuk();
                break;
        }    
    }

    $('.datepicker').bootstrapMaterialDatePicker({
        weekStart: 0,
        format: 'YYYY-MM-DD',
        time: false,
    });
    loadKategorSurat();
    function loadKategorSurat() {
        $('#kategori_surat_masuk_id').empty().trigger("change");
        $.ajax({
            url: '/api/get-kategori-surat-masuk?per_page=all',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                let dataparent = [{ id: "", text: "" }];
                jQuery.each(response.data, function (i, val) {
                    dataparent.push({ id: val['id'], text: val['kategori'] });
                });
                sel2_datalokal("#kategori_surat_masuk_id", dataparent, null,'#modal-form .modal-content');
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    }


    $(document).on('click','.imgprev',function() {
        var image = new Image();
        image.src = $(this).data('url');
        var viewer = new Viewer(image, {
            toolbar: {
                zoomIn: 4,
                zoomOut: 4,
                oneToOne: 4,
                reset: 4,
                prev: 0,
                play: {
                    show: 0,
                    size: 'large',
                },
                next: 0,
                rotateLeft: 4,
                rotateRight: 4,
                flipHorizontal: 4,
                flipVertical: 4,
            },            
            hidden: function() {
                viewer.destroy();
            },
        });        
        viewer.show();
    });    

    function loadData(page = 1) {
        let filter='';
        // if(hakAkses>1)
        //     filter=`{"user_id":"${vUserId}"}`;

        vPage = page;
        $.ajax({
            // url: '/api/surat-masuk?page=' + page +'&filter=' + filter,
            url: '/api/surat-masuk?page=' + page,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                displayData(response);
                displayPagination(response);
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    }

    //pencarian data
    $('#search-data').on('input', function() {
        var keyword = $(this).val();
        var filter='';
        if(hakAkses>1)
            filter=`{"user_id":"${vUserId}"}`;

        if (keyword.length == 0 || keyword.length >= 3) {
            $.ajax({
                url: '/api/surat-masuk?page=1&keyword=' + keyword + '&filter=' + filter,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    displayData(response);
                    displayPagination(response);
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }
    });    

    function refreshData(){
        $('#search-data').val("");
        CrudModule.refresh(displayData);
    }

    $('#btnRefresh').on('click', function() {
        refreshData()
    });

    //read showdata
    function displayData(response) {
        var data = response.data;
        var tableBody = $('#suratMasukTableBody');
        var nomor = response.meta.from;
        tableBody.empty();
        if(data.length>0)
            $.each(data, function(index, suratMasuk) {
                var lampiran=`<span class="badge bg-danger">Belum terupload</span>`;
                var status_disposisi=lampiran;
                var track_disposisi=``;
                var menu_detail=``;
                var dt = suratMasuk;
                var pembuat_surat=false;
                var labelApp=labelSetupVerifikasi(dt.is_diajukan,dt.is_diterima,dt.catatan,dt.verifikator);
                var menu_edit=``;

                var btnUpload=` <div class="btn-group-sm mt-1">
                                    <a href="javascript:;" class="btn btn-primary uploadLampiran" data-surat_masuk_id="${suratMasuk.id}"><i class="fa-solid fa-upload"></i></a>
                                    <a href="javascript:;" class="btn btn-primary fotoLampiran" onclick="upload(${suratMasuk.id})"><i class="fa-solid fa-camera"></i></a>
                                </div>`;
                
                if(dt.user_id==vUserId){
                    pembuat_surat=true;
                    if(dt.is_diajukan!=1){                    
                        menu_edit+=`<li><a class="dropdown-item" href="javascript:;" onclick="ganti(${dt.id})"><i class="fa-solid fa-pen-to-square"></i> Ganti</a></li>
                                    <li><a class="dropdown-item" href="javascript:;" onclick="hapus(${dt.id})"><i class="fa-solid fa-trash"></i> Hapus</a></li>`;
                        if(suratMasuk.jumlah_lampiran>0){
                            menu_edit+=` <li><a class="dropdown-item" href="javascript:;" onclick="ajukan(${dt.id})"><i class="fa-regular fa-share-from-square"></i> Ajukan</a></li>`;
                        }
                    }
                }
                
                if(dt.is_diajukan){
                    menu_edit=``;

                    if(dt.is_diterima==null && (hakAkses==1)){
                        menu_edit=` <li><a class="dropdown-item" href="javascript:;" onclick="validasi(1,${dt.id})"><i class="fa-solid fa-envelope-circle-check"></i> Terima</a></li>
                                    <li><a class="dropdown-item" href="javascript:;" onclick="validasi(0,${dt.id})"><i class="fa-solid fa-rectangle-xmark"></i> Tolak</a></li>
                                    <li><a class="dropdown-item" href="javascript:;" onclick="kembalikan(${dt.id})"><i class="fas fa-arrow-left"></i> Kembalikan Ke Arsip</a></li>`;

                    }
                    if(dt.is_diterima==null)
                        menu_edit+=`<li><a class="dropdown-item" href="javascript:;" onclick="hapus(${dt.id})"><i class="fa-solid fa-trash"></i> Hapus</a></li>`;
                }

                if(suratMasuk.is_diterima && hakAkses!=1){
                    btnUpload=``;
                }

                if(suratMasuk.jumlah_lampiran>0){
                    status_disposisi='<span class="badge bg-danger">Belum diterima</span>';
                    lampiran =`<ul style="list-style: none;margin: 0;padding: 0; font-size:11px;" class="fa-ul images">`;
                    $.each(suratMasuk.lampiran_surat_masuk, function(i, dt) {
                        lampiran +=`<li><span class="fa-li"><i class="fa-solid fa-arrow-up-right-from-square"></i></span>`;
                        if(is_image(dt.upload.type))
                            lampiran +=`<a href="javascript:;" data-url="${dt.upload.path}" class="imgprev" target="_self">${dt.upload.name}</a>`;
                        else
                            lampiran +=`<a href="${dt.upload.path}" target="_blank">${dt.upload.name}</a>`;

                        if(!suratMasuk.is_diterima)
                            lampiran +=` <a href="javascript:;" onclick="hapusLampiranSuratMasuk(${dt.id},${dt.upload.id})"><i class="fa-regular fa-trash-can"></i></a>`;

                        lampiran +=` </li>`;

                    });
                    lampiran +=`</ul>`;
                    if(suratMasuk.is_diterima){
                        
                        if(hakAkses==1){
                            status_disposisi=`<span class="btn btn-primary btn-sm" onclick="prosesDisposisi(${suratMasuk.id})"><i class="fa-solid fa-envelopes-bulk"></i> Proses Disposisi</span>`;
                        }else{
                            status_disposisi=`<span class="badge bg-warning"> Dalam proses</span>`;
                        }
                        track_disposisi =`<span class="badge bg-warning">Belum terdisposisi</span>`;
                        if(suratMasuk.tujuan.length>0){
                            status_disposisi="";
                            // menu_detail=`<li><a class="dropdown-item" href="/disposisi-detail/${suratMasuk.id}"><i class="fa-brands fa-readme"></i> Detail Disposisi</a></li>`;
                            track_disposisi =`<div style="font-size:11px;">`;
                            let conv;
                            let badge_clr;
                            track_disposisi +=`<ul style="list-style: none;margin: 0;padding: 0;" class="fa-ul">`;
                            $.each(suratMasuk.tujuan, function(i, dt) {
                                conv=waktuLalu(dt.created_at);
                                badge_clr='danger'; 
                                badge_icon='check'; 
                                let hapusDispo=`<a href="javascript:;" onclick="hapus(${dt.id},'/api/tujuan/')"><i class="fa-regular fa-trash-can"></i></a>`;
                                if(dt.waktu_akses!==null){
                                    badge_clr='success'; 
                                    badge_icon='check-double'; 
                                    hapusDispo='';
                                }
                                track_disposisi +=`
                                    <li>
                                        <span class="fa-li"><i class="fa-solid fa-${badge_icon}"></i></span>
                                        <i class="fa-regular fa-clock"></i> ${conv} - ${dt.user.name} ${hapusDispo}
                                    </li>`;
                            });
                            track_disposisi +=`</ul></div>`;
                        }
                    }
                    
                }else{
                    lampiran="wajib upload dokumen sebelum diajukan";
                }

                if(suratMasuk.is_diterima && hakAkses==1)
                    menu_edit=`<li><a class="dropdown-item" href="javascript:;" onclick="ganti(${dt.id})"><i class="fa-solid fa-pen-to-square"></i> Ganti</a></li>
                               <li><a class="dropdown-item" href="javascript:;" onclick="hapus(${dt.id})"><i class="fa-solid fa-trash"></i> Hapus</a></li>
                               <li><a class="dropdown-item" href="${vBaseUrl}/cetak-lembar-disposisi/${suratMasuk.id}" target="_blank"><i class="fa-solid fa-print"></i> Cetak Lembar Disposisi</a></li>`;
                else if(suratMasuk.is_diterima==0 && hakAkses==1)
                    menu_edit=`<li><a class="dropdown-item" href="javascript:;" onclick="kembalikan(${dt.id})"><i class="fas fa-arrow-left"></i> Kembalikan Ke Arsip</a></li>
                               <li><a class="dropdown-item" href="javascript:;" onclick="hapus(${dt.id})"><i class="fa-solid fa-trash"></i> Hapus</a></li>`;
                const vno_agenda=(suratMasuk.no_agenda>0)?`(${suratMasuk.no_agenda})`:"";
                var row = `
                    <tr>
                        <td>${nomor++}</td>
                        <td>
                            <h3>${suratMasuk.kategori_surat} ${vno_agenda}</h3>                             
                            <span class="badge bg-success">Tanggal : ${suratMasuk.tanggal}</span><br>
                            No. ${suratMasuk.no_surat} 
                            <div style="font-weight:bold;">${suratMasuk.perihal}</div>
                            <div style="font-style:italic;">Asal : ${suratMasuk.asal} (${suratMasuk.tempat})</div>
                            <div style="font-size:11px;">[Kategori : ${suratMasuk.kategori_surat_masuk.kategori}]</div>
                            <div>${labelApp.catatan}</div>
                        </td>
                        <td style="text-align: center">
                            <div>${labelApp.label}</div>
                            ${btnUpload}
                            ${lampiran}                        
                        </td>
                        <td>
                            ${status_disposisi}
                            ${track_disposisi}
                            <div style="font-size:11px;">Token : ${suratMasuk.token}</div>
                        </td>
                        <td>${suratMasuk.user.name}<div style="text-align:center;font-size:10px;">${suratMasuk.created_at}</div></td>
                        <td>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"></button>
                                <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                    <li><a class="dropdown-item" href="{{ asset('surat-masuk-detail/${dt.id}') }}" target="_blank"><i class="fa-solid fa-newspaper"></i> Selengkapnya</a></li>
                                    ${menu_edit}
                                </ul>
                            </div>                    
                        </td>
                    </tr>
                `;
                tableBody.append(row);
            });
        else{
            var row = `
                    <tr>
                        <td colspan="5">Tidak ditemukan</td>
                    </tr>
                `;
            tableBody.append(row);            
        }
    }

//pagination
function displayPagination(response) {
        // console.log(response);
        var currentPage = response.meta.current_page;
        var lastPage = response.meta.last_page;
        var pagination = $('#pagination');
        var paginationData = $('#pagination-jumlah-data');
        pagination.empty();
        paginationData.empty();
        if(response.meta.total>0){
            for (let i = 1; i <= lastPage; i++) {
                var liClass = (i === currentPage) ? 'page-item active' : 'page-item';
                var linkClass = 'page-link';
                var link = `<li class="${liClass}"><a href="javascript:;" class="${linkClass}" data-page="${i}">${i}</a></li>`;
                pagination.append(link);
            }

            paginationData.html(`<div>Data ke <span class="badge bg-secondary">${response.meta.from}</span> dari <span class="badge bg-secondary">${response.meta.total}</span> total data</div>`);

            $('.page-link').on('click', function(e) {
                e.preventDefault();
                var page = $(this).data('page');
                loadData(page);
            });
        }            
    }

    //tambah
    $("#btnTambah").click(function() {
        showModalForm();
        $("#no_agenda").val("0");
        $("#div_no_agenda").hide();
    });    

    //untuk showModal untuk tambah
    function showModalForm(){
        $('#myForm')[0].reset();
        $("#kategori_surat_masuk_id").val("").trigger("change"); 
        $("#id").val("");
        let myModalForm = new bootstrap.Modal(document.getElementById('modal-form'), {
            backdrop: 'static',
            keyboard: false,
        });
        myModalForm.toggle();
    }

    function hapus(id) {
        if(confirm("apakah anda yakin?")){
            $.ajax({
                url: 'api/surat-masuk/'+id,
                method: 'DELETE',
                dataType: 'json',
                success: function(response) {
                    refresh();
                    InfoModule.updateNotifWeb();
                },
                error: function(xhr, status, error) {
                    appShowNotification(false,[error]);
                }
            });
        }
    }

    function kembalikan(id) {
        if(confirm("apakah anda yakin kembalikan surat ini ?")){
            catatan = prompt("Tuliskan alasan sederhana mengapa dikembalikan?");
            if(catatan){
                $.ajax({
                    url: `/api/kembalikan-surat-masuk/${id}`,
                    type: "PUT",
                    data: {
                        'catatan':catatan,
                    },
                    dataType: 'json',
                    success: function (response) {
                        if(response.success){
                            appShowNotification(true, [response.message]);
                            refresh();
                            InfoModule.updateNotifWeb();
                        }
                    },
                    error: function (xhr, status, error) {
                        appShowNotification(false, ["Something went wrong. Please try again later."]);
                    }
                });                
            }else 
                alert('wajib ada catatan');
        }
    }

    function populateEditForm(data){
        // console.log(data);
        $("#id").val(data.id);
        $("#asal").val(data.asal);
        $("#no_agenda").val(data.no_agenda);        
        $("#no_surat").val(data.no_surat);        
        $("#perihal").val(data.perihal);        
        $("#ringkasan").val(data.ringkasan);        
        $("#tanggal").val(data.tanggal);        
        $("#tempat").val(data.tempat);
        $("#kategori_surat").val(data.kategori_surat);
        
        $("#kategori_surat_masuk_id").val(data.kategori_surat_masuk_id).trigger("change"); 
    }

    $("#myForm").validate({
        messages: {
            no_agenda: "agenda tidak boleh kosong",
            no_surat: "nomor surat tidak boleh kosong",
            perihal: "perihal tidak boleh kosong",
            asal: "asal surat tidak boleh kosong",
            tempat: "tempat surat tidak boleh kosong",
            kategori_surat_masuk: "sifat surat tidak boleh kosong",
            kategori_surat: "kategori surat tidak boleh kosong",
            
        },
        submitHandler: function(form) {
            let setup_ajax={type:'POST',url:'/api/surat-masuk'};
            let id=$("#id").val();
            if (id !== "")
                setup_ajax={type:'PUT',url:'/api/surat-masuk/'+id};
            simpan(setup_ajax,form)
        }
    });  
    
    $("#form-disposisi").validate({
        messages: {
            user_id: "pilih user dulu",
        },
        submitHandler: function(form) {
            simpanTujuan()
        }
    });  
    
    function simpanTujuan(form) {
        var select_user_id = $('#select_user_id').val();
        var surat_masuk_id = $('#surat_masuk_id').val(); 

        $.ajax({
            url: '/api/tujuan',
            type: 'POST',
            dataType: 'json',
            data: {
                surat_masuk_id: surat_masuk_id,
                user_id: select_user_id,
                // created_by: vUserId,
            },
            success: function (response) {
                if(response.success){
                    refresh();
                    InfoModule.updateNotifWeb();
                    $('#modal-disposisi').modal('hide');
                }   
                appShowNotification(response.success,[response.message]);
            },
            error: function (xhr, status, error) {
                appShowNotification(false,['Terjadi kesalahan, '+error]);
            }
        });    
        // multiple user
        // jQuery.each(select_user_id, function (i, val) {
        //     $.ajax({
        //         url: '/api/tujuan',
        //         type: 'POST',
        //         dataType: 'json',
        //         data: {
        //             surat_masuk_id: surat_masuk_id,
        //             user_id: val,
        //         },
        //         success: function (response) {
        //             if(response.success){
        //                 refresh();
        //             }
        //             appShowNotification(response.success,[response.message]);
        //         },
        //         error: function (xhr, status, error) {
        //             appShowNotification(false,['Terjadi kesalahan, '+error]);
        //         }
        //     });    
        // });
    }		    

    function simpan(setup_ajax,form) {
        $.ajax({
            type: setup_ajax.type,
            url: setup_ajax.url,
            data: $(form).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    refreshData();
                    InfoModule.updateNotifWeb();
                    $('#modal-form').modal('hide');
                } 
                appShowNotification(response.success,[response.message]);
            },
            error: function(xhr, status, error) {
                appShowNotification(false,['Something went wrong. Please try again later.']);
            }
        });
    }		    

    function ganti(id) {
        $.ajax({
            type: 'GET',
            url: '/api/surat-masuk/'+id,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $("#div_no_agenda").show();
                    showModalForm();
                    InfoModule.updateNotifWeb();
                    populateEditForm(response.data);
                }else 
                    appShowNotification(response.success,[response.message]);
            },
            error: function(xhr, status, error) {
                appShowNotification(false,['Something went wrong. Please try again later.']);
            }
        });
    }
    
    function prosesDisposisi(surat_masuk_id){
        $('#form-disposisi')[0].reset();
        $("#surat_masuk_id").val(surat_masuk_id);
        $('#select_user_id').val("").trigger("change");
        let myModalForm = new bootstrap.Modal(document.getElementById('modal-disposisi'), {
            backdrop: 'static',
            keyboard: false,
        });
        myModalForm.toggle();
    }

    function linkLampiran(upload_id,surat_masuk_id){
        var formData = {upload_id:upload_id,surat_masuk_id:surat_masuk_id};
        // console.log(formData);
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: "/api/lampiran-surat-masuk",
            data: formData,
            success: function (response) {
                if (response.success) {
                    $('#modal-upload').modal('hide');
                    refresh();
                    appShowNotification(response.success, [response.message]);
                } else {
                    appShowNotification(false, ["Failed to upload attachment."]);
                }
            },
            error: function (xhr, status, error) {
                appShowNotification(false, ["Something went wrong. Please try again later."]);
            },
        });
    }

    function hapusLampiranSuratMasuk(id,upload_id){
        if(confirm("apakah anda yakin?")){
            $.ajax({
                type: "DELETE",
                url: "/api/lampiran-surat-masuk/"+id,
                dataType: 'json',
                success: function (response) {
                    refresh();
                },
                error: function (xhr, status, error) {
                    appShowNotification(false, ["Something went wrong. Please try again later."]);
                },
            });
        }
    }

   

    $(document).on("click", ".uploadLampiran", function () {
        var surat_masuk_id = $(this).data("surat_masuk_id");
        var fileInput = $('<input type="file" id="lampiran" name="lampiran" accept=".jpg, .jpeg, .png, .pdf, .doc, .docx, .ppt, .pptx, .xls, .xlsx" style="display: none;">');
        $("body").append(fileInput);
        fileInput.click();

        fileInput.change(function () {
            var selectedFile = this.files[0];
            if (selectedFile) {
                uploadFile(surat_masuk_id, selectedFile);
            }
        });
    });

    function uploadFile(surat_masuk_id, file, fileName) {
        const formData = new FormData();
        formData.append("surat_masuk_id", surat_masuk_id);
        formData.append("file", file, fileName);

        $.ajax({
            url: "/api/upload",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.success) {
                    linkLampiran(response.data.id, surat_masuk_id);
                } else {
                    appShowNotification(false, ["Failed to upload attachment."]);
                }
            },
            error: function (xhr, status, error) {
                appShowNotification(false, ["Something went wrong. Please try again later."]);
            }
        });
    }  

    function ajukan(id){
        if(confirm("apakah anda yakin?")){
            $.ajax({
                url: "/api/ajukan-surat-masuk",
                type: "POST",
                data: {'id':id},
                dataType: 'json',
                success: function (response) {
                    if(response.success){
                        refresh();
                        InfoModule.updateNotifWeb();
                    }
                    appShowNotification(response.success, response.msg);
                },
                error: function (xhr, status, error) {
                    appShowNotification(false, ["Something went wrong. Please try again later."]);
                }
            });
        }
    }    

    function validasi(is_diterima,id){
        var label='tolak';
        var catatan='';
        if(is_diterima){
            label='terima';
        }

        if(confirm('apakah anda yakin '+label+' usulan ini?')){
            if(!is_diterima)
                catatan = prompt("Tuliskan alasan sederhana mengapa ditolak?");

            $.ajax({
                url: "/api/proses-ajuan-surat-masuk",
                type: "POST",
                data: {
                    'id':id,
                    'is_diterima':is_diterima,
                    'catatan':catatan,
                },
                dataType: 'json',
                success: function (response) {
                    if(response.success){
                        appShowNotification(true, [response.message]);
                        refresh();
                        InfoModule.updateNotifWeb();
                    }
                },
                error: function (xhr, status, error) {
                    appShowNotification(false, ["Something went wrong. Please try again later."]);
                }
            });                
        }
    }    

    sel2_cariUser(3,'#select_user_id','#form-disposisi .modal-content');

    //modal untuk ambil dari kamera
    function upload(id){
        vsurat_masuk_id=id;
        fotoDokumen.setValues(id,'surat-masuk');
        let myModalUpload = new bootstrap.Modal(document.getElementById('modal-upload'), {
            backdrop: 'static',
            keyboard: false,
        });
        myModalUpload.toggle();
    }

    // $(document).on('click','#btn-save',function () {
    //     var selectedUsers = $('#select-users').val();
    //     var surat_masuk_id = $('#surat_masuk_id').val();

    //     jQuery.each(selectedUsers, function (i, val) {
    //         $.ajax({
    //             url: '/api/tujuan',
    //             type: 'POST',
    //             dataType: 'json',
    //             data: {
    //                 surat_masuk_id: surat_masuk_id,
    //                 user_id: val,
    //             },
    //             success: function (response) {
    //                 console.log('Data berhasil disimpan', response);
    //             },
    //             error: function (xhr, status, error) {
    //                 console.error('Terjadi kesalahan', error);
    //             }
    //         });    
    //     });
    // });
    //refresh
    function refresh(){
        CrudModule.refresh(displayData);
    }


    function loadDataKonsep(page = 1) {        
        CrudModule.setFilter(`{"status":"konsep","tahun":"${tahunFilter}","kategori":"${$('#filter_kategori_surat').val()}"}`);
        CrudModule.fRead(page, displayData);
    }

    function loadDataMasuk(page = 1) {
        CrudModule.setFilter(`{"status":"diajukan","tahun":"${tahunFilter}","kategori":"${$('#filter_kategori_surat').val()}"}`);
        CrudModule.fRead(page, displayData);
    }

    function loadDataDiterima(page = 1) {
        CrudModule.setFilter(`{"status":"diterima","tahun":"${tahunFilter}","kategori":"${$('#filter_kategori_surat').val()}"}`);
        CrudModule.fRead(page, displayData);
    }

    function loadDataDitolak(page = 1) {
        CrudModule.setFilter(`{"status":"ditolak","tahun":"${tahunFilter}","kategori":"${$('#filter_kategori_surat').val()}"}`);
        CrudModule.fRead(page, displayData);
    }

    $(document).ready(function() {
        CrudModule.setApi(vApi);
        // Load data default
        loadDataKonsep();        
        InfoModule.updateNotifWeb();

        //start upload with crop

        fotoDokumen.init();
        $('#modal-upload').on('shown.bs.modal', function () {
            fotoDokumen.startCamera();
        }); 

        // Hentikan kamera saat modal ditutup
        $('#modal-upload').on('hidden.bs.modal', function () {
            fotoDokumen.stopCamera();
            fotoDokumen.resetToCamera();
        });

        $('#crop-btn').on('click', () => {
            fotoDokumen.saveCroppedImage((response) => {
                $('#modal-upload').modal('hide');
                if (response) {
                    refresh();
                } else {
                    alert('Terjadi kesalahan');
                }
            });
        });

        //end upload with crop

        function cariNoAgenda(){
            var kategori_surat = $("#kategori_surat").val();
            var tahun = $("#tanggal").val();
            var tanggal = $("#tanggal").val();
            var tahun = new Date(tanggal).getFullYear();

            // if($("#id").val()){
            $.ajax({
                url: `/api/nomor-agenda-terakhir/${kategori_surat}/${tahun}`,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    $('#no_agenda').val(response.data);
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
            // }
        }

        $('#kategori_surat').on('change', function() {
            // cariNoAgenda();
        });

        $('#tanggal').on('change', function() {
            // cariNoAgenda();       
        });

        $('#filter_kategori_surat').on('change', function() {
            var activeTabId = $('.nav-tabs .nav-link.active').attr('id');
            var kategori = $(this).val();
            console.log(activeTabId,kategori)
            
            if(activeTabId=='tabKonsep')        
                CrudModule.setFilter(`{"status":"konsep","tahun":"${tahunFilter}","kategori":"${kategori}"}`);
            else if(activeTabId=='tabAjukan')         
                CrudModule.setFilter(`{"status":"diajukan","tahun":"${tahunFilter}","kategori":"${kategori}"}`);
            else if(activeTabId=='tabTerima')         
                CrudModule.setFilter(`{"status":"diterima","tahun":"${tahunFilter}","kategori":"${kategori}"}`);
            else if(activeTabId=='tabTolak')         
                CrudModule.setFilter(`{"status":"ditolak","tahun":"${tahunFilter}","kategori":"${kategori}"}`);
                                                    
            CrudModule.refresh(displayData);
        });
        
    });

</script>
@endsection
