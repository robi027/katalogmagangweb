<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Tempat;
use App\Bidang;
use App\TempatBidang;
use App\Keahlian;
use App\TempatKeahlian;
use App\Tipe;
use App\Kerjasama;
use App\DetailKerjasama;
use App\TempatKontribusi;

class TempatController extends Controller
{

    public function indexDaftar(){
        $login = Auth::user()->nama;
        return view('pj.tempat', ['login' => $login]);
    }

    public function indexDetail($id){
        $login = Auth::user()->nama;
        $idLogin = Auth::user()->id;
        date_default_timezone_set("asia/jakarta");
        $tglNow = time();

        $query = Tempat::select('tempat.id', 'tipe.tipe', 'tempat.nama', 
        'tempat.foto', 'tempat.no', 'tempat.email', 'tempat.website',
        'tempat.deskripsi', 'tempat.lat', 'tempat.lng', 'tempat.alamat', 
        'tempat.status', 'tempat.tglPublish', 'tempat.tglUpdate')
        ->selectRaw('GROUP_CONCAT(DISTINCT bidang.bidang SEPARATOR ", ") as bidang')
        ->selectRaw('GROUP_CONCAT(DISTINCT keahlian.keahlian SEPARATOR ", ") as keahlian')
        ->leftJoin('tipe', 'tipe.id', '=', 'tempat.idTipe')
        ->leftJoin('tempatbidang', 'tempatbidang.idTempat', '=', 'tempat.id')
        ->leftJoin('bidang', 'bidang.id', '=', 'tempatbidang.idBidang')
        ->leftJoin('tempatkeahlian', 'tempatkeahlian.idTempat', '=', 'tempat.id')
        ->leftJoin('keahlian', 'keahlian.id', '=', 'tempatkeahlian.idKeahlian')
        ->groupBy('tempat.id')
        ->where('tempat.id', '=', $id)->get();

        $queryKerjasama = Kerjasama::select('*')
        ->leftJoin('detailkerjasama', 'detailkerjasama.idKerjasama', '=', 'kerjasama.id')
        ->where('idTempat', $id)->first();

        if($queryKerjasama){
            $kerjasama = 1;
            if($queryKerjasama->tglBerakhir > $tglNow){
                $kerjasama = 2;
            }
        }else{
            $kerjasama = 0;
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
            $tipe = $iTempat->tipe;
            $tglPublish = $iTempat->tglPublish;
            $tglUpdate = $iTempat->tglUpdate;
            $bidang = $iTempat->bidang;
            $keahlian = $iTempat->keahlian;
        }

        return view('pj.detailtempat',  ['login' => $login, 'idLogin' => $idLogin,
        'id' => $id, 'nama' => $nama, 'foto' => $foto, 'no' => $no, 
        'email' => $email, 'website' => $website, 'deskripsi' => $deskripsi, 
        'lat' => $lat, 'lng' => $lng, 'alamat' => $alamat, 'status' => $status, 
        'tipe' => $tipe, 'tglPublish' => $tglPublish, 'tglUpdate' => $tglUpdate,
        'bidang' => $bidang, 'keahlian' => $keahlian, 'kerjasama' => $kerjasama]);
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

    public function getAllKerjasama(Request $request){
        $data = array();
        date_default_timezone_set("asia/jakarta");
        $tglNow = time();
        $columns = array(
            0 => 'id',
            1 => 'nama',
            2 => 'tipe',
            3 => 'alamat',
            4 => 'bidang',
            5 => 'tglPublish',
            6 => 'status',
            7 => 'aciton'
        );

        $totalData = Tempat::select('*')
        ->join('kerjasama', 'kerjasama.idTempat', '=', 'tempat.id')->count();

        $queryDetail = DetailKerjasama::get();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');        

        if(empty($request->input('search.value'))){
            $tempat = Tempat::select('tempat.id', 'tempat.nama', 
            'tipe.tipe', 'alamat', 'tempat.tglPublish', 'kerjasama.id as idKerjasama')
            ->selectRaw('GROUP_CONCAT(DISTINCT bidang.bidang SEPARATOR ", ") as bidang')
            ->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->leftJoin('tipe', 'tipe.id', '=', 'tempat.idTipe')
            ->leftJoin('tempatbidang', 'tempatbidang.idTempat', '=', 'tempat.id')
            ->leftJoin('bidang', 'bidang.id', '=', 'tempatbidang.idBidang')
            ->join('kerjasama', 'kerjasama.idTempat', '=', 'tempat.id')
            ->groupBy('tempat.id')
            ->get();

        }else{
            $search = $request->input('search.value');

            $tempat = Tempat::select('tempat.id', 'tempat.nama', 
            'tipe.tipe', 'alamat', 'tempat.tglPublish', 'kerjasama.id as idKerjasama')
            ->selectRaw('GROUP_CONCAT(DISTINCT bidang.bidang SEPARATOR ", ") as bidang')
            ->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->leftJoin('tipe', 'tipe.id', '=', 'tempat.idTipe')
            ->leftJoin('tempatbidang', 'tempatbidang.idTempat', '=', 'tempat.id')
            ->leftJoin('bidang', 'bidang.id', '=', 'tempatbidang.idBidang')
            ->join('kerjasama', 'kerjasama.idTempat', '=', 'tempat.id')
            ->groupBy('tempat.id')
            ->where('tempat.nama', 'like', "%$search%")
            ->get();

            $totalFiltered = $tempat->count();
        }

        if(!empty($tempat)){
            foreach($tempat as $iTempat){
                $idKerjasama = $iTempat->idKerjasama;
                $status = "Pengajuan Kerjasama";
                $color = "info";
                foreach($queryDetail as $iDetail){
                    $idDetail = $iDetail->idKerjasama;
                    if($idKerjasama === $idDetail){
                        $status = "Kerjasama";
                        if($iDetail->tglBerakhir > $tglNow){
                            $status = "Kerjasama";
                            $color = "primary";
                        }else{
                            $status = "Belum di Perpanjang";
                            $color = "warning";
                        }
                    }
                }

                array_push($data, array(
                    'id' => $iTempat->id,
                    'nama' => $iTempat->nama,
                    'tipe' => $iTempat->tipe,
                    'alamat' => $iTempat->alamat,
                    'bidang' => $iTempat->bidang,
                    'tglPublish' => $iTempat->tglPublish,
                    'status' => '<p><span id="'. $iTempat->id .'" class="badge 
                    badge-'. $color .' bDetailKerjasama">'. $status .'</span></p>',
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

    public function getAllData(Request $request){

        $data = array();
        $columns = array(
            0 => 'id',
            1 => 'nama',
            2 => 'tipe',
            3 => 'alamat',
            4 => 'bidang',
            5 => 'tglUpdate',
            6 => 'action'
        );

        $totalData = Tempat::count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if(empty($request->input('search.value'))){
            $tempat = Tempat::select('tempat.id', 'tempat.nama', 
            'tipe.tipe', 'alamat', 'tempat.tglUpdate')
            ->selectRaw('GROUP_CONCAT(DISTINCT bidang.bidang SEPARATOR ", ") as bidang')
            ->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->leftJoin('tipe', 'tipe.id', '=', 'tempat.idTipe')
            ->leftJoin('tempatbidang', 'tempatbidang.idTempat', '=', 'tempat.id')
            ->leftJoin('bidang', 'bidang.id', '=', 'tempatbidang.idBidang')
            ->groupBy('tempat.id')
            ->get();

        }else{
            $search = $request->input('search.value');

            $tempat = Tempat::select('tempat.id', 'tempat.nama', 
            'tipe.tipe', 'alamat', 'tempat.tglUpdate')
            ->selectRaw('GROUP_CONCAT(DISTINCT bidang.bidang SEPARATOR ", ") as bidang')
            ->offset($start)
            ->limit($limit)
            ->orderBy($order, $dir)
            ->leftJoin('tipe', 'tipe.id', '=', 'tempat.idTipe')
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
                    'nama' => $iTempat->nama,
                    'tipe' => $iTempat->tipe,
                    'alamat' => $iTempat->alamat,
                    'bidang' => $iTempat->bidang,
                    'tglUpdate' => $iTempat->tglUpdate,
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
        date_default_timezone_set("asia/jakarta");
        $tglNow = time();
        
        $query = Tempat::select('tempat.id', 'tipe.tipe', 'tempat.nama', 
        'tempat.foto', 'tempat.no', 'tempat.email', 'tempat.website',
        'tempat.deskripsi', 'tempat.lat', 'tempat.lng', 'tempat.alamat', 
        'tempat.status', 'tempat.tglPublish', 'tempat.tglUpdate')
        ->selectRaw('GROUP_CONCAT(DISTINCT bidang.bidang SEPARATOR ", ") as bidang')
        ->selectRaw('GROUP_CONCAT(DISTINCT keahlian.keahlian SEPARATOR ", ") as keahlian')
        ->leftJoin('tipe', 'tipe.id', '=', 'tempat.idTipe')
        ->leftJoin('tempatbidang', 'tempatbidang.idTempat', '=', 'tempat.id')
        ->leftJoin('bidang', 'bidang.id', '=', 'tempatbidang.idBidang')
        ->leftJoin('tempatkeahlian', 'tempatkeahlian.idTempat', '=', 'tempat.id')
        ->leftJoin('keahlian', 'keahlian.id', '=', 'tempatkeahlian.idKeahlian')
        ->groupBy('tempat.id')
        ->where('tempat.id', '=', $id)->get();

        $queryKerjasama = Kerjasama::select('*')
        ->leftJoin('detailkerjasama', 'detailkerjasama.idKerjasama', '=', 'kerjasama.id')
        ->where('idTempat', $id)->first();

        if($queryKerjasama){
            $kerjasama = 1;
            if($queryKerjasama->tglBerakhir > $tglNow){
                $kerjasama = 2;
            }
        }else{
            $kerjasama = 0;
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
            $tipe = $iTempat->tipe;
            $tglPublish = $iTempat->tglPublish;
            $tglUpdate = $iTempat->tglUpdate;
            $bidang = $iTempat->bidang;
            $keahlian = $iTempat->keahlian;
        }

        return view('admin.reviewtempat',  ['login' => $login, 'idLogin' => $idLogin,
        'id' => $id, 'nama' => $nama, 'foto' => $foto, 'no' => $no, 
        'email' => $email, 'website' => $website, 'deskripsi' => $deskripsi, 
        'lat' => $lat, 'lng' => $lng, 'alamat' => $alamat, 'status' => $status, 
        'tipe' => $tipe, 'tglPublish' => $tglPublish, 'tglUpdate' => $tglUpdate,
        'bidang' => $bidang, 'keahlian' => $keahlian, 'kerjasama' => $kerjasama]);
    }

    public function getAllTempat(){
        $response = array();
        $data = array();
        date_default_timezone_set("asia/jakarta");
        $tglNow = time();

        $queryTempat = Tempat::select('tempat.id as id', 'tempat.nama', 'tipe.tipe')
        ->leftJoin('tipe', 'tipe.id', '=', 'tempat.idTipe')->get();

        $queryKerjasama = Kerjasama::get();

        $queryDetailKerjasama = DetailKerjasama::get();

        foreach($queryTempat as $iTempat){
            $idTempat = $iTempat->id;
            $status = 0;
            foreach($queryKerjasama as $iKerjasama){
                $idTempatK = $iKerjasama->idTempat;
                $idKerjasama = $iKerjasama->id;
                if($idTempat === $idTempatK){
                    $status = 1;
                    foreach($queryDetailKerjasama as $iDetail){
                        $idKerjasamaD = $iDetail->idKerjasama;
                        if($idKerjasama === $idKerjasamaD){
                            $status = 2;
                            if($iDetail->tglBerakhir > $tglNow){
                                $status = 2;
                            }else{
                                $status = 3;
                            }
                        }
                    }
                }
            }
            array_push($data, array(
                'id' => $iTempat->id,
                'tipe' => $iTempat->tipe,
                'nama' => $iTempat->nama,
                'status' => $status
            ));
        }

        if($queryTempat && $queryKerjasama && $queryDetailKerjasama){

            $response['error'] = FALSE;
            $response['message'] = 'All Kerjasama Tempat';
            $response['data'] = $data;

        }else{

            $response['error'] = TRUE;
            $response['message'] = 'Tempat Not Found';
        }

        return json_encode($response);
        
    }

    public function addKerjasama(Request $request){
        $response = array();
        date_default_timezone_set("asia/jakarta");
        $tglNow = time();

        $id = app(IDGeneratorController::class)->generateKerjasama();
        $idTempat = $request->input('idTempat');
        $namaPJKantor = $request->input('namaPJKantor');
        $no = $request->input('no');
        $document = "";
        $idUser = Auth::user()->id;
        $tglMulai = $request->input('tglMulai');
        $tglBerakhir = $request->input('tglBerakhir');
        $keterangan = $request->input('keterangan');

        $fieldKerjasama = array('id' => $id, 'idTempat' => $idTempat, 
        'namaPJKantor' => $namaPJKantor, 'no' => $no, 'document' => $document, 
        'tglPublish' => $tglNow, 'idUser' => $idUser);

        $fieldDetail = array('idKerjasama' => $id, 'tglMulai' => $tglMulai, 
        'tglBerakhir' => $tglBerakhir, 'keterangan' => $keterangan);

        $fieldKontribusi = array('idTempat' => $idTempat, 'idUser' => $idUser, 
        'level' => 3, 'tglJoin' => $tglNow);

        try{
            DB::beginTransaction();

            $queryKerjasama = Kerjasama::insert($fieldKerjasama);
            $queryDetail = DetailKerjasama::insert($fieldDetail);
            $queryKontribusi = TempatKontribusi::insert($fieldKontribusi);

            DB::commit();

            if($queryKerjasama && $queryDetail && $queryKontribusi){
                $response['error'] = FALSE;
                $response['message'] = 'Request successful';

            }else{
                $response['error'] = TRUE;
                $response['message'] = 'Request Failure';
            }

        }catch(Exception $e){
            DB::rollback();

            $response['error'] = TRUE;
            $response['message'] = 'Request Failure';
        }

        return json_encode($response);
    }

    public function deleteKerjasama($id){
        $response = array();

        $cek = DetailKerjasama::where('idKerjasama', $id)->count();

        if($cek > 0){

            try{
                DB::beginTransaction();

                $queryDetail = DetailKerjasama::where('idKerjasama', $id)->delete();
                $queryKerjasama = Kerjasama::where('id', $id)->delete();

                if($queryDetail && $queryDetail){
                    $response['error'] = FALSE;
                    $response['message'] = 'Kerjasama deleted successfully';
                }else{
                    $response['error'] = TRUE;
                    $response['message'] = 'Request Failure';
                }
        
                DB::commit();
            }catch(Exception $e){
                DB::rollback();

                $response['error'] = TRUE;
                $response['message'] = 'Request Failure';
            }
            
        }else{
            $response['error'] = TRUE;
            $response['message'] = 'Request Failure';
        }

        return json_encode($response);
        
    }
}