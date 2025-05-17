<?php
$BASE_URL = dirname(dirname($_SERVER['PHP_SELF']));

$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'db_qurban';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}
?>
