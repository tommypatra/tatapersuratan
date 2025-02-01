@extends('admin.template')

@section('scriptHead')
<title>Dashboard Web</title>
<script src='{{ asset("js/fullcalendar/dist/index.global.js") }}'></script>
@endsection

@section('container')
<div class="container-fluid p-0">
    <h1 class="h3 mb-3">Dashboard Web</h1>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Dashboard Web</h5>
                </div>
                <div class="card-body">
					<div class="row">

                        <div class="col-xl-6 col-xxl-5 d-flex">
                            <div class="w-100">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col mt-0">
                                                        <h5 class="card-title">Konsep</h5>
                                                    </div>
                                                    <div class="col-auto">
                                                        <div class="stat text-primary">
                                                            <i class="align-middle" data-feather="archive"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <h1 class="mt-1 mb-3" id="dashboard-konsep-total">0</h1>
                                                <div class="mb-0">
                                                    <i class="mdi mdi-arrow-bottom-right"></i> <span class="badge bg-primary" id="dashboard-konsep-masuk">0</span> 
                                                    <span class="text-muted">Surat Masuk</span>
                                                </div>
                                                <div class="mb-0">
                                                    <i class="mdi mdi-arrow-bottom-right"></i> <span class="badge bg-success" id="dashboard-konsep-keluar">0</span> 
                                                    <span class="text-muted">Surat Keluar</span>
                                                </div>
                                                <div class="mb-0">
                                                    <i class="mdi mdi-arrow-bottom-right"></i> <span class="badge bg-danger" id="dashboard-konsep-ttd">0</span>
                                                        <span class="text-muted">TTD QrCode</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col mt-0">
                                                        <h5 class="card-title">Diajukan</h5>
                                                    </div>

                                                    <div class="col-auto">
                                                        <div class="stat text-primary">
                                                            <i class="align-middle" data-feather="arrow-right-circle"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <h1 class="mt-1 mb-3" id="dashboard-diajukan-total">0</h1>
                                                <div class="mb-0">
                                                    <i class="mdi mdi-arrow-bottom-right"></i> <span class="badge bg-primary" id="dashboard-diajukan-masuk">0</span> 
                                                    <span class="text-muted">Surat Masuk</span>
                                                </div>
                                                <div class="mb-0">
                                                    <i class="mdi mdi-arrow-bottom-right"></i> <span class="badge bg-success" id="dashboard-diajukan-keluar">0</span> 
                                                    <span class="text-muted">Surat Keluar</span>
                                                </div>
                                                <div class="mb-0">
                                                    <i class="mdi mdi-arrow-bottom-right"></i> <span class="badge bg-danger" id="dashboard-diajukan-ttd">0</span>
                                                        <span class="text-muted">TTD QrCode</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col mt-0">
                                                        <h5 class="card-title">Diterima</h5>
                                                    </div>

                                                    <div class="col-auto">
                                                        <div class="stat text-primary">
                                                            <i class="align-middle" data-feather="check-square"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <h1 class="mt-1 mb-3" id="dashboard-diterima-total">0</h1>
                                                <div class="mb-0">
                                                    <i class="mdi mdi-arrow-bottom-right"></i> <span class="badge bg-primary" id="dashboard-diterima-masuk">0</span> 
                                                    <span class="text-muted">Surat Masuk</span>
                                                </div>
                                                <div class="mb-0">
                                                    <i class="mdi mdi-arrow-bottom-right"></i> <span class="badge bg-success" id="dashboard-diterima-keluar">0</span> 
                                                    <span class="text-muted">Surat Keluar</span>
                                                </div>
                                                <div class="mb-0">
                                                    <i class="mdi mdi-arrow-bottom-right"></i> <span class="badge bg-danger" id="dashboard-diterima-ttd">0</span>
                                                        <span class="text-muted">TTD QrCode</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col mt-0">
                                                        <h5 class="card-title">Ditolak</h5>
                                                    </div>

                                                    <div class="col-auto">
                                                        <div class="stat text-primary">
                                                            <i class="align-middle" data-feather="x-octagon"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <h1 class="mt-1 mb-3" id="dashboard-ditolak-total">0</h1>
                                                <div class="mb-0">
                                                    <i class="mdi mdi-arrow-bottom-right"></i> <span class="badge bg-primary" id="dashboard-ditolak-masuk">0</span> 
                                                    <span class="text-muted">Surat Masuk</span>
                                                </div>
                                                <div class="mb-0">
                                                    <i class="mdi mdi-arrow-bottom-right"></i> <span class="badge bg-success" id="dashboard-ditolak-keluar">0</span> 
                                                    <span class="text-muted">Surat Keluar</span>
                                                </div>
                                                <div class="mb-0">
                                                    <i class="mdi mdi-arrow-bottom-right"></i> <span class="badge bg-danger" id="dashboard-ditolak-ttd">0</span>
                                                        <span class="text-muted">TTD QrCode</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>   
                                        
                        <div class="col-xl-6 col-xxl-7">
							<div class="card flex-fill w-100">
								<div class="card-header">

									<h5 class="card-title mb-0">Aktifitas Persuratan</h5>
								</div>
								<div class="card-body py-3">
									<div class="chart chart-sm">
										<canvas id="aktifitas-dashboard-line"></canvas>
									</div>
								</div>
							</div>
						</div>
					</div>

                    <div class="row">
						<div class="col-12 col-lg-8 col-xxl-9 order-1 d-flex">
							<div class="card flex-fill">
								<div class="card-header">

									<h5 class="card-title mb-0">Data Aktifitas Persuratan</h5>
								</div>
								<div class="card-body d-flex">
									<div class="align-self-center w-100 table-responsive">
										<table class="table table-hover my-0" id="aktifitas-persuratan">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Jenis</th>
                                                    <th>Tanggal</th>
                                                    <th>Nomor/ Perihal</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
									</div>
								</div>
							</div>
						</div>

						<div class="col-12 col-lg-4 col-xxl-3 order-2 d-flex">
							<div class="card flex-fill w-100">
								<div class="card-header">
									<h5 class="card-title mb-0">Aktifitas Bulan Ini</h5>
								</div>
								<div class="card-body d-flex">
									<div class="align-self-center w-100">
										<div class="py-3">
											<div class="chart chart-xs">
												<canvas id="aktifitas-dashboard-pie"></canvas>
											</div>
										</div>

										<table class="table mb-0">
											<tbody>
												<tr>
													<td>Surat Masuk</td>
													<td class="text-end" id="bulanan-surat-masuk">0</td>
												</tr>
												<tr>
													<td>Surat Keluar</td>
													<td class="text-end" id="bulanan-surat-keluar">0</td>
												</tr>
												<tr>
													<td>TTD QrCode</td>
													<td class="text-end" id="bulanan-ttd-qrcode">0</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>

                        <div class="col-12 col-md-12 col-xxl-6 d-flex order-3 d-flex">
							<div class="card flex-fill w-100">
								<div class="card-header">

									<h5 class="card-title mb-0">Kalender Aktifitas</h5>
								</div>
								<div class="card-body px-4">
                                    <div class="mt-3" id="calendar"></div>
								</div>
							</div>
						</div>
					</div>


                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scriptJs')

