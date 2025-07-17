<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    // use SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
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
