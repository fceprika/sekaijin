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
        $user = User::factory()->create($attributes);
        $this->actingAs($user);

        return $user;
    }

    /**
     * Create and authenticate an admin user.
     */
    protected function signInAdmin(array $attributes = []): User
    {
        $user = User::factory()->admin()->create($attributes);
        $this->actingAs($user);

        return $user;
    }

    /**
     * Create and authenticate a premium user.
     */
    protected function signInPremium(array $attributes = []): User
    {
        $user = User::factory()->premium()->create($attributes);
        $this->actingAs($user);

        return $user;
    }

    /**
     * Create and authenticate an ambassador user.
     */
    protected function signInAmbassador(array $attributes = []): User
    {
        $user = User::factory()->ambassador()->create($attributes);
        $this->actingAs($user);

        return $user;
    }

    /**
     * Create and authenticate a free user.
     */
    protected function signInFree(array $attributes = []): User
    {
        $user = User::factory()->create(array_merge(['role' => 'free'], $attributes));
        $this->actingAs($user);

        return $user;
    }
}
