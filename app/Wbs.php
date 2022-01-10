<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wbs extends Model
{
    protected $guarded = [];
    public function users()
    {
        return $this->belongsTo(User::class, 'wbs_master_assignee_id');
    }
    public function projects()
    {
        return $this->belongsTo(Project::class,'wbs_project_id');
    }
    public function wbsHour()
    {
        return $this->hasMany(WbsHourCalculation::class,'wbs_id');
    }
}
