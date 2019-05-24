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
                            <table class="table table-striped table-bordered table-hover daftarTempat" >
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Foto</th>
                                        <th>Nama</th>
                                        <th>Tipe</th>
                                        <th>Alamat</th>
                                        <th>Bidang</th>
                                        <th>Pembagi</th>
                                        <th>Publish</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Id</th>
                                        <th>Foto</th>
                                        <th>Nama</th>
                                        <th>Tipe</th>
                                        <th>Alamat</th>
                                        <th>Bidang</th>
                                        <th>Pembagi</th>
                                        <th>Publish</th>
                                        <th>Action</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        var header = {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')};

        $(document).ready(function(){
            $('.daftarTempat').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                order: [[ 7, 'desc']],
                bDestroy : true,
                bAutoWidth : false,
                ajax: {
                    'headers' : header,
                    'type' : 'POST',
                    'dataType' : 'json',
                    'url' : '/tempat'
                },
                columns : [
                    {'data': 'id'},
                    {'data': 'foto'},
                    {'data': 'nama'},
                    {'data': 'tipe'},
                    {'data': 'alamat'},
                    {'data': 'bidang'},
                    {'data': 'pembagi'},
                    {'data': 'tglPublish'},
                    {'data': 'action'}
                ],
                columnDefs: [{
                    'targets': [0, 7],
                    'visible': false
                }],
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

        $(document).on('click', '.bDetailTempat', function(){
            $(window.location).attr('href', '/detail-tempat/' + this.id);
        });
    </script>
@endsection
        
