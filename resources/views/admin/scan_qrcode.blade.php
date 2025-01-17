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
                                    <li><a href="{{ route('scan-qrcode') }}" id="btnRefresh" class="nav-link px-2 link-dark"><i class="align-middle" data-feather="refresh-cw"></i> Refresh</a></li>
                                </ul> 
                            </div>
                        </div>
                    </header>

                </div>
                <div class="card-body">
                    <div class="row"> 
                        <div class="col-sm-12">                    
                            <div style="width: 100%" id="reader"></div>
                        </div>
                        <div class="col-sm-12">                    
                            <div id="qr-reader-results">
                                <h2>Hasil QR Code:</h2>
                                <p id="output"></p>
                            </div>
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

<script>
    $(document).ready(function () {
        cekAkses('pengguna');

        const html5QrCode = new Html5Qrcode("reader");
        const qrCodeSuccessCallback = (decodedText, decodedResult) => {
            const qrData = JSON.parse(decodedText);
            $("#output").html(qrData.id+' '+qrData.api);


            html5QrCode.stop().then((ignore) => {
            // QR Code scanning is stopped.
            }).catch((err) => {
            // Stop failed, handle it.
            });            
        };
        const config = { fps: 10, qrbox: { width: 250, height: 250 } };

        // If you want to prefer front camera
        html5QrCode.start({ facingMode: "environment" }, config, qrCodeSuccessCallback);
    });
</script>

@endsection
