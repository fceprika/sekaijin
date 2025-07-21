<?php

namespace Tests\Traits;

use App\Models\User;

trait AuthenticatesUsers
{
    /**
     * Create and authenticate a user.
     */
    protected function signIn(array $attributes = []): User
    {
        $user = User::factory()->create(array_merge([
            'email_verified_at' => now(),
        ], $attributes));
        $this->actingAs($user);

        return $user;
    }

    /**
     * Create and authenticate an admin user.
     */
    protected function signInAdmin(array $attributes = []): User
    {
        $user = User::factory()->admin()->create(array_merge([
            'email_verified_at' => now(),
        ], $attributes));
        $this->actingAs($user);

        return $user;
    }

    /**
     * Create and authenticate a premium user.
     */
    protected function signInPremium(array $attributes = []): User
    {
        $user = User::factory()->premium()->create(array_merge([
            'email_verified_at' => now(),
        ], $attributes));
        $this->actingAs($user);

        return $user;
    }

    /**
     * Create and authenticate an ambassador user.
     */
    protected function signInAmbassador(array $attributes = []): User
    {
        $user = User::factory()->ambassador()->create(array_merge([
            'email_verified_at' => now(),
        ], $attributes));
        $this->actingAs($user);

        return $user;
    }

    /**
     * Create and authenticate a free user.
     */
    protected function signInFree(array $attributes = []): User
    {
        $user = User::factory()->create(array_merge([
            'role' => 'free',
            'email_verified_at' => now(),
        ], $attributes));
        $this->actingAs($user);

        return $user;
    }
}
