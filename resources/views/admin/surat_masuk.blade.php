@extends('admin.template')

@section('scriptHead')
<title>Surat Masuk</title>
<link href="{{ asset('js/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') }}" rel="stylesheet">
<link href="{{ asset('js/select2/dist/css/select2.min.css') }}" rel="stylesheet">
<link href="{{ asset('js/select2/dist/css/select2.custom.css') }}" rel="stylesheet">
<link href="{{ asset('js/img-viewer/viewer.min.css') }}" rel="stylesheet">

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
                        
                                <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                                    <li><a href="javascript:;" id="btnRefresh" class="nav-link px-2 link-dark"><i class="align-middle" data-feather="refresh-cw"></i> Refresh</a></li>
                                    <li><a href="javascript:;" id="btnTambah" class="nav-link px-2 link-dark"><i class="align-middle" data-feather="plus-circle"></i> Tambah</a></li>
                                    <li><a href="javascript:;" id="btnFilter" class="nav-link px-2 link-dark" onclick="setfilter()"><i class="align-middle" data-feather="filter"></i> Filter</a></li>

                                </ul>                        
                                <form class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3">
                                    <input type="search" id="search-data" class="form-control" placeholder="Search..." aria-label="Search">
                                </form>                        
                            </div>
                        </div>
                    </header>

                </div>
                <div class="card-body">
                    
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a class="nav-link active" href="javascript:;" id="tabKonsep" onclick="setActiveTab('tabKonsep')">Konsep</a>
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
                    
                    <div class="table-responsive">
                        <table class="table mt-3">
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
            <input type="hidden" name="user_id" id="user_id" value="{{ auth()->user()->id }}">
            <input type="hidden" name="id" id="id" >
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-label">Form Surat Masuk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body ">
                    <div class="row">
						<div class="col-lg-2 mb-3">
                            <label class="form-label">Nomor Agenda</label>
                            <input name="no_agenda" id="no_agenda" type="text" class="form-control" placeholder="" required>
                        </div>
						<div class="col-lg-7 mb-3">
                            <label class="form-label">Nomor Surat</label>
                            <input name="no_surat" id="no_surat" type="text" class="form-control" placeholder="" required>
                        </div>
						<div class="col-lg-3 mb-3">
                            <label class="form-label">Tanggal Surat</label>
                            <input name="tanggal" id="tanggal" type="text" class="form-control datepicker" value="{{ date("Y-m-d") }}" placeholder="" required>
                        </div>
						<div class="col-lg-8 mb-3">
                            <label class="form-label">Kategori Surat</label>
                            <select class="form-control" id="kategori_surat_masuk_id" name="kategori_surat_masuk_id" required></select>
                        </div>
						<div class="col-lg-6 mb-3">
                            <label class="form-label">Asal Daerah Surat</label>
                            <input name="asal" id="asal" type="text" class="form-control" placeholder="ex : kendari atau jakarta, dst" required>
                        </div>
						<div class="col-lg-6 mb-3">
                            <label class="form-label">Nama Institusi</label>
                            <input name="tempat" id="tempat" type="text" class="form-control" placeholder="ex : Kemenag RI, Dirjen Pendis, Gubernur Sultra" required>
                        </div>
						<div class="col-lg-12 mb-3">
                            <label class="form-label">Perihal</label>
                            <textarea name="perihal" id="perihal" class="form-control" rows="4" required></textarea>
                        </div>
						<div class="col-lg-12 mb-3">
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
                <div class="row mb-3" style="align:center">
                    <div class="col-lg-8">
                        <button id="switch-camera" class="btn btn-primary">Switch Camera</button>
                        <video id="camera" autoplay width="100%"></video>
                    </div>                
                    <div class="col-lg-4">
                        <button type="button" class="btn btn-success" id="take-photo">Ambil Gambar</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>                
                </div>                
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
                    <div class="col-lg-12 mb-3">
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

