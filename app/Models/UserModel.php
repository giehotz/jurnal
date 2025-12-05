<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'nip', 
        'nama', 
        'email', 
        'password', 
        'role', 
        'mata_pelajaran', 
        'is_active',
        'profile_picture',
        'banner',
        'tanggal_lahir',
        'alamat',
        'no_telepon'
    ];
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Mengambil semua data pengguna
     *
     * @return array
     */
    public function getUsers()
    {
        return $this->findAll();
    }

    /**
     * Mengambil data pengguna berdasarkan ID
     *
     * @param int $id
     * @return array|null
     */
    public function getUserById($id)
    {
        return $this->find($id);
    }

    /**
     * Mengambil data pengguna berdasarkan email
     *
     * @param string $email
     * @return array|null
     */
    public function getUserByEmail($email)
    {
        return $this->where('email', $email)->first();
    }
    
    /**
     * Mengambil data pengguna berdasarkan NIP
     *
     * @param string $nip
     * @return array|null
     */
    public function getUserByNIP($nip)
    {
        return $this->where('nip', $nip)->first();
    }

    /**
     * Mengambil data pengguna berdasarkan role
     *
     * @param string $role
     * @return array
     */
    public function getUsersByRole($role)
    {
        return $this->where('role', $role)->findAll();
    }
    
    /**
     * Memperbarui data pengguna berdasarkan ID
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function updateUser($id, $data)
    {
        return $this->update($id, $data);
    }
}