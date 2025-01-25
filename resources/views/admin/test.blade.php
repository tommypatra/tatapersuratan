<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foto Dokumen A4</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #f4f4f4;
            padding: 20px;
        }

        #video {
            width: 100%;
            max-width: 400px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        #canvas {
            display: none;
        }

        #preview-container {
            display: none;
            margin-top: 20px;
        }

        #crop-image {
            max-width: 100%;
            height: auto;
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
    </style>
</head>
<body>

    <h2>Ambil Foto Dokumen</h2>
    <video id="video" autoplay playsinline></video>
    <button id="capture-btn">Ambil Foto</button>
    
    <div id="preview-container">
        <img id="crop-image">
        <button id="crop-btn">Simpan Gambar</button>
    </div>

    <canvas id="canvas"></canvas>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

    <script>
        let cropper;

        async function startCamera() {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({
                    video: {
                        facingMode: 'environment', // Gunakan kamera belakang
                        width: { ideal: 4096 }, // Resolusi tinggi
                        height: { ideal: 2160 }
                    }
                });

                const video = document.getElementById('video');
                video.srcObject = stream;
            } catch (error) {
                console.error('Gagal mengakses kamera:', error);
                alert('Tidak dapat mengakses kamera. Pastikan izin sudah diberikan.');
            }
        }

        document.getElementById('capture-btn').addEventListener('click', function() {
            const video = document.getElementById('video');
            const canvas = document.getElementById('canvas');
            const context = canvas.getContext('2d');

            // Set ukuran canvas sesuai dengan video untuk kualitas maksimal
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            context.drawImage(video, 0, 0, canvas.width, canvas.height);

            // Tampilkan gambar di preview
            const imageDataURL = canvas.toDataURL('image/jpeg', 1.0);  // Kualitas gambar max
            document.getElementById('crop-image').src = imageDataURL;
            document.getElementById('preview-container').style.display = 'block';

            // Inisialisasi cropper
            if (cropper) {
                cropper.destroy(); // Hapus jika sebelumnya sudah ada
            }
            cropper = new Cropper(document.getElementById('crop-image'), {
                aspectRatio: 210 / 297,  // Rasio A4 (210x297 mm)
                viewMode: 2,  // Fit ke area crop
                autoCropArea: 0.8,  // Otomatis crop area 80% dari gambar
                movable: true,
                zoomable: true,
                scalable: true,
                rotatable: false
            });
        });

        document.getElementById('crop-btn').addEventListener('click', function() {
            const croppedCanvas = cropper.getCroppedCanvas();

            croppedCanvas.toBlob((blob) => {
                const url = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'foto-dokumen.jpg';
                a.click();
                URL.revokeObjectURL(url);
            }, 'image/jpeg', 1.0);
        });

        // Mulai kamera saat halaman dimuat
        startCamera();
    </script>

</body>
</html>
