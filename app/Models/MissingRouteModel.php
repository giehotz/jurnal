<?php

namespace App\Models;

use CodeIgniter\Model;

class MissingRouteModel extends Model
{
    protected $table = 'missing_routes';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'uri',
        'guessed_controller',
        'guessed_method',
        'status',
        'created_at'
    ];
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
}