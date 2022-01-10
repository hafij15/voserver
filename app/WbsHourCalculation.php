<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WbsHourCalculation extends Model
{
    protected $table = 'wbs_hour_calculation';
    protected $guarded = [];

    public function wbs_user()
    {
        return $this->belongsTo(User::class,'wbs_user_id');
    }
    public function wbs()
    {
        return $this->belongsTo(wbs::class,'wbs_id');
    }
}
