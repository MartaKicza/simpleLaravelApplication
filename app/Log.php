<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $fillable = [
        'data', 'method'
    ];


    public function loggable()
    {
        return $this->morphTo();
    }
}
