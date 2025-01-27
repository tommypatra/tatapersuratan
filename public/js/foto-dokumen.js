class FotoDokumen {
    constructor(videoElementId, previewContainerId, captureBtnId, cropBtnId) {
        this.videoElement = document.getElementById(videoElementId);
        this.previewContainer = document.getElementById(previewContainerId);
        this.captureBtn = document.getElementById(captureBtnId);
        this.cropBtn = document.getElementById(cropBtnId);
        this.canvas = document.createElement('canvas');
        this.cropper = null;
        this.id = null;
        this.grup = null;
        this.stream = null;
    }

    capturePhoto($id=null,$grup=null) {
        this.id = $id;
        this.grup = $grup;
    }

    async startCamera() {
        try {
            this.stream = await navigator.mediaDevices.getUserMedia({
                video: {
                    facingMode: 'environment',
                    width: { ideal: 4096 },
                    height: { ideal: 2160 }
                }
            });
            this.videoElement.srcObject = this.stream;
        } catch (error) {
            console.error('Gagal mengakses kamera:', error);
            alert('Tidak dapat mengakses kamera. Pastikan izin sudah diberikan.');
        }
    }

    stopCamera() {
        if (this.stream) {
            this.stream.getTracks().forEach(track => track.stop());
            this.videoElement.srcObject = null;
        }
    }

    capturePhoto() {
        this.videoElement.style.display = 'none';
        this.captureBtn.style.display = 'none';
        this.previewContainer.innerHTML = 'on proses';

        this.videoElement.pause();
    
        this.captureBtn.disabled = true;
        
        const context = this.canvas.getContext('2d');
        this.canvas.width = this.videoElement.videoWidth;
        this.canvas.height = this.videoElement.videoHeight;
        context.drawImage(this.videoElement, 0, 0, this.canvas.width, this.canvas.height);
    
        const imageDataURL = this.canvas.toDataURL('image/jpeg', 1.0);
        const previewImage = document.createElement('img');
        previewImage.id = 'crop-image';
        previewImage.src = imageDataURL;
    
        this.videoElement.style.display = 'none';
        this.captureBtn.style.display = 'none';
        this.previewContainer.innerHTML = '';
        this.previewContainer.appendChild(previewImage);
        this.previewContainer.style.display = 'block';
    
        if (this.cropper) {
            this.cropper.destroy();
        }
    
        this.cropper = new Cropper(previewImage, {
            viewMode: 2,
            autoCropArea: 0.8,
            movable: true,
            zoomable: true,
            scalable: true,
            aspectRatio: NaN,
        });
    
        this.cropBtn.style.display = 'inline-block';
    
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
            }, 'image/jpeg', 1.0);  
        }
    }

    resetToCamera() {
        this.videoElement.style.display = 'block';
        this.captureBtn.style.display = 'inline-block';
        this.previewContainer.style.display = 'none';
        this.cropBtn.style.display = 'none';
    }

    init() {
        this.captureBtn.addEventListener('click', () => this.capturePhoto());
        this.cropBtn.addEventListener('click', () => this.saveCroppedImage());
    }
}
