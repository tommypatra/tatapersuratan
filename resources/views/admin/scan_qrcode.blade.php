@extends('admin.template')

@section('scriptHead')
<title>Scan QrCode</title>
@endsection

@section('container')
<div class="container-fluid p-0">
    <h1 class="h3 mb-3">Scan QrCode</h1>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <header class="p-2 border-bottom">
                        <div class="container">
                            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">                        
                                <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                                    <li><a href="#" id="btnRefresh" class="nav-link px-2 link-dark"><i class="align-middle" data-feather="refresh-cw"></i> Refresh</a></li>
                                    {{-- <li><a href="{{ route('scan-qrcode') }}" id="btnRefresh" class="nav-link px-2 link-dark"><i class="align-middle" data-feather="refresh-cw"></i> Refresh</a></li> --}}
                                </ul> 
                            </div>
                        </div>
                    </header>

                </div>
                <div class="card-body">
                    <div class="row"> 
                        <div class="col-sm-12" id="scan">
                            <h3>Nomor Surat Masuk</h3>                    
                            <input type="text" id="nomor_surat_masuk" class="form-control" value="{{ date('Y') }}-" maxlength="11">
                            <hr>
                            <h3>Proses Scan QrCode</h3>                    
                            <div style="width: 100%" id="reader"></div>
                        </div>
                        
                        <div class="col-sm-12" id="info-surat" style="display:none">    
                            <hr>                    
                            <h3>Infomasi Surat Masuk</h3>                    
                            <span class="badge bg-primary">Tanggal Surat : <span id="tanggal">_____</span></span>                
                            <h3 id="kategori_surat" class="mt-2">_____</h3>
                            <div>
                                <i class="fas fa-quote-left fa-1x" aria-hidden="true"></i>
                                <blockquote class="blockquote pb-2" >
                                    <p id="perihal">
                                        _____
                                    </p>
                                </blockquote>
                                <figcaption class="blockquote-footer mb-0" >
                                    Nomor Surat : <span id="no_surat">_____</span>
                                </figcaption>                                   
                            </div>     
                            <div class="mt-2">Asal Surat : <span id="asal">_____</span></div>                       
                            <div class="mt-2">Ringkasan : <span id="ringkasan">_____</span></div>    
                            <div class="mt-2" id="lampiran"></div>
                            <hr>
                            <div class="col-sm-6 mb-3">
                                <label class="form-label">Pejabat yang menerima surat : </label>
                                <select name="pejabat_user_id" id="pejabat_user_id" class="form-control"></select>
                            </div>    
                            <button class="btn btn-success mt-2" id="btn-terima">Terima Surat Masuk</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@section('scriptJs')
<script src="{{ asset('js/html5-qrcode-master/minified/html5-qrcode.min.js') }}"></script>
<script src="{{ asset('js/app.js') }}"></script>
<script>
    var vId=null;

    function aksesDisposisi(tahun) {
        $.ajax({
            url: `${vBaseUrl}/api/get-akses-disposisi?tahun=${tahun}`,
            type: "GET",
            success: function(response) {
                var uniqueData = {};

                if (response.data && response.data.length > 0) {
                    // $('#scan').hide();

                    // Iterasi data dan simpan ke objek uniqueData
                    $('#pejabat_user_id').empty();
                    $.each(response.data, function(i, item) {
                        $('#pejabat_user_id').append(new Option(item.jabatan, item.user_pejabat_id));
                    });

                } else {
                    alert("Akses disposisi tidak ditemukan");
                    window.location.replace(vBaseUrl+'/akun-dashboard');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error("Error:", textStaapitus, errorThrown);
            }
        });
    }


    function disposisi() {
        $.ajax({
            url: `${vBaseUrl}/api/tujuan`,
            type: "POST",
            data:{
                surat_masuk_id:vId,
                user_id:$('#pejabat_user_id').val(),
            },
            success: function(response) {
                appShowNotification(true,['surat masuk berhasil diterima']);
                // console.log(aksesJabatan);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                if (jqXHR.responseJSON) {
                    appShowNotification(false, jqXHR.responseJSON.message);
                } else {
                    appShowNotification(false, errorThrown);
                }
            }
        });
    }

    function bacaIsiSuratMasuk(data){

        vId=data.id;

        $('#tanggal').text(data.tanggal);
        $('#kategori_surat').text(data.kategori_surat);
        $('#perihal').text(data.perihal);
        $('#no_surat').text(data.no_surat);
        $('#ringkasan').text(data.ringkasan);
        $('#asal').text(`${data.asal} (${data.tempat})`);


        //lampiran
        var lampiran = `<span class="badge bg-danger">Belum terupload</span>`;
        if (data.lampiran_surat_masuk.length > 0) {
            lampiran = `<h4>Dokumen Surat Keluar :</h4>
                        <div class="row" id="list-images">`;
            $.each(data.lampiran_surat_masuk, function(i, dt) {
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
        $('#info-surat').show();
        alert("surat ditemukan");
        $(document).scrollTop($(document).height());
    }

    function prosesDisposisi(id){
        // Kirim request AJAX
        $.ajax({
            url: `${vBaseUrl}/api/surat-masuk/${id}`,
            type: "GET",
            success: function(response) {
                if(response.success){
                    data=response.data;
                    bacaIsiSuratMasuk(data);   
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                window.location.replace(vBaseUrl+'/akun-dashboard');
            }
        });
    }

    $(document).ready(function () {
        aksesDisposisi("{{ date('Y') }}");


        let config = {
            fps: 10,
            qrbox: {width: 100, height: 100},
            rememberLastUsedCamera: true,
            supportedScanTypes: [Html5QrcodeScanType.SCAN_TYPE_CAMERA]
        };
        let html5QrcodeScanner = new Html5QrcodeScanner("reader", config, false);

        const qrCodeSuccessCallback = (decodedText, decodedResult) => {
            const qrData = JSON.parse(decodedText);
            $("#output").html(qrData.id+' '+qrData.api);
            if(qrData.api=='disposisi'){
                prosesDisposisi(qrData.id);
            }

            //untuk hentikan scan
            html5QrcodeScanner.stop().then(() => {
                console.log("Scanning stopped successfully.");
            }).catch((err) => {
                console.error("Error stopping the scanner: ", err);
            });
        };
        html5QrcodeScanner.render(qrCodeSuccessCallback);


        $('#btnRefresh').click(function(){
            prosesDisposisi(vId);
        });

        $('#btn-terima').click(function(){
            if(confirm("yakin terima surat masuk?"))
                disposisi();
        });
        
        let isRequestInProgress = false;
        $('#nomor_surat_masuk').on('keyup', function(event) {
            var nomor_surat = $(this).val().trim();

            if (nomor_surat.length === 11 && !isRequestInProgress) {
                isRequestInProgress = true;

                $.ajax({
                    url: 'api/get-surat-masuk',
                    type: 'POST',
                    dataType: 'json',
                    data: { nomor_surat: nomor_surat },
                    success: function(response) {
                        if(response.success){
                            data=response.data;
                            bacaIsiSuratMasuk(data);   
                        }
                    },
                    error: function(xhr) {
                        console.log('Terjadi kesalahan:', xhr.responseText);
                        appShowNotification(false,['Terjadi kesalahan, surat tidak ditemukan']);
                    },
                    complete: function() {
                        isRequestInProgress = false;
                    }
                });
            }
        });

    });
</script>

@endsection
