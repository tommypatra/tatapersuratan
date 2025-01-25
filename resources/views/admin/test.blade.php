<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ambil Foto Dokumen</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
    <style>
        body {
            text-align: center;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }
        #video {
            width: 100%;
            max-width: 400px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }
        #preview-container {
            width: 100%;
            height: 60vh;  /* 60% dari tinggi layar */
            max-height: 100vh;  /* Maksimum sesuai tinggi layar */
            overflow: hidden;  /* Menyembunyikan bagian yang berlebih */
            position: relative;  /* Agar crop area bisa diposisikan relatif */
        }

        #preview-container img {
            width: 100%;  /* Gambar menyesuaikan lebar container */
            height: auto;  /* Menjaga rasio gambar */
        }
        #capture-btn, #crop-btn {
            margin-top: 20px;
            padding: 10px 20px;
            font-size: 18px;
            border: none;
            border-radius: 5px;
            background-color: #28a745;
            color: white;
            cursor: pointer;
        }
        #crop-btn {
            background-color: #007bff;
        }
    </style>
</head>
<body>

    <h2>Ambil Foto Dokumen</h2>
    
    <div id="area-video">
        <video id="video" autoplay playsinline></video>
        <button id="capture-btn">Ambil Foto</button>
    </div>

    <div id="preview-container"></div>
    <button id="crop-btn" style="display:none;">Simpan Gambar</button>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/compress.js"></script>
<script src="{{ url('js/foto-dokumen.js')}}"></script>

    <script>
        // Inisialisasi dan gunakan library
        const fotoDokumen = new FotoDokumen('video', 'preview-container', 'capture-btn', 'crop-btn','area-video');
        fotoDokumen.init();
    </script>

</body>
</html>
