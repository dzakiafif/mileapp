<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Koli extends Model {

    protected $connection = "mongodb";

    protected $collection = "koli";

    public function connote()
    {
        return $this->belongsTo(Connote::class,'connote_id', 'connote_id');
    }
}