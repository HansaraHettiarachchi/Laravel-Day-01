<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Equipments;
use App\Models\Equipments_Has_Product;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use League\CommonMark\Extension\CommonMark\Node\Inline\Code;

class EquipController extends Controller
{
    function createEquipments()
    {
        $products = Product::where('status', 'Active')->get();
        $categories = Categories::all();

        return view('equipments', compact('products', 'categories'));
    }

    function insertEquips(Request $request)
    {
        $data = $request->all();
        // Log::channel("myLog")->info($data);

        if (!$request->has('rQty')) {
            return "Please Select Equipments";
        }

        if ($data['catName'] == 0) {
            return "Categroy is Required";
        }

        if (Equipments::where('code', $data['code'])->first()) {
            return "There is equip already with this code, Please try again with new code";
        }

        DB::transaction(function () use ($data, $request) {
            $equip = new Equipments();
            $equip->name = $data['name'];
            $equip->code = $data['code'];
            $equip->price = $data['price'];

            $equip->cat_id = $data['catName'];
            $equip->tQty = array_sum($data['rQty']);
            $equip->sub_tot = array_sum($data['rCost']);
            $equip->created_user = Auth::guard('new_user')->user()->id;
            $equip->save();

            for ($i = 0; $i < count($data['rQty']); $i++) {
                $equip_has_product = new Equipments_Has_Product();

                $equip_has_product->qty = $data['rQty'][$i];
                $equip_has_product->cost = $data['rCost'][$i];
                $equip_has_product->product_id = $data['rPId'][$i];
                $equip_has_product->equipments_id = $equip->id;
                $equip_has_product->sub_total = $data['rCost'][$i] * $data['rQty'][$i];

                $equip_has_product->save();
            }
        });

        $categories = Categories::all();
        return view('equipmentList', compact('categories'));
    }

    function viewEquipments()
    {
        $categories = Categories::all();

        return view('equipmentList', compact('categories'));
    }

    function loadEquipsList(Request $request)
    {
        $req = $request->all();
        // Log::channel("myLog")->info($req);

        $search =  $req['search'] != null ? $req["search"]['value'] : ' ';

        $query = Equipments::where('status', 'Active');
        $totalCount = $query->count();

        if (!empty($search) && is_string($search)) {
            $query->where('name', 'like', '%' . $search . '%');
            $query->where('code', 'like', '%' . $search . '%');
        }

        if ($req['cat_id'] != 0) {
            $query->where('cat_id',  $req['cat_id']);
        }


        $data = $query->offset($req['start'] ?? 0)
            ->limit($req['length'] ?? 10)
            ->get();

        $arr = [];

        foreach ($data as $key => $equip) {
            $arr[] = [
                "No" => $key + 1 + ($req["start"] ?? 0),
                "Name" => $equip->name,
                "Code" => $equip->code,
                "Price" => $equip->price,
                "Quantity" => $equip->tQty,
                "Category" => CategoryController::getCaregoryById($equip->cat_id)->name,
                "Action" => '<button class="btn btn-primary editProductBtn" data-id="' . $equip->id . '">Edit</button>',
                "Delete" => '<button class="btn btn-danger deleteProductBtn" data-id="' . $equip->id . '">Delete</button>'
            ];
        }

        return response()->json([
            'draw' => $req['draw'] ?? 0,
            'recordsTotal' => $totalCount,
            'recordsFiltered' => $data->count(),
            'data' => $arr
        ]);

        // return response()->json([
        //     'draw' => $req['draw'] ?? 0,
        //     'recordsTotal' => $totalCount,
        //     'recordsFiltered' => empty($search) ? $data->count() : $totalCount,
        //     'data' => $arr
        // ]);
    }

    function deleteEquipments(Request $request)
    {
        $data = Equipments::find($request->input('id'));
        $data->status = "Deactive";
        $data->save();

        $categories = Categories::all();
        return view('equipmentList', compact('categories'));
    }

    function getEquipDetails(Request $request)
    {
        $products = Product::where('status', 'Active')->get();
        $categories = Categories::all();

        $equip = Equipments::find($request->input("id"));
        $equip_has_product = Equipments_Has_Product::where('equipments_id', $request->input("id"))->get();

        $data = [];


        foreach ($equip_has_product as $key => $value) {
            $data[] = [
                'qty' => $value->qty,
                'cost' => $value->cost,
                'sub_total' => $value->sub_total,
                'pId' => $value->product_id,
                'eHPId' => $value->id,
                'productName' => ProductController::getProductByAny($value->product_id, "name"),
                'code' => ProductController::getProductByAny($value->product_id, "code"),
                'cost' => ProductController::getProductByAny($value->product_id, "cost"),
            ];
        }

        return view('editEquips', compact('products', 'equip', 'data', 'categories'));
    }

    function editEquip(Request $request)
    {
        $data = $request->all();
        // Log::channel("myLog")->info($data);

        // Log::channel("myLog")->info($request->session()->get('user')->id);
        $equip = Equipments::find($data['equipId']);

        if (!$request->has('rQty')) {
            return "Please Select Equipments";
        }


        if ($data['catName'] == 0) {
            return "Categroy is Required";
        }

        $code = Equipments::where('code', $data['code'])->first();

        if ($code) {
            if ($code->code != $equip->code) {
                return "There is equip already with this code, Please try again with new code";
            }
        }

        $equip->name = $data['name'];
        $equip->code = $data['code'];
        $equip->price = $data['price'];
        $equip->cat_id = $data['catName'];
        $equip->tQty = array_sum($data['rQty']);
        $equip->sub_tot = array_sum($data['rCost']);
        $equip->created_user = Auth::guard('new_user')->user()->id;
        $equip->save();

        $equip_has_product2 =  Equipments_Has_Product::where('equipments_id', $data['equipId'])->get();

        foreach ($equip_has_product2 as $value) {
            $value->delete();
        }

        for ($i = 0; $i < count($data['rQty']); $i++) {
            // $equip_has_product = Equipments_Has_Product::find($data['eHPId'][$i]);
            $equip_has_product = new Equipments_Has_Product();

            $equip_has_product->qty = $data['rQty'][$i];
            $equip_has_product->cost = $data['rCost'][$i];
            $equip_has_product->product_id = $data['rPId'][$i];
            $equip_has_product->equipments_id = $equip->id;
            $equip_has_product->sub_total = $data['rCost'][$i] * $data['rQty'][$i];
            $equip_has_product->save();
        }

        $categories = Categories::all();
        return view('equipmentList', compact('categories'));
    }

    function loadEquipItems(Request $request)
    {
        $data = $request->all();

        $equips = Equipments::where('code', $data['code'])->first();

        $equip_has_product =  Equipments_Has_Product::where('equipments_id', $equips['id'])->get();

        $products = [];

        foreach ($equip_has_product as $key => $value) {
            $product =  ProductController::getProductByAny2($value['product_id'], 'id');

            $products[] = [
                "No" => $key + 1,
                "name" => $product->name,
                "code" => $product->code,
                "qty" => $value->qty,
                "cost" => $value->cost,
                "subtot" => $value->sub_total,
            ];
        }

        return json_encode($products);
    }
}
