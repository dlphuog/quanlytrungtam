<?php

$conn = new mysqli("localhost","root","","quanlytrungtam");
$conn->set_charset("utf8");

$maBuoiHoc = $_GET['MaBuoiHoc'];

$sql = "
SELECT
    nd.HoTen,
    dd.TrangThai,
    dd.GhiChu
FROM diemdanh dd
INNER JOIN hocvien hv
    ON dd.MaHocVien = hv.MaHocVien
INNER JOIN taikhoan tk
    ON hv.MaTaiKhoan = tk.MaTaiKhoan
INNER JOIN nguoidung nd
    ON tk.MaNguoiDung = nd.MaNguoiDung
WHERE dd.MaBuoiHoc='$maBuoiHoc'
";

$result = $conn->query($sql);

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=diemdanh_$maBuoiHoc.xls");

echo "
<table border='1'>
<tr>
    <th>STT</th>
    <th>Họ tên</th>
    <th>Trạng thái</th>
    <th>Ghi chú</th>
</tr>
";

$stt = 1;

while($row = $result->fetch_assoc()){

    echo "
    <tr>
        <td>".$stt++."</td>
        <td>".$row['HoTen']."</td>
        <td>".$row['TrangThai']."</td>
        <td>".$row['GhiChu']."</td>
    </tr>
    ";
}

echo "</table>";