<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Project::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'uuid' => $this->faker->unique()->ean8,
            'api_key' => $this->faker->unique()->sha1,
            'name' => $this->faker->unique()->company,
            'description' => $this->faker->unique()->realText(200, 2),
            'status' => $this->faker->unique()->randomElement([
                'inactive', 'suspended', 'active'
            ]),
        ];
    }
}
