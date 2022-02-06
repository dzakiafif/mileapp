<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Location extends Model
{
    protected $connection = "mongodb";

    protected $collection = "location";

    public $timestamps = false;
    
}