<!DOCTYPE html>
<html lang="en">
<head>
	@include('head')
	<title>Masuk | Persuratan</title>
</head>

<body>
	<main class="d-flex w-100">
		<div class="container d-flex flex-column">
			<div class="row vh-100">
				<div class="col-sm-10 col-md-8 col-lg-6 col-xl-5 mx-auto d-table h-100">
					<div class="d-table-cell align-middle">
						<form id="myForm">

							<div class="text-center mt-4">
								<h1 class="h2">Selamat Datang!</h1>
								<p class="lead">
									Masukan akun anda untuk melanjutkan
								</p>
							</div>

							<div class="card">
								<div class="card-body">
									<div class="m-sm-3">
										<form>
											<div class="mb-3">
												<label class="form-label">Email</label>
												<input class="form-control form-control-lg" type="email" name="email" id="email" required data-rule-email="true" placeholder="Enter your email" />
											</div>
											<div class="mb-3">
												<label class="form-label">Password</label>
												<input class="form-control form-control-lg" type="password" name="password" id="password" required minlength="8" placeholder="Enter your password" />
											</div>
											<div>
												<div class="form-check align-items-center">
													<input id="customControlInline" type="checkbox" class="form-check-input" value="remember-me" name="remember-me" checked>
													<label class="form-check-label text-small" for="customControlInline">Remember me</label>
												</div>
											</div>
											<div class="d-grid gap-2 mt-3">
												<button type="submit" class="btn btn-lg btn-primary">Masuk</button>
											</div>
										</form>
									</div>
								</div>
							</div>
							<div class="text-center mb-3">
								Tidak punya akun? <a href="{{ route('akun-daftar') }}">Mendaftar</a>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</main>

<!-- MULAI MODAL -->
<div class="modal fade" id="modal-pilih-akses" role="dialog">
    <div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">PILIH AKSES AKUN</h5>
			</div>
			<div class="modal-body" id="daftar-hakakses">
			</div>
		</div>
    </div>
</div>
<!-- AKHIR MODAL -->	

	<script src="admin/adminkit-dev/static/js/app.js"></script>
	<script src="js/jquery-3.6.3.min.js"></script>
    <script src="js/jquery-validation-1.19.5/dist/jquery.validate.min.js"></script>
	<script src="js/sweetalert2/dist/sweetalert2.min.js"></script>
	<script src="js/app.js"></script>
	<script src="js/loading/loading.js"></script>

<script>
    var csrfToken = $('meta[name="csrf-token"]').attr('content');

	$(document).ready(function() {
			function pilihAkses(hakakses){
				$('#daftar-hakakses').html("");
				var myModal1 = new bootstrap.Modal(document.getElementById('modal-pilih-akses'), {
					backdrop: 'static',
					keyboard: false,
				});
				$('#daftar-hakakses').html(hakakses);
				myModal1.toggle();
			}

			$('#modal-pilih-akses').on('hidden.bs.modal', function(e) {
				// location.href = '{{ route("akun-dashboard") }}';
			});

			$("#myForm").validate({
				messages: {
					email: "Please enter a valid email address.",
					password: {
						required: "Password cannot be empty.",
						minlength: "Password must be at least 8 characters."
					}
				},
				submitHandler: function(form) {
					disableForm();
					login(form)
				}
			});



			function setSession(param){
				let postData={
					access_token: param.access_token,
					email: param.data.email,
					hakakses: param.hakakses,
					akses: param.akses,
					foto: param.foto,
				};
				$.ajax({
					url: '{{ route("akun-set-session") }}',
					type: 'POST',
					data: postData,
					headers: {
						'X-CSRF-TOKEN': csrfToken
					},
					success: function (response) {
                        if (response.success) {
							if(response.hakakses.length>1){
								pilihAkses(response.hakakses_html);
							}else{
								location.href = '{{ route("akun-dashboard") }}';
							}
						}else {
							appShowNotification(false,['session tidak tersimpan hubungi admin web']);
                        }
					},
					error: function (error) {
						console.error(error);
					}
				});
			}

			function login(form) {
				$('#daftar-hakakses').html('');
                $.ajax({
                    type: 'POST',
                    url: '{{ route("auth-login") }}',
                    data: $(form).serialize(),
                    success: function(response) {
                        if (response.success) {
							setSession(response);				
                        } else {
							disableForm(false);
							appShowNotification(false,[response.message]);
                        }
                    },
                    error: function(xhr, status, error) {
						disableForm(false);
						appShowNotification(false,['Something went wrong. Please try again later.']);
                    }
                });
			}
						
			function goDashboard(){
				let timerInterval;
				Swal.fire({
				title: 'Login Berhasil!',
				html: 'Anda akan di arahkan secara otomatis dalam <b></b> milliseconds, silahkan menunggu',
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
						if(response.hakakses.length>1){
							pilihAkses(response.hakakses_html);
						}else{
							location.href = '{{ route("akun-dashboard") }}';
						}
					}
				})

			}

		});
	</script>


	</body>

</html>
