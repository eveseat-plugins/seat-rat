<?php

namespace RecursiveTree\Seat\RattingMonitor\Http\Datatables;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserRattingDataTable extends RattingDataTable
{
    public function query($system,$start, $end)
    {
        $notLinkedCharacters = DB::table("corporation_wallet_journals")
            ->selectRaw(DB::raw("ROUND(SUM(tax/tax_rate)) as ratted_money"))
            ->selectRaw(DB::raw("IF (universe_names.name IS NOT NULL,universe_names.name,'Unknown Character') as character_name"))
            ->selectRaw(DB::raw("second_party_id as character_id"))
            ->whereIn("ref_type",["bounty_prizes","ess_escrow_transfer"])
            ->where("context_id",$system)
            ->whereDate("date" , ">=", $start)
            ->whereDate("date" , "<=", $end)
            ->join("corporation_infos","tax_receiver_id","=","corporation_infos.corporation_id")

            ->leftJoin("refresh_tokens","second_party_id","refresh_tokens.character_id")
            ->leftJoin("users","refresh_tokens.user_id","=","users.id")

            ->leftJoin("universe_names","second_party_id","=","universe_names.entity_id")
            ->groupBy("universe_names.name","second_party_id")

            ->where("refresh_tokens.user_id", "=", null);

        return DB::table("corporation_wallet_journals")
            ->selectRaw(DB::raw("ROUND(SUM(tax/tax_rate)) as ratted_money"))
            ->selectRaw(DB::raw("universe_names.name as character_name"))
            ->selectRaw(DB::raw("users.main_character_id as character_id"))
            ->whereIn("ref_type",["bounty_prizes","ess_escrow_transfer"])
            ->where("context_id",$system)
            ->whereDate("date" , ">=", $start)
            ->whereDate("date" , "<=", $end)
            ->join("corporation_infos","tax_receiver_id","=","corporation_infos.corporation_id")
            ->leftJoin("refresh_tokens","second_party_id","refresh_tokens.character_id")
            ->leftJoin("users","refresh_tokens.user_id","=","users.id")
            ->leftJoin("universe_names","users.main_character_id","=","universe_names.entity_id")
            ->groupBy("universe_names.name","users.main_character_id")
            ->where("universe_names.name", "!=", "NULL")
            ->union($notLinkedCharacters);
    }
}