<?php

namespace App;
use App\User;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $guarded = [];
    public function project_lead()
    {
        return $this->belongsTo(User::class,'project_lead_id');
    
    }
    public function support_engg()
    {
        return $this->belongsTo(User::class,'support_engg_id');
    }
    public function wbs()
    {
        return $this->hasMany(Wbs::class);
    }
}
