<?php

namespace App\Models;

use App\Http\Resources\CategoryResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    public function category() {
        return $this->belongsTo(Category::class,'category_id','id');
    }

    /**
     *  get the current category
     */
    public function getCategorySubject() {
        $category = $this->category()->get();
        dd(new CategoryResource($category));
        return new CategoryResource($category);
    }
}
