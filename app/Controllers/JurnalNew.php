<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\JurnalNewModel;
use App\Models\KelasModel;
use App\Models\RombelModel;
use App\Models\MapelModel;

class JurnalNew extends BaseController
{
    protected $jurnalModel;
    protected $kelasModel;
    protected $rombelModel;
    protected $mapelModel;

    public function __construct()
    {
        $this->jurnalModel = new JurnalNewModel();
        $this->kelasModel = new KelasModel();
        $this->rombelModel = new RombelModel();
        $this->mapelModel = new MapelModel();
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Jurnal Mengajar',
            'kelas' => $this->rombelModel->findAll(), // Diubah dari kelasModel
            'mapel' => $this->mapelModel->findAll(),
        ];

        return view('guru/jurnal/create', $data);
    }

    public function store()
    {
        // Menyimpan data jurnal
        $validation = \Config\Services::validation();
        
        $rules = [
            'tanggal' => 'required|valid_date',
            'rombel_id' => 'required|integer', // Diubah dari kelas_id
            'mapel_id' => 'required|integer',
            'jam_ke' => 'required|integer',
            'materi' => 'required|max_length[200]',
            'jumlah_jam' => 'required|integer',
            'jumlah_peserta' => 'required|integer',
        ];

        if (!$this->validate($rules)) {
            $data['validation'] = $this->validator;
            $data['kelas'] = $this->rombelModel->findAll(); // Diubah dari kelasModel
            $data['mapel'] = $this->mapelModel->findAll();
            
            return view('guru/jurnal/create', $data);
        }

        // Menyimpan data
        $data = [
            'user_id' => session()->get('id'), // Mengambil ID user dari session
            'tanggal' => $this->request->getPost('tanggal'),
            'rombel_id' => $this->request->getPost('rombel_id'), // Diubah dari kelas_id
            'mapel_id' => $this->request->getPost('mapel_id'),
            'jam_ke' => $this->request->getPost('jam_ke'),
            'materi' => $this->request->getPost('materi'),
            'jumlah_jam' => $this->request->getPost('jumlah_jam'),
            'bukti_dukung' => $this->request->getPost('bukti_dukung'),
            'jumlah_peserta' => $this->request->getPost('jumlah_peserta'),
            'keterangan' => $this->request->getPost('keterangan'),
            'status' => $this->request->getPost('status') ?? 'draft',
        ];

        $this->jurnalModel->save($data);

        return redirect()->to('/guru/dashboard')->with('success', 'Data jurnal berhasil disimpan');
    }
}