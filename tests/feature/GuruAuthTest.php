<?php

namespace Tests\Feature;

use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\CIUnitTestCase;
use Config\Services;

/**
 * @internal
 */
final class GuruAuthTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    public function testUnauthorizedUserCannotAccessGuruPages()
    {
        // Try to access guru dashboard without login
        $result = $this->get('guru/dashboard');
        
        // Should redirect to login page
        $result->assertRedirect();
    }

    public function testGuruCanAccessDashboard()
    {
        // Simulate a logged-in guru user
        $sessionData = [
            'isLoggedIn' => true,
            'user_id' => 1,
            'role' => 'guru',
            'nama' => 'Test Guru'
        ];

        // Try to access guru dashboard as guru
        $result = $this->withSession($sessionData)
                       ->get('guru/dashboard');

        // Should be successful
        $result->assertOK();
    }
}