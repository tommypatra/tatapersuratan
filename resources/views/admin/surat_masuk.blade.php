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
                                    <li><a href="javascript:;" id="btnFilter" class="nav-link px-2 link-dark"><i class="align-middle" data-feather="filter"></i> Filter</a></li>
                                </ul>                        
                                <form class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3">
                                    <input type="search" id="search-data" class="form-control" placeholder="Search..." aria-label="Search">
                                </form>                        
                            </div>
                        </div>
                    </header>

                </div>
                <div class="card-body">                    
                    <div class="table-responsive">
                        <table class="table mt-3">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th width="40%">Surat Masuk</th>
                                    <th width="20%">Lampiran</th>
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
    var vPage = 1;
    var vsurat_masuk_id;
    var hakAkses = {!! session()->get('akses') !!};

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
        loadData(vPage);
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
                var menuDet=``;
                if(suratMasuk.jumlah_lampiran>0){
                    lampiran =`<ul style="list-style: none;margin: 0;padding: 0; font-size:11px;" class="fa-ul images">`;
                    $.each(suratMasuk.lampiran_surat_masuk, function(i, dt) {
                        if(is_image(dt.upload.type))
                            lampiran +=`<li><span class="fa-li"><i class="fa-solid fa-arrow-up-right-from-square"></i></span><a href="javascript:;" data-url="${dt.upload.path}" class="imgprev" target="_self">${dt.upload.name}</a> <a href="javascript:;" onclick="hapusLampiranSuratMasuk(${dt.id},${dt.upload.id})"><i class="fa-regular fa-trash-can"></i></a></li>`;
                        else
                            lampiran +=`<li><span class="fa-li"><i class="fa-solid fa-arrow-up-right-from-square"></i></span><a href="${dt.upload.path}" target="_blank">${dt.upload.name}</a> <a href="javascript:;" onclick="hapusLampiranSuratMasuk(${dt.id},${dt.upload.id})"><i class="fa-regular fa-trash-can"></i></a></li>`;
                    });
                    lampiran +=`</ul>`;
                    status_disposisi=`<span class="btn btn-primary btn-sm" onclick="prosesDisposisi(${suratMasuk.id})"><i class="fa-solid fa-envelopes-bulk"></i> Proses Disposisi</span>`;
                    track_disposisi =`<span class="badge bg-warning">Belum terdisposisi</span>`;
                    if(suratMasuk.tujuan.length>0){
                        status_disposisi="";
                        menuDet=`<li><a class="dropdown-item" href="/disposisi-detail/${suratMasuk.id}"><i class="fa-brands fa-readme"></i> Detail Disposisi</a></li>`;
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
                var row = `
                    <tr>
                        <td>${nomor++}</td>
                        <td><span class="badge bg-success">Tanggal : ${suratMasuk.tanggal}</span><br>
                            No. ${suratMasuk.no_surat} (${suratMasuk.no_agenda})
                            <div style="font-weight:bold;">${suratMasuk.perihal}</div>
                            <div style="font-style:italic;">Asal : ${suratMasuk.asal} (${suratMasuk.tempat})</div>
                            <div style="font-size:11px;">[Kategori : ${suratMasuk.kategori_surat_masuk.kategori}]</div>
                        </td>
                        <td>
                            <div class="btn-group-sm">
                                <a href="javascript:;" class="btn btn-primary uploadLampiran" data-surat_masuk_id="${suratMasuk.id}"><i class="fa-solid fa-upload"></i></a>
                                <a href="javascript:;" class="btn btn-primary fotoLampiran" onclick="upload(${suratMasuk.id})"><i class="fa-solid fa-camera"></i></a>
                            </div>
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
                                    ${menuDet}
                                    <li><a class="dropdown-item" href="javascript:;" onclick="ganti(${suratMasuk.id})"><i class="fa-solid fa-pen-to-square"></i> Ganti</a></li>
                                    <li><a class="dropdown-item" href="javascript:;" onclick="hapus(${suratMasuk.id},'/api/surat-masuk/')"><i class="fa-solid fa-trash"></i> Hapus</a></li>
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
                    appShowNotification(response.success,[response.message]);
                    if(response.success)
                        refreshData();
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
                    loadData();
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
        //                 loadData();
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
                    loadData();
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
                    if (response.success) {
                        loadData();
                        if(confirm("Tautan berhasil dihapus, apakah anda juga ingin menghapus secara permanen file tersebut?")){
                            hapusFileUpload(upload_id);
                        }
                    } else {
                        appShowNotification(false, ["Failed to upload attachment."]);
                    }
                },
                error: function (xhr, status, error) {
                    appShowNotification(false, ["Something went wrong. Please try again later."]);
                },
            });
        }
    }

    function hapusFileUpload(id){
        $.ajax({
            type: "DELETE",
            url: "/api/upload/"+id,
            dataType: 'json',
            success: function (response) {
                if (response.success) {
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

    $(document).ready(function() {
        // Ambil referensi elemen
        const cameraElement = document.getElementById("camera");
        const takePhotoButton = document.getElementById("take-photo");
        let isUploading = false;

        function stopCamera() {
            const stream = cameraElement.srcObject;
            if (stream) {
                const tracks = stream.getTracks();
                tracks.forEach(function(track) {
                    track.stop();
                });
                cameraElement.srcObject = null;
            }
        }

        $('#modal-upload').on('shown.bs.modal', function () {
            navigator.mediaDevices.getUserMedia({ video: true })
            .then(function (stream) {
                cameraElement.srcObject = stream;
            })
            .catch(function (error) {
                console.error("Error accessing camera:", error);
            });

            takePhotoButton.addEventListener("click", function () {
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
                    canvas.toBlob(function (blob) {
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

        $('#modal-upload').on('hidden.bs.modal', function () {
            stopCamera();
        });

        loadData();

    });

</script>
@endsection
