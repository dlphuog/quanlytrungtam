<?php
include("../config/connect.php");

$id = $_GET['id'] ?? '';

$sql = "SELECT * FROM giaovien WHERE MaGiaoVien='$id'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

if (!$row) {
    die("Không tìm thấy giáo viên");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $chuyenmon = $_POST['ChuyenMon'];
    $chungchi = $_POST['ChungChi'];

    $sql = "UPDATE giaovien 
            SET ChuyenMon='$chuyenmon', ChungChi='$chungchi'
            WHERE MaGiaoVien='$id'";

    mysqli_query($conn, $sql);

    header("Location: giaovien.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<script src="https://cdn.tailwindcss.com"></script>

<title>Cập nhật giáo viên</title>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">

<div class="w-full max-w-xl bg-white rounded-2xl shadow-lg p-8">

    <!-- HEADER -->
    <h2 class="text-2xl font-bold text-[#ba1a1a] mb-6 text-center">
        Cập nhật thông tin giáo viên
    </h2>

    <form method="POST" class="space-y-5">

        <!-- CHUYÊN MÔN -->
        <div>
            <label class="block text-sm font-semibold text-gray-600 mb-1">
                Chuyên môn
            </label>
            <input 
                name="ChuyenMon"
                value="<?= $row['ChuyenMon'] ?>"
                class="w-full border rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#ba1a1a]"
                placeholder="Ví dụ: Toán, Lý, IELTS..."
            >
        </div>

        <!-- CHỨNG CHỈ -->
        <div>
            <label class="block text-sm font-semibold text-gray-600 mb-1">
                Chứng chỉ
            </label>
            <input 
                name="ChungChi"
                value="<?= $row['ChungChi'] ?>"
                class="w-full border rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#ba1a1a]"
                placeholder="Ví dụ: TESOL, IELTS 8.0..."
            >
        </div>

        <!-- BUTTON -->
        <div class="flex gap-3 pt-3">

            <a href="giaovien.php"
               class="w-1/2 text-center py-3 rounded-lg border border-gray-300 hover:bg-gray-100 transition">
                Hủy
            </a>

            <button type="submit"
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