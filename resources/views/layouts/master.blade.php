<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <link href="{{asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{asset('font-awesome/css/font-awesome.css') }}" rel="stylesheet">

    <link href="{{asset('css/animate.css') }}" rel="stylesheet">
    <link href="{{asset('css/style.css') }}" rel="stylesheet">

    {{-- Data tables CSS --}}
    <link href="{{asset('css/plugins/dataTables/datatables.min.css') }}" rel="stylesheet">

    {{-- Select2 --}}
    <link href="{{asset('css/plugins/select2/select2.min.css') }}" rel="stylesheet">

    <script src="{{asset('js/jquery-2.1.1.js') }}"></script>
    
    {{-- Select2 --}}
    <script src="{{asset('js/plugins/select2/select2.full.min.js') }}"></script>

    {{-- <link rel="stylesheet" href="https://unpkg.com/leaflet@1.4.0/dist/leaflet.css"
    integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA=="
    crossorigin=""/> --}}

    <link rel="stylesheet" href="{{asset('css/plugins/leaflet/leaflet.css')}}"/>

    <!-- Make sure you put this AFTER Leaflet's CSS -->
    {{-- <script src="https://unpkg.com/leaflet@1.4.0/dist/leaflet.js"
    integrity="sha512-QVftwZFqvtRNi0ZyCtsznlKSWOStnDORoefr1enyq5mVL4tmKB3S/EnC3rRJcxCPavG10IcrVGSmPh6Qw5lwrg=="
    crossorigin=""></script> --}}

    <script src="{{asset('js/plugins/leaflet/leaflet.js')}}"></script>

    {{-- <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" /> --}}
    <link rel="stylesheet" href="{{asset('css/plugins/leaflet/Control.Geocoder.css')}}" />
    <script src="{{asset('js/plugins/leaflet/Control.Geocoder.js')}}"></script> 

    <link href="{{asset('css/custom.css') }}" rel="stylesheet">

</head>

<body>

<div id="wrapper">

    @yield('nav')

    <div id="page-wrapper" class="gray-bg">
        <div class="row border-bottom">
            <nav class="navbar navbar-static-top white-bg" role="navigation" style="margin-bottom: 0">
                <div class="navbar-header">
                    <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
                    <form role="search" class="navbar-form-custom" method="post" action="#">
                        <div class="form-group">
                            <input type="text" placeholder="Search for something..." class="form-control" name="top-search" id="top-search">
                        </div>
                    </form>
                </div>
                <ul class="nav navbar-top-links navbar-right">
                    <li>
                        <a href="{{route('logout')}}">
                            <i class="fa fa-sign-out"></i> Log out
                        </a>
                    </li>
                </ul>

            </nav>
        </div>

        @yield('heading')

        @yield('contents')
        
        <div class="footer">
            <div class="pull-right">
                10GB of <strong>250GB</strong> Free.
            </div>
            <div>
                <strong>Copyright</strong> Example Company &copy; 2014-2015
            </div>
        </div>

    </div>
</div>

<!-- Mainly scripts -->

<script src="{{asset('js/bootstrap.min.js') }}"></script>
<script src="{{asset('js/plugins/metisMenu/jquery.metisMenu.js') }}"></script>
<script src="{{asset('js/plugins/slimscroll/jquery.slimscroll.min.js') }}"></script>

<!-- Custom and plugin javascript -->
<script src="{{asset('js/inspinia.js') }}"></script>
<script src="{{asset('js/plugins/pace/pace.min.js') }}"></script>

{{-- Data Tables JS --}}
<script src="{{asset('js/plugins/dataTables/datatables.min.js') }}"></script>

{{-- ChartJS --}}
<script src="{{asset('js/plugins/chartJs/Chart.min.js') }}"></script>

</body>

</html>
