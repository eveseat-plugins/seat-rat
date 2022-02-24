<?php

/*
 * This file is part of SeAT
 *
 * Copyright (C) 2015 to 2022 Leon Jacobs
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 */

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
