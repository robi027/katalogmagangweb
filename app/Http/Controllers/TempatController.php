<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Tempat;
use App\Bidang;
use App\TempatBidang;
use App\Keahlian;
use App\TempatKeahlian;
use App\Tipe;

class TempatController extends Controller
{

    public function indexDaftar(){
        $login = Auth::user()->nama;
        return view('pj.tempat', ['login' => $login]);
    }

    public function indexDetail($id){
        $login = Auth::user()->nama;
        $idLogin = Auth::user()->id;

        $query = Tempat::select('tempat.id', 'tipe.tipe', 'tempat.nama', 
        'tempat.foto', 'tempat.no', 'tempat.email', 'tempat.website',
        'tempat.deskripsi', 'tempat.lat', 'tempat.lng', 'tempat.alamat', 
        'tempat.status', 'tempat.tglPublish', 'tempat.tglUpdate', 
        'pengguna.nama as pembagi')
        ->selectRaw('GROUP_CONCAT(DISTINCT bidang.bidang SEPARATOR ", ") as bidang')
        ->selectRaw('GROUP_CONCAT(DISTINCT keahlian.keahlian SEPARATOR ", ") as keahlian')
        ->leftJoin('tipe', 'tipe.id', '=', 'tempat.idTipe')
        ->leftJoin('pengguna', 'pengguna.id', '=', 'tempat.idUser')
        ->leftJoin('tempatbidang', 'tempatbidang.idTempat', '=', 'tempat.id')
        ->leftJoin('bidang', 'bidang.id', '=', 'tempatbidang.idBidang')
        ->leftJoin('tempatkeahlian', 'tempatkeahlian.idTempat', '=', 'tempat.id')
        ->leftJoin('keahlian', 'keahlian.id', '=', 'tempatkeahlian.idKeahlian')
        ->groupBy('tempat.id')
        ->where('tempat.id', '=', $id)->get();
        
        foreach($query as $iTempat){
            $id = $iTempat->id;
            $nama = $iTempat->nama;
            $foto = url('/img/tempat') . '/' . $iTempat->foto;
            $no = $iTempat->no;
            $email = $iTempat->email;
            $website = $iTempat->website;
            $deskripsi = $iTempat->deskripsi;
            $lat = $iTempat->lat;
            $lng = $iTempat->lng;
            $alamat = $iTempat->alamat;
            $status = $iTempat->status;
            $tipe = $iTempat->tipe;
            $tglPublish = $iTempat->tglPublish;
            $tglUpdate = $iTempat->tglUpdate;
            $pembagi = $iTempat->pembagi;
            $bidang = $iTempat->bidang;
            $keahlian = $iTempat->keahlian;
        }

        return view('pj.detailtempat',  ['login' => $login, 'idLogin' => $idLogin,
        'id' => $id, 'nama' => $nama, 'foto' => $foto, 'no' => $no, 
        'email' => $email, 'website' => $website, 'deskripsi' => $deskripsi, 
        'lat' => $lat, 'lng' => $lng, 'alamat' => $alamat, 'status' => $status, 
        'tipe' => $tipe, 'tglPublish' => $tglPublish, 'tglUpdate' => $tglUpdate,
        'pembagi' => $pembagi, 'bidang' => $bidang, 'keahlian' => $keahlian]);
    }

    public function indexTambah(){
        $bidang = array();
        $keahlian = array();
        $tipe = array();

        $login = Auth::user()->nama;
        $idLogin = Auth::user()->id;


        $queryBidang = Bidang::get();
        foreach($queryBidang as $iBidang){
            array_push($bidang, (object) array(
                'idBidang' => $iBidang->id,
                'bidang' => $iBidang->bidang
            ));
        }

        $queryKeahlian = Keahlian::get();
        foreach($queryKeahlian as $iKeahlian){
            array_push($keahlian, (object) array(
                'idKeahlian' => $iKeahlian->id,
                'keahlian' => $iKeahlian->keahlian
            ));
        }

        $queryTipe = Tipe::get();
        foreach($queryTipe as $iTipe){
            array_push($tipe, (object) array(
                'idTipe' => $iTipe->id,
                'tipe' => $iTipe->tipe
            ));
        }

        return view('pj.tambahtempat',  ['login' => $login, 'idLogin' => $idLogin,
        'bidang' => $bidang, 'keahlian' => $keahlian, 'tipe' => $tipe]);
    }

