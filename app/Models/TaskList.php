<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskList extends Model
{
    protected $fillable = ['name', 'country_id', 'position'];
    
    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
