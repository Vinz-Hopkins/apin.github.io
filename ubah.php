<?php
session_start();

if( !isset($_SESSION["login"]) ){
    header("Location: login.php");
    exit;
}
require 'functions.php';
 
//ambil data di URL
$id = $_GET["id"];

//query data mahsiswa berdasarkan id
$mhs= query("SELECT * FROM mahasiswa WHERE id = $id")[0];
 
 

//cek apakah submit tombol sudah ditekan atau belum
if ( isset($_POST["submit"]) ){
    //ambil data dari setiap elemen dalam form
    
 
    
        //cek apakah data berhasil diubah atau tidak
        if ( ubah ($_POST) > 0 ) {
            echo "
                <script> 
                    alert('data berhasil diubah!');
                    document.location.href = 'index.php';
                </script>
            ";
        }else {
            echo "
                <script> 
                    alert('data gagal diubah!');
                    document.location.href = 'index.php';
                </script>
            ";
        }

}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UbahData Mahasiswa</title>
</head>
<body>
    <h1>Ubah Data Mahasiswa</h1>


    <form action="" method= "post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $mhs["id"]; ?>">
        <input type="hidden" name="gambarlama" value="<?= $mhs["gambar"]; ?>">
        <ul>
            <li>
                <label for="nrp">NRP : </label>
                <input type="text" name="nrp" id="nrp" required  value="<?= $mhs["nrp"]; ?>">
            </li>
            <li>
                <label for="nama">Nama : </label>
                <input type="text" name="nama" id="nama" required value="<?= $mhs["nama"]; ?>">
            </li>
            <li>
                <label for="email">Email : </label>
                <input type="text" name="email" id="email" required value="<?= $mhs["email"]; ?>">
            </li>
            <li>
                <label for="jurusan">Jurusan : </label>
                <input type="text" name="jurusan" id="jurusan" required value="<?= $mhs["jurusan"]; ?>">
            </li>
            <li>
                
                <label for="gambar">Gambar : </label> <br>
                <img src="img/<?= $mhs['gambar']; ?>" width="75"> <br>
                <input type="file" name="gambar" id="gambar">
            </li>
            <li>
                <button type="submit" name="submit">Ubah data!</button>
            </li>
        </ul>

    </form>



    
</body>
</html>