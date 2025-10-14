<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InternalTask extends Model
{
    protected $fillable = [
        'name',
        'description',
        'department',
        'hourly_rate',
        'is_active',
        'requires_approval',
        'max_hours_per_day',
        'company_id',
        'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'requires_approval' => 'boolean',
        'hourly_rate' => 'decimal:2',
        'max_hours_per_day' => 'integer',
    ];

    /**
     * Relationship with TimeEntry
     */
    public function timeEntries()
    {
        return $this->hasMany(TimeEntry::class, 'internal_task_id');
    }

    /**
     * Relationship with Company (for multi-tenancy)
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Relationship with User (creator)
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relationship with Department (based on department name)
     */
    public function departmentModel()
    {
        return $this->belongsTo(Department::class, 'department', 'name');
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    public function scopeByDepartment($query, $department)
    {
        return $query->where('department', $department);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Helper methods
     */
    public function getTotalHoursAttribute()
    {
        return $this->timeEntries()->sum('hours');
    }

    public function getAvailableDepartments()
    {
        return [
            'HR' => 'Human Resources',
            'IT' => 'Information Technology', 
            'Marketing' => 'Marketing',
            'Sales' => 'Sales',
            'Finance' => 'Finance',
            'Operations' => 'Operations',
            'Admin' => 'Administration',
            'Training' => 'Training & Development',
            'Legal' => 'Legal',
            'Other' => 'Other'
        ];
    }

    public function getAvailableCategories()
    {
        return [
            'Meeting' => 'Meetings',
            'Training' => 'Training',
            'Administrative' => 'Administrative Work',
            'Planning' => 'Planning & Strategy',
            'Research' => 'Research & Development',
            'Documentation' => 'Documentation',
            'Review' => 'Code/Work Review',
            'Support' => 'Internal Support',
            'Maintenance' => 'System Maintenance',
            'Other' => 'Other Activities'
        ];
    }

    /**
     * Check if user can log hours to this task today
     */
    public function canLogHours($userId, $date, $requestedHours = 0)
    {
        if (!$this->is_active) {
            return ['allowed' => false, 'message' => 'This internal task is not active.'];
        }

        // Check daily hour limit if set
        if ($this->max_hours_per_day) {
            $existingHours = TimeEntry::where('internal_task_id', $this->id)
                ->where('user_id', $userId)
                ->whereDate('entry_date', $date)
                ->sum('hours');

            if (($existingHours + $requestedHours) > $this->max_hours_per_day) {
                return [
                    'allowed' => false, 
                    'message' => "Daily limit of {$this->max_hours_per_day} hours exceeded for this task."
                ];
            }
        }

        return ['allowed' => true, 'message' => 'Hours can be logged.'];
    }
}
