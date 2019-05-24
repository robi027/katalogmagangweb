<div class="ibox float-e-margins boxDetail">
    <div class="ibox-title">
        <h5>Detail Pertanyaan</h5>
        <div class="ibox-tools">
            <a class="close-link bDel">
                <i class="fa fa-times"></i>
            </a>
        </div>
    </div>
    <div class="ibox-content ibox-heading">
        <a class="review-tempat" id="{{$dataChat->idTempat}}"><h3>{{$dataChat->namaTempat}}</h3></a>
        <small style="word-wrap: break-word">{{$dataChat->bidang}}</small><br>
        <small><i class="fa fa-map-marker"></i> {{$dataChat->project}}</small>
    </div>
    <div class="ibox-content no-padding scroll-item">
        <ul class="list-group">
            @foreach($listPertanyaan as $iList)
            <li class="list-group-item">
                <p><a class="text-info aDetailPengirim" id="{{$iList->idPengirim}}">{{$iList->namaPengirim}}</a> {{$iList->isi}}</p>
                <small class="block text-muted"><i class="fa fa-clock-o"></i> {{$iList->tglRecord}}</small>
            </li>
            @endforeach
        </ul>
    </div>
    <div class="ibox-content ibox-heading iboxfooter-custom">
        <div class="input-group">
            <input type="text" id="text-chat" class="form-control" placeholder="Text">
            <span class="input-group-btn"> 
                <button type="button" class="btn btn-primary bKirim">Kirim</button>
            </span>
        </div>
    </div>
</div>
