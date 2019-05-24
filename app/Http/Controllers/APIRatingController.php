<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rating;
use App\RatingBidang;
use App\RatingKeahlian;

class APIRatingController extends Controller
{
    public function getRatingTempat($idTempat){
        $response = array();
        $data = array();

        $query = Rating::select('rating.id', 'rating.rating', 
        'rating.project', 'rating.tglPublish', 'pengguna.nama as penilai')
        ->selectRaw('GROUP_CONCAT(DISTINCT bidang.bidang SEPARATOR ", ") as bidang')
        ->selectRaw('GROUP_CONCAT(DISTINCT keahlian.keahlian SEPARATOR ", ") as keahlian')
        ->leftJoin('pengguna', 'pengguna.id', '=', 'rating.idUser')
        ->leftJoin('ratingbidang', 'ratingbidang.idRating', '=', 'rating.id')
        ->leftJoin('bidang', 'bidang.id', '=', 'ratingbidang.idBidang')
        ->leftJoin('ratingkeahlian', 'ratingkeahlian.idRating', '=', 'rating.id')
        ->leftJoin('keahlian', 'keahlian.id', '=', 'ratingkeahlian.idKeahlian')
        ->where('rating.idTempat', $idTempat)->groupBy('rating.id');

        if($query->count() > 0){
            foreach($query->get() as $item){
                array_push($data, array(
                    'id' => $item->id,
                    'rating' => $item->rating,
                    'project' => $item->project,
                    'tglPublish' => $item->tglPublish,
                    'penilai' => $item->penilai,
                    'bidang' => $item->bidang,
                    'keahlian' => $item->keahlian,
                ));
            }

            $response['error'] = FALSE;
            $response['message'] = 'All Data Rating';
            $response['data'] = $data;

        }else{
            $response['error'] = TRUE;
            $response['message'] = 'Not Found Rating';
        }

        return json_encode($response);
    }

