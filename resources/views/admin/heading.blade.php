@section('heading')
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>@yield('title')</h2>
            <ol class="breadcrumb">
                <li>
                <a href="{{ url('/admin/dashboard') }}">Home</a>
                </li>
                <li class="active">
                    <strong>@yield('title')</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-2">

        </div>
    </div>
@endsection