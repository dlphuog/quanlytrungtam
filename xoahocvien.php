<?php

$conn = new mysqli("localhost","root","","quanlytrungtam");

$id = $_GET['id'];

$sql = "DELETE FROM HocVien WHERE MaHocVien='$id'";

$conn->query($sql);

header("Location: hocvien.php");

?>