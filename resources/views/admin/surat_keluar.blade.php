@extends('admin.template')

@section('scriptHead')
<title>Penomoran Surat Keluar</title>
<link href="{{ asset('js/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') }}" rel="stylesheet">
<link href="{{ asset('js/select2/dist/css/select2.min.css') }}" rel="stylesheet">
<link href="{{ asset('js/select2/dist/css/select2.custom.css') }}" rel="stylesheet">
<link href="{{ asset('js/img-viewer/viewer.min.css') }}" rel="stylesheet">

@endsection

@section('container')
<div class="container-fluid p-0">
    <h1 class="h3 mb-3">Penomoran Surat Keluar</h1>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">

                    <header class="p-2 border-bottom">
                        <div class="container">
                            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
                        
                                <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                                    <li><a href="javascript:;" id="btnRefresh" onclick="refresh()" class="nav-link px-2 link-dark"><i class="align-middle" data-feather="refresh-cw"></i> Refresh</a></li>
                                    <li><a href="javascript:;" id="btnTambah" onclick="tambah()" class="nav-link px-2 link-dark"><i class="align-middle" data-feather="plus-circle"></i> Tambah</a></li>
                                    <li><a href="#" id="btnCoba" class="nav-link px-2 link-dark"><i class="align-middle" data-feather="filter"></i> Filter</a></li>
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
                                    <th style="width:15%">Jenis/ Pejabat Spesimen</th>
                                    <th style="width:40%">Tanggal/ Nomor/ Perihal/ Asal</th>
                                    <th style="width:15%">Tujuan/ Ringkasan</th>
                                    <th style="width:20%">Lampiran</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="dataTableBody">
                                <!-- Data will be dynamically populated here -->
                            </tbody>
                        </table>
                    
                        <nav aria-label="Page navigation">
                            <ul class="pagination" id="pagination">
                                <!-- Pagination links will be dynamically populated here -->
                            </ul>
                            <div id="pagination-info"></div>
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
            <input type="hidden" name="pola" id="pola" >
            <input type="hidden" name="no_surat" id="no_surat" >
            <input type="hidden" name="user_id" id="user_id" value="{{ auth()->user()->id }}">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-label">Form</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body ">
                    <div class="row">
						<div class="col-lg-3 mb-3">
                            <label class="form-label">Nomor Surat</label>
                            <input name="no_indeks" id="no_indeks" type="number" class="form-control" >
                        </div>
						<div class="col-lg-3 mb-3">
                            <label class="form-label">Nomor Sub Surat</label>
                            <input name="no_sub_indeks" id="no_sub_indeks" type="number" class="form-control" >
                        </div>
                    </div>
                    <div class="row">
						<div class="col-lg-4 mb-3">
                            <label class="form-label">Tanggal Surat</label>
                            <input name="tanggal" id="tanggal" type="text" class="form-control datepicker" value="{{ date("Y-m-d") }}" required>
                        </div>
						<div class="col-lg-8 mb-3">
                            <label class="form-label">Jenis Surat</label>
                            <select name="akses_pola_id" id="akses_pola_id" type="text" class="form-control " required></select>
                        </div>
						{{-- <div class="col-lg-4 mb-3">
                            <label class="form-label">Spesimen Jabatan</label>
                            <select name="spesimen_jabatan_id" id="spesimen_jabatan_id" type="text" class="form-control " required></select>
                        </div> --}}
                    </div>
                    
                    <div class="row" id="el-klasifikasi" style="display:none;">
						<div class="col-lg-12 mb-3">
                            <label class="form-label">Klasifikasi Surat</label>
                            <select name="klasifikasi_surat_id" id="klasifikasi_surat_id" type="text" class="form-control" required></select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-8 mb-3">
                            <label class="form-label">Perihal</label>
                            <textarea name="perihal" id="perihal" rows="7" class="form-control" required></textarea>
                        </div>
                        <div class="col-lg-4 mb-3 row">
                            <div class="col-lg-12 mb-3">
                                <label class="form-label">Asal</label>
                                <input name="asal" id="asal" type="text" class="form-control" required>
                            </div>
                            <div class="col-lg-12 mb-3">
                                <label class="form-label">Tujuan</label>
                                <textarea name="tujuan" id="tujuan" rows="3" class="form-control" required></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12 mb-3">
                            <label class="form-label">Ringkasan</label>
                            <textarea name="ringkasan" id="ringkasan" rows="3" class="form-control" ></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="btn-simpan">Simpan</button>
                    <button type="button" class="btn btn-outline-primary " data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- AKHIR MODAL -->

