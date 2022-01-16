<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use RecursiveTree\Seat\Inventory\Jobs\UpdateInventory;
use RecursiveTree\Seat\Inventory\Observers\UniverseStationObserver;
use RecursiveTree\Seat\Inventory\Observers\UniverseStructureObserver;
use Seat\Eveapi\Models\Universe\UniverseStation;
use Seat\Eveapi\Models\Universe\UniverseStructure;

class AddRattingTables extends Migration
{
    public function up()
    {
//        if (!Schema::hasTable('recursive_tree_seat_inventory_tracked_corporations')) {
//            Schema::create('recursive_tree_seat_inventory_tracked_corporations', function (Blueprint $table) {
//                $table->bigInteger("corporation_id")->unsigned();
//                $table->bigIncrements("id");
//            });
//        }
    }

    public function down()
    {
        //Schema::drop('recursive_tree_seat_inventory_tracked_corporations');
    }
}

