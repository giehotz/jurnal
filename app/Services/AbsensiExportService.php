<?php

namespace App\Services;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class AbsensiExportService
{
    public function exportToExcel($data, $startDate, $endDate, $effectiveDays, $hariLiburDetail)
    {
        // Tentukan periode berdasarkan tanggal
        $startMonth = date('n', strtotime($startDate));
        $endMonth = date('n', strtotime($endDate));
        $startYear = date('Y', strtotime($startDate));
        $endYear = date('Y', strtotime($endDate));

        // Cek apakah single month
        $isSingleMonth = ($startMonth == $endMonth && $startYear == $endYear);
        
        // Tentukan judul periode
        if ($startMonth == 1 && $endMonth == 12 && $startYear == $endYear) {
            $periodeText = $startYear; // Tahun lengkap
            $isTahunan = true;
        } else if ($isSingleMonth) {
            $periodeText = date('F Y', strtotime($startDate)); // 1 bulan
            $isTahunan = false;
        } else {
            $periodeText = date('M Y', strtotime($startDate)) . ' - ' . date('M Y', strtotime($endDate)); // Beberapa bulan
            $isTahunan = false;
        }

        // Struktur data untuk processing
        $dataSiswa = [];
        $currentSiswa = null;

        foreach ($data as $row) {
            $siswaId = $row['siswa_id'] ?? $row['nis']; // Gunakan NIS jika ID tidak tersedia
            
            // Perbaikan logika deteksi siswa unik
            // Kita gunakan ID siswa sebagai key array untuk memastikan tidak ada duplikasi
            if (!isset($dataSiswa[$siswaId])) {
                // Siswa baru
                $dataSiswa[$siswaId] = [
                    'nama' => $row['nama_siswa'],
                    'nisn' => $row['nisn'] ?? '',
                    'nis' => $row['nis'],
                    'rombel' => $row['nama_rombel'],
                    'kode_rombel' => $row['kode_rombel'],
                    'bulanan' => [],
                    'total' => ['hadir' => 0, 'izin' => 0, 'sakit' => 0, 'alfa' => 0]
                ];
            }
            
            // Data per bulan
            $bulan = date('n', strtotime($row['tanggal']));
            $tahun = date('Y', strtotime($row['tanggal']));
            $bulanTahun = $bulan . '-' . $tahun;
            $date = date('Y-m-d', strtotime($row['tanggal']));
            
            // Inisialisasi data bulan jika belum ada
            if (!isset($dataSiswa[$siswaId]['bulanan'][$bulanTahun])) {
                $dataSiswa[$siswaId]['bulanan'][$bulanTahun] = [
                    'hadir' => 0,
                    'izin' => 0,
                    'sakit' => 0,
                    'alfa' => 0
                ];
            }
            
            // Cek duplikasi harian per status
            // Pastikan satu siswa hanya dihitung satu kali per status per hari
            if (!isset($dataSiswa[$siswaId]['processed_daily'][$date][$row['status']])) {
                $dataSiswa[$siswaId]['processed_daily'][$date][$row['status']] = true;
                
                // Akumulasi data berdasarkan status
                $dataSiswa[$siswaId]['bulanan'][$bulanTahun][$row['status']] += 1;
                $dataSiswa[$siswaId]['total'][$row['status']] += 1;
            }
        }

        // Create Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set properties
        $spreadsheet->getProperties()
            ->setCreator('Jurnal Guru App')
            ->setLastModifiedBy('Jurnal Guru App')
            ->setTitle('Laporan Absensi ' . $periodeText)
            ->setSubject('Laporan Absensi')
            ->setDescription('Laporan Absensi Siswa Periode ' . $periodeText);
            
        // Header
        $sheet->setCellValue('A1', 'LAPORAN ABSENSI SISWA');
        $sheet->setCellValue('A2', 'PERIODE: ' . strtoupper($periodeText));
        $sheet->mergeCells('A1:H1');
        $sheet->mergeCells('A2:H2');
        $sheet->getStyle('A1:A2')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1:A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        
        // Info Hari Efektif
        $sheet->setCellValue('A4', 'Hari Efektif: ' . $effectiveDays . ' hari');
        
        // Table Header
        $row = 6;
        $sheet->setCellValue('A'.$row, 'No');
        $sheet->setCellValue('B'.$row, 'NIS');
        $sheet->setCellValue('C'.$row, 'NISN');
        $sheet->setCellValue('D'.$row, 'Nama Siswa');
        $sheet->setCellValue('E'.$row, 'Kelas');
        $sheet->setCellValue('F'.$row, 'Hadir');
        $sheet->setCellValue('G'.$row, 'Sakit');
        $sheet->setCellValue('H'.$row, 'Izin');
        $sheet->setCellValue('I'.$row, 'Alfa');
        $sheet->setCellValue('J'.$row, 'Persentase');
        
        $sheet->getStyle('A'.$row.':J'.$row)->getFont()->setBold(true);
        $sheet->getStyle('A'.$row.':J'.$row)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        
        // Data Rows
        $row++;
        $no = 1;
        foreach ($dataSiswa as $siswa) {
            $sheet->setCellValue('A'.$row, $no++);
            $sheet->setCellValue('B'.$row, $siswa['nis']);
            $sheet->setCellValue('C'.$row, $siswa['nisn']);
            $sheet->setCellValue('D'.$row, $siswa['nama']);
            $sheet->setCellValue('E'.$row, $siswa['rombel']);
            $sheet->setCellValue('F'.$row, $siswa['total']['hadir']);
            $sheet->setCellValue('G'.$row, $siswa['total']['sakit']);
            $sheet->setCellValue('H'.$row, $siswa['total']['izin']);
            $sheet->setCellValue('I'.$row, $siswa['total']['alfa']);
            
            // Hitung persentase kehadiran
            $totalAbsen = $siswa['total']['hadir'] + $siswa['total']['sakit'] + $siswa['total']['izin'] + $siswa['total']['alfa'];
            // Atau gunakan hari efektif sebagai pembagi jika ingin persentase terhadap hari efektif
            // $persentase = ($effectiveDays > 0) ? ($siswa['total']['hadir'] / $effectiveDays) * 100 : 0;
            
            // Gunakan total pertemuan yang tercatat saja untuk saat ini
            $persentase = ($totalAbsen > 0) ? ($siswa['total']['hadir'] / $totalAbsen) * 100 : 0;
            
            $sheet->setCellValue('J'.$row, number_format($persentase, 2) . '%');
            
            $sheet->getStyle('A'.$row.':J'.$row)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $row++;
        }
        
        // Auto size columns
        foreach (range('A', 'J') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Output
        $writer = new Xlsx($spreadsheet);
        $filename = 'Laporan_Absensi_' . str_replace(' ', '_', $periodeText) . '.xlsx';
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
        exit;
    }

    public function exportToPDF($data, $startDate, $endDate)
    {
        // Implementasi PDF export jika diperlukan
        // Untuk saat ini placeholder atau redirect back
        return redirect()->back()->with('error', 'Fitur export PDF belum tersedia');
    }
}
