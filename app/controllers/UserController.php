<?php

namespace App\Controllers;

require_once __DIR__ . '/../models/User.php';

use App\Models\User;

class UserController
{
    public function index()
    {
        $data['judul'] = 'Daftar Pembagian Qurban';

        // Set jumlah data per halaman dan ambil halaman saat ini dari URL
        $limit = 10;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        // Ambil data pengguna untuk halaman saat ini
        $data['users'] = User::getAllUsers($page, $limit);

        // Ambil total pengguna dan hitung total halaman
        $totalUsers = User::getTotalUsers();
        $data['totalPages'] = ceil($totalUsers / $limit);
        $data['currentPage'] = $page;

        // Ambil data berdasarkan role (Warga, Panitia, Mudhohi)
        $data['warga'] = User::getByRole('warga', $page, $limit);
        $data['panitia'] = User::getByRole('panitia', $page, $limit);
        $data['berqurban'] = User::getByRole('berqurban', $page, $limit);

        return $data;
    }
}
