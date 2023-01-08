<?php

namespace Database\Factories;

use App\Models\Cv;
use App\Models\Jobadvertisement;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Jobapplication>
 */
class JobapplicationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            // 'user_id' => User::factory()->create()->id,
            'jobadvertisement_id' => Jobadvertisement::factory()->create()->id,
            'cv_id' => Cv::factory()->create()->id,
            'status' => 'Nincs megnézve.',
        ];
    }
}
