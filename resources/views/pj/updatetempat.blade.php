@extends('layouts.master')
@extends('pj.nav')
@extends('pj.heading')

@section('title', 'Update Tempat')

@section('contents')
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Data Tempat</h5>
                    </div>
                    <div class="ibox-content">
                        <form role="form" class="form-horizontal">
                            <div class="row">
                                <div class="col-sm-3">
                                    <h2>Foto</h2>
                                    <div class="btn-group text-center img-pilihfoto">
                                        <img class="img-responsive" id="image-preview" src="{{$foto}}"/>
                                        <br>
                                        <div class="text-center">
                                            <label title="Upload image file" for="inputImage" class="btn btn-primary">
                                                <input type="file" accept="image/*" name="file" id="inputImage" class="hide" onchange="previewImage();">
                                                Pilih Foto
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-5">
                                    <div class="form-group"><label class="col-sm-2 control-label">Tipe</label>
                                        <div class="col-sm-10">
                                            <select class="form-control tipe" name="tipe">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group"><label class="col-sm-2 control-label">Nama</label>
                                        <div class="col-sm-10">
                                            <input type="text" id="nama" placeholder="Nama" class="form-control" value="{{$nama}}">
                                        </div>
                                    </div>
                                    <div class="form-group"><label class="col-sm-2 control-label">Deskripsi</label>
                                        <div class="col-sm-10">
                                        <textarea class="form-control textarea-custom" rows="3" id="deskripsi" placeholder="Deskripsi">{{$deskripsi}}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group"><label class="col-sm-2 control-label">Email</label>
                                        <div class="col-sm-10">
                                            <input type="email" id="email" placeholder="Email" class="form-control" value="{{$email}}">
                                        </div>
                                    </div>
                                    <div class="form-group"><label class="col-sm-2 control-label">Telepon</label>
                                        <div class="col-sm-10">
                                            <input type="number" id="no" placeholder="No Telepon" class="form-control" value="{{$no}}">
                                        </div>
                                    </div>
                                    <div class="form-group"><label class="col-sm-2 control-label">Website</label>
                                        <div class="col-sm-10">
                                            <input type="text" id="website" placeholder="Website" class="form-control" value="{{$website}}">
                                        </div>
                                    </div>
                                    <div class="form-group"><label class="col-sm-2 control-label">Bidang</label>
                                        <div class="col-sm-10">
                                            <select class="select2_bidang form-control" multiple="multiple">
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group"><label class="col-sm-2 control-label">Keahlian</label>
                                        <div class="col-sm-10">
                                            <select class="select2_keahlian form-control" multiple="multiple">
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group col-sm-12">
                                        <label control-label>Pilih Lokasi</label>  
                                        <div id='mapid' style='width: 100%; height: 200px;'></div>
                                    </div>
                                    <div class="form-group col-sm-12">
                                        <label control-label>Alamat</label> 
                                        <textarea class="form-control textarea-custom" rows="3" id="alamat" placeholder="Alamat">{{$alamat}}</textarea>
                                    </div>
                                    <div class="form-group col-sm-12">
                                        <button class="btn btn-block" id="bAktif" type="button">Loading...</button>
                                        <button class="btn btn-block btn-primary" id="bPublish" type="submit">Update</button>
                                    </div>
                                </div>
                            </div>
                        {{-- </form> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        var header = {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')};
        var mymap = L.map('mapid');
        var lg = new L.LayerGroup().addTo(mymap);
        var lat = {{$lat}};
        var lng = {{$lng}};
        var status = {{$status}};
        var idTempat = '{{$id}}';
        var marker;
        var geocoder = L.Control.Geocoder.nominatim();

        mymap.setView([lat, lng], 15);
        L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1Ijoicm9iaTAyNyIsImEiOiJjam02NGQ0YmkxNnJyM3BwN2FrMHQ2ZXg3In0.VGYNiP-1I4aPACHEkehV9A', {
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
            maxZoom: 18,
            id: 'mapbox.streets',
            accessToken: 'pk.eyJ1Ijoicm9iaTAyNyIsImEiOiJjam02NGQ0YmkxNnJyM3BwN2FrMHQ2ZXg3In0.VGYNiP-1I4aPACHEkehV9A'
        }).addTo(mymap);

        
        var onDrag = function(e){
            
            var latlng = marker.getLatLng();
            lat = latlng.lat;
            lng = latlng.lng;
            geocoder.reverse(latlng, mymap.options.crs.scale(mymap.getZoom()), function(e){
                console.log(e);
                $('#alamat').val(e[0].name);
            });
            mymap.panTo(latlng);
        };

        marker = L.marker([lat, lng], {
                draggable: true
        }).addTo(mymap);

        marker.on('drag', onDrag);

        $(".select2_bidang").select2({
            placeholder: "Pilih Bidang"
        });

        $(".select2_keahlian").select2({
            placeholder: "Pilih Keahlian"
        });

        function previewImage() {
            var oFReader = new FileReader();
            oFReader.readAsDataURL(document.getElementById("inputImage").files[0]);

            oFReader.onload = function(oFREvent) {
            document.getElementById("image-preview").src = oFREvent.target.result;
            };
        };

        $(document).ready(function(){
            '@foreach($bidang as $iBidang)'
                var option = new Option('{{$iBidang->bidang}}', '{{$iBidang->idBidang}}', false, '{{$iBidang->selected}}');
                $('.select2_bidang').append(option).trigger('change');
            '@endforeach'

            '@foreach($keahlian as $iKeahlian)'
                var option = new Option('{{$iKeahlian->keahlian}}', '{{$iKeahlian->idKeahlian}}', false, '{{$iKeahlian->selected}}');
                $('.select2_keahlian').append(option).trigger('change');
            '@endforeach'

            '@foreach($tipe as $iTipe)'
                var option = new Option('{{$iTipe->tipe}}', '{{$iTipe->idTipe}}', false, false);
                $('.tipe').append(option).trigger('change').val('{{$idTipe}}');                                
            '@endforeach'

            if({{$status}} == 0){
                $('#bAktif').addClass("btn-primary").text('active');
            }else if({{$status}} == 1){
                $('#bAktif').removeClass("btn-primary").text('non-active');
            }
        });

        $('#bAktif').on('click', function(){
            if(status == 0){
                status = 1;
                $('#bAktif').removeClass("btn-primary").text('non-active');
                console.log(status);
            }else if(status == 1){
                status = 0;
                $('#bAktif').addClass("btn-primary").text('active');
                console.log(status);
            }
        });

        $('#bPublish').on('click', function(e){
            var bidang = $('.select2_bidang').val();
            var keahlian = $('.select2_keahlian').val();

            var formData = new FormData();
            formData.append('_method', 'PUT');
            formData.append('id', idTempat);
            formData.append('idTipe', $('.tipe').val());
            formData.append('nama', $('#nama').val());
            formData.append('deskripsi', $('#deskripsi').val());
            formData.append('no', $('#no').val());
            formData.append('email', $('#email').val());
            formData.append('website', $('#website').val());
            formData.append('lat', lat);
            formData.append('lng', lng);
            formData.append('alamat', $('#alamat').val());
            formData.append('status', status);
            formData.append('foto', $('input[type=file]')[0].files[0]);
            $.each(bidang, function(i, item){
                formData.append('bidang[]', bidang[i]);
            });
            $.each(keahlian, function(i, item){
                formData.append('keahlian[]', keahlian[i]);
            });

            $.ajax({
                headers : header,
                type : 'POST',
                dataType : 'json',
                contentType : false,
                processData : false,
                url : '/api/tempat',
                data : formData,
                success:function(data){
                    console.log(data);
                }
            });
            
        });
    </script>
@endsection
        
