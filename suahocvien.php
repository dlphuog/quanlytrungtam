<?php
require_once __DIR__ . "/../config/connect.php";

if (!isset($_GET['id'])) {
    header("Location: hocvien.php");
    exit;
}

$id = $_GET['id'];

$sql = "SELECT 
            hv.MaHocVien,
            hv.TrinhDoHienTai,
            hv.MucTieuHocTap,

            tk.MaTaiKhoan,
            tk.Email,
            tk.Username,

            nd.MaNguoiDung,
            nd.HoTen,
            nd.GioiTinh,
            nd.NgaySinh,
            nd.SDT,
            nd.DiaChi

        FROM HocVien hv
        JOIN TaiKhoan tk ON hv.MaTaiKhoan = tk.MaTaiKhoan
        JOIN NguoiDung nd ON tk.MaNguoiDung = nd.MaNguoiDung
        WHERE hv.MaHocVien = ?";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

if (!$row) {
    die("Không tìm thấy học viên!");
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Sửa học viên</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex justify-center items-center min-h-screen">

<div class="w-full max-w-3xl bg-white rounded-2xl shadow-lg p-8">

    <h2 class="text-2xl font-bold text-center text-[#ba1a1a] mb-6">
        Sửa thông tin học viên
    </h2>

    <form method="POST" class="space-y-5">

        <!-- THÔNG TIN CÁ NHÂN -->
        <div>
            <h3 class="font-semibold text-gray-700 mb-2">Thông tin cá nhân</h3>

            <input name="HoTen"
                value="<?= $row['HoTen'] ?>"
                class="w-full border rounded-lg px-4 py-3 mb-3 focus:ring-2 focus:ring-[#ba1a1a]"
                placeholder="Họ tên">

            <select name="GioiTinh"
                class="w-full border rounded-lg px-4 py-3 mb-3 focus:ring-2 focus:ring-[#ba1a1a]">

                <option value="Nam" <?= $row['GioiTinh']=='Nam'?'selected':'' ?>>Nam</option>
                <option value="Nữ" <?= $row['GioiTinh']=='Nữ'?'selected':'' ?>>Nữ</option>
            </select>

            <input type="date" name="NgaySinh"
                value="<?= $row['NgaySinh'] ?>"
                class="w-full border rounded-lg px-4 py-3 mb-3 focus:ring-2 focus:ring-[#ba1a1a]">

            <input name="SDT"
                value="<?= $row['SDT'] ?>"
                class="w-full border rounded-lg px-4 py-3 mb-3 focus:ring-2 focus:ring-[#ba1a1a]"
                placeholder="SĐT">

            <input name="DiaChi"
                value="<?= $row['DiaChi'] ?>"
                class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-[#ba1a1a]"
                placeholder="Địa chỉ">
        </div>

        <!-- TÀI KHOẢN -->
        <div>
            <h3 class="font-semibold text-gray-700 mb-2">Tài khoản</h3>

            <input name="Email"
                value="<?= $row['Email'] ?>"
                class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-[#ba1a1a]"
                placeholder="Email">
        </div>

        <!-- HỌC TẬP -->
        <div>
            <h3 class="font-semibold text-gray-700 mb-2">Học tập</h3>

            <input name="TrinhDoHienTai"
                value="<?= $row['TrinhDoHienTai'] ?>"
                class="w-full border rounded-lg px-4 py-3 mb-3 focus:ring-2 focus:ring-[#ba1a1a]"
                placeholder="Trình độ hiện tại">

            <input name="MucTieuHocTap"
                value="<?= $row['MucTieuHocTap'] ?>"
                class="w-full border rounded-lg px-4 py-3 focus:ring-2 focus:ring-[#ba1a1a]"
                placeholder="Mục tiêu học tập">
        </div>

        <!-- BUTTON -->
        <div class="flex gap-3 pt-2">

            <a href="hocvien.php"
               class="w-1/2 text-center py-3 rounded-lg border hover:bg-gray-100 transition">
                Hủy
            </a>

            <button type="submit"
                name="update"
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