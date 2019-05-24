<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use Intervention\Image\ImageManagerStatic as Image;
use App\Tempat;
use App\TempatBidang;
use App\TempatKeahlian;
use App\Bidang;
use App\Bookmark;
use App\CobaUpload;
use App\Pengguna;

class APITempatController extends Controller
{
    public function getAllTempat(){
        $response = array();
        $data = array();
        
        $query = Tempat::select('tempat.id', 'tipe.tipe', 'tempat.nama', 'tempat.foto', 'tempat.alamat')
        ->selectRaw('GROUP_CONCAT(DISTINCT bidang.bidang SEPARATOR ", ") as bidang')
        ->selectRaw('IFNULL(SUM(DISTINCT rating.rating), 0) as rating')
        ->selectRaw('COUNT(DISTINCT rating.rating) as perating')
        ->leftJoin('tipe', 'tipe.id', '=', 'tempat.idTipe')
        ->leftJoin('tempatbidang', 'tempatbidang.idTempat', '=', 'tempat.id')
        ->leftJoin('bidang', 'bidang.id', '=', 'tempatbidang.idBidang')
        ->leftJoin('rating', 'rating.idTempat', '=', 'tempat.id')
        ->groupBy('tempat.id');

        if($query->count() > 0){
            foreach($query->get() as $item){
                array_push($data, array(
                    'id' => $item->id,
                    'tipe' => $item->tipe,
                    'nama' => $item->nama,
                    'foto' => url('/img/tempat') . '/' . $item->foto,
                    'alamat' => $item->alamat,
                    'bidang' => $item->bidang,
                    'rating' => $item->rating,
                    'perating' => $item->perating
                ));
            }
            $response['error'] = FALSE;
            $response['message'] = "All Data";
            $response['data'] = $data;
        }else{
            $response['error'] = TRUE;
            $response['message'] = "Data Not Found";
        }

        return json_encode($response);
    }

    public function addTempat(Request $request){

        $destinationFoto = public_path('') . '/img/tempat/';
        $response = array();
        $fieldBidang = array();
        $fieldKeahlian = array();
        
        $id = app(IDGeneratorController::class)->generateTempat();
        $idTipe = $request->input('idTipe');
        $idUser = $request->input('idUser');
        $nama = $request->input('nama');
        $foto = $request->file('foto');
        $bidang = json_decode(json_encode($request->input('bidang')), true);
        $keahlian = json_decode(json_encode($request->input('keahlian')), true);
        $deskripsi = $request->input('deskripsi');
        $no = $request->input('no');
        $email = $request->input('email');
        $website = $request->input('website');
        $lat = $request->input('lat');
        $lng = $request->input('lng');
        $alamat = $request->input('alamat');
        $status = 1;
        date_default_timezone_set("asia/jakarta");
        $tglPublish = time();
        $tglUpdate = time();

        $fotoName = $id .'.jpg';
        $foto->move($destinationFoto, $fotoName);

        foreach($bidang as $iBidang){
            array_push($fieldBidang, array(
                'idTempat' => $id,
                'idBidang' =>  str_replace('"', '', $iBidang)
            ));
        }
        
        foreach($keahlian as $iKeahlian){
            array_push($fieldKeahlian, array(
                'idTempat' => $id,
                'idKeahlian' => str_replace('"', '', $iKeahlian)
            ));
        }

        $fieldTempat = array('id' => $id, 'nama' => $nama, 'foto' => $fotoName,
        'no' => $no, 'email' => $email, 'website' => $website, 'deskripsi' => $deskripsi,
        'lat' => $lat, 'lng' => $lng, 'alamat' => $alamat, 'status' => $status,
        'idTipe' => $idTipe, 'tglPublish' => $tglPublish, 'tglUpdate' => $tglUpdate,
        'idUser' => $idUser);

        $queryTempat = Tempat::insert($fieldTempat);
        if($queryTempat){
            $queryBidang = TempatBidang::insert($fieldBidang);
            $queryKeahlian = TempatKeahlian::insert($fieldKeahlian);
        }else{
            $response['error'] = TRUE;
            $response['message'] = "Failed to publish data";
        }
        
        if($queryBidang && $queryKeahlian){
            $response['error'] = FALSE;
            $response['message'] = "Data published successfully";
            $response['data']['id'] = $id;
        }else{
            $response['error'] = TRUE;
            $response['message'] = "Failed to publish data";
        }

        return json_encode($response);
    }

