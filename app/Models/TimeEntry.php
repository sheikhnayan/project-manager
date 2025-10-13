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
        'task_type',
        'internal_task_id',
        'entry_date',
        'date',
        'hours',
        'description',
    ];

    protected $casts = [
        'date' => 'date',
        'entry_date' => 'date',
        'hours' => 'decimal:2',
        'task_type' => 'string',
    ];

    /**
     * Existing relationships (maintained for backward compatibility)
     */
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

    /**
     * New relationship for internal tasks
     */
    public function internalTask()
    {
        return $this->belongsTo(InternalTask::class, 'internal_task_id');
    }

    /**
     * Dynamic task relationship (returns either project task or internal task)
     */
    public function getTaskDetailsAttribute()
    {
        if ($this->task_type === 'internal') {
            return $this->internalTask;
        }
        return $this->task;
    }

    /**
     * Get display name for the task
     */
    public function getTaskNameAttribute()
    {
        if ($this->task_type === 'internal') {
            return $this->internalTask ? $this->internalTask->name : 'Unknown Internal Task';
        }
        return $this->task ? $this->task->name : 'Unknown Task';
    }

    /**
     * Get project name (null for internal tasks)
     */
    public function getProjectNameAttribute()
    {
        if ($this->task_type === 'internal') {
            return null;
        }
        return $this->project ? $this->project->name : 'Unknown Project';
    }

    /**
     * Check if this is a project time entry
     */
    public function isProjectEntry()
    {
        return $this->task_type === 'project';
    }

    /**
     * Check if this is an internal time entry
     */
    public function isInternalEntry()
    {
        return $this->task_type === 'internal';
    }

    /**
     * Scopes
     */
    public function scopeProjectEntries($query)
    {
        return $query->where('task_type', 'project');
    }

    public function scopeInternalEntries($query)
    {
        return $query->where('task_type', 'internal');
    }

    public function scopeForDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('entry_date', [$startDate, $endDate]);
    }
}
