<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    // use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'project_id',
        'start_date',
        'end_date',
        'status',
        'priority',
        'progress',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'progress' => 'integer',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function assignees()
    {
        return $this->belongsToMany(User::class, 'task_assignees');
    }

    public function dependencies()
    {
        return $this->belongsToMany(Task::class, 'task_dependencies', 'task_id', 'dependency_id');
    }

    public function timeEntries()
    {
        return $this->hasMany(TimeEntry::class);
    }
}
