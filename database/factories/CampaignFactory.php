<?php

namespace Database\Factories;

use App\Models\Campaign;
use Illuminate\Database\Eloquent\Factories\Factory;

class CampaignFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Campaign::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $from = $this->faker->dateTimeBetween('+1 days', '+30 days');
        $clone = clone $from;
        $interval = $this->faker->numberBetween(0, 30);
        $to = $clone->modify("+ $interval days");
        return [
            'name' => $this->faker->name(),
            'daily_budget' => $this->faker->randomNumber(3),
            'from' => $from->format('Y-m-d'),
            'to' => $to->format('Y-m-d'),
        ];
    }
}
