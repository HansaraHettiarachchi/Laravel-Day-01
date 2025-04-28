<?php

namespace App\Http\Controllers;

use App\Models\Equipments;
use App\Models\Equipments_Has_Product;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EquipController extends Controller
{
    function viewEquipmentList()
    {
        $products = Product::where('status', 'Active')->get();

        return view('equipments', compact('products'));
    }

    function insertEquips(Request $request)
    {
        $data = $request->all();
        // Log::channel("myLog")->info($request->session()->get('user')->id);

        if (!$request->has('rQty')) {
            return "Please Select Equipments";
        }

        if (Equipments::where('code', $data['code'])->first()) {
            return "There is equip already with this code, Please try again with new code";
        }

        $equip = new Equipments();
        $equip->name = $data['name'];
        $equip->code = $data['code'];
        $equip->price = $data['price'];
        $equip->tQty = array_sum($data['rQty']);
        $equip->sub_tot = array_sum($data['rCost']);
        $equip->created_user = $request->session()->get('user')->id;
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

        return "Equip Created Successful";
    }

    function loadEquipsList()
    {
        // Load Data to Table using server side rendering
        
        $data = Equipments::all();


        return view("equipmentList", compact('data'));
    }
}
