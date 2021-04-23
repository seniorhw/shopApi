<?php

namespace Database\Factories;

use App\Models\Slide;
use Illuminate\Database\Eloquent\Factories\Factory;

class SlideFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Slide::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->text(20),
            'url' => '',
            'img' => 'http://placeimg.com/1960/400/any',
            'status' => $this->faker->randomElement([0,1]),
            'seq' => $this->faker->numberBetween(0, 999),
        ];
    }
}
