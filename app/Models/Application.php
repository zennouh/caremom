<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $fillable = [
        'job_id',
        'mom_id',
        'candidate_id',
        'cover_letter',
        'status'
    ];
    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }
}
