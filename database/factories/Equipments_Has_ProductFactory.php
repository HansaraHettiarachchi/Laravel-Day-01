<?php

namespace Database\Factories;

use App\Models\Equipments_Has_Product;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class Equipments_Has_ProductFactory extends Factory
{

    protected $model = Equipments_Has_Product::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'qty' => $this->faker->numberBetween(1, 10),
            'cost' => $this->faker->randomFloat(2, 10, 1000),
            'sub_total' => function (array $attributes) {
                return $attributes['qty'] * $attributes['cost'];
            },
            'product_id' => Product::factory()->create()->id,
            'equipments_id' => $this->faker->numberBetween(1, 50),
        ];
    }
}
