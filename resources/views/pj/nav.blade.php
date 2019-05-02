@section('nav')
    <nav class="navbar-default navbar-static-side" role="navigation">
        <div class="sidebar-collapse">
            <ul class="nav metismenu" id="side-menu">
                <li class="nav-header">
                    <div class="dropdown profile-element">
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <span class="clear"> <span class="block m-t-xs"> <strong class="font-bold">David Williams</strong>
                             </span> <span class="text-muted text-xs block">Art Director <b class="caret"></b></span> </span> </a>
                            <ul class="dropdown-menu animated fadeInRight m-t-xs">
                                <li><a href="#">Logout</a></li>
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
                    <a href="{{ url('tempat') }}"><i class="fa fa-th-large"></i> <span class="nav-label">Katalog Tempat</span></a>
                </li>
            </ul>

        </div>
    </nav>
@endsection