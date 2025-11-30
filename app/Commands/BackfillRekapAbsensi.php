<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\AbsensiModel;
use App\Models\RekapAbsensiModel;
use App\Models\RombelModel;

class BackfillRekapAbsensi extends BaseCommand
{
    protected $group       = 'Absensi';
    protected $name        = 'absensi:rekap-backfill';
    protected $description = 'Populates the rekap_absensi table from existing absensi data.';

    public function run(array $params)
    {
        CLI::write('Starting backfill process...', 'yellow');

        $absensiModel = new AbsensiModel();
        $rekapModel = new RekapAbsensiModel();
        $rombelModel = new RombelModel();

        // Get all absensi records
        // For large datasets, chunking is better, but let's assume it fits in memory or use pagination
        $total = $absensiModel->countAll();
        CLI::write("Found $total absensi records.", 'white');

        $limit = 100;
        $offset = 0;
        $processed = 0;

        while ($processed < $total) {
            $records = $absensiModel->findAll($limit, $offset);
            
            if (empty($records)) break;

            foreach ($records as $record) {
                // Logic similar to syncRekap in AbsensiModel
                $rombel = $rombelModel->find($record['rombel_id']);
                if (!$rombel) continue;

                $status = $record['status'];
                $totalHadir = ($status == 'hadir') ? 1 : 0;
                $totalSakit = ($status == 'sakit') ? 1 : 0;
                $totalIzin = ($status == 'izin') ? 1 : 0;
                $totalAlfa = ($status == 'alfa') ? 1 : 0;

                $date = date_create($record['tanggal']);
                $bulan = date_format($date, 'n');
                $tahun = date_format($date, 'Y');

                $rekapData = [
                    'tanggal' => $record['tanggal'],
                    'rombel_id' => $record['rombel_id'],
                    'siswa_id' => $record['siswa_id'],
                    'guru_id' => $record['guru_id'],
                    'mapel_id' => $record['mapel_id'],
                    'total_hadir' => $totalHadir,
                    'total_sakit' => $totalSakit,
                    'total_izin' => $totalIzin,
                    'total_alfa' => $totalAlfa,
                    'total_pertemuan' => 1,
                    'persentase_kehadiran' => ($totalHadir * 100),
                    'bulan' => $bulan,
                    'tahun' => $tahun,
                    'semester' => $rombel['semester'],
                    'tahun_ajaran' => $rombel['tahun_ajaran']
                ];

                // Check existence
                $existing = $rekapModel->where('tanggal', $record['tanggal'])
                                     ->where('rombel_id', $record['rombel_id'])
                                     ->where('siswa_id', $record['siswa_id'])
                                     ->where('mapel_id', $record['mapel_id'])
                                     ->first();

                if ($existing) {
                    $rekapModel->update($existing['id'], $rekapData);
                } else {
                    $rekapModel->insert($rekapData);
                }
            }

            $processed += count($records);
            $offset += $limit;
            CLI::showProgress($processed, $total);
        }

        CLI::write('Backfill completed!', 'green');
    }
}
