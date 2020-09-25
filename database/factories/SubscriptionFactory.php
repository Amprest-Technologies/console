<?php

namespace Database\Factories;

use App\Models\Subscription;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubscriptionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Subscription::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'usage_limit' => $this->faker->numberBetween(1000, 10000),
            'amount' => $this->faker->randomFloat(2, 500, 20000),
            'expires_at' => $this->faker->unique()
                ->dateTimeThisYear('now', 'Africa/Nairobi')
        ];
    }
}