<script type="text/javascript">

    var tahun="{{ date('Y') }}"; 
    var bulan="{{ date('m') }}";
    var dataMasuk;
    var dataKeluar;
    var dataTtd;

    var grafikMasuk=[];
    var grafikTtd=[];
    var grafikKeluar=[];


    InfoModule.infoDistribusi(function(response) {});
    InfoModule.infoDisposisi(function(response) {});    
    InfoModule.infoGeneral(function(response) {
        let masuk=response.data.surat_masuk.total;
        let ttd=response.data.ttd.total;
        let keluar=response.data.surat_keluar.total;

        grafikMasuk=response.data.surat_masuk.grafik;
        grafikTtd=response.data.ttd.grafik;
        grafikKeluar=response.data.surat_keluar.grafik;

        createChart(grafikMasuk, grafikTtd, grafikKeluar);

        $('#dashboard-konsep-masuk').html(masuk.konsep);
        $('#dashboard-konsep-keluar').html(keluar.konsep);
        $('#dashboard-konsep-ttd').html(ttd.konsep);
        $('#dashboard-konsep-total').html(masuk.konsep+keluar.konsep+ttd.konsep);

        $('#dashboard-diterima-masuk').html(masuk.diterima);
        $('#dashboard-diterima-keluar').html(keluar.diterima);
        $('#dashboard-diterima-ttd').html(ttd.diterima);
        $('#dashboard-diterima-total').html(masuk.diterima+keluar.diterima+ttd.diterima);

        $('#dashboard-diajukan-masuk').html(masuk.diajukan);
        $('#dashboard-diajukan-keluar').html(keluar.diajukan);
        $('#dashboard-diajukan-ttd').html(ttd.diajukan);
        $('#dashboard-diajukan-total').html(masuk.diajukan+keluar.diajukan+ttd.diajukan);

        $('#dashboard-ditolak-masuk').html(masuk.ditolak);
        $('#dashboard-ditolak-keluar').html(keluar.ditolak);
        $('#dashboard-ditolak-ttd').html(ttd.ditolak);
        $('#dashboard-ditolak-total').html(masuk.ditolak+keluar.ditolak+ttd.ditolak);
    });

    function createChart(grafikMasuk, grafikTtd, grafikKeluar) {
        var ctx = document.getElementById("aktifitas-dashboard-line").getContext("2d");
        var gradient = ctx.createLinearGradient(0, 0, 0, 225);
        gradient.addColorStop(0, "rgba(215, 227, 244, 1)");
        gradient.addColorStop(1, "rgba(215, 227, 244, 0)");

        var masukColor = "rgba(0, 0, 255, 1)";
        var keluarColor = "rgba(0, 255, 0, 1)";
        var ttdColor = "rgba(255, 0, 0, 1)";

        new Chart(document.getElementById("aktifitas-dashboard-line"), {
            type: "line",
            data: {
                labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                datasets: [
                    {
                        label: "Surat Masuk",
                        fill: true,
                        backgroundColor: gradient,
                        borderColor: masukColor,
                        data: grafikMasuk
                    },
                    {
                        label: "Surat Keluar",
                        fill: true,
                        backgroundColor: gradient,
                        borderColor: keluarColor,
                        data: grafikKeluar
                    },
                    {
                        label: "TTD QrCode",
                        fill: true,
                        backgroundColor: gradient,
                        borderColor: ttdColor,
                        data: grafikTtd
                    }
                ]
            },
            options: {
                maintainAspectRatio: false,
                legend: {
                    display: false
                },
                tooltips: {
                    intersect: false
                },
                hover: {
                    intersect: true
                },
                plugins: {
                    filler: {
                        propagate: false
                    }
                },
                scales: {
                    xAxes: [{
                        reverse: true,
                        gridLines: {
                            color: "rgba(0,0,0,0.0)"
                        }
                    }],
                    yAxes: [{
                        ticks: {
                            stepSize: 1000
                        },
                        display: true,
                        borderDash: [3, 3],
                        gridLines: {
                            color: "rgba(0,0,0,0.0)"
                        }
                    }]
                }
            }
        });
    }

    function createPie(dataSet){

        $('#bulanan-surat-masuk').html(dataSet[0]);
        $('#bulanan-surat-keluar').html(dataSet[1]);
        $('#bulanan-ttd-qrcode').html(dataSet[2]);

        new Chart(document.getElementById("aktifitas-dashboard-pie"), {
            type: "pie",
            data: {
                labels: ["Surat Masuk", "Surat Keluar", "TTD QrCode"],
                datasets: [{
                    data: dataSet,
                    backgroundColor: [
                        window.theme.primary,
                        window.theme.success,
                        window.theme.danger
                    ],
                    borderWidth: 5
                }]
            },
            options: {
                responsive: !window.MSInputMethodContext,
                maintainAspectRatio: false,
                legend: {
                    display: false
                },
                cutoutPercentage: 75
            }
        });
    }

    var nomor=1;
    var dtBase = [];
    var dtEvents = [];
    var dtTabel = [];

    function generateLabel(is_diajukan,is_diterima){
        let status = {
            'label':'<span class="badge bg-warning">Belum Diajukan</span>',
            'text':'Belum Diajukan'
        }
        if(is_diajukan){
            if(!is_diterima)
                status = {
                    'label':'<span class="badge bg-primary">Menunggu Verifikasi</span>',
                    'text':'Menunggu Verifikasi'
                }
            else{
                if(is_diterima)
                    status = {
                        'label':'<span class="badge bg-success">Diterima</span>',
                        'text':'Menunggu Verifikasi'
                    }                
                else
                    status = {
                        'label':'<span class="badge bg-danger">Ditolak</span>',
                        'text':'Menunggu Verifikasi'
                    }
            }
        }
        return status;
    }


    async function getData(tahun,bulan){
        const dataMasuk = await $.ajax({
            url: '/api/surat-masuk?page=all&filter={"bulan":'+bulan+',"tahun":'+tahun+'}',
            type: 'get',
            dataType: 'json',
        });
        const dataKeluar = await $.ajax({
            url: '/api/surat-keluar?page=all&filter={"bulan":'+bulan+',"tahun":'+tahun+'}',
            type: 'get',
            dataType: 'json',
        });
        const dataTtd = await $.ajax({
            url: '/api/ttd-elektronik?page=all&filter={"bulan":'+bulan+',"tahun":'+tahun+'}',
            type: 'get',
            dataType: 'json',
        });

        for (var i = 0; i < Math.min(dataMasuk.data.length, 2); i++) {
            dtTabel.push(dataMasuk.data[i]);
        }
        for (var i = 0; i < Math.min(dataKeluar.data.length, 2); i++) {
            dtTabel.push(dataKeluar.data[i]);
        }
        for (var i = 0; i < Math.min(dataTtd.data.length, 2); i++) {
            dtTabel.push(dataTtd.data[i]);
        }

        createPie([dataMasuk.data.length,dataKeluar.data.length,dataTtd.data.length]);

        dtBase.push(dataMasuk.data);
        dtBase.push(dataKeluar.data);
        dtBase.push(dataTtd.data);

        // console.log(dtBase);
        // Tampilkan tabel
        var tbody = $('#aktifitas-persuratan tbody');
        tbody.empty();
        for (var i = 0; i < Math.min(dtTabel.length); i++) {
            var item = dtTabel[i];
            let status = generateLabel(item.is_diajukan,item.is_diterima);
            
            var row = ` <tr> 
                            <td> ${i+1} </td>
                            <td>${item.type}</td> 
                            <td>${item.tanggal}</td> 
                            <td>
                                <div><u>${item.no_surat}</u></div> 
                                <div>${item.perihal}</div> 
                            </td>
                            <td>
                                <div>${status.label}</div> 
                            </td>
                        </tr>`;
            tbody.append(row);
        }
        // console.log(dtBase);
        $.each(dtBase, function(i, item) {
            $.each(item, function(i, dt) {
                let status = generateLabel(dt.is_diajukan,dt.is_diterima);

                let bgClr = '#AD0303';
                if(dt.type=='Surat Masuk'){
                    bgClr = '#0068FF';
                }else if(dt.type=='Surat Keluar'){
                    bgClr = '#007D04';
                }
                
                var eventItem = {
                    title: '['+dt.type+'] '+dt.perihal+' - '+dt.tanggal+', status : '+status.text,
                    backgroundColor: bgClr,
                    start: dt.tanggal,  
                    end: dt.tanggal,    
                };
                dtEvents.push(eventItem);            
            });
        });

        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialDate: '{{ date("Y-m-d") }}',
            headerToolbar: {
                right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
            },
            navLinks: true,
            editable: true,
            selectable: true,
            businessHours: true,
            dayMaxEvents: true,
            events: dtEvents
        });
        calendar.render();
    }    

    getData(tahun, bulan);
    
</script>

@endsection