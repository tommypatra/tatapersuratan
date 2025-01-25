<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foto Dokumen</title>
    <style>
        #video {
            width: 100%;
            max-width: 800px;
            border: 2px solid #000;
            position: relative;
        }

        /* Area crop A4 overlay */
        .overlay {
            position: absolute;
            width: 70%;
            height: 100%;
            border: 2px dashed red;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            pointer-events: none;
        }

        #canvas {
            display: none;
        }

        #capture-btn {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <video id="video" autoplay playsinline></video>
    <div class="overlay"></div>
    <button id="capture-btn">Ambil Foto</button>
    <canvas id="canvas"></canvas>

    <script>
        async function startCamera() {
            try {
                // Akses kamera belakang dengan resolusi tinggi
                const stream = await navigator.mediaDevices.getUserMedia({
                    video: {
                        facingMode: 'environment', // Kamera belakang
                        width: { ideal: 1920 },   // Resolusi tinggi
                        height: { ideal: 1080 }   // Resolusi tinggi
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

            // Atur ukuran canvas sesuai video untuk kualitas maksimal
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            
            // Gambar video ke canvas
            context.drawImage(video, 0, 0, canvas.width, canvas.height);

            // Ambil area A4 dalam video (70% lebar, penuh tinggi)
            const cropX = canvas.width * 0.15; // Ambil area 70% di tengah
            const cropY = 0;
            const cropWidth = canvas.width * 0.70;
            const cropHeight = canvas.height;

            // Potong sesuai area overlay A4
            const croppedImage = context.getImageData(cropX, cropY, cropWidth, cropHeight);

            // Buat canvas baru untuk gambar hasil crop
            const croppedCanvas = document.createElement('canvas');
            croppedCanvas.width = cropWidth;
            croppedCanvas.height = cropHeight;
            croppedCanvas.getContext('2d').putImageData(croppedImage, 0, 0);

            // Konversi hasil cropping ke gambar dan download
            croppedCanvas.toBlob((blob) => {
                const url = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'foto-dokumen.jpg';
                a.click();
                URL.revokeObjectURL(url);
            }, 'image/jpeg', 1.0);  // Simpan dalam format JPEG dengan kualitas tinggi (1.0)
        });

        // Mulai kamera saat halaman dimuat
        startCamera();
    </script>
</body>
</html>
