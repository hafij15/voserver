<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Day extends Model
{
    protected $guarded = [];
    protected $fillable = [
        'name',
    ];
}
