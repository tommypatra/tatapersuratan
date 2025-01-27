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

    setValues($id=null,$grup=null) {
        this.id = $id;
        this.grup = $grup;
    }

    uploadFile(file, fileName, callback) {
        const formData = new FormData();
        let endpoint;
        formData.append("file", file, fileName);
        if(this.grup=='surat-masuk'){
            formData.append("surat_masuk_id", this.id);
            endpoint='api/lampiran-surat-masuk';
        }else if(this.grup=='surat-keluar'){
            formData.append("surat_keluar_id", this.id);
            endpoint='api/lampiran-surat-keluar';
        }
        if(endpoint){
            $.ajax({
                url: endpoint,
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    // Panggil callback dengan respons dari server
                    callback({ success: true, message: 'berhasil terupload.' });
                },
                error: function (xhr, status, error) {
                    // Panggil callback dengan respons error
                    callback({ success: false, message: 'gagal terupload.' });
                }
            });
        }else
            callback({ success: false, message: 'gagal terupload.' });
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

    saveCroppedImage(callback) {
        if (this.cropper) {
            const croppedCanvas = this.cropper.getCroppedCanvas();
            croppedCanvas.toBlob((blob) => {
                const now = new Date();
                const formattedDate = 
                    now.getFullYear().toString().slice(2) + 
                    ('0' + (now.getMonth() + 1)).slice(-2) + 
                    ('0' + now.getDate()).slice(-2) + '-' +  
                    ('0' + now.getHours()).slice(-2) +       
                    ('0' + now.getMinutes()).slice(-2) +     
                    ('0' + now.getSeconds()).slice(-2);      
                
                const fileName = `${this.grup}-${formattedDate}.jpg`;                this.uploadFile(blob, fileName, (response) => {
                    if (response.success) {
                        console.log('Berhasil upload');
                        callback(true);
                    } else {
                        console.log('Gagal upload: ' + response.message);
                        callback(false);
                    }
                    this.resetToCamera();
                });                    
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
        // this.cropBtn.addEventListener('click', () => this.saveCroppedImage());
    }
}
