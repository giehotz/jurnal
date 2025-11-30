<?php

namespace App\Models;

use CodeIgniter\Model;

class AbsensiModel extends Model
{
    protected $table = 'absensi';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'jurnal_id',
        'tanggal',
        'rombel_id',
        'guru_id',
        'mapel_id',
        'siswa_id',
        'status',
        'keterangan'
    ];
    protected $returnType = 'array';
    protected $useTimestamps = false;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    // Menyimpan data absensi
    public function simpanAbsensi($data)
    {
        return $this->insert($data);
    }
    
    // Mendapatkan data absensi berdasarkan jurnal
    public function getAbsensiByJurnal($jurnal_id)
    {
        return $this->where('jurnal_id', $jurnal_id)
                    ->findAll();
    }
    
    // Mendapatkan data absensi berdasarkan siswa
    public function getAbsensiBySiswa($siswa_id)
    {
        return $this->where('siswa_id', $siswa_id)->first();
    }
    
    // Memperbarui data absensi
    public function updateAbsensi($id, $data)
    {
        return $this->update($id, $data);
    }
    
    // Mendapatkan data absensi berdasarkan tanggal dan rombel
    public function getAbsensiByDate($startDate, $endDate, $rombelId = null)
    {
        $builder = $this->db->table('absensi a');
        $builder->select('a.*, s.nis, s.nama as nama_siswa, r.nama_rombel');
        $builder->join('siswa s', 'a.siswa_id = s.id');
        $builder->join('rombel r', 'a.rombel_id = r.id');
        $builder->where('a.tanggal >=', $startDate);
        $builder->where('a.tanggal <=', $endDate);
        
        if ($rombelId) {
            $builder->where('a.rombel_id', $rombelId);
        }
        
        $builder->orderBy('a.tanggal, r.nama_rombel, s.nama');
        
        return $builder->get()->getResultArray();
    }
    
    // Mendapatkan rekap absensi
    public function getRekapAbsensi($tahunAjaran, $semester, $rombelId = null)
    {
        $builder = $this->db->table('absensi a');
        $builder->select('
            s.id as siswa_id,
            s.nis,
            s.nama as nama_siswa,
            r.nama_rombel,
            COUNT(CASE WHEN a.status = "hadir" THEN 1 END) as hadir,
            COUNT(CASE WHEN a.status = "sakit" THEN 1 END) as sakit,
            COUNT(CASE WHEN a.status = "izin" THEN 1 END) as izin,
            COUNT(CASE WHEN a.status = "alfa" THEN 1 END) as alfa,
            COUNT(*) as total
        ');
        $builder->join('siswa s', 'a.siswa_id = s.id');
        $builder->join('rombel r', 'a.rombel_id = r.id');
        $builder->where('r.tahun_ajaran', $tahunAjaran);
        $builder->where('r.semester', $semester);
        
        if ($rombelId) {
            $builder->where('a.rombel_id', $rombelId);
        }
        
        $builder->groupBy('s.id, r.id');
        $builder->orderBy('r.nama_rombel, s.nama');
        
        return $builder->get()->getResultArray();
    }
    
    // Input absensi batch
    public function inputAbsensiBatch($dataAbsensi)
    {
        return $this->insertBatch($dataAbsensi);
    }
    
    // Mendapatkan rekap absensi per tanggal
    public function getRekapAbsensiPerTanggal($startDate, $endDate, $rombelId = null)
    {
        $builder = $this->db->table('absensi a');
        $builder->select('
            a.tanggal,
            COUNT(CASE WHEN a.status = "hadir" THEN 1 END) as hadir,
            COUNT(CASE WHEN a.status = "sakit" THEN 1 END) as sakit,
            COUNT(CASE WHEN a.status = "izin" THEN 1 END) as izin,
            COUNT(CASE WHEN a.status = "alfa" THEN 1 END) as alfa
        ');
        $builder->join('siswa s', 'a.siswa_id = s.id');
        $builder->join('rombel r', 'a.rombel_id = r.id');
        $builder->where('a.tanggal >=', $startDate);
        $builder->where('a.tanggal <=', $endDate);
        
        if ($rombelId) {
            $builder->where('a.rombel_id', $rombelId);
        }
        
        $builder->groupBy('a.tanggal');
        $builder->orderBy('a.tanggal');
        
        return $builder->get()->getResultArray();
    }
    // Events
    protected $afterInsert = ['updateRekapAfterInsert', 'updateRekapHarianAfterInsert'];
    protected $afterUpdate = ['updateRekapAfterUpdate', 'updateRekapHarianAfterUpdate'];
    protected $beforeDelete = ['getDeletedData'];
    protected $afterDelete = ['updateRekapAfterDelete', 'updateRekapHarianAfterDelete'];
    
    protected $tempDeletedData = [];

    // Event Callbacks
    protected function updateRekapAfterInsert(array $data)
    {
        if ($data['result']) {
            $this->syncRekap($data['data']);
        }
        return $data;
    }

    protected function updateRekapAfterUpdate(array $data)
    {
        if ($data['result']) {
            $ids = $data['id'];
            if (!is_array($ids)) $ids = [$ids];
            
            foreach ($ids as $id) {
                $record = $this->find($id);
                if ($record) {
                    $this->syncRekap($record);
                }
            }
        }
        return $data;
    }
    
    protected function getDeletedData(array $data)
    {
        $ids = $data['id'];
        if (!is_array($ids)) $ids = [$ids];
        
        $this->tempDeletedData = $this->whereIn('id', $ids)->findAll();
        return $data;
    }
    
    protected function updateRekapAfterDelete(array $data)
    {
        if ($data['result'] && !empty($this->tempDeletedData)) {
            $rekapModel = new \App\Models\RekapAbsensiModel();
            foreach ($this->tempDeletedData as $record) {
                $rekapModel->where('tanggal', $record['tanggal'])
                           ->where('rombel_id', $record['rombel_id'])
                           ->where('siswa_id', $record['siswa_id'])
                           ->where('mapel_id', $record['mapel_id'])
                           ->delete();
            }
            $this->tempDeletedData = [];
        }
        return $data;
    }

    /**
     * Sync data to rekap_absensi table
     */
    protected function syncRekap($absensiData)
    {
        $rekapModel = new \App\Models\RekapAbsensiModel();
        $rombelModel = new \App\Models\RombelModel();
        
        // Get Rombel Info for Semester & Tahun Ajaran
        $rombel = $rombelModel->find($absensiData['rombel_id']);
        if (!$rombel) return;

        // Calculate flags
        $status = $absensiData['status'];
        $totalHadir = ($status == 'hadir') ? 1 : 0;
        $totalSakit = ($status == 'sakit') ? 1 : 0;
        $totalIzin = ($status == 'izin') ? 1 : 0;
        $totalAlfa = ($status == 'alfa') ? 1 : 0;
        
        // Parse Date
        $date = date_create($absensiData['tanggal']);
        $bulan = date_format($date, 'n');
        $tahun = date_format($date, 'Y');
        
        // Prepare Data
        $rekapData = [
            'tanggal' => $absensiData['tanggal'],
            'rombel_id' => $absensiData['rombel_id'],
            'siswa_id' => $absensiData['siswa_id'],
            'guru_id' => $absensiData['guru_id'],
            'mapel_id' => $absensiData['mapel_id'],
            'total_hadir' => $totalHadir,
            'total_sakit' => $totalSakit,
            'total_izin' => $totalIzin,
            'total_alfa' => $totalAlfa,
            'total_pertemuan' => 1,
            'persentase_kehadiran' => ($totalHadir * 100), // 100% if hadir, 0% otherwise for single record
            'bulan' => $bulan,
            'tahun' => $tahun,
            'semester' => $rombel['semester'],
            'tahun_ajaran' => $rombel['tahun_ajaran']
        ];
        
        // Upsert (Insert or Update)
        // Check if exists first (CI4 upsert is not always available or behaves differently across drivers)
        // Since we have a unique key, we can try to find it first.
        $existing = $rekapModel->where('tanggal', $absensiData['tanggal'])
                             ->where('rombel_id', $absensiData['rombel_id'])
                             ->where('siswa_id', $absensiData['siswa_id'])
                             ->where('mapel_id', $absensiData['mapel_id'])
                             ->first();
                             
        if ($existing) {
            $rekapModel->update($existing['id'], $rekapData);
        } else {
            $rekapModel->insert($rekapData);
        }
        }


    // --- Rekap Harian Logic ---

    protected function updateRekapHarianAfterInsert(array $data)
    {
        if ($data['result']) {
            $this->syncRekapHarian($data['data']);
        }
        return $data;
    }

    protected function updateRekapHarianAfterUpdate(array $data)
    {
        if ($data['result']) {
            $ids = $data['id'];
            if (!is_array($ids)) $ids = [$ids];
            
            foreach ($ids as $id) {
                $record = $this->find($id);
                if ($record) {
                    $this->syncRekapHarian($record);
                }
            }
        }
        return $data;
    }

    protected function updateRekapHarianAfterDelete(array $data)
    {
        if ($data['result'] && !empty($this->tempDeletedData)) {
            foreach ($this->tempDeletedData as $record) {
                $this->syncRekapHarian($record);
            }
        }
        return $data;
    }

    /**
     * Sync data to rekap_absensi_harian table
     */
    protected function syncRekapHarian($absensiData)
    {
        $rombelId = $absensiData['rombel_id'];
        $tanggal = $absensiData['tanggal'];
        
        // Calculate stats from absensi table
        // We use $this->db->table($this->table) to avoid model scopes if any
        $stats = $this->db->table($this->table)
            ->select('
                COUNT(*) as total_siswa,
                SUM(CASE WHEN status = "hadir" THEN 1 ELSE 0 END) as total_hadir,
                SUM(CASE WHEN status = "sakit" THEN 1 ELSE 0 END) as total_sakit,
                SUM(CASE WHEN status = "izin" THEN 1 ELSE 0 END) as total_izin,
                SUM(CASE WHEN status = "alfa" THEN 1 ELSE 0 END) as total_alfa
            ')
            ->where('rombel_id', $rombelId)
            ->where('tanggal', $tanggal)
            ->get()
            ->getRowArray();
            
        // If no records found (e.g. all deleted), stats will be 0s
        $totalSiswa = (int)($stats['total_siswa'] ?? 0);
        $totalHadir = (int)($stats['total_hadir'] ?? 0);
        
        $persentase = ($totalSiswa > 0) ? ($totalHadir / $totalSiswa) * 100 : 0;
        
        // Get Rombel Info
        $rombelModel = new \App\Models\RombelModel();
        $rombel = $rombelModel->find($rombelId);
        
        if (!$rombel) return; // Should not happen

        // Date info
        $date = date_create($tanggal);
        $bulan = date_format($date, 'n');
        $tahun = date_format($date, 'Y');

        $rekapHarianModel = new \App\Models\RekapAbsensiHarianModel();
        
        $dataToSave = [
            'tanggal' => $tanggal,
            'rombel_id' => $rombelId,
            'guru_id' => $absensiData['guru_id'] ?? null,
            'mapel_id' => $absensiData['mapel_id'] ?? null,
            'total_siswa' => $totalSiswa,
            'total_hadir' => $totalHadir,
            'total_sakit' => (int)($stats['total_sakit'] ?? 0),
            'total_izin' => (int)($stats['total_izin'] ?? 0),
            'total_alfa' => (int)($stats['total_alfa'] ?? 0),
            'persentase_kehadiran' => $persentase,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'semester' => $rombel['semester'] ?? '1',
            'tahun_ajaran' => $rombel['tahun_ajaran'] ?? '',
        ];

        // Check if exists
        $existing = $rekapHarianModel->where('tanggal', $tanggal)
                                     ->where('rombel_id', $rombelId)
                                     ->first();
                                     
        if ($existing) {
            if ($totalSiswa == 0) {
                // If no students left in absensi, maybe we should delete the rekap record?
                // Or keep it as 0? User didn't specify "delete on empty".
                // But "Case 1: Tidak ada absensi" implies we might want to keep it or it just doesn't exist.
                // If we delete all absensi, usually we want the rekap to reflect that (0 or gone).
                // Let's delete it to keep it clean if no absensi exists.
                $rekapHarianModel->delete($existing['id']);
            } else {
                $rekapHarianModel->update($existing['id'], $dataToSave);
            }
        } else {
            if ($totalSiswa > 0) {
                $rekapHarianModel->insert($dataToSave);
            }
        }
    }
}