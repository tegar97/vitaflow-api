<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sportTrackingActivity extends Model
{
    use HasFactory;

    // relation with exercise types
    public function exerciseType()
    {
        return $this->belongsTo(exerciseType::class);
    }
}
