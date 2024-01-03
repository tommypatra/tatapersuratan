@extends('admin.template')

@section('scriptHead')
<title>Dashboard Web</title>
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
                </div>
            </div>
        </div>
    </div>
</div>


<!-- MULAI MODAL -->
<div class="modal fade modal-lg" id="modalweb" role="dialog">
    <div class="modal-dialog">
        <form id="fweb">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">FORM</h5>
                    <button type="button" class="btn btn-sm" data-bs-dismiss="modal" aria-label="Close">X</button>
                </div>
                <div class="modal-body ">

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                    <button type="button" class="btn btn-outline-primary btn-sm" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- AKHIR MODAL -->
@endsection

@section('scriptJs')
<script type="text/javascript">
</script>
@endsection