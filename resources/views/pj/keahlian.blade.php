@extends('layouts.master')
@extends('pj.nav')
@extends('pj.heading')

@section('title', 'Keahlian')

@section('contents')
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>List Keahlian</h5>
                        <div class="pull-right">        
                            <button type="button" class="btn btn-xs btn-primary tambahKeahlian"><i class="fa fa-plus"></i> Tambah</button>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <div class="tabldataTables-examplee-responsive">
                            <table class="table table-striped table-bordered table-hover daftarKeahlian" >
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Keahlian</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Id</th>
                                        <th>Keahlian</th>
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

    {{-- Modal Tambah Keahlian --}}
    <div class="modal fade" id="mTambahKeahlian" role="dialog">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Tambah Keahlian</label> </h4>
                </div>
                <div class="modal-body">
                    <form role="form">
                        <div class="form-group">
                            <label>Keahlian</label> 
                            <input type="text" id="tKeahlian" placeholder="Keahlian" class="form-control">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-default" type="submit" data-dismiss="modal"><strong>Batal</strong></button>
                    <button class="btn btn btn-primary" id="bPublish" type="submit"><strong>Tambah</strong></button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Edit Keahlian --}}
    <div class="modal fade" id="mEditKeahlian" role="dialog">
            <div class="modal-dialog ">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Edit Keahlian</label> </h4>
                    </div>
                    <div class="modal-body">
                        <form role="form">
                            <div class="form-group">
                                <label>Keahlian</label> 
                                <input type="text" id="tEditKeahlian" placeholder="Keahlian" class="form-control">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-default" type="submit" data-dismiss="modal"><strong>Batal</strong></button>
                        <button class="btn btn btn-primary" id="bGanti" type="submit"><strong>Tambah</strong></button>
                    </div>
                </div>
            </div>
        </div>

    <script>
        var header = {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')};
        var tabelKeahlian;
        var id;

        $(document).ready(function(){
            tabelKeahlian = $('.daftarKeahlian').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                order: [[ 0, 'desc']],
                bDestroy : true,
                bAutoWidth : false,
                ajax: {
                    'headers' : header,
                    'type' : 'POST',
                    'dataType' : 'json',
                    'url' : '/keahlian'
                },
                columns : [
                    {'data': 'id'},
                    {'data': 'keahlian'},
                    {'data': 'action'}
                ],
                columnDefs: [{
                    'targets': [0],
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

        $('.tambahKeahlian').on('click', function(){
            $('#mTambahKeahlian').modal('show');

        });

        $('#bPublish').on('click', function(){
            var keahlian = document.getElementById('tKeahlian').value;

            $.ajax({
                headers : header,
                type : 'POST',
                dataType : 'json',
                contentType : 'application/x-www-form-urlencoded; charset=UTF-8',
                url : '/tambah-keahlian',
                data : {keahlian:keahlian},
                success:function(data){
                    $('#mTambahKeahlian').modal('hide');
                    tabelKeahlian.ajax.reload(null, false);
                }
            });
        });

        $('#mTambahKeahlian').on('hidden.bs.modal', function(){
            $(this).find('form').trigger('reset');
        });

        $('.daftarKeahlian tbody').on('click', 'button', function(){
            var keahlian = tabelKeahlian.row($(this).parents('tr')).data().keahlian;
            id = tabelKeahlian.row($(this).parents('tr')).data().id;
        
            $('#mEditKeahlian').modal('show');
            document.getElementById('tEditKeahlian').value = keahlian;
        });

        $('#bGanti').on('click', function(){
            var keahlian = document.getElementById('tEditKeahlian').value;

            $.ajax({
                headers : header,
                type : 'POST',
                dataType : 'json',
                contentType : 'application/x-www-form-urlencoded; charset=UTF-8',
                url : '/keahlian',
                data : {_method:'PUT', id:id, keahlian:keahlian},
                success:function(data){
                    $('#mEditKeahlian').modal('hide');
                    tabelKeahlian.ajax.reload(null, false);
                }
            });
        });
        

    </script>
@endsection
        
