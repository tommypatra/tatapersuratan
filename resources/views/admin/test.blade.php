<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foto Dokumen A4</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            text-align: center;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

        #video-container {
            position: relative;
            display: inline-block;
            width: 100%;
            max-width: 400px; /* Ukuran maksimal tampilan kamera */
        }

        #video {
            width: 100%;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        /* Overlay untuk area crop A4 portrait */
        .overlay {
            position: absolute;
            width: 70%;  /* Sesuaikan proporsi dengan layar */
            height: 90%;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            border: 2px dashed red;
            pointer-events: none;
            border-radius: 5px;
        }

        #capture-btn {
            margin-top: 20px;
            padding: 10px 20px;
            font-size: 18px;
            border: none;
            border-radius: 5px;
            background-color: #28a745;
            color: white;
            cursor: pointer;
        }

        #canvas {
            display: none;
        }
    </style>
</head>
<body>

    <div id="video-container">
        <video id="video" autoplay playsinline></video>
        <div class="overlay"></div>
    </div>

    <button id="capture-btn">Ambil Foto</button>
    <canvas id="canvas"></canvas>

    <script>
        async function startCamera() {
            try {
                // Akses kamera belakang dengan resolusi tinggi
                const stream = await navigator.mediaDevices.getUserMedia({
                    video: {
                        facingMode: 'environment',  // Gunakan kamera belakang
                        width: { ideal: 1920 },
                        height: { ideal: 1080 }
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

            // Sesuaikan ukuran canvas sesuai dengan ukuran video untuk kualitas maksimal
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            context.drawImage(video, 0, 0, canvas.width, canvas.height);

            // Hitung area crop sesuai dengan overlay (portrait A4)
            const cropX = canvas.width * 0.15; // 15% dari kiri
            const cropY = canvas.height * 0.05; // 5% dari atas
            const cropWidth = canvas.width * 0.70;  // 70% lebar
            const cropHeight = canvas.height * 0.90; // 90% tinggi

            // Potong sesuai area overlay A4
            const croppedImage = context.getImageData(cropX, cropY, cropWidth, cropHeight);

            // Buat canvas baru untuk gambar hasil crop
            const croppedCanvas = document.createElement('canvas');
            croppedCanvas.width = cropWidth;
            croppedCanvas.height = cropHeight;
            croppedCanvas.getContext('2d').putImageData(croppedImage, 0, 0);

            // Konversi hasil cropping ke gambar dan download otomatis
            croppedCanvas.toBlob((blob) => {
                const url = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'foto-dokumen.jpg';
                a.click();
                URL.revokeObjectURL(url);
            }, 'image/jpeg', 1.0);  // Simpan dalam kualitas tinggi (JPEG 1.0)
        });

        // Jalankan kamera saat halaman dimuat
        startCamera();
    </script>

</body>
</html>
