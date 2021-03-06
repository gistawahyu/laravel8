<?php

namespace App\Http\Controllers;
use DB;
use App\Artikel;
use App\Cabang;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent as Agent;

class ArtikelkController extends Controller
{
    public function store(Request $request)
    {
       $request->validate([
        'gambar' => 'required|mimes:pdf,doc,docx'
    ],[
        'gambar.required' => 'Gambar tidak boleh kosong!!!',
        ]);    
        $nm = $request->gambar;
        $namafile = $nm->getClientOriginalName();

        $cabang_id = Auth()->user()->id;
        $cabang = DB::select("select * from users where id='$cabang_id'");
        foreach ($cabang as $c){
            $cabang_id = $c->cabang_id;
            }
        
            $artikel = new Artikel;
            $artikel->nama = Auth()->user()->id;
            $artikel->nama_id = Auth()->user()->name;
            $artikel->tgl = date('Y-m-d');
            $artikel->cabang_id = $cabang_id;
            $artikel->gambar = $namafile;
            $nm->move(public_path().'/pdf', $namafile);
            $artikel->save();
            return redirect('artikelk')->with('status', 'Laporan Artikel Berhasil di Serahkan!!!');
    }
    public function destroy($id,Artikel $artikel)
    {
        $artikel->where('id',$id)->delete();
      return redirect('artikelk')->with('status', 'Data Berhasil di Hapus!!!');
    }
}