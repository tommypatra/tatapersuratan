  function sel2_datalokal(vselector,vdata=null,vAllowClear=true,vDropDownParent="",vTags=false){
    $(vselector).select2({
      data: vdata,
      dropdownAutoWidth: true,
      dropdownParent: vDropDownParent,
      placeholder: '-pilih-',
      allowClear: vAllowClear,
      tags:vTags,
    });
  }
  
  function sel2_jeniskelamin(vselector,vDropDownParent=""){
    $(vselector).select2({
      data:[{id:"",text:""},
            {id:"L",text:"Laki-Laki"}, 
            {id:"P",text:'Perempuan'}],
      dropdownAutoWidth: true,
      dropdownParent: vDropDownParent,
      placeholder: '-pilih-',
      allowClear: true,
    });
  }  

  function sel2_publish(vselector,vDropDownParent=""){
    $(vselector).select2({
      data:[{id:"",text:""},
            {id:"1",text:"Ya"}, 
            {id:"0",text:'Tidak'}],
      dropdownAutoWidth: true,
      allowClear: false,
      dropdownParent: vDropDownParent,
      placeholder: '-pilih-',
    });
  }
  
  function sel2_aktif(vselector,vDropDownParent=""){
    $(vselector).select2({
      data:[{id:"",text:""},
            {id:"y",text:"Ya"}, 
            {id:"n",text:'Tidak'}],
      dropdownAutoWidth: true,
      allowClear: false,
      dropdownParent: vDropDownParent,
      placeholder: '-pilih-',
    });
  }

  function sel2_tahun(vselector,vDropDownParent=""){
    let thn=new Date().getFullYear();
    let list_thn=[];
    let x=1;
    list_thn[0]={id:"",text:""};
    for(i=(thn);i>=(vTahunApp);i--){
      list_thn[x]={id:i,text:i};
      x++;
    }
    console.log(list_thn);
    $(vselector).select2({
      data:list_thn,
      dropdownParent: vDropDownParent,
      dropdownAutoWidth: true,
      placeholder: '-pilih-',
      allowClear: true,
    });
  }


function sel2_cariUser($vLength=3,$vElement='#select_user_id',vdropdownParent=''){
  $($vElement).select2({
      minimumInputLength: $vLength,
      dropdownAutoWidth: true,
      placeholder: 'Cari nama pegawai',
      dropdownParent:vdropdownParent,
      ajax: {
          url: '/api/get-users',
          type: 'GET',
          dataType: 'json',
          delay: 250,
          data: function (params) {
              return {
                  keyword: params.term,
              };
          },
          processResults: function (data) {
              return {
                  results: data.data.map(function (user) {
                      return {
                          id: user.id,
                          text: user.name
                      };
                  }),
              };
          },
          cache: true
      },
  });
}

function sel2_cariKlasifikasi($vLength=3,$vElement='#klasifikasi_surat_id',vdropdownParent=''){
  $($vElement).select2({
      minimumInputLength: $vLength,
      dropdownAutoWidth: true,
      placeholder: 'Cari klasifikasi',
      dropdownParent:vdropdownParent,
      ajax: {
          url: '/api/klasifikasi-surat-keluar',
          type: 'GET',
          dataType: 'json',
          delay: 250,
          data: function (params) {
              return {
                  keyword: params.term,
              };
          },
          processResults: function (data) {
              return {
                  results: data.data.map(function (user) {
                      return {
                          id: user.id,
                          text: user.kode+' '+user.klasifikasi
                      };
                  }),
              };
          },
          cache: true
      },
  });
}