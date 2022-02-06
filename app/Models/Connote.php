<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Connote extends Model
{
    protected $connection = "mongodb";

    protected $collection = "connote";

    protected $with = ['koli'];

    public function koli() {
        return $this->hasMany(Koli::class, 'connote_id', 'connote_id');
    }
}