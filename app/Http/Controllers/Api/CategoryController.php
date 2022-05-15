<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;


class CategoryController extends Controller {
    public function index(Request $request) {

        return CategoryResource::collection(Category::all());
    }
}
