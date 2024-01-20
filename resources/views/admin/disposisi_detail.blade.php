@extends('admin.template')

@section('scriptHead')
<title>Detail Disposisi</title>
<link href="{{ asset('js/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') }}" rel="stylesheet">
<link href="{{ asset('js/select2/dist/css/select2.min.css') }}" rel="stylesheet">
<link href="{{ asset('js/select2/dist/css/select2.custom.css') }}" rel="stylesheet">
<link href="{{ asset('js/img-viewer/viewer.min.css') }}" rel="stylesheet">

<style>
    .timeline {
        border-left: 1px solid hsl(0, 0%, 90%);
        position: relative;
        list-style: none;
    }

    .timeline .timeline-item {
        position: relative;
    }

    .timeline .timeline-item:after {
        position: absolute;
        display: block;
        top: 0;
    }

    .timeline .timeline-item:after {
        background-color: hsl(0, 0%, 90%);
        left: -38px;
        border-radius: 50%;
        height: 11px;
        width: 11px;
        content: "";
    }
</style>

@endsection

@section('container')
<div class="container-fluid p-0">
    <h1 class="h3 mb-3">Detail Disposisi</h1>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">

                    <header class="p-2 border-bottom">
                        <div class="container">
                            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">                        
                                <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                                    <li><a href="javascript:;" id="btnRefresh" onclick="refresh()" class="nav-link px-2 link-dark"><i class="align-middle" data-feather="refresh-cw"></i> Refresh</a></li>
                                    <li id="tombol-disposisi"><a href="javascript:;" id="btnDisposisi" onclick="prosesDisposisi()" class="nav-link px-2 link-dark"><i class="fa-solid fa-share"></i> Disposisi</a></li>
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

                            <h4>Timeline Proses Disposisi :</h4>
                            <div class="col-lg-12 mt-3">
                                <section id="section-timeline">
                                    <ul class="timeline"></ul>
                                </section>
                            </div>
                            <!-- Section: Timeline -->    
                            
                        </div>
                                        
                </div>

            </div>
        </div>
    </div>
