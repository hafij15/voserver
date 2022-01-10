<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectPlan extends Model
{
    protected $guarded = [];

    public function workPackage()
    {
        return $this->belongsTo(WorkPackage::class, 'project_plan_work_package_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'project_plan_assignee');
    }

    public function projectPlanDetails()
    {
        return $this->hasMany(ProjectPlanDetail::class, 'project_plan_project_plan_id');
    }


}
