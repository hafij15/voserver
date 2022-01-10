<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WbsMaster extends Model
{
    protected $guarded = [];

    public function wbs()
    {
        return $this->belongsTo(Wbs::class, 'wbs_master_wbs_id');
    }

    public function projectPlanDetails()
    {
        return $this->belongsTo(ProjectplanDetail::class, 'wbs_master_project_plan_details_id');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'wbs_master_assignee_id');
    }

    public function reporters()
    {
        return $this->belongsTo(User::class, 'reporter');
    }

}
