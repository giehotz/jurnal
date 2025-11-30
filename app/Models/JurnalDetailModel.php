<?php

namespace App\Models;

use CodeIgniter\Model;

class JurnalDetailModel extends Model
{
    protected $table            = 'jurnal';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id',
        'tanggal',
        'kelas_id',
        'mapel_id',
        'topik',
        'tujuan_pembelajaran',
        'aktivitas_pembelajaran',
        'refleksi_guru',
        'kendala',
        'tindak_lanjut',
        'status',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = '';

    // Validation
    protected $validationRules      = [
        'user_id'                => 'required|integer',
        'tanggal'                => 'required|valid_date',
        'kelas_id'               => 'required|integer',
        'mapel_id'               => 'required|integer',
        'topik'                  => 'required|min_length[3]',
        'tujuan_pembelajaran'    => 'required|min_length[10]',
        'aktivitas_pembelajaran' => 'required|min_length[20]',
        'refleksi_guru'          => 'required|min_length[10]',
        'status'                 => 'required|in_list[draft,published]',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $beforeUpdate   = [];

    /**
     * Get complete jurnal data with all related information
     */
    public function getCompleteJurnalData($jurnalId)
    {
        // Load required models
        $jurnalModel = new JurnalModel();
        $jurnalP5Model = new JurnalP5Model();
        $jurnalAsesmenModel = new JurnalAsesmenModel();
        $jurnalLampiranModel = new JurnalLampiranModel();
        
        // Get main jurnal data
        $jurnal = $jurnalModel->getJurnalWithRelations($jurnalId);
        
        if (!$jurnal) {
            return null;
        }
        
        // Get related data
        $p5 = $jurnalP5Model->getP5WithLabels($jurnalId);
        $asesmen = $jurnalAsesmenModel->getAssessmentsByJurnalId($jurnalId);
        $lampiran = $jurnalLampiranModel->getAttachmentsByJurnalId($jurnalId);
        
        // Combine all data
        $jurnal['p5'] = $p5;
        $jurnal['asesmen'] = $asesmen;
        $jurnal['lampiran'] = $lampiran;
        
        return $jurnal;
    }

    /**
     * Save complete jurnal data with all related information
     */
    public function saveCompleteJurnalData($data)
    {
        // Start transaction
        $this->db->transBegin();
        
        try {
            // Load required models
            $jurnalModel = new JurnalModel();
            $jurnalP5Model = new JurnalP5Model();
            $jurnalAsesmenModel = new JurnalAsesmenModel();
            $jurnalLampiranModel = new JurnalLampiranModel();
            
            // Save main jurnal data
            $jurnalData = [
                'user_id' => $data['user_id'],
                'tanggal' => $data['tanggal'],
                'kelas_id' => $data['kelas_id'],
                'mapel_id' => $data['mapel_id'],
                'topik' => $data['topik'],
                'tujuan_pembelajaran' => $data['tujuan_pembelajaran'],
                'aktivitas_pembelajaran' => $data['aktivitas_pembelajaran'],
                'refleksi_guru' => $data['refleksi_guru'],
                'kendala' => $data['kendala'] ?? null,
                'tindak_lanjut' => $data['tindak_lanjut'] ?? null,
                'status' => $data['status'] ?? 'draft',
            ];
            
            if (isset($data['id']) && $data['id']) {
                // Update existing jurnal
                $jurnalModel->update($data['id'], $jurnalData);
                $jurnalId = $data['id'];
                
                // Delete existing related data
                $jurnalP5Model->where('jurnal_id', $jurnalId)->delete();
                $jurnalAsesmenModel->where('jurnal_id', $jurnalId)->delete();
                $jurnalLampiranModel->where('jurnal_id', $jurnalId)->delete();
            } else {
                // Insert new jurnal
                $jurnalModel->save($jurnalData);
                $jurnalId = $jurnalModel->getInsertID();
            }
            
            // Save P5 data
            if (isset($data['p5']) && is_array($data['p5'])) {
                foreach ($data['p5'] as $p5) {
                    if (!empty($p5['dimensi']) && !empty($p5['aktivitas'])) {
                        $p5Data = [
                            'jurnal_id' => $jurnalId,
                            'dimensi' => $p5['dimensi'],
                            'aktivitas' => $p5['aktivitas'],
                        ];
                        $jurnalP5Model->save($p5Data);
                    }
                }
            }
            
            // Save asesmen data
            if (isset($data['asesmen']) && is_array($data['asesmen'])) {
                foreach ($data['asesmen'] as $asesmen) {
                    if (!empty($asesmen['jenis_asesmen']) && !empty($asesmen['hasil'])) {
                        $asesmenData = [
                            'jurnal_id' => $jurnalId,
                            'jenis_asesmen' => $asesmen['jenis_asesmen'],
                            'hasil' => $asesmen['hasil'],
                            'siswa_tuntas' => $asesmen['siswa_tuntas'] ?? 0,
                            'siswa_total' => $asesmen['siswa_total'] ?? 0,
                        ];
                        $jurnalAsesmenModel->save($asesmenData);
                    }
                }
            }
            
            // Save lampiran data
            if (isset($data['lampiran']) && is_array($data['lampiran'])) {
                foreach ($data['lampiran'] as $lampiran) {
                    if (!empty($lampiran['nama_file']) && !empty($lampiran['file_path'])) {
                        $lampiranData = [
                            'jurnal_id' => $jurnalId,
                            'nama_file' => $lampiran['nama_file'],
                            'file_path' => $lampiran['file_path'],
                            'tipe_file' => $lampiran['tipe_file'] ?? '',
                        ];
                        $jurnalLampiranModel->save($lampiranData);
                    }
                }
            }
            
            // Commit transaction
            $this->db->transCommit();
            return $jurnalId;
        } catch (\Exception $e) {
            // Rollback transaction
            $this->db->transRollback();
            throw $e;
        }
    }
}