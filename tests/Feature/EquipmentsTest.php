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

    public function test_EquipData_isComing(): void
    {
        $equip = Equipments::factory()->create();
        $equipHasProduct = Equipments_Has_Product::factory()->count(2)->create([
            'equipments_id' => $equip->id
        ]);

        $user = NewUser::factory()->create();
        $rs = $this->actingAs($user, "new_user")->post('load-equips-items', ['code' => $equip->code]);

        $responseData = json_decode($rs->getContent(), true);

        // Log::channel('myLog')->info('Equipment Items Response:', $responseData);

        $this->assertIsArray($responseData);
        $this->assertCount(count($equipHasProduct), $responseData);

        foreach ($responseData as $rsData) {
            $this->assertArrayHasKey('No', $rsData);
            $this->assertArrayHasKey('name', $rsData);
            $this->assertArrayHasKey('code', $rsData);
            $this->assertArrayHasKey('cost', $rsData);
            $this->assertArrayHasKey('qty', $rsData);
            $this->assertArrayHasKey('subtot', $rsData);

            $filter = array_filter($equipHasProduct->toArray(), function ($item) use ($rsData) {
                return $item['code'] = $rsData['code'];
            });

            Log::channel('myLog')->info($equipHasProduct);

            // $product = Product::find($filter->product_id);

            // $this->assertEquals($filter->name, $rsData['name']);
        }
    }

    protected function tearDown(): void
    {
        NewUser::truncate();
        Equipments::truncate();
        Equipments_Has_Product::truncate();
        parent::tearDown();
    }
}
