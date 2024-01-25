@extends('admin.template')

@section('scriptHead')
<title>Klasifikasi Surat Keluar</title>
<link href="{{ asset('js/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') }}" rel="stylesheet">
<link href="{{ asset('js/select2/dist/css/select2.min.css') }}" rel="stylesheet">
<link href="{{ asset('js/select2/dist/css/select2.custom.css') }}" rel="stylesheet">
@endsection

@section('container')
<div class="container-fluid p-0">
    <h1 class="h3 mb-3">Klasifikasi Surat Keluar</h1>
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
                                    <th width="10%">Kode</th>
                                    <th width="35%">Klasifikasi</th>
                                    <th width="30%">Keterangan</th>
                                    <th width="10%">Aktif</th>
                                    <th>Pengelola</th>
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
            <input type="hidden" name="user_id" id="user_id" value="{{ auth()->user()->id }}">
            <input type="hidden" name="id" id="id" >
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-label">Form Pola Surat Keluar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body ">
                    <div class="row">
						<div class="col-lg-4 mb-3">
                            <label class="form-label">Kode</label>
                            <input name="kode" id="kode" type="text" class="form-control" required>
                        </div>
						<div class="col-lg-6 mb-3">
                            <label class="form-label">Klasifikasi</label>
                            <input name="klasifikasi" id="klasifikasi" type="text" class="form-control" required>
                        </div>
						<div class="col-lg-12 mb-3">
                            <label class="form-label">Keterangan</label>
                            <textarea name="keterangan" id="keterangan" rows="4" class="form-control" ></textarea>
                        </div>
						<div class="col-lg-6 mb-3">
                            <label class="form-label">Aktif</label>
                            <select name="is_aktif" id="is_aktif" class="form-control" required></select>
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

@endsection

@section('scriptJs')
<script src="{{ asset('js/bootstrap-material-moment/moment.js') }}"></script>
<script src="{{ asset('js/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js') }}"></script>
<script src="{{ asset('js/select2/dist/js/select2.min.js') }}"></script>
<script src="{{ asset('js/select2lib.js') }}"></script>
<script src="{{ asset('js/crud.js') }}"></script>

<script type="text/javascript">
    var vApi='/api/klasifikasi-surat-keluar';
    var vJudul='Klasifikasi Surat Keluar';
    var fieldInit={
        'id': { action: 'val' },
        'kode': { action: 'val' },
        'klasifikasi': { action: 'val' },
        'keterangan': { action: 'val' },
        'is_aktif': { action: 'select2' },
    };
    sel2_publish("#is_aktif","#modal-form");

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

    //display data
    function displayData(response) {
        var data = response.data;
        var tableBody = $('#dataTableBody');
        var nomor = response.meta.from;
        var lblaktif;
        tableBody.empty();
        if(data.length>0)
            $.each(data, function(index, dt) {
                lblaktif=(!dt.is_aktif)?`<span class="badge bg-danger">Tidak Aktif</span>`:`<span class="badge bg-success">Aktif</span>`;
                var row = `
                    <tr>
                        <td>${nomor++}</td>
                        <td>${dt.kode}</td>
                        <td>${dt.klasifikasi}</td>
                        <td>${dt.keterangan}</td>
                        <td>${lblaktif}</td>
                        <td>${dt.user.name}<div style="text-align:left;font-size:10px;">${dt.created_at}</div></td>
                        <td>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"></button>
                                <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
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
        CrudModule.fEdit(id, function(response) {
            if(response.success){
                showModalForm();
                var dt = response.data;
                //populasi data secara dinamis
                CrudModule.populateEditForm(dt,fieldInit);
                //ubah form
                $('#modal-label').text('Ganti '+vJudul);
                $('#btn-simpan').text('Ubah Sekarang');
            }
        });
    }

    //validasi form dan submit handler untuk simpan atau ganti
    $("#myForm").validate({
        messages: {
            kode: "kode tidak boleh kosong",
            klasifikasi: "klasifikasi tidak boleh kosong",
            is_aktif: "pilih terlebih dahulu",
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
                $('#modal-form').modal('hide');
            } 
            appShowNotification(response.success,[response.message]);
        });
    }		    

    // hapus
    function hapus(id) {
        CrudModule.fDelete(id, function(response) {
            refresh();
        });
    }

    $(document).ready(function() {
        CrudModule.setApi(vApi);
        // Load data default
        loadData();
        function loadData(page = 1) {
            CrudModule.fRead(page, displayData);
        }
        InfoModule.updateNotifWeb();
    });
</script>
@endsection
