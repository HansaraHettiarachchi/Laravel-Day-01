<?php

namespace Tests\Feature;

use App\Models\NewUser;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductListViewTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/product-list');
        $response->assertStatus(200);
        $response->assertViewIs('productListView');
    }

    public function test_insert(): void
    {
        $testData = Product::factory()->make()->toArray();


        $file = \Illuminate\Http\UploadedFile::fake()
            ->create('product.txt', 100);

        $rs =  $this->post('/save-product', array_merge($testData, [
            'imageUpload' => $file
        ]));

        $rs->assertStatus(200);
        $rs->assertSeeText("Success");

        $this->assertDatabaseHas('product', [
            'name' => $testData['name'],
            'code' => $testData['code'],
            'cost' => $testData['cost'],
            'price' => $testData['price'],
        ]);

        $response =  $this->post('/save-product', array_merge($testData, [
            'imageUpload' => $file
        ]));

        $response->assertStatus(200);
        $response->assertSeeText("Product Already exist with this code");
    }


    public function test_update(): void
    {
        $testData = Product::factory()->create()->toArray();


        $file = \Illuminate\Http\UploadedFile::fake()
            ->create('product.txt', 100);

        // Log::channel("myLog")->info($testData);

        $rs =  $this->post('/update-products', array_merge($testData, [
            'imageUpload' => $file
        ]));


        $rs->assertStatus(200);
        $rs->assertViewIs("productListView");

        $this->assertDatabaseHas('product', [
            'name' => $testData['name'],
            'code' => $testData['code'],
            'cost' => $testData['cost'],
            'price' => $testData['price'],
        ]);
    }

    public function test_delete(): void
    {
        Storage::fake('public');

        $file = \Illuminate\Http\UploadedFile::fake()
            ->create('product.txt', 100);

        $path = $file->store('public/products');

        $product = Product::factory()->create([
            'status' => 'Active',
            'img_location' => str_replace('public/products/', '', $path)
        ]);

        $response = $this->get('/delete-products?id=' . $product->id);

        $response->assertStatus(200);
        $response->assertSee("Product Deleted Successfully");

        $product->refresh();

        $this->assertEquals('Deactive', $product->status);
        $this->assertNull($product->img_location);
        $this->assertFalse(Storage::disk('public')->exists('products/' . $path));
    }

    protected function tearDown(): void
    {
        Product::truncate();
        parent::tearDown();
    }
}
