<?php

namespace RecursiveTree\Seat\RattingMonitor\Http\Controllers;

use Seat\Web\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Seat\Eveapi\Models\Wallet\CorporationWalletJournal;

class RattingMonitorController extends Controller
{
    public function user(Request $request){
        return view("rattingmonitor::character");
    }

    public function character(Request $request){
        $data = CorporationWalletJournal::all();
        dd(json_encode($data, JSON_PRETTY_PRINT));
        return view("rattingmonitor::character");
    }
}