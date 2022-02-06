<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Transaction extends Model
{
    protected $connection = "mongodb";

    protected $collection = "transaction";

    protected $with = ['connote'];

    public function connote()
    {
        return $this->hasOne(Connote::class, 'transaction_id', 'transaction_id');
    }
}