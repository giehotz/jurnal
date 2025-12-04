<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class UserManagement extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        // Ambil data user dari database
        $data = [
            'users' => $this->userModel->getUsers()
        ];

        return view('admin/users/list', $data);
    }

    public function exportToPdf()
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        // Ambil data user dari database
        $users = $this->userModel->getUsers();

        // Load library DOMPDF
        $options = new \Dompdf\Options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new \Dompdf\Dompdf($options);

        // Data untuk ditampilkan di PDF
        $data = [
            'users' => $users
        ];

        // Render view ke HTML
        $html = view('admin/users/pdf', $data);

        // Load HTML ke DOMPDF
        $dompdf->loadHtml($html);

        // Setup ukuran kertas dan orientasi
        $dompdf->setPaper('A4', 'portrait');

        // Render PDF
        $dompdf->render();

        // Output PDF ke browser
        $dompdf->stream('daftar_pengguna.pdf', [
            'Attachment' => false // Tampilkan di browser, bukan download otomatis
        ]);

        exit();
    }

    public function create()
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        return view('admin/users/create');
    }

    public function store()
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        // Validasi input
        $rules = [
            'nama' => 'required|min_length[3]',
            'email' => 'required|valid_email',
            'password' => 'required|min_length[8]',
            'role' => 'required|in_list[guru,admin]'
        ];

        if (!$this->validate($rules)) {
            return $this->create();
        }

        // Simpan data user
        $userData = [
            'nip' => $this->request->getPost('nip'),
            'nama' => $this->request->getPost('nama'),
            'email' => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role' => $this->request->getPost('role'),
            'is_active' => 1
        ];

        $this->userModel->save($userData);

        session()->setFlashdata('success', 'User berhasil ditambahkan!');
        return redirect()->to('/admin/users');
    }

    public function edit($id)
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        // Ambil data user dari database
        $data = [
            'user' => $this->userModel->getUserById($id)
        ];

        return view('admin/users/edit', $data);
    }

    public function update($id)
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        // Validasi input
        $rules = [
            'nama' => 'required|min_length[3]',
            'email' => 'required|valid_email',
            'role' => 'required|in_list[guru,admin]'
        ];

        if (!$this->validate($rules)) {
            return $this->edit($id);
        }

        // Update data user
        $userData = [
            'nip' => $this->request->getPost('nip'),
            'nama' => $this->request->getPost('nama'),
            'email' => $this->request->getPost('email'),
            'role' => $this->request->getPost('role'),
            'is_active' => $this->request->getPost('is_active') ? 1 : 0
        ];

        $this->userModel->update($id, $userData);

        session()->setFlashdata('success', 'User berhasil diperbarui!');
        return redirect()->to('/admin/users');
    }

    public function delete($id)
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        // Cek apakah user mencoba menghapus akun sendiri
        if ($id == session()->get('id')) {
            session()->setFlashdata('error', 'Anda tidak dapat menghapus akun Anda sendiri!');
            return redirect()->to('/admin/users');
        }

        // Hapus user dengan error handling
        try {
            $this->userModel->delete($id);
            session()->setFlashdata('success', 'User berhasil dihapus!');
        } catch (\CodeIgniter\Database\Exceptions\DatabaseException $e) {
            // Cek error foreign key constraint (1451)
            if ($e->getCode() == 1451) {
                session()->setFlashdata('error', 'User tidak dapat dihapus karena masih memiliki data terkait (Jurnal, Absensi, dll). Silahkan nonaktifkan user ini sebagai alternatif.');
            } else {
                session()->setFlashdata('error', 'Gagal menghapus user: ' . $e->getMessage());
            }
        } catch (\Exception $e) {
            session()->setFlashdata('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }

        return redirect()->to('/admin/users');
    }

    public function resetPassword($id)
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        // Reset password user (set password default: 12345678)
        $userData = [
            'password' => password_hash('12345678', PASSWORD_DEFAULT)
        ];

        $this->userModel->update($id, $userData);

        session()->setFlashdata('success', 'Password user berhasil direset!');
        return redirect()->to('/admin/users');
    }

    public function import()
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        return view('admin/users/import');
    }

    public function importUsersFromExcel()
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        $validationRule = [
            'file_excel' => [
                'label' => 'File Excel',
                'rules' => 'uploaded[file_excel]|ext_in[file_excel,xls,xlsx]|max_size[file_excel,2048]',
            ],
        ];

        if (!$this->validate($validationRule)) {
            session()->setFlashdata('error', 'File tidak valid. Pastikan file berupa Excel (xls/xlsx) dan ukuran maksimal 2MB.');
            return redirect()->back()->withInput()->with('error', $this->validator->getError('file_excel'));
        }

        $file = $this->request->getFile('file_excel');

        if ($file->isValid() && !$file->hasMoved()) {
            // Mendapatkan path file temporary
            $filepath = $file->getPathname();

            // Membaca file Excel
            $spreadsheet = IOFactory::load($filepath);
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            // Counter untuk data yang berhasil diimpor
            $importedCount = 0;
            $duplicateNIPs = [];
            $duplicateEmails = [];
            $errors = [];

            // Mulai Transaction
            $db = \Config\Database::connect();
            $db->transStart();

            // Proses data
            foreach ($rows as $index => $row) {
                // Lewati baris header (baris pertama)
                if ($index == 0) continue;

                // Pastikan baris memiliki data
                if (!empty(array_filter($row))) {
                    // Validasi data dasar (NIP tidak wajib, Nama dan Email wajib)
                    if (empty($row[2]) || empty($row[3])) {
                        $errors[] = "Baris " . ($index + 1) . ": Nama dan Email harus diisi";
                        continue;
                    }

                    // Cek apakah NIP sudah ada (jika NIP diisi)
                    if (!empty($row[1])) {
                        $existingUserByNIP = $this->userModel->getUserByNIP($row[1]);
                        if ($existingUserByNIP) {
                            $duplicateNIPs[] = "Baris " . ($index + 1) . ": NIP " . $row[1] . " sudah terdaftar (" . $existingUserByNIP['nama'] . ")";
                            continue;
                        }
                    }

                    // Cek apakah email sudah ada
                    $existingUserByEmail = $this->userModel->getUserByEmail($row[3]);
                    if ($existingUserByEmail) {
                        $duplicateEmails[] = "Baris " . ($index + 1) . ": Email " . $row[3] . " sudah terdaftar (" . $existingUserByEmail['nama'] . ")";
                        continue;
                    }

                    // Password logic: gunakan dari excel jika ada, jika tidak gunakan default
                    $password = !empty($row[5]) ? (string)$row[5] : '12345678';

                    // Siapkan data untuk insert
                    $userData = [
                        'nip' => !empty($row[1]) ? $row[1] : null,
                        'nama' => $row[2],
                        'email' => $row[3],
                        'role' => !empty($row[4]) ? strtolower($row[4]) : 'guru',
                        'password' => password_hash($password, PASSWORD_DEFAULT),
                        'is_active' => isset($row[6]) ? (int)$row[6] : 1
                    ];

                    // Simpan ke database
                    try {
                        $this->userModel->insert($userData);
                        $importedCount++;
                    } catch (\Exception $e) {
                        $errors[] = "Baris " . ($index + 1) . ": " . $e->getMessage();
                    }
                }
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                session()->setFlashdata('error', 'Terjadi kesalahan saat menyimpan data ke database. Transaksi dibatalkan.');
                return redirect()->to('/admin/users/import');
            }

            // Prepare success message
            $message = "";
            if ($importedCount > 0) {
                $message .= "Berhasil mengimpor $importedCount data pengguna.";
            }

            // Prepare duplicate warnings
            $warnings = [];
            if (!empty($duplicateNIPs)) {
                $warnings[] = "<strong>NIP Duplikat (dilewati):</strong><br>" . implode('<br>', $duplicateNIPs);
            }

            if (!empty($duplicateEmails)) {
                $warnings[] = "<strong>Email Duplikat (dilewati):</strong><br>" . implode('<br>', $duplicateEmails);
            }

            if (!empty($errors)) {
                $warnings[] = "<strong>Error Lainnya:</strong><br>" . implode('<br>', $errors);
            }

            // Set flash messages
            if (!empty($message)) {
                session()->setFlashdata('success', $message);
            }

            if (!empty($warnings)) {
                session()->setFlashdata('warning', implode('<br><br>', $warnings));
            }

            return redirect()->to('/admin/users');
        }

        session()->setFlashdata('error', 'Gagal mengimpor file.');
        return redirect()->to('/admin/users/import');
    }

    public function downloadTemplate()
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
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'NIP (Opsional)');
        $sheet->setCellValue('C1', 'Nama');
        $sheet->setCellValue('D1', 'Email');
        $sheet->setCellValue('E1', 'Role (guru/admin/super_admin)');
        $sheet->setCellValue('F1', 'Password (Opsional)');
        $sheet->setCellValue('G1', 'Status (1=Aktif, 0=Non-aktif)');

        // Menambahkan contoh data
        $sheet->setCellValue('A2', '1');
        $sheet->setCellValue('B2', '123456789');
        $sheet->setCellValue('C2', 'Budi Santoso');
        $sheet->setCellValue('D2', 'budi@example.com');
        $sheet->setCellValue('E2', 'guru');
        $sheet->setCellValue('F2', 'rahasia123');
        $sheet->setCellValue('G2', '1');

        $sheet->setCellValue('A3', '2');
        $sheet->setCellValue('B3', '');
        $sheet->setCellValue('C3', 'Siti Rahayu');
        $sheet->setCellValue('D3', 'siti@example.com');
        $sheet->setCellValue('E3', 'admin');
        $sheet->setCellValue('F3', '');
        $sheet->setCellValue('G3', '1');

        // Menyiapkan file untuk diunduh
        $filename = 'template-user-' . date('Y-m-d-His') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}
