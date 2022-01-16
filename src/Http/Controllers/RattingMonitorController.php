<?php

namespace RecursiveTree\Seat\RattingMonitor\Http\Controllers;

use Seat\Web\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Seat\Eveapi\Models\Sde\SolarSystem;
use Seat\Eveapi\Models\Wallet\CorporationWalletJournal;
use Seat\Eveapi\Models\Corporation\CorporationInfo;
use Seat\Eveapi\Models\RefreshToken;
use Carbon\Carbon;
use Seat\Web\Models\User;
use Seat\Eveapi\Models\Character\CharacterInfo;
use Seat\Eveapi\Models\Universe\UniverseName;


class RattingMonitorController extends Controller
{
    public function user(Request $request){
        $days = $request->days ?: 30;
        $system = $request->system ?: 30000142; //Jita
        $system_name = $request->system_text ?: "Jita";

        $corporation_info_table = (new CorporationInfo())->getTable();
        $refresh_token_table = (new RefreshToken())->getTable();
        $user_table = (new User())->getTable();
        $character_table = (new UniverseName())->getTable();

         $query1 = CorporationWalletJournal::select(
            DB::raw("ROUND(SUM(tax/tax_rate)) as tax"),
            DB::raw("$character_table.name as name"),
            DB::raw("$user_table.main_character_id as character_id")
        )
            ->where("ref_type","bounty_prizes")
            ->where("context_id",$system)
            ->where("context_id_type","system_id")
            ->whereDate("date" , ">=", Carbon::now()->subDays($days))
            ->join($corporation_info_table,"tax_receiver_id","=","$corporation_info_table.corporation_id")
            ->leftJoin($refresh_token_table,"second_party_id","$refresh_token_table.character_id")
            ->leftJoin($user_table,"$refresh_token_table.user_id","=","$user_table.id")
            ->leftJoin($character_table,"$user_table.main_character_id","=","$character_table.entity_id")
            ->groupBy("$character_table.name","$user_table.main_character_id")

            ->where("$character_table.name", "!=", "NULL")

            ->orderBy("tax","DESC");

         $ratting_entries = CorporationWalletJournal::select(
             DB::raw("ROUND(SUM(tax/tax_rate)) as tax"),
             DB::raw("IF ($character_table.name IS NOT NULL,$character_table.name,'Unknown Character') as name"),
             DB::raw("second_party_id as character_id"),
         )
             ->where("ref_type","bounty_prizes")
             ->where("context_id",$system)
             ->where("context_id_type","system_id")
             ->whereDate("date" , ">=", Carbon::now()->subDays($days))
             ->join($corporation_info_table,"tax_receiver_id","=","$corporation_info_table.corporation_id")

             ->leftJoin($refresh_token_table,"second_party_id","$refresh_token_table.character_id")
             ->leftJoin($user_table,"$refresh_token_table.user_id","=","$user_table.id")

             ->leftJoin($character_table,"second_party_id","=","$character_table.entity_id")
             ->groupBy("$character_table.name","second_party_id")

             ->where("$refresh_token_table.user_id", "=", null)

             ->union($query1)

             ->orderBy("tax","DESC")->get();


        //dd(json_encode($ratting_entries, JSON_PRETTY_PRINT));

        return view("rattingmonitor::rattingtable", compact("days","system","system_name", "ratting_entries"));
    }

    public function character(Request $request){
        $days = $request->days ?: 30;
        $system = $request->system ?: 30000142; //Jita
        $system_name = $request->system_text ?: "Jita";

        $corporation_info_table = (new CorporationInfo())->getTable();
        $character_table = (new UniverseName())->getTable();

        $ratting_entries = CorporationWalletJournal::select(
            DB::raw("ROUND(SUM(tax/tax_rate)) as tax"),
            DB::raw("IF ($character_table.name IS NOT NULL,$character_table.name,'Unknown Character') as name"),
            DB::raw("second_party_id as character_id"),
        )
            ->where("ref_type","bounty_prizes")
            ->where("context_id",$system)
            ->where("context_id_type","system_id")
            ->whereDate("date" , ">=", Carbon::now()->subDays($days))
            ->join($corporation_info_table,"tax_receiver_id","=","$corporation_info_table.corporation_id")
            ->leftJoin($character_table,"second_party_id","=","$character_table.entity_id")
            ->groupBy("$character_table.name","second_party_id")
            ->orderBy("tax","DESC")
            ->limit(100)->get();

        //dd(json_encode($ratting_entries, JSON_PRETTY_PRINT));

        return view("rattingmonitor::rattingtable", compact("days","system","system_name", "ratting_entries"));
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