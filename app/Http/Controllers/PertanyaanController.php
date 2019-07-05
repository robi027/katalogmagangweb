<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Pertanyaan;
use App\Tempat;
use App\Chat;

class PertanyaanController extends Controller
{
    public function indexPertanyaanMahasiswa(){

        $login = Auth::user()->nama;
        $idLogin = Auth::user()->id;

        $lastestPertanyaan = Pertanyaan::select('idChat', DB::raw('MAX(tglRecord) as last_tglRecord'))
        ->where(function($query){
            $query->whereNotIn('idPenerima', ["US2407AC"])->whereNotIn('idPengirim', ["US2407AC"]);
        })
        ->groupBy('idChat');

        $queryPertanyaan = Pertanyaan::select('pertanyaan.idChat','pertanyaan.id as idPertanyaan',
        'project', 'isi', 'tglRecord', 'idPengirim', 'pengguna.nama as namaPengirim',
        'idPenerima', 'idTempat', 'pengguna.foto')
        ->orderBy('tglRecord', 'DESC')
        ->leftJoin('chat', 'chat.id', '=', 'pertanyaan.idChat')
        ->leftJoin('pengguna', 'pengguna.id', '=', 'pertanyaan.idPengirim')
        ->joinSub($lastestPertanyaan, 'last_Record', function($join){
            $join->on('pertanyaan.tglRecord', 'last_Record.last_tglRecord');
        })     
        ->where('idPenerima', $idLogin)
        ->orWhere('idPengirim', $idLogin)
        ->groupBy('pertanyaan.idChat')
        ->paginate(5);

        return view('admin.pertanyaanmahasiswa', ['login' => $login, 'idLogin' => $idLogin, 
        'pertanyaan' => $queryPertanyaan]);
    }

    public function getMorePertanyaanMahasiswa(Request $request){
        $idLogin = Auth::user()->id;

        if($request->ajax()){

            $lastestPertanyaan = Pertanyaan::select('idChat', DB::raw('MAX(tglRecord) as last_tglRecord'))
            ->where(function($query){
                $query->whereNotIn('idPenerima', ["US2407AC"])->whereNotIn('idPengirim', ["US2407AC"]);
            })
            ->groupBy('idChat');
            
            $queryPertanyaan = Pertanyaan::select('pertanyaan.idChat','pertanyaan.id as idPertanyaan',
            'project', 'isi', 'tglRecord', 'idPengirim', 'pengguna.nama as namaPengirim',
            'idPenerima', 'idTempat', 'pengguna.foto')
            ->orderBy('tglRecord', 'DESC')
            ->leftJoin('chat', 'chat.id', '=', 'pertanyaan.idChat')
            ->leftJoin('pengguna', 'pengguna.id', '=', 'pertanyaan.idPengirim')
            ->joinSub($lastestPertanyaan, 'last_Record', function($join){
                $join->on('pertanyaan.tglRecord', 'last_Record.last_tglRecord');
            })
            ->where('idPenerima', $idLogin)
            ->orWhere('idPengirim', $idLogin)
            ->groupBy('pertanyaan.idChat')
            ->paginate(5);   

            return view('admin.listpertanyaan', ['pertanyaan' => $queryPertanyaan])->render();
        }
        
    }

    public function indexPertanyaanPJ(){
        $login = Auth::user()->nama;
        $idLogin = Auth::user()->id;

        $lastestPertanyaan = Pertanyaan::select('idChat', DB::raw('MAX(tglRecord) as last_tglRecord'))
        ->groupBy('idChat');

        $queryPertanyaan = Pertanyaan::select('pertanyaan.idChat','pertanyaan.id as idPertanyaan',
        'project', 'isi', 'tglRecord', 'idPengirim', 'pengguna.nama as namaPengirim',
        'idPenerima', 'idTempat', 'pengguna.foto')
        ->orderBy('tglRecord', 'DESC')
        ->leftJoin('chat', 'chat.id', '=', 'pertanyaan.idChat')
        ->leftJoin('pengguna', 'pengguna.id', '=', 'pertanyaan.idPengirim')
        ->joinSub($lastestPertanyaan, 'last_Record', function($join){
            $join->on('pertanyaan.tglRecord', 'last_Record.last_tglRecord');
        })
        ->where([['idPengirim' , '=',$idLogin],['idPenerima', '=', "US2407AC"]])
        ->orWhere([['idPengirim' , '=', "US2407AC"],['idPenerima', '=', $idLogin]])
        ->groupBy('pertanyaan.idChat')
        ->paginate(5);

        return view('admin.pertanyaanpj', ['login' => $login, 'idLogin' => $idLogin, 
        'pertanyaan' => $queryPertanyaan]);
    }

