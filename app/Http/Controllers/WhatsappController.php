<?php

namespace App\Http\Controllers;
use App\Whatsapp;
use App\Cabang;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent as Agent;
use DB;

class WhatsappController extends Controller
{
    public function index(Request $request)
    {
        if(auth()->user()->level=="Admin"){
            $whatsapps= "";    
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
                        $whatsapps = Whatsapp::where('nama_id','like','%'.$request->q.'%')->where('cabang_id','like','%'.$request->cabang_id.'%')
                        ->whereBetween('tgl',[$tgl1,$tgl1])->orderBy('nilaiwa','ASC')->paginate(5);
                    }else{    
                        $whatsapps = Whatsapp::where('nama_id','like','%'.$request->q.'%')->where('cabang_id','like','%'.$request->cabang_id.'%')
                        ->whereBetween('tgl',[$tgl1,$tgl1])->orderBy('nilaiwa','DESC')->paginate(5);     
                    }
                }
                else{
                    $whatsapps = Whatsapp::where('nama_id','like','%'.$request->q.'%')->where('cabang_id','like','%'.$request->cabang_id.'%')
                        ->whereBetween('tgl',[$tgl1,$tgl1])->paginate(5);
                }}
        if(auth()->user()->level=="Karyawan"){
            $whatsapps= "";    
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
                        $whatsapps = Whatsapp::where('nama_id','like','%'.$request->q.'%')->where('cabang_id','like','%'.$request->cabang_id.'%')->where('nama','=',Auth()->user()->id)
                        ->whereBetween('tgl',[$tgl1,$tgl1])->orderBy('nilaiwa','ASC')->paginate(5);
                    }else{    
                        $whatsapps = Whatsapp::where('nama_id','like','%'.$request->q.'%')->where('cabang_id','like','%'.$request->cabang_id.'%')->where('nama','=',Auth()->user()->id)
                        ->whereBetween('tgl',[$tgl1,$tgl1])->orderBy('nilaiwa','DESC')->paginate(5);     
                    }
                }
                else{
                    $whatsapps = Whatsapp::where('nama_id','like','%'.$request->q.'%')->where('cabang_id','like','%'.$request->cabang_id.'%')->where('nama','=',Auth()->user()->id)
                        ->whereBetween('tgl',[$tgl1,$tgl1])->paginate(5);
                }
            }
        
        $Agent = new Agent();
            
        if(auth()->user()->level=="Admin"){
            if ($Agent->isMobile()) {
                // you're a mobile device
                    return view('mobile.whatsapp.index',compact('whatsapps'));
            } else {
                // you're a desktop device, or something similar
                    return view('whatsapp.index',compact('whatsapps'));
            }
        }
        if(auth()->user()->level=="Karyawan"){
            if ($Agent->isMobile()) {
                // you're a mobile device
                    return view('mobile.whatsappk.index',compact('whatsapps'));
            } else {
                // you're a desktop device, or something similar
                    return view('whatsappk.index',compact('whatsapps'));
            }
        }
        }
        public function create()
    {
    $Agent = new Agent();

    $cabangs = Cabang::all();

    if ($Agent->isMobile()) {
        return view('mobile.whatsapp.create', compact('cabangs'));
    } else {
        return view('whatsapp.create', compact('cabangs'));
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
            'gambar' => 'required',
        ],[
            'tgl.required' => 'Alamat Karyawan tidak boleh kosong!!!',
            'cabang_id.required' => 'Wilayah Samchick tidak boleh kosong!!!',
            'gambar.required' => 'Status Karyawan tidak boleh kosong!!!'
        ]);    
        $nm = $request->gambar;
        $namafile = $nm->getClientOriginalName();

            $whatsapp = new Whatsapp;
            $whatsapp->nama = Auth()->user()->id;
            $whatsapp->nama_id = Auth()->user()->name;
            $whatsapp->tgl =date('Y-m-d');
            $whatsapp->cabang_id = $request->cabang_id;
            $whatsapp->gambar = $namafile;
            $nm->move(public_path().'/pdf', $namafile);
            $whatsapp->save();
            return redirect('whatsappk')->with('status', 'Laporan Share Whatsapp Berhasil di Serahkan!!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Dkaryawan  $dkaryawan
     * @return \Illuminate\Http\Response
     */
    public function show(Whatsapp $whatsapp)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Dkaryawan  $dkaryawan
     * @return \Illuminate\Http\Response
     */
    public function edit(Whatsapp $whatsapp)
    {
        {
            $Agent = new Agent();
        
            $cabangs = Cabang::all();
        
            if ($Agent->isMobile()) {
                return view('mobile.whatsapp.edit', compact('whatsapp','cabangs'));
            } else {
                return view('whatsapp.edit', compact('whatsapp', 'cabangs'));
                }
            }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Dkaryawan  $dkaryawan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Whatsapp $whatsapp)
    {
        
        $request->validate([
            'nilaiwa' => 'required',
        ],[
            'nilaiwa.required' => 'Status Karyawan tidak boleh kosong!!!'
        ]);
        // return $request;
        // cara1

        $cek = DB::select("select * from rekap where tgl='$whatsapp->tgl' AND nama_id='$whatsapp->nama_id' AND tipe=0");
        $nilai = $request->nilaiwa;
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
                'nama_id' => $whatsapp->nama_id, 
                'tgl' => $whatsapp->tgl,
                'wa' => $nilai
                ]);
        }else{
            foreach($cek as $c){
                DB::table('rekap')->where('id_rekap', $c->id_rekap)->update([
                    'wa' => ($c->wa - $whatsapp->nilaiwa)+$nilai
                    ]);
            }        
        }
        $whatsapp->nilaiwa = $request->nilaiwa;
        $whatsapp->predikat = $predikat;
        $whatsapp->save();

        return redirect('whatsapp')->with('status', 'Tugas Karyawan Berhasil di Nilai!!!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Dkaryawan  $dkaryawan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Whatsapp $whatsapp)
    {
        $whatsapp->delete();
        return redirect('whatsapp')->with('status', 'Tugas Share Whatsapp Berhasil di Hapus!!!');
    }
}
