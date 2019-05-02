@extends('layouts.master')
@extends('pj.nav')
@extends('pj.heading')

@section('title', 'Tambah Tempat')



@section('contents')
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Data Tempat</h5>
                    </div>
                    <div class="ibox-content">
                        <form method="GET" class="form-horizontal">
                            <div class="row">
                                <div class="col-sm-3">
                                    <h2>Foto</h2>
                                    <div class="btn-group text-center img-pilihfoto">
                                        <img class="img-responsive" id="image-preview" src="https://www.google.co.id/images/branding/googlelogo/1x/googlelogo_color_272x92dp.png"/>
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
                                            <select class="form-control" name="account">
                                                <option>PT</option>
                                                <option>CV</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group"><label class="col-sm-2 control-label">Nama</label>
                                        <div class="col-sm-10">
                                            <input type="text" placeholder="Nama" name="nama" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group"><label class="col-sm-2 control-label">Deskripsi</label>
                                        <div class="col-sm-10">
                                            <textarea class="form-control textarea-custom" rows="3" id="comment" placeholder="Deskripsi"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group"><label class="col-sm-2 control-label">Email</label>
                                        <div class="col-sm-10">
                                            <input type="email" placeholder="Email" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group"><label class="col-sm-2 control-label">Telepon</label>
                                        <div class="col-sm-10">
                                            <input type="number" placeholder="No Telepon" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group"><label class="col-sm-2 control-label">Website</label>
                                        <div class="col-sm-10">
                                            <input type="text" placeholder="Website" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group"><label class="col-sm-2 control-label">Bidang</label>
                                        <div class="col-sm-10">
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
                                    </div>
                                    <div class="form-group"><label class="col-sm-2 control-label">Keahlian</label>
                                        <div class="col-sm-10">
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
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group col-sm-12">
                                        <label control-label>Pilih Lokasi</label>  
                                        <div id='mapid' style='width: 100%; height: 200px;'></div>
                                    </div>
                                    <div class="form-group col-sm-12">
                                        <label control-label>Alamat</label> 
                                        <textarea class="form-control textarea-custom" rows="3" id="alamat" placeholder="Alamat"></textarea>
                                    </div>
                                    <div class="form-group col-sm-12">
                                        <button class="btn btn-block btn-primary" type="submit">Publish</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        
        var mymap = L.map('mapid');
        var lg = new L.LayerGroup().addTo(mymap);
        var lat = -7.9422017;
        var lng = 112.609622;
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

            geocoder.reverse(latlng, mymap.options.crs.scale(mymap.getZoom()), function(e){
                console.log(e);
                $('#alamat').val(e[0].name);
            });
            mymap.panTo(latlng);
        }
        
        var onClick = function(e){
            mymap.off('click', onClick);
            marker = L.marker(e.latlng, {draggable: true}).addTo(mymap);
            lat = e.latlng.lat;
            lng = e.latlng.lng;
            console.log(lat);
            marker.on('drag', onDrag);
        }

        mymap.on('click', onClick);

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
    </script>
@endsection
        