    public function getMorePertanyaanPJ(Request $request){
        $idLogin = Auth::user()->id;
        if($request->ajax()){

            $lastestPertanyaan = Pertanyaan::select('idChat', DB::raw('MAX(tglRecord) as last_tglRecord'))
            ->groupBy('idChat');
            
            $queryPertanyaan = Pertanyaan::select('pertanyaan.idChat','pertanyaan.id as idPertanyaan',
            'project', 'isi', 'tglRecord', 'idPengirim', 'pengguna.nama as namaPengirim',
            'idPenerima', 'idTempat', 'pengguna.foto')
            ->orderBy('tglRecord', 'DESC')
            ->leftJoin('chat', 'chat.id', '=', 'pertanyaan.idChat')
            ->leftJoin('pengguna', 'pengguna.id', '=', 'pertanyaan.idPengirim')
            ->joinSub($lastestPertanyaan, 'last_Record', function($join){
                $join->on('pertanyaan.tglRecord', 'last_Record.last_tglRecord');
            })
            ->where([['idPengirim' , '=',$idLogin],['idPenerima', '=', "US2407AC"]])
            ->orWhere([['idPengirim' , '=', "US2407AC"],['idPenerima', '=', $idLogin]])
            ->groupBy('pertanyaan.idChat')
            ->paginate(5);   

            return view('admin.listpertanyaan', ['pertanyaan' => $queryPertanyaan])->render();
        }
    }

    public function detailPertanyaanAdmin($idChat){
        $response = array();
        $dataChat = array();

        $queryPertanyaan = Pertanyaan::select('pertanyaan.idChat','pertanyaan.id as idPertanyaan',
            'project', 'isi', 'tglRecord', 'idPengirim', 'pengguna.nama as namaPengirim',
            'idPenerima', 'chat.idTempat')
            ->orderBy('tglRecord', 'DESC')
            ->leftJoin('chat', 'chat.id', '=', 'pertanyaan.idChat')
            ->leftJoin('pengguna', 'pengguna.id', '=', 'pertanyaan.idPengirim')
            ->where('idChat', $idChat)->get();

        $idTempat = $queryPertanyaan[0]->idTempat;
        $project = $queryPertanyaan[0]->project;

        $queryTempat = Tempat::select('tempat.nama', 'tipe.tipe')
        ->selectRaw('GROUP_CONCAT(DISTINCT bidang.bidang SEPARATOR ", ") as bidang')
        ->leftJoin('tipe', 'tipe.id', '=', 'tempat.idTipe')
        ->leftJoin('tempatbidang', 'tempatbidang.idTempat', '=', 'tempat.id')
        ->leftJoin('bidang', 'bidang.id', '=', 'tempatbidang.idBidang')
        ->where('tempat.id', $idTempat)->groupBy('tempat.id')
        ->get();

        $queryChat = Pertanyaan::select('pertanyaan.idChat',
            'project', 'isi', 'tglRecord', 'idPengirim', 'idPenerima', 'chat.idTempat')
            ->orderBy('tglRecord', 'ASC')
            ->leftJoin('chat', 'chat.id', '=', 'pertanyaan.idChat')
            ->where('idChat', $idChat)->first();

        foreach($queryTempat as $iTempat){
            $dataChat['idTempat'] = $idTempat;
            $dataChat['namaTempat'] = '(' . $iTempat->tipe .  ') '. $iTempat->nama;
            $dataChat['project'] = $project;
            $dataChat['bidang'] = $iTempat->bidang;
        }
       
        $dataChat = (object) $dataChat;
        
        return view('admin.detailpertanyaan', ['listPertanyaan' => $queryPertanyaan, 
        'dataChat' => $dataChat, 'idPengirim' => $queryChat->idPengirim, 
        'idPenerima' => $queryChat->idPenerima])->render();
    }

