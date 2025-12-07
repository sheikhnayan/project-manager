<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'company_id',
        'created_by',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    /**
     * Get the company that owns the department
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the categories for the department
     */
    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    /**
     * Get the internal tasks for the department
     */
    public function internalTasks()
    {
        return $this->hasMany(InternalTask::class, 'department', 'id');
    }

    /**
     * Get the users assigned to this department
     */
    public function assignedUsers()
    {
        return $this->belongsToMany(User::class, 'department_user_assignments', 'department_id', 'user_id')
                    ->withTimestamps();
    }

    /**
     * Get the user who created this department
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope a query to only include active departments
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to filter by company
     */
    public function scopeForCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }
}
