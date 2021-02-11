<?php

namespace Database\Factories;

use App\Models\House;
use Illuminate\Database\Eloquent\Factories\Factory;

class HouseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = House::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => 'The ' . ucwords($this->faker->words(rand(2, 3), true)),
            'price' => rand(250000, 700000),
            'bedrooms' => rand(2, 5),
            'bathrooms' => rand(2, 4),
            'storeys' => rand(1, 3),
            'garages' => rand(1, 2),
        ];
    }
}
