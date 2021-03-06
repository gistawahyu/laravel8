<?php

namespace App\Http\Controllers;
use DB;
use App\Googlemap;
use App\Cabang;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent as Agent;

class GMapsController extends Controller
{
    public function index(Request $request)
    {
        if(auth()->user()->level=="Admin"){
            $googlemaps= "";    
            $tgl1 = "";
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
                        $googlemaps = Googlemap::where('nama_id','like','%'.$request->q.'%')->where('cabang_id','like','%'.$request->cabang_id.'%')
                        ->whereBetween('tgl',[$tgl1,$tgl1])->orderBy('nilaigm','ASC')->paginate(5);
                    }else{    
                        $googlemaps = Googlemap::where('nama_id','like','%'.$request->q.'%')->where('cabang_id','like','%'.$request->cabang_id.'%')
                        ->whereBetween('tgl',[$tgl1,$tgl1])->orderBy('nilaigm','DESC')->paginate(5);     
                    }
                }
                else{
                    $googlemaps = Googlemap::where('nama_id','like','%'.$request->q.'%')->where('cabang_id','like','%'.$request->cabang_id.'%')
                        ->whereBetween('tgl',[$tgl1,$tgl1])->paginate(5);
                }}
        if(auth()->user()->level=="Karyawan"){
            $googlemaps= "";    
            $tgl1 = "";
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
                        $googlemaps = Googlemap::where('nama_id','like','%'.$request->q.'%')->where('cabang_id','like','%'.$request->cabang_id.'%')->where('nama','=',Auth()->user()->id)
                        ->whereBetween('tgl',[$tgl1,$tgl1])->orderBy('nilaigm','ASC')->paginate(5);
                    }else{    
                        $googlemaps = Googlemap::where('nama_id','like','%'.$request->q.'%')->where('cabang_id','like','%'.$request->cabang_id.'%')->where('nama','=',Auth()->user()->id)
                        ->whereBetween('tgl',[$tgl1,$tgl1])->orderBy('nilaigm','DESC')->paginate(5);     
                    }
                }
                else{
                    $googlemaps = Googlemap::where('nama_id','like','%'.$request->q.'%')->where('cabang_id','like','%'.$request->cabang_id.'%')->where('nama','=',Auth()->user()->id)
                        ->whereBetween('tgl',[$tgl1,$tgl1])->paginate(5);
                }
            }
        
        $Agent = new Agent();
            
        if(auth()->user()->level=="Admin"){
            if ($Agent->isMobile()) {
                // you're a mobile device
                    return view('mobile.googlemap.index',compact('googlemaps'));
            } else {
                // you're a desktop device, or something similar
                    return view('googlemap.index',compact('googlemaps'));
            }
        }
        if(auth()->user()->level=="Karyawan"){
            if ($Agent->isMobile()) {
                // you're a mobile device
                    return view('mobile.googlemapk.index',compact('googlemaps'));
            } else {
                // you're a desktop device, or something similar
                    return view('googlemapk.index',compact('googlemaps'));
            }
        }
        }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    $Agent = new Agent();
    $cabangs = Cabang::all();

    if ($Agent->isMobile()) {
        return view('mobile.googlemap.create', compact('cabangs'));
    } else {    
        return view('googlemap.create', compact('cabangs'));
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       $request->validate([
            'tgl' => 'required|min:3',
            'cabang_id' => 'required',
            'link' => 'required',
        ],[
            'tgl.required' => 'Alamat Karyawan tidak boleh kosong!!!',
            'cabang_id.required' => 'Wilayah Samchick tidak boleh kosong!!!',
            'link.required' => 'Link Tugas tidak boleh kosong!!!'
        ]);
        // return $request;   
            $googlemap = new Googlemap;
            $googlemap->nama = Auth()->user()->id;
            $googlemap->nama_id = Auth()->user()->name;
            $googlemap->tgl = date('Y-m-d');
            $googlemap->cabang_id = $request->cabang_id;
            $googlemap->link = $request->link;
            $googlemap->save();

        return redirect('googlemapk')->with('status', 'Laporan Google Views Berhasil di Serahkan!!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Dkaryawan  $dkaryawan
     * @return \Illuminate\Http\Response
     */
    public function show(Googlemap $googlemap)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Dkaryawan  $dkaryawan
     * @return \Illuminate\Http\Response
     */
    public function edit(Googlemap $googlemap)
    {
        $cabangs = Cabang::all();
        return view('googlemap.edit', compact('googlemap','cabangs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Dkaryawan  $dkaryawan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Googlemap $googlemap)
    {
        $request->validate([
            'nilaigm' => 'required',
        ],[
            'nilaigm.required' => 'Nilai tidak boleh kosong!!!'
        ]);
        // return $request;
        // cara1
        $cek = DB::select("select * from rekap where tgl='$googlemap->tgl' AND nama_id='$googlemap->nama_id' AND tipe=0");
        $nilai = $request->nilaigm;
        $predikat = "";
        if ($nilai == "0") {
            $predikat = "E";
        }else if ($nilai == "1") {
            $predikat = "D-";
        }else if ($nilai == "2") {
            $predikat = "D";
        }else if ($nilai == "3") {
            $predikat = "D+";
        }else if ($nilai == "4") {
            $predikat = "C-";
        }else if ($nilai == "5") {
            $predikat = "C";
        }else if ($nilai == "6") {
            $predikat = "C+";
        }else if ($nilai == "7") {
            $predikat = "B-";
        }else if ($nilai == "8") {
            $predikat = "B";
        }else if ($nilai == "9") {
            $predikat = "B+";
        }else if ($nilai == "10") {
            $predikat = "A";
        }


        if($cek == null || $cek == ""){
            $save = DB::table('rekap')->insert([
                'nama_id' => $googlemap->nama_id, 
                'tgl' => $googlemap->tgl,
                'gm' => $nilai
                ]);
        }else{
            foreach($cek as $c){
                DB::table('rekap')->where('id_rekap', $c->id_rekap)->update([
                    'gm' => ($c->gm - $googlemap->nilaigm)+$nilai
                    ]);
            }        
        }
        $googlemap->nilaigm = $request->nilaigm;
        $googlemap->predikat = $predikat;
        $googlemap->save();

        return redirect('googlemap')->with('status', 'Tugas Google Maps Berihasil Dinilai!!!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Dkaryawan  $dkaryawan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Googlemap $googlemap)
    {
        $googlemap->delete();
        return redirect('googlemap')->with('status', 'Laporan Google Views Berhasil di Hapus!!!');
    }
}
