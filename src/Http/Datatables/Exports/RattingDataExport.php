<?php

namespace RecursiveTree\Seat\RattingMonitor\Http\Datatables\Exports;

use Seat\Eveapi\Models\Universe\UniverseName;
use Yajra\DataTables\Exports\DataTablesCollectionExport;

class RattingDataExport extends DataTablesCollectionExport
{
    public function collection()
    {
        $character_ids = $this->collection->pluck("character_id");
        $characters = UniverseName::whereIn("entity_id",$character_ids)->get();

        return $this->collection->map(function ($entry) use ($characters) {
            $character = $characters->firstWhere("entity_id",$entry["character_id"]);

            return [
                $character ? $character->name : "Unknown Character ".$entry["character_id"],
                $entry["ratted_money"]
            ];
        });
    }

    public function headings(): array {
        return [
            'Character',
            'Ratted ISK',
        ];
    }
}
