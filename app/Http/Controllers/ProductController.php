<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

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

    static function getProductByAny($id, $column)
    {
        $product = Product::where('status', 'Active')->where('id', $id)->first()->$column;

        if ($product) {
            return $product;
        } else {
            return "Procut not found";
        }
    }

    static function getProductByAny2($id, $column)
    {
        $product = Product::where('status', 'Active')->where($column, $id)->first();

        if ($product) {
            return $product;
        } else {
            return "Procut not found";
        }
    }



    function importProducts(Request $request)
    {
        $file = $request->file('data');
        $csvData = file_get_contents($file->getRealPath());

        if (empty($csvData)) {
            return json_encode([
                'status' => 'error',
                'data' => '<p class="text-danger text-center my-3">Empty file</p>'
            ]);
        }

        $rows = array_map('str_getcsv', explode("\n", $csvData));


        if (count($rows) < 1 || (count($rows) === 1 && empty($rows[0][0]))) {
            return json_encode([
                'status' => 'error',
                'data' => '<p class="text-danger text-center my-3">Empty file or no valid data</p>'
            ]);
        }

        foreach ($rows as $row) {
            if ($this->checkArr($row, $rows)) {
                return json_encode([
                    'status' => 'error',
                    'data' => '<p class="text-danger text-center my-3">Empty file</p>'
                ]);
            }
        }

        array_shift($rows);

        $error = [];

        foreach ($rows as $key => $row) {
            if (empty($row) || (count($row) === 1 && empty($row[0]))) {
                continue;
            }

            $row = array_pad($row, 4, null);

            $productName = $row[0] ?? null;
            $productCode = $row[1] ?? null;
            $productCost = $row[2] ?? 0;
            $productPrice = $row[3] ?? 0;

            if (empty($productName)) {
                $error[] = 'Product Name is Empty at row ' . $key + 1;
            } elseif (empty($productCode)) {
                $error[] = 'Product Code is Empty at row ' . $key + 1;
            } elseif (empty($productCost)) {
                $error[] = 'Product Cost is Empty at row ' . $key + 1;
            } elseif (empty($productPrice)) {
                $error[] = 'Product Price is Empty at row ' . $key + 1;
            }
        }

        // Log::channel('myLog')->info($error);

        if (count($error) > 0) {

            $rest = ' ';

            foreach ($error as $key => $value) {
                $rest .= '
                      <tr>
                        <th scope="row">' . $key + 1 . '</th>
                        <td class="text-danger">' . $value . '</td>
                     </tr>
                ';
            }

            $deign = '<table class="table border border-1 my-3">
                                <thead>
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Error At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                   ' . $rest . '
                                </tbody>
                            </table>';

            Log::channel('myLog')->info($deign);

            return json_encode([
                'status' => 'error',
                'data' => $deign
            ]);
        }

        foreach ($rows as $row) {
            if (empty($row) || (count($row) === 1 && empty($row[0]))) {
                continue;
            }

            $row = array_pad($row, 4, null);

            $product = new Product();
            $product->name = $row[0] ?? null;
            $product->code = $row[1] ?? null;
            $product->cost = $row[2] ?? 0;
            $product->price = $row[3] ?? 0;
            $product->status = "Active";
            $product->save();
        }

        return response()->json([
            'status' => 'success',
            'data' => "Operation Successful"
        ]);
    }

    function checkArr($arr, $totArr)
    {
        $i = 0;
        foreach ($arr as $key => $value) {
            if ($value == null) {
                $i++;
            }
        }

        Log::channel('myLog')->info($i);
        Log::channel('myLog')->info("=================================");
        Log::channel('myLog')->info(count($arr));


        if (count($arr) == 1 && $i == 1 && count($totArr) == 2) {
            return true;
        }

        return false;
    }
}
