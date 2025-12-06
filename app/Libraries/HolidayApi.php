<?php

namespace App\Libraries;

class HolidayApi
{
    protected $baseUrl = 'https://dayoffapi.vercel.app/api';
    // Alternative: https://api-harilibur.vercel.app/api
    // Using dayoffapi.vercel.app as it is often reliable for Indonesia.
    // Or simpler: https://raw.githubusercontent.com/guangrei/Json-Indonesia-holidays/master/calendar.json (Static but reliable)

    // Let's use a reliable source. `https://api-harilibur.vercel.app/api` returns JSON.

    public function getHolidays($year = null, $month = null)
    {
        $year = $year ?? date('Y');
        $cacheKey = "holidays_{$year}";
        $cache = \Config\Services::cache();

        // Try to get from cache
        $cachedData = $cache->get($cacheKey);

        if ($cachedData) {
            return $this->filterHolidays($cachedData, $month);
        }

        // If not in cache, fetch from API
        $url = "https://dayoffapi.vercel.app/api?year={$year}";

        try {
            $client = \Config\Services::curlrequest();
            $response = $client->request('GET', $url, [
                'timeout' => 5,
                'verify' => false
            ]);

            if ($response->getStatusCode() == 200) {
                $data = json_decode($response->getBody(), true);
                $holidays = [];

                if (is_array($data)) {
                    foreach ($data as $holiday) {
                        $holidays[] = [
                            'tanggal' => $holiday['tanggal'],
                            'keterangan' => $holiday['keterangan'],
                            'is_negeri' => $holiday['is_cuti'] ?? false
                        ];
                    }
                }

                // Save to cache for 30 days
                $cache->save($cacheKey, $holidays, 30 * 24 * 3600);

                return $this->filterHolidays($holidays, $month);
            }
        } catch (\Exception $e) {
            log_message('error', 'Failed to fetch holidays: ' . $e->getMessage());
            // Optionally: could try to load a backup JSON file here if critical
        }

        return [];
    }

    private function filterHolidays(array $holidays, $month)
    {
        if (!$month) {
            return $holidays;
        }

        return array_filter($holidays, function ($h) use ($month) {
            return date('m', strtotime($h['tanggal'])) == $month;
        });
    }
}
