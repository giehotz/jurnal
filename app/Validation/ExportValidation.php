<?php

namespace App\Validation;

class ExportValidation
{
    public function getRules()
    {
        return [
            'start_month' => 'required',
            'end_month' => 'required',
            'export_type' => 'required|in_list[pdf,excel]'
        ];
    }
}
