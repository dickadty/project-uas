<?php

namespace App\Controllers;

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Qurban.php';
require_once __DIR__ . '/../models/Warga.php';
require_once __DIR__ . '/../models/PembagianQurban.php';

use App\Models\User;
use App\Models\PembagianQurban;
use App\Models\Warga;

class UserController
{
    public function index()
    {
        $limit = 10;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $data['users'] = User::getAllUsers($page, $limit);
        $totalUsers = User::getTotalUsers();
        $data['totalPages'] = ceil($totalUsers / $limit);
        $data['currentPage'] = $page;
        $data['warga'] = User::getByRole('warga', $page, $limit);
        $data['panitia'] = User::getByRole('panitia', $page, $limit);
        $data['berqurban'] = User::getByRole('berqurban', $page, $limit);

        return $data;
    }
    public function create()
    {
        $data['judul'] = 'Tambah User';
        return $data;
    }
  
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nama = $_POST['nama'];
            $nik = $_POST['nik'];
            $no_hp = $_POST['no_hp'];
            $no_rumah = $_POST['no_rumah'];
            $role = $_POST['role'];
            $password = $_POST['password'];
            $jenis_hewan = $_POST['jenis_hewan'] ?? null;

            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $userId = User::createUser($nik, $role, $hashed_password);
            $id_warga = Warga::createWarga($nik, $nama, $no_hp, $no_rumah, $role);
            $id_qurban = null;
            $jumlah_kg = ($role === 'berqurban') ? 6 : 2;
            PembagianQurban::createPembagianQurban($id_warga, $id_qurban, $jumlah_kg);


            header('Location: /dashboard');
            exit;
        }
    }
}
