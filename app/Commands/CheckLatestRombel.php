<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\RombelModel;

class CheckLatestRombel extends BaseCommand
{
    protected $group       = 'Custom';
    protected $name        = 'check:rombel';
    protected $description = 'Check the latest rombel entry';

    public function run(array $params)
    {
        $model = new RombelModel();
        $rombel = $model->orderBy('id', 'DESC')->first();
        
        if ($rombel) {
            CLI::write('Latest Rombel:', 'green');
            foreach ($rombel as $key => $value) {
                CLI::write("$key: $value");
            }
        } else {
            CLI::write('No rombel found.', 'red');
        }
    }
}
