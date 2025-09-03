@extends('admin.template')

@section('scriptHead')
<title>Profil</title>
<link href="{{ asset('js/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css') }}" rel="stylesheet">
<link href="{{ asset('js/select2/dist/css/select2.min.css') }}" rel="stylesheet">
<link href="{{ asset('js/select2/dist/css/select2.custom.css') }}" rel="stylesheet">
@endsection

@section('container')
<div class="container-fluid p-0">
    <h1 class="h3 mb-3">Profil</h1>
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
                            </div>
                        </div>
                    </header>

                </div>
                <div class="card-body">                    

                    <form id="myForm">
                        <input type="hidden" name="id" id="id" >
                        <div class="row">
                            <div class="col-lg-8 mb-3">
                                <h3 id="name"></h3>
                                <div id="email" style="font-style:italic;"></div>
                            </div>
                            <div class="col-lg-4 mb-3">
                                <img src="{{ url('images/user-avatar.png') }}" class="foto-profil" height="100px ">
                                <input name="foto" id="foto" type="file" accept="image/*" onchange="uploadFoto()" class="form-control" >
                            </div>
                            <div class="col-lg-4 mb-3">
                                <label class="form-label">NIP</label>
                                <input name="nip" id="nip" type="text" class="form-control" placeholder="" required>
                            </div>
                            <div class="col-lg-4 mb-3">
                                <label class="form-label">Jenis Kelamin</label>
                                <select name="jenis_kelamin" id="jenis_kelamin" class="form-control" placeholder="" required></select>
                            </div>
                            <div class="col-lg-4 mb-3">
                                <label class="form-label">HP</label>
                                <input name="hp" id="hp" type="text" class="form-control" placeholder="" required>
                            </div>
                            <div class="col-lg-12 mb-3">
                                <label class="form-label">Alamat</label>
                                <textarea name="alamat" id="alamat" rows="5" class="form-control" placeholder="" required></textarea>
                            </div>
                        </div>
    
                        <button type="submit" class="btn btn-primary" id="btn-simpan">Simpan</button>
                        <button type="button" class="btn btn-outline-primary " data-bs-dismiss="modal">Tutup</button>
                    </form>                    
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
<script src="{{ asset('js/crud.js?v=3') }}"></script>

<script type="text/javascript">
    cekAkses('pengguna');
    var vApi='/api/profil';
    var vJudul='Profil';
    var user_id;
    var profil_id;
    sel2_jeniskelamin("#jenis_kelamin");
    function refresh(){
        initData();
    }

    //initData()
    function initData(){
        ajaxRequest(vBaseUrl+'/api/info-akun-login', 'GET', null, false,
            function(response) {
                displayData(response.data);
            }
        );        
    }

    //display data
    function displayData(dt) {
        $('#myForm')[0].reset();
        $("#jenis_kelamin").val("").trigger("change"); 
        $("#id").val("");
        user_id=dt.id;
        $("#name").html(dt.name);
        $("#email").html(dt.email);

        let profil=dt.profil;
        profil_id=profil.id;
        // $("#id").val(profil.id);
        $("#hp").val(profil.hp);
        $("#nip").val(profil.nip);
        $("#alamat").val(profil.alamat);
        $('#jenis_kelamin').val(profil.jenis_kelamin).trigger("change");
        
        if(profil.foto){
            $('.foto-profil').attr('src', profil.foto);
        }
    }    

    //validasi form dan submit handler untuk simpan atau ganti
    $("#myForm").validate({
        submitHandler: function(form) {
            let setup_ajax={type:'POST',url:vApi};
            let id=profil_id;
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
                initData();
            } 
            appShowNotification(response.success,[response.message]);
        });
    }	
    
    function uploadFoto(){
        if(confirm("Apakah Anda yakin ingin mengupload foto profil?")){
            var fileInput = $('#foto')[0];
            var formData = new FormData();
            formData.append('foto', fileInput.files[0]);

            $.ajax({
                url: 'api/ganti-foto-profil',
                type: 'post',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    if(response.success){
			            localStorage.setItem('foto', response.data.foto);
                        $('.foto-profil').attr('src', response.data.foto);
                    }
                },
                error: function (xhr, status, error) {
                    console.error(error);
                }
            });            
        }
    }

    $(document).ready(function() {
        CrudModule.setApi(vApi);
        initData();
        InfoModule.updateNotifWeb();
    });
</script>

@endsection
