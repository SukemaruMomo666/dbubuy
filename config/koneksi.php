<?php

$DB_HOST = 'localhost';
$DB_USER = 'root'; 
$DB_PASS = '';     
$DB_NAME = 'si_gudang'; 


$koneksi = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

// Cek koneksi
if ($koneksi->connect_error) {
    die("Koneksi Gagal: " . $koneksi->connect_error);
}
$koneksi->set_charset("utf8mb4");

?>