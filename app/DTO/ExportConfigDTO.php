<?php

namespace App\DTO;

class ExportConfigDTO
{
    public $startMonth;
    public $endMonth;
    public $exportType;
    public $rombelId;

    public function __construct($data)
    {
        $this->startMonth = $data['start_month'] ?? null;
        $this->endMonth = $data['end_month'] ?? null;
        $this->exportType = $data['export_type'] ?? null;
        $this->rombelId = $data['rombel_id'] ?? null;
    }
}
