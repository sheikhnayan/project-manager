<?php

namespace App\Models;

use App\Traits\CompanyScope;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use CompanyScope;
    // use SoftDeletes;

    protected $fillable = [
        'project_number',
        'name',
        'description',
        'client_id',
        'company_id',
        'start_date',
        'end_date',
        'status',
        'budget',
        'is_archived',
        'expected_profit',
        'manual_progress',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'budget' => 'decimal:2',
        'is_archived' => 'boolean',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function teamMembers()
    {
        return $this->hasMany(TaskAssignee::class);
    }

    public function timeEntries()
    {
        return $this->hasMany(TimeEntry::class);
    }

    public function estimatedtimeEntries()
    {
        return $this->hasMany(EstimatedTimeEntry::class);
    }

    public function members()
    {
        return $this->hasMany(ProjectTeamMember::class, 'project_id', 'id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
