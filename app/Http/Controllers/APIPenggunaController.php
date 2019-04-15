<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Pengguna;
use App\Kemampuan;
use App\Berkeahlian;

class APIPenggunaController extends Controller
{
    public function register(Request $request){
        $response = array();
        $id = app(GenerateIDController::class)->generatePengguna();
        $nama = $request->input('nama');
        $email = $request->input('email');
        $password = $request->input('password');
        $angkatan = $request->input('angkatan');
        $sharing = $request->input('sharing');
        $level = 1;
        $idPS = "PS000001";
        date_default_timezone_set("asia/jakarta");
        $tglRegister = time();

        $queryCek = Pengguna::where('email', '=', $email)->count();
        
        if($queryCek == 0){
            $field = array('id' => $id, 'nama' => $nama, 
                'email' => $email, 'password' => $password, 
                'angkatan' => $angkatan, 'sharing' => $sharing,
                'level' => $level, 'idPS' => $idPS,
                'tglRegister' => $tglRegister);

            $query = Pengguna::insert($field);

            if($query){
                $response['error'] = FALSE;
                $response['message'] = "register successfull";
            }else{
                $response['error'] = TRUE;
                $response['message'] = "register failure";
            }
            
        }else{
            $response['error'] = TRUE;
            $response['message'] = "email is already exits";
        }

        return json_encode($response);
    }

    public function login(Request $request){
        $response = array();
        $email = $request->input('email');
        $password = $request->input('password');

        $query = Pengguna::select()
        ->where('email', '=' , $email)
        ->where('password', '=' , $password)
        ->get();

        $queryCount = Pengguna::select()
        ->where('email', '=' , $email)
        ->where('password', '=' , $password)
        ->count();

        if($queryCount > 0 ){
            foreach($query as $item){
                $kemampuan= Kemampuan::select()->where('idUser', '=', $item->id)->count();
                $berkeahlian= Berkeahlian::select()->where('idUser', '=', $item->id)->count();
                $response['error'] = FALSE;
                $response['message'] = "login successfull";
                $response['data'] = $item;
                if($kemampuan > 0 && $berkeahlian > 0){
                    $response['data']['complete'] = TRUE;
                }else{
                    $response['data']['complete'] = FALSE;
                }
            }
        }else{
            $response['error'] = TRUE;
            $response['message'] = "login failure";
        }

        return json_encode($response);
    }

    public function complete(Request $request){
        
        $response = array();
        $idBidang = array();
        $fieldBidang = array();
        $fieldKeahlian = array();
        $id = $request->input('id');
        $idPS = $request->input('idPS');
        $bidang = json_decode(json_encode($request->input('bidang')), true);
        $keahlian = json_decode(json_encode($request->input('keahlian')), true);
        
        foreach($bidang as $iBidang){
            array_push($fieldBidang, [
                'idUser' => $id,
                'idBidang' => $iBidang
            ]);
        }

        foreach($keahlian as $iKeahlian){
            array_push($fieldKeahlian,[
                'idUser' => $id, 
                'idKeahlian' => $iKeahlian
            ]);
        }

        $fieldPengguna = array('idPS' => $idPS);
        
        $queryPengguna = Pengguna::where('id', '=', $id)->update($fieldPengguna);
        $queryBidang = Kemampuan::insert($fieldBidang);
        $queryBerkeahlian = Berkeahlian::insert($fieldKeahlian);

        if($queryPengguna && $queryBidang && $queryBerkeahlian){
            $response['error'] = FALSE;
            $response['message'] = "Data saved successfully";
        }else{
            $response['error'] = TRUE;
            $response['message'] = "Failed to save data";
        }

        return json_encode($response);
    }
    
    public function tidakdipakai(Request $request){
        $id = app(GenerateIDController::class)->generatePengguna();
        $nama = $request->input('nama');
        $email = json_decode(json_encode($request->input('email')), true);
        $password = $request->input('sharing');
        $angkatan = $request->input('angkatan');
        $level = $request->input('level');
        $tglRegister = $request->input('tglRegister');

        $a = array("a","b","c");
        $b = array();

        foreach($a as $item){
            
            array_push($b, $item . 'i');
        }

        return $b;
    }
}