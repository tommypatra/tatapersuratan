var InfoModule = (function() {

    function infoDisposisi(callback){
        $.get("/api/info-tujuan-disposisi", function (response) {
            let data_notif_tujuan ='';
            $(".jumlah_tujuan_belum_diakses").html(response.jumlah_tujuan_belum_diakses);
            jQuery.each(response.data, function (i, val) {
                data_notif_tujuan +=`									
                    <a href="/disposisi-detail/${val.surat_masuk.id}" class="list-group-item" id="baca-disposisi">
                        <div class="row g-0 align-items-center">
                            <div class="col-2">
                                <i class="fa-regular fa-envelope fa-2xl"></i>
                            </div>
                            <div class="col-10">
                                <div class="text-dark">${val.surat_masuk.perihal}</div>
                                <div class="text-muted small mt-1">${val.surat_masuk.asal} (${val.surat_masuk.tempat})</div>
                                <div class="text-muted small mt-1">${val.surat_masuk.no_surat}</div>
                                <div class="text-muted small mt-1"><i class="fa-regular fa-clock"></i> ${waktuLalu(val.created_at)}</div>
                            </div>
                        </div>
                    </a>
                `;
            });
            $("#data_notif_tujuan").html(data_notif_tujuan);
            callback(response);
        });
    }

    function infoDistribusi(callback){
        $.get("/api/info-distribusi", function (response) {
            let data_notif_distribusi ='';
            $(".jumlah_distribusi_belum_diakses").html(response.jumlah_distribusi_belum_diakses);
            jQuery.each(response.data, function (i, val) {
                data_notif_distribusi +=`									
                    <a href="/surat-keluar-detail/${val.surat_keluar.id}" class="list-group-item" id="baca-disposisi">
                        <div class="row g-0 align-items-center">
                            <div class="col-2">
                                <i class="fa-regular fa-envelope fa-2xl"></i>
                            </div>
                            <div class="col-10">
                                <div class="text-dark">${val.surat_keluar.perihal}</div>
                                <div class="text-muted small mt-1">${val.surat_keluar.asal}</div>
                                <div class="text-muted small mt-1">${val.surat_keluar.no_surat}</div>
                                <div class="text-muted small mt-1"><i class="fa-regular fa-clock"></i> ${waktuLalu(val.created_at)}</div>
                            </div>
                        </div>
                    </a>
                `;
            });
            $("#data_notif_distribusi").html(data_notif_distribusi);
            callback(response);
        });
    }
    
    function infoGeneral(callback){
        $.get("/api/info-general", function (response) {
            if(response.success){
                let surat_masuk=response.data.surat_masuk.total;
                $('#konsep-masuk').text(surat_masuk.konsep);
                $('#diajukan-masuk').text(surat_masuk.diajukan);
                $('#diterima-masuk').text(surat_masuk.diterima);
                $('#ditolak-masuk').text(surat_masuk.ditolak);

                let surat_keluar=response.data.surat_keluar.total;
                $('#konsep-keluar').text(surat_keluar.konsep);
                $('#diajukan-keluar').text(surat_keluar.diajukan);
                $('#diterima-keluar').text(surat_keluar.diterima);
                $('#ditolak-keluar').text(surat_keluar.ditolak);

                let ttd=response.data.ttd.total;
                $('#konsep-ttd').text(ttd.konsep);
                $('#diajukan-ttd').text(ttd.diajukan);
                $('#diterima-ttd').text(ttd.diterima);
                $('#ditolak-ttd').text(ttd.ditolak);
            }
            callback(response);            
        });
    }

    function updateNotifWeb(){
        infoDistribusi(function(response) {});
        infoDisposisi(function(response) {});    
    }

    return {
        infoGeneral:infoGeneral,
        infoDistribusi:infoDistribusi,
        infoDisposisi:infoDisposisi,
        updateNotifWeb:updateNotifWeb,
    };
})();
