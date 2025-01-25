class FotoDokumen {
    constructor(videoElementId, previewContainerId, captureBtnId, cropBtnId) {
        this.videoElement = document.getElementById(videoElementId);
        this.previewContainer = document.getElementById(previewContainerId);
        this.captureBtn = document.getElementById(captureBtnId);
        this.cropBtn = document.getElementById(cropBtnId);
        this.canvas = document.createElement('canvas');
        this.cropper = null;
    }

    async startCamera() {
        try {
            const stream = await navigator.mediaDevices.getUserMedia({
                video: {
                    facingMode: 'environment', // Pakai kamera belakang
                    width: { ideal: 1280 },  // Resolusi tinggi
                    height: { ideal: 720 }
                }
            });
            this.videoElement.srcObject = stream;
        } catch (error) {
            console.error('Gagal mengakses kamera:', error);
            alert('Tidak dapat mengakses kamera. Pastikan izin sudah diberikan.');
        }
    }

    capturePhoto() {
        // Hentikan video saat pengambilan foto
        this.videoElement.pause();
    
        // Menonaktifkan tombol ambil foto sementara
        this.captureBtn.disabled = true;
        
        // Ambil foto setelah video dipause
        const context = this.canvas.getContext('2d');
        this.canvas.width = this.videoElement.videoWidth;
        this.canvas.height = this.videoElement.videoHeight;
        context.drawImage(this.videoElement, 0, 0, this.canvas.width, this.canvas.height);
    
        // Konversi ke gambar
        const imageDataURL = this.canvas.toDataURL('image/jpeg', 0.5);
        const previewImage = document.createElement('img');
        previewImage.id = 'crop-image';
        previewImage.src = imageDataURL;
    
        // Sembunyikan video dan tampilkan crop area saja
        this.videoElement.style.display = 'none';
        this.captureBtn.style.display = 'none';
        this.previewContainer.innerHTML = '';  // Bersihkan preview sebelumnya
        this.previewContainer.appendChild(previewImage);
        this.previewContainer.style.display = 'block';
    
        // Jika cropper sudah ada, pastikan untuk mengganti gambar
        if (this.cropper) {
            this.cropper.destroy();  // Hancurkan cropper lama
        }
    
        // Gunakan replace() untuk mengganti gambar di dalam cropper
        this.cropper = new Cropper(previewImage, {
            viewMode: 2,
            autoCropArea: 0.8,
            movable: true,
            zoomable: true,
            scalable: true,
            aspectRatio: NaN,  // Bebas memilih crop area
        });
    
        this.cropBtn.style.display = 'inline-block';
    
        // Aktifkan kembali tombol ambil foto setelah crop selesai
        this.captureBtn.disabled = false;
    }
    

    saveCroppedImage() {
        if (this.cropper) {
            const croppedCanvas = this.cropper.getCroppedCanvas();
            croppedCanvas.toBlob((blob) => {
                const url = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'foto-dokumen.jpg';
                a.click();
                URL.revokeObjectURL(url);
    
                this.resetToCamera();
            }, 'image/jpeg', 0.5);  
        }
    }

    resetToCamera() {
        // Tampilkan kembali video dan tombol capture, sembunyikan crop area
        this.videoElement.style.display = 'block';
        this.captureBtn.style.display = 'inline-block';
        this.previewContainer.style.display = 'none';
        this.cropBtn.style.display = 'none';
    }

    init() {
        this.startCamera();
        this.captureBtn.addEventListener('click', () => this.capturePhoto());
        this.cropBtn.addEventListener('click', () => this.saveCroppedImage());
    }
}
