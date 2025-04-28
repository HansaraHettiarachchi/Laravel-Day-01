<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    function createProduct(Request $request)
    {
        $data = $request->all();

        $existingProduct = Product::where('code', $data['code'])->first();

        if ($existingProduct !== null) {
            return "Product Already exist with this code";
        }


        $imageName = time() . "." . $data['imageUpload']->getClientOriginalExtension();
        $data['imageUpload']->storeAs("public/products", $imageName);

        $product = new Product();
        $product->name = $data['name'];
        $product->code = $data['code'];
        $product->cost = $data['cost'];
        $product->price = $data['price'];
        $product->img_location =  $imageName;
        $product->save();

        return "Success";
    }

    function getAllProducts(Request $request)
    {
        $data = $request->all();
        $total = Product::count();

        $products = Product::offset($data['start'])->limit($data['length'])->get();

        $arr = [];

        foreach ($products as $key => $product) {
            if ($product["status"] !== "Deactive") {
                $arr[] = [
                    "No" => $key + 1 + $data["start"],
                    "Name" => $product['name'],
                    "Code" => $product['code'],
                    "Cost" => $product['cost'],
                    "Price" => $product['price'],
                    "Action" => '<button class="btn btn-primary editProductBtn" data-id="' . $product->id . '">Edit</button>',
                    "Delete" => '<button class="btn btn-primary deleteProductBtn" data-id="' . $product->id . '">Delete</button>',
                    "Picture" => '<img src="/storage/products/' . asset($product["img_location"]) . '" >'
                ];
            }
        }

        return response()->json([
            'draw' => $data['draw'],
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $arr
        ]);

        Log::channel('myLog')->info($products);

        // Log::channel('myLog')->info($data);
    }

    function viewEditProducts(Request $request)
    {
        $data = Product::find($request->input("id"));

        return view("editProducts", compact("data"));
    }

    function updateProducts(Request $request)
    {
        $data = $request->all();
        $product = Product::find($data['id']);
        $imageName = $product->img_location;

        // Log::channel("myLog")->info($data);

        if ($request->hasFile("file")) {
            Storage::delete("public/products/" . $product->img_location);
            $imageName = time() . "." . $data['file']->getClientOriginalExtension();
            $data['file']->storeAs("public/products", $imageName);
        }


        $product->id = $data['id'];
        $product->name = $data['name'];
        $product->code = $data['code'];
        $product->cost = $data['cost'];
        $product->price = $data['price'];
        $product->img_location =  $imageName;

        $product->save();

        return view('productListView');
    }

    function deleteProducts(Request $request)
    {
        $product = Product::find($request->input("id"));

        $product->status = "Deactive";
        // Log::channel("myLog")->info("public/products/" . $product->img_location);

        if ($product->img_location !== null) {
            Storage::delete("public/products/" . $product->img_location);
        }

        $product->img_location = null;
        $product->save();


        return "Product Deleted Succesfully";
    }

    function getProductById(Request $request)
    {
        $product = Product::where('status', 'Active')->where('id', $request->input('id'))->first();

        if ($product) {
            return json_encode($product);
        } else {
            return "Procut not found";
        }
    }
}
