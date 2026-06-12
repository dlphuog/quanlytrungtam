<?php
session_start();

if(!isset($_SESSION['MaTaiKhoan'])){
    header("Location: ../login.php");
    exit();
}

if($_SESSION['role'] != 'GV'){
    header("Location: ../login.php");
    exit();
}

$conn = new mysqli("localhost","root","","quanlytrungtam");

if($conn->connect_error){
    die("Lỗi kết nối");
}

$conn->set_charset("utf8");

$maTaiKhoan = $_SESSION['MaTaiKhoan'];

$maLop = !empty($_GET['lop'])
    ? $_GET['lop']
    : 'LH001';

/* Danh sách lớp */
$sqlLop = "
SELECT *
FROM lophoc
ORDER BY MaLopHoc
";

$resultLop = $conn->query($sqlLop);

/* Lấy tên lớp */
$sqlTenLop = "
SELECT TenLop
FROM lophoc
WHERE MaLopHoc='$maLop'
";

$resultTenLop = $conn->query($sqlTenLop);

if($resultTenLop && $resultTenLop->num_rows > 0){
    $tenlop = $resultTenLop->fetch_assoc();
}else{
    $tenlop = [
        'TenLop' => 'Không tìm thấy lớp'
    ];
}

/* Lấy bảng điểm */
$sqlDiem = "
SELECT
    hv.MaHocVien,
    nd.HoTen,

    MAX(
        CASE
            WHEN kq.LoaiKiemTra='Bai tap'
            THEN kq.DiemSo
        END
    ) AS BaiTap,

    MAX(
        CASE
            WHEN kq.LoaiKiemTra='Giua ky'
            THEN kq.DiemSo
        END
    ) AS GiuaKy,

    MAX(
        CASE
            WHEN kq.LoaiKiemTra='Cuoi ky'
            THEN kq.DiemSo
        END
    ) AS CuoiKy

FROM chitietlophoc ctl

INNER JOIN hocvien hv
    ON ctl.MaHocVien = hv.MaHocVien

INNER JOIN taikhoan tk
    ON hv.MaTaiKhoan = tk.MaTaiKhoan

INNER JOIN nguoidung nd
    ON tk.MaNguoiDung = nd.MaNguoiDung

LEFT JOIN ketquahoctap kq
    ON hv.MaHocVien = kq.MaHocVien
    AND ctl.MaLopHoc = kq.MaLopHoc

WHERE ctl.MaLopHoc='$maLop'

GROUP BY
    hv.MaHocVien,
    nd.HoTen

ORDER BY hv.MaHocVien
";

$resultDiem = $conn->query($sqlDiem);

$sqlGV = "
SELECT nd.HoTen
FROM giaovien gv
INNER JOIN taikhoan tk
    ON gv.MaTaiKhoan = tk.MaTaiKhoan
INNER JOIN nguoidung nd
    ON tk.MaNguoiDung = nd.MaNguoiDung
WHERE gv.MaTaiKhoan='$maTaiKhoan'
";

$giaovien = $conn->query($sqlGV)->fetch_assoc();

?>
<!DOCTYPE html>

