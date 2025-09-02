@extends('admin.template')

@section('scriptHead')
<title>Penomoran Surat Keluar</title>
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
                                    <li><a href="javascript:;" id="btnFilter" class="nav-link px-2 link-dark" ><i class="align-middle" data-feather="filter"></i> Filter</a></li>
                                    <li><a href="javascript:;" id="btnCetak" class="nav-link px-2 link-dark"><i class="align-middle" data-feather="printer"></i> Cetak</a></li>
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
                        {{-- <li class="nav-item">
                            <a class="nav-link active" href="javascript:;" id="tabKonsep" onclick="setActiveTab('tabKonsep')">Konsep</a>
                        </li> --}}
                        <li class="nav-item">
                            <a class="nav-link active" href="javascript:;" id="tabAjukan" onclick="setActiveTab('tabAjukan')">Diajukan</a>
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
                                    <th style="width:15%">Jenis/ Pejabat Spesimen</th>
                                    <th style="width:40%">Tanggal/ Nomor/ Perihal/ Asal</th>
                                    <th style="width:15%">Tujuan/ Ringkasan</th>
                                    <th style="text-align: center; width:20%">Lampiran</th>
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
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-label">Form</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body ">
                    <div class="row" id="no-surat-manual">
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
                            <select name="pola_spesimen_id" id="pola_spesimen_id" type="text" class="form-control " required></select>
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
                        <div class="col-lg-12 mb-3">
                            <label class="form-label">Perihal</label>
                            <textarea name="perihal" id="perihal" rows="5" class="form-control" required></textarea>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-4 mb-3">
                            <label class="form-label">Asal</label>
                            <input name="asal" id="asal" type="text" class="form-control" required>
                        </div>
                        <div class="col-lg-8 mb-3">
                            <label class="form-label">Tujuan</label>
                            <textarea name="tujuan" id="tujuan" rows="5" class="form-control"></textarea>
                            <input type="checkbox" id="gabungkan" name="gabungkan" value="1"> gabung perihal dan tujuan
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

