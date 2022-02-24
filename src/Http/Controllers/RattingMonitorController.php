<?php

namespace RecursiveTree\Seat\RattingMonitor\Http\Controllers;

use RecursiveTree\Seat\RattingMonitor\Http\Datatables\CharacterRattingDataTable;
use RecursiveTree\Seat\RattingMonitor\Http\Datatables\UserRattingDataTable;
use RecursiveTree\Seat\RattingMonitor\Models\FavoriteSystem;
use Seat\Web\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Seat\Eveapi\Models\Sde\SolarSystem;
use Carbon\Carbon;


class RattingMonitorController extends Controller
{
    private function process_favorites(Request $request){
        if($request->add_favorite){
            if(SolarSystem::find($request->add_favorite) == null) return;
            if(FavoriteSystem::where("system_id",$request->add_favorite)->exists()) return;

            $favorite = new FavoriteSystem();
            $favorite->system_id =$request->add_favorite;
            $favorite->save();
        }

        if($request->remove_favorite){
            FavoriteSystem::where("system_id",$request->remove_favorite)->delete();
        }
    }

    public function user(Request $request, UserRattingDataTable $dataTable){
        $this->process_favorites($request);

        $days = intval($request->days) ?: 30;
        $system = intval($request->system) ?: 30000142; //Jita
        $system_name = $request->system_text ?: "Jita";

        $favorites = FavoriteSystem::all();
        $ratting_entries = collect();

        return $dataTable
            ->render("rattingmonitor::rattingtable", compact("days", "system", "system_name", "ratting_entries", "favorites"));
    }

    public function character(Request $request, CharacterRattingDataTable $dataTable)
    {
        $this->process_favorites($request);

        $days = intval($request->days) ?: 30;
        $system = intval($request->system) ?: 30000142; //Jita
        $system_name = $request->system_text ?: "Jita";

        $favorites = FavoriteSystem::all();
        $ratting_entries = collect();

        return $dataTable
            ->render("rattingmonitor::rattingtable", compact("days", "system", "system_name", "ratting_entries", "favorites"));
    }

    public function systems(Request $request){
        $query = $request->q;

        if($query==null){
            $systems = SolarSystem::limit(100)->get();
        } else {
            $systems = SolarSystem::where("name", "like", "%$query%")->limit(100)->get();
        }

        $suggestions = [];

        foreach ($systems as $system){
            $suggestions[] = [
                "text" => "$system->name",
                "value" => $system->system_id,
            ];
        }

        return response()->json($suggestions);
    }

    public function about(){
        return view("rattingmonitor::about");
    }
}