    public function indexUpdate($id){
        $bidangSelected = array();
        $keahlianSelected = array();
        $tipe = array();

        $login = Auth::user()->nama;
        $idLogin = Auth::user()->id;

        $queryTipe = Tipe::get();

        $queryBidang = Bidang::get();
        $queryTempatBidang = TempatBidang::where('idTempat', $id)->get();

        $queryKeahlian = Keahlian::get();
        $queryTempatKeahlian = TempatKeahlian::where('idTempat', $id)->get();

        $query = Tempat::where('tempat.id', '=', $id)->get();
        
        foreach($queryTipe as $iTipe){
            array_push($tipe, (object) array(
                'idTipe' => $iTipe->id,
                'tipe' => $iTipe->tipe
            ));
        }

        foreach($queryBidang as $iBidang){
            $id = $iBidang->id;
            $selected = FALSE;
            foreach($queryTempatBidang as $iTempat){
                $idBidang = $iTempat->idBidang;
                if($id === $idBidang){
                    $selected = TRUE;
                }
            }
            array_push($bidangSelected, (object) array(
                'idBidang' => $id,
                'bidang' => $iBidang->bidang,
                'selected' => $selected
            ));
        }

        foreach($queryKeahlian as $iKeahlian){
            $id = $iKeahlian->id;
            $selected = FALSE;
            foreach($queryTempatKeahlian as $iTempat){
                $idKeahlian = $iTempat->idKeahlian;
                if($id === $idKeahlian){
                    $selected = TRUE;
                }
            }

            array_push($keahlianSelected, (object) array(
                'idKeahlian' => $id,
                'keahlian' => $iKeahlian->keahlian,
                'selected' => $selected
            ));
        }
        
        foreach($query as $iTempat){
            $id = $iTempat->id;
            $nama = $iTempat->nama;
            $foto = url('/img/tempat') . '/' . $iTempat->foto;
            $no = $iTempat->no;
            $email = $iTempat->email;
            $website = $iTempat->website;
            $deskripsi = $iTempat->deskripsi;
            $lat = $iTempat->lat;
            $lng = $iTempat->lng;
            $alamat = $iTempat->alamat;
            $status = $iTempat->status;
            $idTipe = $iTempat->idTipe;
            $tglPublish = $iTempat->tglPublish;
            $tglUpdate = $iTempat->tglUpdate;
            $idUser = $iTempat->idUser;
        }

        return view('pj.updatetempat', ['login' => $login, 'idLogin' => $idLogin,
        'id' => $id, 'nama' => $nama, 'foto' => $foto, 'no' => $no, 'email' => $email, 
        'website' => $website, 'deskripsi' => $deskripsi, 'lat' => $lat, 'lng' => $lng, 
        'alamat' => $alamat, 'status' => $status, 'idTipe' => $idTipe, 'idUser' => $idUser,
        'tglPublish' => $tglPublish, 'tglUpdate' => $tglUpdate, 'bidang' => $bidangSelected,
        'keahlian' => $keahlianSelected, 'tipe' => $tipe]);
    }

    public function getAllData(Request $request){

        $data = array();
        $columns = array(
            0 => 'id',
            1 => 'foto',
            2 => 'nama',
            3 => 'tipe',
            4 => 'alamat',
            5 => 'bidang',
            6 => 'pembagi',
            7 => 'tglPublish',
            8 => 'action'
        );

        $totalData = Tempat::count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value'))){
            $tempat = Tempat::select('tempat.id', 'tempat.foto', 
            'tempat.nama', 'tipe.tipe', 'alamat', 'pengguna.nama as pembagi', 'tempat.tglPublish')
            ->selectRaw('GROUP_CONCAT(DISTINCT bidang.bidang SEPARATOR ", ") as bidang')
            ->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->leftJoin('tipe', 'tipe.id', '=', 'tempat.idTipe')
            ->leftJoin('pengguna', 'pengguna.id', '=', 'tempat.idUser')
            ->leftJoin('tempatbidang', 'tempatbidang.idTempat', '=', 'tempat.id')
            ->leftJoin('bidang', 'bidang.id', '=', 'tempatbidang.idBidang')
            ->groupBy('tempat.id')
            ->get();

        }else{
            $search = $request->input('search.value');

            $tempat = Tempat::select('tempat.id', 'tempat.foto', 
            'tempat.nama', 'tipe.tipe', 'alamat', 'pengguna.nama as pembagi', 'tempat.tglPublish')
            ->selectRaw('GROUP_CONCAT(DISTINCT bidang.bidang SEPARATOR ", ") as bidang')
            ->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->leftJoin('tipe', 'tipe.id', '=', 'tempat.idTipe')
            ->leftJoin('pengguna', 'pengguna.id', '=', 'tempat.idUser')
            ->leftJoin('tempatbidang', 'tempatbidang.idTempat', '=', 'tempat.id')
            ->leftJoin('bidang', 'bidang.id', '=', 'tempatbidang.idBidang')
            ->groupBy('tempat.id')
            ->where('tempat.nama', 'like', "%$search%")
            ->get();

            $totalFiltered = $tempat->count();
        }

