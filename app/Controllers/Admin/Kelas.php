<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\KelasModel;
use App\Models\UserModel;

class Kelas extends BaseController
{
    protected $kelasModel;
    protected $userModel;

    public function __construct()
    {
        $this->kelasModel = new KelasModel();
        $this->userModel = new UserModel();
    }

    public function index()
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        // Ambil data kelas untuk ditampilkan
        $classes = $this->kelasModel->getKelas();
        
        // Dapatkan koneksi database
        $db = \Config\Database::connect();
        
        // Tambahkan informasi wali kelas dari tabel rombel
        foreach ($classes as &$class) {
            // Dapatkan rombel yang terkait dengan kelas ini
            $rombel = $db->table('rombel')
                ->where('tingkat', $class['tingkat'])
                ->get()
                ->getResultArray();
            
            if (!empty($rombel) && isset($rombel[0]['wali_kelas']) && $rombel[0]['wali_kelas']) {
                $waliKelas = $this->userModel->find($rombel[0]['wali_kelas']);
                if ($waliKelas) {
                    $class['wali_kelas_nama'] = $waliKelas['nama'];
                } else {
                    $class['wali_kelas_nama'] = 'Tidak diketahui';
                }
            } else {
                $class['wali_kelas_nama'] = 'Belum ditentukan';
            }
        }

        $data = [
            'title' => 'Manajemen Kelas',
            'active' => 'kelas',
            'classes' => $classes
        ];

        return view('admin/kelas/index', $data);
    }

    public function create()
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        $data = [
            'title' => 'Tambah Kelas',
            'active' => 'kelas',
            'teachers' => $this->userModel->where('role', 'guru')->findAll()
        ];

        return view('admin/kelas/create', $data);
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
            'kode_kelas' => 'required|is_unique[kelas.kode_kelas]',
            'nama_kelas' => 'required',
            'tingkat' => 'required|integer|greater_than[0]|less_than_equal_to[12]'
        ];

        if (!$this->validate($validationRules)) {
            session()->setFlashdata('error', 'Gagal menambahkan kelas. Silakan periksa kembali data yang dimasukkan.');
            return redirect()->back()->withInput();
        }

        // Simpan data kelas
        $data = [
            'kode_kelas' => $this->request->getPost('kode_kelas'),
            'nama_kelas' => $this->request->getPost('nama_kelas'),
            'tingkat' => $this->request->getPost('tingkat')
        ];

        if ($this->kelasModel->save($data)) {
            session()->setFlashdata('success', 'Kelas berhasil ditambahkan.');
        } else {
            session()->setFlashdata('error', 'Gagal menambahkan kelas.');
        }

        return redirect()->to('/admin/kelas');
    }

    public function view($id)
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        // Ambil data kelas berdasarkan ID
        $class = $this->kelasModel->getKelasById($id);

        if (!$class) {
            session()->setFlashdata('error', 'Kelas tidak ditemukan!');
            return redirect()->to('/admin/kelas');
        }

        // Tambahkan informasi wali kelas
        $db = \Config\Database::connect();
        $rombel = $db->table('rombel')
            ->where('tingkat', $class['tingkat'])
            ->get()
            ->getResultArray();
        
        if (!empty($rombel) && isset($rombel[0]['wali_kelas']) && $rombel[0]['wali_kelas']) {
            $waliKelas = $this->userModel->find($rombel[0]['wali_kelas']);
            if ($waliKelas) {
                $class['wali_kelas_nama'] = $waliKelas['nama'];
            } else {
                $class['wali_kelas_nama'] = 'Tidak diketahui';
            }
        } else {
            $class['wali_kelas_nama'] = 'Belum ditentukan';
        }

        $data = [
            'title' => 'Detail Kelas',
            'active' => 'kelas',
            'class' => $class
        ];

        return view('admin/kelas/view', $data);
    }

    public function edit($id)
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        // Ambil data kelas berdasarkan ID
        $class = $this->kelasModel->getKelasById($id);

        if (!$class) {
            session()->setFlashdata('error', 'Kelas tidak ditemukan!');
            return redirect()->to('/admin/kelas');
        }

        $data = [
            'title' => 'Edit Kelas',
            'active' => 'kelas',
            'class' => $class,
            'teachers' => $this->userModel->where('role', 'guru')->findAll()
        ];

        return view('admin/kelas/edit', $data);
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
            'kode_kelas' => 'required|is_unique[kelas.kode_kelas,id,' . $id . ']',
            'nama_kelas' => 'required',
            'tingkat' => 'required|in_list[1,2,3,4,5,6,7,8,9,10,11,12]',
        ];

        if (!$this->validate($validationRules)) {
            session()->setFlashdata('error', 'Gagal mengupdate kelas. Silakan periksa kembali data yang dimasukkan.');
            return redirect()->back()->withInput();
        }

        // Update data kelas
        $data = [
            'kode_kelas' => $this->request->getPost('kode_kelas'),
            'nama_kelas' => $this->request->getPost('nama_kelas'),
            'tingkat' => $this->request->getPost('tingkat')
        ];

        if ($this->kelasModel->update($id, $data)) {
            session()->setFlashdata('success', 'Kelas berhasil diupdate.');
        } else {
            session()->setFlashdata('error', 'Gagal mengupdate kelas.');
        }

        return redirect()->to('/admin/kelas');
    }

    public function delete($id)
    {
        // Cek jika user sudah login dan memiliki role admin/super_admin
        $role = session()->get('role');
        if (!session()->get('logged_in') || ($role !== 'admin' && $role !== 'super_admin')) {
            return redirect()->to('/auth/login');
        }

        // Hapus data kelas
        if ($this->kelasModel->delete($id)) {
            session()->setFlashdata('success', 'Kelas berhasil dihapus.');
        } else {
            session()->setFlashdata('error', 'Gagal menghapus kelas.');
        }

        return redirect()->to('/admin/kelas');
    }
}