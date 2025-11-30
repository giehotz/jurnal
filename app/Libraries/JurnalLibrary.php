<?php

namespace App\Libraries;

use CodeIgniter\Validation\ValidationInterface;

class JurnalLibrary
{
    protected $validation;
    protected $uploadPath;

    public function __construct(ValidationInterface $validation)
    {
        $this->validation = $validation;
        $this->uploadPath = FCPATH . 'uploads' . DIRECTORY_SEPARATOR;
    }

    /**
     * Validate jurnal input data
     *
     * @param array|null $data
     * @return array|bool
     */
    public function validateInput(?array $data = null)
    {
        $request = service('request');
        $data = $data ?? $request->getPost();

        $rules = [
            'tanggal' => 'required|valid_date',
            'rombel_id' => 'required|integer',
            'mapel_id' => 'required|integer',
            'jam_ke' => 'required',
            'materi' => 'required|max_length[200]',
            'jumlah_jam' => 'required|integer',
            'jumlah_peserta' => 'permit_empty|integer',
        ];

        $this->validation->setRules($rules);

        if (!$this->validation->run($data)) {
            return $this->validation->getErrors();
        }

        return true;
    }

    /**
     * Validate uploaded file
     * 
     * @return array|bool
     */
    public function validateFile()
    {
        $request = service('request');
        $file = $request->getFile('bukti_dukung');

        if ($file && $file->isValid() && !$file->hasMoved()) {
            $rules = [
                'bukti_dukung' => 'mime_in[bukti_dukung,image/jpg,image/jpeg,image/png,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet]|max_size[bukti_dukung,5120]'
            ];
            
            $this->validation->setRules($rules);
            if (!$this->validation->withRequest($request)->run()) {
                return $this->validation->getErrors();
            }
        }
        
        return true;
    }

    /**
     * Format data before saving to database
     *
     * @param int|null $userId
     * @return array
     */
    public function formatData(?int $userId = null): array
    {
        $request = service('request');

        $data = [
            'tanggal' => $request->getPost('tanggal'),
            'rombel_id' => $request->getPost('rombel_id'),
            'mapel_id' => $request->getPost('mapel_id'),
            'jam_ke' => $request->getPost('jam_ke'),
            'materi' => $request->getPost('materi'),
            'jumlah_jam' => $request->getPost('jumlah_jam'),
            'jumlah_peserta' => $request->getPost('jumlah_peserta'),
            'keterangan' => $request->getPost('keterangan'),
            'status' => $request->getPost('status') ?? 'draft',
        ];

        if ($userId) {
            $data['user_id'] = $userId;
        }

        return $data;
    }

    /**
     * Handle file upload for jurnal evidence
     *
     * @param \CodeIgniter\HTTP\Files\UploadedFile|null $file
     * @param string|null $existingFileName
     * @return string|null
     */
    public function handleFileUpload(?\CodeIgniter\HTTP\Files\UploadedFile $file, ?string $existingFileName = null): ?string
    {
        if (!$file || !$file->isValid() || $file->hasMoved()) {
            return $existingFileName;
        }

        // Generate unique file name
        $newName = $file->getRandomName();

        // Move file to uploads directory
        if ($file->move($this->uploadPath, $newName)) {
            // Delete old file if exists
            if ($existingFileName && file_exists($this->uploadPath . $existingFileName)) {
                unlink($this->uploadPath . $existingFileName);
            }

            return $newName;
        }

        return $existingFileName;
    }

    /**
     * Delete file from storage
     *
     * @param string|null $fileName
     * @return bool
     */
    public function deleteFile(?string $fileName): bool
    {
        if ($fileName && file_exists($this->uploadPath . $fileName)) {
            return unlink($this->uploadPath . $fileName);
        }

        return false;
    }

    /**
     * Generate jurnal code
     *
     * @param int $userId
     * @param string $date
     * @return string
     */
   public function generateJurnalCode(int $userId, string $date): string
{
    $random = strtoupper(bin2hex(random_bytes(3)));
    return 'JRN-' . $userId . '-' . date('Ymd', strtotime($date)) . '-' . $random;
}

}