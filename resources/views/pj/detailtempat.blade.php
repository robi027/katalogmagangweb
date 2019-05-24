@extends('layouts.master')
@extends('pj.nav')
@extends('pj.heading')

@section('title', 'Detail Tempat')

@section('contents')
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-7">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Data Tempat</h5>
                        <div class="pull-right">        
                            <a href="{{url('update-tempat'). '/' . $id }}"><button type="button" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> Edit</button></a>    
                        </div>
                    </div>
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="product-images">
                                    <img class="img-tempat-tabel" src="{{$foto}}"/>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <h2 class="font-bold m-b-xs">
                                    {{ $nama . ' ('.$tipe.')' }}  <span class="label lStatus">Loading...</span>
                                </h2>
                                <small>
                                    {{$bidang}}
                                </small>
                                <hr>
                                <h4>Deskripsi</h4>
                                <div class="small text-muted">
                                    {{$deskripsi}}
                                </div>
                                <dl class="small m-t-md">
                                    <dt>Keahlian</dt>
                                    <dd>{{$keahlian}}</dd>
                                    <dt>Email</dt>
                                    <dd>{{$email}}</dd>
                                    <dt>No Telepon</dt>
                                    <dd>{{$no}}</dd>
                                    <dt>Website</dt>
                                    <dd>{{$website}}</dd>
                                    <dt>Alamat</dt>
                                    <dd>{{$alamat}}</dd>
                                    <br>
                                    <div id='mapid' style='width: 100%; height: 150px;'></div>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Info Rekrutmen</h5>
                        <div class="pull-right">        
                            <button type="button" class="btn btn-xs btn-primary bTambahInfo"><i class="fa fa-edit"></i> Tambah</button>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <div class="tabldataTables-examplee-responsive">
                            <table class="table table-striped table-bordered table-hover daftarInfo" id="tbInfo" >
                                <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Tanggal</th>
                                    <th>Project</th>
                                    <th>Penginfo</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Daftar Penilaian</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="tabldataTables-examplee-responsive">
                            <table class="table table-striped table-bordered table-hover daftarRating" id="tbPenilaian" >
                                <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Perating</th>
                                    <th>Project</th>
                                    <th>Rating</th>
                                    <th>tglPublish</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Tambah Info --}}
    <div class="modal fade" id="mTambahInfo" role="dialog">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Tambah Info</h4>
                </div>
                <div class="modal-body">
                    <form role="form">
                        <div class="form-group"><label>Bidang</label>
                            <select class="select2_bidang form-control" id="tBidangInfo" multiple="multiple">
                            </select>
                        </div>
                        <div class="form-group"><label>Keahlian</label>
                            <select class="select2_keahlian form-control" id="tKeahlianInfo" multiple="multiple">
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Project</label> 
                            <input type="text" id="tProjectInfo" placeholder="Project Yang Akan Dikerjakan" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Durasi</label>
                            <div class="input-group m-b">
                                <input type="number" id="tDurasi" placeholder="Durasi" class="form-control">
                                <span class="input-group-addon">bulan</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Keterangan</label>
                            <textarea class="form-control textarea-custom" rows="2" id="tKetInfo" placeholder="Keterangan"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-default" type="submit" data-dismiss="modal"><strong>Batal</strong></button>
                    <button class="btn btn btn-primary" id="bPublish" type="submit"><strong>Publish</strong></button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Detail Info --}}
    <div class="modal fade" id="mDetailInfo" role="dialog">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Detail Info <label id="idInfo" hidden></label> </h4>
                </div>
                <div class="modal-body">
                    <form role="form">
                        <div class="form-group"><label>Bidang</label>
                            <select class="dSelect2_bidang form-control" multiple="multiple">
                            </select>
                        </div>
                        <div class="form-group"><label>Keahlian</label>
                            <select class="dSelect2_keahlian form-control" multiple="multiple">
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Project</label> 
                            <input type="text" id="dProjectInfo" placeholder="Project Yang Akan Dikerjakan" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Durasi</label>
                            <div class="input-group m-b">
                                <input type="number" id="dDurasi" placeholder="Durasi" class="form-control">
                                <span class="input-group-addon">bulan</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Keterangan</label>
                            <textarea class="form-control textarea-custom" rows="2" id="dKetInfo" placeholder="Keterangan"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Penginfo</label>
                            <a class="bDetailPengguna" style="padding-left: 5px">
                                <i class="fa fa-info-circle text-info"></i>
                            </a>
                            <input type="text" id="dPenginfo" placeholder="Penginfo" class="form-control">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger pull-left" id="bHapus" type="submit"><strong>Hapus</strong></button>
                    <button class="btn btn-default" type="submit" data-dismiss="modal"><strong>Batal</strong></button>
                    <button class="btn btn btn-primary" id="bGanti" type="submit"><strong>Ganti</strong></button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Detail Penilai --}}
    <div class="modal fade" id="mDetailPenilai" role="dialog">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Detail Penilai</h4>
                </div>
                <div class="modal-body">
                    <form role="form">
                        <div class="form-group">
                            <label>Perating</label> 
                            <a class="bDetailPengguna" style="padding-left: 5px">
                                <i class="fa fa-info-circle text-info"></i>
                            </a>
                            <input type="text" placeholder="Perating" id="dPenilai" class="form-control" disabled>
                        </div>
                        <div class="form-group">
                            <label>Rating</label>
                            <div class="input-group m-b">
                                <span class="input-group-btn">
                                    <a class="btn btn-white btn-bitbucket">
                                        <i class="fa fa-star"></i>
                                    </a> 
                                </span> 
                                <input type="number" placeholder="Rating" id="dRating" class="form-control" disabled>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Project</label> 
                            <input type="text" placeholder="Project" id="dProjectRating" class="form-control" disabled>
                        </div>
                        <div class="form-group">
                            <label>Bidang</label> 
                            <input type="text" placeholder="Bidang" id="dBidangRating" class="form-control" disabled>
                        </div>
                        <div class="form-group">
                            <label>Keahlian</label> 
                            <input type="text" placeholder="Keahlian" id="dKeahlianRating" class="form-control" disabled>
                        </div>
                        <div class="form-group">
                            <label>Keterangan</label>
                            <textarea class="form-control textarea-custom" rows="2" id="dKetRating" placeholder="Keterangan" disabled></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-default" type="submit" data-dismiss="modal"><strong>Back</strong></button>
                    <button class="btn btn-danger" id="bHide" type="submit"><strong>Hide</strong></button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Detail Pengguna --}}
    <div class="modal fade" id="mDetailPengguna" role="dialog">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Detail Pengguna</h4>
                </div>
                <div class="modal-body">
                    <form role="form">
                        <div class="form-group">
                            <label>Foto</label> 
                            <img class="img-responsive" src="https://www.google.co.id/images/branding/googlelogo/1x/googlelogo_color_272x92dp.png" alt="Chania">
                        </div>
                        <div class="form-group">
                            <label>Nama</label> 
                            <input type="text" placeholder="Nama" class="form-control" disabled>
                        </div>
                        <div class="form-group">
                            <label>Angkatan</label>
                            <input type="text" placeholder="Angkatan" class="form-control" disabled>
                        </div>
                        <div class="form-group">
                            <label>Bidang</label>
                            <textarea class="form-control textarea-custom" rows="2" id="comment" placeholder="Keterangan" disabled></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-default" type="submit" data-dismiss="modal"><strong>Close</strong></button>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        var header = {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')};
        var idTempat = '{{$id}}';
        var idLogin = '{{$idLogin}}';
        var mymap = L.map('mapid');
        var lg = new L.LayerGroup().addTo(mymap);
        var lat = {{$lat}};
        var lng = {{$lng}};
        var tabelRating;
        var tabelInfo;
        
        $(document).ready(function(){
            if({{$status}} == 0){
                $('.lStatus').addClass("label-default").text('non-active');
            }else if({{$status}} == 1){
                $('.lStatus').addClass("label-success").text('active');
            }

            getInfoTempat();
            getRatingTempat();  
            
        });

        function getInfoTempat(){
            tabelInfo = $('.daftarInfo').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                order: [[ 1, 'desc']],
                bDestroy : true,
                bAutoWidth : false,
                bLengthChange: false,
                bFilter: false,
                pageLength : 3,
                ajax: {
                    'headers' : header,
                    'type' : 'POST',
                    'dataType' : 'json',
                    'url' : '/listInfo',
                    'data' : {idTempat: idTempat}
                },
                columns : [
                    {'data': 'id'},
                    {'data': 'tglPublish'},
                    {'data': 'project'},
                    {'data': 'penginfo'}
                ],
                columnDefs: [{
                    'targets': [0],
                    'visible': false
                }]
            });
        };

        function getRatingTempat()  {
            tabelRating = $('.daftarRating').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                order: [[ 4, 'desc']],
                bDestroy : true,
                bAutoWidth : false,
                bLengthChange: false,
                bFilter: false,
                pageLength : 3,
                ajax: {
                    'headers' : header,
                    'type' : 'POST',
                    'dataType' : 'json',
                    'url' : '/listRating',
                    'data' : {idTempat: idTempat}
                },
                columns : [
                    {'data': 'id'},
                    {'data': 'penilai'},
                    {'data': 'project',
                        'render': function(data, type, row){
                            return data.length > 35 ?
                            data.substr(0, 35) + '...' :data;}
                    },
                    {'data': 'rating'},
                    {'data': 'tglPublish'},
                ],
                columnDefs: [{
                    'targets': [0, 4],
                    'visible': false
                }]
            });
        };

        $('#tbPenilaian tbody').on('click', 'tr', function(){
            var id = tabelRating.row( this ).data().id;
            $.ajax({
                headers : header,
                type : 'GET',
                dataType : 'json',
                url : '/rating/' + id,
                success:function(data){
                    $('#mDetailPenilai').modal("show");
                    document.getElementById('dPenilai').value = data.detail.penilai;
                    document.getElementById('dRating').value = data.detail.rating;
                    document.getElementById('dProjectRating').value = data.detail.project;
                    document.getElementById('dBidangRating').value = data.detail.bidang;
                    document.getElementById('dKeahlianRating').value = data.detail.keahlian;
                    document.getElementById('dKetRating').value = data.detail.keterangan;
                }
            });
        });

        $('#tbInfo tbody').on('click',  'tr', function(){
            var id = tabelInfo.row( this ).data().id;
            $.ajax({
                headers : header,
                type : 'GET',
                dataType : 'json',
                url : '/info/' + id,
                success:function(data){
                    document.getElementById('idInfo').innerHTML = id;
                    document.getElementById('dProjectInfo').value = data.detail.project;
                    document.getElementById('dDurasi').value = data.detail.durasi;
                    document.getElementById('dKetInfo').value = data.detail.keterangan;
                    document.getElementById('dPenginfo').value = data.detail.pembagi;
                    var bidang = data.detail.bidang;
                    var keahlian = data.detail.keahlian;
                    
                    $.each(bidang, function(i, item){
                        var option = new Option(bidang[i].bidang, bidang[i].idBidang, false, bidang[i].selected);
                        $('.dSelect2_bidang').append(option).trigger('change');
                    });

                    $.each(keahlian, function(i, item){
                        var option = new Option(keahlian[i].keahlian, keahlian[i].idKeahlian, false, keahlian[i].selected);
                        $('.dSelect2_keahlian').append(option).trigger('change');
                    })

                    $('#mDetailInfo').modal("show");
                    $('#mDetailInfo').on("shown.bs.modal", function(){    
                        console.log('shown');
                    });

                    $('#mDetailInfo').on("hidden.bs.modal", function(){
                        console.log('hidden');
                        $('.dSelect2_bidang').empty().trigger('change');
                        $('.dSelect2_bidang').val(null).trigger('change');
                        $('.dSelect2_keahlian').empty().trigger('change');
                        $('.dSelect2_keahlian').val(null).trigger('change');
                    });
                }
            });

        });

        mymap.setView([lat, lng], 15);
        L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1Ijoicm9iaTAyNyIsImEiOiJjam02NGQ0YmkxNnJyM3BwN2FrMHQ2ZXg3In0.VGYNiP-1I4aPACHEkehV9A', {
            maxZoom: 18,
            id: 'mapbox.streets',
            accessToken: 'pk.eyJ1Ijoicm9iaTAyNyIsImEiOiJjam02NGQ0YmkxNnJyM3BwN2FrMHQ2ZXg3In0.VGYNiP-1I4aPACHEkehV9A'
        }).addTo(mymap);
        var marker = L.marker([lat, lng]).addTo(mymap);

        $(".select2_bidang").select2({
            placeholder: "Pilih Bidang"
        });

        $(".select2_keahlian").select2({
            placeholder: "Pilih Keahlian"
        });

        $(".dSelect2_bidang").select2({
            placeholder: "Pilih Bidang"
        });

        $(".dSelect2_keahlian").select2({
            placeholder: "Pilih Keahlian"
        });

        $('.bTambahInfo').on('click', function(){
            $('#mTambahInfo').modal("show");

            var bidang = $.ajax({
                    headers : header,
                    type : 'GET',
                    dataType : 'json',
                    url : '/api/bidang',
                    success:function(data){}
                });

            var keahlian = $.ajax({
                headers : header,
                type : 'GET',
                dataType : 'json',
                url : '/api/keahlian',
                success:function(data){}
            });

            $.when(bidang, keahlian).done(function(data1, data2){
                var listBidang = data1[0].data;
                var listKeahlian = data2[0].data;
                $.each(listBidang, function(i, item){
                    var option = new Option(listBidang[i].bidang, listBidang[i].id, false, false);
                    $('.select2_bidang').append(option).trigger('change');
                });
                $.each(listKeahlian, function(i, item){
                    var option = new Option(listKeahlian[i].keahlian, listKeahlian[i].id, false, false);
                    $('.select2_keahlian').append(option).trigger('change');
                });
            });

            $('#mTambahInfo').on('shown.bs.modal', function(){
                console.log("show");
            });

            $('#mTambahInfo').on('hidden.bs.modal', function(){
                $(this).find('form').trigger('reset');
                $('.select2_bidang').empty().trigger('change');
                $('.select2_bidang').val(null).trigger('change');
                $('.select2_keahlian').empty().trigger('change');
                $('.select2_keahlian').val(null).trigger('change');
            });
        });

        $('.bDetailPengguna').on('click', function(){
            $('#mDetailPengguna').modal("show");
        });

        $('#bPublish').on('click', function(){
           
           var project = document.getElementById('tProjectInfo').value;
           var durasi = document.getElementById('tDurasi').value;
           var keterangan = document.getElementById('tKetInfo').value;
           
           var bidangSelected = $('.select2_bidang').val();
           var keahlianSelected = $('.select2_keahlian').val();

           $.ajax({
                headers : header,
                type : 'POST',
                dataType : 'json',
                contentType : 'application/x-www-form-urlencoded; charset=UTF-8',
                url : '/api/info',
                data : {idTempat:idTempat, bidang:bidangSelected, keahlian:keahlianSelected,
                project:project, durasi:durasi, keterangan:keterangan ,idUser:idLogin},
                success:function(data){
                    console.log(data);
                    $('#mTambahInfo').modal("hide");
                    tabelInfo.ajax.reload(null, false)
                }
           });
           
        });

        $('#bGanti').on('click', function(){
            var idInfo = document.getElementById('idInfo').innerHTML;
            var project = document.getElementById('dProjectInfo').value;
            var durasi = document.getElementById('dDurasi').value;
            var keterangan = document.getElementById('dKetInfo').value;
            
            var bidangSelected = $('.dSelect2_bidang').val();
            var keahlianSelected = $('.dSelect2_keahlian').val();

            $.ajax({
                headers : header,
                type : 'POST',
                dataType : 'json',
                contentType : 'application/x-www-form-urlencoded; charset=UTF-8',
                url : '/api/info',
                data : {_method:'PUT', id:idInfo, bidang:bidangSelected, 
                keahlian:keahlianSelected, project:project, durasi:durasi, 
                keterangan:keterangan ,idUser:idLogin},
                success:function(data){
                    $('#mDetailInfo').modal("hide");
                    tabelInfo.ajax.reload(null, false);
                }
           });
        });

        $('#bHapus').on('click', function(){
            var idInfo = document.getElementById('idInfo').innerHTML;
            $.ajax({
                headers : header,
                type : 'POST',
                dataType : 'json',
                contentType : 'application/x-www-form-urlencoded; charset=UTF-8',
                url : '/api/info/' + idInfo,
                data : {_method:'DELETE'},
                success:function(data){
                    $('#mDetailInfo').modal("hide");
                    tabelInfo.ajax.reload(null, false);
                }
            });
            
        });

        $('#bHide').on('click', function(){
            console.log('Click');
        });
    </script>
@endsection
        
