<?php

namespace Database\Factories;

use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Generator as Faker;

class SupplierFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Supplier::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $faker = \Faker\Factory::create('id_ID');

        return [
            'name' => $faker->company,
            'address' => $faker->address,
            'phone' => $faker->phoneNumber,
            'npwp' => $faker->numerify('##.###.###.#-###.###'),
            'contact_name' => $faker->name,
            'contact_phone' => $faker->phoneNumber,
        ];
    }
}
