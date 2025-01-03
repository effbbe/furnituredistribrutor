<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Generator as Faker;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Category::class;

    public function definition(): array
    {
        return [
            'category' => $this->faker->randomElement([
                'Sofa', 'Meja', 'Kursi', 'Lemari', 'Rak Buku', 'Tempat Tidur',
                'Meja Makan', 'Kursi Kantor', 'Meja Kantor', 'Buffet'
            ])            
        ];
    }
}
