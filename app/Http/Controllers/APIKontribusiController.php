<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TempatKontribusi;

class APIKontribusiController extends Controller
{
    
    public function getKontribusiTempat(Request $request){
        $response = array();
        $data = array();

        $idTempat = $request->input('idTempat');
        $idUser = $request->input('idUser');

        $query = TempatKontribusi::select('tempatkontribusi.idTempat', 
        'tempatkontribusi.idUser', 'pengguna.nama', 
        'tempatkontribusi.level', 'tempatkontribusi.tglJoin')
        ->where('idTempat', $idTempat)
        ->orderBy('tempatkontribusi.level', 'DESC')
        ->leftJoin('pengguna', 'pengguna.id', '=', 'tempatkontribusi.idUser');

        if($query->count() > 0){
            $status = 0;
            foreach($query->get() as $iKontribusi){
                if($idUser === $iKontribusi->idUser){
                    $status = $iKontribusi->level;
                }

                if($iKontribusi->level !== 1){
                    array_push($data, array(
                        'idTempat' => $iKontribusi->idTempat,
                        'idUser' => $iKontribusi->idUser,
                        'namaPengguna' => $iKontribusi->nama,
                        'tglJoin' => date('d/m/Y', $iKontribusi->tglJoin)
                    ));
                }                
            }

            $response['error'] = FALSE;
            $response['message'] = 'All Data Kontribusi';
            $response['status'] = $status;
            $response['data'] = $data;

        }else{

            $response['error'] = TRUE;
            $response['message'] = 'Not Found Kontribusi';
        }

        return json_encode($response);
        
    }

    public function getKontribusi($idTempat){
        $response = array();
        $data = array();

        $query = TempatKontribusi::select('tempatkontribusi.idTempat', 
        'tempatkontribusi.idUser', 'pengguna.nama', 
        'tempatkontribusi.level', 'tempatkontribusi.tglJoin')
        ->where('idTempat', $idTempat)
        ->orderBy('tempatkontribusi.level', 'ASC')
        ->leftJoin('pengguna', 'pengguna.id', '=', 'tempatkontribusi.idUser');

        if($query->count() > 0){
            foreach($query->get() as $iKontribusi){
                array_push($data, array(
                    'idTempat' => $iKontribusi->idTempat,
                    'idUser' => $iKontribusi->idUser,
                    'namaPengguna' => $iKontribusi->nama,
                    'level' => $iKontribusi->level,
                    'tglJoin' => date('d/m/Y', $iKontribusi->tglJoin)
                ));
            }

            $response['error'] = FALSE;
            $response['message'] = 'All Data Kontribusi';
            $response['data'] = $data;

        }else{

            $response['error'] = TRUE;
            $response['message'] = 'Not Found Kontribusi';
        }

        return json_encode($response);
        
    }

    public function reqKontribusi(Request $request){
        //requestkontribusi  = 0
        //penanggungjawabpengguna = 1
        //penanggungjawabfilkom = 2
        $response = array();
        $idTempat = $request->input('idTempat');
        $idUser = $request->input('idUser');
        $level = 1;
        date_default_timezone_set("asia/jakarta");
        $tglReq = time();

        $field = array('idTempat' => $idTempat, 
        'idUser' => $idUser, 'level' => $level, 
        'tglJoin' => $tglReq);

        $query = TempatKontribusi::insert($field);

        if($query){

            $response['error'] = FALSE;
            $response['message'] = 'Request has been sent successfully';

        }else{

            $response['error'] = TRUE;
            $response['message'] = 'Request Failure';
        }

        return json_encode($response);
    }
    
    public function conKontribusi(Request $request){
        $response = array();
        $idTempat = $request->input('idTempat');
        $idUser = $request->input('idUser');
        $level = 2;
        date_default_timezone_set("asia/jakarta");
        $tglJoin = time();

        $query = TempatKontribusi::where([
            ['idTempat', $idTempat], 
            ['idUser', $idUser]
            ])->update(['level' => $level, 'tglJoin' => $tglJoin]);

        if($query > 0){

            $response['error'] = FALSE;
            $response['message'] = 'Request successfully';

        }else{

            $response['error'] = TRUE;
            $response['message'] = 'Request Failure';
        }

        return json_encode($response);
    }

    public function deleteKontribusi(Request $request){
        $response = array();
        $idTempat = $request->input('idTempat');
        $idUser = $request->input('idUser');

        $query = TempatKontribusi::where([['idTempat', $idTempat],
        ['level', 2]]);

        if($query->count() > 1){
            $queryDel = TempatKontribusi::where([
                ['idTempat', $idTempat], 
                ['idUser', $idUser]
                ])->delete();

            if($queryDel > 0){
                $response['error'] = FALSE;
                $response['message'] = 'Request successfully';    
            }else{
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