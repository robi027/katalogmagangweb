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
    public function indexDaftar(){

        $login = Auth::user()->nama;
        $idLogin = Auth::user()->id;

        $lastestPertanyaan = Pertanyaan::select('idChat', DB::raw('MAX(tglRecord) as last_tglRecord'))
        ->groupBy('idChat');

        $queryPertanyaan = Pertanyaan::select('pertanyaan.idChat','pertanyaan.id as idPertanyaan',
        'project', 'isi', 'tglRecord', 'idPengirim', 'pengguna.nama as namaPengirim',
        'idPenerima', 'idTempat')
        ->orderBy('tglRecord', 'DESC')
        ->leftJoin('chat', 'chat.id', '=', 'pertanyaan.idChat')
        ->leftJoin('pengguna', 'pengguna.id', '=', 'pertanyaan.idPengirim')
        ->joinSub($lastestPertanyaan, 'last_Record', function($join){
            $join->on('pertanyaan.tglRecord', 'last_Record.last_tglRecord');
        })
        ->groupBy('pertanyaan.idChat')
        ->paginate(5);        

        return view('admin.pertanyaan', ['login' => $login, 'idLogin' => $idLogin, 
        'pertanyaan' => $queryPertanyaan]);
    }

    public function getMorePertanyaan(Request $request){

        if($request->ajax()){

            $lastestPertanyaan = Pertanyaan::select('idChat', DB::raw('MAX(tglRecord) as last_tglRecord'))
            ->groupBy('idChat');
            
            $queryPertanyaan = Pertanyaan::select('pertanyaan.idChat','pertanyaan.id as idPertanyaan',
            'project', 'isi', 'tglRecord', 'idPengirim', 'pengguna.nama as namaPengirim',
            'idPenerima', 'idTempat')
            ->orderBy('tglRecord', 'DESC')
            ->leftJoin('chat', 'chat.id', '=', 'pertanyaan.idChat')
            ->leftJoin('pengguna', 'pengguna.id', '=', 'pertanyaan.idPengirim')
            ->joinSub($lastestPertanyaan, 'last_Record', function($join){
                $join->on('pertanyaan.tglRecord', 'last_Record.last_tglRecord');
            })
            ->groupBy('pertanyaan.idChat')
            ->paginate(5);   

            return view('admin.listpertanyaan', ['pertanyaan' => $queryPertanyaan])->render();
        }
        
    }

    public function detailPertanyaan($idChat){
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

        foreach($queryTempat as $iTempat){
            $dataChat['idTempat'] = $idTempat;
            $dataChat['namaTempat'] = '(' . $iTempat->tipe .  ') '. $iTempat->nama;
            $dataChat['project'] = $project;
            $dataChat['bidang'] = $iTempat->bidang;
            
        }
       
        $dataChat = (object) $dataChat;
        
        return view('admin.detailpertanyaan', ['listPertanyaan' => $queryPertanyaan, 
        'dataChat' => $dataChat])->render();
    }
}
