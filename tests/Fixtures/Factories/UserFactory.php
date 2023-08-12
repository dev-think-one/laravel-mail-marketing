<?php

namespace MailMarketing\Tests\Fixtures\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use MailMarketing\Tests\Fixtures\Models\User;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'email'      => fake()->unique()->safeEmail(),
            'first_name' => fake()->firstName(),
            'last_name'  => fake()->lastName(),
            'phone'      => fake()->phoneNumber(),
        ];
    }
}
