<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jobapplication extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Relationship To User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relationship To Jobadvertisement
    public function jobadvertisement()
    {
        return $this->belongsTo(Jobadvertisement::class, 'jobadvertisement_id');
    }

    // Relationship To Cv
    public function cv()
    {
        return $this->belongsTo(Cv::class, 'cv_id');
    }
}
