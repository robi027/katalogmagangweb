<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TempatKontribusi;

class KontribusiController extends Controller
{
    public function getKontribusiTempat(Request $request){
        $data = array();
        $idTempat = $request->input('idTempat');        

        $columns = array(
            0 => 'idTempat',
            1 => 'idUser',
            2 => 'namaPengguna',
            3 => 'tglJoin',
            4 => 'level'
            
        );

        $totalData = TempatKontribusi::where('idTempat', $idTempat)->count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $query = TempatKontribusi::select('tempatkontribusi.idTempat', 
        'tempatkontribusi.idUser', 'pengguna.nama as namaPengguna',
        'tempatkontribusi.level', 'tempatkontribusi.tglJoin')
        ->offset($start)
        ->limit($limit)
        ->orderBy($order, $dir)
        ->leftJoin('pengguna', 'pengguna.id', '=', 'tempatkontribusi.idUser')
        ->where('idTempat', $idTempat)
        ->get();

        if(!empty($query)){
            foreach($query as $iKontribusi){
                $level = 'Aktif';
                if($iKontribusi->level === 1){
                    $level = 'Pengajuan';
                }

                array_push($data, array(
                    'idTempat' => $iKontribusi->idTempat,
                    'idUser' => $iKontribusi->idUser,
                    'namaPengguna' => $iKontribusi->namaPengguna,
                    'tglJoin' => $iKontribusi->tglJoin,
                    'level' => $level
                ));
            }
        }

        $jsonData = array(
            'draw' => intval($request->input('draw')),
            'recordsTotal' => intval($totalData),
            'recordsFiltered' => intval($totalFiltered),
            'data' => $data
        );

        return json_encode($jsonData);
    }
}
