<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Chat;
use App\Pertanyaan;

class APIPertanyaanController extends Controller
{
    public function getMyPertanyaan($idUser){
        $response = array();
        $data = array();
        date_default_timezone_set("asia/jakarta");
        $tglNow = time();

        $lastestPertanyaan = Pertanyaan::select('idChat', DB::raw('MAX(tglRecord) as last_tglRecord'))
        ->groupBy('idChat');

        $query = Pertanyaan::select('pertanyaan.id', 'pertanyaan.isi',
        'pertanyaan.tglRecord', 'pertanyaan.idChat', 'pertanyaan.idPengirim',
        'pengirim.nama as namaPengirim','tempat.nama as namaTempat', 'tipe.tipe', 
        'chat.idTempat','pertanyaan.idPenerima', 'penerima.nama as namaPenerima')
        ->leftJoin('pengguna as pengirim', 'pengirim.id', '=', 'pertanyaan.idPengirim')
        ->leftJoin('pengguna as penerima', 'penerima.id', '=', 'pertanyaan.idPenerima')
        ->leftJoin('chat', 'chat.id', '=', 'pertanyaan.idChat')
        ->leftJoin('tempat', 'tempat.id', '=', 'chat.idTempat')
        ->leftJoin('tipe', 'tipe.id', '=', 'tempat.idTipe')
        ->joinSub($lastestPertanyaan, 'last_record', function($join){
            $join->on('pertanyaan.tglRecord', 'last_record.last_tglRecord');
        })
        ->where('pertanyaan.idPengirim', $idUser)
        ->orWhere('pertanyaan.idPenerima', $idUser)
        ->groupBy('pertanyaan.idChat');

        if($query->count() > 0){
            foreach($query->get() as $item){
                $waktu = ($tglNow - $item->tglRecord) / 60 % 60;
                $ket = " menit yang lalu";
                if($waktu > 59){
                    $waktu = ($tglNow - $item->tglRecord) / 3600 % 24;
                    $ket = " jam yang lalu";
                    if($waktu > 23){
                        $waktu = ($tglNow - $item->tglRecord) / 86400 % 7;
                        $ket = " hari yang lalu";
                    }
                }

                array_push($data, array(
                    'id' => $item->id,
                    'isi' => $item->isi,
                    'tglRecord' => $waktu . $ket,
                    'idPengirim' => $item->idPengirim,
                    'namaPengirim' => $item->namaPengirim,
                    'idChat' => $item->idChat,
                    'idTempat' => $item->idTempat,
                    'namaTempat' => $item->namaTempat . ' (' . $item->tipe . ')',
                    'idPenerima' => $item->idPenerima,
                    'namaPenerima' => $item->namaPenerima
                ));
            }

            $response['error'] = FALSE;
            $response['message'] = 'All Data Question';
            $response['data'] = $data;

        }else{

            $response['error'] = TRUE;
            $response['message'] = 'Not Found Question';
        }

        return json_encode($response);
    }

    public function addPertanyaan(Request $request){
        $response = array();
        $idChat = app(IDGeneratorController::class)->generateChat();
        $idPertanyaan = app(IDGeneratorController::class)->generatePertanyaan();
        $project = $request->input('project');
        $idTempat = $request->input('idTempat');
        $isi = $request->input('isi');
        date_default_timezone_set("asia/jakarta");
        $tglRecord = time();
        $idPengirim = $request->input('idPengirim');
        $idPenerima = $request->input('idPenerima');

        $fieldChat = array('id' => $idChat, 'idTempat' => $idTempat, 
        'project' => $project);
        $fieldPertanyaan = array('id' => $idPertanyaan, 'isi' => $isi, 
        'tglRecord' => $tglRecord, 'idPengirim' => $idPengirim, 
        'idChat' => $idChat, 'idPenerima' => $idPenerima);
        
        $queryChat = Chat::insert($fieldChat);

        if($queryChat){
            
            $queryPertanyaan = Pertanyaan::insert($fieldPertanyaan);

            if($queryPertanyaan){
                $response['error'] = FALSE;
                $response['message'] = "Question has been delivered";
            }else{
                $response['error'] = TRUE;
                $response['message'] = "Sending question failed";
            }

        }else{
            $response['error'] = TRUE;
            $response['message'] = "Sending question failed";
        }

        return json_encode($response);
    }

