<!DOCTYPE html>
<html lang="en">

<head>
	@include('head')
	@yield('scriptHead')
	<script type="text/javascript">
		var vBaseUrl = '{{ url("/") }}';
        var vUserId = '{{ auth()->user()->id }}';
        var vTahunApp = {{ env("APP_TAHUN") }};
		var vFoto = '{{ session()->get("foto") }}';
	</script>
</head>

<body>
	<div class="wrapper">
		<nav id="sidebar" class="sidebar js-sidebar">
			<div class="sidebar-content js-simplebar">
				<a class="sidebar-brand" href="index.html">
					<span class="align-middle">Persuratan</span>
				</a>
				<ul class="sidebar-nav">
					<li class="sidebar-header">
						Persuratan Digital
					</li>
					<li class="sidebar-item">
						<a class="sidebar-link" href="{{ route('akun-dashboard') }}">
							<i class="align-middle" data-feather="home"></i> <span class="align-middle">Dashboard</span>
						</a>
					</li>
					<li class="sidebar-item">
						<a class="sidebar-link" href="{{ route('ttd-elektronik') }}">
							<i class="align-middle" data-feather="pen-tool"></i> <span class="align-middle">Tanda Tangan QRCode</span>
						</a>
					</li>
					<li class="sidebar-item">
						<a class="sidebar-link" href="{{ route('distribusi') }}">
							<i class="align-middle" data-feather="hard-drive"></i> <span class="align-middle">Distribusi Surat</span>
						</a>
					</li>
					<li class="sidebar-item">
						<a class="sidebar-link" href="{{ route('disposisi') }}">
							<i class="align-middle" data-feather="file-text"></i> <span class="align-middle">Disposisi</span> 
						</a>
					</li>
					<li class="sidebar-item">
						<a class="sidebar-link" href="{{ route('surat-masuk') }}">
							<i class="align-middle" data-feather="inbox"></i> <span class="align-middle">Surat Masuk</span>
						</a>
					</li>
					<li class="sidebar-item">
						<a class="sidebar-link" href="{{ route('surat-keluar') }}">
							<i class="align-middle" data-feather="mail"></i> <span class="align-middle">Penomoran Surat</span>
						</a>
					</li>				
					@if(session()->get('akses') == 1)
						@include('admin.partials.menu_admin')
					@endif
				</ul>
				@include('admin.partials.menu_general')
			</div>
		</nav>

		<div class="main">
			<nav class="navbar navbar-expand navbar-light navbar-bg">
				<a class="sidebar-toggle js-sidebar-toggle">
          <i class="hamburger align-self-center"></i>
        </a>

				<div class="navbar-collapse collapse">
					<ul class="navbar-nav navbar-align">
						<li class="nav-item dropdown">
							<a class="nav-icon dropdown-toggle" href="#" id="alertsDropdown2" data-bs-toggle="dropdown">
								<div class="position-relative">
									<i class="align-middle" data-feather="layers"></i>
								</div>
							</a>
							<div class="dropdown-menu dropdown-menu-lg dropdown-menu-end py-0" aria-labelledby="alertsDropdown2">
								<div class="list-group" id="data_notif_info">
									<table class="table">
										<thead>
											<tr style="font-weight: bold;">
												<td>Tahap</td>
												<td style="text-align:center;">Surat Masuk</td>
												<td style="text-align:center;">Surat Keluar</td>
												<td style="text-align:center;">TTD QrCode</td>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td><span class="badge bg-warning">Konsep</span></td>
												<td id="konsep-masuk" style="text-align:center;">0</td>
												<td id="konsep-keluar" style="text-align:center;">0</td>
												<td id="konsep-ttd" style="text-align:center;">0</td>
											</tr>
											<tr>
												<td><span class="badge bg-info">Diajukan</span></td>
												<td id="diajukan-masuk" style="text-align:center;">0</td>
												<td id="diajukan-keluar" style="text-align:center;">0</td>
												<td id="diajukan-ttd" style="text-align:center;">0</td>
											</tr>
											<tr>
												<td><span class="badge bg-success">Diterima</span></td>
												<td id="diterima-masuk" style="text-align:center;">0</td>
												<td id="diterima-keluar" style="text-align:center;">0</td>
												<td id="diterima-ttd" style="text-align:center;">0</td>
											</tr>
											<tr>
												<td><span class="badge bg-danger">Ditolak</span></td>
												<td id="ditolak-masuk" style="text-align:center;">0</td>
												<td id="ditolak-keluar" style="text-align:center;">0</td>
												<td id="ditolak-ttd" style="text-align:center;">0</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</li>

						<li class="nav-item dropdown">
							<a class="nav-icon dropdown-toggle" href="#" id="alertsDropdown" data-bs-toggle="dropdown">
								<div class="position-relative">
									<i class="align-middle" data-feather="bell"></i>
									<span class="indicator jumlah_tujuan_belum_diakses">0</span>
								</div>
							</a>
							<div class="dropdown-menu dropdown-menu-lg dropdown-menu-end py-0" aria-labelledby="alertsDropdown">
								<div class="dropdown-menu-header">
									<span class="jumlah_tujuan_belum_diakses">0</span> Disposisi Baru
								</div>
								<div class="list-group" id="data_notif_tujuan">
								</div>
								<div class="dropdown-menu-footer">
									<a href="disposisi" class="text-muted">Tampilkan semua disposisi</a>
								</div>
							</div>
						</li>
						<li class="nav-item dropdown">
							<a class="nav-icon dropdown-toggle" href="#" id="alertsDropdown2" data-bs-toggle="dropdown">
								<div class="position-relative">
									<i class="align-middle" data-feather="inbox"></i>
									<span class="indicator jumlah_distribusi_belum_diakses">0</span>
								</div>
							</a>
							<div class="dropdown-menu dropdown-menu-lg dropdown-menu-end py-0" aria-labelledby="alertsDropdown2">
								<div class="dropdown-menu-header">
									<span class="jumlah_distribusi_belum_diakses">0</span> Surat Baru
								</div>
								<div class="list-group" id="data_notif_distribusi">
								</div>
								<div class="dropdown-menu-footer">
									<a href="distribusi" class="text-muted">Tampilkan semua surat</a>
								</div>
							</div>
						</li>


						<li class="nav-item dropdown">
							<a class="nav-icon dropdown-toggle d-inline-block d-sm-none" href="#" data-bs-toggle="dropdown">
                <i class="align-middle" data-feather="settings"></i>
              </a>

							<a class="nav-link dropdown-toggle d-none d-sm-inline-block" href="#" data-bs-toggle="dropdown">
								<img src="{{ asset(session()->get("foto")) }}" class="avatar img-fluid rounded me-1 foto-profil" alt="foto" /> 
								<span class="text-dark">{{ auth()->user()->name }}</span>
							</a>
							<div class="dropdown-menu dropdown-menu-end">
								<a class="dropdown-item" href="{{  route('profil') }}"><i class="align-middle me-1" data-feather="user"></i> Profile</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="{{  route('akun-keluar') }}">Log out</a>
							</div>
						</li>
					</ul>
				</div>
			</nav>

			<main class="content">
				@yield('container')
			</main>

			
			@include('admin.partials.footer')
		</div>
	</div>



