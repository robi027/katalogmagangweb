<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use App\Info;
Use App\InfoBidang;
Use App\InfoKeahlian;
Use App\Tempat;
Use App\TempatKontribusi;

class APIInfoController extends Controller
{
    public function getInfoTempat($idTempat){
        $response = array();
        $data = array();
        date_default_timezone_set("asia/jakarta");
        $tglNow = time();

        $query = Info::select('info.id', 'info.durasi', 'info.project',
        'info.keterangan', 'info.tglPublish', 'info.tglBerakhir', 
        'pengguna.nama as pembagi')
        ->selectRaw('GROUP_CONCAT(DISTINCT bidang.bidang SEPARATOR ", ") as bidang')
        ->selectRaw('GROUP_CONCAT(DISTINCT keahlian.keahlian SEPARATOR ", ") as keahlian')
        ->leftJoin('pengguna', 'pengguna.id', '=', 'info.idUser')
        ->leftJoin('infobidang', 'infobidang.idInfo', '=', 'info.id')
        ->leftJoin('bidang', 'bidang.id', '=', 'infobidang.idBidang')
        ->leftJoin('infokeahlian', 'infokeahlian.idInfo', '=', 'info.id')
        ->leftJoin('keahlian', 'keahlian.id', '=', 'infokeahlian.idKeahlian')
        ->orderBy('info.tglBerakhir', 'DESC')
        ->where('idTempat', $idTempat)->groupBy('info.id');

        if($query->count() > 0){
            foreach($query->get() as $item){
                if($item->tglBerakhir > $tglNow){
                    array_push($data, array(
                        'id' => $item->id,
                        'durasi' => $item->durasi . ' bulan',
                        'project' => $item->project,
                        'keterangan' => $item->keterangan,
                        'tglPublish' => $item->tglPublish,
                        'tglBerakhir' => $item->tglBerakhir,
                        'pembagi' => $item->pembagi,
                        'bidang' => $item->bidang,
                        'keahlian' => $item->keahlian,
                    ));
                }
            }
    
            $response['error'] = FALSE;
            $response['message'] = 'All Data Info';
            $response['data'] = $data;

        }else{

            $response['error'] = TRUE;
            $response['message'] = 'Not Found Info';
        }

        return json_encode($response);
    }

    public function getInfoTempatKontribusi($idTempat){
        $response = array();
        $data = array();
        date_default_timezone_set("asia/jakarta");
        $tglNow = time();

        $query = Info::select('info.id', 'info.durasi', 'info.project',
        'info.keterangan', 'info.tglPublish', 'info.tglBerakhir', 
        'pengguna.nama as pembagi')
        ->selectRaw('GROUP_CONCAT(DISTINCT bidang.bidang SEPARATOR ", ") as bidang')
        ->selectRaw('GROUP_CONCAT(DISTINCT keahlian.keahlian SEPARATOR ", ") as keahlian')
        ->leftJoin('pengguna', 'pengguna.id', '=', 'info.idUser')
        ->leftJoin('infobidang', 'infobidang.idInfo', '=', 'info.id')
        ->leftJoin('bidang', 'bidang.id', '=', 'infobidang.idBidang')
        ->leftJoin('infokeahlian', 'infokeahlian.idInfo', '=', 'info.id')
        ->leftJoin('keahlian', 'keahlian.id', '=', 'infokeahlian.idKeahlian')
        ->orderBy('info.tglBerakhir', 'DESC')
        ->where('idTempat', $idTempat)->groupBy('info.id');

        if($query->count() > 0){
            foreach($query->get() as $item){
                array_push($data, array(
                    'id' => $item->id,
                    'durasi' => $item->durasi . ' bulan',
                    'project' => $item->project,
                    'keterangan' => $item->keterangan,
                    'tglPublish' => $item->tglPublish,
                    'tglBerakhir' => $item->tglBerakhir,
                    'pembagi' => $item->pembagi,
                    'bidang' => $item->bidang,
                    'keahlian' => $item->keahlian,
                ));
            }
    
            $response['error'] = FALSE;
            $response['message'] = 'All Data Info';
            $response['data'] = $data;

        }else{

            $response['error'] = TRUE;
            $response['message'] = 'Not Found Info';
        }

        return json_encode($response);
    }

