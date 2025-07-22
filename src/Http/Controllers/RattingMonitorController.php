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
    public function addFavoriteSystem(Request $request){
        $request->validate([
            "add_favorite"=>"nullable|integer",
            "remove_favorite"=>"nullable|integer"
        ]);

        if($request->add_favorite !== null){
            if(SolarSystem::find($request->add_favorite) == null) {
                return redirect()->back()->with(["error"=>"Couldn't find system"]);
            }
            if(FavoriteSystem::where("system_id",$request->add_favorite)->exists()) {
                return redirect()->back()->with(["success"=>"System is already in favorites!"]);
            }

            $favorite = new FavoriteSystem();
            $favorite->system_id =$request->add_favorite;
            $favorite->save();

            return redirect()->back()->with(["success"=>"Successfully added system to favorites"]);
        }

        if($request->remove_favorite){
            FavoriteSystem::where("system_id",$request->remove_favorite)->delete();

            return redirect()->back()->with(["success"=>"Successfully removed system from favorites"]);
        }

        return redirect()->back();
    }

    public function user(Request $request, UserRattingDataTable $dataTable){
        $request->validate([
            "timeType"=>"nullable|in:days,month",
            "days" => "nullable|integer",
            "system" => "nullable|integer",
            "system_text" => "nullable|string",
            "month" => "nullable",
        ]);

        $timeType = $request->timeType ?: "days";
        $days = intval($request->days) ?: 30;
        $system = intval($request->system) ?: 30000142; //Jita
        $system_name = $request->system_text ?: "Jita";
        $month = $request->month;

        $favorites = FavoriteSystem::all();
        $ratting_entries = collect();

        return $dataTable
            ->render("rattingmonitor::rattingtable", compact("days", "system", "system_name", "ratting_entries", "favorites", "timeType", "month"));
    }

    public function character(Request $request, CharacterRattingDataTable $dataTable)
    {
        $request->validate([
            "timeType"=>"nullable|in:days,month",
            "days" => "nullable|integer",
            "system" => "nullable|integer",
            "system_text" => "nullable|string",
            "month" => "nullable",
        ]);

        $timeType = $request->timeType ?: "days";
        $days = intval($request->days) ?: 30;
        $system = intval($request->system) ?: 30000142; //Jita
        $system_name = $request->system_text ?: "Jita";
        $month = $request->month;

        $favorites = FavoriteSystem::all();
        $ratting_entries = collect();

        return $dataTable
            ->render("rattingmonitor::rattingtable", compact("days", "system", "system_name", "ratting_entries", "favorites", "timeType", "month"));
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