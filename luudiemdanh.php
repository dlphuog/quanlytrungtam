<?php
$conn = new mysqli("localhost","root","","quanlytrungtam");
$conn->set_charset("utf8");

$maBuoiHoc = $_POST['MaBuoiHoc'];

foreach($_POST['status'] as $maHV => $trangThai){

    $ghiChu = $_POST['ghichu'][$maHV] ?? '';

    $check = $conn->query("
        SELECT *
        FROM diemdanh
        WHERE MaBuoiHoc='$maBuoiHoc'
        AND MaHocVien='$maHV'
    ");

    if($check->num_rows > 0){

        $conn->query("
            UPDATE diemdanh
            SET TrangThai='$trangThai',
                GhiChu='$ghiChu'
            WHERE MaBuoiHoc='$maBuoiHoc'
            AND MaHocVien='$maHV'
        ");

    }else{

        $maDD = uniqid("DD");

        $conn->query("
            INSERT INTO diemdanh
            VALUES(
                '$maDD',
                '$maBuoiHoc',
                '$maHV',
                '$trangThai',
                '$ghiChu'
            )
        ");
    }
}

echo "<script>
alert('Lưu điểm danh thành công!');
window.location='diemdanh.php?MaBuoiHoc=$maBuoiHoc';
</script>";
exit;
?> */