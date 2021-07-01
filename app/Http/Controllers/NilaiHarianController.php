<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Jenssegers\Agent\Agent as Agent;
class NilaiHarianController extends Controller
{
    public function index()
    {
    $Agent = new Agent();
    if ($Agent->isMobile()) {
        return view('mobile.nilaiharian.index');
    } else {
        return view('nilaiharian.index',);
        }
    }
}
