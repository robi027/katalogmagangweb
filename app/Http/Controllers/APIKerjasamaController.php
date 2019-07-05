<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Kerjasama;
use App\DetailKerjasama;
use App\Tempat;
use File;

class APIKerjasamaController extends Controller
{

    public function getKerjasamaTempat(){
        // tidak Kerjasama = 0
        // pengajuan kerjasama = 1  
        // kerjasama = 2
        // tidak diperpanjang 3

        $response = array();
        $data = array();
        date_default_timezone_set("asia/jakarta");
        $tglNow = time();

        $queryTempat = Tempat::select('tempat.id', 'tempat.nama', 'tipe.tipe', 'tempat.foto')
        ->leftJoin('tipe', 'tipe.id', '=', 'tempat.idTipe')
        ->get();

        $queryKerjasama = Kerjasama::get();

        $queryDetail = DetailKerjasama::get();

        foreach($queryTempat as $iTempat){
            $id = $iTempat->id;
            $status = 0;
            foreach($queryKerjasama as $iKerjasama){
                $idKerjasama = $iKerjasama->id;
                $idTempat = $iKerjasama->idTempat;
                if($id === $idTempat){
                    $status = 1;
                    foreach($queryDetail as $iDetail){
                        $idDetail = $iDetail->idKerjasama;
                        $tglBerakhir = $iDetail->tglBerakhir;
                        if($idKerjasama === $idDetail){
                            $status = 2;
                            if($tglBerakhir > $tglNow){
                                $status = 2;
                            }elseif($tglBerakhir < $tglNow){
                                $status = 3;
                            }
                        }
                    }
                }
            }
            array_push($data, array(
                'id' => $id,
                'namaTempat' => $iTempat->nama,
                'tipe' => $iTempat->tipe,
                'foto' => url('/img/tempat') . '/' . $iTempat->foto,
                'status' => $status
            ));
        }

        if($queryTempat && $queryKerjasama && $queryDetail){

            $response['error'] = FALSE;
            $response['message'] = 'All Kerjasama';
            $response['data'] = $data;

        }else{

            $response['error'] = TRUE;
            $response['message'] = 'Kerjasama Not Found';
        }

        return json_encode($response);

    }

    public function getKerjasama($idTempat){
        $response = array();
        date_default_timezone_set("asia/jakarta");
        $tglNow = time();

        $query = Kerjasama::select('kerjasama.id', 'kerjasama.idTempat', 
        'tempat.nama as namaTempat', 'kerjasama.namaPJKantor', 
        'kerjasama.no', 'kerjasama.document', 'kerjasama.tglPublish',
        'kerjasama.idUser', 'pengguna.nama as namaPengguna',
        'tempat.idTipe', 'tipe.tipe', 'tempat.id as idTempat',
        'tempat.nama as namaTempat')
        ->where('kerjasama.idTempat', $idTempat)
        ->leftJoin('tempat', 'tempat.id', '=', 'kerjasama.idTempat')
        ->leftJoin('tipe', 'tipe.id', '=', 'tempat.idTipe')
        ->leftJoin('pengguna', 'pengguna.id', '=', 'kerjasama.idUser')
        ->first();

        if($query){

            $status = 1;
            $queryCekStatus = DetailKerjasama::where('idKerjasama', $query->id)->first();

            if($queryCekStatus){
                $status = 2;
                if($queryCekStatus->tglBerakhir > $tglNow){
                    $status = 2;
                }else{
                    $status = 3;
                }
            }

            $response['error'] = FALSE;
            $response['message'] = 'All Kerjasama';
            $response['data']['id'] = $query->id;
            $response['data']['namaPJKantor'] = $query->namaPJKantor;
            $response['data']['no'] = $query->no;
            $response['data']['document'] = url('/img/kerjasama') . '/' . $query->document;
            $response['data']['tglPublish'] = $query->tglPublish;
            $response['data']['idUser'] = $query->idUser;
            $response['data']['namaPengguna'] = $query->namaPengguna;
            $response['data']['idTipe'] = $query->idTipe;
            $response['data']['tipe'] = $query->tipe;
            $response['data']['idTempat'] = $query->idTempat;
            $response['data']['namaTempat'] = $query->namaTempat;
            $response['data']['status'] = $status;

        }else{

            $response['error'] = TRUE;
            $response['message'] = 'Kerjasama Not Found';

        }

        return json_encode($response);
        
    }

