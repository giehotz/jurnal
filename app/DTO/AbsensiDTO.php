<?php

namespace App\DTO;

class AbsensiDTO
{
    public $tanggal;
    public $rombelId;
    public $mapelId;
    public $jamKe;
    public $materi;
    public $absensiData; // Array of siswa_id => [status, keterangan]

    public function __construct($data)
    {
        $this->tanggal = $data['tanggal'] ?? null;
        $this->rombelId = $data['rombel_id'] ?? null;
        $this->mapelId = $data['mapel_id'] ?? null;
        $this->jamKe = $data['jam_ke'] ?? null;
        $this->materi = $data['materi'] ?? null;
        $this->absensiData = $data['absensi'] ?? [];
    }
}
