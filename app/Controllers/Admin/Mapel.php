<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\MataPelajaranModel;

class Mapel extends BaseController
{
    protected $mapelModel;

    public function __construct()
    {
        $this->mapelModel = new MataPelajaranModel();
    }

    public function index()
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        // Ambil data mata pelajaran untuk ditampilkan
        $mapels = $this->mapelModel->getSubjects();

        $data = [
            'title' => 'Manajemen Mata Pelajaran',
            'active' => 'mapel',
            'mapels' => $mapels
        ];

        return view('admin/mapel/index', $data);
    }

    public function create()
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        $data = [
            'title' => 'Tambah Mata Pelajaran',
            'active' => 'mapel'
        ];

        return view('admin/mapel/create', $data);
    }

    public function store()
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        // Validasi input
        $validationRules = [
            'kode_mapel' => 'required|is_unique[mata_pelajaran.kode_mapel]',
            'nama_mapel' => 'required',
        ];

        if (!$this->validate($validationRules)) {
            session()->setFlashdata('error', 'Gagal menambahkan mata pelajaran. Silakan periksa kembali data yang dimasukkan.');
            return redirect()->back()->withInput();
        }

        // Simpan data mata pelajaran
        $data = [
            'kode_mapel' => $this->request->getPost('kode_mapel'),
            'nama_mapel' => $this->request->getPost('nama_mapel'),
        ];

        if ($this->mapelModel->save($data)) {
            session()->setFlashdata('success', 'Mata pelajaran berhasil ditambahkan.');
        } else {
            session()->setFlashdata('error', 'Gagal menambahkan mata pelajaran.');
        }

        return redirect()->to('/admin/mapel');
    }

    public function edit($id)
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        // Ambil data mata pelajaran berdasarkan ID
        $mapel = $this->mapelModel->getSubjectById($id);

        if (!$mapel) {
            session()->setFlashdata('error', 'Mata pelajaran tidak ditemukan!');
            return redirect()->to('/admin/mapel');
        }

        $data = [
            'title' => 'Edit Mata Pelajaran',
            'active' => 'mapel',
            'mapel' => $mapel
        ];

        return view('admin/mapel/edit', $data);
    }

    public function update($id)
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        // Validasi input
        $validationRules = [
            'kode_mapel' => 'required|is_unique[mata_pelajaran.kode_mapel,id,' . $id . ']',
            'nama_mapel' => 'required',
        ];

        if (!$this->validate($validationRules)) {
            session()->setFlashdata('error', 'Gagal mengupdate mata pelajaran. Silakan periksa kembali data yang dimasukkan.');
            return redirect()->back()->withInput();
        }

        // Update data mata pelajaran
        $data = [
            'kode_mapel' => $this->request->getPost('kode_mapel'),
            'nama_mapel' => $this->request->getPost('nama_mapel'),
        ];

        if ($this->mapelModel->update($id, $data)) {
            session()->setFlashdata('success', 'Mata pelajaran berhasil diupdate.');
        } else {
            session()->setFlashdata('error', 'Gagal mengupdate mata pelajaran.');
        }

        return redirect()->to('/admin/mapel');
    }

    public function delete($id)
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        // Hapus data mata pelajaran
        if ($this->mapelModel->delete($id)) {
            session()->setFlashdata('success', 'Mata pelajaran berhasil dihapus.');
        } else {
            session()->setFlashdata('error', 'Gagal menghapus mata pelajaran.');
        }

        return redirect()->to('/admin/mapel');
    }

    public function upload()
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        $data = [
            'title' => 'Upload Mata Pelajaran',
            'active' => 'mapel'
        ];

        return view('admin/mapel/upload', $data);
    }

    public function downloadTemplate()
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        try {
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Header
            $sheet->setCellValue('A1', 'Kode Mapel');
            $sheet->setCellValue('B1', 'Nama Mapel');

            // Contoh Data
            $sheet->setCellValue('A2', 'MTK');
            $sheet->setCellValue('B2', 'Matematika');
            $sheet->setCellValue('A3', 'IPA');
            $sheet->setCellValue('B3', 'Ilmu Pengetahuan Alam');

            // Style Header
            $headerStyle = [
                'font' => ['bold' => true],
                'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E2E2E2']],
                'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]]
            ];
            $sheet->getStyle('A1:B1')->applyFromArray($headerStyle);

            // Auto size column
            foreach (range('A', 'B') as $columnID) {
                $sheet->getColumnDimension($columnID)->setAutoSize(true);
            }

            $filename = 'template_mapel.xlsx';

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');

            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->save('php://output');
            exit;
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal membuat template: ' . $e->getMessage());
        }
    }

    public function import()
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        $validationRule = [
            'file' => [
                'label' => 'File Excel',
                'rules' => 'uploaded[file]|ext_in[file,xls,xlsx,csv]|max_size[file,2048]',
            ],
        ];

        if (!$this->validate($validationRule)) {
            return redirect()->back()->withInput()->with('error', 'File tidak valid. Pastikan file berupa Excel (xls/xlsx) dan ukuran maksimal 2MB.');
        }

        $file = $this->request->getFile('file');

        if ($file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(ROOTPATH . 'writable/uploads', $newName);

            try {
                $filePath = ROOTPATH . 'writable/uploads/' . $newName;
                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
                $worksheet = $spreadsheet->getActiveSheet();
                $rows = $worksheet->toArray();

                // Skip header row
                $dataToInsert = [];
                $successCount = 0;
                $failCount = 0;
                $errors = [];

                for ($i = 1; $i < count($rows); $i++) {
                    $row = $rows[$i];
                    
                    if (empty($row[0]) || empty($row[1])) {
                        continue;
                    }

                    $kodeMapel = $row[0];
                    $namaMapel = $row[1];

                    // Cek duplikasi kode mapel
                    $existing = $this->mapelModel->where('kode_mapel', $kodeMapel)->first();
                    
                    if ($existing) {
                        // Update existing
                        $this->mapelModel->update($existing['id'], [
                            'nama_mapel' => $namaMapel
                        ]);
                        $successCount++;
                    } else {
                        // Insert new
                        if ($this->mapelModel->insert([
                            'kode_mapel' => $kodeMapel,
                            'nama_mapel' => $namaMapel
                        ])) {
                            $successCount++;
                        } else {
                            $failCount++;
                            $errors[] = "Baris " . ($i + 1) . ": Gagal menyimpan $kodeMapel";
                        }
                    }
                }

                unlink($filePath);

                $message = "Proses selesai. Berhasil: $successCount, Gagal: $failCount.";
                if (!empty($errors)) {
                    $message .= " Detail error: " . implode(', ', $errors);
                }

                return redirect()->to('/admin/mapel')->with('success', $message);

            } catch (\Exception $e) {
                if (isset($filePath) && file_exists($filePath)) {
                    unlink($filePath);
                }
                return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
            }
        }

        return redirect()->back()->with('error', 'Gagal mengupload file.');
    }
}