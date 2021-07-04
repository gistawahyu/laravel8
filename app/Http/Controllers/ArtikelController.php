<?php

namespace App\Http\Controllers;
use App\Artikel;
use App\Cabang;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent as Agent;
use DB;

class ArtikelController extends Controller
{
    public function index(Request $request)
    {
    $artikels= "";    
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
        if($request->tgl2 == "" || $request->tgl2 == null){
            $tgl2 = date("Y-m-d");
        }
        if($request->tgl2 != "" || $request->tgl2 != null){
            $tgl2 = $request->tgl2;
            $tgl2 = str_replace("/","-",$tgl2);
            $tgl2 = date('Y-m-d',strtotime($tgl2));
        }

        if($request->orderBy != null || $request->orderBy != ""){
            if($request->orderBy=="0"){            
                $artikels = Artikel::where('nama_id','like','%'.$request->q.'%')->where('cabang_id','like','%'.$request->cabang_id.'%')
                ->whereBetween('tgl',[$tgl1,$tgl2])->orderBy('nilaiar','ASC')->paginate(5);
            }else{    
                $artikels = Artikel::where('nama_id','like','%'.$request->q.'%')->where('cabang_id','like','%'.$request->cabang_id.'%')
                ->whereBetween('tgl',[$tgl1,$tgl2])->orderBy('nilaiar','DESC')->paginate(5);     
            }
        }
        else{
            $artikels =Artikel::where('nama_id','like','%'.$request->q.'%')->where('cabang_id','like','%'.$request->cabang_id.'%')
                ->whereBetween('tgl',[$tgl1,$tgl2])->paginate(10);
        }
    $Agent = new Agent();
    if ($Agent->isMobile()) {
        return view('mobile.artikel.index', compact('artikels'));
    } else {
        return view('artikel.index', compact('artikels'));
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
        return view('mobile.artikel.create', compact('cabangs'));
    } else {
        return view('artikel.create', compact('cabangs'));
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

            $artikel = new Artikel;
            $artikel->nama = Auth()->user()->id;
            $artikel->nama_id = Auth()->user()->name;
            $artikel->tgl = $request->tgl;
            $artikel->cabang_id = $request->cabang_id;
            $artikel->gambar = $namafile;
            $nm->move(public_path().'/pdf', $namafile);
            $artikel->save();
            return redirect('artikelk')->with('status', 'Laporan Artikel Berhasil di Serahkan!!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Dkaryawan  $dkaryawan
     * @return \Illuminate\Http\Response
     */
    public function show(Artikel $artikel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Dkaryawan  $dkaryawan
     * @return \Illuminate\Http\Response
     */
    public function edit(Artikel $artikel)
    {
        $cabangs = Cabang::all();
        return view('artikel.edit', compact('artikel', 'cabangs'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Dkaryawan  $dkaryawan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Artikel $artikel)
    {
        
        $request->validate([
            'nilaiar' => 'required',
        ],[
            'nilaiar.required' => 'Status Karyawan tidak boleh kosong!!!'
        ]);
        // return $request;
        // cara1
        $cek = DB::select("select * from rekapmingguan where tgl='$artikel->tgl' AND nama_id='$artikel->nama_id'");
        $nilai = $request->nilaiar;
       

        if($cek == null || $cek == ""){
            $save = DB::table('rekapmingguan')->insert([
                'nama_id' => $artikel->nama_id, 
                'tgl' => $artikel->tgl,
                'ar' => $nilai
                ]);
        }else{
            foreach($cek as $c){
                DB::table('rekapmingguan')->where('id_mingguan', $c->id_mingguan)->update([
                    'ar' => ($c->ar - $artikel->nilaiar)+$nilai
                    ]);
            }        
        }

        $artikel->nilaiar = $request->nilaiar;
        $artikel->save();

        return redirect('artikel')->with('status', 'Tugas Karyawan Berhasil di Nilai!!!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Dkaryawan  $dkaryawan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Artikel $artikel)
    {
        $artikel->delete();
        return redirect('artikel')->with('status', 'Tugas Artikel Karyawan Berhasil di Hapus!!!');
    }
}
