<?php

namespace Database\Factories;

use App\Models\Channel;
use App\Models\ChannelSubscription;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;
    protected static ?string $type;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'username' => fake()->userName(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->unique()->phoneNumber(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'type' => static::$type ?? $this->faker->randomElement(['normal', 'coach']),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
    public function configure()
    {
        return $this->afterCreating(function (User $user) {
            if ($user->type == 'coach') {
                $user->status = 'approved';
                $user->update();
                $channel = Channel::factory()->create(['name' => 'Coach.' . $user->id, 'type' => 'private']);
                // Create a subscription for the user and channel
                ChannelSubscription::factory()->create([
                    'user_id' => $user->id,
                    'channel_id' => $channel->id,
                ]);
            }
        });
    }
}
