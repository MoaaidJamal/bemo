<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Column;
use App\Models\Card;

class CardFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Card::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'column_id' => Column::query()->inRandomOrder()->first()?->getKey(),
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->text,
            'order' => $this->faker->numberBetween(1, 15),
        ];
    }
}
