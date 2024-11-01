<?php
$konek=new mysqli("localhost","root","","ebook");
if ($konek->connect_error) {
     // jika terjadi error, matikan proses dengan die() atau exit();
    die('Maaf koneksi gagal: '. $konek->connect_error);}
?>