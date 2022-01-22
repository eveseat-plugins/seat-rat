<?php

namespace RecursiveTree\Seat\RattingMonitor\Models;

use Illuminate\Database\Eloquent\Model;
use Seat\Eveapi\Models\Sde\SolarSystem;

class FavoriteSystem extends Model
{
    public $timestamps = false;

    protected $table = 'recursive_tree_seat_rat_favorites';

    public function system(){
        return $this->hasOne(SolarSystem::class,"system_id","system_id");
    }
}