<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Categories;
use App\Models\Equipments;
use App\Models\Equipments_Has_Product;
use App\Models\NewUser;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NewEquipController extends Controller
{
    function insertEquip(Request $request)
    {
        $data = $request->all();

        // return ;
        // Log::channel("myLog")->info($data);

        if ($data['catName'] == 0) {
            return "Categroy is Required";
        }

        if (Equipments::where('code', $data['code'])->first()) {
            return "There is equip already with this code, Please try again with new code";
        }

        $user = NewUser::where('name', $data['user'])->first();

        if (!$user) {
            $user->name = "Api User";
            $user->address = "Galle Sri Lnaka";
            $user->email = "apiUser@gmail.com";
            $user->passwrod = "root";
            $user->status = "Active";
            $user->save();
        }

        $equip = new Equipments();
        $equip->name = $data['name'];
        $equip->code = $data['code'];
        $equip->price = $data['price'];
        $equip->cat_id = $data['catName'];
        $equip->tQty = array_sum(array_column($data['products'], 'qty'));
        $equip->sub_tot = array_sum(array_column($data['products'], 'cost'));
        $equip->created_user = $user->id;
        $equip->save();


        foreach ($data['products'] as $value) {
            $equip_has_product = new Equipments_Has_Product();

            $equip_has_product->qty = $value['qty'];
            $equip_has_product->cost = $value['cost'];

            $product = Product::where("code", $value['code'])->first();

            $equip_has_product->product_id = $product['id'];
            $equip_has_product->equipments_id = $equip->id;
            $equip_has_product->sub_total = $value['qty'] *  $value['cost'];

            $equip_has_product->save();
        }

        // for ($i = 0; $i < count($data['rQty']); $i++) {
        //     $equip_has_product = new Equipments_Has_Product();

        //     $equip_has_product->qty = $data['rQty'][$i];
        //     $equip_has_product->cost = $data['rCost'][$i];
        //     $equip_has_product->product_id = $data['rPId'][$i];
        //     $equip_has_product->equipments_id = $equip->id;
        //     $equip_has_product->sub_total = $data['rCost'][$i] * $data['rQty'][$i];

        //     $equip_has_product->save();
        // }


        return "Successfull";
    }
}
