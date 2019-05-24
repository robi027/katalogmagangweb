<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Pengguna;
use App\Kemampuan;
use App\Berkeahlian;

class APIPenggunaController extends Controller
{
    public function register(Request $request){
        $response = array();
        $id = app(IDGeneratorController::class)->generatePengguna();
        $nama = $request->input('nama');
        $email = $request->input('email');
        $password = bcrypt($request->input('password'));
        $angkatan = $request->input('angkatan');
        $sharing = $request->input('sharing');
        $level = 0;
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

        $pengguna = array('email' => $email, 'password' => $password);

        if(Auth::attempt($pengguna)){
            $kemampuan= Kemampuan::select()->where('idUser', '=', Auth::id())->count();
            $berkeahlian= Berkeahlian::select()->where('idUser', '=', Auth::id())->count();
            $response['error'] = FALSE;
            $response['message'] = "login successfull";
            $response['pengguna'] = Auth::user();
            if($kemampuan > 0 && $berkeahlian > 0){
                $response['pengguna']['complete'] = TRUE;
            }else{
                $response['pengguna']['complete'] = FALSE;
            }

        }else{
            $response['error'] = TRUE;
            $response['message'] = "login failure";
        }

        return json_encode($response);
    }

    public function complete(Request $request){
        $response = array();
        $fieldBidang = array();
        $fieldKeahlian = array();
        $id = $request->input('id');
        $angkatan = $request->input('angkatan');
        $idPS = $request->input('idPS');
        $bidang = json_decode(json_encode($request->input('bidang')), true);
        $keahlian = json_decode(json_encode($request->input('keahlian')), true);

        $fieldPengguna = array('angkatan' => $angkatan, 'idPS' => $idPS);
        $queryPengguna = Pengguna::where('id', $id)->update($fieldPengguna);

        if($bidang !=null){

            foreach($bidang as $iBidang){
                array_push($fieldBidang, [
                    'idUser' => $id,
                    'idBidang' => $iBidang
                ]);
            }

            $cekBidang = Kemampuan::where('idUser', $id);

            if($cekBidang->count() > 0){
                $queryDelBidang = $cekBidang->delete();
                if($queryDelBidang){
                    $queryBidang = Kemampuan::insert($fieldBidang);
                }
            }else{
                $queryBidang = Kemampuan::insert($fieldBidang);
            }

        }else{
            $queryBidang = true;
        }

        if($keahlian !=null){

            foreach($keahlian as $iKeahlian){
                array_push($fieldKeahlian,[
                    'idUser' => $id, 
                    'idKeahlian' => $iKeahlian
                ]);
            }

            $cekKeahlian = Berkeahlian::where('idUser', $id);

            if($cekKeahlian->count() > 0){
                $queryDelKeahlian = $cekKeahlian->delete();
                if($queryDelKeahlian){
                    $queryBerkeahlian = Berkeahlian::insert($fieldKeahlian);
                }
            }else{
                $queryBerkeahlian = Berkeahlian::insert($fieldKeahlian);
            }

        }else{
            $queryBerkeahlian = true;
        }
        
        if($queryBidang && $queryBerkeahlian){
            $response['error'] = FALSE;
            $response['message'] = "Data saved successfully";
        }else{
            $response['error'] = TRUE;
            $response['message'] = "Failed to save data";
        }
        
        return json_encode($response);
    }

    public function getProfile($id){
        $response = array();

        $query = Pengguna::select('pengguna.nama', 'pengguna.foto', 'pengguna.email', 
        'pengguna.password', 'pengguna.angkatan', 'pengguna.sharing',
        'pengguna.tglRegister', 'ps.ps')
        ->selectRaw('GROUP_CONCAT(DISTINCT bidang.bidang SEPARATOR ", ") as bidang')
        ->selectRaw('GROUP_CONCAT(DISTINCT keahlian.keahlian SEPARATOR ", ") as keahlian')
        ->leftJoin('ps', 'ps.id', '=', 'pengguna.idPS')
        ->leftJoin('kemampuan', 'kemampuan.idUser', '=', 'pengguna.id')
        ->leftJoin('bidang', 'bidang.id', '=', 'kemampuan.idBidang')
        ->leftJoin('berkeahlian', 'berkeahlian.idUser', '=', 'pengguna.id')
        ->leftJoin('keahlian', 'keahlian.id', '=', 'berkeahlian.idKeahlian')
        ->groupBy('pengguna.id')
        ->where('pengguna.id', $id);

        if($query->count() > 0){
            foreach($query->get() as $item){
                $response['error'] = FALSE;
                $response['message'] = 'Data Profile';
                $response['pengguna']['nama'] = $item->nama;
                $response['pengguna']['angkatan'] = $item->angkatan;
                $response['pengguna']['foto'] = url('/img/pengguna') . '/' . $item->foto;
                $response['pengguna']['tglRegister'] = $item->tglRegister;
                $response['pengguna']['email'] = $item->email;
                $response['pengguna']['password'] = '*****';
                $response['pengguna']['sharing'] = $item->sharing;
                $response['pengguna']['ps'] = $item->ps;
                $response['pengguna']['bidang'] = $item->bidang;
                $response['pengguna']['keahlian'] = $item->keahlian;
            }
        }else{
                $response['error'] = TRUE;
                $response['message'] = 'Data Not Found';
        }

        return json_encode($response);
    }

    public function getAllAdmin(){
        $response = array();
        $data = array();

        $query = Pengguna::where('level', '1');

        if($query->count() > 0){
            foreach($query->get() as $item){
                array_push($data, array(
                    'id' => $item->id,
                    'nama' => $item->nama,
                    'email' => $item->email,
                    'password' => $item->password,
                    'angkatan' => $item->angkatan,
                    'sharing' => $item->sharing,
                    'level' => $item->level,
                    'idPS' => $item->idPS,
                    'tglRegister' => $item->tglRegister
                ));
            }

            $response['error'] = FALSE;
            $response['message'] = "All Admin";
            $response['data'] = $data;
        }else{
            $response['error'] = TRUE;
            $response['message'] = "Admin not found";
        }

        return json_encode($response);
    }

    public function editFoto(Request $request){
        $response = array();

        $id = $request->input('id');

        $foto = $request->file('foto');

        $destinationFoto = public_path('') . '/img/pengguna/';

        $fotoName = $id .'.jpg';
        $foto->move($destinationFoto, $fotoName);

        $queryCek = Pengguna::select('foto')->where('id', $id)->get();

        if($queryCek[0]->foto == ""){

            $query = Pengguna::where('id', $id)->update(['foto' => $fotoName]);

            if($query){
                $response['error'] = FALSE;
                $response['message'] = "Foto updated successfully";
            }else{
                $response['error'] = TRUE;
                $response['message'] = "Failed to update foto";
            }
    
        }else{
            $response['error'] = FALSE;
            $response['message'] = "Foto updated successfully";
        }

        
        return json_encode($response);

    }

    public function editDataProfile(Request $request){
        $response = array();

        $id = $request->input('id');
        $nama = $request->input('nama');
        $sharing =  $request->input('sharing');

        $fieldQuery = array('nama' => $nama, 'sharing' => $sharing);

        $query = Pengguna::where('id', $id)->update($fieldQuery);

        if($query){
            $response['error'] = FALSE;
            $response['message'] = "Profile updated successfully";
        }else{
            $response['error'] = TRUE;
            $response['message'] = "Failed to update profile";
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