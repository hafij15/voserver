<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubTask extends Model
{
    protected $guarded = [];
    
    public function tdo()
    {
        return $this->belongsTo(Tdo::class, 'subtask_tdo_id');
    }

    public function workPackage()
    {
        return $this->hasMany(WorkPackage::class, 'work_package_subtask_id');
    }

    public function files()
    {
        return $this->hasMany(File::class, 'sub_task_id');
    }
}
