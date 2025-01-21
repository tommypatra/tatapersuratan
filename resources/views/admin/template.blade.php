<!DOCTYPE html>
<html lang="en">

<head>
	@include('head')
	@yield('scriptHead')
	<script type="text/javascript">
		var vBaseUrl = '{{ url("/") }}';
        var vUserId = localStorage.getItem('id');
        var vAksesId = localStorage.getItem('akses');
        var vNama = localStorage.getItem('nama');
        var vTahunApp = {{ date("Y") }};
		var vFoto = vBaseUrl+'/'+localStorage.getItem('foto');
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
						<a class="sidebar-link" href="{{ route('scan-qrcode') }}">
							<i class="align-middle" data-feather="aperture"></i> <span class="align-middle">Scan Surat Masuk</span>
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
					<div id="menu-user"></div>
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
									<a href="{{ route('disposisi') }}" class="text-muted">Tampilkan semua disposisi</a>
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
									<a href="{{ route('distribusi') }}" class="text-muted">Tampilkan semua surat</a>
								</div>
							</div>
						</li>


						<li class="nav-item dropdown">
							<a class="nav-icon dropdown-toggle d-inline-block d-sm-none" href="#" data-bs-toggle="dropdown">
								<i class="align-middle" data-feather="settings"></i>
							</a>

							<a class="nav-link dropdown-toggle d-none d-sm-inline-block" href="#" data-bs-toggle="dropdown">
								<img src="{{ url('images/user-avatar.png') }}" class="avatar img-fluid rounded me-1 foto-profil" id="navbar-foto" alt="foto" /> 
								<span class="text-dark" id="navbar-nama"></span>
							</a>
							<div class="dropdown-menu dropdown-menu-end">
								<a class="dropdown-item" href="{{  route('profil') }}"><i class="align-middle me-1" data-feather="user"></i> Profile</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item akun-keluar" href="javascript:;" >Log out</a>
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
	<script>
		var dataNotif=[];
    	var authToken=localStorage.getItem('access_token');
		$.ajaxSetup({
			headers: {
				'Authorization': 'Bearer ' + authToken
			}
		});		

		function forceLogout(){
			localStorage.removeItem('access_token');
			localStorage.removeItem('email');
			localStorage.removeItem('hakakses');
			localStorage.removeItem('akses');
			localStorage.removeItem('foto');
			localStorage.removeItem('nama');
			localStorage.removeItem('id');
			window.location.replace(vBaseUrl+'/akun-keluar');
		}

	</script>
	<script src="{{ asset('js/app.js') }}"></script>
	<script src="{{ asset('js/loading/loading.js') }}"></script>
	<script src="{{ asset('js/info.js') }}"></script>
	
	<script>
		$('#navbar-nama').html(vNama);
		$('#navbar-foto').attr('src',vFoto);

		$("#ganti-akses").click(function(){
			pilihAkses();
		});

		$('.akun-keluar').click(function(){
			forceLogout();
		})

		// function showModalAkses() {
		// 	$('#daftar-akses').html('');
		// 	var daftar_akses = localStorage.getItem('hakakses');
		// 	var nama = localStorage.getItem('nama');
		// 	daftar_akses = JSON.parse(daftar_akses);
		// 	if (daftar_akses && daftar_akses.length > 1) {
		// 		showAkses();
		// 		myModalAkses.show();
		// 	}else{
		// 		window.location.replace(base_url+'/akun-dashboard');
		// 	}
		// }
				
        function pilihAkses(){
            var myModal1 = new bootstrap.Modal(document.getElementById('modal-ganti-akses'), {
                backdrop: 'static',
                keyboard: false,
            });
            myModal1.toggle();
			showAkses('#daftar-hakakses');
        }

		loadMenu();		
		function loadMenu(){
			$('#menu-user').empty();
			ajaxRequest(vBaseUrl+'/api/get-menu/'+vAksesId, 'GET', null, false,
				function(response) {
					console.log(response.data);
					if (response.data.length > 0) {
						var menuHtml = '';
						response.data.forEach(function(item) {
							menuHtml += `
								<li class="sidebar-item">
									<a class="sidebar-link" href="${vBaseUrl}/${item.route}">
										<i class="align-middle" data-feather="${item.icon}"></i> 
										<span class="align-middle">${item.label}</span>
									</a>
								</li>
							`;
						});
						$('#menu-user').append(menuHtml);
						feather.replace();
					} 
				}
			);
		}

	</script>
	@yield('scriptJs')
</body>

</html>