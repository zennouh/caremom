<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    protected $fillable = [
        'resume_url'
    ];
    public function applications()
    {
        return $this->hasMany(Application::class);
    }
}
