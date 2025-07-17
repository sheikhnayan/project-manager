<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstimatedTimeEntry extends Model
{
    protected $fillable = [
        'user_id',
        'task_id',
        'hours',
        'entry_date',
        'project_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function task()
    {
        return $this->belongsTo(Task::class);
    }
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
