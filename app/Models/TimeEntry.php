<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TimeEntry extends Model
{
    // use SoftDeletes;

    protected $fillable = [
        'user_id',
        'project_id',
        'task_id',
        'entry_date',
        'date',
        'hours',
        'description',
    ];

    protected $casts = [
        'date' => 'date',
        'hours' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
