<?php

namespace Tests\Feature;

use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\CIUnitTestCase;
use Config\Services;

/**
 * @internal
 */
final class GuruJurnalTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    public function testGuruCanAccessJurnalIndex()
    {
        // Simulate a logged-in guru user
        $sessionData = [
            'isLoggedIn' => true,
            'user_id' => 1,
            'role' => 'guru',
            'nama' => 'Test Guru'
        ];

        // Make a request to the guru jurnal index with the session data
        $result = $this->withSession($sessionData)
                       ->get('guru/jurnal');

        // Assert that the request was successful
        $result->assertOK();
    }

    public function testGuruCanAccessCreateJurnalPage()
    {
        // Simulate a logged-in guru user
        $sessionData = [
            'isLoggedIn' => true,
            'user_id' => 1,
            'role' => 'guru',
            'nama' => 'Test Guru'
        ];

        // Make a request to the create jurnal page with the session data
        $result = $this->withSession($sessionData)
                       ->get('guru/jurnal/create');

        // Assert that the request was successful
        $result->assertOK();
    }
}