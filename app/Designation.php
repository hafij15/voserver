<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Designation extends Model
{
    protected $guarded = [];
    public function company_info()
    {
        return $this->belongsTo(Company::class,'company_id');
    
    }
}
