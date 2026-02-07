<?php

namespace Database\Factories;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class AdminFactory extends Factory
{
    protected $model = Admin::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'username' => $this->faker->unique()->userName(),
            'password' => $this->faker->unique()->password(8, 20)
        ];
    }

    public function withPassword(string $password): static
    {
        return $this->state(fn() => [
            'password' => Hash::make($password),
        ]);
    }
}