    public function editTempat(Request $request){
        $response = array();
        $fieldBidang = array();
        $fieldKeahlian = array();

        $id = $request->input('id');
        $idTipe = $request->input('idTipe');
        $nama = $request->input('nama');
        $deskripsi = $request->input('deskripsi');
        $no = $request->input('no');
        $email = $request->input('email');
        $website = $request->input('website');
        $lat = $request->input('lat');
        $lng = $request->input('lng');
        $alamat = $request->input('alamat');
        $status = $request->input('status');
        date_default_timezone_set("asia/jakarta");
        $tglUpdate = time();
        $foto = $request->file('foto');

        $bidang = json_decode(json_encode($request->input('bidang')), true);
        $keahlian = json_decode(json_encode($request->input('keahlian')), true);

        $queryTempat = false;
        $queryBidang = false;
        $queryKeahlian = false;

        if($foto != null){

            $destinationFoto = public_path('') . '/img/tempat/';

            $fotoName = $id .'.jpg';
            $foto->move($destinationFoto, $fotoName);

            $fieldTempatWithFoto = array('nama' => $nama, 'foto' => $fotoName,
            'deskripsi' => $deskripsi, 'no' => $no, 'email' => $email, 
            'website' => $website, 'lat' => $lat, 'lng' => $lng, 'alamat' => $alamat,
            'status' => $status, 'idTipe' => $idTipe, 'tglUpdate' => $tglUpdate);

            $queryTempatWithFoto = Tempat::where('id', $id)->update($fieldTempatWithFoto);
            if($queryTempatWithFoto){
                $queryTempat = true;
            }
        
        }else{       

            $fieldTempatWhotFoto = array('nama' => $nama, 'deskripsi' => $deskripsi, 
            'no' => $no, 'email' => $email, 'website' => $website, 'lat' => $lat, 
            'lng' => $lng, 'alamat' => $alamat, 'status' => $status, 'idTipe' => $idTipe, 
            'tglUpdate' => $tglUpdate);
            
            $queryTempatWhotFoto = Tempat::where('id', $id)->update($fieldTempatWhotFoto);
            if($queryTempatWhotFoto){
                $queryTempat = true;
            }
        }

        if($bidang != null){
            foreach($bidang as $iBidang){
                array_push($fieldBidang, array(
                    'idTempat' => $id,
                    'idBidang' =>  str_replace('"', '', $iBidang)
                ));
            }

            $queryDeleteBidang = TempatBidang::where('idTempat', $id)->delete();    
            
            if($queryDeleteBidang){
                $queryAddBidang = TempatBidang::insert($fieldBidang);
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
                    'idTempat' => $id,
                    'idKeahlian' =>  str_replace('"', '', $iKeahlian)
                ));
            }

            $queryDeleteKeahlian = TempatKeahlian::where('idTempat', $id)->delete();

