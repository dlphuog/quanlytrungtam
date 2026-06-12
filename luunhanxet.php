<?php

$conn = new mysqli("localhost","root","","quanlytrungtam");
$conn->set_charset("utf8");

if(isset($_POST['luuNhanXet'])){

    $maHocVien = $_POST['maHocVien'];
    $maLop     = $_POST['maLop'];
$nhanXet = $_POST['nhanXetTong'];
    $sqlCheck = "
    SELECT MaKQHT
    FROM ketquahoctap
    WHERE MaHocVien='$maHocVien'
    AND MaLopHoc='$maLop'
    LIMIT 1
    ";

    $rs = $conn->query($sqlCheck);

    if($rs->num_rows > 0){

        $row = $rs->fetch_assoc();

        $sqlUpdate = "
        UPDATE ketquahoctap
        SET NhanXet='$nhanXet'
        WHERE MaKQHT='".$row['MaKQHT']."'
        ";

        $ok = $conn->query($sqlUpdate);

    }else{

        $maKQHT = "KQ".time();

        $sqlInsert = "
        INSERT INTO ketquahoctap
        (
            MaKQHT,
            MaHocVien,
            MaLopHoc,
            NhanXet,
            LoaiKiemTra
        )
        VALUES
        (
            '$maKQHT',
            '$maHocVien',
            '$maLop',
            '$nhanXet',
            'Nhan xet'
        )
        ";

        $ok = $conn->query($sqlInsert);
    }

    if($ok){
        header("Location: nhanxetdanhgia.php?lop=$maLop&hv=$maHocVien&success=1");
    }else{
        header("Location: nhanxetdanhgia.php?lop=$maLop&hv=$maHocVien&error=1");
    }

    exit;
}

?>