<?php
$conn = new mysqli("localhost","root","","quanlytrungtam");

$maBuoiHoc = $_POST['MaBuoiHoc'];

$sql = "UPDATE buoihoc
        SET TrangThaiXacNhan='TuChoi'
        WHERE MaBuoiHoc='$maBuoiHoc'";

if($conn->query($sql)){
    echo "
    <script>
        alert('Đã từ chối lịch dạy!');
        window.location='lichgiangday.php';
    </script>
    ";
}else{
    echo "
    <script>
        alert('Lỗi từ chối lịch!');
        history.back();
    </script>
    ";
}
?>