    public function reqKerjasama(Request $request){
        $response = array();

        $id = app(IDGeneratorController::class)->generateKerjasama();
        $idTempat = $request->input('idTempat');
        $namaPJKantor = $request->input('namaPJKantor');
        $no = $request->input('no');
        $document = $request->file('document');
        $idUser = $request->input('idUser');
        date_default_timezone_set("asia/jakarta");
        $tglPublish = time();

        $destinationDocument = public_path('') . '/img/kerjasama/';

        $documentName = $id .'.jpg';
        $document->move($destinationDocument, $documentName);

        $field = array('id' => $id, 'idTempat' => $idTempat, 
        'namaPJKantor' => $namaPJKantor, 'no' => $no, 
        'document' => $documentName, 'tglPublish' => $tglPublish, 
        'idUser' => $idUser);

        $query = Kerjasama::insert($field);

        if($query){

            $response['error'] = FALSE;
            $response['message'] = 'Request has been sent successfully';

        }else{

            $response['error'] = TRUE;
            $response['message'] = 'Request Failure';
        }

        return json_encode($response);

    }

    public function conKerjasama(Request $request){
        $response = array();
        $idKerjasama = $request->input('idKerjasama');
        $tglMulai = $request->input('tglMulai');
        $tglBerakhir = $request->input('tglBerakhir');
        $keterangan = $request->input('keterangan');
        // $status = 2;

        // $fieldKerjasama = array('status' => $status);
        $fieldDetail = array('idKerjasama' => $idKerjasama, 
        'tglMulai' => $tglMulai, 'tglBerakhir' => $tglBerakhir,
        'keterangan' => $keterangan);

        // $queryKerjasama = Kerjasama::where('id', $idKerjasama)->update($fieldKerjasama);

        // if($queryKerjasama > 0){
        //     $queryDetail = DetailKerjasama::insert($fieldDetail);

        //     $response['error'] = FALSE;
        //     $response['message'] = 'Request successfully';

        // }else{

        //     $response['error'] = TRUE;
        //     $response['message'] = 'Request Failure';
        // }

        try{
            DB::beginTransaction();
            

            $queryDetail = DetailKerjasama::insert($fieldDetail);
            
            DB::commit();

            if($queryDetail){
                $response['error'] = FALSE;
                $response['message'] = 'Request successfully';
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

    public function reReqKerjasama(Request $request){
        $response = array();
        $idKerjasama = $request->input('idKerjasama');
        $namaPJKantor = $request->input('namaPJKantor');
        $no = $request->input('no');
        $document = $request->file('document');
        $idUser = $request->input('idUser');
        date_default_timezone_set("asia/jakarta");
        $tglPublish = time();
        $destinationDocument = public_path('') . '/img/kerjasama/';

        $queryCek = Kerjasama::where('id', $idKerjasama)->first();

        if($queryCek->tglBerakhir < $tglPublish){
    
            if($document != null){

                $documentName = $idKerjasama .'.jpg';
                $document->move($destinationDocument, $documentName);
                $field = array('id' => $idKerjasama,
                'namaPJKantor' => $namaPJKantor, 'no' => $no, 
                'document' => $documentName, 'tglPublish' => $tglPublish, 
                'idUser' => $idUser);

            }else{

                $field = array('id' => $idKerjasama,
                'namaPJKantor' => $namaPJKantor, 'no' => $no,
                'tglPublish' => $tglPublish, 'idUser' => $idUser);

            }

            try{

                DB::beginTransaction();
    
                $queryDetail = DetailKerjasama::where('idKerjasama', $idKerjasama)->delete();
    
                $updateKerjasama = Kerjasama::where('id', $idKerjasama)->update($field);
    
                DB::commit();
    
                if($updateKerjasama){
                    $response['error'] = FALSE;
                    $response['message'] = 'Kerjasama already updated';
                }else{
                    $response['error'] = TRUE;
                    $response['message'] = 'Request Failure';
                }
    
            }catch(Exception $e){
                DB::rollback();
    
                $response['error'] = TRUE;
                $response['message'] = 'Request Failure'; 
            }

        }else{

            $response['error'] = TRUE;
            $response['message'] = 'Kerjasama already created'; 
            
        }
        
        return json_encode($response);
    }

    public function deleteKerjasama($id){
        $response = array();
        date_default_timezone_set("asia/jakarta");
        $tglNow = time();    

        $queryDetail = DetailKerjasama::where('idKerjasama' , $id)->first();

        if($queryDetail){
            if($queryDetail->tglBerakhir < $tglNow){

                try{

                    DB::beginTransaction();
        
                    $queryDetail = DetailKerjasama::where('idKerjasama', $id)->delete();
                    $updateDetail = Kerjasama::where('id', $id)->delete();
        
                    DB::commit();
    
                    if($queryDetail){
                        File::delete(public_path().'/img/kerjasama/' . $id . '.jpg');
                        $response['error'] = FALSE;
                        $response['message'] = 'Kerjasama deleted successfully';
                    }else{
                        $response['error'] = TRUE;
                        $response['message'] = 'Request Failure';
                    }
        
                }catch(Exception $e){
                    DB::rollback();
        
                    $response['error'] = TRUE;
                    $response['message'] = 'Request Failure'; 
                }

            }else{

                $response['error'] = TRUE;
                $response['message'] = 'Kerjasama already created';

            }

        }else{
            $queryKerjasama = Kerjasama::where('id', $id)->delete();

            if($queryKerjasama){
                File::delete(public_path().'/img/kerjasama/' . $id . '.jpg');
                $response['error'] = FALSE;
                $response['message'] = 'Kerjasama deleted successfully';
            }else{
                $response['error'] = TRUE;
                $response['message'] = 'Request Failure';
            }

        }
     
        return json_encode($response);
    }

    public function getDetailKerjasama($id){
        $response = array();
        

        $query = DetailKerjasama::where('idKerjasama', $id)->first();

        if($query){

            $response['error'] = FALSE;
            $response['message'] = 'Detail Kerjasama';
            $response['data']['idKerjasama'] = $query->idKerjasama;
            $response['data']['tglMulai'] = date('d/m/Y', $query->tglMulai);
            $response['data']['tglBerakhir'] = date('d/m/Y', $query->tglBerakhir);
            $response['data']['keterangan'] = $query->keterangan;

        }else{

            $response['error'] = TRUE;
            $response['message'] = 'Request Failed';

        }

        return json_encode($response);  
    }

    public function reKerjasama(Request $request){
        $response = array();
        $idKerjasama = $request->input('idKerjasama');
        $tglMulai = $request->input('tglMulai');
        $tglBerakhir = $request->input('tglBerakhir');
        $keterangan = $request->input('keterangan');

        $field = array('tglMulai' => $tglMulai, 'tglBerakhir' => $tglBerakhir,
        'keterangan' => $keterangan);

        $query = DetailKerjasama::where('idKerjasama', $idKerjasama)->update($field);

        if($query){

            $response['error'] = FALSE;
            $response['message'] = 'Request successfully';
        }else{
            $response['error'] = TRUE;
            $response['message'] = 'Request Failure';    
        }

        return json_encode($response);

    }
}