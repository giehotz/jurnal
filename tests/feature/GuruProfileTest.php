<?php

namespace Tests\Feature;

use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\CIUnitTestCase;
use Config\Services;

/**
 * @internal
 */
final class GuruProfileTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    public function testGuruCanAccessProfilePage()
    {
        // Simulate a logged-in guru user
        $sessionData = [
            'isLoggedIn' => true,
            'user_id' => 1,
            'role' => 'guru',
            'nama' => 'Test Guru',
            'email' => 'guru@test.com'
        ];

        // Make a request to the guru profile page with the session data
        $result = $this->withSession($sessionData)
                       ->get('guru/profile');

        // Assert that the request was successful
        $result->assertOK();
    }

    public function testGuruCanAccessEditProfilePage()
    {
        // Simulate a logged-in guru user
        $sessionData = [
            'isLoggedIn' => true,
            'user_id' => 1,
            'role' => 'guru',
            'nama' => 'Test Guru',
            'email' => 'guru@test.com'
        ];

        // Make a request to the edit profile page with the session data
        $result = $this->withSession($sessionData)
                       ->get('guru/profile/edit');

        // Assert that the request was successful
        $result->assertOK();
    }
}