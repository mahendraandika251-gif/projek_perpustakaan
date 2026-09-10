<!-- fungsinya untuk menghubungkan data ke datasabe -->
<?php

class Koneksi {
    private $host = "localhost",
            $username = "root",
            $pass = "",
            $db = "perpustakaan";

    public $koneksi;

    function __construct() {
        $this->koneksi = mysqli_connect(
            $this->host,
            $this->username,
            $this->pass,
            $this->db
        );

        // echo "koneksi ke database berhasil";
        
        
        if (!$this->koneksi) {
            echo "Koneksi ke database gagal";
        }
    }
}


$koneksi = new Koneksi();


$conn = $koneksi->koneksi;
