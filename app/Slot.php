<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Slot extends Model
{
    protected $guarded = [];
    protected $fillable = [
        'start_time', 'end_time',
    ];
}
