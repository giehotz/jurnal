<?php
if (!function_exists('format_tanggal_indonesia')) {
    /**
     * Format tanggal dalam Bahasa Indonesia
     * Contoh: 01 Januari 2025 14:30:25
     * 
     * @param mixed $date Tanggal dalam format yang diterima DateTime
     * @return string Tanggal dalam format Bahasa Indonesia
     */
    function format_tanggal_indonesia($date) {
        // Buat objek DateTime dari parameter
        if (!($date instanceof DateTime)) {
            $date = new DateTime($date);
        }
        
        // Array nama bulan dalam Bahasa Indonesia
        $namaBulan = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];
        
        // Dapatkan komponen tanggal
        $tanggal = (int)$date->format('j'); // 1 sampai 31
        $bulan = (int)$date->format('n'); // 1 sampai 12
        $tahun = $date->format('Y'); // Tahun 4 digit
        $waktu = $date->format('H:i:s'); // Waktu
        
        // Format tanggal dalam bahasa Indonesia
        $hasil = str_pad($tanggal, 2, '0', STR_PAD_LEFT) . ' ' . $namaBulan[$bulan] . ' ' . $tahun . ' ' . $waktu;
        
        return $hasil;
    }
}

if (!function_exists('format_tanggal_lengkap')) {
    /**
     * Format tanggal lengkap dalam Bahasa Indonesia
     * Contoh: Senin, 01 Januari 2025
     * 
     * @param mixed $date Tanggal dalam format yang diterima DateTime
     * @return string Tanggal dalam format lengkap Bahasa Indonesia
     */
    function format_tanggal_lengkap($date) {
        // Buat objek DateTime dari parameter
        if (!($date instanceof DateTime)) {
            $date = new DateTime($date);
        }
        
        // Array nama hari dan bulan dalam Bahasa Indonesia
        $namaHari = [
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
            7 => 'Minggu'
        ];
        
        $namaBulan = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];
        
        // Dapatkan komponen tanggal
        $hariDalamMinggu = (int)$date->format('N'); // 1 (Senin) sampai 7 (Minggu)
        $tanggal = (int)$date->format('j'); // 1 sampai 31
        $bulan = (int)$date->format('n'); // 1 sampai 12
        $tahun = $date->format('Y'); // Tahun 4 digit
        
        // Format tanggal dalam bahasa Indonesia
        $hasil = $namaHari[$hariDalamMinggu] . ', ' . str_pad($tanggal, 2, '0', STR_PAD_LEFT) . ' ' . $namaBulan[$bulan] . ' ' . $tahun;
        
        return $hasil;
    }
}

if (!function_exists('get_dates_by_week')) {
    /**
     * Mendapatkan tanggal-tanggal dalam bulan tertentu, dikelompokkan per minggu
     * 
     * @param int $month Bulan (1-12)
     * @param int $year Tahun
     * @return array Daftar minggu dengan tanggal-tanggal di dalamnya
     */
    function get_dates_by_week($month, $year) {
        $weeks = [];
        $firstDay = new DateTime("$year-$month-01");
        $lastDay = clone $firstDay;
        $lastDay->modify('last day of this month');
        
        // Menyesuaikan tanggal awal ke awal minggu (Senin)
        $firstDayOfWeek = clone $firstDay;
        $firstDayOfWeek->modify('monday this week');
        
        // Menyesuaikan tanggal akhir ke akhir minggu (Minggu)
        $lastDayOfWeek = clone $lastDay;
        $lastDayOfWeek->modify('sunday this week');
        
        $currentDate = clone $firstDayOfWeek;
        $weekNumber = 1;
        
        while ($currentDate <= $lastDayOfWeek) {
            $weeks[$weekNumber][] = [
                'date_sql' => $currentDate->format('Y-m-d'),
                'day_name' => format_tanggal_lengkap($currentDate),
                'day_num' => $currentDate->format('j'),
                'month' => $currentDate->format('n'),
                'year' => $currentDate->format('Y')
            ];
            
            $currentDate->modify('+1 day');
            
            // Jika hari Minggu (akhir minggu), pindah ke minggu berikutnya
            if ($currentDate->format('N') == 1) { // 1 = Senin
                $weekNumber++;
            }
        }
        
        return $weeks;
    }
}