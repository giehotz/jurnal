<?php

namespace App\Validation;

class AbsensiValidation
{
    public function getRules()
    {
        return [
            'tanggal' => 'required|valid_date',
            'rombel_id' => 'required|is_not_unique[rombel.id]',
            'mapel_id' => 'required|is_not_unique[mata_pelajaran.id]',
        ];
    }
}
