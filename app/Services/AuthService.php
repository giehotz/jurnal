<?php

namespace App\Services;

class AuthService
{
    public function checkGuruAccess()
    {
        $role = session()->get('role');
        if (!session()->get('logged_in') || $role !== 'guru') {
            return false;
        }
        return true;
    }

    public function getUserId()
    {
        return session()->get('user_id');
    }
}
