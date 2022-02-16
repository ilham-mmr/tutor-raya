<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tutoring extends Model
{
    use HasFactory;

    public function tutor() {
        return $this->belongsTo(User::class,'tutor_id','id');
    }
    public function subject() {
        return $this->belongsTo(Subject::class, 'subject_id', 'id');
    }
}
