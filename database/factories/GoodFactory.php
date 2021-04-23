<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Good;
use Illuminate\Database\Eloquent\Factories\Factory;

class GoodFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Good::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->text(20),
            'user_id' =>1,
            'category_id' => $this->faker->randomElement(Category::where('group','goods')->where('level',3)->pluck('id')),
            'description' => $this->faker->text(200),
            'price' => $this->faker->numberBetween(0, 10000),
            'stock' => $this->faker->numberBetween(0, 999),
            'cover' => 'http://placeimg.com/640/480/any',
            'pics' =>[
                'http://placeimg.com/640/480/any',
                'http://placeimg.com/640/480/any',
                'http://placeimg.com/640/480/any',
            ],
            'is_on' => $this->faker->randomElement([0,1]),
            'is_recommend' => $this->faker->randomElement([0,1]),
            'details' => $this->faker->text(200),
        ];
    }
}
