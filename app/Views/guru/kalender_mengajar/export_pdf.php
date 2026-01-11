<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Kalender Pendidikan Mengajar <?= $tahun_ajaran ?></title>
    <style>
        @page {
            margin: 10mm 10mm 10mm 10mm;
            /* Tight margins */
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 8px;
            /* Slightly smaller base font */
            color: #333;
            margin: 0;
            padding: 0;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
            border-bottom: 2px solid #444;
            padding-bottom: 5px;
        }

        .header h1 {
            margin: 0;
            font-size: 14px;
            text-transform: uppercase;
            font-weight: 800;
        }

        .header h2 {
            margin: 3px 0 0;
            font-size: 10px;
            font-weight: normal;
        }

        /* 
           Layout Strategy for 6 months on Landscape A4:
           - Grid: 2 Rows x 3 Columns (Wait, user image showed 4x3? No, user image showed 4 cols.
             But 6 months doesn't fit nicely in 4 cols (4 then 2).
             3 cols x 2 rows = 6 months perfectly. This is much better for space.
        */
        .grid-container {
            width: 100%;
            display: table;
            border-spacing: 5px;
        }

        .grid-row {
            display: table-row;
        }

        .grid-col {
            display: table-cell;
            width: 33.33%;
            /* 3 columns */
            vertical-align: top;
        }

        .month-box {
            border: 1px solid #aaa;
            padding: 0;
            background: #fff;
            border-radius: 3px;
            overflow: hidden;
            margin-bottom: 5px;
        }

        .month-title {
            background-color: #2c3e50;
            color: white;
            text-align: center;
            padding: 4px 0;
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase;
            border-bottom: 1px solid #000;
        }

        .calendar-table {
            width: 100%;
            border-collapse: collapse;
        }

        .calendar-table th {
            text-align: center;
            font-size: 7px;
            color: #555;
            padding: 2px 0;
            background-color: #f4f4f4;
            border-bottom: 1px solid #ddd;
        }

        .calendar-table td {
            text-align: center;
            padding: 1px;
            vertical-align: middle;
            height: 16px;
            /* Reduced height */
            border-bottom: 1px solid #eee;
        }

        .day-cell {
            display: block;
            width: 14px;
            height: 14px;
            line-height: 14px;
            margin: 0 auto;
            border-radius: 50%;
            font-weight: bold;
            font-size: 8px;
        }

        .month-footer {
            padding: 4px;
            font-size: 7px;
            border-top: 1px solid #eee;
            min-height: 60px;
            /* Pre-allocate space for holidays */
            background-color: #fafafa;
        }

        .stat-row {
            margin-bottom: 1px;
        }

        .holiday-list {
            margin-top: 2px;
            padding-top: 2px;
            border-top: 1px dotted #ccc;
        }

        .holiday-item {
            color: #d9534f;
            margin-bottom: 1px;
            font-style: italic;
            display: block;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 100%;
        }

        .legend {
            margin-top: 5px;
            font-size: 8px;
            padding: 5px;
            border: 1px solid #eee;
            background: #f9f9f9;
        }

        .legend-item {
            display: inline-block;
            margin-right: 15px;
        }

        .color-box {
            display: inline-block;
            width: 8px;
            height: 8px;
            margin-right: 4px;
            vertical-align: middle;
            border: 1px solid #ccc;
        }

        .stats-summary {
            margin-top: 10px;
            font-size: 9px;
            width: 50%;
        }

        .stats-summary ul {
            margin: 2px 0;
            padding-left: 15px;
        }

        .footer-sign {
            float: right;
            width: 30%;
            text-align: center;
            margin-top: 10px;
        }

        /* Force page break */
        .page-break {
            page-break-after: always;
        }

        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
    </style>
</head>

