<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $fillable = [
        'job_id',
        'mom_id',
        'cover_letter',
        'status'
    ];
    public function job()
    {
        return $this->belongsTo(Job::class);
    }
}
