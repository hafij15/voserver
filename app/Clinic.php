<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Clinic extends Model
{
    protected $guarded = [];
    protected $fillable = [
        'name', 'phone','email', 'address',
    ];
}
