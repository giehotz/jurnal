<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class Export extends BaseController
{
    public function index()
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        return view('admin/export/index');
    }

    public function usersToExcel()
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        // Membuat objek spreadsheet baru
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Menambahkan header
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'NIP');
        $sheet->setCellValue('C1', 'Nama');
        $sheet->setCellValue('D1', 'Email');
        $sheet->setCellValue('E1', 'Role');
        $sheet->setCellValue('F1', 'Status');

        // Menambahkan data (contoh data statis)
        $data = [
            ['id' => 1, 'nip' => '123456789', 'nama' => 'Budi Santoso', 'email' => 'budi@example.com', 'role' => 'guru', 'is_active' => 1],
            ['id' => 2, 'nip' => '987654321', 'nama' => 'Siti Rahayu', 'email' => 'siti@example.com', 'role' => 'guru', 'is_active' => 1],
            ['id' => 3, 'nip' => '456789123', 'nama' => 'Ahmad Fauzi', 'email' => 'ahmad@example.com', 'role' => 'admin', 'is_active' => 0],
        ];

        $row = 2;
        foreach ($data as $item) {
            $sheet->setCellValue('A' . $row, $item['id']);
            $sheet->setCellValue('B' . $row, $item['nip']);
            $sheet->setCellValue('C' . $row, $item['nama']);
            $sheet->setCellValue('D' . $row, $item['email']);
            $sheet->setCellValue('E' . $row, $item['role']);
            $sheet->setCellValue('F' . $row, $item['is_active'] ? 'Aktif' : 'Non-aktif');
            $row++;
        }

        // Menyiapkan file untuk diunduh
        $filename = 'users-' . date('Y-m-d-His') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    public function import()
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        return view('admin/export/import');
    }

    public function importUsersFromExcel()
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        $validationRule = [
            'file' => [
                'label' => 'File Excel',
                'rules' => 'uploaded[file]|ext_in[file,xls,xlsx]|max_size[file,2048]',
            ],
        ];

        if (!$this->validate($validationRule)) {
            session()->setFlashdata('error', 'File tidak valid. Pastikan file berupa Excel (xls/xlsx) dan ukuran maksimal 2MB.');
            return redirect()->back()->withInput()->with('error', $this->validator->getError('file'));
        }

        $file = $this->request->getFile('file');

        if ($file->isValid() && !$file->hasMoved()) {
            // Mendapatkan path file temporary
            $filepath = $file->getPathname();

            // Membaca file Excel
            $spreadsheet = IOFactory::load($filepath);
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            // Proses data (contoh sederhana)
            $dataImported = [];
            foreach ($rows as $index => $row) {
                // Lewati baris header
                if ($index == 0) continue;

                // Pastikan baris memiliki data
                if (!empty(array_filter($row))) {
                    $dataImported[] = [
                        'id' => $row[0],
                        'nip' => $row[1],
                        'nama' => $row[2],
                        'email' => $row[3],
                        'role' => $row[4],
                        'status' => $row[5],
                    ];
                }
            }

            session()->setFlashdata('success', 'Berhasil mengimpor ' . count($dataImported) . ' data dari file Excel.');
            return redirect()->to('/admin/export/import');
        }

        session()->setFlashdata('error', 'Gagal mengimpor file.');
        return redirect()->to('/admin/export/import');
    }
}