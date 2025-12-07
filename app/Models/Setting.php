<?php

namespace App\Models;

use App\Traits\CompanyScope;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use CompanyScope;
    protected $fillable = [
        'time_format',
        'date_format', 
        'currency',
        'working_hour',
        'company_name',
        'logo',
        'company_id',
    ];

    /**
     * Get the company that owns the setting
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
