<?php

namespace App\Services;

class HariLiburService
{
    public function getHariLibur($tahun, $bulan = null)
    {
        $cacheKey = "hari_libur_{$tahun}" . ($bulan ? "_{$bulan}" : "");
        
        // Cek cache dulu (1 hari cache)
        if ($cached = cache($cacheKey)) {
            return $cached;
        }
        
        // Build API URL
        $apiUrl = "https://libur.deno.dev/api/{$tahun}";
        if ($bulan) {
            $apiUrl .= "/{$bulan}";
        }
        
        try {
            $client = \Config\Services::curlrequest();
            $response = $client->get($apiUrl, [
                'timeout' => 10,
                'headers' => [
                    'User-Agent' => 'JurnalGuruApp/1.0'
                ]
            ]);
            
            if ($response->getStatusCode() === 200) {
                $liburData = json_decode($response->getBody(), true);
                
                // Extract hanya tanggalnya
                $tanggalLibur = array_column($liburData, 'date');
                
                // Cache untuk 1 hari
                cache()->save($cacheKey, $tanggalLibur, 86400);
                
                return $tanggalLibur;
            }
        } catch (\Exception $e) {
            // Fallback: return empty array jika API error or use hardcoded fallback
            log_message('error', 'Error fetch hari libur: ' . $e->getMessage());
            return array_column($this->getHariLiburFallback($tahun), 'date');
        }
        
        return array_column($this->getHariLiburFallback($tahun), 'date');
    }

    public function getDetailHariLibur($startDate, $endDate)
    {
        $startYear = date('Y', strtotime($startDate));
        $endYear = date('Y', strtotime($endDate));
        $allHariLibur = [];
        $apiSuccess = false;
        
        for ($year = $startYear; $year <= $endYear; $year++) {
            $apiUrl = "https://libur.deno.dev/api/{$year}";
            
            try {
                $client = \Config\Services::curlrequest();
                $response = $client->get($apiUrl, ['timeout' => 5]); // Reduced timeout
                
                if ($response->getStatusCode() === 200) {
                    $liburData = json_decode($response->getBody(), true);
                    
                    if (!empty($liburData)) {
                        $apiSuccess = true;
                        // Filter hanya yang dalam range periode
                        foreach ($liburData as $libur) {
                            if ($libur['date'] >= $startDate && $libur['date'] <= $endDate) {
                                $allHariLibur[] = $libur;
                            }
                        }
                    }
                }
            } catch (\Exception $e) {
                log_message('error', 'Error fetch detail libur: ' . $e->getMessage());
            }
        }

        // Use fallback if API failed for all years or returned no data
        if (empty($allHariLibur) && !$apiSuccess) {
            $fallbackData = [];
            for ($year = $startYear; $year <= $endYear; $year++) {
                $fallbackData = array_merge($fallbackData, $this->getHariLiburFallback($year));
            }
            
            foreach ($fallbackData as $libur) {
                if ($libur['date'] >= $startDate && $libur['date'] <= $endDate) {
                    $allHariLibur[] = $libur;
                }
            }
        }
        
        return $allHariLibur;
    }

    private function getHariLiburFallback($tahun)
    {
        return [
            ['date' => $tahun . '-01-01', 'holiday_name' => 'Tahun Baru Masehi'],
            ['date' => $tahun . '-02-08', 'holiday_name' => 'Isra Mi\'raj Nabi Muhammad SAW'], // Tanggal bisa berubah
            ['date' => $tahun . '-02-10', 'holiday_name' => 'Tahun Baru Imlek'], // Tanggal bisa berubah
            ['date' => $tahun . '-03-11', 'holiday_name' => 'Hari Suci Nyepi'], // Tanggal bisa berubah
            ['date' => $tahun . '-03-29', 'holiday_name' => 'Wafat Isa Al Masih'], // Tanggal bisa berubah
            ['date' => $tahun . '-03-31', 'holiday_name' => 'Hari Paskah'], // Tanggal bisa berubah
            ['date' => $tahun . '-04-10', 'holiday_name' => 'Hari Raya Idul Fitri'], // Tanggal bisa berubah
            ['date' => $tahun . '-04-11', 'holiday_name' => 'Cuti Bersama Idul Fitri'], // Tanggal bisa berubah
            ['date' => $tahun . '-05-01', 'holiday_name' => 'Hari Buruh Internasional'],
            ['date' => $tahun . '-05-09', 'holiday_name' => 'Kenaikan Isa Al Masih'], // Tanggal bisa berubah
            ['date' => $tahun . '-05-23', 'holiday_name' => 'Hari Raya Waisak'], // Tanggal bisa berubah
            ['date' => $tahun . '-06-01', 'holiday_name' => 'Hari Lahir Pancasila'],
            ['date' => $tahun . '-06-17', 'holiday_name' => 'Hari Raya Idul Adha'], // Tanggal bisa berubah
            ['date' => $tahun . '-07-07', 'holiday_name' => 'Tahun Baru Islam'], // Tanggal bisa berubah
            ['date' => $tahun . '-08-17', 'holiday_name' => 'Hari Kemerdekaan RI'],
            ['date' => $tahun . '-09-16', 'holiday_name' => 'Maulid Nabi Muhammad SAW'], // Tanggal bisa berubah
            ['date' => $tahun . '-12-25', 'holiday_name' => 'Hari Raya Natal'],
        ];
    }
}
