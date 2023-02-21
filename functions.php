<?php
//koneksi ke database
$conn = mysqli_connect("localhost:8111","root", "","phpdasar");

 
function query($query) {
    global $conn;
    $result = mysqli_query($conn, $query);
    $rows = [];
    while( $row = mysqli_fetch_assoc($result) ){
        $rows[] = $row;
    }
    return $rows;
} 

 function tambah($data) {
    global $conn;

    $nrp = htmlspecialchars($data["nrp"]);
    $nama = htmlspecialchars($data["nama"]);
    $email = htmlspecialchars($data["email"]);
    $jurusan= htmlspecialchars($data["jurusan"]);

    //upload gambar
    $gambar = upload();
    if( !$gambar ) {
        return false;
    }



    $query = "INSERT INTO mahasiswa VALUES ('', '$nrp', '$nama', '$email', '$jurusan', '$gambar')";

        mysqli_query($conn, $query);

        return mysqli_affected_rows ($conn);

 }


function upload(){

    $namafile = $_FILES['gambar']['name'];
    $ukuranfile = $_FILES['gambar']['size'];
    $error = $_FILES['gambar']['error'];
    $tmpname = $_FILES['gambar']['tmp_name'];

    //cek apakah tidak ada gambar yg di upload
    if( $error === 4 ){
        echo "<script>
                alert('pilih gambar terlebih dahulu!');
            </script>";
        return false;

    }

    //cek apakah yg diupload adalah gambar
    $esktensigambarvalid = ['jpg', 'jpeg', 'png','webp'];
    $ekstensigambar = explode('.', $namafile);
    $ekstensigambar = strtolower(end($ekstensigambar));
    if( !in_array($ekstensigambar, $esktensigambarvalid) ) {
            echo "<script>
                    alert('Yg anda upload bukan gambar!');
                </script>";
            return false;
    }

    //cek jika ukurannya terlalu besar 
    if($ukuranfile > 1000000 ) {
        echo "<script>
                alert('Gambar yang anda upload terlalu besar!');
            </script>";
        return false;
    }

    //lolos pengecekan, gambar siap diupload
    //generate nama gambar baru
    $namafilebaru = uniqid();
    $namafilebaru .= '.';
    $namafilebaru .= $ekstensigambar;


    move_uploaded_file($tmpname, 'img/' . $namafilebaru);

    return $namafilebaru;

}




function hapus($id) {
    global $conn;
    mysqli_query($conn, "DELETE FROM mahasiswa WHERE id = $id");

    return mysqli_affected_rows($conn);
}


function ubah($data) {
    global $conn;

    $id = $data["id"];
    $nrp = htmlspecialchars($data["nrp"]);
    $nama = htmlspecialchars($data["nama"]);
    $email = htmlspecialchars($data["email"]);
    $jurusan = htmlspecialchars($data["jurusan"]);
    $gambarlama = htmlspecialchars($data["gambarlama"]);

    //cek apakah user pilih gambar baru atau tidak
    if($_FILES['gambar']['error'] === 4 ) {
        $gambar = $gambarlama;
    } else {
        $gambar = upload();
    }
    
    $query = "UPDATE mahasiswa SET 
                nrp = '$nrp', 
                nama = '$nama', 
                email = '$email', 
                jurusan = '$jurusan',
                gambar = '$gambar'
                WHERE id = $id 
                ";

        mysqli_query($conn, $query);

        return mysqli_affected_rows ($conn);

 }

function cari($keyword) {
    $query = "SELECT * FROM mahasiswa WHERE nama LIKE '%$keyword%' OR nrp LIKE '%$keyword%' OR email LIKE '%$keyword%' OR jurusan LIKE '%$keyword%' ";

    return query($query);
}


function registrasi($data) {
    global $conn;

    $username = strtolower(stripslashes($data["username"]));
    $password = mysqli_real_escape_string($conn, $data["password"]);
    $password2 = mysqli_real_escape_string($conn, $data["password2"]);


//cek usernamae sudah ada atau belum
$result = mysqli_query($conn, "SELECT username FROM user WHERE username = '$username'");
    
    if( mysqli_fetch_assoc($result) ){
        echo 
            "<script>
                alert('USERNAME SUDAH ADA DALAM DAFTAR SILAKAN COBA USERNAME YANG LAIN!');
            </script>";
            return false;
        }


//cek kofirmasi password
if( $password != $password2 ) {
    echo 
        "<script>
            alert('PASSWORD TIDAK SESUAI!');
        </script>";
        return false;
    } 

    //enskripsi password
    $password = password_hash($password, PASSWORD_DEFAULT);

//tambahkan userbaru kedata base
mysqli_query($conn, "INSERT INTO user VALUES('', '$username', '$password')");

return mysqli_affected_rows($conn);


}

?>