<script type="text/javascript">
    var vApi='/api/surat-masuk';
    var vJudul='Surat Masuk';
    var vPage = 1;
    var vsurat_masuk_id;

    var hakAkses = {!! session()->get('akses') !!};
    var tahunFilter = '{{ date("Y") }}';

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

                var labelApp=labelSetupVerifikasi(dt.is_diajukan,dt.is_diterima,dt.catatan,dt.verifikator);
                var menu_edit=``;

                var btnUpload=` <div class="btn-group-sm mt-1">
                                    <a href="javascript:;" class="btn btn-primary uploadLampiran" data-surat_masuk_id="${suratMasuk.id}"><i class="fa-solid fa-upload"></i></a>
                                    <a href="javascript:;" class="btn btn-primary fotoLampiran" onclick="upload(${suratMasuk.id})"><i class="fa-solid fa-camera"></i></a>
                                </div>`;
                
                if(dt.user_id==vUserId && dt.is_diajukan!=1){
                    if(suratMasuk.jumlah_lampiran>0){
                        menu_edit+=` <li><a class="dropdown-item" href="javascript:;" onclick="ajukan(${dt.id})"><i class="fa-regular fa-share-from-square"></i> Ajukan</a></li>`;
                    }
                
                    menu_edit+=`<li><a class="dropdown-item" href="javascript:;" onclick="ganti(${dt.id})"><i class="fa-solid fa-pen-to-square"></i> Ganti</a></li>
                                <li><a class="dropdown-item" href="javascript:;" onclick="hapus(${dt.id})"><i class="fa-solid fa-trash"></i> Hapus</a></li>`;
                }
                
                if(dt.is_diajukan){
                    menu_edit=``;

                    if(dt.is_diterima==null && hakAkses==1){
                        menu_edit=` <li><a class="dropdown-item" href="javascript:;" onclick="validasi(1,${dt.id})"><i class="fa-solid fa-envelope-circle-check"></i> Terima</a></li>
                                    <li><a class="dropdown-item" href="javascript:;" onclick="validasi(0,${dt.id})"><i class="fa-solid fa-rectangle-xmark"></i> Tolak</a></li>`;
                    }
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
                        
                        if(hakAkses==1)
                            status_disposisi=`<span class="btn btn-primary btn-sm" onclick="prosesDisposisi(${suratMasuk.id})"><i class="fa-solid fa-envelopes-bulk"></i> Proses Disposisi</span>`;
                        else{
                            status_disposisi=`<span class="badge bg-warning"> Dalam proses</span>`;
                        }
                        track_disposisi =`<span class="badge bg-warning">Belum terdisposisi</span>`;
                        if(suratMasuk.tujuan.length>0){
                            status_disposisi="";
                            menu_detail=`<li><a class="dropdown-item" href="/disposisi-detail/${suratMasuk.id}"><i class="fa-brands fa-readme"></i> Detail Disposisi</a></li>`;
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
                    
                }

                var row = `
                    <tr>
                        <td>${nomor++}</td>
                        <td><span class="badge bg-success">Tanggal : ${suratMasuk.tanggal}</span><br>
                            No. ${suratMasuk.no_surat} (${suratMasuk.no_agenda})
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
                        </td>
                        <td>${suratMasuk.user.name}<div style="text-align:center;font-size:10px;">${suratMasuk.created_at}</div></td>
                        <td>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"></button>
                                <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                    ${menu_detail}
                                    ${menu_edit}
                                    <li><a class="dropdown-item" href="{{ asset('surat-keluar-detail/${dt.id}') }}" target="_blank"><i class="fa-solid fa-newspaper"></i> Selengkapnya</a></li>
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
                        <td colspan="4">Tidak ditemukan</td>
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

    function hapus(id,url) {
        if(confirm("apakah anda yakin?")){
            $.ajax({
                url: url+id,
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
        $("#kategori_surat_masuk_id").val(data.kategori_surat_masuk_id).trigger("change"); 
    }

    $("#myForm").validate({
        messages: {
            no_agenda: "agenda tidak boleh kosong",
            no_surat: "nomor surat tidak boleh kosong",
            perihal: "perihal tidak boleh kosong",
            asal: "asal surat tidak boleh kosong",
            tempat: "tempat surat tidak boleh kosong",
            kategori_surat_masuk: "nomor surat tidak boleh kosong",
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
        var user_id = "{{ auth()->user()->id }}";
        var fileInput = $('<input type="file" id="lampiran" name="lampiran" accept=".jpg, .jpeg, .png, .pdf, .doc, .docx, .ppt, .pptx, .xls, .xlsx" style="display: none;">');
        $("body").append(fileInput);
        fileInput.click();

        fileInput.change(function () {
            var selectedFile = this.files[0];
            if (selectedFile) {
                uploadFile(surat_masuk_id, user_id, selectedFile);
            }
        });
    });

    function uploadFile(surat_masuk_id, user_id, file, fileName) {
        const formData = new FormData();
        formData.append("user_id", user_id);
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
                catatan = prompt("Tuliskan alasan mengapa usulan ini ditolak?");

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
        // $("#uploadForm")[0].reset();
        // $("#surat_masuk_id").val(id);
        vsurat_masuk_id=id;
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
        CrudModule.setFilter('{"kategori":"konsep","tahun":'+tahunFilter+'}');
        CrudModule.fRead(page, displayData);
    }

    function loadDataMasuk(page = 1) {
        CrudModule.setFilter('{"kategori":"diajukan","tahun":'+tahunFilter+'}');
        CrudModule.fRead(page, displayData);
    }

    function loadDataDiterima(page = 1) {
        CrudModule.setFilter('{"kategori":"diterima","tahun":'+tahunFilter+'}');
        CrudModule.fRead(page, displayData);
    }

    function loadDataDitolak(page = 1) {
        CrudModule.setFilter('{"kategori":"ditolak","tahun":'+tahunFilter+'}');
        CrudModule.fRead(page, displayData);
    }

    $(document).ready(function() {
        CrudModule.setApi(vApi);
        // Load data default
        loadDataKonsep();        
        InfoModule.updateNotifWeb();
        // Ambil referensi elemen
        const cameraElement = document.getElementById("camera");
        const takePhotoButton = document.getElementById("take-photo");
        const switchCameraButton = document.getElementById("switch-camera"); // Tambahkan ini

        let isUploading = false;
        let stream; // Tambahkan ini untuk menyimpan referensi stream kamera

        function stopCamera() {
            if (stream) {
                const tracks = stream.getTracks();
                tracks.forEach(function(track) {
                    track.stop();
                });
                cameraElement.srcObject = null;
            }
        }

        // Tambahkan fungsi untuk switch kamera
        function switchCamera() {
            stopCamera();

            const videoConstraints = {
                video: {
                    facingMode: (stream.getVideoTracks()[0].getSettings().facingMode === 'user') ? 'environment' : 'user'
                }
            };

            navigator.mediaDevices.getUserMedia(videoConstraints)
                .then(function(newStream) {
                    stream = newStream;
                    cameraElement.srcObject = newStream;
                })
                .catch(function(error) {
                    console.error("Error switching camera:", error);
                });
        }

        $('#modal-upload').on('shown.bs.modal', function() {
            navigator.mediaDevices.getUserMedia({ video: true })
                .then(function(initialStream) {
                    stream = initialStream; // Simpan referensi stream
                    cameraElement.srcObject = initialStream;
                })
                .catch(function(error) {
                    console.error("Error accessing camera:", error);
                });

            // Tambahkan event listener untuk switch camera
            switchCameraButton.addEventListener("click", switchCamera);

            takePhotoButton.addEventListener("click", function() {
                if (!isUploading) {
                    isUploading = true;
                    takePhotoButton.disabled = true;

                    const canvas = document.createElement("canvas");
                    const scaleFactor = 1.5;
                    const targetWidth = cameraElement.videoWidth * scaleFactor;
                    const targetHeight = cameraElement.videoHeight * scaleFactor;

                    canvas.width = targetWidth;
                    canvas.height = targetHeight;

                    const context = canvas.getContext("2d");
                    context.drawImage(
                        cameraElement,
                        (cameraElement.videoWidth - targetWidth) / 2,
                        (cameraElement.videoHeight - targetHeight) / 2,
                        targetWidth,
                        targetHeight,
                        0,
                        0,
                        canvas.width,
                        canvas.height
                    );
                    canvas.toBlob(function(blob) {
                        if (blob) {
                            const user_id = "{{ auth()->user()->id }}";
                            uploadFile(vsurat_masuk_id, user_id, blob, 'capture.jpg');
                        }
                        isUploading = false;
                        takePhotoButton.disabled = false;
                    }, "image/jpeg", 1);
                }
            });
        });

        $('#modal-upload').on('hidden.bs.modal', function() {
            stopCamera();
        });
    });

</script>
@endsection