{{-- Modal distribusi --}}
<div class="modal fade" id="modal-distribusi" role="dialog">
    <div class="modal-dialog">
        <form id="form-distribusi">
            <input type="hidden" name="surat_keluar_id" id="surat_keluar_id">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-distribusi-label">Proses Distribusi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="col-lg-12 mb-3">
                        <label class="form-label">Distribusi Kepada</label>
                        <select class="form-control" id="select_user_id" name="user_id[]" required multiple="multiple"></select>
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

@endsection

@section('scriptJs')
<script src="{{ asset('js/bootstrap-material-moment/moment.js') }}"></script>
<script src="{{ asset('js/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js') }}"></script>
<script src="{{ asset('js/select2/dist/js/select2.min.js') }}"></script>
<script src="{{ asset('js/select2lib.js') }}"></script>
<script src="{{ asset('js/crud.js') }}"></script>
<script src="{{ asset('js/img-viewer/viewer.min.js') }}"></script>

<script type="text/javascript">
    var vApi='/api/surat-keluar';
    var vJudul='Penomoran Surat Keluar';
    var vsurat_keluar_id;
    // console.log(hakAkses);
    var hakAkses = {!! session()->get('akses') !!};
    // if(hakAkses>1)
    //     CrudModule.setFilter(`{"user_id":"${vUserId}"}`);

    var fieldInit={
        'id': { action: 'val' },
        'asal': { action: 'val' },
        'no_surat': { action: 'val' },
        'no_indeks': { action: 'val' },
        'no_sub_indeks': { action: 'val' },
        'pola': { action: 'val' },
        'perihal': { action: 'val' },
        'asal': { action: 'val' },
        'tujuan': { action: 'val' },
        'ringkasan': { action: 'val' },
        'akses_pola_id': { action: 'select2' },
        'klasifikasi_surat_id': { action: 'select2' },
    };

    $('.datepicker').bootstrapMaterialDatePicker({
        weekStart: 0,
        format: 'YYYY-MM-DD',
        time: false,
    });


    //refresh
    function refresh(){
        CrudModule.refresh(displayData);
    }
    
    

    //pencarian data
    $('#search-data').on('input', function() {
        var keyword = $(this).val();
        if (keyword.length == 0 || keyword.length >= 3) {
            CrudModule.setKeyword(keyword);
            CrudModule.fRead(1, displayData);
        }
    });    

    function loadData(page = 1) {
        CrudModule.fRead(page, displayData);
    }

    //read showdata
    function displayData(response) {
        var data = response.data;
        var tableBody = $('#dataTableBody');
        var nomor = response.meta.from;
        tableBody.empty();
        if(data.length>0)
            $.each(data, function(index, dt) {

                var lampiran=`<span class="badge bg-danger">Belum terupload</span>`;
                var menu_detail=``;
                if(dt.jumlah_lampiran>0){
                    lampiran =`<ul style="list-style: none;margin: 0;padding: 0; font-size:11px;" class="fa-ul images">`;
                    $.each(dt.lampiran_surat_keluar, function(i, ds) {
                        if(is_image(ds.upload.type))
                            lampiran +=`<li><span class="fa-li"><i class="fa-solid fa-arrow-up-right-from-square"></i></span><a href="javascript:;" data-url="${ds.upload.path}" class="imgprev" target="_self">${ds.upload.name}</a> <a href="javascript:;" onclick="hapusLampiranSuratKeluar(${ds.id},${ds.upload.id})"><i class="fa-regular fa-trash-can"></i></a></li>`;
                        else
                            lampiran +=`<li><span class="fa-li"><i class="fa-solid fa-arrow-up-right-from-square"></i></span><a href="${ds.upload.path}" target="_blank">${ds.upload.name}</a> <a href="javascript:;" onclick="hapusLampiranSuratKeluar(${ds.id},${ds.upload.id})"><i class="fa-regular fa-trash-can"></i></a></li>`;
                    });
                    lampiran +=`</ul>`;
                    menu_detail=`   <li><a class="dropdown-item" href="{{ asset('surat-keluar-detail/${dt.id}') }}" ><i class="fa-solid fa-newspaper"></i> Selengkapnya</a></li>
                                    <li><a class="dropdown-item" href="javascript:;" onclick="prosesdistribusi(${dt.id})"><i class="fa-regular fa-paper-plane"></i> Distribusi</a></li>`;
                }
                
                var lblaktif=(!dt.is_aktif)?`<span class="badge bg-danger">Tidak Aktif</span>`:`<span class="badge bg-success">Aktif</span>`;
                var ringkasan=(dt.ringkasan)?dt.ringkasan:"";
                var row = `
                    <tr>
                        <td>${nomor++}</td>
                        <td><span class="badge bg-primary">${dt.spesimen_jabatan.jabatan}</span><br>${dt.pola_surat.kategori}</td>
                        <td><span class="badge bg-primary">${dt.tanggal}</span><br>${dt.no_surat}
                            <div>
                                <i class="fas fa-quote-left fa-1x"></i>
                                <blockquote class="blockquote pb-2">
                                    <p>
                                    ${dt.perihal}
                                    </p>
                                </blockquote>
                                <figcaption class="blockquote-footer mb-0">
                                    ${dt.user.name} (${dt.asal}) 
                                </figcaption>                                   
                            </div>                               
                        </td>
                        <td>${dt.tujuan}<div style="font-style:italic;font-size:12px;">${ringkasan}</div></td>
                        <td>
                            <i class="fa-solid fa-users"></i> ${dt.jumlah_distribusi}
                            <div class="btn-group-sm">
                                <a href="javascript:;" class="btn btn-primary uploadLampiran" data-surat_keluar_id="${dt.id}"><i class="fa-solid fa-upload"></i></a>
                                <a href="javascript:;" class="btn btn-primary fotoLampiran" onclick="upload(${dt.id})"><i class="fa-solid fa-camera"></i></a>
                            </div>
                            ${lampiran}                        
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"></button>
                                <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                    ${menu_detail}
                                    <li><a class="dropdown-item" href="javascript:;" onclick="ganti(${dt.id})"><i class="fa-solid fa-pen-to-square"></i> Ganti</a></li>
                                    <li><a class="dropdown-item" href="javascript:;" onclick="hapus(${dt.id})"><i class="fa-solid fa-trash"></i> Hapus</a></li>
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
                        <td colspan="7">Tidak ditemukan</td>
                    </tr>
                `;
                tableBody.append(row);            
        }
    }

    //ketika halaman paging di klik
    $(document).on('click','.page-link', function(e) {
        e.preventDefault();
        var page = $(this).data('page');
        CrudModule.fRead(page,displayData);
    });


    //untuk tampilkan modal 
    function showModalForm(){
        $('#myForm')[0].reset();
        $("#id").val("");
        CrudModule.resetForm(fieldInit);
        let myModalForm = new bootstrap.Modal(document.getElementById('modal-form'), {
            backdrop: 'static',
            keyboard: false,
        });
        myModalForm.toggle();
    }

    // tambah data
    function tambah() {
        showModalForm();
        $('#modal-label').text('Tambah '+vJudul);
        $('#btn-simpan').text('Simpan');
    };

    // ganti dan populasi data
    function ganti(id) {
        $.ajax({
            // url: '/api/surat-keluar?keyword={"id":"'+id+'"}',
            url: '/api/surat-keluar/'+id,
            method: 'GET',
            dataType: 'json',
            success: function (response){
                if(response.success){
                    showModalForm();
                    var dt = response.data;
                    //populasi data secara dinamis
                    CrudModule.populateEditForm(dt,fieldInit);
                    
                    //ubah form
                    $('#modal-label').text('Ganti '+vJudul);
                    $('#btn-simpan').text('Ubah Sekarang');

                    if(dt.klasifikasi_surat_id){                    
                        let option_klasifikasi = new Option(dt.klasifikasi_surat.kode, dt.klasifikasi_surat.id, true, true);
                        $("#klasifikasi_surat_id").append(option_klasifikasi).trigger('change');
                    }                    
                }

            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    }

    //validasi form dan submit handler untuk simpan atau ganti
    $("#myForm").validate({
        messages: {
            grup: "grup tidak boleh kosong",
        },
        submitHandler: function(form) {
            let setup_ajax={type:'POST',url:vApi};
            let id=$("#id").val();
            if (id !== "")
                setup_ajax={type:'PUT',url:vApi+'/'+id};
            simpan(setup_ajax,form)
        }
    });    

    //simpan baru atau simpan perubahan
    function simpan(setup_ajax,form) {
        let dataForm = $(form).serialize();
        CrudModule.fSave(setup_ajax, dataForm, function(response) {
            if (response.success) {
                refresh();
                // $('#modal-form').modal('hide');
            } 
            appShowNotification(response.success,[response.message]);
        });
    }		    

    // hapus
    function hapus(id) {
        CrudModule.fDelete(id, function(response) {
            appShowNotification(response.success, [response.message]);
            if (response.success) {
                refresh();
            }
        });
    }

    // $('#spesimen_jabatan_id').select2({
    //     placeholder: "-pilih-"
    // });
    $('#akses_pola_id').select2({
        placeholder: "-pilih-"
    });
    sel2_cariKlasifikasi(3,'#klasifikasi_surat_id','#myForm .modal-content')


    $("#tanggal").change(function() { 
        initPola();
    });

    initPola();
    function initPola(){
        //set empty dan hide dulu
        $('#akses_pola_id').empty();
        $('#spesimen_jabatan_id').val("");
        $("#el-klasifikasi").hide();
        //load pola
        let thn = new Date($("#tanggal").val()).getFullYear();        
        let dataCari = {tahun:thn,user_id:vUserId};
        let keywordString = encodeURIComponent(JSON.stringify(dataCari));

        $.ajax({
            url: '/api/get-akses-pola?filter='+keywordString,
            method: 'GET',
            dataType: 'json',
            success: function (response){
                let vdata=[];
                vdata.push({id:'',text:'-pilih-'});
                if(response.data.length>0){
                    $.each(response.data, function(index, dt) {
                        vdata.push(
                            {
                                id:dt.id,
                                text:dt.pola_surat.kategori +' - '+dt.spesimen_jabatan.jabatan,
                                needs_klasifikasi:dt.pola_surat.needs_klasifikasi,
                            }
                        );
                    });
                }

                sel2_datalokal('#akses_pola_id',vdata,false,'#myForm .modal-content');

            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    }

    $("#el-klasifikasi").hide();
    $('#akses_pola_id').on('change', function() {
        // initSpesimen();
        let needs_klasifikasi=$(this).select2('data')[0].needs_klasifikasi;

        if(needs_klasifikasi){
            $("#el-klasifikasi").show();
        }else{
            $("#el-klasifikasi").hide();
        }
    });

    // function initSpesimen(){
    //     //load spesimen
    //     $('#spesimen_jabatan_id').empty();
    //     let id=$("#pola_surat_id").val();
    //     if(id){
    //         let dataCari = {akses_pola_id:id};
    //         let keywordString = encodeURIComponent(JSON.stringify(dataCari));
    //         $.ajax({
    //             url: '/api/akses-spesimen?keyword='+keywordString,
    //             method: 'GET',
    //             dataType: 'json',
    //             success: function (response){
    //                 let vdata=[];
    //                 vdata.push({id:'',text:'-pilih-'});
    //                 if(response.data.length>0){
    //                     $.each(response.data, function(index, dt) {
    //                         vdata.push({id:dt.spesimen_jabatan.id,text:dt.spesimen_jabatan.jabatan});
    //                     });
    //                 }
    //                 // sel2_datalokal('#spesimen_jabatan_id',vdata,false,'#myForm .modal-content');

    //             },
    //             error: function(xhr, status, error) {
    //                 console.error(error);
    //             }
    //         });
    //     }

    // }
    //-------------------------

    var image = new Image();
    $(document).on('click','.imgprev',function() {
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

    function linkLampiran(upload_id,surat_keluar_id){
        var formData = {upload_id:upload_id,surat_keluar_id:surat_keluar_id};
        // console.log(formData);
        $.ajax({
            type: "POST",
            dataType: 'json',
            url: "/api/lampiran-surat-keluar",
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

    function hapusLampiranSuratKeluar(id,upload_id){
        if(confirm("apakah anda yakin?")){
            $.ajax({
                type: "DELETE",
                url: "/api/lampiran-surat-keluar/"+id,
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
        var surat_keluar_id = $(this).data("surat_keluar_id");
        var fileInput = $('<input type="file" id="lampiran" name="lampiran" accept=".jpg, .jpeg, .png, .pdf, .doc, .docx, .ppt, .pptx, .xls, .xlsx" style="display: none;">');
        $("body").append(fileInput);
        fileInput.click();

        fileInput.change(function () {
            var selectedFile = this.files[0];
            if (selectedFile) {
                uploadFile(surat_keluar_id, vUserId, selectedFile);
            }
        });
    });    

    function uploadFile(surat_keluar_id, user_id, file, fileName) {
        const formData = new FormData();
        formData.append("user_id", user_id);
        formData.append("surat_keluar_id", surat_keluar_id);
        formData.append("file", file, fileName);

        $.ajax({
            url: "/api/upload",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.success) {
                    linkLampiran(response.data.id, surat_keluar_id);
                } else {
                    appShowNotification(false, ["Failed to upload attachment."]);
                }
            },
            error: function (xhr, status, error) {
                appShowNotification(false, ["Something went wrong. Please try again later."]);
            }
        });
    }  


    function upload(id){
        vsurat_keluar_id=id;
        let myModalUpload = new bootstrap.Modal(document.getElementById('modal-upload'), {
            backdrop: 'static',
            keyboard: false,
        });
        myModalUpload.toggle();
    }


    sel2_cariUser(3, '#select_user_id', '#form-distribusi .modal-content');
    function prosesdistribusi(surat_keluar_id) {
        $('#form-distribusi')[0].reset();
        $('#select_user_id').val("").trigger("change");
        $('#surat_keluar_id').val(surat_keluar_id);
        let myModalForm = new bootstrap.Modal(document.getElementById('modal-distribusi'), {
            backdrop: 'static',
            keyboard: false,
        });
        myModalForm.toggle();
    }


    $("#form-distribusi").validate({
        messages: {
            surat_keluar_id: "surat keluar harus ada",
            user_id: "pilih user dulu",
        },
        submitHandler: function(form) {
            simpanDistribusi()
        }
    });

    function simpanDistribusi() {
        var select_user_id = $('#select_user_id').val();
        var surat_keluar_id = $('#surat_keluar_id').val();
        var totalRequests = select_user_id.length;
        var completedRequests = 0;
        // multiple user
        jQuery.each(select_user_id, function (i, val) {
            $.ajax({
                url: '/api/distribusi',
                type: 'POST',
                dataType: 'json',
                data: {
                    surat_keluar_id: surat_keluar_id,
                    user_id: val,
                },
                success: function (response) {
                    completedRequests++;
                    if (completedRequests === totalRequests) {
                        refresh();
                        appShowNotification(true, ["Selsai dilakukan"]);
                    }                    
                },
                error: function (xhr, status, error) {
                    appShowNotification(false,['Terjadi kesalahan, '+error]);
                }
            });    
        });

    }   

    $(document).ready(function() {
        CrudModule.setApi(vApi);
        // Load data default
        loadData();
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
