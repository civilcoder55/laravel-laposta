<?php

namespace Database\Factories;

use App\Models\Session;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SessionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Session::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id' => $this->faker->md5,
            'user_id' => User::factory()->create()->id,
            'ip_address' => '127.0.0.1',
            'user_agent' => $this->faker->userAgent,
            'payload'=>$this->faker->text,
            'last_activity' => now()->timestamp,
        ];
    }
}
