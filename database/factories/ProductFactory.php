<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_name' => $this->faker->randomElement(['Asus','Acer','MacBook','Lenovo']),
            'product_code' => $this->faker->randomNumber(5),
            'product_price' => $this->faker->randomNumber(5)
        ];
    }
}
