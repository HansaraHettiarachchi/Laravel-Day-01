<?php

namespace Tests\Feature;

use App\Models\Equipments;
use App\Models\Equipments_Has_Product;
use App\Models\NewUser;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class EquipmentsTest extends TestCase
{
    public function test_insert(): void
    {
        // Product::factory()->count(20)->create();
        $equip = Equipments::factory()->make();
        $equipHasProduct = Equipments_Has_Product::factory()->count(5)->make();

        $qty = [];
        $pId = [];
        $cost = [];

        for ($i = 0; $i < 5; $i++) {
            $qty[] = $equipHasProduct[$i]['qty'];
            $pId[] = $equipHasProduct[$i]['product_id'];
            $cost[] = $equipHasProduct[$i]['cost'];
        }

        $arr = [
            'name' => $equip['name'],
            'code' => $equip['code'],
            'price' => $equip['price'],
            'catName' => $equip['cat_id'],
            'rQty' => $qty,
            'rCost' => $cost,
            'rPId' => $pId,
        ];


        $user = NewUser::factory()->create();

        $response = $this->actingAs($user, 'new_user')
            ->post('/insert-equips', $arr);

        // Log::channel("myLog")->info(json_encode($response));

        $response->assertViewIs('equipmentList');

        $this->assertDatabaseHas('equipments', [
            'name' => $arr['name'],
            'code' => $arr['code'],
            'price' => $arr['price'],
            'cat_id' => $arr['catName'],
            'created_user' => $user->id,
        ]);

        for ($i = 0; $i < 5; $i++) {

            $this->assertDatabaseHas('equipments_has_product', [
                'qty' => $arr['rQty'][$i],
                'cost' => $arr['rCost'][$i],
                'product_id' => $arr['rPId'][$i],
            ]);
        }
    }

    public function test_update(): void
    {
        // Product::factory()->count(20)->create();
        $equip = Equipments::factory()->create();
        $equipHasProduct = Equipments_Has_Product::factory()->count(5)->create();


        $equipT = Equipments::factory()->make();

        $equipHasProductT = Equipments_Has_Product::factory()->count(4)->make();

        $qty = [];
        $pId = [];
        $cost = [];

        for ($i = 0; $i < count($equipHasProductT); $i++) {
            $qty[] = $equipHasProductT[$i]['qty'];
            $pId[] = $equipHasProductT[$i]['product_id'];
            $cost[] = $equipHasProductT[$i]['cost'];
        }

        $arr = [
            'equipId' => $equip['id'],
            'name' => $equipT['name'],
            'code' => $equip['code'],
            'price' => $equipT['price'],
            'catName' => $equip['cat_id'],
            'rQty' => $qty,
            'rCost' => $cost,
            'rPId' => $pId,
        ];


        $user = NewUser::factory()->create();

        $response = $this->actingAs($user, 'new_user')
            ->post('/edit-equip-submit', $arr);


        Log::channel('myLog')->info(json_encode($response));

        $response->assertViewIs('equipmentList');

        $this->assertDatabaseHas('equipments', [
            'id' => $arr['equipId'],
            'name' => $arr['name'],
            'code' => $arr['code'],
            'price' => $arr['price'],
            'cat_id' => $arr['catName'],
            'created_user' => $user->id,
        ]);

        for ($i = 0; $i < count($equipHasProductT); $i++) {

            $this->assertDatabaseHas('equipments_has_product', [
                // 'id' => $equipHasProductT[$i]['id'],
                'qty' => $arr['rQty'][$i],
                'cost' => $arr['rCost'][$i],
                'product_id' => $arr['rPId'][$i],
                'equipments_id' => $equip->id,
            ]);
        }

        foreach ($equipHasProduct as $key => $item) {
            $this->assertDatabaseMissing('equipments_has_product', [
                'id' => $item['id'],
                'qty' => $item['qty'],
                'cost' => $item['cost'],
                'product_id' => $item['product_id'],
                'equipments_id' => $equip->id,
            ]);
        }
    }

    public function test_delete(): void
    {
        $equip = Equipments::factory()->create();

        $user = NewUser::factory()->create();

        $rs = $this->actingAs($user, 'new_user')->get('/delete-equip?id=' . $equip->id);

        $rs->assertViewIs('equipmentList');

        $this->assertDatabaseHas('equipments', [
            'id' => $equip->id,
            'status' => "Deactive",
        ]);
    }

    //There is a Problem that need to be fixed
    // public function test_EquipData_isComing(): void
    // {
    //     $equip = Equipments::factory()->create();
    //     $equipHasProduct = Equipments_Has_Product::factory()->count(2)->create([
    //         'equipments_id' => $equip->id
    //     ]);

    //     $user = NewUser::factory()->create();
    //     $rs = $this->actingAs($user, "new_user")->post('load-equips-items', ['code' => $equip->code]);

    //     $responseData = json_decode($rs->getContent(), true);


    //     $this->assertIsArray($responseData);
    //     $this->assertCount(count($equipHasProduct), $responseData);
    //     $allResultMatch = false;

    //     foreach ($equipHasProduct as $item) {
            
    //         $productData = Product::find($item->product_id);
    //         $matchFound = false;
    //         foreach ($responseData as $innerItem) {
    //             Log::channel('myLog')->info('=====================Inner Item======================');
    //             Log::channel('myLog')->info($innerItem);
    //             Log::channel('myLog')->info('=====================PRoduct======================');
    //             Log::channel('myLog')->info($productData);
    //             Log::channel('myLog')->info('=====================Items======================');
    //             Log::channel('myLog')->info($item);

    //             if (
    //             $innerItem['name'] == $productData->name 
    //             && $innerItem['code'] == $productData->code 
    //             && $innerItem['cost'] == $item->cost 
    //             && $innerItem['qty'] == $item->qty 
    //             && $innerItem['subtot'] == $item->sub_total) {
    //                 $matchFound = true;

    //             Log::channel('myLog')->info('++++++++++++++++$productData->name ++++++++++++++++++++');

    //                 break;
    //             }
    //         }


    //         if (!$matchFound) {
    //             $allResultMatch = true;
    //             break;
    //         }
    //     }


    //     $this->assertTrue($allResultMatch);

    //     // foreach ($responseData as $rsData) {
    //     //     $this->assertArrayHasKey('No', $rsData);
    //     //     $this->assertArrayHasKey('name', $rsData);
    //     //     $this->assertArrayHasKey('code', $rsData);
    //     //     $this->assertArrayHasKey('cost', $rsData);
    //     //     $this->assertArrayHasKey('qty', $rsData);
    //     //     $this->assertArrayHasKey('subtot', $rsData);

    //     //     $product = Product::where('code', $rsData['code'])->first();
    //     //     $this->assertNotNull($product, "Product with code {$rsData['code']} not found");

    //     //     $equipProduct = Equipments_Has_Product::where([
    //     //         'equipments_id' => $equip->id,
    //     //         'product_id' => $product->id
    //     //     ])->first();

    //     //     $this->assertNotNull($equipProduct, "Equipment-Product not found");

    //     //     $this->assertEquals($product->name, $rsData['name']);
    //     //     $this->assertEquals($equipProduct->qty, $rsData['qty']);
    //     //     $this->assertEquals($equipProduct->cost, $rsData['cost']);
    //     //     $this->assertEquals($equipProduct->sub_total, $rsData['subtot']);
    //     // }
    // }

    protected function tearDown(): void
    {
        NewUser::truncate();
        Product::truncate();
        Equipments::truncate();
        Equipments_Has_Product::truncate();
        parent::tearDown();
    }
}
