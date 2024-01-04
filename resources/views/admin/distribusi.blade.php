@extends('admin.template')

@section('scriptHead')
<title>Distribusi Surat</title>
<link href="{{ asset('js/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') }}" rel="stylesheet">
<link href="{{ asset('js/select2/dist/css/select2.min.css') }}" rel="stylesheet">
<link href="{{ asset('js/select2/dist/css/select2.custom.css') }}" rel="stylesheet">
@endsection

@section('container')
<div class="container-fluid p-0">
    <h1 class="h3 mb-3">Distribusi Surat</h1>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">

                    <header class="p-2 border-bottom">
                        <div class="container">
                            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
                        
                                <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                                    <li><a href="javascript:;" id="btnRefresh" onclick="refresh()" class="nav-link px-2 link-dark"><i class="align-middle" data-feather="refresh-cw"></i> Refresh</a></li>
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
    var vApi='/api/distribusi';
    var vJudul='Distribusi Surat';

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
            $.each(data, function(index, distribusi) {
                var suratKeluar=distribusi.surat_keluar;
                var linkDetail=`/surat-keluar-detail/${suratKeluar.id}`;
                
                var ketAkses=`<span class="badge bg-danger">belum dibaca</span>`;
                if(distribusi.waktu_akses){
                    ketAkses=`<span class="badge bg-success">sudah dibaca</span>`;
                }

                var row = `
                    <tr>
                        <td>${nomor++}</td>
                        <td><span class="badge bg-success">Tanggal : ${suratKeluar.tanggal}</span><br>
                            No. ${suratKeluar.no_surat}
                            <div style="font-weight:bold;">${suratKeluar.perihal}</div>
                            <div style="font-style:italic;">Asal : ${suratKeluar.asal}</div>
                            <div style="font-style:italic;">Tujuan : ${suratKeluar.tujuan}</div>
                        </td>
                        <td>
                            <div>${ketAkses}</div>
                            <div style="font-size:10px;">${distribusi.created_at}</div>
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
    });
</script>
@endsection