    public function getDetailInfo($idInfo){
        $response = array();
        date_default_timezone_set("asia/jakarta");
        $tglNow = time();

        $query = Info::select('info.id', 'info.durasi', 'info.project',
        'info.keterangan', 'info.tglPublish', 'info.tglBerakhir', 'info.idUser as idPembagi', 
        'pengguna.nama as pembagi')
        ->selectRaw('GROUP_CONCAT(DISTINCT bidang.bidang SEPARATOR ", ") as bidang')
        ->selectRaw('GROUP_CONCAT(DISTINCT keahlian.keahlian SEPARATOR ", ") as keahlian')
        ->leftJoin('pengguna', 'pengguna.id', '=', 'info.idUser')
        ->leftJoin('infobidang', 'infobidang.idInfo', '=', 'info.id')
        ->leftJoin('bidang', 'bidang.id', '=', 'infobidang.idBidang')
        ->leftJoin('infokeahlian', 'infokeahlian.idInfo', '=', 'info.id')
        ->leftJoin('keahlian', 'keahlian.id', '=', 'infokeahlian.idKeahlian')
        ->where('info.id', $idInfo);

        if($query->count() > 0){
            foreach($query->get() as $item){
                $response['error'] = FALSE;
                $response['message'] = 'All Data Info';
                $response['detail']['id'] = $item->id;
                $response['detail']['durasi'] = $item->durasi;
                $response['detail']['project'] = $item->project;
                $response['detail']['keterangan'] = $item->keterangan;
                if(empty($item->tglBerakhir)){
                    $item->tglBerakhir = date('d/m/Y', $tglNow);
                }else{
                    $item->tglBerakhir = date('d/m/Y', $item->tglBerakhir);
                }
                $response['detail']['tglPublish'] = date('d/m/Y', $item->tglPublish);
                $response['detail']['tglBerakhir'] = $item->tglBerakhir;
                $response['detail']['idPembagi'] = $item->idPembagi;
                $response['detail']['pembagi'] = $item->pembagi;
                $response['detail']['bidang'] = $item->bidang;
                $response['detail']['keahlian'] = $item->keahlian;
            }
            
        }else{

            $response['error'] = TRUE;
            $response['message'] = 'Not Found Info';
        }

        return json_encode($response);

    }

    public function addInfo(Request $request){
        $response = array();
        $fieldBidang = array();
        $fieldKeahlian = array();

        $id = app(IDGeneratorController::class)->generateInfo();
        $idTempat = $request->input('idTempat');
        $idUser = $request->input('idUser');
        $durasi = $request->input('durasi');
        $project = $request->input('project');
        $keterangan = $request->input('keterangan');
        date_default_timezone_set("asia/jakarta");
        $tglPublish = time();
        $tglBerakhir = $request->input('tglBerakhir');
        $userStatus = $request->input('userStatus');

        $bidang = json_decode(json_encode($request->input('bidang')), true);
        $keahlian = json_decode(json_encode($request->input('keahlian')), true);

        foreach($bidang as $iBidang){
            array_push($fieldBidang, array(
                'idInfo' => $id,
                'idBidang' => $iBidang
            ));
        }

        foreach($keahlian as $iKeahlian){
            array_push($fieldKeahlian, array(
                'idInfo' => $id,
                'idKeahlian' => $iKeahlian
            ));
        }

        $fieldInfo = array('id' => $id, 'durasi' => $durasi, 
        'project' => $project, 'keterangan' => $keterangan, 
        'tglPublish' => $tglPublish, 'tglBerakhir' => $tglBerakhir, 
        'idTempat' => $idTempat, 'idUser' => $idUser);

        $queryInfo = Info::insert($fieldInfo);

        if($queryInfo){
            $queryBidang = InfoBidang::insert($fieldBidang);
            $queryKeahlian = InfoKeahlian::insert($fieldKeahlian);
            $queryTempat = Tempat::where('id', $idTempat)->update(array('tglUpdate' => $tglPublish));

            if($userStatus == true){
                $queryCekKontribusi = TempatKontribusi::where([
                    ['idTempat', '=', $idTempat],
                    ['idUser', '=', $idUser]])->count();
                
                if($queryCekKontribusi < 1){
                    $queryKontribusi = TempatKontribusi::insert(array(
                        'idTempat' => $idTempat, 
                        'idUser' => $idUser,
                        'level' => 3,
                        'tglJoin' => $tglPublish
                    ));
                }
            }

        }else{

            $response['error'] = TRUE;
            $response['message'] = "Failed to publish info";
        }
        
        if($queryBidang && $queryKeahlian){
            $response['error'] = FALSE;
            $response['message'] = "info published successfully";
            
        }else{
            $response['error'] = TRUE;
            $response['message'] = "Failed to publish info";
        }

        return json_encode($response);

    }

