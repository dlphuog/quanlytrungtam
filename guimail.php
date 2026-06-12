<?php

$conn = new mysqli(
    "localhost",
    "root",
    "",
    "quanlytrungtam"
);

if($conn->connect_error){
    die("Lỗi kết nối");
}

$conn->set_charset("utf8");

$maBuoiHoc = $_GET['MaBuoiHoc'];

$sql = "
SELECT
    tk.Email,
    nd.HoTen
FROM diemdanh dd

INNER JOIN hocvien hv
ON dd.MaHocVien = hv.MaHocVien

INNER JOIN taikhoan tk
ON hv.MaTaiKhoan = tk.MaTaiKhoan

INNER JOIN nguoidung nd
ON tk.MaNguoiDung = nd.MaNguoiDung

WHERE dd.MaBuoiHoc='$maBuoiHoc'
AND dd.TrangThai='Vang'
";

$result = $conn->query($sql);

while($row = $result->fetch_assoc()){

    $email = $row['Email'];

    mail(
        $email,
        "Thông báo vắng học",
        "Bạn đã vắng mặt trong buổi học hôm nay."
    );
}

echo "
<script>
alert('Đã gửi email');
location='diemdanh.php?MaBuoiHoc=$maBuoiHoc';
</script>
";