<!-- MULAI MODAL -->
<div class="modal fade" id="modal-ganti-akses" role="dialog">
    <div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">PILIH AKSES AKUN</h5>
				<button type="button" class="btn btn-sm" data-bs-dismiss="modal" aria-label="Close">X</button>
			</div>
			<div class="modal-body" id="daftar-hakakses">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-primary btn-sm" data-bs-dismiss="modal">Tutup</button>
			</div>
		</div>
    </div>
</div>
<!-- AKHIR MODAL -->	

	<script src="{{ asset('admin/adminkit-dev/static/js/app.js') }}"></script>
	{{-- <script src="{{ asset('js/jquery-3.6.3.min.js') }}"></script> --}}
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/jquery-validation-1.19.5/dist/jquery.validate.min.js') }}"></script>
	<script src="{{ asset('js/sweetalert2/dist/sweetalert2.min.js') }}"></script>
	<script src="{{ asset('js/app.js') }}"></script>
	<script src="{{ asset('js/loading/loading.js') }}"></script>
	
	<script>
		var showNotif = true;
    	var authToken="{{ session()->get('access_token') }}";
		$.ajaxSetup({
			headers: {
				'Authorization': 'Bearer ' + authToken
			}
		});
	</script>
	@yield('scriptJs')
	<script>
		$("#ganti-akses").click(function(){
			$.get("{{ route('akun-daftar-akses') }}", function(response, status){
				if(status=='success'){
					if(response.hakakses.length>1){
						pilihAkses(response.hakakses_html);
					}else{
						location.href = '{{ route("akun-dashboard") }}';
					}
				}
			});
		});

        function pilihAkses(hakakses){
			$('#daftar-hakakses').html("");
            var myModal1 = new bootstrap.Modal(document.getElementById('modal-ganti-akses'), {
                backdrop: 'static',
                keyboard: false,
            });
            myModal1.toggle();
			$('#daftar-hakakses').html(hakakses);
        }

		function infoDisposisi(){
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
			});
		}

		function infoDistribusi(){
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
			});
		}
		
		function infoGeneral(){
			$.get("/api/info-general", function (response) {
				if(response.success){
					let surat_masuk=response.data.surat_masuk;
					$('#konsep-masuk').text(surat_masuk.konsep);
					$('#diajukan-masuk').text(surat_masuk.diajukan);
					$('#diterima-masuk').text(surat_masuk.diterima);
					$('#ditolak-masuk').text(surat_masuk.ditolak);

					let surat_keluar=response.data.surat_keluar;
					$('#konsep-keluar').text(surat_keluar.konsep);
					$('#diajukan-keluar').text(surat_keluar.diajukan);
					$('#diterima-keluar').text(surat_keluar.diterima);
					$('#ditolak-keluar').text(surat_keluar.ditolak);

					let ttd=response.data.ttd;
					$('#konsep-ttd').text(ttd.konsep);
					$('#diajukan-ttd').text(ttd.diajukan);
					$('#diterima-ttd').text(ttd.diterima);
					$('#ditolak-ttd').text(ttd.ditolak);

				}
			});
		}
		
		function updateNotifWeb(){
			if(showNotif){
				infoDisposisi();
				infoDistribusi();
				infoGeneral();
			}
		}

		updateNotifWeb();

	</script>
</body>

</html>