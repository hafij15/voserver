<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WorkPackage extends Model
{
    protected $guarded = [];

    public function subTask()
    {
        return $this->belongsTo(SubTask::class, 'work_package_subtask_id');
    }

    public function projectPlan()
    {
        return $this->hasMany(ProjectPlan::class, 'project_plan_work_package_id');
    }
}
