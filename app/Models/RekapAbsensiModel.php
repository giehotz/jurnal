<?php

namespace App\Models;

use CodeIgniter\Model;

class RekapAbsensiModel extends Model
{
    protected $table = 'rekap_absensi';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'tanggal',
        'rombel_id',
        'siswa_id',
        'guru_id',
        'mapel_id',
        'total_hadir',
        'total_sakit',
        'total_izin',
        'total_alfa',
        'total_pertemuan',
        'persentase_kehadiran',
        'bulan',
        'tahun',
        'semester',
        'tahun_ajaran'
    ];
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Get monthly recap for a specific class and period
     */
    public function getMonthlyRekap($rombelId, $month, $year)
    {
        return $this->select('rekap_absensi.*, siswa.nama as nama_siswa, siswa.nis')
            ->join('siswa', 'siswa.id = rekap_absensi.siswa_id')
            ->where('rekap_absensi.rombel_id', $rombelId)
            ->where('rekap_absensi.bulan', $month)
            ->where('rekap_absensi.tahun', $year)
            ->orderBy('siswa.nama', 'ASC')
            ->findAll();
    }
}
