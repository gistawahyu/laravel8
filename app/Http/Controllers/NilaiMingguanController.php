<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent as Agent;

class NilaiMingguanController extends Controller
{
  public function index(Request $request)
    {
        $mingguan = "";
        $tgl1 = "";
        $tgl2 = "";
        if($request->tgl1 == "" || $request->tgl1 == null ){
            $tgl1 = date("Y-m-d");
        }
        if($request->tgl1 != "" || $request->tgl1 != null ){
            $tgl1 = $request->tgl1;
            $tgl1 = str_replace("/","-",$tgl1);
            $tgl1 = date('Y-m-d',strtotime($tgl1));
            }

        if($request->orderBy != null || $request->orderBy != ""){
            if($request->orderBy=="0"){            
                $mingguan = DB::select("select a.id_rekap , a.tgl , a.ar , a.wa , a.pam, b.name, (a.ar+a.wa+a.pam) as total, c.namacbg from rekap a, users b, cabangs c
                WHERE a.nama_id LIKE '%$request->q%' AND WEEK(a.tgl) = WEEK('$request->tgl')
                AND a.nama_id = b.name AND b.cabang_id=c.id AND a.tipe=0 ORDER BY total ASC");
            }else{    
                $mingguan = DB::select("select a.id_rekap , a.tgl , a.ar , a.wa , a.pam, b.name, (a.ar+a.wa+a.pam) as total, c.namacbg from rekap a, users b, cabangs c
                WHERE a.nama_id LIKE '%$request->q%' AND WEEK(a.tgl) = WEEK('$request->tgl')
                AND a.nama_id = b.name AND b.cabang_id=c.id AND a.tipe=0 ORDER BY total DESC");
            }
        }
        else{$mingguan = DB::select("select a.id_rekap , a.tgl , a.ar , a.wa , a.pam, b.name, (a.ar+a.wa+a.pam) as total, c.namacbg from rekap a, users b, cabangs c
            WHERE a.nama_id LIKE '%$request->q%' AND  WEEK(a.tgl) = WEEK('$request->tgl1')
            AND a.nama_id = b.name AND b.cabang_id=c.id AND a.tipe=0");
        }
    $Agent = new Agent();
    if ($Agent->isMobile()) {
        return view('mobile.nilaimingguan.index', compact('mingguan'),);
    } else {
        return view('nilaimingguan.index', compact('mingguan'),);
        }
    }
}
