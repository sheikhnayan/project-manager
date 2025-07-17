<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectTeamMember extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
