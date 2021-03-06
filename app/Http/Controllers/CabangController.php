<?php

namespace App\Http\Controllers;

use App\Cabang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Jenssegers\Agent\Agent as Agent;

class CabangController extends Controller
{
    public function data()
    {
        $Agent = new Agent();
        $cabangs = DB::table('cabangs')->paginate(20);
        //dd($dkaryawan);
        //return $dkaryawan;
        //return view('dkaryawan.data',['dkaryawan'=> $dkaryawan]);
        if ($Agent->isMobile()) {
            return view('mobile.cabang.data', compact('cabangs'));
        } else {
            return view('cabang.data', compact('cabangs'));
            }
        }
    public function add()
    {   
        $Agent = new Agent();
        if ($Agent->isMobile()) {
            return view('mobile.cabang.add');
        } else {
            return view('cabang.add');
            }
    }
    public function addProcess(Request $request)
    {
        $request->validate([
            'namacbg' => 'required|min:3',
        ],[
            'namacbg.required' => 'Wilayah Samchick tidak boleh kosong!!!'
        ]);

        DB::table('cabangs')->insert([
            'namacbg' => $request->namacbg,
            'alamat' => $request->alamat
        ]);
        return redirect('cabang')->with('status', 'Wilayah Samchick Berhasil Ditambah!!!');
    }
    public function edit($id)
    {
        $Agent = new Agent();
        $cabangg = DB::table('cabangs')->where('id', $id)->first();
        if ($Agent->isMobile()) {
            return view('mobile.cabang/edit', compact('cabangg'));
        } else {
            return view('cabang/edit', compact('cabangg'));
            }



       
        
    }
    public function editProcess(Request $request, $id)
    {
        $request->validate([
            'namacbg' => 'required|min:3',
            'alamat' => 'required|min:3'
        ],[
            'namacbg.required' => 'Wilayah Samchick tidak boleh kosong!!!',
            'alamat.required' => 'Alamat Samchick tidak boleh kosong!!!'
        ]);
        DB::table('cabangs')->where('id', $id)->update([
            'namacbg' => $request->namacbg,
            'alamat' => $request->alamat
        ]);
        return redirect('cabang')->with('status', 'Wilayah Samchick Berhasil update!!!');
    }
    public function delete($id)
    {
        DB::table('cabangs')->where('id', $id)->delete();
        return redirect('cabang')->with('status', 'Wilayah Samchick berhasil dihapus!!!');
    }
}
