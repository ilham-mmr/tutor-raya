<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model {
    use HasFactory;

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function tutoring() {
        return $this->hasOne(Tutoring::class, 'id', 'tutoring_id');
    }

     /**
     * Get the activity's image.
     */
    public function image()
    {
        return $this->morphMany(Image::class, 'imageable');
    }
}
