<?php

namespace Tests\Traits;

use Illuminate\Testing\TestResponse;

trait AssertionsHelper
{
    /**
     * Assert that the response contains the given text.
     */
    protected function assertResponseContains(TestResponse $response, string $text): void
    {
        $response->assertSee($text, false);
    }

    /**
     * Assert that the response does not contain the given text.
     */
    protected function assertResponseDoesNotContain(TestResponse $response, string $text): void
    {
        $response->assertDontSee($text, false);
    }

    /**
     * Assert that the response is a successful JSON response.
     */
    protected function assertJsonSuccess(TestResponse $response): void
    {
        $response->assertSuccessful();
        $response->assertJson(['success' => true]);
    }

    /**
     * Assert that the response is a JSON error response.
     */
    protected function assertJsonError(TestResponse $response, ?string $message = null): void
    {
        $response->assertJson(['success' => false]);

        if ($message !== null) {
            $response->assertJson(['message' => $message]);
        }
    }

    /**
     * Assert that the response has validation errors.
     */
    protected function assertValidationErrors(TestResponse $response, array $fields): void
    {
        $response->assertSessionHasErrors($fields);
    }

    /**
     * Assert that the user is redirected to login.
     */
    protected function assertRedirectToLogin(TestResponse $response): void
    {
        // Check for both possible login redirect routes
        $location = $response->headers->get('Location');
        $this->assertTrue(
            str_contains($location, '/connexion') || str_contains($location, '/invitation-membre'),
            "Expected redirect to login page, but got: {$location}"
        );
    }

    /**
     * Assert that the user is redirected to home.
     */
    protected function assertRedirectToHome(TestResponse $response): void
    {
        $response->assertRedirect('/');
    }

    /**
     * Assert that the response is a 403 Forbidden.
     */
    protected function assertForbidden(TestResponse $response): void
    {
        $response->assertStatus(403);
    }

    /**
     * Assert that the response is a 404 Not Found.
     */
    protected function assertNotFound(TestResponse $response): void
    {
        $response->assertStatus(404);
    }

    /**
     * Assert that the response is a 422 Unprocessable Entity.
     */
    protected function assertUnprocessable(TestResponse $response): void
    {
        $response->assertStatus(422);
    }

    /**
     * Assert that the current user has a specific role.
     */
    protected function assertUserHasRole(string $role): void
    {
        $this->assertTrue(auth()->user()->isRole($role));
    }

    /**
     * Assert that the current user is an admin.
     */
    protected function assertUserIsAdmin(): void
    {
        $this->assertTrue(auth()->user()->isAdmin());
    }

    /**
     * Assert that the current user is an ambassador.
     */
    protected function assertUserIsAmbassador(): void
    {
        $this->assertTrue(auth()->user()->isAmbassador());
    }

    /**
     * Assert that the current user is premium.
     */
    protected function assertUserIsPremium(): void
    {
        $this->assertTrue(auth()->user()->isPremium());
    }
}
