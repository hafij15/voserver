<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tdo extends Model
{
    protected $guarded = [];

    public function subTask()
    {
        return $this->hasMany(SubTask::class, 'subtask_tdo_id');
    }
     public function users()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
