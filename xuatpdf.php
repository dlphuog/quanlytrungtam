<?php

$conn = new mysqli("localhost","root","","quanlytrungtam");
$conn->set_charset("utf8");

$maHocVien = $_GET['hv'];
$maLop = $_GET['lop'];

$sql = "
SELECT
    nd.HoTen,
    hv.MaHocVien,
    kq.NhanXet,
    kq.DiemSo,
    lh.TenLop
FROM hocvien hv
INNER JOIN taikhoan tk
    ON hv.MaTaiKhoan = tk.MaTaiKhoan
INNER JOIN nguoidung nd
    ON tk.MaNguoiDung = nd.MaNguoiDung
LEFT JOIN ketquahoctap kq
    ON hv.MaHocVien = kq.MaHocVien
LEFT JOIN lophoc lh
    ON kq.MaLopHoc = lh.MaLopHoc
WHERE hv.MaHocVien='$maHocVien'
LIMIT 1
";



$row = $conn->query($sql)->fetch_assoc();

$html = "
<!DOCTYPE html>
<html>
<head>
<meta charset='utf-8'>
<style>

body{
    font-family: Arial, sans-serif;
    margin:40px;
    color:#333;
}

.header{
    text-align:center;
    border-bottom:3px solid #b91c1c;
    padding-bottom:15px;
    margin-bottom:25px;
}

.logo{
    color:#b91c1c;
    font-size:28px;
    font-weight:bold;
}

.title{
    font-size:24px;
    font-weight:bold;
    margin-top:10px;
}

.info{
    background:#f8f8f8;
    border:1px solid #ddd;
    border-radius:8px;
    padding:15px;
    margin-bottom:20px;
}

.info p{
    margin:8px 0;
}

.section{
    margin-top:20px;
}

.section-title{
    background:#b91c1c;
    color:white;
    padding:10px;
    font-weight:bold;
}

.content{
    border:1px solid #ddd;
    padding:15px;
    min-height:80px;
}

.footer{
    margin-top:50px;
    text-align:right;
}

</style>
</head>

<body>

<div class='header'>
    <div class='logo'>TRUNG TÂM ATHENA</div>
    <div class='title'>BÁO CÁO ĐÁNH GIÁ HỌC VIÊN</div>
</div>

<div class='info'>
    <p><b>Họ tên:</b> {$row['HoTen']}</p>
    <p><b>Mã học viên:</b> {$row['MaHocVien']}</p>
    <p><b>Lớp:</b> {$row['TenLop']}</p>
    <p><b>Điểm:</b> {$row['DiemSo']}</p>
</div>

<div class='section'>
    <div class='section-title'>NHẬN XÉT GIÁO VIÊN</div>
    <div class='content'>
        ".nl2br($row['NhanXet'])."
    </div>
</div>

<div class='footer'>
    <p>Ngày xuất báo cáo: ".date('d/m/Y')."</p>
    <br><br>
    <b>Giáo viên</b>
</div>

</body>
</html>
";
echo $html;