            if ($queryDeleteKeahlian){
                $queryAddKeahlian = TempatKeahlian::insert($fieldKeahlian);
                if($queryAddKeahlian){
                    $queryKeahlian = true;
                }
            }

        }else{
            $queryKeahlian = true;
        }

        if($queryTempat && $queryBidang && $queryKeahlian){
            $response['error'] = FALSE;
            $response['message'] = "Data updated successfully";
        }else{
            $response['error'] = TRUE;
            $response['message'] = "Failed to update data";
        }

        return json_encode($response);
    }

    public function getMyTempat($idUser){
        $response = array();
        $data = array();
        
        $query = $query = Tempat::select('tempat.id', 'tipe.tipe', 'tempat.nama', 'tempat.foto', 'tempat.alamat')
        ->selectRaw('GROUP_CONCAT(DISTINCT bidang.bidang SEPARATOR ", ") as bidang')
        ->selectRaw('IFNULL(SUM(DISTINCT rating.rating), 0) as rating')
        ->selectRaw('COUNT(DISTINCT rating.rating) as perating')
        ->leftJoin('tipe', 'tipe.id', '=', 'tempat.idTipe')
        ->leftJoin('tempatbidang', 'tempatbidang.idTempat', '=', 'tempat.id')
        ->leftJoin('bidang', 'bidang.id', '=', 'tempatbidang.idBidang')
        ->leftJoin('rating', 'rating.idTempat', '=', 'tempat.id')
        ->groupBy('tempat.id')
        ->where('tempat.idUser', '=', $idUser);

        if($query->count() > 0){
            foreach($query->get() as $item){
                array_push($data, array(
                    'id' => $item->id,
                    'tipe' => $item->tipe,
                    'nama' => $item->nama,
                    'foto' => url('/img/tempat') . '/' . $item->foto,
                    'alamat' => $item->alamat,
                    'bidang' => $item->bidang,
                    'rating' => $item->rating,
                    'perating' => $item->perating
                ));
            }
            $response['error'] = FALSE;
            $response['message'] = "All Data";
            $response['data'] = $data;
        }else{
            $response['error'] = TRUE;
            $response['message'] = "Data Not Found";
        }

        return json_encode($response);
    }

    public function getRekomendasi(Request $request){
        $response = array();
        $data = array();
        $fieldBidang = array();
        $idUser = $request->input('idUser');

        $queryPengguna = Pengguna::select('idPS')->where('id', $idUser)->get();

        foreach($queryPengguna as $iPengguna){
            $idPS = $iPengguna->idPS;
        }

        $queryBidang = Bidang::where('idPS', $idPS)->get();

        foreach($queryBidang as $iBidang){
            array_push($fieldBidang, array(
                $iBidang->id
            ));
        }
    
        $query = Tempat::select('tempat.id', 'tipe.tipe', 'tempat.nama', 'tempat.foto', 'tempat.alamat')
        ->selectRaw('GROUP_CONCAT(DISTINCT bidang.bidang SEPARATOR ", ") as bidang')
        ->selectRaw('IFNULL(SUM(DISTINCT rating.rating), 0) as rating')
        ->selectRaw('COUNT(DISTINCT rating.rating) as perating')
        ->leftJoin('tipe', 'tipe.id', '=', 'tempat.idTipe')
        ->leftJoin('tempatbidang', 'tempatbidang.idTempat', '=', 'tempat.id')
        ->leftJoin('bidang', 'bidang.id', '=', 'tempatbidang.idBidang')
        ->leftJoin('rating', 'rating.idTempat', '=', 'tempat.id')
        ->groupBy('tempat.id')
        ->whereIn('tempatbidang.idBidang', $fieldBidang);

        if($query->count() > 0){
            foreach($query->get() as $item){
                array_push($data, array(
                    'id' => $item->id,
                    'tipe' => $item->tipe,
                    'nama' => $item->nama,
                    'foto' => url('/img/tempat') . '/' . $item->foto,
                    'alamat' => $item->alamat,
                    'bidang' => $item->bidang,
                    'rating' => $item->rating,
                    'perating' => $item->perating
                ));
            }
            $response['error'] = FALSE;
            $response['message'] = "All Data";
            $response['data'] = $data;
        }else{
            $response['error'] = TRUE;
            $response['message'] = "Data Not Found";
        }

        return json_encode($response);
    }

    public function getDetailTempat(Request $request){
        $response = array();
        $id = $request->input('id');
        $idUser = $request->input('idUser');

        $queryTempat = Tempat::select('tempat.id', 'tipe.tipe', 'tempat.nama', 
        'tempat.foto', 'tempat.no', 'tempat.email', 'tempat.website',
        'tempat.deskripsi', 'tempat.lat', 'tempat.lng', 'tempat.alamat', 
        'tempat.status', 'tempat.tglPublish', 'tempat.tglUpdate', 
        'tempat.idUser as idPembagi', 'pengguna.nama as pembagi')
        ->selectRaw('GROUP_CONCAT(DISTINCT bidang.bidang SEPARATOR ", ") as bidang')
        ->selectRaw('GROUP_CONCAT(DISTINCT keahlian.keahlian SEPARATOR ", ") as keahlian')
        ->selectRaw('IFNULL(SUM(rating.rating), 0) as rating')
        ->selectRaw('COUNT(rating.rating) as perating')
        ->leftJoin('tipe', 'tipe.id', '=', 'tempat.idTipe')
        ->leftJoin('pengguna', 'pengguna.id', '=', 'tempat.idUser')
        ->leftJoin('tempatbidang', 'tempatbidang.idTempat', '=', 'tempat.id')
        ->leftJoin('bidang', 'bidang.id', '=', 'tempatbidang.idBidang')
        ->leftJoin('tempatkeahlian', 'tempatkeahlian.idTempat', '=', 'tempat.id')
        ->leftJoin('keahlian', 'keahlian.id', '=', 'tempatkeahlian.idKeahlian')
        ->leftJoin('rating', 'rating.idTempat', '=', 'tempat.id')
        ->groupBy('tempat.id')
        ->where('tempat.id', '=', $id);

        $fieldBookmark = array('idTempat' => $id, 'idUser' => $idUser);
        $bookmark = false;
        $queryBookmark = Bookmark::where($fieldBookmark);

        if($queryBookmark->count() > 0){
            $bookmark = true;
        }else{
            $bookmark = false;
        }

        if($queryTempat->count() > 0){
            foreach($queryTempat->get() as $iTempat){
                $response['error'] = FALSE;
                $response['message'] = 'Detail Tempat';
                $response['data']['id'] = $iTempat->id;
                $response['data']['tipe'] = $iTempat->tipe;
                $response['data']['nama'] = $iTempat->nama;
                $response['data']['foto'] = url('/img/tempat') . '/' . $iTempat->foto;
                $response['data']['no'] = $iTempat->no;
                $response['data']['email'] = $iTempat->email;
                $response['data']['website'] = $iTempat->website;
                $response['data']['deskripsi'] = $iTempat->deskripsi;
                $response['data']['lat'] = $iTempat->lat;
                $response['data']['lng'] = $iTempat->lng;
                $response['data']['alamat'] = $iTempat->alamat;
                $response['data']['status'] = $iTempat->status;
                $response['data']['idPembagi'] = $iTempat->idPembagi;
                $response['data']['pembagi'] = $iTempat->pembagi;
                $response['data']['tglPublish'] = $iTempat->tglPublish;
                $response['data']['tglUpdate'] = $iTempat->tglUpdate;
                $response['data']['bidang'] = $iTempat->bidang;
                $response['data']['keahlian'] = $iTempat->keahlian;
                $response['data']['rating'] = $iTempat->rating;
                $response['data']['perating'] = $iTempat->perating;
                $response['data']['bookmark'] = $bookmark;
            }
            
        }else{
            $response['error'] = FALSE;
            $response['message'] = 'Not Found Data';
        }

        return json_encode($response);
    }

    public function getDataTempat($id){
        $response = array();

        $queryTempat = Tempat::select('tempat.id', 'tipe.tipe', 'tempat.nama', 
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
        ->where('tempat.id', '=', $id);

        if($queryTempat->count() > 0){
            foreach($queryTempat->get() as $iTempat){
                $response['error'] = FALSE;
                $response['message'] = 'Detail Tempat';
                $response['data']['id'] = $iTempat->id;
                $response['data']['tipe'] = $iTempat->tipe;
                $response['data']['nama'] = $iTempat->nama;
                $response['data']['foto'] = url('/img/tempat') . '/' . $iTempat->foto;
                $response['data']['no'] = $iTempat->no;
                $response['data']['email'] = $iTempat->email;
                $response['data']['website'] = $iTempat->website;
                $response['data']['deskripsi'] = $iTempat->deskripsi;
                $response['data']['lat'] = $iTempat->lat;
                $response['data']['lng'] = $iTempat->lng;
                $response['data']['alamat'] = $iTempat->alamat;
                $response['data']['status'] = $iTempat->status;
                $response['data']['tglPublish'] = $iTempat->tglPublish;
                $response['data']['tglUpdate'] = $iTempat->tglUpdate;
                $response['data']['bidang'] = $iTempat->bidang;
                $response['data']['keahlian'] = $iTempat->keahlian;
            }
            
        }else{
            $response['error'] = FALSE;
            $response['message'] = 'Not Found Data';
        }

        return json_encode($response);
    }

    public function searchTempat(Request $request){
        $response = array();
        $data = array();
        $keyword = $request->input('keyword');

        if($keyword != null){

            $query = Tempat::select('tempat.id', 'tipe.tipe', 'tempat.nama', 'tempat.foto', 'tempat.alamat')
            ->selectRaw('GROUP_CONCAT(DISTINCT bidang.bidang SEPARATOR ", ") as bidang')
            ->selectRaw('IFNULL(SUM(DISTINCT rating.rating), 0) as rating')
            ->selectRaw('COUNT(DISTINCT rating.rating) as perating')
            ->leftJoin('tipe', 'tipe.id', '=', 'tempat.idTipe')
            ->leftJoin('tempatbidang', 'tempatbidang.idTempat', '=', 'tempat.id')
            ->leftJoin('bidang', 'bidang.id', '=', 'tempatbidang.idBidang')
            ->leftJoin('rating', 'rating.idTempat', '=', 'tempat.id')
            ->where('tempat.nama', 'like', '%' .$keyword. '%')->groupBy('tempat.id');

            if($query->count() > 0){
                foreach($query->get() as $item){
                    array_push($data, array(
                        'id' => $item->id,
                        'tipe' => $item->tipe,
                        'nama' => $item->nama,
                        'foto' => url('/img/tempat') . '/' . $item->foto,
                        'alamat' => $item->alamat,
                        'bidang' => $item->bidang,
                        'rating' => $item->rating,
                        'perating' => $item->perating
                    ));
                }
                $response['error'] = FALSE;
                $response['message'] = "All Data";
                $response['data'] = $data;
            }else{
                $response['error'] = TRUE;
                $response['message'] = "Data Not Found";
            }

        }else{

            $response['error'] = TRUE;
            $response['message'] = "Data Not Found";
            
        }

        return json_encode($response);

    }

    public function cobaUpload(Request $request){

        dd($request->input('nama'));

        $destinationFoto = public_path('') . '/img/tempat/';
        $id = app(IDGeneratorController::class)->generateTempat();
        $foto = $request->file('image');
        // $foto = Image::make($request->file('image'))->encode('jpg');
        
        // $fotoName = $id .'.'. $foto->getClientOriginalExtension();
        $fotoName = $id .'.jpg';
        // $foto->save($destinationFoto . $id . '.jpg');
        $foto->move($destinationFoto, $fotoName);
    }
}
