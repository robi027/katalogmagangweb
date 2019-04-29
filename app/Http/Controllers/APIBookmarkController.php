<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Bookmark;

class APIBookmarkController extends Controller
{
    public function addBookmark(Request $request){
        $response = array();
        $idUser = $request->input('idUser');
        $idTempat = $request->input('idTempat');
        date_default_timezone_set("asia/jakarta");
        $tglBookmark = time();

        
        $fieldBookmark = array('idUser' => $idUser, 
        'idTempat' => $idTempat, 'tglBookmark' => $tglBookmark);

        $query = Bookmark::insert($fieldBookmark);

        if($query == true){
            $response['error'] = FALSE;
            $response['message'] = "Data bookmark successfully";
        }else{
            $response['error'] = TRUE;
            $response['message'] = "Failed to bookmark data";
        }

        return json_encode($response);
    }

    public function deleteBookmark(Request $request){
        $response = array();
        $idUser = $request->input('idUser');
        $idTempat = $request->input('idTempat');

        $fieldBookmark = array('idUser' => $idUser, 
        'idTempat' => $idTempat);

        $query = Bookmark::where($fieldBookmark)->delete();

        if($query){
            $response['error'] = FALSE;
            $response['message'] = "Delete bookmark successfully";
        }else{
            $response['error'] = TRUE;
            $response['message'] = "Failed to delete bookmark";
        }

        return json_encode($response);
        
    }

    public function getMyBookmark($idUser){
        $response = array();
        $data = array();

        $query = Bookmark::select('tempat.id', 'tipe.tipe', 'tempat.nama', 
        'tempat.foto', 'tempat.alamat')
        ->selectRaw('GROUP_CONCAT(DISTINCT bidang.bidang SEPARATOR ", ") as bidang')
        ->selectRaw('IFNULL(SUM(DISTINCT rating.rating), 0) as rating')
        ->selectRaw('COUNT(DISTINCT rating.rating) as perating')
        ->leftJoin('tempat', 'tempat.id', '=', 'bookmark.idTempat')
        ->leftJoin('tipe', 'tipe.id', '=', 'tempat.idTipe')
        ->leftJoin('tempatbidang', 'tempatbidang.idTempat', '=', 'tempat.id')
        ->leftJoin('bidang', 'bidang.id', '=', 'tempatbidang.idBidang')
        ->leftJoin('rating', 'rating.idTempat', '=', 'tempat.id')
        ->where('bookmark.idUser', $idUser)
        ->groupBy('tempat.id');

        if($query->count() > 0){
            foreach($query->get() as $item){
                array_push($data, array(
                    'id' => $item->id,
                    'tipe' => $item->tipe,
                    'nama' => $item->nama,
                    'foto' => "http://192.168.1.80:8000/img/tempat/" . $item->foto,
                    'alamat' => $item->alamat,
                    'bidang' => $item->bidang,
                    'rating' => $item->rating,
                    'perating' => $item->perating,
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
    
}
