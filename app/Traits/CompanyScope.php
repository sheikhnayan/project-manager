<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait CompanyScope
{
    /**
     * Scope a query to only include records for the user's company
     */
    public function scopeForUserCompany(Builder $query, $user = null)
    {
        $user = $user ?? auth()->user();
        
        // Role ID 8 is super admin - they can see all companies
        if (!$user || $user->role_id == 8) {
            return $query;
        }
        
        if ($user->company_id) {
            return $query->where('company_id', $user->company_id);
        }
        
        // If user has no company, return empty result
        return $query->where('id', -1);
    }
    
    /**
     * Scope a query to only include active records for the user's company
     */
    public function scopeActiveForUserCompany(Builder $query, $user = null)
    {
        return $query->forUserCompany($user)->where('is_active', true);
    }
}