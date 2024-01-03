function appShowNotification(vStatus, vPesan) {
    let vIcon = "success";
    let vTitle = "Berhasil";
    if (!vStatus) {
        vIcon = "error";
        vTitle = "Terjadi Kesalahan...";
    }
    let pesan = "";
    $.each(vPesan, function (key, value) {
        pesan += value;
        if (key + 1 < vPesan.length)
        pesan += ",";
        pesan += "<br>";
    });

    Swal.fire({
        icon: vIcon,
        title: vTitle,
        html: pesan,
    })
}

function appPilihAkses(hakakses){
    let pilih='<ul>';	
    let link;
    jQuery.each(hakakses, function(index, item) {
        link = "{{ route('akun-set-akses', ['grup_id' => ':grup_id']) }}";
        link = link.replace(':grup_id', item.grup_id);
        pilih+='<li><a href="'+link+'">'+item.grup.grup+'</a></li>';
    });			
    pilih+='</ul>';	
    return pilih;
}

function convertTimestamp(utcTimestamp) {
    var utcDate = new Date(utcTimestamp);
    var formattedDate = utcDate.toISOString().slice(0, 19).replace('T', ' ');
    return formattedDate;
}

var my_date_format = function (input) {
    var d = new Date(Date.parse(input.replace(/-/g, "/")));
    var month = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 
    'Nov', 'Dec'];
    var date = d.getDay().toString() + " " + month[d.getMonth().toString()] + ", " + 
    d.getFullYear().toString();
    return (date);
}; 

function waktuLalu(timestamp,skrng=null,lbl='lalu') {
    var waktu = "";
    if (timestamp) {
        var phpDate = new Date(timestamp.replace(/-/g, '/')); // Convert MySQL timestamp string to Date object
        
        if(!skrng)
            skrng = Date.now();
        else
            skrng = new Date(skrng.replace(/-/g, '/'));

        var selisih = Math.floor((skrng - phpDate) / 1000);
        var detik = selisih;
        var menit = Math.round(selisih / 60);
        var jam = Math.round(selisih / 3600);
        var hari = Math.round(selisih / 86400);
        var minggu = Math.round(selisih / 604800);
        var bulan = Math.round(selisih / 2419200);
        var tahun = Math.round(selisih / 29030400);

        if (detik <= 60) {
            waktu = detik + ' detik ';
        } else if (menit <= 60) {
            waktu = menit + ' menit ';
        } else if (jam <= 24) {
            waktu = jam + ' jam ';
        } else if (hari <= 7) {
            waktu = hari + ' hari ';
        } else if (minggu <= 4) {
            waktu = minggu + ' minggu ';
        } else if (bulan <= 12) {
            waktu = bulan + ' bulan ';
        } else {
            waktu = tahun + ' tahun ';
        }
    }
    return waktu+lbl;
}

function is_image(fileType) {
    return fileType.startsWith('image/');
}

function cekNilaiArray(dataArray, targetUserId, colName) {
    return dataArray.some(item => item[colName] === targetUserId);
}   

function cariArray(dataArray, targetUserId, colName) {
    return dataArray.filter(item => item[colName] === targetUserId);

}   

function getCurrentDateTime() {
    const now = new Date();
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, '0');
    const day = String(now.getDate()).padStart(2, '0');
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const seconds = String(now.getSeconds()).padStart(2, '0');
    const formattedDateTime = `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
    return formattedDateTime;
}

function disableForm(disabled=true,formEl='#myForm'){
    $(formEl + ' input, ' + formEl + ' select, ' + formEl + ' textarea').prop('readonly', disabled);
    $(formEl + ' input[type="submit"], ' + formEl + ' button').prop('disabled', disabled);
}

function isUserInDisposisi(tujuanArray, userId) {
    for (let i = 0; i < tujuanArray.length; i++) {
        const tujuan = tujuanArray[i];
        if (tujuan.disposisi && tujuan.disposisi.user_id == userId) {
            return true;
        }
    }
    return false;
}    

function showHideModal(el,status=true){
    if(status){
        let myModalForm = new bootstrap.Modal(document.getElementById(el), {
            backdrop: 'static',
            keyboard: false,
        });
        myModalForm.toggle();
    }else{
        const cmodal = document.querySelector('#'+el);
        const modal = bootstrap.Modal.getInstance(cmodal);    
        modal.hide();      
    }
}