        if(!empty($tempat)){
            foreach($tempat as $iTempat){
                array_push($data, array(
                    'id' => $iTempat->id,
                    'foto' => $iTempat->foto,
                    'nama' => $iTempat->nama,
                    'tipe' => $iTempat->tipe,
                    'alamat' => $iTempat->alamat,
                    'bidang' => $iTempat->bidang,
                    'pembagi' => $iTempat->pembagi,
                    'tglPublish' => $iTempat->tglPublish,
                    'action' => '<button id="' . $iTempat->id . '" 
                    class="btn btn-xs btn-primary bDetailTempat" 
                    type="button"><i class="fa fa-external-link"></i> Detail</button>'
                ));
            }
        }

        $jsonData = array(
            'draw' =>intval($request->input('draw')),
            'recordsTotal' =>intval($totalData),
            'recordsFiltered' =>intval($totalFiltered),
            'data' => $data
        );

        echo json_encode($jsonData);
    }

    public function indexReview($id){
        $login = Auth::user()->nama;
        $idLogin = Auth::user()->id;

        $query = Tempat::select('tempat.id', 'tipe.tipe', 'tempat.nama', 
        'tempat.foto', 'tempat.no', 'tempat.email', 'tempat.website',
        'tempat.deskripsi', 'tempat.lat', 'tempat.lng', 'tempat.alamat', 
        'tempat.status', 'tempat.tglPublish', 'tempat.tglUpdate', 
        'pengguna.nama as pembagi')
        ->selectRaw('GROUP_CONCAT(DISTINCT bidang.bidang SEPARATOR ", ") as bidang')
        ->selectRaw('GROUP_CONCAT(DISTINCT keahlian.keahlian SEPARATOR ", ") as keahlian')
        ->leftJoin('tipe', 'tipe.id', '=', 'tempat.idTipe')
        ->leftJoin('pengguna', 'pengguna.id', '=', 'tempat.idUser')
        ->leftJoin('tempatbidang', 'tempatbidang.idTempat', '=', 'tempat.id')
        ->leftJoin('bidang', 'bidang.id', '=', 'tempatbidang.idBidang')
        ->leftJoin('tempatkeahlian', 'tempatkeahlian.idTempat', '=', 'tempat.id')
        ->leftJoin('keahlian', 'keahlian.id', '=', 'tempatkeahlian.idKeahlian')
        ->groupBy('tempat.id')
        ->where('tempat.id', '=', $id)->get();
        
        foreach($query as $iTempat){
            $id = $iTempat->id;
            $nama = $iTempat->nama;
            $foto = url('/img/tempat') . '/' . $iTempat->foto;
            $no = $iTempat->no;
            $email = $iTempat->email;
            $website = $iTempat->website;
            $deskripsi = $iTempat->deskripsi;
            $lat = $iTempat->lat;
            $lng = $iTempat->lng;
            $alamat = $iTempat->alamat;
            $status = $iTempat->status;
            $tipe = $iTempat->tipe;
            $tglPublish = $iTempat->tglPublish;
            $tglUpdate = $iTempat->tglUpdate;
            $pembagi = $iTempat->pembagi;
            $bidang = $iTempat->bidang;
            $keahlian = $iTempat->keahlian;
        }

        return view('admin.reviewtempat',  ['login' => $login, 'idLogin' => $idLogin,
        'id' => $id, 'nama' => $nama, 'foto' => $foto, 'no' => $no, 
        'email' => $email, 'website' => $website, 'deskripsi' => $deskripsi, 
        'lat' => $lat, 'lng' => $lng, 'alamat' => $alamat, 'status' => $status, 
        'tipe' => $tipe, 'tglPublish' => $tglPublish, 'tglUpdate' => $tglUpdate,
        'pembagi' => $pembagi, 'bidang' => $bidang, 'keahlian' => $keahlian]);
    }

}

