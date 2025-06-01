<?php


$BASE_URL = dirname(dirname($_SERVER['PHP_SELF']));

function getConnection()
{
    static $conn = null;
    if ($conn === null) {
        $host = 'localhost';
        $username = 'root';
        $password = '';
        $dbname = 'qurban_db';
        $conn = new mysqli($host, $username, $password, $dbname);
        if ($conn->connect_error) {
            error_log("Koneksi database gagal: " . $conn->connect_error);
            die("Terjadi kesalahan saat menghubungkan ke database.");
        }
        $conn->set_charset("utf8mb4");
    }

    return $conn;
}