    public function indexPertanyaan(){
        $login = Auth::user()->nama;
        $idLogin = Auth::user()->id;

        $lastestPertanyaan = Pertanyaan::select('idChat', DB::raw('MAX(tglRecord) as last_tglRecord'))
        ->groupBy('idChat');

        $queryPertanyaan = Pertanyaan::select('pertanyaan.idChat','pertanyaan.id as idPertanyaan',
        'project', 'isi', 'tglRecord', 'idPengirim', 'pengguna.nama as namaPengirim',
        'idPenerima', 'idTempat', 'pengguna.foto')
        ->orderBy('tglRecord', 'DESC')
        ->leftJoin('chat', 'chat.id', '=', 'pertanyaan.idChat')
        ->leftJoin('pengguna', 'pengguna.id', '=', 'pertanyaan.idPengirim')
        ->joinSub($lastestPertanyaan, 'last_Record', function($join){
            $join->on('pertanyaan.tglRecord', 'last_Record.last_tglRecord');
        })
        ->where('idPenerima', $idLogin)
        ->orWhere('idPengirim', $idLogin)
        ->groupBy('pertanyaan.idChat')
        ->paginate(5);

        return view('pj.pertanyaan', ['login' => $login, 'idLogin' => $idLogin, 
        'pertanyaan' => $queryPertanyaan]);
    }

    public function getMorePertanyaan(Request $request){
        $idLogin = Auth::user()->id;
        if($request->ajax()){

            $lastestPertanyaan = Pertanyaan::select('idChat', DB::raw('MAX(tglRecord) as last_tglRecord'))
            ->groupBy('idChat');
            
            $queryPertanyaan = Pertanyaan::select('pertanyaan.idChat','pertanyaan.id as idPertanyaan',
            'project', 'isi', 'tglRecord', 'idPengirim', 'pengguna.nama as namaPengirim',
            'idPenerima', 'idTempat', 'pengguna.foto')
            ->orderBy('tglRecord', 'DESC')
            ->leftJoin('chat', 'chat.id', '=', 'pertanyaan.idChat')
            ->leftJoin('pengguna', 'pengguna.id', '=', 'pertanyaan.idPengirim')
            ->joinSub($lastestPertanyaan, 'last_Record', function($join){
                $join->on('pertanyaan.tglRecord', 'last_Record.last_tglRecord');
            })
            ->where('idPenerima', $idLogin)
            ->orWhere('idPengirim', $idLogin)
            ->groupBy('pertanyaan.idChat')
            ->paginate(5);   

            return view('pj.listpertanyaan', ['pertanyaan' => $queryPertanyaan])->render();
        }
    }

    public function detailPertanyaanPJ($idChat){
        $response = array();
        $dataChat = array();

        $queryPertanyaan = Pertanyaan::select('pertanyaan.idChat','pertanyaan.id as idPertanyaan',
            'project', 'isi', 'tglRecord', 'idPengirim', 'pengguna.nama as namaPengirim',
            'idPenerima', 'chat.idTempat')
            ->orderBy('tglRecord', 'DESC')
            ->leftJoin('chat', 'chat.id', '=', 'pertanyaan.idChat')
            ->leftJoin('pengguna', 'pengguna.id', '=', 'pertanyaan.idPengirim')
            ->where('idChat', $idChat)->get();

        $idTempat = $queryPertanyaan[0]->idTempat;
        $project = $queryPertanyaan[0]->project;

        $queryTempat = Tempat::select('tempat.nama', 'tipe.tipe')
        ->selectRaw('GROUP_CONCAT(DISTINCT bidang.bidang SEPARATOR ", ") as bidang')
        ->leftJoin('tipe', 'tipe.id', '=', 'tempat.idTipe')
        ->leftJoin('tempatbidang', 'tempatbidang.idTempat', '=', 'tempat.id')
        ->leftJoin('bidang', 'bidang.id', '=', 'tempatbidang.idBidang')
        ->where('tempat.id', $idTempat)->groupBy('tempat.id')
        ->get();

        $queryChat = Pertanyaan::select('pertanyaan.idChat',
            'project', 'isi', 'tglRecord', 'idPengirim', 'idPenerima', 'chat.idTempat')
            ->orderBy('tglRecord', 'ASC')
            ->leftJoin('chat', 'chat.id', '=', 'pertanyaan.idChat')
            ->where('idChat', $idChat)->first();

        foreach($queryTempat as $iTempat){
            $dataChat['idTempat'] = $idTempat;
            $dataChat['namaTempat'] = '(' . $iTempat->tipe .  ') '. $iTempat->nama;
            $dataChat['project'] = $project;
            $dataChat['bidang'] = $iTempat->bidang;
        }
       
        $dataChat = (object) $dataChat;
        
        return view('pj.detailpertanyaan', ['listPertanyaan' => $queryPertanyaan, 
        'dataChat' => $dataChat, 'idPengirim' => $queryChat->idPengirim, 
        'idPenerima' => $queryChat->idPenerima])->render();
    }
}
