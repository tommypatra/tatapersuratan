@extends('admin.template')

@section('scriptHead')
<title>Tanda Tangan Elektronik</title>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/smoothness/jquery-ui.css" crossorigin="anonymous">

<link href="{{ asset('js/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') }}" rel="stylesheet">
<link href="{{ asset('js/select2/dist/css/select2.min.css') }}" rel="stylesheet">
<link href="{{ asset('js/select2/dist/css/select2.custom.css') }}" rel="stylesheet">
<link href="{{ asset('js/img-viewer/viewer.min.css') }}" rel="stylesheet">

@endsection

@section('container')
<div class="container-fluid p-0">
    <h1 class="h3 mb-3">Tanda Tangan Elektronik</h1>
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
                                    <li><a href="#" id="btnFilter" onclick="setfilter()" class="nav-link px-2 link-dark"><i class="align-middle" data-feather="filter"></i> Filter</a></li>
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
                                    <th style="width:15%">Pejabat Spesimen/ Asal</th>
                                    <th style="width:50%">Tanggal/ Nomor/ Perihal</th>
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
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-label">Form</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body ">
                    <div class="row">
						<div class="col-lg-6 mb-3">
                            <label class="form-label">Nomor Surat</label>
                            <input name="no_surat" id="no_surat" type="text" class="form-control" required>
                        </div>
						<div class="col-lg-3 mb-3">
                            <label class="form-label">Tanggal Surat</label>
                            <input name="tanggal" id="tanggal" type="text" class="form-control datepicker" value="{{ date("Y-m-d") }}" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-8 mb-3">
                            <label class="form-label">Pejabat Bertanda Tangan</label>
                            <select class="form-control" id="user_ttd_id" name="user_ttd_id" required></select>
                        </div>
						<div class="col-lg-6 mb-3">
                            <label class="form-label">Nama Pegawai</label>
                            <input name="pejabat" id="pejabat" type="text" class="form-control" required>
                        </div>
						<div class="col-lg-6 mb-3">
                            <label class="form-label">Jabatan</label>
                            <input name="jabatan" id="jabatan" type="text" class="form-control" readonly required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12 mb-3">
                            <label class="form-label">Perihal</label>
                            <textarea name="perihal" id="perihal" rows="7" class="form-control" required></textarea>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12 mb-3">
                            <label class="form-label">File Surat (Pdf)</label>
                            <input type="file" name="file" id="file" class="form-control" accept=".pdf" required>
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

{{-- Modal Upload --}}
<div class="modal fade" id="modal-verifikasi" role="dialog">
    <div class="modal-dialog">
        <form id="myFormVerifikasi">
            <div id="datapdf" data-pdf=""></div>
            <div id="idttd" data-id=""></div>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-label" >Form</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body ">
                    <div class="row">
						<div class="col-lg-8 mb-3">
                            <label class="form-label">Diterima</label>
                            <select class="form-control" id="is_diterima" name="is_diterima" required></select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 mb-3">
                            <label class="form-label">Keterangan</label>
                            <textarea name="catatan" id="catatan" rows="3" class="form-control" ></textarea>
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
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script> --}}
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js" crossorigin="anonymous"></script>

