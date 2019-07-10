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
                        <h5>List Kerjasama</h5>
                        <div class="pull-right">        
                            <button type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#mAddKerjasama"><i class="fa fa-plus"></i> Tambah Kerjasama</button>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <div class="tabldataTables-examplee-responsive">
                            <table class="table table-striped table-bordered table-hover daftarKerjasama" >
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Nama</th>
                                        <th>Tipe</th>
                                        <th>Alamat</th>
                                        <th>Bidang</th>
                                        <th>Publish</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Id</th>
                                        <th>Nama</th>
                                        <th>Tipe</th>
                                        <th>Alamat</th>
                                        <th>Bidang</th>
                                        <th>Publish</th>
                                        <th>Status</th>
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
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>List Semua Katalog</h5>
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
                                        <th>Nama</th>
                                        <th>Tipe</th>
                                        <th>Alamat</th>
                                        <th>Bidang</th>
                                        <th>Update</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Id</th>
                                        <th>Nama</th>
                                        <th>Tipe</th>
                                        <th>Alamat</th>
                                        <th>Bidang</th>
                                        <th>Update</th>
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

    {{-- Modal Kerjasama --}}
    <div class="modal fade" id="mDetailKerjasama" role="dialog">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Info Kerjasama <b hidden id="bIdKerjasama"></b></h4>
                </div>
                <div class="modal-body">
                    <form role="form">
                        <div class="form-group"><label>Status : </label>
                            <div class="pull-right">        
                                <button type="button" style="display: none" class="btn btn-xs btn-primary" id="detailKerjasama"><i class="fa fa-plus"></i> Detail</button>
                            </div>
                            <p id="pStatus">Status</p>
                        </div>
                        <div class="form-group"><label>Penanggung Jawab Kantor: </label>
                            <p id="pPJKantor">Penanggung jawab</p>
                        </div>
                        <div class="form-group">
                            <label>No Telepon</label> 
                            <p id="pNo">083850277373</p>
                        </div>
                        <div class="form-group">
                                
                            <label>Document</label>
                            <button class="btn btn-xs pull-right" onclick="openDocument()" type="button">
                                <i class="fa fa-external-link"></i>
                            </button>
                            <p id="pDocument">Document</p>
                        </div>
                        <div class="form-group">
                            <label>Pengurus</label>
                            <p id="pPengurus">Pengurus</p>
                        </div>
                        <div id="infoKerjasama" style="display: none">
                            <hr>
                            <div class="text-center">
                                <label>Detail Kerjasama </label>
                            </div>
                            <div class="form-group"><label>Tanggal Mulai : </label>
                                <div class="pull-right">        
                                    <button type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#mEditKerjasama" id="editKerjasama"><i class="fa fa-edit"></i> Edit</button>
                                </div>
                                <p id="pTglMulai">Tanggal</p>
                            </div>
                            <div class="form-group"><label>Tanggal Berakhir :</label>
                                <p id="pTglBerakhir">Tanggal</p>
                            </div>
                            <div class="form-group">
                                <label>Keterangan</label> 
                                <p id="pKet">Tidak Ada</p>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-default" type="submit" data-dismiss="modal"><strong>Batal</strong></button>
                    <button class="btn btn-primary" id="bConKerjasama" style="display: none;" type="button" data-toggle="modal" data-target="#mConKerjasama"><strong>Terima</strong></button>
                    <button class="btn btn btn-danger pull-left" onclick="deleteKerjasama()" type="submit"><strong>Hapus Kerjasama</strong></button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Edit Kerjasama --}}
    <div class="modal fade" id="mEditKerjasama" role="dialog">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Edit Kerjasama <b hidden id="bIdKerjasama"></b></h4>
                </div>
                <div class="modal-body">
                    <form role="form" id="formEdit">
                        <div class="form-group">
                            <label class="font-noraml">Tanggal Mulai</label>
                            <div class="input-group date">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" class="form-control" id="data_1" placeholder="27/08/2018">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="font-noraml">Tanggal Mulai</label>
                            <div class="input-group date">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" class="form-control" id="data_2" placeholder="27/08/2018">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Keterangan</label>
                            <textarea class="form-control textarea-custom" rows="2" placeholder="Keterangan" id="taKet"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <small id="validationEditKerjasama" class="pull-left">*Tidak Boleh Kosong</small>
                    <button class="btn btn-default" type="submit" data-dismiss="modal"><strong>Batal</strong></button>
                    <button class="btn btn btn-primary" id="conKerjasama" type="submit"><strong>Publish</strong></button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Tambah Kerjasama --}}
    <div class="modal fade" id="mAddKerjasama" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Tambah Kerjasama <b hidden id="bIdKerjasama"></b></h4>
                </div>
                <div class="modal-body">
                    <form role="form" id="formAddKerjasama">
                        <div class="form-group"><label class="control-label">Tempat PKL</label>
                            <select class="select2_tempat form-control">
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Penanggung Jawab Kantor</label> 
                            <input type="text" id="iPJ" placeholder="Nama Penanggung Jawab Kantor" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>No Telepon</label> 
                            <input type="number" id="iNo" placeholder="No Telepon" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="font-group">Tanggal Mulai</label>
                            <div class="input-group date">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" class="form-control" id="data_3" placeholder="27/08/2018">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="font-group">Tanggal Mulai</label>
                            <div class="input-group date">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" class="form-control" id="data_4" placeholder="27/08/2018">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Keterangan</label>
                            <textarea class="form-control textarea-custom" rows="2" placeholder="Keterangan" id="taAddKet"></textarea>
                        </div>
                    </form>              
                </div>
                <div class="modal-footer">
                    <small id="messagevalidation" class="pull-left">*Tidak Boleh Kosong</small>
                    <button class="btn btn-default" type="submit" data-dismiss="modal"><strong>Batal</strong></button>
                    <button class="btn btn btn-primary" onclick="addKerjasama()" type="submit"><strong>Save</strong></button>
                </div>
                      
            </div>
        </div>
    </div>

    {{-- Modal Konfirmasi Kerjasama --}}
    <div class="modal fade" id="mConKerjasama" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Konfirmasi Kerjasama</b></h4>
                </div>
                <div class="modal-body">
                    <form role="form">
                        <div class="form-group">
                            <label class="font-group">Tanggal Mulai</label>
                            <div class="input-group date">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" class="form-control" id="data_5" placeholder="27/08/2018">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="font-group">Tanggal Mulai</label>
                            <div class="input-group date">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input type="text" class="form-control" id="data_6" placeholder="27/08/2018">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Keterangan</label>
                            <textarea class="form-control textarea-custom" rows="2" placeholder="Keterangan" id="taConKet"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <small id="validationConfirm" class="pull-left">*Tidak Boleh Kosong</small>
                    <button class="btn btn-default" type="submit" data-dismiss="modal"><strong>Batal</strong></button>
                    <button class="btn btn btn-primary" onclick="conKerjasama()" type="submit"><strong>Save</strong></button>
                </div>
            </div>
        </div>
    </div>

    <script>
        var header = {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')};
        var datapicker;
        var tabelKerjasama;
        var idKerjasama;
        var document;
        var curdate = new Date();

        $(document).ready(function(){
            $('.daftarTempat').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                order: [[ 5, 'desc']],
                bDestroy : true,
                bAutoWidth : false,
                pageLength : 5,
                ajax: {
                    'headers' : header,
                    'type' : 'POST',
                    'dataType' : 'json',
                    'url' : '/tempat'
                },
                columns : [
                    {'data': 'id'},
                    {'data': 'nama'},
                    {'data': 'tipe'},
                    {'data': 'alamat'},
                    {'data': 'bidang'},
                    {'data': 'tglUpdate'},
                    {'data': 'action'}
                ],
                columnDefs: [{
                    'targets': [0, 5],
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

            tabelKerjasama = $('.daftarKerjasama').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                order: [[ 5, 'desc']],
                bDestroy : true,
                bAutoWidth : false,
                pageLength : 5,
                ajax: {
                    'headers' : header,
                    'type' : 'POST',
                    'dataType' : 'json',
                    'url' : '/tempat/kerjasama'
                },
                columns : [
                    {'data': 'id'},
                    {'data': 'nama'},
                    {'data': 'tipe'},
                    {'data': 'alamat'},
                    {'data': 'bidang'},
                    {'data': 'tglPublish'},
                    {'data': 'status'},
                    {'data': 'action'}
                ],
                columnDefs: [{
                    'targets': [0, 5],
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
            $(".datepicker").datepicker("hide");
        });

        $(document).on('click', '.bDetailTempat', function(){
            $(window.location).attr('href', '/detail-tempat/' + this.id);
        });

        $(document).on('click', '.bDetailKerjasama', function(){
            getKerjasama(this.id);
            $('#mDetailKerjasama').modal("show");
        });

        $('#mDetailKerjasama').on('hidden.bs.modal', function(){
            document.getElementById('detailKerjasama').style['display'] = 'none';
            document.getElementById('infoKerjasama').style['display'] = 'none';
            document.getElementById('bConKerjasama').style['display'] = 'none';
        });

        function getKerjasama(idTempat){

            $.ajax({
                headers : header,
                type : 'GET',
                dataType : 'json',
                url : '/api/kerjasama/' + idTempat,
                success:function(data){
                    var status;
                    if(data.data.status == 1){
                        status = "Pengajuan Kerjasama";
                        document.getElementById('detailKerjasama').style['display'] = "none";
                        document.getElementById('bConKerjasama').style['display'] = "inline";
                    }else if (data.data.status == 2) {
                        status = "Kerjasama";
                        document.getElementById('detailKerjasama').style['display'] = "inline";
                    } else if (data.data.status == 3){
                        status = "Belum di Perpanjang";
                        document.getElementById('detailKerjasama').style['display'] = "inline";
                    }
                    
                    document.getElementById('pStatus').innerHTML = status;
                    document.getElementById('pPJKantor').innerHTML = data.data.namaPJKantor;
                    document.getElementById('pNo').innerHTML = data.data.no;
                    document.getElementById('pDocument').innerHTML = data.data.document;
                    document.getElementById('pPengurus').innerHTML = data.data.namaPengguna;
                    document.getElementById('bIdKerjasama').innerHTML = data.data.id;
                    idKerjasama = data.data.id;
                }
            });
        };

        function detailKerjasama(idKerjasama){

            $.ajax({
                headers : header,
                type : 'GET',
                dataType : 'json',
                url : 'api/detailkerjasama/' + idKerjasama,
                beforeSend:function(){
                    document.getElementById('detailKerjasama').innerHTML = '<i class="fa fa-plus"></i> Loading...';
                },
                success:function(data){
                    document.getElementById('pTglMulai').innerHTML = data.data.tglMulai;
                    document.getElementById('pTglBerakhir').innerHTML = data.data.tglBerakhir;
                    document.getElementById('pKet').innerHTML = data.data.keterangan;
                },
                complete:function(){
                    document.getElementById('detailKerjasama').innerHTML = '<i class="fa fa-plus"></i> Detail';
                    document.getElementById('infoKerjasama').style['display'] = 'inline';
                }
            });

        };

        $('#detailKerjasama').on('click', function(){
            detailKerjasama(idKerjasama);
        });

        $('#mEditKerjasama').on('shown.bs.modal', function(){
            $( "#data_1" ).datepicker( "setDate", document.getElementById('pTglMulai').innerHTML);
            $( "#data_2" ).datepicker( "setDate", document.getElementById('pTglBerakhir').innerHTML);
            document.getElementById('taKet').value = document.getElementById('pKet').innerHTML;
        }).on('hidden.bs.modal', function(){
            $( "#data_1" ).datepicker( "setDate", curdate);
            $( "#data_2" ).datepicker( "setDate", curdate);
            $('#formEdit').trigger('reset');
            document.getElementById('validationEditKerjasama').style['display'] = 'none';
        });

        $('#data_1').datepicker({
            todayBtn: "linked",
            autoclose: true,
            format: "dd/mm/yyyy",
            todayHighlight: true
        });

        $('#data_2').datepicker({
            todayBtn: "linked",
            autoclose: true,
            format: "dd/mm/yyyy",
            todayHighlight: true
        });

        $('#data_3').datepicker({
            todayBtn: "linked",
            autoclose: true,
            format: "dd/mm/yyyy",
            todayHighlight: true
        });

        $('#data_4').datepicker({
            todayBtn: "linked",
            autoclose: true,
            format: "dd/mm/yyyy",
            todayHighlight: true
        });

        $('#data_5').datepicker({
            todayBtn: "linked",
            autoclose: true,
            format: "dd/mm/yyyy",
            todayHighlight: true
        });

        $('#data_6').datepicker({
            todayBtn: "linked",
            autoclose: true,
            format: "dd/mm/yyyy",
            todayHighlight: true
        });

        $(".select2_tempat").select2({
            placeholder: "Pilih Tempat"
        });

        $(document).on('click', '#conKerjasama' ,function(){
            var date1 = $('#data_1').datepicker().data("datepicker").viewDate;
            var date2 = $('#data_2').datepicker().data("datepicker").viewDate;
            var opDate1 =date1 / 1000;
            var opDate2 =date2 / 1000;
            var keterangan = document.getElementById('taKet').value;

            if(!opDate1 || !opDate2 || !keterangan){

                document.getElementById('validationEditKerjasama').style['display'] = 'inline';

            }else{
                $.ajax({
                    headers : header,
                    type : 'POST',
                    dataType : 'json',
                    contentType : 'application/x-www-form-urlencoded; charset=UTF-8',
                    url : '/api/detailkerjasama',
                    data : {_method:'PUT', idKerjasama:idKerjasama,
                    tglMulai:opDate1, tglBerakhir:opDate2, keterangan:keterangan},
                    success:function(data){
                        $('#mEditKerjasama').modal('hide');

                    },
                    complete:function(){
                        detailKerjasama(idKerjasama);
                        tabelKerjasama.ajax.reload(null, false);
                    }
                });
            }
        });
        
        $('#mAddKerjasama').on('shown.bs.modal', function(){
            getAllTempat();
        }).on('hidden.bs.modal', function(){
            $( "#data_3" ).datepicker( "setDate", curdate);
            $( "#data_4" ).datepicker( "setDate", curdate);
            $(this).find('form').trigger('reset');
            $('.select2_tempat').empty().trigger('change');
            $('.select2_tempat').val(null).trigger('change');
            document.getElementById('messagevalidation').style['display'] = 'none';
        });

        $('#mConKerjasama').on('hidden.bs.modal', function(){
            $( "#data_5" ).datepicker( "setDate", curdate);
            $( "#data_6" ).datepicker( "setDate", curdate); 
            $(this).find('form').trigger('reset');
            document.getElementById('validationConfirm').style['display'] = 'none';
        });

        function getAllTempat(){
            $.ajax({
                headers : header,
                type : 'GET',
                dataType : 'json',
                url : 'tempat/kerjasama',
                beforeSend:function(){
                    var option = new Option();
                    $('.select2_tempat').append(option).trigger('change');
                },
                success:function(data){
                    var listTempat = data.data;
                    $.each(listTempat, function(i, item){
                    var option = new Option(listTempat[i].nama + ' (' + listTempat[i].tipe + ')', listTempat[i].id, false, false);
                    $('.select2_tempat').append(option).trigger('change');
                    if(listTempat[i].status !== 0){
                        $('.select2_tempat option[value="' + listTempat[i].id + '"]').prop('disabled', true);
                    }else{
                        $('.select2_tempat option[value="' + listTempat[i].id + '"]').prop('disabled', false);
                    }
                });
                }
            });
        }

        function addKerjasama(){
       
            var idTempat = $('.select2_tempat').val();
            var namaPJKantor = $('#iPJ').val();
            var no = $('#iNo').val();
            var dTglMulai = $('#data_3').datepicker().data("datepicker").viewDate;
            var dTglBerakhir = $('#data_4').datepicker().data("datepicker").viewDate;
            var tglMulai = dTglMulai / 1000;
            var tglBerakhir = dTglBerakhir / 1000;
            var keterangan = document.getElementById('taAddKet').value;

            if(!idTempat || !namaPJKantor || !no || !tglMulai ||
                !tglBerakhir || !keterangan){

                document.getElementById('messagevalidation').style['display'] = 'inline';
                
            }else{
                $.ajax({
                    headers : header,
                    type : 'POST',
                    dataType : 'json',
                    contentType : 'application/x-www-form-urlencoded; charset=UTF-8',
                    url : 'kerjasama',
                    data : {idTempat:idTempat, namaPJKantor:namaPJKantor, no:no, 
                    tglMulai:tglMulai, tglBerakhir:tglBerakhir, keterangan:keterangan},
                    success:function(data){
                        console.log(data);
                        toast(data.error, data.message);
                    },
                    complete:function(){
                        $('#mAddKerjasama').modal('hide');
                        tabelKerjasama.ajax.reload(null, false);
                    }
                });
            }
        }

        function deleteKerjasama(){
            $.ajax({
                headers : header,
                type : 'POST',
                dataType : 'json',
                contentType : 'application/x-www-form-urlencoded; charset=UTF-8',
                url : 'api/kerjasama/' + idKerjasama,
                data : {_method:'DELETE'},
                success:function(data){
                    console.log(data);
                    toast(data.error, data.message);
                },
                complete:function(){
                    $('#mDetailKerjasama').modal('hide');
                    tabelKerjasama.ajax.reload(null, false);
                }
            });
        }

        function conKerjasama(){
            var dTglMulai = $('#data_5').datepicker().data("datepicker").viewDate;
            var dTglBerakhir = $('#data_6').datepicker().data("datepicker").viewDate;
            var tglMulai = dTglMulai / 1000;
            var tglBerakhir = dTglBerakhir / 1000;
            var keterangan = document.getElementById('taConKet').value;

            if(!tglMulai || !tglBerakhir || !keterangan){
                document.getElementById('validationConfirm').style['display'] = 'inline';
            }else{
                $.ajax({
                    headers : header,
                    type : 'POST',
                    dataType : 'json',
                    contentType : 'application/x-www-form-urlencoded; charset=UTF-8',
                    url : 'api/detailkerjasama',
                    data : {idKerjasama:idKerjasama, tglMulai:tglMulai, 
                    tglBerakhir:tglBerakhir, keterangan:keterangan},
                    success:function(data){
                        console.log(data);
                        toast(data.error, data.message);
                    },
                    complete:function(){
                        $('#mConKerjasama').modal('hide');
                        $('#mDetailKerjasama').modal('hide');
                        tabelKerjasama.ajax.reload(null, false);
                    }
                });
            }            
        }

        function openDocument(){
            var documentKerjasama = document.getElementById('pDocument').innerHTML;
            window.open(documentKerjasama, '_blank');
        }

        function toast(error, message){
            setTimeout(function() {
                toastr.options = {
                    closeButton: true,
                    progressBar: true,
                    showMethod: 'slideDown',
                    timeOut: 2000
                };
                if(!error){
                    toastr.success(message, 'Info');
                }else{
                    toastr.error(message, 'Info');
                }
                

            }, 0);
        }

    </script>
@endsection
        