    public function editInfo(Request $request){

        $response = array();
        $fieldBidang = array();
        $fieldKeahlian = array();

        $id = $request->input('id');
        $durasi = $request->input('durasi');
        $project = $request->input('project');
        $keterangan = $request->input('keterangan');
        $tglBerakhir = $request->input('tglBerakhir');

        $bidang = json_decode(json_encode($request->input('bidang')), true);
        $keahlian = json_decode(json_encode($request->input('keahlian')), true);  
        
        $queryInfo = false;
        $queryBidang = false;
        $queryKeahlian = false;

        if(!empty($tglBerakhir)){
            $fieldInfo = array('durasi' => $durasi, 
            'project' => $project, 'keterangan' => $keterangan, 
            'tglBerakhir' => $tglBerakhir);
        }else{
            $fieldInfo = array('durasi' => $durasi, 
            'project' => $project, 'keterangan' => $keterangan);
        }

        $queryUpdateInfo = Info::where('id', $id)->update($fieldInfo);

        if($queryUpdateInfo){
            $queryInfo = true;
        }else{
            $queryInfo = true;
        }

        if($bidang != null){

            foreach($bidang as $iBidang){
                array_push($fieldBidang, array(
                    'idInfo' => $id,
                    'idBidang' => $iBidang
                ));
            }

            $queryDeleteBidang = InfoBidang::where('idInfo', $id)->delete();

            if($queryDeleteBidang){
                $queryAddBidang = InfoBidang::insert($fieldBidang);
                if($queryAddBidang){
                    $queryBidang = true;
                }
            }
        }else{
            $queryBidang = true;
        }

        if($keahlian != null){

            foreach($keahlian as $iKeahlian){
                array_push($fieldKeahlian, array(
                    'idInfo' => $id,
                    'idKeahlian' => $iKeahlian
                ));
            }

            $queryDeleteKeahlian = InfoKeahlian::where('idInfo', $id)->delete();

            if($queryDeleteKeahlian){
                $queryAddKeahlian = InfoKeahlian::insert($fieldKeahlian);
                if($queryAddKeahlian){
                    $queryKeahlian = true;
                }
            }
        }else{
            $queryKeahlian = true;
        }
    
        if($queryInfo && $queryBidang && $queryKeahlian){
            $response['error'] = FALSE;
            $response['message'] = "info published successfully";
            
        }else{
            $response['error'] = TRUE;
            $response['message'] = "Failed to publish info";
        }

        return json_encode($response);
    }

    public function deleteInfo($id){
        $response = array();
        
        $queryDeleteKeahlian = InfoKeahlian::where('idInfo', $id)->delete();
        $queryDeleteBidang = InfoBidang::where('idInfo', $id)->delete();

        if($queryDeleteKeahlian && $queryDeleteBidang){

            $queryDeleteInfo = Info::where('id', $id)->delete();

            if($queryDeleteInfo){

                $response['error'] = FALSE;
                $response['message'] = "info deleted successfully";
                
            }else{
    
                $response['error'] = TRUE;
                $response['message'] = "Failed to delete info";
    
            }
            
        }else{
            $response['error'] = TRUE;
            $response['message'] = "Failed to delete info";
        }

        return json_encode($response);
    }

    public function getMyInfo($idUser){
        $response = array();
        $data = array();

        $query = Info::select('info.id', 'info.durasi', 'info.project',
        'info.keterangan', 'info.tglPublish', 'info.tglBerakhir', 
        'pengguna.nama as pembagi')
        ->selectRaw('GROUP_CONCAT(DISTINCT bidang.bidang SEPARATOR ", ") as bidang')
        ->selectRaw('GROUP_CONCAT(DISTINCT keahlian.keahlian SEPARATOR ", ") as keahlian')
        ->leftJoin('pengguna', 'pengguna.id', '=', 'info.idUser')
        ->leftJoin('infobidang', 'infobidang.idInfo', '=', 'info.id')
        ->leftJoin('bidang', 'bidang.id', '=', 'infobidang.idBidang')
        ->leftJoin('infokeahlian', 'infokeahlian.idInfo', '=', 'info.id')
        ->leftJoin('keahlian', 'keahlian.id', '=', 'infokeahlian.idKeahlian')
        ->where('info.idUser', $idUser)
        ->groupBy('info.id');

        if($query->count() > 0){
            foreach($query->get() as $item){
                array_push($data, array(
                    'id' => $item->id,
                    'durasi' => $item->durasi . ' bulan',
                    'project' => $item->project,
                    'keterangan' => $item->keterangan,
                    'tglPublish' => $item->tglPublish,
                    'tglBerakhir' => $item->tglBerakhir,
                    'pembagi' => $item->pembagi,
                    'bidang' => $item->bidang,
                    'keahlian' => $item->keahlian,
                ));
            }
    
            $response['error'] = FALSE;
            $response['message'] = 'All Data Info';
            $response['data'] = $data;

        }else{

            $response['error'] = TRUE;
            $response['message'] = 'Not Found Info';
        }

        return json_encode($response);
    }
}