{{-- Modal Filter --}}
<div class="modal fade " id="modal-filter" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-filter-label">Filter</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-4 mb-3">
                        <label class="form-label">Tanggal</label>
                        <select class="form-control" id="filter_tanggal" name="filter_tanggal">
                            <option value="SEMUA">SEMUA</option>
                            @for ($i = 1; $i <= 31; $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>

                    <div class="col-sm-4 mb-3">
                        <label class="form-label">Bulan</label>
                        <select class="form-control" id="filter_bulan" name="filter_bulan">
                            <option value="SEMUA">SEMUA</option>
                            @php
                                $bulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','November','Desember'];
                            @endphp
                            @foreach ($bulan as $i => $item)
                                <option value="{{ $i+1 }}">{{ $item }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-4 mb-3">
                        <label class="form-label">Tahun</label>
                        <select class="form-control" id="filter_tahun" name="filter_tahun">
                            @php
                                $tahun_awal = 2024;
                                $tahun_sekarang = (int)date('Y');
                            @endphp
                            @for ($i = $tahun_sekarang; $i >= $tahun_awal; $i--)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <label class="form-label">Kategori</label>
                        <select class="form-control mt-2" id="filter_kategori" >
                        </select>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <label class="form-label">Jabatan</label>
                        <select class="form-control mt-2" id="filter_jabatan" >
                        </select>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="terapkan-filter" class="btn btn-primary">Terapkan</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>

        </div>
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

@endsection

@section('scriptJs')
<script src="{{ asset('js/bootstrap-material-moment/moment.js') }}"></script>
<script src="{{ asset('js/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js') }}"></script>
<script src="{{ asset('js/select2/dist/js/select2.min.js') }}"></script>
<script src="{{ asset('js/select2lib.js') }}"></script>
<script src="{{ asset('js/crud.js?v=1') }}"></script>
<script src="{{ asset('js/img-viewer/viewer.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script src="{{ url('js/foto-dokumen.js')}}"></script>

<script type="text/javascript">
    cekAkses('pengguna');
    var vApi='/api/surat-keluar';
    var vJudul='Penomoran Surat Keluar';
    var vsurat_keluar_id;
    var tahunFilter = '{{ date("Y") }}';
    var vsurat_masuk_id;
    var vPolaAkses={};
    // console.log(hakAkses);
    var hakAkses = vAksesId;
    // if(hakAkses>1)
    //     CrudModule.setFilter(`{"user_id":"${vUserId}"}`);
    const fotoDokumen = new FotoDokumen('video', 'preview-container', 'capture-btn', 'crop-btn');


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
        'pola_spesimen_id': { action: 'select2' },
        'klasifikasi_surat_id': { action: 'select2' },
    };

    $('.datepicker').bootstrapMaterialDatePicker({
        weekStart: 0,
        format: 'YYYY-MM-DD',
        time: false,
    });


    function isValidTahun(input) {
        const regex = /^\d{4}$/;
        return regex.test(input);
    }   

    // Fungsi untuk mengatur kelas active
    function setActiveTab(tabId) {
        $('.nav-link').removeClass('active'); 
        $('#' + tabId).addClass('active');
        console.log(tabId);
        loadDataTab();
    }
    
    //refresh
    function refresh(page=null){
        CrudModule.setFilter(filterData());
        if(page)
            CrudModule.fRead(page, displayData);
        else
            CrudModule.refresh(displayData);
    }
    
    

    //pencarian data
    $('#search-data').on('input', function() {
        var keyword = $(this).val();
        if (keyword.length == 0 || keyword.length >= 3) {
            CrudModule.setKeyword(keyword);
            CrudModule.setFilter(filterData());
            CrudModule.fRead(1, displayData);
        }
    });    

    function loadData(page = 1) {
        CrudModule.fRead(page, displayData);
    }

    function cekAksesPola(pola_spesimen_id) {
        let item = vPolaAkses.find(function(item) {
            return item.pola_spesimen_id === pola_spesimen_id;
        });

        // Jika item ditemukan, return true, jika tidak ditemukan return false
        return item !== undefined; 
    }


    //read showdata
    function displayData(response) {
        var data = response.data;
        var tableBody = $('#dataTableBody');
        var nomor = response.meta.from;
        // console.log(vPolaAkses);
        tableBody.empty();
        if(data.length>0)
            $.each(data, function(index, dt) {
                var pembuat_surat = false;

                var lampiran=`<span class="badge bg-danger">Belum terupload</span>`;
                var labelApp=labelSetupVerifikasi(dt.is_diajukan,dt.is_diterima,dt.catatan,dt.verifikator);
                var menu_edit=``;
                var ada_akses = cekAksesPola(dt.pola_spesimen_id);

                if(dt.user_id==vUserId){
                    pembuat_surat=true;
                    menu_edit=` <li><a class="dropdown-item" href="javascript:;" onclick="ganti(${dt.id})"><i class="fa-solid fa-pen-to-square"></i> Ganti</a></li>`;
                    // if(dt.jumlah_lampiran>0 && !dt.is_diajukan)
                    menu_edit+=` <li><a class="dropdown-item" href="javascript:;" onclick="ajukan(${dt.id})"><i class="fa-regular fa-share-from-square"></i> Ajukan</a></li>`;
                }
                                
                if(dt.is_diajukan){
                    menu_edit=``;
                    if(dt.is_diterima==null && ada_akses){
                        menu_edit+=` <li><a class="dropdown-item" href="javascript:;" onclick="validasi(1,${dt.id})"><i class="fa-solid fa-envelope-circle-check"></i> Terima</a></li>
                                    <li><a class="dropdown-item" href="javascript:;" onclick="validasi(0,${dt.id})"><i class="fa-solid fa-rectangle-xmark"></i> Tolak</a></li>`;
                    }
                    menu_edit+=`<li><a class="dropdown-item" href="javascript:;" onclick="hapus(${dt.id})"><i class="fa-solid fa-trash"></i> Hapus</a></li>`;
                }

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
                    if(dt.is_diterima)
                        menu_detail=`<li><a class="dropdown-item" href="javascript:;" onclick="prosesdistribusi(${dt.id})"><i class="fa-regular fa-paper-plane"></i> Distribusi</a></li>`;
                }
                
                if(dt.is_diterima && (hakAkses==1 || pembuat_surat))
                    menu_detail+=`<li><a class="dropdown-item" href="javascript:;" onclick="ganti(${dt.id})"><i class="fa-solid fa-pen-to-square"></i> Ganti</a></li>`;
                else if(dt.is_diterima==0 && (hakAkses==1))
                    menu_edit+=`<li><a class="dropdown-item" href="javascript:;" onclick="validasi(1,${dt.id})"><i class="fa-solid fa-envelope-circle-check"></i> Terima</a></li>`;

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
                            <div>${labelApp.catatan}</div>                             
                        </td>
                        <td>${(dt.tujuan!=null)?dt.tujuan:""}<div style="font-style:italic;font-size:12px;">${ringkasan}</div></td>
                        <td style="text-align: center">
                            <div>${labelApp.label}</div>
                            <i class="fa-solid fa-users"></i> ${dt.jumlah_distribusi}
                            <div class="btn-group-sm">
                                <a href="javascript:;" class="btn btn-primary uploadLampiran" data-surat_keluar_id="${dt.id}"><i class="fa-solid fa-upload"></i></a>
                                <a href="javascript:;" class="btn btn-primary fotoLampiran" data-surat_keluar_id="${dt.id}"><i class="fa-solid fa-camera"></i></a>
                            </div>
                            ${lampiran}                        
                        </td>
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
    function tambah() 
    {
        $('#gabungkan').removeAttr('disabled');
        showModalForm();
        $('#modal-label').text('Tambah '+vJudul);
        $('#btn-simpan').text('Simpan');
    };

    // ganti dan populasi data
    function ganti(id) {
        $('#gabungkan').attr('disabled', true);
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
                    $('#tanggal').val(dt.tanggal);
                    
                    //ubah form
                    $('#modal-label').text('Ganti '+vJudul);
                    $('#btn-simpan').text('Ubah Sekarang');

                    if(dt.klasifikasi_surat_id){                    
                        let option_klasifikasi = new Option(dt.klasifikasi_surat.kode+' '+dt.klasifikasi_surat.klasifikasi, dt.klasifikasi_surat.id, true, true);
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
                InfoModule.updateNotifWeb();
                $('#modal-form').modal('hide');
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

    // $('#spesimen_jabatan_id').select2({
    //     placeholder: "-pilih-"
    // });
    $('#pola_spesimen_id').select2({
        placeholder: "-pilih-"
    });
    sel2_cariKlasifikasi(3,'#klasifikasi_surat_id','#myForm .modal-content')


    $("#tanggal").change(function() { 
        initPola();
    });

    function initAkses(callback){
        $.ajax({
            url: `/api/get-akses-pola?page=all&filter={"tahun":2025,"user_id_login":null}`,
            method: 'GET',
            dataType: 'json',
            success: function (response){
                vPolaAkses = response.data;
                console.log('initAkses selesai', vPolaAkses);
                if (callback) 
                    callback();            
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    }     

    function initPola(){
        //set empty dan hide dulu
        $('#pola_spesimen_id').empty();
        $('#spesimen_jabatan_id').val("");
        $("#el-klasifikasi").hide();
        //load pola
        let thn = new Date($("#tanggal").val()).getFullYear();        
        // let dataCari = {tahun:thn,user_id:vUserId};
        let dataCari = {tahun:thn};
        let keywordString = encodeURIComponent(JSON.stringify(dataCari));

        $.ajax({
            url: '/api/get-pola-spesimen?page=all',
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

                sel2_datalokal('#pola_spesimen_id',vdata,false,'#myForm .modal-content');

            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    }

    $("#el-klasifikasi").hide();
    $('#pola_spesimen_id').on('change', function() {
        // initSpesimen();
        let needs_klasifikasi=$(this).select2('data')[0].needs_klasifikasi;

        if(needs_klasifikasi){
            $("#el-klasifikasi").show();
        }else{
            $("#el-klasifikasi").hide();
        }
    });

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
                url: "/api/proses-ajuan-surat-keluar",
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

    function initPolaSurat(){
        $.ajax({
            url: '/api/pola-surat-keluar?page=all',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                console.log(response);
                let $select = $('#filter_kategori');
                $select.empty();
                $select.append('<option value="SEMUA">SEMUA</option>');
                if (response.data.length > 0) {
                    response.data.forEach(function(item) {
                        $select.append('<option value="' + item.id + '">' + item.kategori + '</option>');
                    });
                }
            },
            error: function(xhr, status, error) {
                alert(error);
            }
        });
    }

    function initJabatan(){
        $.ajax({
            url: '/api/spesimen-jabatan?page=all',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                console.log(response);
                let $select = $('#filter_jabatan');
                $select.empty();
                $select.append('<option value="SEMUA">SEMUA</option>');
                if (response.data.length > 0) {
                    response.data.forEach(function(item) {
                        $select.append('<option value="' + item.id + '">' + item.jabatan + '</option>');
                    });
                }
            },
            error: function(xhr, status, error) {
                alert(error);
            }
        });
    }

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
                    loadData();
                },
                error: function (xhr, status, error) {
                    appShowNotification(false, ["Something went wrong. Please try again later."]);
                },
            });
        }
    }

    $(document).on("click", ".uploadLampiran", function () {
        var surat_keluar_id = $(this).data("surat_keluar_id");
        var fileInput = $('<input type="file" id="lampiran" name="lampiran" accept=".jpg, .jpeg, .png, .pdf, .doc, .docx, .ppt, .pptx, .xls, .xlsx" style="display: none;">');
        $("body").append(fileInput);
        fileInput.click();

        fileInput.change(function () {
            var selectedFile = this.files[0];
            if (selectedFile) {
                const formData = new FormData();
                formData.append("surat_keluar_id", surat_keluar_id);
                formData.append("file", selectedFile);
                $.ajax({
                    url: `api/lampiran-surat-keluar`,
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        refresh();
                    },
                    error: function (xhr, status, error) {
                        alert('gagal terupload.');
                    }
                });
            }
        });        
    });  
    
    function ajukan(id){
        if(confirm("apakah anda yakin?")){
            $.ajax({
                url: "/api/ajukan-surat-keluar",
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

    function uploadFile(surat_keluar_id,  file, fileName) {
        const formData = new FormData();
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

    $(document).on("click", ".fotoLampiran", function () {
        vsurat_keluar_id = $(this).data("surat_keluar_id");
        fotoDokumen.setValues(vsurat_keluar_id,'surat-keluar');
        // alert(id);
        let myModalUpload = new bootstrap.Modal(document.getElementById('modal-upload'), {
            backdrop: 'static',
            keyboard: false,
        });
        myModalUpload.toggle();
    });


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
            simpanDistribusi();
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
                        InfoModule.updateNotifWeb();
                    }                    
                },
                error: function (xhr, status, error) {
                    appShowNotification(false,['Terjadi kesalahan, '+error]);
                }
            });    
        });

    }   

    function salinText() {
        var textToCopy = $('#salinText').text().trim();
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(textToCopy).then(function() {
                alert("Teks berhasil disalin ke clipboard!");
            }).catch(function(error) {
                console.error("Gagal menyalin teks: ", error);
            });
        } 
    }

    function filterData(){
        var activeTabId = $('.nav-tabs .nav-link.active').attr('id');
        var kategori = $('#filter_kategori').val();
        var jabatan = $('#filter_jabatan').val();
        var tahun=$('#filter_tahun').val();
        var bulan=$('#filter_bulan').val();
        var tanggal=$('#filter_tanggal').val();
        // var keyword=$('#search-data').val();
                
        status='konsep';
        if(activeTabId=='tabAjukan')         
            status='diajukan';
        else if(activeTabId=='tabTerima')         
            status='diterima';
        else if(activeTabId=='tabTolak')         
            status='ditolak';

        return `{"status":"${status}","tahun":"${tahun}","bulan":"${bulan}","tanggal":"${tanggal}","kategori":"${kategori}","jabatan":"${jabatan}"}`;
    }    

    function loadDataTab(page = 1) {        
        refresh(page);
    }

        
    $(document).ready(function() {

        initPolaSurat();
        initJabatan();

        if (hakAkses === 1) {
            $('#no-surat-manual').show(); // Menampilkan elemen jika nilai variabel adalah 1
        } else {
            $('#no-surat-manual').hide(); // Menyembunyikan elemen jika nilai variabel bukan 1
        }
        
        // Mengatur API untuk CrudModule
        CrudModule.setApi(vApi);
        // Load data default

        // Urutkan eksekusi
        initAkses(function() {
            initPola(); 
            loadDataTab();
        });        
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

        $('#btnFilter').click(function () {
            let myModalFilter = new bootstrap.Modal(document.getElementById('modal-filter'), {
                backdrop: 'static',
                keyboard: false,
            });
            myModalFilter.toggle();
        });

        $('#btnCetak').click(function () {
            var keyword = $("#search-data").val();
            var filter = filterData();
            var url = vBaseUrl+`/cetak-surat-keluar?keyword=${keyword}&filter=${filter}`;
            window.open(url, '_blank');
        });       
        
        $('#terapkan-filter').click(function(){   
            refresh(1);
        });

    });


</script>
@endsection
