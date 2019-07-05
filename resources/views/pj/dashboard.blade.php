@extends('layouts.master')
@extends('pj.nav')
@section('title', 'Dashboard')

@section('contents')
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-4">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <span class="label label-success pull-right">All</span>
                        <h5>Kerjasama</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins">{{$totalKerjasama}}</h1>
                        <small>Total Kerjasama</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <span class="label label-success pull-right">All</span>
                        <h5>Pengajuan</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins">{{$totalPengajuan}}</h1>
                        <small>Total Pengajuan</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <span class="label label-success pull-right">All</span>
                        <h5>Belum Diperpanjang</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins">{{$totalPerpanjangan}}</h1>
                        <small>Total Belum Diperpanjang</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <span class="label label-success pull-right">All</span>
                        <h5>Katalog</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins">{{$totalKatalog}}</h1>
                        <small>Total Katalog</small>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <span class="label label-success pull-right">All</span>
                        <h5>Katalog Non-Active</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins">{{$nonActive}}</h1>
                        <small>Total Katalog Non-Active</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <span class="label label-info pull-right">All</span>
                        <h5>Angkatan Pengguna</h5>
                    </div>
                    <div class="ibox-content">
                        <div>
                            <canvas id="lineChart" height="100"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <span class="label label-info pull-right">All</span>
                        <h5>Detail Pengguna</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="text-center">
                            <canvas id="doughnutChart" height="140"></canvas>
                            <br>
                            <small class="no-margins">Total Pengguna - {{$totalPengguna}}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">

        $(document).ready(function(){
            var angkatan = [];
            var totalAngkatan = [];
            var ps = [];
            
            '@foreach($angkatan as $iAngkatan)'
                angkatan.push('{{$iAngkatan->angkatan}}');
                totalAngkatan.push('{{$iAngkatan->total}}');
            '@endforeach'

            '@foreach($totalPS as $iPS)'
                ps.push({
                    'value' : {{$iPS->value}},
                    'color' : '{{$iPS->color}}',
                    'highlight' : '{{$iPS->highlight}}',
                    'label' : '{{$iPS->label}}'
                });
            '@endforeach'

            var lineData = {
            labels: angkatan,
            datasets: [
                    {
                        label: "Example dataset",
                        fillColor: "rgba(26,179,148,0.5)",
                        strokeColor: "rgba(26,179,148,0.7)",
                        pointColor: "rgba(26,179,148,1)",
                        pointStrokeColor: "#fff",
                        pointHighlightFill: "#fff",
                        pointHighlightStroke: "rgba(26,179,148,1)",
                        data: totalAngkatan
                    }
                ]
            };

            var lineOptions = {
                scaleShowGridLines: true,
                scaleGridLineColor: "rgba(0,0,0,.05)",
                scaleGridLineWidth: 1,
                bezierCurve: true,
                bezierCurveTension: 0.4,
                pointDot: true,
                pointDotRadius: 4,
                pointDotStrokeWidth: 1,
                pointHitDetectionRadius: 20,
                datasetStroke: true,
                datasetStrokeWidth: 2,
                datasetFill: true,
                responsive: true,
            };


            var ctx = document.getElementById("lineChart").getContext("2d");
            var myNewChart = new Chart(ctx).Line(lineData, lineOptions);

            var doughnutData = ps;

            var doughnutOptions = {
                segmentShowStroke: true,
                segmentStrokeColor: "#fff",
                segmentStrokeWidth: 2,
                percentageInnerCutout: 45, // This is 0 for Pie charts
                animationSteps: 100,
                animationEasing: "easeOutBounce",
                animateRotate: true,
                animateScale: false
            };

            var ctx = document.getElementById("doughnutChart").getContext("2d");
            var DoughnutChart = new Chart(ctx).Doughnut(doughnutData, doughnutOptions);

        })
        
    </script>
@endsection
        