<body>

    <?php
    function renderMonthsGrid($monthsChunk)
    {
        // Switch to 3 columns per row for better fit (6 months total => 2 rows)
        $output = '<div class="grid-container">';
        $colCount = 0;

        foreach ($monthsChunk as $md) {
            if ($colCount % 3 == 0) $output .= '<div class="grid-row">';

            $output .= '<div class="grid-col"><div class="month-box">';

            // Title
            $enToId = [
                'January' => 'Januari',
                'February' => 'Februari',
                'March' => 'Maret',
                'April' => 'April',
                'May' => 'Mei',
                'June' => 'Juni',
                'July' => 'Juli',
                'August' => 'Agustus',
                'September' => 'September',
                'October' => 'Oktober',
                'November' => 'November',
                'December' => 'Desember'
            ];
            $monthName = $enToId[$md['month_name']] ?? $md['month_name'];
            $output .= '<div class="month-title">' . $monthName . ' ' . $md['tahun'] . '</div>';

            // Grid
            $output .= '<table class="calendar-table">
                        <thead>
                            <tr>
                                <th>Sen</th><th>Sel</th><th>Rab</th><th>Kam</th><th>Jum</th><th>Sab</th><th class="text-danger">Min</th>
                            </tr>
                        </thead>
                        <tbody>';

            foreach ($md['weeks'] as $weekNumber => $daysInWeek) {
                $output .= '<tr>';
                if ($weekNumber == 1) {
                    $firstDay = reset($daysInWeek);
                    $dayIndex = date('N', strtotime($firstDay['date_sql']));
                    for ($i = 1; $i < $dayIndex; $i++) $output .= '<td></td>';
                }
                foreach ($daysInWeek as $day) {
                    $dateSql = $day['date_sql'];
                    $events = $md['events_by_date'][$dateSql] ?? [];
                    $bgColor = 'transparent';
                    $textColor = '#333';
                    $isSunday = (date('N', strtotime($dateSql)) == 7);

                    if ($isSunday) $textColor = '#d9534f';

                    foreach ($events as $evt) {
                        if (!empty($evt['warna_kode'])) {
                            $bgColor = $evt['warna_kode'];
                            $textColor = '#000';
                            break;
                        }
                    }

                    $output .= '<td><span class="day-cell" style="background-color: ' . $bgColor . '; color: ' . $textColor . ';">' . $day['day_num'] . '</span></td>';
                }
                $lastDay = end($daysInWeek);
                $lastDayIndex = date('N', strtotime($lastDay['date_sql']));
                for ($i = $lastDayIndex; $i < 7; $i++) $output .= '<td></td>';
                $output .= '</tr>';
            }
            // Ensure fixed number of rows for vertical alignment? 6 weeks max usually.
            // If less than 6 weeks, fill? No, variable height is okay if handled. 
            $output .= '</tbody></table>';

            // Stats & Holidays
            $totalHari = cal_days_in_month(CAL_GREGORIAN, $md['bulan'], $md['tahun']);
            $hariEfektif = $md['stats']['hari_efektif'];

            $holidaysList = [];
            foreach ($md['events_by_date'] as $date => $events) {
                foreach ($events as $evt) {
                    // Include holidays OR "libur" keyword events
                    if (in_array($evt['jenis_hari'], ['libur_nasional', 'libur_sekolah']) || stripos($evt['keterangan'], 'libur') !== false) {
                        $d = date('j', strtotime($date));
                        // Shorten string
                        $ket = esc(ucwords(strtolower($evt['keterangan'])));
                        if (strlen($ket) > 25) $ket = substr($ket, 0, 23) . '..';
                        $holidaysList[] = "<b>{$d}</b>: {$ket}";
                    }
                }
            }
            $holidaysList = array_unique($holidaysList);

            $output .= '<div class="month-footer">';
            $output .= '<div class="stat-row"><b>Total hari</b>: ' . $totalHari . '</div>';
            $output .= '<div class="stat-row"><b>Hari efektif</b>: ' . $hariEfektif . '</div>';

            if (!empty($holidaysList)) {
                $output .= '<div class="holiday-list">';
                foreach ($holidaysList as $h) {
                    $output .= '<div class="holiday-item">' . $h . '</div>';
                }
                $output .= '</div>';
            }
            $output .= '</div>'; // End Footer

            $output .= '</div></div>'; // Close Box & Col

            $colCount++;
            if ($colCount % 3 == 0) $output .= '</div>';
        }

        if ($colCount % 3 != 0) {
            while ($colCount % 3 != 0) {
                $output .= '<div class="grid-col"></div>';
                $colCount++;
                if ($colCount % 3 == 0) $output .= '</div>';
            }
        }

        $output .= '</div>';
        return $output;
    }

    $semester1 = array_slice($monthsData, 0, 6);
    $semester2 = array_slice($monthsData, 6, 6);
    ?>

    <!-- PAGE 1 -->
    <div class="header">
        <h1>Pedoman Kalender Pendidikan Madrasah Tahun Ajaran <?= $tahun_ajaran ?></h1>
        <h2><?= esc($school_name) ?> - Semester Ganjil</h2>
    </div>

    <?= renderMonthsGrid($semester1) ?>

    <!-- Compact Legend and Info -->
    <div class="clearfix" style="margin-top: 5px;">
        <div class="legend">
            <strong>Keterangan</strong>
            <span class="legend-item"><span class="color-box" style="background-color: #dc3545;"></span> Libur</span>
            <span class="legend-item"><span class="color-box" style="background-color: #28a745;"></span> Event</span>
            <span class="legend-item"><span class="color-box" style="background-color: #ffc107;"></span> Ujian</span>
        </div>
    </div>

    <div class="page-break"></div>

    <!-- PAGE 2 -->
    <div class="header">
        <h1>Pedoman Kalender Pendidikan Madrasah Tahun Ajaran <?= $tahun_ajaran ?></h1>
        <h2><?= esc($school_name) ?> - Semester Genap</h2>
    </div>

    <?= renderMonthsGrid($semester2) ?>

    <div class="stats-summary" style="margin-top: 10px; margin-bottom: 5px;">
        <strong>STATISTIK TAHUN AJARAN</strong>
        <ul style="list-style: none; padding: 0; margin: 2px 0;">
            <li>Total Hari Efektif: <?= $totalStats['hari_efektif'] ?></li>
            <li>Total Libur Nasional: <?= $totalStats['libur_nasional'] ?></li>
        </ul>
    </div>

    <!-- Signature Section -->
    <div style="margin-top: 20px;">
        <table style="width: 100%; border: none;">
            <!-- Row 1: Date (Right Only) -->
            <tr>
                <td style="width: 40%;"></td> <!-- Left Empty -->
                <td style="width: 20%;"></td> <!-- Spacer -->
                <td style="width: 40%; text-align: center;">
                    <p style="margin: 0; padding-bottom: 5px;">Gisting, <?= date('d F Y') ?></p>
                </td>
            </tr>
            <!-- Row 2: Titles -->
            <tr>
                <td style="width: 40%; text-align: center; vertical-align: top;">
                    <p style="margin: 0;">Guru Kelas/Mata Pelajaran,</p>
                </td>
                <td style="width: 20%;"></td>
                <td style="width: 40%; text-align: center; vertical-align: top;">
                    <p style="margin: 0;">Mengetahui,<br>Kepala Madrasah,</p>
                </td>
            </tr>
            <!-- Row 3: Space for Signatures -->
            <tr>
                <td style="height: 60px;"></td>
                <td></td>
                <td></td>
            </tr>
            <!-- Row 4: Names & NIP -->
            <tr>
                <td style="width: 40%; text-align: center; vertical-align: top;">
                    <p style="text-decoration: underline; font-weight: bold; margin: 0;"><?= esc($user['nama'] ?? 'Guru') ?></p>
                    <p style="margin: 2px 0 0 0;">NIP. <?= esc($user['nip'] ?? '-') ?></p>
                </td>
                <td style="width: 20%;"></td>
                <td style="width: 40%; text-align: center; vertical-align: top;">
                    <p style="text-decoration: underline; font-weight: bold; margin: 0;"><?= esc($headmaster_name) ?></p>
                    <p style="margin: 2px 0 0 0;">NIP. <?= esc($headmaster_nip) ?></p>
                </td>
            </tr>
        </table>
    </div>

</body>

</html>