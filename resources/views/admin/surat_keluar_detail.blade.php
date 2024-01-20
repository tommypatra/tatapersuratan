@extends('admin.template')

@section('scriptHead')
<title>Detail Surat Keluar</title>
<link href="{{ asset('js/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') }}" rel="stylesheet">
<link href="{{ asset('js/select2/dist/css/select2.min.css') }}" rel="stylesheet">
<link href="{{ asset('js/select2/dist/css/select2.custom.css') }}" rel="stylesheet">
<link href="{{ asset('js/img-viewer/viewer.min.css') }}" rel="stylesheet">

@endsection

@section('container')
<div class="container-fluid p-0">
    <h1 class="h3 mb-3">Detail Surat Keluar</h1>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">

                    <header class="p-2 border-bottom">
                        <div class="container">
                            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
                                <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                                    <li><a href="javascript:;" id="btnRefresh" onclick="refresh()" class="nav-link px-2 link-dark"><i class="align-middle" data-feather="refresh-cw"></i> Refresh</a></li>
                                    <li id="tombol-distribusi"><a href="javascript:;" id="btndistribusi" onclick="prosesdistribusi()" class="nav-link px-2 link-dark"><i class="align-middle" data-feather="send"></i> distribusi</a></li>
                                </ul>
                            </div>
                        </div>
                    </header>

                </div>
                <div class="card-body">


                    <div class="row">
                        <div class="col-lg-2 mb-3">
                            <img src="{{ asset('images/mail.webp') }}" style="width: 100%;">
                        </div>
                        <div class="col-lg-10 mb-3">
                            <div id="asal"></div>
                            <h3 id="perihal"></h3>
                            <div id="no_surat" style="font-style:italic;"></div>
                            <div id="tempat_tanggal" style="font-style:italic;"></div>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <div id="lampiran"></div>
                        </div>

                        <h4>Distribusi Surat :</h4>
                        <div id="track_distribusi"></div>
                        <div class="col-lg-12 mt-3">
                            <ul id="distribusi-surat"></ul>
                        </div>
                        <!-- Section: Timeline -->

                    </div>

                </div>

            </div>
        </div>
    </div>
</div>

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

@endsection

@section('scriptJs')
<script src="{{ asset('js/select2/dist/js/select2.min.js') }}"></script>
<script src="{{ asset('js/select2lib.js') }}"></script>
<script src="{{ asset('js/crud.js') }}"></script>
<script src="{{ asset('js/img-viewer/viewer.min.js') }}"></script>

<script type="text/javascript">
    var vApi = '/api/distribusi';
    var vJudul = 'Detail Surat Keluar';

    function refresh() {
        // infodistribusi();
        initData();
    }

    initData();

    function initData() {
        //load data tujuan berdasarkan id
        var htmlData;
        //reset form
        $("#asal").empty();
        $("#no_surat").empty();
        $("#tempat_tanggal").empty();
        $("#perihal").empty();
        $("#created_at").empty();
        $('#distribusi-surat').empty();
        $("#surat_keluar_id").val("");

        CrudModule.setApi('/api/surat-keluar');
        CrudModule.fSearchId("{{ $id }}", function(response) {
            if (response.data.length > 0) {
                let suratKeluar = response.data[0];
                let cariData = cariArray(suratKeluar.distribusi, parseInt(vUserId), 'user_id');

                if (cariData.length > 0) {
                    if (cariData[0].waktu_akses == null) {
                        updateWaktuAkses(cariData[0].id, cariData[0].surat_keluar_id);
                    }
                } else if (response.data.user_id == vUserId) {
                    window.location.replace("{{ route('akun-dashboard') }}");
                    alert("Maaf, akses ditolak");
                    return;
                }

                let is_owner=true;
                if(suratKeluar.user_id!=vUserId){
                    $("#tombol-distribusi").empty();
                    is_owner=false;
                }
                // console.log(suratKeluar);
                $("#asal").html(suratKeluar.asal);
                $("#no_surat").html(suratKeluar.no_surat);
                $("#tempat_tanggal").html('Tanggal Surat : ' + suratKeluar.tanggal);
                $("#perihal").html(suratKeluar.perihal);
                $("#created_at").html(suratKeluar.created_at);

                $("#surat_keluar_id").val(suratKeluar.id);

                //lampiran
                var lampiran = `<span class="badge bg-danger">Belum terupload</span>`;
                if (suratKeluar.jumlah_lampiran > 0) {
                    lampiran = ` <h4>Dokumen Surat Keluar :</h4>
                                <div class="row" id="list-images">`;
                    $.each(suratKeluar.lampiran_surat_keluar, function(i, dt) {
                        let linkfile = `${vBaseUrl}/${dt.upload.path}`;
                        lampiran += `<div class="col-lg-3 mb-3 ">`;
                        if (is_image(dt.upload.type))
                            lampiran += `<span class="img-preview"><img src="${linkfile}" width="100%"></span>`;
                        else
                            lampiran += `<i class="fa-solid fa-arrow-up-right-from-square"></i> <a href="${linkfile}" target="_blank">${dt.upload.name}</a>`;
                        lampiran += `</div>`;
                    });
                    lampiran += `</div>`;
                }
                $("#lampiran").html(lampiran);

                // track_distribusi =`<div style="font-size:11px;">`;
                let conv;
                let badge_clr;
                let track_distribusi =`<ul style="list-style: none;margin: 1;padding: 0;" class="fa-ul">`;
                $.each(suratKeluar.distribusi, function(i, dt) {
                    conv=waktuLalu(dt.created_at);
                    badge_clr='danger'; 
                    badge_icon='check'; 
                    let hapusDistribusi=`<a href="javascript:;" onclick="hapusDistribusi(${dt.id})"><i class="fa-regular fa-trash-can"></i></a>`;
                    if(dt.waktu_akses!==null){
                        badge_clr='success'; 
                        badge_icon='check-double'; 
                        hapusDistribusi='';
                    }

                    if(!is_owner){
                        hapusDistribusi='';
                    }

                    track_distribusi +=`
                        <li>
                            <span class="fa-li"><i class="fa-solid fa-${badge_icon}"></i></span>
                            <i class="fa-regular fa-clock"></i> ${conv} - ${dt.user.name} ${hapusDistribusi}
                        </li>`;
                });
                track_distribusi +=`</ul>`;
                $("#track_distribusi").html(track_distribusi);

            } else {
                window.location.replace("{{ route('akun-dashboard') }}");
            }
        });
    }

    // hapus
    function hapusDistribusi(id) {
        CrudModule.setApi('/api/distribusi');
        CrudModule.fDelete(id, function(response) {
            refresh();
        });
    }
    
    function updateWaktuAkses(id, surat_keluar_id) {
        let setup_ajax = {
            type: 'PUT',
            url: '/api/distribusi/' + id
        };
        let dataForm = {
            id:id,
            user_id: vUserId,
            surat_keluar_id: surat_keluar_id,
        };
        CrudModule.fSave(setup_ajax, dataForm, function(response) {});
    }

    $(document).on("click", ".img-preview", function() {
        const gallery = new Viewer(document.getElementById('list-images'));
        gallery.show();
    })

    sel2_cariUser(3, '#select_user_id', '#form-distribusi .modal-content');

    function prosesdistribusi() {
        $('#form-distribusi')[0].reset();
        $('#select_user_id').val("").trigger("change");
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

</script>

@endsection