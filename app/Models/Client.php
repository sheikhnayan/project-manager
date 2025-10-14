<?php

namespace App\Models;

use App\Traits\CompanyScope;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use CompanyScope;
    // use SoftDeletes;

    protected $fillable = [
        'name',
        'custom_id',
        'contact_person',
        'email',
        'phone',
        'address',
        'tax_number',
        'company_id',
        'is_archived',
    ];

    protected $casts = [
        'is_archived' => 'boolean',
    ];

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