<html class="light" lang="vi"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Athena Teacher - Quản lý điểm số</title>
 <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "on-surface-variant": "#5d3f3c",
                        "secondary-fixed": "#d8e3fb",
                        "on-background": "#191c1d",
                        "tertiary": "#0052d1",
                        "on-primary-fixed": "#410003",
                        "on-tertiary-container": "#c9d8ff",
                        "surface-tint": "#bf0026",
                        "outline-variant": "#e6bdb8",
                        "inverse-primary": "#ffb4ac",
                        "tertiary-fixed": "#d8e2ff",
                        "surface": "#f8f9fa",
                        "surface-container-high": "#e7e8e9",
                        "on-primary-container": "#ffcdc7",
                        "outline": "#916f6b",
                        "background": "#f8f9fa",
                        "surface-container": "#edeeef",
                        "on-secondary": "#ffffff",
                        "tertiary-fixed-dim": "#adc6ff",
                        "on-tertiary-fixed-variant": "#004395",
                        "on-tertiary": "#ffffff",
                        "on-primary": "#ffffff",
                        "secondary-container": "#d5e0f8",
                        "on-error-container": "#93000a",
                        "secondary-fixed-dim": "#bcc7de",
                        "on-secondary-container": "#586377",
                        "error": "#ba1a1a",
                        "surface-dim": "#d9dadb",
                        "secondary": "#545f73",
                        "inverse-surface": "#2e3132",
                        "surface-bright": "#f8f9fa",
                        "inverse-on-surface": "#f0f1f2",
                        "surface-variant": "#e1e3e4",
                        "on-secondary-fixed": "#111c2d",
                        "on-primary-fixed-variant": "#93000e",
                        "surface-container-lowest": "#ffffff",
                        "on-tertiary-fixed": "#001a42",
                        "on-surface": "#191c1d",
                        "tertiary-container": "#005ac2",
                        "primary-fixed-dim": "#ffb4ac",
                        "on-secondary-fixed-variant": "#3c475a",
                        "on-error": "#ffffff",
                        "primary-fixed": "#ffdad6",
                        "surface-container-low": "#f3f4f5",
                        "primary": "#bb0025",
                        "surface-container-highest": "#e1e3e4",
                        "error-container": "#ffdad6",
                        "primary-container": "#e90031"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    "spacing": {
                        "gutter": "20px",
                        "unit-md": "16px",
                        "unit-xl": "32px",
                        "sidebar-width": "260px",
                        "unit-lg": "24px",
                        "unit-xs": "4px",
                        "unit-sm": "8px",
                        "card-padding": "24px",
                        "container-margin": "24px"
                    },
                    "fontFamily": {
                        "body-sm": ["Montserrat"],
                        "headline-md-mobile": ["Montserrat"],
                        "label-md": ["Montserrat"],
                        "body-md": ["Montserrat"],
                        "headline-sm": ["Montserrat"],
                        "label-sm": ["Montserrat"],
                        "headline-md": ["Montserrat"],
                        "display-lg": ["Montserrat"],
                        "body-lg": ["Montserrat"]
                    },
                    "fontSize": {
                        "body-sm": ["14px", {"lineHeight": "20px", "fontWeight": "400"}],
                        "headline-md-mobile": ["20px", {"lineHeight": "28px", "fontWeight": "600"}],
                        "label-md": ["14px", {"lineHeight": "20px", "fontWeight": "600"}],
                        "body-md": ["16px", {"lineHeight": "24px", "fontWeight": "400"}],
                        "headline-sm": ["20px", {"lineHeight": "28px", "fontWeight": "600"}],
                        "label-sm": ["12px", {"lineHeight": "16px", "fontWeight": "500"}],
                        "headline-md": ["24px", {"lineHeight": "32px", "fontWeight": "600"}],
                        "display-lg": ["48px", {"lineHeight": "56px", "letterSpacing": "-0.02em", "fontWeight": "700"}],
                        "body-lg": ["18px", {"lineHeight": "28px", "fontWeight": "400"}]
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Montserrat', sans-serif; background-color: #f8f9fa; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        .chart-bar-grow { transition: height 1s ease-out; }
        .sidebar-active { background-color: #ffdad6; color: #93000e; font-weight: 700; border-radius: 0.5rem; }
    </style>
</head>
<body class="text-on-surface">
<?php if(isset($_GET['success'])){ ?>
<div
    class="fixed top-20 right-5 bg-green-600 text-white px-5 py-3 rounded-lg shadow-lg z-50"
    id="successMsg">

    ✓ Lưu bảng điểm thành công

</div>

<script>
setTimeout(function(){
    document.getElementById('successMsg').style.display='none';
},3000);
</script>
<?php } ?>
<!-- Fixed SideNavBar -->
<aside class="fixed left-0 top-0 h-full w-sidebar-width bg-surface-container-lowest shadow-sm flex flex-col p-unit-md border-r border-outline-variant z-50">
<div class="px-gutter pt-8 pb-4 flex flex-col items-center">          
    <img
src="/quanlytrungtam/logo.jpg"
alt="Athena Logo"
class="w-20 h-20 mx-auto rounded-3xl shadow-2xl">

    <h1 class="mt-4 text-[20px] font-bold text-primary text-center">
        Athena Teacher
</h1>
    </div>
<nav class="flex-1 space-y-2">
<a class="flex items-center gap-3 text-secondary hover:bg-surface-container rounded-lg px-4 py-3 transition-colors" href="tongquan.php">
<span class="material-symbols-outlined" data-icon="dashboard">dashboard</span>
<span class="font-label-md text-label-md">Tổng quan</span>
</a>
<a class="flex items-center gap-3 text-secondary hover:bg-surface-container rounded-lg px-4 py-3 transition-colors" href="lichgiangday.php">
<span class="material-symbols-outlined" data-icon="calendar_month">calendar_month</span>
<span class="font-label-md text-label-md">Lịch giảng dạy</span>
</a>
<a class="flex items-center gap-3 text-secondary hover:bg-surface-container rounded-lg px-4 py-3 transition-colors" href="quanlylophoc.php">
<span class="material-symbols-outlined" data-icon="groups">groups</span>
<span class="font-label-md text-label-md">Quản lý lớp học</span>
</a>
<a class="flex items-center gap-3 text-secondary hover:bg-surface-container rounded-lg px-4 py-3 transition-colors" href="diemdanh.php">
<span class="material-symbols-outlined" data-icon="fact_check">fact_check</span>
<span class="font-label-md text-label-md">Điểm danh</span>
</a>
<a class="flex items-center gap-3 bg-primary-fixed text-primary rounded-lg px-4 py-3 transition-colors" href="quanlydiemso.php">
<span class="material-symbols-outlined" data-icon="grade" style="font-variation-settings: 'FILL' 1;">grade</span>
<span class="font-label-md text-label-md">Quản lý điểm số</span>
</a>
<a class="flex items-center gap-3 text-secondary hover:bg-surface-container rounded-lg px-4 py-3 transition-colors" href="nhanxetdanhgia.php">
<span class="material-symbols-outlined" data-icon="rate_review">rate_review</span>
<span class="font-label-md text-label-md">Đánh giá &amp; Nhận xét</span>
</a>
</nav>
<div class="mt-auto border-t border-outline-variant pt-4">

    <div class="flex items-center justify-between px-3 py-2">

        <div class="flex items-center gap-3">

            <div
            class="w-10 h-10 rounded-full
                   bg-primary text-white
                   flex items-center justify-center
                   font-bold text-sm">

                <?= strtoupper(substr($giaovien['HoTen'],0,1)) ?>

            </div>

            <div>

                <p class="font-bold text-sm">
                    <?= htmlspecialchars($giaovien['HoTen']) ?>
                </p>

                <p class="text-xs text-secondary">
                    Giáo viên
                </p>

            </div>

        </div>

        <a
        href="../backend/logout.php"
        title="Đăng xuất"
        class="p-2 rounded-lg
               hover:bg-red-50
               text-secondary
               hover:text-red-600
               transition">

            <span class="material-symbols-outlined">
                logout
            </span>

        </a>

    </div>
</aside>
<!-- TopNavBar -->
<main class="ml-[260px] min-h-screen flex flex-col overflow-y-auto">
<header class="flex justify-between items-center h-16 px-gutter sticky top-0 z-40 bg-surface/80 backdrop-blur-md border-b border-surface-container-highest">
    <div class="flex items-center">
        <h2 class="font-headline-md text-headline-md font-bold text-on-surface">
           Quản lý điểm số
        </h2>
    </div>
</header>
<!-- Main Content Canvas -->
<div class="w-full flex flex-col gap-6">
<!-- Page Header & Actions -->
<div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
<div class="space-y-1">

<p class="text-body-md text-secondary">Cập nhật và quản lý kết quả học tập của học viên theo từng học phần.</p>
</div>
<div class="flex flex-wrap items-center gap-3">
<button class="flex items-center gap-2 px-4 py-2 bg-white border border-outline-variant rounded-lg font-label-md text-label-md text-secondary hover:bg-surface-container transition-colors active:scale-95">
<span class="material-symbols-outlined text-[20px]" data-icon="download">download</span>
                        Xuất file Excel
                    </button>
</div>
</div>
</div>
<!-- Stats & Filters -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-gutter">
<div class="col-span-1 md:col-span-1 tonal-card p-unit-md rounded-xl">
<label class="block text-label-sm text-secondary mb-2 uppercase tracking-wider font-bold">Lớp học mục tiêu</label>
<div class="relative">
<select
onchange="location='?lop='+this.value"
class="w-full p-3 bg-surface-container-low border-none rounded-lg">

<?php while($lop = $resultLop->fetch_assoc()){ ?>

<option
value="<?= $lop['MaLopHoc'] ?>"
<?= $maLop==$lop['MaLopHoc'] ? 'selected' : '' ?>>

<?= $lop['TenLop'] ?>

</option>

<?php } ?>

</select>
<span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-secondary" data-icon="expand_more">expand_more</span>
</div>
</div>
<div class="col-span-1 md:col-span-1 tonal-card p-unit-md rounded-xl">
<label class="block text-label-sm text-secondary mb-2 uppercase tracking-wider font-bold">Kỳ đánh giá</label>
<div class="relative">
<select class="w-full p-3 bg-surface-container-low border-none rounded-lg font-body-md text-body-md appearance-none cursor-pointer hover:bg-surface-container transition-colors">
<option>Kiểm tra giữa kỳ (Đánh giá định kỳ)</option>
<option>Kiểm tra tiến độ hàng tháng</option>
<option>Đánh giá đầu vào</option>
</select>
<span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-secondary" data-icon="event_available">event_available</span>
</div>
</div>
<div class="col-span-1 md:col-span-1 bg-primary text-on-primary p-unit-md rounded-xl shadow-lg flex flex-col justify-between">
<div>
<p class="text-label-sm opacity-80 uppercase tracking-widest font-bold">Điểm trung bình lớp</p>
<h3 class="font-display-lg text-display-lg mt-1">7.42 <span class="text-body-sm opacity-70">/ 10</span></h3>
</div>
<div class="flex items-center gap-2 mt-2">
<span class="bg-white/20 px-2 py-0.5 rounded text-[10px] font-bold">↑ 0.4%</span>
<p class="text-[11px] opacity-80">so với kỳ đánh giá trước</p>
</div>
</div>
</div>
<!-- Grade Table Section -->
<div class="tonal-card rounded-xl overflow-hidden flex flex-col">
<form action="luudiem.php" method="POST">
<input type="hidden" name="ma_lop" value="<?= $maLop ?>">
<div class="p-unit-md border-b border-outline-variant/30 flex justify-between items-center bg-surface-container-low/20">
<?php

$sqlTenLop = "
SELECT TenLop
FROM lophoc
WHERE MaLopHoc='$maLop'
";

$resultTenLop = $conn->query($sqlTenLop);

if($resultTenLop && $resultTenLop->num_rows > 0){
    $tenlop = $resultTenLop->fetch_assoc();
} else {
    $tenlop = ['TenLop' => 'Không tìm thấy lớp'];
}

?>

<h4 class="font-headline-sm text-headline-sm">

Danh sách điểm -
<?= $tenlop['TenLop'] ?>

</h4><div class="flex items-center gap-4">
<span class="flex items-center gap-1.5 text-secondary font-label-sm text-label-sm">
<span class="w-2.5 h-2.5 rounded-full bg-primary"></span> Trọng số cuối cùng: 100%
                        </span>
<span class="text-outline-variant">|</span>
<button class="p-2 hover:bg-surface-container rounded-full transition-all">
<span class="material-symbols-outlined text-secondary" data-icon="filter_list">filter_list</span>
</button>
</div>
</div>
<div class="overflow-x-auto">
<table class="w-full border-collapse text-left">
<thead>
<tr class="bg-surface-container-low/50 text-label-sm text-secondary border-b border-outline-variant/30">
<th class="px-6 py-4 font-semibold uppercase tracking-wider w-16">STT</th>
<th class="px-6 py-4 font-semibold uppercase tracking-wider">Học viên</th>
<th class="px-6 py-4 font-semibold uppercase tracking-wider text-center w-32">Chuyên cần (%)</th>
<th class="px-6 py-4 font-semibold uppercase tracking-wider text-center w-32">Bài tập (10%)</th>
<th class="px-6 py-4 font-semibold uppercase tracking-wider text-center w-32">Giữa kỳ (40%)</th>
<th class="px-6 py-4 font-semibold uppercase tracking-wider text-center w-32">Cuối kỳ (50%)</th>
<th class="px-6 py-4 font-bold uppercase tracking-wider text-primary text-center w-32">ĐTB</th>
</tr>
</thead>
<tbody class="divide-y divide-outline-variant/10">

<?php
$stt=1;

while($row = $resultDiem->fetch_assoc()){
        $baitap = $row['BaiTap'] ?? 0;
        $giuaky = $row['GiuaKy'] ?? 0;
        $cuoiky = $row['CuoiKy'] ?? 0;

        $dtb = ($baitap * 0.1)
         + ($giuaky * 0.4)
         + ($cuoiky * 0.5);

?>

<tr>

<td class="px-6 py-4">
<?= $stt++ ?>
</td>

<td class="px-6 py-4">
<?= htmlspecialchars($row['HoTen']) ?>
</td>

<td class="px-6 py-4 text-center">
-
</td>

<td class="px-6 py-4 text-center">
<input
type="number"
step="0.1"
name="baitap[<?= $row['MaHocVien'] ?>]"
value="<?= $row['BaiTap'] ?>"
class="grade-input">
</td>

<td class="px-6 py-4 text-center">
<input
type="number"
step="0.1"
name="giuaky[<?= $row['MaHocVien'] ?>]"
value="<?= $row['GiuaKy'] ?>"
class="grade-input">
</td>

<td class="px-6 py-4 text-center">
<input
type="number"
step="0.1"
name="cuoiky[<?= $row['MaHocVien'] ?>]"
value="<?= $row['CuoiKy'] ?>"
class="grade-input">
</td>

<td class="px-6 py-4 text-center">
<?= number_format($dtb,1) ?>
</td>

</tr>

<?php } ?>

</tbody>
</table>
</div>
<div class="p-4 border-t border-outline-variant/30 flex justify-between items-center bg-surface-container-low/20">
<p class="text-body-sm text-secondary">Đang hiển thị 4 trên 24 học viên</p>
<div class="flex gap-2">
<button class="w-8 h-8 flex items-center justify-center rounded border border-outline-variant text-secondary hover:bg-surface-container transition-colors"><span class="material-symbols-outlined text-sm" data-icon="chevron_left">chevron_left</span></button>
<button class="w-8 h-8 flex items-center justify-center rounded bg-primary text-on-primary font-bold text-xs">1</button>
<button class="w-8 h-8 flex items-center justify-center rounded border border-outline-variant text-secondary hover:bg-surface-container transition-colors text-xs">2</button>
<button class="w-8 h-8 flex items-center justify-center rounded border border-outline-variant text-secondary hover:bg-surface-container transition-colors text-xs">3</button>
<button class="w-8 h-8 flex items-center justify-center rounded border border-outline-variant text-secondary hover:bg-surface-container transition-colors"><span class="material-symbols-outlined text-sm" data-icon="chevron_right">chevron_right</span></button>
</div>
</div>
</div>
<div class="p-4 text-right">
    <button
        type="submit"
        class="flex items-center gap-2 px-6 py-2 bg-primary text-on-primary rounded-lg ml-auto">
        Lưu bảng điểm
    </button>
</div>
</form>
<!-- Footer Info -->
<div class="bg-secondary-container/20 p-unit-md rounded-xl border border-secondary-container/30 flex gap-4 items-start">
<span class="material-symbols-outlined text-primary" data-icon="info">info</span>
<div class="space-y-1">
<h5 class="font-label-md text-on-secondary-container">Nhắc nhở về chính sách chấm điểm</h5>
<p class="text-body-sm text-on-secondary-container/80">Điểm trung bình được tính theo công thức: (Bài tập × 10%) + (Giữa kỳ × 40%) + (Cuối kỳ × 50%). Điểm số phải nằm trong khoảng từ 0.0 đến 10.0. Sau khi lưu, điểm số sẽ được gửi cho Trưởng bộ phận học thuật phê duyệt trước khi công bố chính thức cho học viên.</p>
</div>
</div>
</main>
<footer class="ml-sidebar-width py-6 px-unit-lg text-center border-t border-outline-variant bg-surface-container-low/50">
<p class="text-label-sm text-secondary">© 2026 Athena Education Group. Bảo lưu mọi quyền. Module Quản lý Điểm số Chuyên nghiệp v4.2</p>
</footer>
<script>
        // Simple interaction for input feedback
        document.querySelector('button.bg-primary'); 
        if(submitBtn) 
            { submitBtn.addEventListener('click', () => { 
                const originalText = submitBtn.innerHTML; 
                submitBtn.innerHTML = <span class="material-symbols-outlined animate-spin text-[20px]" data-icon="sync">sync</span> Đang xử lý...; 
                submitBtn.classList.add('opacity-80'); setTimeout(() => { 
                    submitBtn.innerHTML = <span class="material-symbols-outlined text-[20px]" data-icon="check_circle">check_circle</span> Đã lưu điểm;
            submitBtn.classList.remove('bg-primary');
                    submitBtn.classList.add('bg-green-600');
                    setTimeout(() => {
                        submitBtn.innerHTML = originalText;
                        submitBtn.classList.remove('bg-green-600', 'opacity-80');
                        submitBtn.classList.add('bg-primary');
                    }, 2000);
                }, 1500);
            });
        }
    </script>
</body></html>

