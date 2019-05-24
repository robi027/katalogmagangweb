<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rating;

class RatingController extends Controller
{
    public function getRatingTempat(Request $request){

        $data = array();
        $idTempat = $request->input('idTempat');

        $columns = array(
            0 => 'id',
            1 => 'penilai',
            2 => 'project',
            3 => 'rating',
            4 => 'tglPublish'
        );

        $totalData = Rating::where('idTempat', $idTempat)->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        
        $query = Rating::select('rating.id', 'rating.rating', 
        'rating.project', 'rating.tglPublish', 'pengguna.nama as penilai')
        ->offset($start)
        ->limit($limit)
        ->orderBy($order, $dir)
        ->leftJoin('pengguna', 'pengguna.id', '=', 'rating.idUser')
        ->where('rating.idTempat', $idTempat)->groupBy('rating.id')->get();

        if(!empty($query)){
            foreach($query as $iRating){
                array_push($data, array(
                    'id' => $iRating->id,
                    'penilai' => $iRating->penilai,
                    'project' => $iRating->project,
                    'rating' => $iRating->rating,
                    'tglPublish' => $iRating->tglPublish
                ));
            }
        }

        $jsonData = array(
            'draw' =>intval($request->input('draw')),
            'recordsTotal' =>intval($totalData),
            'recordsFiltered' =>intval($totalFiltered),
            'data' => $data
        );

        return json_encode($jsonData);
    }

    public function getRating($id){
        $response = array();

        $query = Rating::select('rating.id', 'rating.rating', 
        'rating.project', 'rating.tglPublish', 'rating.idUser', 'pengguna.nama as penilai')
        ->selectRaw('GROUP_CONCAT(DISTINCT bidang.bidang SEPARATOR ", ") as bidang')
        ->selectRaw('GROUP_CONCAT(DISTINCT keahlian.keahlian SEPARATOR ", ") as keahlian')
        ->leftJoin('pengguna', 'pengguna.id', '=', 'rating.idUser')
        ->leftJoin('ratingbidang', 'ratingbidang.idRating', '=', 'rating.id')
        ->leftJoin('bidang', 'bidang.id', '=', 'ratingbidang.idBidang')
        ->leftJoin('ratingkeahlian', 'ratingkeahlian.idRating', '=', 'rating.id')
        ->leftJoin('keahlian', 'keahlian.id', '=', 'ratingkeahlian.idKeahlian')
        ->where('rating.id', $id)->groupBy('rating.id');

        if($query->count() > 0){
            foreach($query->get() as $item){
                $response['error'] = FALSE;
                $response['message'] = 'All Data Rating';
                $response['detail']['id'] = $item->id;
                $response['detail']['rating'] = $item->rating;
                $response['detail']['project'] = $item->project;
                $response['detail']['tglPublish'] = $item->tglPublish;
                $response['detail']['idUser'] = $item->idUser;
                $response['detail']['penilai'] = $item->penilai;
                $response['detail']['bidang'] = $item->bidang;
                $response['detail']['keahlian'] = $item->keahlian;
            }
        }else{
            $response['error'] = TRUE;
            $response['message'] = 'Not Found Rating';
        }

        return json_encode($response);
    }
}
