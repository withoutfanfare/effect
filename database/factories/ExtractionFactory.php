<?php

namespace Database\Factories;

use App\Models\Extraction;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExtractionFactory extends Factory
{
    protected $model = Extraction::class;

    public function definition(): array
    {
        return [
            'job_id' => $this->faker->uuid,
            'text' => $this->faker->text,
            'status' => $this->faker->randomElement(['pending']),
        ];
    }
}
