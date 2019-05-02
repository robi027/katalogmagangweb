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
                            <a href="{{url('tambah-tempat')}}"><button type="button" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> Edit</button></a>    
                        </div>
                    </div>
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="product-images">
                                    <img class="img-tempat-tabel" src="https://www.google.co.id/images/branding/googlelogo/1x/googlelogo_color_272x92dp.png"/>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <h2 class="font-bold m-b-xs">
                                    Universitas Big Data (PT) <span class="label label-success">Active</span>
                                </h2>
                                <small>Mobile Application Specialist, Mobile Application Specialist, 
                                    Mobile Application Specialist
                                </small>
                                <hr>
                                <h4>Deskripsi</h4>
                                <div class="small text-muted">
                                        UBIG.CO.ID adalah startup IT yang fokus menyediakan platform dan 
                                        konten big data dengan berbagai kategori data (harga, berita, 
                                        gaya hidup, dsb) di lebih dari 30 negara untuk didistribusikan 
                                        di berbagai website dan aplikasi mobile yang dibuat oleh partner.
                                </div>
                                <dl class="small m-t-md">
                                    <dt>Keahlian</dt>
                                    <dd>Java, Javascript</dd>
                                    <dt>Email</dt>
                                    <dd>Robi_dwisetiawan@yahoo.com</dd>
                                    <dt>No Telepon</dt>
                                    <dd>081331625837</dd>
                                    <dt>Website</dt>
                                    <dd>http://robi.com</dd>
                                    <dt>Alamat</dt>
                                    <dd>Jl. Mawar Jambe No. 13 Kalpataru - Kota Malang, Jawa Timur, Indonesia</dd>
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
                            <table class="table table-striped table-bordered table-hover dataTables-example" id="tbInfo" >
                                <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Project</th>
                                    <th>Penginfo</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr class="gradeX">
                                    <td>12-12-12</td>
                                    <td>Internet
                                        Explorer 4.0
                                    </td>
                                    <td class="center">4</td>
                                </tr>
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
                            <table class="table table-striped table-bordered table-hover dataTables-example" id="tbPenilaian" >
                                <thead>
                                <tr>
                                    <th>Perating</th>
                                    <th>Project</th>
                                    <th>Rating</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr class="gradeX">
                                    <td>Robi Dwi</td>
                                    <td>Internet
                                        Explorer 4.0
                                    </td>
                                    <td class="center">4</td>
                                </tr>
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
                            <select class="select2_bidang form-control" multiple="multiple">
                                <option value="Mayotte">Mayotte</option>
                                <option value="Mexico">Mexico</option>
                                <option value="Micronesia, Federated States of">Micronesia, Federated States of</option>
                                <option value="Moldova, Republic of">Moldova, Republic of</option>
                                <option value="Monaco">Monaco</option>
                                <option value="Mongolia">Mongolia</option>
                                <option value="Montenegro">Montenegro</option>
                            </select>
                        </div>
                        <div class="form-group"><label>Keahlian</label>
                            <select class="select2_keahlian form-control" multiple="multiple">
                                <option value="Mayotte">Mayotte</option>
                                <option value="Mexico">Mexico</option>
                                <option value="Micronesia, Federated States of">Micronesia, Federated States of</option>
                                <option value="Moldova, Republic of">Moldova, Republic of</option>
                                <option value="Monaco">Monaco</option>
                                <option value="Mongolia">Mongolia</option>
                                <option value="Montenegro">Montenegro</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Project</label> 
                            <input type="text" placeholder="Project Yang Akan Dikerjakan" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Durasi</label>
                            <div class="input-group m-b">
                                <input type="number" placeholder="Durasi" class="form-control">
                                <span class="input-group-addon">bulan</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Keterangan</label>
                            <textarea class="form-control textarea-custom" rows="2" id="comment" placeholder="Keterangan"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-default" type="submit" data-dismiss="modal"><strong>Batal</strong></button>
                    <button class="btn btn btn-primary editPesertaS" type="submit"><strong>Publish</strong></button>
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
                        <h4 class="modal-title">Detail Info</h4>
                    </div>
                    <div class="modal-body">
                        <form role="form">
                            <div class="form-group"><label>Bidang</label>
                                <select class="select2_bidang form-control" multiple="multiple">
                                    <option value="Mayotte">Mayotte</option>
                                    <option value="Mexico">Mexico</option>
                                    <option value="Micronesia, Federated States of">Micronesia, Federated States of</option>
                                    <option value="Moldova, Republic of">Moldova, Republic of</option>
                                    <option value="Monaco">Monaco</option>
                                    <option value="Mongolia">Mongolia</option>
                                    <option value="Montenegro">Montenegro</option>
                                </select>
                            </div>
                            <div class="form-group"><label>Keahlian</label>
                                <select class="select2_keahlian form-control" multiple="multiple">
                                    <option value="Mayotte">Mayotte</option>
                                    <option value="Mexico">Mexico</option>
                                    <option value="Micronesia, Federated States of">Micronesia, Federated States of</option>
                                    <option value="Moldova, Republic of">Moldova, Republic of</option>
                                    <option value="Monaco">Monaco</option>
                                    <option value="Mongolia">Mongolia</option>
                                    <option value="Montenegro">Montenegro</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Project</label> 
                                <input type="text" placeholder="Project Yang Akan Dikerjakan" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Durasi</label>
                                <div class="input-group m-b">
                                    <input type="number" placeholder="Durasi" class="form-control">
                                    <span class="input-group-addon">bulan</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Keterangan</label>
                                <textarea class="form-control textarea-custom" rows="2" id="comment" placeholder="Keterangan"></textarea>
                            </div>
                            <div class="form-group">
                                <label>Penginfo</label>
                                <a class="bDetailPengguna" style="padding-left: 5px">
                                    <i class="fa fa-info-circle text-info"></i>
                                </a>
                                <input type="text" placeholder="Penginfo" class="form-control">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-danger pull-left" type="submit" data-dismiss="modal"><strong>Hapus</strong></button>
                        <button class="btn btn-default" type="submit" data-dismiss="modal"><strong>Batal</strong></button>
                        <button class="btn btn btn-primary editPesertaS" type="submit"><strong>Ganti</strong></button>
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
                            <input type="number" placeholder="Durasi" class="form-control" disabled>
                        </div>
                        <div class="form-group">
                            <label>Rating</label>
                            <div class="input-group m-b">
                                <span class="input-group-btn">
                                    <a class="btn btn-white btn-bitbucket">
                                        <i class="fa fa-star"></i>
                                    </a> 
                                </span> 
                                <input type="number" placeholder="Rating" class="form-control" disabled>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Project</label> 
                            <input type="text" placeholder="Project" class="form-control" disabled>
                        </div>
                        <div class="form-group">
                            <label>Bidang</label> 
                            <input type="text" placeholder="Bidang" class="form-control" disabled>
                        </div>
                        <div class="form-group">
                            <label>Keahlian</label> 
                            <input type="text" placeholder="Keahlian" class="form-control" disabled>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-default" type="submit" data-dismiss="modal"><strong>Back</strong></button>
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

        var mymap = L.map('mapid');
        var lg = new L.LayerGroup().addTo(mymap);
        var lat = -7.9422017;
        var lng = 112.609622;

        $(document).ready(function(){
            $('.dataTables-example').DataTable({
                responsive: true,
                bLengthChange: false,
                bFilter: false
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

        $('.bTambahInfo').on('click', function(){
            $('#mTambahInfo').modal("show");
        });

        $('#tbPenilaian tbody').on('click', function(){
            $('#mDetailPenilai').modal("show");
        });

        $('#tbInfo tbody').on('click', function(){
            $('#mDetailInfo').modal("show");
        });

        $('.bDetailPengguna').on('click', function(){
            $('#mDetailPengguna').modal("show");
        });

    </script>
@endsection
        
