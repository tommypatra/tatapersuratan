@extends('admin.template')

@section('scriptHead')
<title>Disposisi Surat</title>
<link href="{{ asset('js/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') }}" rel="stylesheet">
<link href="{{ asset('js/select2/dist/css/select2.min.css') }}" rel="stylesheet">
<link href="{{ asset('js/select2/dist/css/select2.custom.css') }}" rel="stylesheet">
@endsection

@section('container')
<div class="container-fluid p-0">
    <h1 class="h3 mb-3">Disposisi Surat</h1>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">

                    <header class="p-2 border-bottom">
                        <div class="container">
                            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
                        
                                <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                                    <li><a href="javascript:;" id="btnRefresh" onclick="refresh()" class="nav-link px-2 link-dark"><i class="align-middle" data-feather="refresh-cw"></i> Refresh</a></li>
                                    <li><a href="{{ route('scan-qrcode') }}" id="btnScan" class="nav-link px-2 link-dark"><i class="align-middle" data-feather="aperture"></i> Scan Surat Masuk</a></li>
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
                                    <th style="width:80%">Surat Masuk</th>
                                    <th style="width:20%">Keterangan</th>
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


@endsection

@section('scriptJs')
<script src="{{ asset('js/bootstrap-material-moment/moment.js') }}"></script>
<script src="{{ asset('js/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js') }}"></script>
<script src="{{ asset('js/select2/dist/js/select2.min.js') }}"></script>
<script src="{{ asset('js/select2lib.js') }}"></script>
<script src="{{ asset('js/crud.js') }}"></script>

<script type="text/javascript">
    var vApi='/api/tujuan';
    var vJudul='Disposisi Surat';

    // CrudModule.setFilter(`${vUserId}`);
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

   

    //read showdata
    function displayData(response) {
        var data = response.data;
        var tableBody = $('#dataTableBody');
        var nomor = response.meta.from;
        tableBody.empty();
        if(data.length>0)
            $.each(data, function(index, tujuan) {
                var suratMasuk=tujuan.surat_masuk;
                var linkDetail=`/surat-masuk-detail/${suratMasuk.id}`;
                const sudahDisposisi = isUserInDisposisi(suratMasuk.tujuan,vUserId);
                
                var ketAkses=`<span class="badge bg-danger">belum dibaca</span>`;
                if(tujuan.waktu_akses){
                    ketAkses=`<span class="badge bg-success">sudah dibaca</span>`;
                }

                ketDisposisi=`<span class="badge bg-danger">belum mendisposisi</span>`;
                if(sudahDisposisi){
                    ketDisposisi=`<span class="badge bg-success">sudah mendisposisi</span>`;
                }
                var row = `
                    <tr>
                        <td>${nomor++}</td>
                        <td><span class="badge bg-success">Tanggal : ${suratMasuk.tanggal}</span><br>
                            No. ${suratMasuk.no_surat} (${suratMasuk.no_agenda})
                            <div style="font-weight:bold;">${suratMasuk.perihal}</div>
                            <div style="font-style:italic;">Asal : ${suratMasuk.asal} (${suratMasuk.tempat})</div>
                        </td>
                        <td>
                            <div>${ketAkses}</div>
                            <div>${ketDisposisi}</div>
                            <div style="font-size:10px;">${tujuan.created_at}</div>
                        </td>
                        <td>
                            <a href="${linkDetail}" class="btn btn-primary">Detail</a>
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

    function encodeID(){
        $.get("/api/encode/"+vUserId, function(data, status){
            console.log(data.encoded);
        });        
    }

    $(document).ready(function() {
        CrudModule.setApi(vApi);
        // Load data default
        // encodeID();
        loadData();
        function loadData(page = 1) {
            CrudModule.fRead(page, displayData,'filter={"user_id":"'+vUserId+'"}');
        }
        InfoModule.updateNotifWeb();
    });
</script>
@endsection
