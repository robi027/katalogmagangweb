<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Info;
use App\Bidang;
use App\InfoBidang;
use App\Keahlian;
use App\InfoKeahlian;

class InfoController extends Controller
{
    public function getInfoTempat(Request $request){
        $data = array();
        $idTempat = $request->input('idTempat');

        $columns = array(
            0 => 'id',
            1 => 'tglPublish',
            2 => 'project',
            3 => 'penginfo'
        );

        $totalData = Info::where('idTempat', $idTempat)->count();

        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        
        $query = Info::select('info.id', 'info.project',
        'info.tglPublish', 'pengguna.nama as penginfo')
        ->offset($start)
        ->limit($limit)
        ->orderBy($order, $dir)
        ->leftJoin('pengguna', 'pengguna.id', '=', 'info.idUser')
        ->where('idTempat', $idTempat)->groupBy('info.id')->get();

        if(!empty($query)){
            foreach($query as $iInfo){
                array_push($data, array(
                    'id' => $iInfo->id,
                    'tglPublish' => $iInfo->tglPublish,
                    'project' => $iInfo->project,
                    'penginfo' => $iInfo->penginfo,
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

    public function getInfo($id){
        $response = array();
        $bidangSelected = array();
        $keahlianSelected = array();

        $queryBidang = Bidang::get();
        $queryInfoBidang = InfoBidang::where('idInfo', $id)->get();

        $queryKeahlian = Keahlian::get();
        $queryInfoKeahlian = InfoKeahlian::where('idInfo', $id)->get();

        $query = Info::select('info.id', 'info.durasi', 'info.project',
        'info.keterangan', 'info.tglPublish', 'pengguna.nama as pembagi')
        ->leftJoin('pengguna', 'pengguna.id', '=', 'info.idUser')
        ->where('info.id', $id);

        foreach($queryBidang as $iBidang){
            $id = $iBidang->id;
            $selected = FALSE;
            foreach($queryInfoBidang as $iInfo){
                $idBidang = $iInfo->idBidang;
                if($id === $idBidang){
                    $selected = TRUE;
                }
            }
            array_push($bidangSelected, array(
                'idBidang' => $id,
                'bidang' => $iBidang->bidang,
                'selected' => $selected
            ));
        }

        foreach($queryKeahlian as $iKeahlian){
            $id = $iKeahlian->id;
            $selected = FALSE;
            foreach($queryInfoKeahlian as $iInfo){
                $idKeahlian = $iInfo->idKeahlian;
                if($id === $idKeahlian){
                    $selected = TRUE;
                }
            }

            array_push($keahlianSelected, array(
                'idKeahlian' => $id,
                'keahlian' => $iKeahlian->keahlian,
                'selected' => $selected
            ));
        }

        if($query->count() > 0){
            foreach($query->get() as $item){
                $response['error'] = FALSE;
                $response['message'] = 'All Data Info';
                $response['detail']['id'] = $item->id;
                $response['detail']['durasi'] = $item->durasi;
                $response['detail']['project'] = $item->project;
                $response['detail']['keterangan'] = $item->keterangan;
                $response['detail']['tglPublish'] = $item->tglPublish;
                $response['detail']['pembagi'] = $item->pembagi;
                $response['detail']['bidang'] = $bidangSelected;
                $response['detail']['keahlian'] = $keahlianSelected;
            }
            
        }else{

            $response['error'] = TRUE;
            $response['message'] = 'Not Found Info';
        }

        return json_encode($response);
    }

    public function editInfo(Request $request){
        return $request->all();
    }
}
