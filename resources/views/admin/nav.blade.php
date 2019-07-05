@section('nav')
    <nav class="navbar-default navbar-static-side" role="navigation">
        <div class="sidebar-collapse">
            <ul class="nav metismenu" id="side-menu">
                <li class="nav-header">
                    <div class="dropdown profile-element">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <span class="clear"> <span class="block m-t-xs"> <strong class="font-bold">{{$login}}</strong>
                            </span> <span class="text-muted text-xs block">FILKOM<b class="caret"></b></span> </span> </a>
                        <ul class="dropdown-menu animated fadeInRight m-t-xs">
                            <li><a href="{{route('logout')}}">Logout</a></li>
                        </ul>
                    </div>
                    <div class="logo-element">
                        KM
                    </div>
                </li>
                <li class="{{ request()->is('admin/dashboard') ? 'active' : '' }}">
                    <a href="{{ url('admin/dashboard') }}"><i class="fa fa-th-large"></i> <span class="nav-label">Dashboard</span></a>
                </li>
                <li class="{{ request()->is('pertanyaan-mahasiswa') || request()->is('pertanyaan-pj') ? 'active' : '' }}">
                    <a href="#"><i class="fa fa-question-circle"></i> <span class="nav-label">Pertanyaan</span><span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li class=" {{ Request::is('pertanyaan-mahasiswa') ? 'active' : '' }}">
                            <a href="{{ url('pertanyaan-mahasiswa') }}">Mahasiswa</a>
                        </li>
                        <li class=" {{ Request::is('pertanyaan-pj') ? 'active' : '' }}">
                            <a href="{{ url('pertanyaan-pj') }}">Penanggung Jawab PKL</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
@endsection