<script type="text/javascript">
    cekAkses('pengguna');

    var vApi='/api/ttd-elektronik';
    var vJudul='Tanda Tangan Elektronik';
    var vsurat_keluar_id;

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

    var fieldInit={
        'id': { action: 'val' },
        'no_surat': { action: 'val' },
        'tanggal': { action: 'val' },
        'pejabat': { action: 'val' },
        'jabatan': { action: 'val' },
        'perihal': { action: 'val' },
        'user_ttd_id': { action: 'select2' },
        // 'file': { action: 'val' },
    };

    $('.datepicker').bootstrapMaterialDatePicker({
        weekStart: 0,
        format: 'YYYY-MM-DD',
        time: false,
    });

    sel2_publish("#is_diterima","#myFormVerifikasi .modal-content");

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
                
                var labelApp=labelSetupVerifikasi(dt.is_diajukan,dt.is_diterima,dt.catatan,dt.tujuan.name);

                var my_menu=`<ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">`;

                if(dt.user_id==vUserId){
                    if(!dt.is_diajukan){
                        my_menu+=`  <li><a class="dropdown-item" href="javascript:;" onclick="ajukan(${dt.id})"><i class="fa-regular fa-share-from-square"></i> Ajukan</a></li>
                                    <li><a class="dropdown-item" href="javascript:;" onclick="ganti(${dt.id})"><i class="fa-solid fa-pen-to-square"></i> Ganti</a></li>
                                    <li><a class="dropdown-item" href="javascript:;" onclick="hapus(${dt.id})"><i class="fa-solid fa-trash"></i> Hapus</a></li>`;
                    }
                }
                if(dt.user_ttd_id==vUserId){
                    if(dt.is_diajukan && dt.is_diterima==null){
                        my_menu+=`  <li><a class="dropdown-item" href="javascript:;" onclick="validasi(1,${dt.id})"><i class="fa-solid fa-envelope-circle-check"></i> Terima</a></li>
                                    <li><a class="dropdown-item" href="javascript:;" onclick="validasi(0,${dt.id})"><i class="fa-solid fa-rectangle-xmark"></i> Tolak</a></li>`;
                    }
                }

                my_menu+=`      <li><a class="dropdown-item" href="tte/${dt.kode}" target="_blank"><i class="fa-solid fa-book"></i> Detail</a></li>
                            </ul>`;

                var qrcode=(dt.qrcode)?`<img src="${vBaseUrl}/${dt.qrcode}" class="mt-2 mb-2" height="100px">`:'';
                
                var row = `
                    <tr>
                        <td>${nomor++}</td>
                        <td>
                            ${dt.pejabat}
                            <div style="font-style:italic;">${dt.jabatan}</div>
                            <div style="font-size:10px;font-style:italic;">                                
                                (${dt.user.name}) 
                            </div>
                        </td>
                        <td><span class="badge bg-primary">${dt.tanggal}</span><br>${dt.no_surat}
                            <div>
                                <i class="fas fa-quote-left fa-1x"></i>
                                <blockquote class="blockquote pb-2">
                                    <p>
                                    ${dt.perihal}
                                    </p>
                                </blockquote>
                                ${labelApp.catatan}                                 
                            </div>                               
                        </td>
                        <td>
                            <div>${labelApp.label}</div>
                            <div>
                                ${qrcode}
                                <div><a href="${dt.file}" id="urlpdf" target="_blank">Lampiran File</a></div>
                            </div>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"></button>
                                ${my_menu}
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
        $('#user_ttd_id').val("").trigger("change");

        CrudModule.resetForm(fieldInit);
        showHideModal('modal-form');
    }

    // tambah data
    function tambah() {
        $('#file').prop('required', true);
        showModalForm();
        $('#modal-label').text('Tambah '+vJudul);
        $('#btn-simpan').text('Simpan');
    };

    // ganti dan populasi data
    function ganti(id) {
        $('#file').prop('required', false);
        $.ajax({
            url: '/api/ttd-elektronik/'+id,
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

                }

            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    }

    
    //validasi form dan submit handler untuk simpan atau ganti
    $("#myForm").validate({
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

        var dataForm = new FormData($(form)[0]);
        dataForm.append('_method', setup_ajax.type);

        CrudModule.fSaveUpload(setup_ajax, dataForm, function(response) {
            if (response.success) {
                refresh();
            } 
            appShowNotification(response.success,[response.message]);
        });
    }		    

    // hapus
    function hapus(id) {
        CrudModule.fDelete(id, function(response) {
            refresh();
            InfoModule.updateNotifWeb();
        });
    }



    //----------------- verifikasi -----------------------------

    function verifikasi(vid){
        $('#myFormVerifikasi')[0].reset();
        $('#idttd').data('id',vid);
        var closestRow = $(event.target).closest('tr');
        var pdfUrl = closestRow.find('#urlpdf').attr('href');
        $('#is_diterima').val("").trigger("change");
        
        CrudModule.resetForm(fieldInit);
        showHideModal('modal-verifikasi');

        // extractPdf(pdfUrl).then(function(resultArray) {
        //     $('#datapdf').data('pdf','');
        //     console.log(resultArray);
        //     if(resultArray.proses){
        //         $('#datapdf').data('pdf',resultArray);

        //         let myModalForm = new bootstrap.Modal(document.getElementById('modal-verifikasi'), {
        //             backdrop: 'static',
        //             keyboard: false,
        //         });
        //         myModalForm.toggle();
        //     }else{
        //         alert('tidak ada tempat tanda tangan');
        //     }
        // });

    }

    $("#myFormVerifikasi").validate({
        submitHandler: function(form) {
            simpanVerifikasi(form)
        }
    });   

    function ajukan(id){
        if(confirm("apakah anda yakin?")){
            $.ajax({
                url: "/api/ajukan-ttd-elektronik",
                type: "POST",
                data: {'id':id},
                dataType: 'json',
                success: function (response) {
                    if(response.success){
                        refresh();
                        InfoModule.updateNotifWeb()
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
                url: '/api/verifikasi-ttd-elektronik',
                method: 'POST',
                dataType: 'json',
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


    // ganti dan populasi data
    function simpanVerifikasi(form) {
        let id=$("#idttd").data('id');
        // let pdf=$("#datapdf").data('pdf');
        // let dataFormArray = $(form).serializeArray();
        let dataForm = $(form).serialize();
        // dataFormArray.push({ name: 'pdf', value: JSON.stringify(pdf) });
        // let dataForm = $.param(dataFormArray);
        $.ajax({
            url: '/api/ttd-elektronik-verifikasi/'+id,
            method: 'PUT',
            dataType: 'json',
            data:dataForm,
            success: function (response){
                if (response.success) {
                    showHideModal('modal-verifikasi',false);
                    refresh();
                    InfoModule.updateNotifWeb();
                } 
                appShowNotification(response.success,[response.message]);
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    }    


    //-------------------------

    function extractPdf(pdfUrl) {
        return new Promise(function(resolve, reject) {
            var searchText = '^';
            var resultArray = {'proses':0,'extract':{}};
            pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

            pdfjsLib.getDocument(pdfUrl).promise.then(function(pdfDoc) {
                var numPages = pdfDoc.numPages;

                var promises = [];
                
                for (var pageNum = 1; pageNum <= numPages; pageNum++) {
                    (function(pageNum) {
                        var promise = pdfDoc.getPage(pageNum).then(function(page) {
                            return page.getTextContent().then(function(textContent) {
                                var pageResult = { 'qrqode': 0, 'x': 0, 'y': 0 };
                                textContent.items.forEach(function(textItem) {
                                    if (textItem.str.trim() == searchText) {
                                        resultArray['proses'] = 1;

                                        pageResult['qrqode'] = 1;
                                        pageResult['x'] = textItem.transform[4];
                                        pageResult['y'] = textItem.transform[5];
                                        // pageResult['transform'] = textItem.transform;
                                    }
                                });
                                resultArray['extract'][pageNum] = pageResult;
                            });
                        });
                        promises.push(promise);
                    })(pageNum);
                }

                Promise.all(promises).then(function() {
                    resolve(resultArray);
                });
            });
        });
    }


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


        $("#no_surat").autocomplete({
            appendTo: "#modal-form",
            source: function(request, response) {
                var dateInput = $('#tanggal').val();
                var year = new Date(dateInput).getFullYear();
                year = (year)?year:"{{ date('Y') }}";
                $.ajax({
                    url: "api/surat-keluar",
                    type: "GET",
                    dataType: "json",
                    data: {
                        page: 1,
                        filter: JSON.stringify({ status: 'diterima', tahun: year }),
                        keyword: request.term                    
                    },
                    success: function(data) {
                        var data_respon = data.data.map(function(item) {
                            return {
                                label: item.no_surat, // Teks yang akan ditampilkan dalam saran
                                value: item.no_surat, // Nilai yang akan diisi ke input saat dipilih
                                id: item.id,
                                tanggal: item.tanggal,
                                perihal: item.perihal,
                            };
                        });
                        console.log(data_respon);
                        response(data_respon);
                    },
                });
            },
            minLength: 2,
            select: function(event, ui) {
                $("#perihal").val(ui.item.perihal);
                $("#tanggal").val(ui.item.tanggal);
            }
        });

        loadDataKonsep();
        InfoModule.updateNotifWeb();

        //load user
        $.ajax({
            url: '/api/get-pejabat?page=all',
            method: 'GET',
            dataType: 'json',
            success: function (response){
                let vdata=[];
                if(response.data.length>0){
                    $.each(response.data, function(index, dt) {
                        var vjabatan=dt.jabatan;
                        if(dt.pejabat)
                            // vdata.push({id:dt.pejabat.id,text:dt.pejabat.name+' ('+dt.pejabat.email+') '+vjabatan,jabatan:vjabatan,nama:dt.pejabat.name});
                            vdata.push({id:dt.pejabat.id,text:vjabatan,jabatan:vjabatan,nama:''});
                    });
                }
                sel2_datalokal('#user_ttd_id',vdata,false,'#myForm .modal-content');
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });

        $('#user_ttd_id').on('select2:select', function(e) {
            var data=$(e.params.data);            
            $('#pejabat').val(data[0].nama);
            $('#jabatan').val(data[0].jabatan);
            // console.log(data.id);
        });        



    });    

</script>
@endsection
