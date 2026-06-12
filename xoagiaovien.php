<?php
include("../config/connect.php");

$id = $_GET['id'] ?? '';

if ($id != '') {

    // xóa giáo viên
    $sql = "DELETE FROM giaovien WHERE MaGiaoVien = '$id'";
    mysqli_query($conn, $sql);

    header("Location: giaovien.php");
    exit();
}
?>