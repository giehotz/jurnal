<?php
namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\RombelModel;

class CheckRombel extends Controller
{
    public function index()
    {
        $model = new RombelModel();
        $rombel = $model->orderBy('id', 'DESC')->first();
        
        echo "Latest Rombel:\n";
        print_r($rombel);
    }
}
