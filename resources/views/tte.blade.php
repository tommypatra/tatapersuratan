<!DOCTYPE html>
<html lang="en">

<head>
	@include('head')
	<title>Verifikasi Tanda Tangan Elektronik</title>
</head>

<body>
	<main class="d-flex w-100">
		<div class="container d-flex flex-column">
			<div class="row vh-100">
				<div class="col-sm-10 col-md-8 col-lg-6 col-xl-5 mx-auto d-table h-100">
					<div class="d-table-cell align-middle">
						<div style="text-align: center;">
							<img src="{{ asset('images/logo.png') }}" height="115px" style="display: inline-block; margin: 0 auto;">
						</div>
						<div class="text-center mt-4">
							<h1 class="h2">Verifikasi TTE (Tanda Tangan Elektronik)</h1>
							<p class="lead">
								Manajemen persuratan digital.
							</p>
						</div>

						<div class="card">
							<div class="card-body">
								<div class="mb-3">
									Bahwa, surat keluar dengan :  
								</div>
								<div class="mb-3">
									Nomor Surat : 
									<div style="font-weight:bold;" id="no_surat"><i>nomor surat</i></div>
								</div>
								<div class="mb-3">
									Tanggal : <span class="badge bg-success" id="tanggal"><i>tanggal</i></span>
								</div>
								<div class="mb-3">
									Perihal:
									<div>
										<i class="fas fa-quote-left fa-1x" aria-hidden="true"></i>
										<blockquote class="blockquote pb-2">
											<p id="perihal"><i>perihal</i></p>
										</blockquote>
										<figcaption class="blockquote-footer mb-0">
											<span class="jabatan"><i>jabatan</i></span> 
										</figcaption>                                   
									</div>
								</div>
								<div class="mb-3" id="keterangan">
									Telah sah dan ditandatangani secara elektronik oleh <span id="pejabat"><i>pejabat</i></span> sebagai <span class="jabatan"><i>jabatan</i></span> IAIN Kendari.  
								</div>								
								<hr>
								<div class="text-center mb-3">
									<i class="bi bi-filetype-pdf"></i> 
									<a href="#" id="file" class="btn btn-sm btn-primary" target="_blank">
										Arsip Dokumen
									</a> 
								</div>
		
							</div>
						</div>
						<div class="text-center mb-3">
							<a href="{{ route('akun-masuk') }}">Kembali ke halaman utama </a>
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
		var vBaseUrl = '{{ url("/") }}';

		loadTTE('{{ $kode }}');
		function loadTTE(kode) {
			$.ajax({
				url: vBaseUrl+'/api/tte/'+kode,
				method: 'GET',
				dataType: 'json',
				success: function (response){
					console.log(response);
					if(response.success){
						$('#no_surat').html(response.data.no_surat);
						$('#perihal').html(response.data.perihal);
						$('#tanggal').html(response.data.tanggal);
						$('.jabatan').html(response.data.jabatan);
						$('#pejabat').html(response.data.pejabat);
						$('#file').attr("href", vBaseUrl+'/'+response.data.file);
						if(!response.data.is_diterima){
							var ket='tidak sah dan tidak ditanda tangani secara elektronik';
							if(response.data.catatan)
								ket+=' karena '+response.data.catatan;
							$('#keterangan').html(ket.toUpperCase());
							$('#file').attr("href", "javascript:;");
							setTimeout(
								function () {
									window.location.href = '{{ route("akun-masuk") }}';
								}, 5000
							);						
						}
					}
				},
				error: function(xhr, status, error) {
					alert(error);
					// window.location.href = '{{ route("akun-masuk") }}';
				}
			});
		}
	</script>
</body>

</html>