</div>

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
                        <label class="form-label">Disposisi Kepada</label>
                        {{-- <select class="form-control" id="select_user_id" name="user_id[]" required multiple="multiple"></select> --}}
                        <select class="form-control" id="select_user_id" name="user_id" required></select>
                    </div>
                    <div class="col-lg-12 mb-3">
                        <label class="form-label">Catatan</label>
                        <textarea class="form-control" id="catatan" name="catatan" required rows="4"></textarea>
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
    var vApi='/api/tujuan';
    var vJudul='Disposisi Baca';
    showNotif=false;
    
    function refresh(){
        infoDisposisi();                
        initData();
    }

    initData();

    function initData(){
        //load data tujuan berdasarkan id
        var htmlData;
        //reset form
        $("#asal").empty();
        $("#no_surat").empty();
        $("#tempat_tanggal").empty();
        $("#perihal").empty();
        $("#created_at").empty();
        $(".timeline").empty();
        $("#surat_masuk_id").val("");


        $.ajax({
            url: '/api/get-surat-masuk',
            type: 'POST',
            dataType: 'json',
            data: {
                id: "{{ $id }}",
            },
            success: function (response) {
                // dd(response);
                if(response.data){
                    let suratMasuk = response.data;
                    //cariArray(array,dicari,kolom)
                    let cariData = cariArray(suratMasuk.tujuan, parseInt(vUserId),'user_id');
                    const sudahDisposisi = isUserInDisposisi(suratMasuk.tujuan,vUserId);
                    if(sudahDisposisi){
                        $("#tombol-disposisi").empty();
                        // alert('sudah mendisposisi');
                    }

                    if(cariData.length>0){
                        if(cariData[0].waktu_akses==null){
                            updateWaktuAkses(cariData[0].id,cariData[0].surat_masuk_id);
                        }
                    }
                    showNotif=true;
                    updateNotifWeb();

                    $("#asal").html(suratMasuk.asal);
                    $("#no_surat").html(suratMasuk.no_surat);
                    $("#tempat_tanggal").html(suratMasuk.tempat+', '+suratMasuk.tanggal);
                    $("#perihal").html(suratMasuk.perihal);
                    $("#created_at").html(suratMasuk.created_at);

                    $("#surat_masuk_id").val(suratMasuk.id);
                    
                    //lampiran
                    var lampiran=`<span class="badge bg-danger">Belum terupload</span>`;
                    if(suratMasuk.jumlah_lampiran>0){
                        lampiran =` <h4>Dokumen Surat Masuk :</h4>
                                    <div class="row" id="list-images">`;
                        $.each(suratMasuk.lampiran_surat_masuk, function(i, dt) {
                            let linkfile=`${vBaseUrl}/${dt.upload.path}`;
                            lampiran +=`<div class="col-lg-3 mb-3 ">`;
                            if(is_image(dt.upload.type))
                                lampiran +=`<span class="img-preview"><img src="${linkfile}" width="100%"></span>`;
                            else
                                lampiran +=`<i class="fa-solid fa-arrow-up-right-from-square"></i> <a href="${linkfile}" target="_blank">${dt.upload.name}</a>`;
                            lampiran +=`</div>`;
                        });
                        lampiran +=`</div>`;
                    } 
                    $("#lampiran").html(lampiran);

                    let label=", belum terdisposisi sejak "+waktuLalu(suratMasuk.created_at);
                        if(suratMasuk.tujuan[0])
                            label=", membutuhkan waktu "+waktuLalu(suratMasuk.created_at, suratMasuk.tujuan[0].created_at,"")+" untuk memproses disposisi";

                    $('.timeline').append(`
                        <li class="timeline-item mb-5">
                            <h5 class="fw-bold">${suratMasuk.user.name}</h5>
                            <p class="text-muted mb-2" style="font-size:11px;">${suratMasuk.created_at}</p>
                            <p class="text-muted">
                                Surat masuk dan tercatat pada bagian umum${label}. 
                            </p>
                        </li>
                    `);

                    $.each(suratMasuk.tujuan, function(i, dt) {
                        label="";
                        if(dt.waktu_akses==null)
                            label=", belum dibaca sejak "+waktuLalu(dt.created_at);
                        else{
                            label=", belum terdisposisi sejak "+waktuLalu(dt.waktu_akses);
                            if(suratMasuk.tujuan[i+1]){
                                // label=", membutuhkan waktu "+waktuLalu(dt.created_at,suratMasuk.tujuan[i+1].created_at,'')+" untuk proses disposisi ke yth. "+suratMasuk.tujuan[i+1].user.name;
                                label=", membutuhkan waktu "+waktuLalu(dt.created_at,suratMasuk.tujuan[i+1].created_at,'')+" untuk proses disposisi ke yth. "+suratMasuk.tujuan[i+1].user.name;
                            }
                        }

                        let btnAksiDisposisi='';
                        if(dt.waktu_akses==null && dt.disposisi){
                            if(dt.disposisi.user_id==vUserId){
                                btnAksiDisposisi=`
                                    <a href="javascript:;" onclick="hapusDisposisi(${dt.id})"><i class="fa-regular fa-trash-can"></i></a>
                                `;
                            }
                        }

                        let catatanDisposisi='';
                        if(dt.disposisi){
                            catatanDisposisi=`
                                <div>
                                    <i class="fas fa-quote-left fa-2x"></i>
                                    <blockquote class="blockquote pb-2">
                                        <p>
                                        ${dt.disposisi.catatan}
                                        </p>
                                    </blockquote>
                                    <figcaption class="blockquote-footer mb-0">
                                        ${dt.disposisi.user.name}
                                        ${btnAksiDisposisi}
                                    </figcaption>                                      
                                </div>                             
                            `;
                        }

                        $('.timeline').append(`
                            <li class="timeline-item mb-5">
                                <h5 class="fw-bold">${dt.user.name}</h5>
                                <p class="text-muted mb-2" style="font-size:11px;">${dt.created_at}</p>
                                <p class="text-muted mb-2" style="font-size:11px;">Waktu tunggu baca : 5 menit</p>
                                <p class="text-muted">
                                    Surat telah masuk${label}.
                                    ${catatanDisposisi}
                                </p>
                            </li>
                        `);
                    });
                    
                    
                }else{
                    // window.location.replace("{{ route('akun-dashboard') }}");
                } 
            },
            error: function (xhr, status, error) {
                appShowNotification(false,['Terjadi kesalahan, '+error]);
            }
        });

    }

    function updateWaktuAkses(id,surat_masuk_id){
        let setup_ajax={type:'PUT',url:'/api/tujuan/'+id};
        let dataForm = {id:id,user_id:vUserId,surat_masuk_id:surat_masuk_id};
        $.ajax({
            type: setup_ajax.type,
            url: setup_ajax.url,
            data: dataForm,
            dataType: 'json',
            success: function(response) {
            },
            error: function(xhr, status, error) {
                appShowNotification(false, ['Something went wrong. Please try again later.']);
            }
        });

    }

    $(document).on("click",".img-preview",function(){
        const gallery = new Viewer(document.getElementById('list-images'));
        gallery.show();
    })

    sel2_cariUser(3,'#select_user_id','#form-disposisi .modal-content');

    function prosesDisposisi(){
        $('#form-disposisi')[0].reset();
        $('#select_user_id').val("").trigger("change");
        let myModalForm = new bootstrap.Modal(document.getElementById('modal-disposisi'), {
            backdrop: 'static',
            keyboard: false,
        });
        myModalForm.toggle();
    }

    $("#form-disposisi").validate({
        messages: {
            user_id: "pilih user dulu",
            catatan: "tidak boleh kosong",
        },
        submitHandler: function(form) {
            simpanTujuan()
        }
    });  
    
    function simpanTujuan() {
        var select_user_id = $('#select_user_id').val();
        var surat_masuk_id = $('#surat_masuk_id').val(); 
        var catatan = $('#catatan').val(); 

        $.ajax({
            url: '/api/tujuan',
            type: 'POST',
            dataType: 'json',
            data: {
                surat_masuk_id: surat_masuk_id,
                user_id: select_user_id,
                created_by:vUserId,
            },
            success: function (response) {
                if(response.success){
                    simpanDisposisi(response.data.id,catatan);
                }
            },
            error: function (xhr, status, error) {
                appShowNotification(false,['Terjadi kesalahan, '+error]);
            }
        });
    }		    

    // hapus
    function hapusDisposisi(id) {
        CrudModule.setApi('/api/tujuan');
        CrudModule.fDelete(id, function(response) {
            refresh();
        });
    }

    function simpanDisposisi(tujuan_id,catatan) {
        $.ajax({
            url: '/api/disposisi',
            type: 'POST',
            dataType: 'json',
            data: {
                tujuan_id: tujuan_id,
                catatan: catatan,
                user_id: vUserId,
            },
            success: function (response) {
                if(response.success){
                    $('#modal-disposisi').modal('hide');
                    refresh();
                }
                appShowNotification(response.success,[response.message]);
            },
            error: function (xhr, status, error) {
                appShowNotification(false,['Terjadi kesalahan, '+error]);
            }
        });
    }		    

</script>

@endsection
