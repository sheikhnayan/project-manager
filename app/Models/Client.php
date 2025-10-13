<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    // use SoftDeletes;

    protected $fillable = [
        'name',
        'custom_id',
        'contact_person',
        'email',
        'phone',
        'address',
        'tax_number',
        'is_archived',
    ];

    protected $casts = [
        'is_archived' => 'boolean',
    ];

    public function projects()
    {
        return $this->hasMany(Project::class);
    }
}
