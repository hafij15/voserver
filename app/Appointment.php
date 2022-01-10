<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $guarded = [];
    public function users()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }
    public function pm_info()
    {
        return $this->belongsTo(User::class, 'pm_id');
    }

    public function projectPlan()
    {
        return $this->belongsTo(ProjectPlan::class, 'project_plan_id');
    }

    public function subTask()
    {
        return $this->belongsTo(SubTask::class, 'subtask_id');
    }

    public function slots()
    {
        return $this->belongsTo(Slot::class, 'slot_id');
    }

}
