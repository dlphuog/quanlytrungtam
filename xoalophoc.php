<?php
include("../config/connect.php");

if (isset($_GET['id'])) {
    $maLop = $_GET['id'];

    // Xóa lớp học
    $sql = "DELETE FROM LopHoc WHERE MaLopHoc = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $maLop);

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>
            alert('Xóa lớp học thành công!');
            window.location.href='lophoc.php';
        </script>";
    } else {
        echo "Lỗi khi xóa: " . mysqli_error($conn);
    }
} else {
    header("Location: lophoc.php");
}
?>
