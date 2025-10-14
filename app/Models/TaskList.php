<?php

namespace App\Models;

use App\Traits\CompanyScope;
use Illuminate\Database\Eloquent\Model;

class TaskList extends Model
{
    use CompanyScope;
    protected $fillable = ['name', 'country_id', 'position', 'company_id'];
    
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Get the company that owns the task list
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Scope a query to filter by company
     */
    public function scopeForCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }
}
