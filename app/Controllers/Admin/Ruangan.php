<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\RuanganModel;

class Ruangan extends BaseController
{
    protected $ruanganModel;

    public function __construct()
    {
        $this->ruanganModel = new RuanganModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Manajemen Ruangan',
            'active' => 'ruangan',
            'ruangan' => $this->ruanganModel->findAll()
        ];

        return view('admin/ruangan/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Ruangan',
            'active' => 'ruangan'
        ];

        return view('admin/ruangan/create', $data);
    }

    public function store()
    {
        if (!$this->validate([
            'nama_ruangan' => 'required|min_length[3]|max_length[100]|is_unique[ruangan.nama_ruangan]',
            'kapasitas' => 'required|integer|greater_than[0]',
            'jenis' => 'required'
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        if (!$this->ruanganModel->save([
            'nama_ruangan' => $this->request->getPost('nama_ruangan'),
            'kapasitas' => $this->request->getPost('kapasitas'),
            'jenis' => $this->request->getPost('jenis'),
            'keterangan' => $this->request->getPost('keterangan'),
        ])) {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data ruangan.');
        }

        return redirect()->to('/admin/ruangan')->with('success', 'Ruangan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $ruangan = $this->ruanganModel->find($id);

        if (!$ruangan) {
            return redirect()->to('/admin/ruangan')->with('error', 'Ruangan tidak ditemukan.');
        }

        $data = [
            'title' => 'Edit Ruangan',
            'active' => 'ruangan',
            'ruangan' => $ruangan
        ];

        return view('admin/ruangan/edit', $data);
    }

    public function update($id)
    {
        if (!$this->validate([
            'nama_ruangan' => 'required|min_length[3]|max_length[100]|is_unique[ruangan.nama_ruangan,id,' . $id . ']',
            'kapasitas' => 'required|integer|greater_than[0]',
            'jenis' => 'required'
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->ruanganModel->update($id, [
            'nama_ruangan' => $this->request->getPost('nama_ruangan'),
            'kapasitas' => $this->request->getPost('kapasitas'),
            'jenis' => $this->request->getPost('jenis'),
            'keterangan' => $this->request->getPost('keterangan'),
        ]);

        return redirect()->to('/admin/ruangan')->with('success', 'Ruangan berhasil diperbarui.');
    }

    public function delete($id)
    {
        // Check if ruangan exists
        $ruangan = $this->ruanganModel->find($id);
        if (!$ruangan) {
            return redirect()->to('/admin/ruangan')->with('error', 'Ruangan tidak ditemukan.');
        }

        // Update related rombel records to set ruangan_id to null
        $rombelModel = new \App\Models\RombelModel();
        $rombelModel->where('ruangan_id', $id)->set(['ruangan_id' => null])->update();

        // Delete the ruangan
        $this->ruanganModel->delete($id);
        return redirect()->to('/admin/ruangan')->with('success', 'Ruangan berhasil dihapus.');
    }
}
