<?php

namespace App\Libraries;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ExcelValidator
{
    protected $allowedJenisHari = ['hari_efektif', 'libur_nasional', 'libur_sekolah', 'ujian', 'event', 'rapat'];

    public function validateAndParse($filePath)
    {
        try {
            $spreadsheet = IOFactory::load($filePath);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();
        } catch (\Exception $e) {
            return ['valid' => false, 'errors' => ["Gagal membaca file: " . $e->getMessage()]];
        }

        $data = [];
        $errors = [];
        $rowCount = 0;

        foreach ($rows as $index => $row) {
            // Skip header row
            if ($index === 0) continue;

            // Skip empty rows
            if (empty($row[0]) && empty($row[1])) continue;

            $rowCount++;
            $rowError = [];

            // 1. Validate Date (Col A / Index 0)
            $rawDate = $row[0];
            $parsedDate = null;

            if (is_numeric($rawDate)) {
                // Excel Date Serial
                $parsedDate = Date::excelToDateTimeObject($rawDate)->format('Y-m-d');
            } else {
                // String Date. Try strtotime
                $timestamp = strtotime($rawDate);
                if ($timestamp) {
                    $parsedDate = date('Y-m-d', $timestamp);
                } else {
                    $rowError[] = "Format tanggal tidak valid (Row " . ($index + 1) . ").";
                }
            }

            // 2. Validate Jenis Hari (Col B / Index 1)
            $jenisHari = trim($row[1] ?? '');
            if (empty($jenisHari)) {
                $rowError[] = "Jenis hari tidak boleh kosong (Row " . ($index + 1) . ").";
            }
            // Allow any string, removed in_array check

            // 3. Keterangan (Col C / Index 2)
            $keterangan = trim($row[2] ?? '');

            if (!empty($rowError)) {
                $errors = array_merge($errors, $rowError);
            } else {
                $data[] = [
                    'tanggal' => $parsedDate,
                    'jenis_hari' => $jenisHari,
                    'keterangan' => $keterangan
                ];
            }
        }

        if (empty($data) && empty($errors)) {
            return ['valid' => false, 'errors' => ["File kosong atau tidak ada data yang valid."]];
        }

        return [
            'valid' => empty($errors),
            'data' => $data,
            'errors' => $errors
        ];
    }
}
