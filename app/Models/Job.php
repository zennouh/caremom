<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    protected $fillable = [
        'title',
        'description',
        'company_name',
        'location',
        'salary',
        'employment_type'
    ];
    public function applications()
    {
        return $this->hasMany(Application::class);
    }
}
