<div class="sidebar-cta">
    <div class="sidebar-cta-content">
        <strong class="d-inline-block mb-2">Akun</strong>

        <div class="d-grid">
            <a href="{{ route('profil') }}" class="btn btn-primary mb-2">
                <i data-feather="user"></i> Profil
            </a>
        </div>
        @if(count(session()->get('hakakses')) > 1)
        <div class="d-grid">
            <a href="javascript:;" class="btn btn-primary mb-2" id="ganti-akses">
                <i data-feather="shuffle"></i> Ganti Akses
            </a>
        </div>
        @endif
        <div class="d-grid">
            <a href="{{ route('akun-keluar') }}" class="btn btn-primary">
                <i data-feather="power"></i> Keluar
            </a>
        </div>
    </div>
</div>