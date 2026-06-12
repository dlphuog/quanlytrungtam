<?php
session_start();
$conn = new mysqli("localhost", "root", "", "quanlytrungtam");


if (!isset($_SESSION['MaTaiKhoan'])) {
    header("Location: login.php");
    exit();
}


$maTaiKhoan = $_SESSION['MaTaiKhoan'];
$maLopHoc = $_GET['malop'] ?? '';


// --- PHẦN XỬ LÝ DỮ LIỆU ĐẦU TRANG ---


// 1. Lấy Họ Tên
$sql_user = "SELECT HoTen FROM NguoiDung JOIN TaiKhoan ON NguoiDung.MaNguoiDung = TaiKhoan.MaNguoiDung WHERE TaiKhoan.MaTaiKhoan = '$maTaiKhoan'";
$result_user = mysqli_query($conn, $sql_user);
$hoTen = ($row = mysqli_fetch_assoc($result_user)) ? $row['HoTen'] : 'Học viên';


// 2. Lấy thông báo
$sql_tb = "SELECT TieuDe, NoiDung FROM ThongBao JOIN ChiTietThongBao ON ThongBao.MaThongBao = ChiTietThongBao.MaThongBao WHERE ChiTietThongBao.MaTaiKhoan = '$maTaiKhoan' ORDER BY ChiTietThongBao.ThoiGianGui DESC";
$result_tb = mysqli_query($conn, $sql_tb);


// 3. Lấy danh sách lớp
$sql_lop = "SELECT L.MaLopHoc, L.TenLop FROM LopHoc L JOIN ChiTietLopHoc CTLH ON L.MaLopHoc = CTLH.MaLopHoc JOIN HocVien HV ON CTLH.MaHocVien = HV.MaHocVien JOIN TaiKhoan TK ON HV.MaTaiKhoan = TK.MaTaiKhoan WHERE TK.MaTaiKhoan = '$maTaiKhoan'";
$result_lop = mysqli_query($conn, $sql_lop);


// Mặc định lớp đầu tiên
if (empty($maLopHoc) && mysqli_num_rows($result_lop) > 0) {
    mysqli_data_seek($result_lop, 0);
    $firstLop = mysqli_fetch_assoc($result_lop);
    $maLopHoc = $firstLop['MaLopHoc'];
}


// 4. Lấy MaHocVien
$res_hv = mysqli_query($conn, "SELECT MaHocVien FROM HocVien WHERE MaTaiKhoan = '$maTaiKhoan'");
$row_hv = mysqli_fetch_assoc($res_hv);
$maHocVien = $row_hv['MaHocVien'] ?? 0;


// 5. Truy vấn điểm số & Điểm danh
$diem_data = [];
$thongke = ['Co mat' => 0, 'Vang' => 0];
$phanTram = 0;


