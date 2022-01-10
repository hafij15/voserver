<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{

	protected $guarded = [];
    // protected $fillable = [
    // 	'title', 'description', 'path', 'sub_task_id'
    // ];

    public function subTask()
    {
        return $this->belongsTo(SubTask::class, 'sub_task_id');
    }
}
