<?php

namespace App\Controllers;

require_once __DIR__ . '/../models/User.php';

use App\Models\User;

class UserController
{
    public function index()
    {
        // Judul halaman
        $data['judul'] = 'Daftar Pembagian Qurban';

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
}
