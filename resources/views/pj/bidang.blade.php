@extends('layouts.master')
@extends('pj.nav')
@extends('pj.heading')

@section('title', 'Bidang')

@section('contents')
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>List Bidang</h5>
                        <div class="pull-right">        
                            <button type="button" class="btn btn-xs btn-primary bTambahBidang"><i class="fa fa-plus"></i> Tambah</button>  
                        </div>
                    </div>
                    <div class="ibox-content">
                        <div class="tabldataTables-examplee-responsive">
                            <table class="table table-striped table-bordered table-hover daftarBidang" >
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Bidang</th>
                                        <th>Id PS</th>
                                        <th>Program Studi</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Id</th>
                                        <th>Bidang</th>
                                        <th>Id PS</th>
                                        <th>Program Studi</th>
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

    {{-- Modal Tambah Bidang --}}
    <div class="modal fade" id="mTambahBidang" role="dialog">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Tambah Bidang</label> </h4>
                </div>
                <div class="modal-body">
                    <form role="form">
                        <div class="form-group"><label>Keahlian</label>
                            <select class="select2_ps form-control">
                                <option></option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Bidang</label> 
                            <input type="text" id="tBidang" placeholder="Bidang" class="form-control">
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

    {{-- Modal Edit Bidang --}}
    <div class="modal fade" id="mEditBidang" role="dialog">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Edit Bidang <label id="idInfo" hidden></label> </h4>
                </div>
                <div class="modal-body">
                    <form role="form">
                        <div class="form-group"><label>Keahlian</label>
                            <select class="select2_editps form-control">
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Bidang</label> 
                            <input type="text" id="tEditBidang" placeholder="Bidang" class="form-control">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-default" type="submit" data-dismiss="modal"><strong>Batal</strong></button>
                    <button class="btn btn btn-primary" id="bGanti" type="submit"><strong>Ganti</strong></button>
                </div>
            </div>
        </div>
    </div>

    <script>
        var header = {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')};
        var tabelBidang;
        var dataBidang;
        var idBidang;

        $(document).ready(function(){
            tabelBidang = $('.daftarBidang').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                order: [[ 2, 'desc']],
                bDestroy : true,
                bAutoWidth : false,
                ajax: {
                    'headers' : header,
                    'type' : 'POST',
                    'dataType' : 'json',
                    'url' : '/bidang'
                },
                columns : [
                    {'data': 'id'},
                    {'data': 'bidang'},
                    {'data': 'idPS'},
                    {'data': 'ps'},
                    {'data': 'action'}
                ],
                columnDefs: [{
                    'targets': [0, 2],
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

        $('.bTambahBidang').on('click', function(){
            $('#mTambahBidang').modal("show");

            databidang = $.ajax({
                headers : header,
                type : 'GET',
                dataType : 'json',
                url : '/api/ps',
                success:function(data){
                    var listPS = data.data;
                    $.each(listPS, function(i, item){
                        var option = new Option(listPS[i].ps, listPS[i].id, false, false);
                        $('.select2_ps').append(option).trigger('change');
                    });

                    $('#mTambahBidang').on('shown.bs.modal', function(){
                        
                    });
            
                    $('#mTambahBidang').on('hidden.bs.modal', function(){
                        $(this).find('form').trigger('reset');
                        $('.select2_ps').empty().trigger('change');
                        $('.select2_ps').val(null).trigger('change');
                    });
                }
            });
        });

        $(".select2_ps").select2({
            placeholder: "Pilih Program Studi",
            allowClear: true
        });
        
        $(".select2_editps").select2({
            allowClear: true
        });

        $('#bPublish').on('click', function(){
            var idPS = $('.select2_ps').val();
            var bidang = document.getElementById('tBidang').value;
            
            $.ajax({
                headers : header,
                type : 'POST',
                dataType : 'json',
                contentType : 'application/x-www-form-urlencoded; charset=UTF-8',
                url : '/tambah-bidang',
                data : {idPS:idPS, bidang:bidang},
                success:function(data){
                    $('#mTambahBidang').modal('hide');
                    tabelBidang.ajax.reload(null, false);
                }
            });
              
        });

        $('.daftarBidang tbody').on('click', 'button', function(){
            var idPS = tabelBidang.row($(this).parents('tr')).data().idPS;
            var bidang = tabelBidang.row($(this).parents('tr')).data().bidang;
            idBidang = tabelBidang.row($(this).parents('tr')).data().id;
            $('#tEditBidang').val(bidang);
            
            $.ajax({
                headers : header,
                type : 'GET',
                dataType : 'json',
                url : '/api/ps',
                success:function(data){
                    $('#mEditBidang').modal('show');
                    var listPS = data.data;
                    $.each(listPS, function(i, item){
                        var option = new Option(listPS[i].ps, listPS[i].id, false, false);
                        $('.select2_editps').append(option).val(idPS).trigger('change');
                    });

                    $('#mEditBidang').on('shown.bs.modal', function(){
                        
                    });

                    $('#mEditBidang').on('hidden.bs.modal', function(){
                        $(this).find('form').trigger('reset');
                        $('.select2_editps').empty().trigger('change');
                        $('.select2_editps').val(null).trigger('change');
                    });
                }
            });
        });

        $('#bGanti').on('click', function(){
            var idPS = $('.select2_editps').val();
            var bidang = document.getElementById('tEditBidang').value;
            
            $.ajax({
                headers : header,
                type : 'POST',
                dataType : 'json',
                contentType : 'application/x-www-form-urlencoded; charset=UTF-8',
                url : '/bidang',
                data : {_method:'PUT', id:idBidang, bidang:bidang, idPS:idPS},
                success:function(data){
                    $('#mEditBidang').modal('hide');
                    tabelBidang.ajax.reload(null, false);
                }
            });
        });
        
    </script>
@endsection
        
