@foreach($pertanyaan as $iPertanyaan)
<div class="feed-element" id="{{$iPertanyaan->idChat}}">
    <a class="pull-left">
        @if(!empty($iPertanyaan->foto))
        <img alt="image" class="img-circle" src="{{url('img/pengguna')}}/{{$iPertanyaan->foto}}">
        @else
        <img alt="image" class="img-circle" src="{{url('img/logo.png')}}">
        @endif
    </a>
    <div class="media-body mediatext-custom">
        <small class="text-muted pull-right"><i class="fa fa-clock-o"></i> {{$iPertanyaan->tglRecord}}</small>
        <strong class="pengirim" id="{{$iPertanyaan->idPengirim}}">{{$iPertanyaan->namaPengirim}}</strong><br>
        <small class="text-muted">{{$iPertanyaan->isi}}</small>
    </div>
</div>
@endforeach
<div style="text-align: center">
    {{$pertanyaan->links()}}
</div>

