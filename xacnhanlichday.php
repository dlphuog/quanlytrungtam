<?php
$conn = new mysqli("localhost","root","","quanlytrungtam");

$maBuoiHoc = $_POST['MaBuoiHoc'];

$sql = "UPDATE buoihoc
        SET TrangThaiXacNhan='DaXacNhan'
        WHERE MaBuoiHoc='$maBuoiHoc'";

if($conn->query($sql)){
    echo "
    <script>
        alert('Xác nhận lịch thành công!');
        window.location='lichgiangday.php';
    </script>
    ";
}else{
    echo "
    <script>
        alert('Lỗi xác nhận lịch!');
        history.back();
    </script>
    ";
}
?>