    public function getDetailRating($id){
        $response = array();

        $query = Rating::select('rating.id', 'rating.rating', 
        'rating.project', 'rating.tglPublish', 'rating.idUser as idPenilai', 
        'pengguna.nama as penilai', 'tempat.id as idTempat', 
        'tempat.nama as namaTempat', 'tipe.tipe')
        ->selectRaw('GROUP_CONCAT(DISTINCT bidang.bidang SEPARATOR ", ") as bidang')
        ->selectRaw('GROUP_CONCAT(DISTINCT keahlian.keahlian SEPARATOR ", ") as keahlian')
        ->leftJoin('pengguna', 'pengguna.id', '=', 'rating.idUser')
        ->leftJoin('tempat', 'tempat.id', '=', 'rating.idTempat')
        ->leftJoin('tipe', 'tipe.id', '=', 'tempat.idTipe')
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
                $response['detail']['idPenilai'] = $item->idPenilai;
                $response['detail']['penilai'] = $item->penilai;
                $response['detail']['bidang'] = $item->bidang;
                $response['detail']['keahlian'] = $item->keahlian;
                $response['detail']['idTempat'] = $item->idTempat;
                $response['detail']['namaTempat'] = '('. $item->tipe .') ' . $item->namaTempat;
            }
        }else{
            $response['error'] = TRUE;
            $response['message'] = 'Not Found Rating';
        }

        return json_encode($response);
    }

    public function addRating(Request $request){
        $response = array();
        $fieldBidang = array();
        $fieldKeahlian = array();

        $id = app(IDGeneratorController::class)->generateRating();
        $idTempat = $request->input('idTempat');
        $idUser = $request->input('idUser');
        $rating = $request->input('rating');
        $project = $request->input('project');
        date_default_timezone_set("asia/jakarta");
        $tglPublish = time();
        
        $bidang = json_decode(json_encode($request->input('bidang')), true);
        $keahlian = json_decode(json_encode($request->input('keahlian')), true);

        foreach($bidang as $iBidang){
            array_push($fieldBidang, array(
                'idRating' => $id,
                'idBidang' => $iBidang
            ));
        }

        foreach($keahlian as $iKeahlian){
            array_push($fieldKeahlian, array(
                'idRating' => $id,
                'idKeahlian' => $iKeahlian
            ));
        }

        $fieldRating = array('id' => $id, 'rating' => $rating, 
        'project' => $project, 'tglPublish' => $tglPublish, 
        'idTempat' => $idTempat, 'idUser' => $idUser);

        $queryRating = Rating::insert($fieldRating);

        if($queryRating){
            $queryBidang = RatingBidang::insert($fieldBidang);
            $queryKeahlian = RatingKeahlian::insert($fieldKeahlian);
        }else{

            $response['error'] = TRUE;
            $response['message'] = "Failed to publish rating";
        }
        
        if($queryBidang && $queryKeahlian){

            $response['error'] = FALSE;
            $response['message'] = "rating published successfully";
            
        }else{
            $response['error'] = TRUE;
            $response['message'] = "Failed to rating info";
        }

        return json_encode($response);

    }

    public function editRating(Request $request){
        $response = array();
        $fieldBidang = array();
        $fieldKeahlian = array();

        $id = $request->input('id');
        $rating = $request->input('rating');
        $project = $request->input('project');
        
        $bidang = json_decode(json_encode($request->input('bidang')), true);
        $keahlian = json_decode(json_encode($request->input('keahlian')), true);

        $queryRating = false;
        $queryBidang = false;
        $queryKeahlian = false;

        $fieldRating = array('rating' => $rating, 'project' => $project);

        $queryUpdateRating = Rating::where('id', $id)->update($fieldRating);

        if($queryUpdateRating){
            $queryRating = true;
        }else{
            $queryRating = true;
        }

        if($bidang != null){
            foreach($bidang as $iBidang){
                array_push($fieldBidang, array(
                    'idRating' => $id,
                    'idBidang' => $iBidang
                ));
            }

            $queryDeleteBidang = RatingBidang::where('idRating', $id)->delete();
            if($queryDeleteBidang){
                $queryAddBidang = RatingBidang::insert($fieldBidang);
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
                    'idRating' => $id,
                    'idKeahlian' => $iKeahlian
                ));
            }

            $queryDeleteKeahlian = RatingKeahlian::where('idRating', $id)->delete();
            if($queryDeleteKeahlian){
                $queryAddKeahlian = RatingKeahlian::insert($fieldKeahlian);
                if($queryAddKeahlian){
                    $queryKeahlian = true;
                }
            }

        }else{
            $queryKeahlian = true;
        }

        if ($queryRating && $queryBidang && $queryKeahlian){
            $response['error'] = FALSE;
            $response['message'] = "rating updated successfully";
            
        }else{
            $response['error'] = TRUE;
            $response['message'] = "Failed to publish rating";
        }

        return json_encode($response);

    }

    public function deleteRating($id){
        $response = array();

        $queryDeleteKeahlian = RatingKeahlian::where('idRating', $id)->delete();
        $queryDeleteBidang = RatingBidang::where('idRating', $id)->delete();

        if($queryDeleteKeahlian && $queryDeleteBidang){
            $queryDeleteRating = Rating::where('id', $id)->delete();
        }else{

            $response['error'] = TRUE;
            $response['message'] = "Failed to delete rating";
        }
        
        if($queryDeleteRating && $queryDeleteKeahlian && $queryDeleteBidang){
            $response['error'] = FALSE;
            $response['message'] = "rating deleted successfully";
            
        }else{
            $response['error'] = TRUE;
            $response['message'] = "Failed to delete rating";
        }

        return json_encode($response);
    }

    public function getMyRating($idUser){
        $response = array();
        $data = array();

        $query = Rating::select('rating.id', 'rating.rating', 
        'rating.project', 'rating.tglPublish', 'pengguna.nama as penilai', 
        'tempat.id as idTempat', 'tempat.nama as namaTempat', 'tipe.tipe')
        ->selectRaw('GROUP_CONCAT(DISTINCT bidang.bidang SEPARATOR ", ") as bidang')
        ->selectRaw('GROUP_CONCAT(DISTINCT keahlian.keahlian SEPARATOR ", ") as keahlian')
        ->leftJoin('pengguna', 'pengguna.id', '=', 'rating.idUser')
        ->leftJoin('tempat', 'tempat.id', '=', 'rating.idTempat')
        ->leftJoin('tipe', 'tipe.id', '=', 'tempat.idTipe')
        ->leftJoin('ratingbidang', 'ratingbidang.idRating', '=', 'rating.id')
        ->leftJoin('bidang', 'bidang.id', '=', 'ratingbidang.idBidang')
        ->leftJoin('ratingkeahlian', 'ratingkeahlian.idRating', '=', 'rating.id')
        ->leftJoin('keahlian', 'keahlian.id', '=', 'ratingkeahlian.idKeahlian')
        ->where('rating.idUser', $idUser)
        ->groupBy('rating.id');

        if($query->count() > 0){
            foreach($query->get() as $item){
                array_push($data, array(
                    'id' => $item->id,
                    'rating' => $item->rating,
                    'project' => $item->project,
                    'tglPublish' => $item->tglPublish,
                    'penilai' => $item->penilai,
                    'bidang' => $item->bidang,
                    'keahlian' => $item->keahlian,
                    'idTempat' => $item->idTempat,
                    'namaTempat' => '('. $item->tipe .') ' . $item->namaTempat
                ));
            }

            $response['error'] = FALSE;
            $response['message'] = 'All Data Rating';
            $response['data'] = $data;

        }else{
            $response['error'] = TRUE;
            $response['message'] = 'Not Found Rating';
        }

        return json_encode($response);
    }
}