if ($maHocVien) {
    $res_diem = mysqli_query($conn, "SELECT DiemSo, LoaiKiemTra, NhanXet FROM KetQuaHocTap WHERE MaHocVien = '$maHocVien' AND MaLopHoc = '$maLopHoc'");
    while($row = mysqli_fetch_assoc($res_diem)) { $diem_data[] = $row; }


    $res_dd = mysqli_query($conn, "SELECT TrangThai, COUNT(*) as SoLuong FROM DiemDanh DD JOIN BuoiHoc BH ON DD.MaBuoiHoc = BH.MaBuoiHoc WHERE DD.MaHocVien = '$maHocVien' AND BH.MaLopHoc = '$maLopHoc' GROUP BY TrangThai");
    while($row = mysqli_fetch_assoc($res_dd)) { $thongke[$row['TrangThai']] = $row['SoLuong']; }
    $tong = $thongke['Co mat'] + $thongke['Vang'];
    $phanTram = ($tong > 0) ? round(($thongke['Co mat'] / $tong) * 100) : 0;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <title>Trung tâm Anh ngữ Athena</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
    <style>body { font-family: 'Montserrat', sans-serif; background-color: #f8f9fa; }</style>
</head>
<body>
<header class="flex justify-between items-center h-20 px-8 bg-white border-b shadow-sm">
    <div class="flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl overflow-hidden bg-primary"><img src="/quanlytrungtam/logo.jpg" class="w-full h-full object-cover"></div>
        <div>
            <h1 class="text-xl font-extrabold text-primary">Trung tâm Anh ngữ Athena</h1>
            <p class="text-[12px] text-secondary uppercase font-semibold">English Learning Center</p>
        </div>
    </div>
    <div class="flex items-center gap-6">
        <button id="bell-icon" class="relative p-2 rounded-full hover:bg-gray-100">
            <span class="material-symbols-outlined">notifications</span>
            <?php if (mysqli_num_rows($result_tb) > 0) { ?><span class="absolute top-2 right-2 w-2 h-2 bg-red-600 rounded-full"></span><?php } ?>
        </button>
        <!-- Nút đăng xuất -->
        <a href="../backend/logout.php"
            title="Đăng xuất"
            class="p-2 rounded-full hover:bg-black-100 text-gray-700 hover:text-black transition">
            <span class="material-symbols-outlined">logout</span>
        </a>
        <div id="notification-box" style="display:none;" class="absolute right-10 top-20 bg-white shadow-lg border rounded-lg w-64 p-4 z-50">
            <?php while($tb = mysqli_fetch_assoc($result_tb)) { echo "<p class='text-sm font-bold text-primary'>{$tb['TieuDe']}</p><p class='text-xs mb-2'>{$tb['NoiDung']}</p>"; } ?>
        </div>
        <div class="flex items-center gap-2">
            <div class="w-10 h-10 rounded-full bg-primary flex items-center justify-center text-white"><span class="material-symbols-outlined">school</span></div>
            <div><p class="font-bold"><?php echo htmlspecialchars($hoTen); ?></p><p class="text-[12px] text-primary">English Student</p></div>
        </div>
    </div>
</header>


<main class="p-8 max-w-6xl mx-auto space-y-8">
    <div class="bg-white p-6 rounded-xl shadow-sm border">
        <label class="block font-bold mb-2">Lựa chọn Lớp học</label>
        <select onchange="window.location.href='?malop='+this.value" class="w-full border rounded-lg p-3">
            <?php
            mysqli_data_seek($result_lop, 0);
            while($row = mysqli_fetch_assoc($result_lop)) {
                echo "<option value='{$row['MaLopHoc']}' ".($row['MaLopHoc']==$maLopHoc?'selected':'').">".htmlspecialchars($row['TenLop'])."</option>";
            } ?>
        </select>
    </div>


    <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
        <div class="md:col-span-8 bg-white p-8 rounded-xl shadow-sm border">
            <h3 class="font-bold text-xl mb-6">Kết quả học tập</h3>
            <?php foreach ($diem_data as $diem) { ?>
                <div class="mb-6">
                    <div class="flex justify-between mb-2"><span><?php echo htmlspecialchars($diem['LoaiKiemTra']); ?></span><span class="font-bold text-primary"><?php echo $diem['DiemSo']; ?> / 10.0</span></div>
                    <div class="h-3 w-full bg-gray-200 rounded-full"><div class="h-full bg-red-600 rounded-full" style="width: <?php echo ($diem['DiemSo'] * 10); ?>%;"></div></div>
                </div>
            <?php } ?>
        </div>


        <div class="md:col-span-4 bg-white p-8 rounded-xl shadow-sm border text-center">
            <h3 class="font-bold text-xl mb-6">Điểm danh</h3>
            <div class="relative w-48 h-48 mx-auto mb-6">
                <svg viewBox="0 0 36 36"><path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="#eee" stroke-width="3"></path><path stroke-dasharray="<?php echo $phanTram; ?>, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="#bb0025" stroke-width="3"></path></svg>
                <div class="absolute inset-0 flex items-center justify-center font-bold text-3xl"><?php echo $phanTram; ?>%</div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div><p class="text-sm">Có mặt</p><p class="text-xl font-bold text-green-600"><?php echo $thongke['Co mat']; ?></p></div>
                <div><p class="text-sm">Vắng</p><p class="text-xl font-bold text-red-600"><?php echo $thongke['Vang']; ?></p></div>
            </div>
        </div>
    </div>
</main>


<script>
    document.getElementById('bell-icon').onclick = () => document.getElementById('notification-box').style.display = (document.getElementById('notification-box').style.display === 'none') ? 'block' : 'none';
</script>
</body>
</html>



