<?php

namespace RecursiveTree\Seat\RattingMonitor\Http\Datatables;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CharacterRattingDataTable extends RattingDataTable
{
    public function query($system,$start, $end)
    {
        return DB::table("corporation_wallet_journals")
            ->selectRaw(DB::raw("ROUND(SUM(tax/tax_rate)) as ratted_money"))
            ->selectRaw(DB::raw("IF (universe_names.name IS NOT NULL,universe_names.name,'Unknown Character') as character_name"))
            ->selectRaw(DB::raw("second_party_id as character_id"))
            ->where("ref_type","bounty_prizes")
            ->where("context_id",$system)
            ->where("context_id_type","system_id")
            ->whereDate("date" , ">=", $start)
            ->whereDate("date" , "<=", $end)
            ->join("corporation_infos","tax_receiver_id","=","corporation_infos.corporation_id")
            ->leftJoin("universe_names","second_party_id","=","universe_names.entity_id")
            ->groupBy("universe_names.name","second_party_id");
    }
}