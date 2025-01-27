<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ambil Foto Dokumen</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
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
</head>
<body>

    <div class="container text-center mt-5">
        <h2>Ambil Foto Dokumen</h2>
        <button class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#fotoModal">
            Buka Kamera
        </button>
    </div>

    <!-- Modal Bootstrap -->
    <div class="modal fade" id="fotoModal" tabindex="-1" aria-labelledby="fotoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="fotoModalLabel">Ambil Foto Dokumen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <div id="area-video" class="mb-3">
                        <video id="video" autoplay playsinline class="img-fluid"></video>
                    </div>
                    <div id="preview-container" class="mb-3"></div>
                </div>
                <div class="modal-footer">
                    <button id="capture-btn" class="btn btn-success">Ambil Foto</button>
                    <button id="crop-btn" class="btn btn-primary" style="display:none;">Simpan Gambar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap, jQuery, dan CropperJS -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ url('js/foto-dokumen.js')}}"></script>

    <script>
        $(document).ready(function() {
            const fotoDokumen = new FotoDokumen('video', 'preview-container', 'capture-btn', 'crop-btn');

            // Inisialisasi kamera saat modal dibuka
            $('#fotoModal').on('shown.bs.modal', function () {
                fotoDokumen.init();
                fotoDokumen.startCamera();
            });

            // Hentikan kamera saat modal ditutup
            $('#fotoModal').on('hidden.bs.modal', function () {
                fotoDokumen.stopCamera();
                fotoDokumen.resetToCamera();
            });
        });
    </script>

</body>
</html>
