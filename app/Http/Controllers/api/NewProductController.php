<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NewProductController extends Controller
{
    function insertProduct(Request $request)
    {
        $data = $request->all();
        Log::channel("myLog")->info($data['name']);

        $existingProduct = Product::where('code', $data['code'])->first();

        if ($existingProduct !== null) {
            return "Product Already exist with this code";
        }

        $product = new Product();
        $product->name = $data['name'];
        $product->code = $data['code'];
        $product->cost = $data['cost'];
        $product->price = $data['price'];
        $product->status = 'Active';
        $product->save();

        return "Success";
    }
}
