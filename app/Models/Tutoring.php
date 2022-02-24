<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tutoring extends Model {
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'start_time',
        'end_time'
    ];

    public function scopeOfBetween($query, $startTime) {
        return $query->whereRaw("? BETWEEN start_time and end_time", $startTime);
    }

    public function tutor() {
        return $this->belongsTo(User::class, 'tutor_id', 'id');
    }
    public function subject() {
        return $this->belongsTo(Subject::class, 'subject_id', 'id');
    }
}
