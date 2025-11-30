<?php

namespace App\Services;

class AbsensiCalculationService
{
    protected $hariLiburService;

    public function __construct()
    {
        $this->hariLiburService = new HariLiburService();
    }

    public function hitungHariEfektif($startDate, $endDate)
    {
        $effectiveDays = 0;
        $currentDate = $startDate;
        
        // Extract tahun dan bulan dari periode
        $startYear = date('Y', strtotime($startDate));
        $endYear = date('Y', strtotime($endDate));
        
        // Get hari libur untuk semua tahun dalam range
        $allHariLibur = [];
        for ($year = $startYear; $year <= $endYear; $year++) {
            $liburTahun = $this->hariLiburService->getHariLibur($year);
            $allHariLibur = array_merge($allHariLibur, $liburTahun);
        }
        
        while ($currentDate <= $endDate) {
            $dayOfWeek = date('N', strtotime($currentDate));
            $isHoliday = in_array($currentDate, $allHariLibur);
            
            // Exclude Minggu (7) dan hari libur nasional
            if ($dayOfWeek != 7 && !$isHoliday) {
                $effectiveDays++;
            }
            
            $currentDate = date('Y-m-d', strtotime($currentDate . ' +1 day'));
        }
        
        return $effectiveDays;
    }
}
