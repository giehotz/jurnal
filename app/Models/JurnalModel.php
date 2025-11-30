<?php

namespace App\Models;

use CodeIgniter\Model;

class JurnalModel extends Model
{
    protected $table = 'jurnal_new';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'user_id',
        'tanggal',
        'rombel_id', // Diubah dari kelas_id
        'mapel_id',
        'jam_ke',
        'materi',
        'jumlah_jam',
        'bukti_dukung',
        'jumlah_peserta',
        'jumlah_siswa_hadir',
        'jumlah_siswa_tidak_hadir',
        'keterangan',
        'status',
        'created_at',
        'updated_at'
    ];
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $deletedField  = '';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Untuk menyimpan data jurnal
     */
    public function getJurnal($id = false)
    {
        if ($id === false) {
            return $this->findAll();
        }

        return $this->find($id);
    }

    /**
     * Mengambil jurnal dengan filter
     *
     * @param array $filters
     * @return array
     */
    public function getFilteredJurnal($filters = [])
    {
        $builder = $this->db->table('jurnal_new j');
        $builder->select('
            j.*, 
            r.nama_rombel as nama_kelas, 
            r.kode_rombel as kode_kelas, 
            u.nama as nama_guru, 
            m.nama_mapel
        ');
        $builder->join('rombel r', 'j.rombel_id = r.id'); // Diubah dari kelas
        $builder->join('users u', 'j.user_id = u.id');
        $builder->join('mata_pelajaran m', 'j.mapel_id = m.id');
        
        // Terapkan filter jika ada
        if (!empty($filters['user_id'])) {
            $builder->where('j.user_id', $filters['user_id']);
        }
        
        if (!empty($filters['tanggal'])) {
            $builder->where('j.tanggal', $filters['tanggal']);
        }
        
        if (!empty($filters['rombel_id'])) { // Diubah dari kelas_id
            $builder->where('j.rombel_id', $filters['rombel_id']); // Diubah dari kelas_id
        }
        
        if (!empty($filters['mapel_id'])) {
            $builder->where('j.mapel_id', $filters['mapel_id']);
        }
        
        $builder->where('j.mapel_id !=', 18); // Exclude Absensi
        $builder->notLike('j.materi', 'Absensi Kelas');
        $builder->notLike('j.keterangan', 'Generated from Absensi');
        
        $builder->orderBy('j.tanggal', 'DESC');
        
        return $builder->get()->getResultArray();
    }
    
    /**
     * Mengambil jurnal dengan detail rombel, guru, dan mata pelajaran
     *
     * @param int|null $userId
     * @return array
     */
    public function getJurnalWithDetails($userId = null)
    {
        $builder = $this->db->table('jurnal_new j');
        $builder->select('
            j.*, 
            r.nama_rombel as nama_kelas, 
            r.kode_rombel as kode_kelas, 
            u.nama as nama_guru, 
            m.nama_mapel
        ');
        $builder->join('rombel r', 'j.rombel_id = r.id'); // Diubah dari kelas
        $builder->join('users u', 'j.user_id = u.id');
        $builder->join('mata_pelajaran m', 'j.mapel_id = m.id');
        
        if ($userId) {
            $builder->where('j.user_id', $userId);
        }
        
        // Exclude Absensi (Mapel ID 18) from Jurnal list
        $builder->where('j.mapel_id !=', 18);
        $builder->notLike('j.materi', 'Absensi Kelas');
        $builder->notLike('j.keterangan', 'Generated from Absensi');
        
        $builder->orderBy('j.created_at', 'DESC');
        
        return $builder->get()->getResultArray();
    }
}