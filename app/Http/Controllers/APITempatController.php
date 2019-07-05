<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;
use App\Tempat;
use App\TempatBidang;
use App\TempatKeahlian;
use App\Bidang;
use App\Bookmark;
use App\CobaUpload;
use App\Pengguna;
use App\Kerjasama;
use App\DetailKerjasama;
use App\TempatKontribusi;
use App\Kemampuan;

class APITempatController extends Controller
{
    public function getAllTempat(){
        $response = array();
        $data = array();
        date_default_timezone_set("asia/jakarta");
        $tglNow = time();
        
        $query = Tempat::select('tempat.id', 'tipe.tipe', 'tempat.nama', 'tempat.foto', 'tempat.alamat')
        ->selectRaw('GROUP_CONCAT(DISTINCT bidang.bidang SEPARATOR ", ") as bidang')
        ->selectRaw('IFNULL(SUM(DISTINCT rating.rating), 0) as rating')
        ->selectRaw('COUNT(DISTINCT rating.rating) as perating')
        ->orderBy('tempat.tglUpdate', 'DESC')
        ->leftJoin('tipe', 'tipe.id', '=', 'tempat.idTipe')
        ->leftJoin('tempatbidang', 'tempatbidang.idTempat', '=', 'tempat.id')
        ->leftJoin('bidang', 'bidang.id', '=', 'tempatbidang.idBidang')
        ->leftJoin('rating', 'rating.idTempat', '=', 'tempat.id')
        ->groupBy('tempat.id');

        if($query->count() > 0){
            $queryKerjasama = Kerjasama::join('detailkerjasama', 
                'detailkerjasama.idKerjasama', '=', 'kerjasama.id')
                ->get();
            foreach($query->get() as $item){
                $id = $item->id;
                $verified = FALSE;
                foreach($queryKerjasama as $iKerjasama){
                    $idTempat = $iKerjasama->idTempat;
                    if($idTempat === $id){
                        if($iKerjasama->tglBerakhir > $tglNow){
                            $verified = TRUE;
                        }
                    }
                }

                if($item->perating !== 0){
                    $rating = ($item->rating/$item->perating);
                }else{
                    $rating = 0;
                }

                array_push($data, array(
                    'id' => $item->id,
                    'tipe' => $item->tipe,
                    'nama' => $item->nama,
                    'foto' => url('/img/tempat') . '/' . $item->foto,
                    'alamat' => $item->alamat,
                    'bidang' => $item->bidang,
                    'verified' => $verified,
                    'rating' => $rating,
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

        // $destinationFoto = public_path('') . '/img/tempat/';
        $response = array();
        $fieldBidang = array();
        $fieldKeahlian = array();
        
        $id = app(IDGeneratorController::class)->generateTempat();
        $destinationFoto = public_path('/img/tempat/'.  $id .'.jpg');
        $idTipe = $request->input('idTipe');
        $idUser = $request->input('idUser');
        $nama = $request->input('nama');
        // $foto = $request->file('foto');
        $foto = Image::make($request->file('foto'))->save($destinationFoto, 10);
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
        // $foto->move($destinationFoto, $fotoName);


        // $foto->image = '/img/tempat/'. $id;
        // $foto->save();

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

        $fieldKontribusi = array('idTempat' => $id, 
        'idUser' => $idUser, 'level' => '2', 'tglJoin' => $tglPublish);

        $fieldTempat = array('id' => $id, 'nama' => $nama, 'foto' => $fotoName,
        'no' => $no, 'email' => $email, 'website' => $website, 'deskripsi' => $deskripsi,
        'lat' => $lat, 'lng' => $lng, 'alamat' => $alamat, 'status' => $status,
        'idTipe' => $idTipe, 'tglPublish' => $tglPublish, 'tglUpdate' => $tglUpdate);

        $queryTempat = Tempat::insert($fieldTempat);
        
        if($queryTempat){
            $queryKontribusi = TempatKontribusi::insert($fieldKontribusi);
            $queryBidang = TempatBidang::insert($fieldBidang);
            $queryKeahlian = TempatKeahlian::insert($fieldKeahlian);

        }else{
            $response['error'] = TRUE;
            $response['message'] = "Failed to publish data";
        }
        
        if($queryKontribusi && $queryBidang && $queryKeahlian){
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

            // $destinationFoto = public_path('') . '/img/tempat/';
            $destinationFoto = public_path('/img/tempat/'.  $id .'.jpg');
            $foto = Image::make($request->file('foto'))->save($destinationFoto, 10);

            // $fotoName = $id .'.jpg';
            // $foto->move($destinationFoto, $fotoName);

            $fieldTempatWithFoto = array('nama' => $nama, 'deskripsi' => $deskripsi, 
            'no' => $no, 'email' => $email, 'website' => $website, 'lat' => $lat, 
            'lng' => $lng, 'alamat' => $alamat, 'status' => $status, 
            'idTipe' => $idTipe, 'tglUpdate' => $tglUpdate);

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
        date_default_timezone_set("asia/jakarta");
        $tglNow = time();

        $queryKontribusi = TempatKontribusi::select('tempat.id', 
        'tempat.nama', 'tipe.tipe', 'tempat.foto')
        ->leftJoin('tempat', 'tempat.id', '=', 'tempatkontribusi.idTempat')
        ->leftJoin('tipe', 'tipe.id', '=', 'tempat.idTipe')
        ->orderBy('tempatkontribusi.tglJoin', 'DESC')
        ->where([['tempatkontribusi.idUser', $idUser],
        ['tempatkontribusi.level', '2']])->get();

        $queryKerjasama = Kerjasama::get();

        $queryDetail = DetailKerjasama::get();

        foreach($queryKontribusi as $iTempat){
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
                'nama' => $iTempat->nama,
                'tipe' => $iTempat->tipe,
                'foto' => url('/img/tempat') . '/' . $iTempat->foto,
                'status' => $status
            ));
        }

        if($queryKontribusi && $queryKerjasama && $queryDetail){

            $response['error'] = FALSE;
            $response['message'] = 'All Kerjasama';
            $response['data'] = $data;

        }else{

            $response['error'] = TRUE;
            $response['message'] = 'Kerjasama Not Found';
        }

        return json_encode($response);
    }

    public function getRekomendasi(Request $request){
        $response = array();
        $data = array();
        $fieldBidang = array();
        $idUser = $request->input('idUser');
        date_default_timezone_set("asia/jakarta");
        $tglNow = time();

        $queryBidang = Kemampuan::select('idBidang')->where('idUser', $idUser)->get();

        if(count($queryBidang) > 0){
            foreach($queryBidang as $iBidang){
                array_push($fieldBidang, array(
                    $iBidang->idBidang
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
            ->orderBy('tempat.tglUpdate', 'DESC')
            ->whereIn('tempatbidang.idBidang', $fieldBidang)
            ->groupBy('tempat.id');
            

            $queryKerjasama = Kerjasama::join('detailkerjasama', 
                'detailkerjasama.idKerjasama', '=', 'kerjasama.id')
                ->get();

            if($query->count() > 0){
                foreach($query->get() as $item){
                    $id = $item->id;
                    $verified = FALSE;
                    foreach($queryKerjasama as $iKerjasama){
                        $idTempat = $iKerjasama->idTempat;
                        if($idTempat === $id){
                            if($iKerjasama->tglBerakhir > $tglNow){
                                $verified = TRUE;
                            }
                        }
                    }

                    if($item->perating !== 0){
                        $rating = ($item->rating/$item->perating);
                    }else{
                        $rating = 0;
                    }

                    array_push($data, array(
                        'id' => $item->id,
                        'tipe' => $item->tipe,
                        'nama' => $item->nama,
                        'foto' => url('/img/tempat') . '/' . $item->foto,
                        'alamat' => $item->alamat,
                        'bidang' => $item->bidang,
                        'verified' => $verified,
                        'rating' => $rating,
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
            $response['message'] = "Bidang Not Found";
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
        'tempat.status', 'tempat.tglPublish', 'tempat.tglUpdate')
        ->selectRaw('GROUP_CONCAT(DISTINCT bidang.bidang SEPARATOR ", ") as bidang')
        ->selectRaw('GROUP_CONCAT(DISTINCT keahlian.keahlian SEPARATOR ", ") as keahlian')
        ->selectRaw('IFNULL(SUM(rating.rating), 0) as rating')
        ->selectRaw('COUNT(rating.rating) as perating')
        ->leftJoin('tipe', 'tipe.id', '=', 'tempat.idTipe')
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
                $verified = FALSE;
                if($iTempat->idPembagi == "US2407AC"){
                    $verified = TRUE;
                }
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
                $response['data']['tglPublish'] = date('d/m/Y', $iTempat->tglPublish);
                $response['data']['tglUpdate'] = date('d/m/Y', $iTempat->tglUpdate);
                $response['data']['bidang'] = $iTempat->bidang;
                $response['data']['keahlian'] = $iTempat->keahlian;
                $response['data']['verified'] = $verified;
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
