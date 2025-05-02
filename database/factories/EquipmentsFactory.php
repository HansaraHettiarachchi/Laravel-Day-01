<?php

namespace Database\Factories;

use App\Models\Equipments;
use App\Models\NewUser;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Equipments>
 */
class EquipmentsFactory extends Factory
{
    protected $model = Equipments::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'code' => $this->faker->unique()->numerify('EQP###'),
            'price' => $this->faker->randomFloat(2, 100, 5000),
            'tQty' => $this->faker->randomNumber(2),
            'sub_tot' => $this->faker->randomFloat(2, 100, 5000),
            'created_user' => NewUser::inRandomOrder()->first()->id ?? NewUser::factory(),
            'cat_id' => $this->faker->numberBetween(1, 2),
            'status' => 'Active'
        ];
    }
}
