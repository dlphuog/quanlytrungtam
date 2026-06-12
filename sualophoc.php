<?php
require_once __DIR__ . "/../config/connect.php";

if (!isset($_GET['id'])) {
    header("Location: lophoc.php");
    exit;
}

$id = $_GET['id'];

// GET DATA
$sql = "SELECT * FROM LopHoc WHERE MaLopHoc = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

if (!$row) {
    die("Không tìm thấy lớp học!");
}

// UPDATE
if (isset($_POST['update'])) {

    $tenLop = $_POST['TenLop'];
    $trinhDo = $_POST['TrinhDo'];
    $siSo = (int)$_POST['SiSoToiDa'];
    $ngayBD = $_POST['NgayBatDau'];
    $trangThai = $_POST['TrangThai'];
    $maLop = $_POST['MaLopHoc'];

    $sql = "UPDATE LopHoc 
            SET TenLop=?, TrinhDo=?, SiSoToiDa=?, NgayBatDau=?, TrangThai=?
            WHERE MaLopHoc=?";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssisss",
        $tenLop,
        $trinhDo,
        $siSo,
        $ngayBD,
        $trangThai,
        $maLop
    );

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>
            alert('Cập nhật thành công!');
            window.location.href='lophoc.php';
        </script>";
    } else {
        echo "Lỗi: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="https://cdn.tailwindcss.com"></script>
<title>Cập nhật lớp học</title>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">

<div class="w-full max-w-2xl bg-white rounded-2xl shadow-lg p-8">

    <!-- TITLE -->
    <h2 class="text-2xl font-bold text-center text-[#ba1a1a] mb-6">
        Cập nhật lớp học
    </h2>

    <form method="POST" class="space-y-5">

        <input type="hidden" name="MaLopHoc" value="<?= $row['MaLopHoc'] ?>">

        <!-- TÊN LỚP -->
        <div>
            <label class="block text-sm font-semibold text-gray-600 mb-1">Tên lớp</label>
            <input type="text" name="TenLop"
                   value="<?= $row['TenLop'] ?>"
                   class="w-full border rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#ba1a1a]">
        </div>

        <!-- TRÌNH ĐỘ -->
        <div>
            <label class="block text-sm font-semibold text-gray-600 mb-1">Trình độ</label>
            <input type="text" name="TrinhDo"
                   value="<?= $row['TrinhDo'] ?>"
                   class="w-full border rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#ba1a1a]">
        </div>

        <!-- SĨ SỐ -->
        <div>
            <label class="block text-sm font-semibold text-gray-600 mb-1">Sĩ số tối đa</label>
            <input type="number" name="SiSoToiDa"
                   value="<?= $row['SiSoToiDa'] ?>"
                   class="w-full border rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#ba1a1a]">
        </div>

        <!-- NGÀY BẮT ĐẦU -->
        <div>
            <label class="block text-sm font-semibold text-gray-600 mb-1">Ngày bắt đầu</label>
            <input type="date" name="NgayBatDau"
                   value="<?= $row['NgayBatDau'] ?>"
                   class="w-full border rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#ba1a1a]">
        </div>

        <!-- TRẠNG THÁI -->
        <div>
            <label class="block text-sm font-semibold text-gray-600 mb-1">Trạng thái</label>
            <input type="text" name="TrangThai"
                   value="<?= $row['TrangThai'] ?>"
                   class="w-full border rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#ba1a1a]">
        </div>

        <!-- BUTTON -->
        <div class="flex gap-3 pt-2">

            <a href="lophoc.php"
               class="w-1/2 text-center py-3 rounded-lg border hover:bg-gray-100 transition">
                Hủy
            </a>

            <button type="submit" name="update"
                class="w-1/2 text-white py-3 rounded-lg transition"
                style="background-color:#ba1a1a;"
                onmouseover="this.style.backgroundColor='#a31515'"
                onmouseout="this.style.backgroundColor='#ba1a1a'">
                Cập nhật
            </button>

        </div>

    </form>
</div>

</body>
</html>