<?php

namespace Tests\Feature;

use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\CIUnitTestCase;
use Config\Services;

/**
 * @internal
 */
final class GuruDashboardTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    public function testGuruCanAccessDashboard()
    {
        // Simulate a logged-in guru user
        $sessionData = [
            'isLoggedIn' => true,
            'user_id' => 1,
            'role' => 'guru',
            'nama' => 'Test Guru'
        ];

        // Make a request to the guru dashboard with the session data
        $result = $this->withSession($sessionData)
                       ->get('guru/dashboard');

        // Assert that the request was successful
        $result->assertOK();
    }
}