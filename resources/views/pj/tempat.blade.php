@extends('layouts.master')
@extends('pj.nav')
@extends('pj.heading')

@section('title', 'Tempat')

@section('contents')
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>List Tempat</h5>
                        <div class="pull-right">        
                            <a href="{{url('tambah-tempat')}}"><button type="button" class="btn btn-xs btn-primary"><i class="fa fa-plus"></i> Tambah</button></a>    
                        </div>
                    </div>
                    <div class="ibox-content">
                        <div class="tabldataTables-examplee-responsive">
                            <table class="table table-striped table-bordered table-hover dataTables-example" >
                                <thead>
                                <tr>
                                    <th>Foto</th>
                                    <th>Nama</th>
                                    <th>Tipe</th>
                                    <th>Alamat</th>
                                    <th>Bidang</th>
                                    <th>Pembagi</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr class="gradeX">
                                    <td><img class="img-tempat-tabel" src="https://www.google.co.id/images/branding/googlelogo/1x/googlelogo_color_272x92dp.png"/></td>
                                    <td>Internet
                                        Explorer 4.0
                                    </td>
                                    <td>Win 95+</td>
                                    <td class="center">4</td>
                                    <td class="center">X</td>
                                    <td class="center">X</td>
                                </tr>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th>Foto</th>
                                    <th>Nama</th>
                                    <th>Tipe</th>
                                    <th>Alamat</th>
                                    <th>Bidang</th>
                                    <th>Pembagi</th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            $('.dataTables-example').DataTable({
                responsive: true,
                dom: '<"html5buttons"B>lTfgitp',
                buttons: [
                    { extend: 'copy'},
                    {extend: 'csv'},
                    {extend: 'excel', title: 'ExampleFile'},
                    {extend: 'pdf', title: 'ExampleFile'},

                    {extend: 'print',
                     customize: function (win){
                            $(win.document.body).addClass('white-bg');
                            $(win.document.body).css('font-size', '10px');

                            $(win.document.body).find('table')
                                    .addClass('compact')
                                    .css('font-size', 'inherit');
                        }
                    }
                ]
            });
        });

        $('.dataTables-example tbody').on('click', function(){
            window.location = '{{URL::asset('/detail-tempat')}}';
        });
    </script>
@endsection
        
