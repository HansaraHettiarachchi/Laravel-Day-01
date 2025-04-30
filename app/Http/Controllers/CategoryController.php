<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    function insertCategory(Request $request)
    {

        $data = $request->all();

        if (Categories::where('name', $data['catName'])->first()) {
            return "Category already Present";
        }

        $cat = new Categories();
        $cat->name = $data['catName'];
        $cat->save();

        return view('createCategory');
    }

    static function getCaregoryById($id)
    {
        $data =  Categories::find($id);

        if ($data) {
            return $data;
        } else {
            return "Category Not found";
        }
    }

    static function findCategoryByName($text)
    {
        $data =  Categories::where("name", 'like', '%' . $text . '%')->first();

        if ($data) {
            return $data;
        } else {
            return " ";
        }
    }
}
