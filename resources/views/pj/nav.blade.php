@section('nav')
    <nav class="navbar-default navbar-static-side" role="navigation">
        <div class="sidebar-collapse">
            <ul class="nav metismenu" id="side-menu">
                <li class="nav-header">
                    <div class="dropdown profile-element">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <span class="clear"> <span class="block m-t-xs"> <strong class="font-bold">{{ $login }}</strong>
                            </span> <span class="text-muted text-xs block">FILKOM<b class="caret"></b></span> </span> </a>
                        <ul class="dropdown-menu animated fadeInRight m-t-xs">
                            <li><a href="{{route('logout')}}">Logout</a></li>
                        </ul>
                    </div>
                    <div class="logo-element">
                        KM
                    </div>
                </li>
                <li class="{{ request()->is('pj/dashboard') ? 'active' : '' }}">
                    <a href="{{ url('pj/dashboard') }}"><i class="fa fa-th-large"></i> <span class="nav-label">Dashboard</span></a>
                </li>
                <li class="{{ request()->is('tempat') || request()->is('add-tempat') ||
                request()->is('detail-tempat')? 'active' : '' }}">
                    <a href="{{ url('tempat') }}"><i class="fa fa-institution"></i> <span class="nav-label">Katalog Tempat</span></a>
                </li>
                <li class="{{ request()->is('pertanyaan') ? 'active' : '' }}">
                    <a href="{{ url('pertanyaan') }}"><i class="fa fa-question-circle"></i> <span class="nav-label">Pertanyaan</span></a>
                </li>
                <li class="{{ request()->is('keahlian') ? 'active' : '' }}">
                    <a href="{{ url('keahlian') }}"><i class="fa fa-suitcase"></i> <span class="nav-label">Keahlian</span></a>
                </li>
                <li class="{{ request()->is('bidang') ? 'active' : '' }}">
                    <a href="{{ url('bidang') }}"><i class="fa fa-graduation-cap"></i> <span class="nav-label">Bidang</span></a>
                </li>
            </ul>
        </div>
    </nav>
@endsection