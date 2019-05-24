@extends('layouts.master')
@extends('admin.nav')
@extends('admin.heading')

@section('title', 'Pertanyaan')

@section('contents')
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-7">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Daftar Pertanyaan</h5>
                    </div>
                    <div class="ibox-content">
                        <div class="feed-activity-list listPertanyaan">
                            @include('admin.listpertanyaan')
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 detailPertanyaan">
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
                            <img class="img-responsive" id="fotoPengguna" src="https://www.google.co.id/images/branding/googlelogo/1x/googlelogo_color_272x92dp.png" alt="Chania">
                        </div>
                        <div class="form-group">
                            <label>Nama</label> 
                            <input type="text" id="namaPengguna" placeholder="Nama" class="form-control" disabled>
                        </div>
                        <div class="form-group">
                            <label>Bidang</label>
                            <textarea class="form-control textarea-custom" rows="2" id="bidangPengguna" placeholder="Bidang" disabled></textarea>
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
        var idChat;
        var idLogin = '{{$idLogin}}';
        var idPenerima;
        var urlDetail;

        $(document).on('click', '.pagination a', function(e){
            e.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            getPertanyaan(page);
        });

        function getPertanyaan(page){
            $.ajax({
                headers : header,
                type : 'GET',
                url : '/list-pertanyaan',
                data : {page:page},
                success:function(data){
                    $('.listPertanyaan').html(data);
                }
            });
            
        }


        $(document).on('click', '.feed-element', function(e){
            idChat = this.id;
            idPenerima = $('.pengirim').attr('id');
            $.ajax({
                headers : header,
                type : 'GET',
                url : '/detail-pertanyaan/' + idChat,
                success:function(data){
                    $('.detailPertanyaan').html(data);
                    urlDetail = this.url;
                }
            })
        });

        $(document).on('click', '.bDel', function(e){ 
            $('.boxDetail').remove();
        });

        $(document).on('click', '.aDetailPengirim', function(e){
            var idUser = this.id;
            $('#mDetailPengguna').modal("show");
            $.ajax({
                headers : header,
                type : 'GET',
                dataType : 'json',
                url : '/api/profile/' + idUser,
                success:function(data){
                    document.getElementById('namaPengguna').value = data.pengguna.nama;
                    document.getElementById('bidangPengguna').value = data.pengguna.bidang;
                }
            });
        });

        $(document).on('click', '.bKirim', function(e){
            var isi = document.getElementById('text-chat').value;
            console.log(urlDetail);
            
            $.ajax({
                headers : header,
                type : 'POST',
                dataType : 'json',
                url : '/api/jawaban',
                contentType : 'application/x-www-form-urlencoded; charset=UTF-8',
                data : {idChat:idChat, isi:isi, idPengirim:idLogin, idPenerima:idPenerima},
                success:function(data){
                    $.ajax({
                        headers : header,
                        type : 'GET',
                        url : urlDetail,
                        success:function(data){
                            $('.detailPertanyaan').html(data);
                        }
                    });
                },
            });
        });

        $(document).on('click', '.review-tempat', function(e){
            var url = window.location.origin + '/review-tempat/' + this.id;
            window.open(url, '_blank');
        })

    </script>
@endsection
        
