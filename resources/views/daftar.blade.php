<!DOCTYPE html>
<html lang="en">

<head>
	@include('head')
	<title>Mendaftar | Persuratan</title>
</head>

<body>
	<main class="d-flex w-100">
		<div class="container d-flex flex-column">
			<div class="row vh-100">
				<div class="col-sm-10 col-md-8 col-lg-6 col-xl-5 mx-auto d-table h-100">
					<div class="d-table-cell align-middle">

						<div class="text-center mt-4">
							<h1 class="h2">Mendaftar Akun</h1>
							<p class="lead">
								Manajemen persuratan digital.
							</p>
						</div>

						<div class="card">
							<div class="card-body">
								<div class="m-sm-3">
									<form id="myForm">
										<div class="mb-3">
											<label class="form-label">Nama Lengkap</label>
											<input class="form-control form-control-lg" type="text" name="name" placeholder="Enter your name" required />
										</div>
										<div class="mb-3">
											<label class="form-label">Email</label>
											<input class="form-control form-control-lg" type="email" name="email" placeholder="Enter your email" required />
										</div>
										<div class="mb-3">
											<label class="form-label">Jenis Kelamin</label>
											<select class="form-control form-control-lg" type="jenis_kelamin" name="jenis_kelamin" required>
												<option value="L">Laki-laki</option>
												<option value="P">Perempuan</option>
											</select>
										</div>
										<div class="mb-3">
											<label class="form-label">NIP</label>
											<input class="form-control form-control-lg" type="nip" name="nip" placeholder="Enter your nip" required />
										</div>
										<div class="mb-3">
											<label class="form-label">Alamat</label>
											<textarea class="form-control form-control-lg" rows="3" type="alamat" name="alamat" placeholder="Enter your address"></textarea>
										</div>
										<div class="mb-3">
											<label class="form-label">HP</label>
											<input class="form-control form-control-lg" type="hp" name="hp" placeholder="Enter your hp" required />
											gunakan nomor WA anda yang aktif.
										</div>
										<div class="mb-3">
											<label class="form-label">Password</label>
											<input class="form-control form-control-lg" type="password" name="password" placeholder="Enter password" minlength="8" required />
										</div>
										<div class="d-grid gap-2 mt-3">
											<button type="submit" class="btn btn-lg btn-primary">Daftar Sekarang</button>
										</div>
									</form>
								</div>
							</div>
						</div>
						<div class="text-center mb-3">
							Sudah punya akun? <a href="{{ route('akun-masuk') }}">Masuk</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</main>

	<script src="{{ asset('admin/adminkit-dev/static/js/app.js') }}"></script>
	<script src="{{ asset('js/jquery-3.6.3.min.js') }}"></script>
	{{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
    <script src="{{ asset('js/jquery-validation-1.19.5/dist/jquery.validate.min.js') }}"></script>
	<script src="{{ asset('js/sweetalert2/dist/sweetalert2.min.js') }}"></script>
	<script src="{{ asset('js/app.js') }}"></script>
	<script src="{{ asset('js/loading/loading.js') }}"></script>

	<script>

		//validasi form dan submit handler untuk simpan atau ganti
		$("#myForm").validate({
			submitHandler: function(form) {
				simpan(form)
			}
		});    

		//simpan baru atau simpan perubahan
		function simpan(form) {
			let dataForm = $(form).serialize();
			//load user
			$.ajax({
				url: '{{ route("akun-baru-simpan") }}',
				method: 'POST',
				dataType: 'json',
				data : dataForm,
				success: function (response){
					if(response.success){
						let timerInterval;
						Swal.fire({
							title: 'Pendaftaran Berhasil!',
							html: 'Anda akan di arahkan ke halaman login dalam <b></b> milliseconds, silahkan menunggu',
							timer: 2000,
							icon: 'success',
							allowOutsideClick: false,
							timerProgressBar: true,
							didOpen: () => {
								Swal.showLoading()
								const b = Swal.getHtmlContainer().querySelector('b')
								timerInterval = setInterval(() => {
								b.textContent = Swal.getTimerLeft()
								}, 100)
							},
							willClose: () => {
								clearInterval(timerInterval)
							}
							}).then((result) => {
							if (result.dismiss === Swal.DismissReason.timer) {
								location.href = '{{ route("akun-masuk") }}';
							}
						})  
					}else
						appShowNotification(response.success,[response.message]);
				},
				error: function(xhr, status, error) {
                    // appShowNotification(false,[response.message]);
					console.log(xhr.responseText);
				}
			});
		}		    

	</script>
</body>

</html>