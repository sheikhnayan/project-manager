<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = ['name'];
    
    public function taskLists()
    {
        return $this->hasMany(TaskList::class);
    }
}