    public function addJawaban(Request $request){
        $response = array();

        $idChat = $request->input('idChat');
        $idPertanyaan = app(IDGeneratorController::class)->generatePertanyaan();
        $isi = $request->input('isi');
        date_default_timezone_set("asia/jakarta");
        $tglRecord = time();
        $idPengirim = $request->input('idPengirim');
        $idPenerima = $request->input('idPenerima');

        $fieldPertanyaan = array('id' => $idPertanyaan, 'isi' => $isi, 
        'tglRecord' => $tglRecord, 'idPengirim' => $idPengirim, 
        'idChat' => $idChat, 'idPenerima' => $idPenerima);
            
        $queryPertanyaan = Pertanyaan::insert($fieldPertanyaan);

        if($queryPertanyaan){
            $response['error'] = FALSE;
            $response['message'] = "Question has been delivered";
        }else{
            $response['error'] = TRUE;
            $response['message'] = "Sending question failed";
        }

        return json_encode($response);

    }

    public function deleteMyPertanyaan($idChat){
        $response = array();

        $queryPertanyaan = Pertanyaan::where('idChat', $idChat)->delete();

        if($queryPertanyaan){

            $queryChat = Chat::where('id', $idChat)->delete();

            if($queryChat){

                $response['error'] = FALSE;
                $response['message'] = "Question has been deleted";

            }else{

                $response['error'] = TRUE;
                $response['message'] = "Delete question failed";

            }
   
        }else{
            $response['error'] = TRUE;
            $response['message'] = "Delete question failed";
        }

        return json_encode($response);
    }

    public function getdetailPertanyaan($idChat){
        $response = array();
        $data = array();
        date_default_timezone_set("asia/jakarta");
        $tglNow = time();

        $query = Chat::select('chat.id', 'chat.idTempat', 'tempat.nama as namaTempat',
        'chat.project', 'pertanyaan.id as idPertanyaan', 'pertanyaan.isi', 
        'pertanyaan.tglRecord', 'pertanyaan.idPengirim', 'pengirim.nama as namaPengirim', 
        'pertanyaan.idPenerima', 'penerima.nama as namaPenerima')
        ->orderBy('pertanyaan.tglRecord', 'DESC')
        ->leftJoin('tempat', 'tempat.id', '=', 'chat.idTempat')
        ->leftJoin('pertanyaan', 'pertanyaan.idChat', '=', 'chat.id')
        ->leftJoin('pengguna as pengirim', 'pengirim.id', '=', 'pertanyaan.idPengirim')
        ->leftJoin('pengguna as penerima', 'penerima.id', '=', 'pertanyaan.idPenerima')
        ->where('chat.id', $idChat);

        $queryFirst = Pertanyaan::orderBy('tglRecord', 'ASC')
        ->where('pertanyaan.idChat', $idChat)->first();

        if($query->count() > 0){
            foreach($query->get() as $item){
                $waktu = ($tglNow - $item->tglRecord) / 60 % 60;
                $ket = " menit yang lalu";
                if($waktu > 59){
                    $waktu = ($tglNow - $item->tglRecord) / 3600 % 24;
                    $ket = " jam yang lalu";
                    if($waktu > 23){
                        $waktu = ($tglNow - $item->tglRecord) / 86400 % 7;
                        $ket = " hari yang lalu";
                    }
                }

                array_push($data, array(
                    'id' => $item->id,
                    'idTempat' => $item->idTempat,
                    'namaTempat' => $item->namaTempat,
                    'project' => $item->project,
                    'idPertanyaan' => $item->idPertanyaan,
                    'isi' => $item->isi,
                    'tglRecord' => $waktu . $ket,
                    'idPengirim' => $item->idPengirim,
                    'namaPengirim' => $item->namaPengirim,
                    'idPenerima' => $queryFirst->idPenerima,
                    'namaPenerima' => $item->namaPenerima,
                ));
            }

            $response['error'] = FALSE;
            $response['message'] = 'Detail Question';
            $response['data'] = $data;

        }else{
            $response['error'] = TRUE;
            $response['message'] = 'Not Found Detail';
        }

        return json_encode($response);
    }

    public function updatePertanyaan(Request $request){
    }
}
