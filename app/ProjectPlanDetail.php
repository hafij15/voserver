<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectPlanDetail extends Model
{
    protected $guarded = [];

    public function projectPlan()
    {
        return $this->belongsTo(ProjectPlan::class, 'project_plan_project_plan_id');
    }
}
