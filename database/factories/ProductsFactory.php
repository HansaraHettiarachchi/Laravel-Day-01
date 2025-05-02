<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Products>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->words(3, true),
            'code' => Str::upper(Str::random(6)),
            'cost' => $this->faker->randomFloat(2, 10, 100),
            'price' => $this->faker->randomFloat(2, 50, 500),
            'img_location' => $this->faker->imageUrl(640, 480, 'product', true),
        ];
    }
}
