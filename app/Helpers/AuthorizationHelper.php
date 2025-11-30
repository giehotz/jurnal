<?php

namespace App\Helpers;

class AuthorizationHelper
{
    public static function isGuruMengajarKelas($userId, $rombelId)
    {
        // Mengizinkan semua guru mengakses semua kelas
        // Validasi bisa diperketat jika diperlukan di masa depan
        return true;
    }
}
