<?php
$conn = new mysqli("localhost","root","","quanlytrungtam");

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

/* =======================
   YEAR FILTER
======================= */
$year = isset($_GET['year']) ? (int)$_GET['year'] : (int)date("Y");

/* =======================
   BIỂU ĐỒ THEO THÁNG
======================= */
$monthly = array_fill(1, 12, 0);

$sql = $conn->query("
    SELECT MONTH(NgayThamGia) AS m, COUNT(*) AS total
    FROM ChiTietLopHoc
    WHERE NgayThamGia IS NOT NULL
      AND YEAR(NgayThamGia) = $year
    GROUP BY MONTH(NgayThamGia)
    ORDER BY m
");

if ($sql) {
    while ($row = $sql->fetch_assoc()) {
        $m = (int)$row['m'];
        if ($m >= 1 && $m <= 12) {
            $monthly[$m] = (int)$row['total'];
        }
    }
}

/* =======================
   THỐNG KÊ CƠ BẢN
======================= */
$hocvien = $conn->query("SELECT COUNT(*) AS tong FROM HocVien")->fetch_assoc()['tong'] ?? 0;
$giaovien = $conn->query("SELECT COUNT(*) AS tong FROM GiaoVien")->fetch_assoc()['tong'] ?? 0;
$lophoc   = $conn->query("SELECT COUNT(*) AS tong FROM LopHoc")->fetch_assoc()['tong'] ?? 0;

/* =======================
   LỚP HÔM NAY
======================= */
$lophomnay_count = $conn->query("
    SELECT COUNT(*) AS tong
    FROM BuoiHoc
    WHERE NgayHoc = CURDATE()
")->fetch_assoc()['tong'] ?? 0;

$lophoc_trong_ngay = $conn->query("
    SELECT 
        lh.TenLop,
        lh.TrinhDo,
        lh.SiSoToiDa,
        lh.SiSoHienTai,
        lh.TrangThai,
        bh.NgayHoc,
        lhct.ThoiGianBatDau,
        lhct.ThoiGianKetThuc,
        nd.HoTen AS TenGiaoVien,
        ph.TenPhong
    FROM BuoiHoc bh
    JOIN LopHoc lh ON bh.MaLopHoc = lh.MaLopHoc
    LEFT JOIN GiaoVien gv ON lh.MaGiaoVien = gv.MaGiaoVien
    LEFT JOIN TaiKhoan tk ON gv.MaTaiKhoan = tk.MaTaiKhoan
    LEFT JOIN NguoiDung nd ON tk.MaNguoiDung = nd.MaNguoiDung
    LEFT JOIN LichHocChiTiet lhct ON lh.MaLopHoc = lhct.MaLopHoc
    LEFT JOIN PhongHoc ph ON lhct.MaPhongHoc = ph.MaPhongHoc
    WHERE bh.NgayHoc = CURDATE()
");

/* =======================
   HỌC VIÊN MỚI
======================= */
$hocvienmoi = $conn->query("
    SELECT COUNT(*) AS tong FROM HocVien
")->fetch_assoc()['tong'] ?? 0;

/* =======================
   LỚP ĐÔNG NHẤT
======================= */
$lopdongnhat = $conn->query("
    SELECT TenLop, SiSoHienTai
    FROM LopHoc
    ORDER BY SiSoHienTai DESC
    LIMIT 1
")->fetch_assoc();

$ten_lop_dong = $lopdongnhat['TenLop'] ?? 'Không có dữ liệu';
$si_so_dong   = $lopdongnhat['SiSoHienTai'] ?? 0;

/* =======================
   TỶ LỆ ĐẠT MỤC TIÊU (FIX LỖI BẠN ĐANG GẶP)
   dựa trên bảng KetQuaHocTap
======================= */

$totalResult = $conn->query("
    SELECT COUNT(*) AS t FROM KetQuaHocTap
")->fetch_assoc()['t'] ?? 0;

$passResult = $conn->query("
    SELECT COUNT(*) AS t 
    FROM KetQuaHocTap
    WHERE DiemSo >= 5
")->fetch_assoc()['t'] ?? 0;

$overallRate = $totalResult > 0
    ? round(($passResult / $totalResult) * 100)
    : 0;

/* IELTS / TOEIC (nếu bạn dùng loại kiểm tra) */
$totalIelts = $conn->query("
    SELECT COUNT(*) AS t FROM KetQuaHocTap WHERE LoaiKiemTra='IELTS'
")->fetch_assoc()['t'] ?? 0;

$passIelts = $conn->query("
    SELECT COUNT(*) AS t 
    FROM KetQuaHocTap 
    WHERE LoaiKiemTra='IELTS' AND DiemSo >= 5
")->fetch_assoc()['t'] ?? 0;

$ieltsRate = $totalIelts > 0 ? round(($passIelts/$totalIelts)*100) : 0;

$totalToeic = $conn->query("
    SELECT COUNT(*) AS t FROM KetQuaHocTap WHERE LoaiKiemTra='TOEIC'
")->fetch_assoc()['t'] ?? 0;

$passToeic = $conn->query("
    SELECT COUNT(*) AS t 
    FROM KetQuaHocTap 
    WHERE LoaiKiemTra='TOEIC' AND DiemSo >= 5
")->fetch_assoc()['t'] ?? 0;

$toeicRate = $totalToeic > 0 ? round(($passToeic/$totalToeic)*100) : 0;

?> 
<!DOCTYPE html><html class="light" lang="vi">
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>Athena Admin Dashboard</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&amp;display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "inverse-on-surface": "#f0f1f2",
                        "tertiary-container": "#156aff",
                        "on-background": "#191c1d",
                        "inverse-surface": "#2e3132",
                        "on-error": "#ffffff",
                        "outline": "#936e6c",
                        "surface": "#f8f9fa",
                        "tertiary": "#0052d1",
                        "on-secondary-fixed-variant": "#454749",
                        "outline-variant": "#e8bcba",
                        "on-primary-fixed": "#410006",
                        "background": "#f8f9fa",
                        "on-error-container": "#93000a",
                        "error": "#ba1a1a",
                        "secondary-fixed-dim": "#c6c6c9",
                        "surface-container-low": "#f3f4f5",
                        "primary-container": "#e90031",
                        "tertiary-fixed-dim": "#b3c5ff",
                        "surface-container": "#edeeef",
                        "secondary": "#5d5e61",
                        "primary-fixed": "#ffdad8",
                        "on-tertiary-fixed-variant": "#003fa4",
                        "surface-tint": "#bf0026",
                        "inverse-primary": "#ffb3b0",
                        "on-surface-variant": "#5e3f3d",
                        "primary": "#bb0025",
                        "on-primary-fixed-variant": "#93001b",
                        "surface-dim": "#d9dadb",
                        "tertiary-fixed": "#dae1ff",
                        "on-tertiary-container": "#fefcff",
                        "on-secondary-fixed": "#1a1c1e",
                        "on-primary": "#ffffff",
                        "on-secondary": "#ffffff",
                        "secondary-container": "#e2e2e5",
                        "surface-bright": "#f8f9fa",
                        "on-surface": "#191c1d",
                        "on-secondary-container": "#636467",
                        "surface-container-lowest": "#ffffff",
                        "surface-container-high": "#e7e8e9",
                        "on-tertiary": "#ffffff",
                        "surface-container-highest": "#e1e3e4",
                        "primary-fixed-dim": "#ffb3b0",
                        "surface-variant": "#e1e3e4",
                        "secondary-fixed": "#e2e2e5",
                        "on-primary-container": "#fffbff",
                        "on-tertiary-fixed": "#001849",
                        "error-container": "#ffdad6"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    "spacing": {
                        "margin-desktop": "64px",
                        "unit": "8px",
                        "margin-tablet": "32px",
                        "container-max-width": "1280px",
                        "margin-mobile": "16px",
                        "gutter": "24px"
                    },
                    "fontFamily": {
                        "label-sm": ["Montserrat"],
                        "headline-md": ["Montserrat"],
                        "display-lg": ["Montserrat"],
                        "label-md": ["Montserrat"],
                        "body-lg": ["Montserrat"],
                        "headline-lg-mobile": ["Montserrat"],
                        "headline-lg": ["Montserrat"],
                        "body-md": ["Montserrat"]
                    },
                    "fontSize": {
                        "label-sm": ["12px", { "lineHeight": "16px", "fontWeight": "500" }],
                        "headline-md": ["24px", { "lineHeight": "32px", "fontWeight": "600" }],
                        "display-lg": ["48px", { "lineHeight": "56px", "letterSpacing": "-0.02em", "fontWeight": "700" }],
                        "label-md": ["14px", { "lineHeight": "20px", "letterSpacing": "0.01em", "fontWeight": "600" }],
                        "body-lg": ["18px", { "lineHeight": "28px", "fontWeight": "400" }],
                        "headline-lg-mobile": ["28px", { "lineHeight": "36px", "fontWeight": "700" }],
                        "headline-lg": ["32px", { "lineHeight": "40px", "letterSpacing": "-0.01em", "fontWeight": "700" }],
                        "body-md": ["16px", { "lineHeight": "24px", "fontWeight": "400" }]
                    }
                }
            }
        }
    </script>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .material-symbols-outlined.fill {
            font-variation-settings: 'FILL' 1;
        }
        /* Custom scrollbar for sidebar */
        ::-webkit-scrollbar {
            width: 6px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background-color: #e2e2e5;
            border-radius: 20px;
        }
    </style>
</head>
<body class="bg-background text-on-background antialiased overflow-x-hidden">
<aside class="h-full w-64 fixed left-0 top-0 bg-white border-r border-outline-variant flex flex-col z-50">
<!-- Brand Identity -->
<div class="px-gutter pt-10 pb-6 flex flex-col"><img alt="Athena Admin Logo" class="w-20 h-auto mx-auto mb-4" src="/quanlytrungtam/logo.jpg">
<h1 class="font-headline-md text-[20px] font-bold text-primary leading-none text-center">Athena Admin</h1>
</div>
<!-- Navigation Menu -->
<nav class="flex-1 overflow-y-auto py-2 px-4 space-y-0.5 scrollbar-hide">

    <!-- Dashboard -->
    <a class="flex items-center gap-3 px-4 py-2.5 bg-primary/10 text-primary rounded-lg transition-colors"
       href="dashboard.php">

        <span class="material-symbols-outlined text-[20px]">
            dashboard
        </span>

        <span class="font-label-md text-label-md">
            Dashboard
        </span>
    </a>

    <!-- Học viên -->
    <a class="flex items-center gap-3 px-4 py-2.5 text-secondary hover:text-primary hover:bg-primary/5 rounded-lg transition-colors"
       href="hocvien.php">

        <span class="material-symbols-outlined text-[20px]">
            group
        </span>

        <span class="font-label-md text-label-md">
            Học viên
        </span>
    </a>

    <!-- Lớp học -->
    <a class="flex items-center gap-3 px-4 py-2.5 text-secondary hover:text-primary hover:bg-primary/5 rounded-lg transition-colors"
       href="lophoc.php">

        <span class="material-symbols-outlined text-[20px] fill">
            school
        </span>

        <span class="font-label-md text-label-md">
            Lớp học
        </span>
    </a>

    <!-- Giáo viên -->
    <a class="flex items-center gap-3 px-4 py-2.5 text-secondary hover:text-primary hover:bg-primary/5 rounded-lg transition-colors"
       href="giaovien.php">

        <span class="material-symbols-outlined text-[20px]">
            record_voice_over
        </span>

        <span class="font-label-md text-label-md">
            Giáo viên
        </span>
    </a>

    <!-- Thời khóa biểu -->
    <a class="flex items-center gap-3 px-4 py-2.5 text-secondary hover:text-primary hover:bg-primary/5 rounded-lg transition-colors"
       href="thoikhoabieu.php">

        <span class="material-symbols-outlined text-[20px]">
            calendar_today
        </span>

        <span class="font-label-md text-label-md">
            Thời khóa biểu
        </span>
    </a>

    <!-- Điểm danh -->
    <a class="flex items-center gap-3 px-4 py-2.5 text-secondary hover:text-primary hover:bg-primary/5 rounded-lg transition-colors"
       href="diemdanh.php">

        <span class="material-symbols-outlined text-[20px]">
            fact_check
        </span>

        <span class="font-label-md text-label-md">
            Điểm danh
        </span>
    </a>

    <!-- Kết quả học tập -->
    <a class="flex items-center gap-3 px-4 py-2.5 text-secondary hover:text-primary hover:bg-primary/5 rounded-lg transition-colors"
       href="ketquahoctap.php">

        <span class="material-symbols-outlined text-[20px]">
            analytics
        </span>

        <span class="font-label-md text-label-md">
            Kết quả học tập
        </span>
    </a>

    <!-- Báo cáo -->
    <a class="flex items-center gap-3 px-4 py-2.5 text-secondary hover:text-primary hover:bg-primary/5 rounded-lg transition-colors"
       href="baocao.php">

        <span class="material-symbols-outlined text-[20px]">
            assessment
        </span>

        <span class="font-label-md text-label-md">
            Báo cáo
        </span>
    </a>

    <!-- Cài đặt -->
    <a class="flex items-center gap-3 px-4 py-2.5 text-secondary hover:text-primary hover:bg-primary/5 rounded-lg transition-colors"
       href="caidat.php">

        <span class="material-symbols-outlined text-[20px]">
            settings
        </span>

        <span class="font-label-md text-label-md">
            Cài đặt
        </span>
    </a>

</nav>
<!-- Sidebar Footer -->
<div class="p-6">
<div class="flex items-center justify-center gap-3 bg-surface-container-low/30 py-3 rounded-xl">
<div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center text-[10px] font-bold text-white">AD</div>
<div class="flex items-center gap-2">
<p class="font-label-md text-label-md text-on-surface font-semibold">Admin User</p>
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
</div>
</div>
</aside>
<!-- Main Canvas -->
<main class="ml-64 min-h-screen flex flex-col overflow-y-auto">
<header class="flex justify-between items-center h-16 px-gutter sticky top-0 z-40 bg-surface/80 backdrop-blur-md border-b border-surface-container-highest">
    <div class="flex items-center">
        <h2 class="font-headline-md text-headline-md font-bold text-on-surface">
            Tổng quan
        </h2>
    </div>
</header>
<!-- Page Content -->
<div class="p-gutter flex flex-col gap-8 max-w-container-max mx-auto w-full"><!-- Statistics Cards (Bento Style) - Single Horizontal Row -->
<section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
<div class="bg-white p-6 rounded-2xl border border-surface-container-high shadow-sm hover:shadow-md transition-shadow group h-full">
<div class="flex justify-between items-start mb-4">
<div class="p-3 bg-primary/10 rounded-xl group-hover:bg-primary transition-colors">
<span class="material-symbols-outlined text-primary group-hover:text-white" data-icon="menu_book">menu_book</span>
</div>
<span class="text-primary font-bold bg-primary/10 px-2 py-0.5 rounded text-[10px]">+12%</span>
</div>
<p class="text-[10px] text-secondary font-bold uppercase tracking-wider mb-1">Lớp học hôm nay</p>
<p class="font-headline-lg text-[32px] font-extrabold text-on-surface">
<?= $lophomnay_count ?>
</p>
</div>
<div class="bg-white p-6 rounded-2xl border border-surface-container-high shadow-sm hover:shadow-md transition-shadow group h-full">
<div class="flex justify-between items-start mb-4">
<div class="p-3 bg-secondary/10 rounded-xl group-hover:bg-secondary transition-colors">
<span class="material-symbols-outlined text-secondary group-hover:text-white" data-icon="group">group</span>
</div>
<span class="text-secondary font-bold bg-secondary/10 px-2 py-0.5 rounded text-[10px]">560</span>
</div>
<p class="text-[10px] text-secondary font-bold uppercase tracking-wider mb-1">Tổng học viên</p>
<p class="font-headline-lg text-[32px] font-extrabold text-on-surface">
<?= $hocvien ?>
</p>
</div>
<div class="bg-white p-6 rounded-2xl border border-surface-container-high shadow-sm hover:shadow-md transition-shadow group h-full">
<div class="flex justify-between items-start mb-4">
<div class="p-3 bg-tertiary/10 rounded-xl group-hover:bg-tertiary transition-colors">
<span class="material-symbols-outlined text-tertiary group-hover:text-white" data-icon="person_add">person_add</span>
</div>
<span class="text-tertiary font-bold bg-tertiary/10 px-2 py-0.5 rounded text-[10px]">+10</span>
</div>
<p class="text-[10px] text-secondary font-bold uppercase tracking-wider mb-1">Học viên mới (Tháng)</p>
<p class="font-headline-lg text-[32px] font-extrabold text-on-surface">
<?= $hocvienmoi ?>
</p>
</div>
<div class="bg-primary p-6 rounded-2xl shadow-lg shadow-primary/20 hover:brightness-105 transition-all text-on-primary flex flex-col justify-between h-full">
<div>
<p class="text-[10px] text-white/70 font-bold uppercase tracking-wider mb-1">Lớp đông nhất</p>
<p class="font-headline-md text-xl font-bold leading-snug">
<?= $ten_lop_dong ?>
</p>
</div>
<div class="flex items-center gap-3 mt-4">
<div class="flex -space-x-2">
<img class="w-8 h-8 rounded-full border-2 border-primary" data-alt="Student avatar" src="https://lh3.googleusercontent.com/aida-public/AB6AXuD78ibuNcAjTFt3Aa4PRlFW-TV6lF_Xv9YGtQrNK6bSRDC2mIgEEJs6JoLLizkDZAF1u25R1QkbrbWYu0Ax_hvq6xItcmMDhLI3UsInrsMjBeqD4VH9FqfXNv3_9OTNfKQLEPLPxi1mv9YyDC4Fukcc9CpCgU__qkxvV4tRZPrctoGHLfwtmdilWI5v0tuQRqX6r3mk3FXQhzh0DJ9ihPae6ClSnfQqEA6Stu75Cc5YJ99PINpJXadq6N0RfInemh-RpB4EuIO0vFc">
<img class="w-8 h-8 rounded-full border-2 border-primary" data-alt="Student avatar" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCvXEV4Vnbp_360aHG-5srM_RF62TP25QQeVk8DKOybEm2m_KlHFEBXhRcHDnU6fUtHIEzhTo9ToHxZq6vul6Rlf9ZlvV71G4REIbeOCUYuO8euTdY2EXDWObhiLZwAz0aYa8zcUOqpMGlKRIwjR2MGRD5VgA6uCGW9cAUzNBDcMGCtQV5fxwHu3WPImKzkCjTwkxaVOJYecW0aP1-C8KbQ53HeEBUzvjfzugHcRNGzi_pOyPBmTfjEKtIEQNbpcvGU7g7m7DQpRaY">
<img class="w-8 h-8 rounded-full border-2 border-primary" data-alt="Student avatar" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAGQwEcAIpE8pweUNcNzMUMqPRGVzsOfjVFoCDPjjP8d4dLpnSCsJyJAYXCTfk66l5LkjUmntp0GYXLYRBlF6DzZf3yIdUkpcIJ_5rSDSGo2QW4UBMZL_zbtYv7QrR2Y295f2iHSamJLsMvlzK2wla_6XF9naEBcAe7VmfzWuNNuRYcC43TcCGOP5snYWY19LXVNCzmLnZQhibmUWlA7-rBe06DshAU1nH-lWcVp1xqSy3TAr0xGbz_gck11HZdm0UMPpSbiA8wUps">
<div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center text-[10px] font-bold">+22</div>
</div>
<span class="text-xs font-medium">
<?= $lopdongnhat['SiSoHienTai'] ?> HV
</span>
</div>
</div>
</section>
<!-- Charts Section - Side-by-Side -->
<section class="grid grid-cols-1 lg:grid-cols-2 gap-6">
<div class="bg-white p-8 rounded-3xl border shadow-sm">

<div class="flex justify-between items-center mb-8">
    <h3 class="font-bold text-xl">Số lượng học viên theo tháng</h3>

<div class="flex bg-gray-100 p-1 rounded-xl w-fit">
    <a href="?year=2026"
       class="px-4 py-1 text-sm font-semibold rounded-lg transition
       <?= $year==2026 ? 'bg-red-600 text-white shadow' : 'text-gray-600 hover:bg-white' ?>">
        2026
    </a>

    <a href="?year=2025"
       class="px-4 py-1 text-sm font-semibold rounded-lg transition
       <?= $year==2025 ? 'bg-red-600 text-white shadow' : 'text-gray-600 hover:bg-white' ?>">
        2025
    </a>
</div>
</div>

<?php
$max = max($monthly);
if ($max <= 0) $max = 1;
?>

<div class="h-64 flex items-end gap-2">
<?php for ($i=1; $i<=12; $i++): 
    $value = $monthly[$i] ?? 0;
    $height = ($value / $max) * 100;
?>
    <div class="flex-1 bg-red-500/10 hover:bg-red-500/20 transition rounded-t-xl relative group"
         style="height: <?= $height ?>%">

        <div class="absolute -top-6 left-1/2 -translate-x-1/2 text-xs opacity-0 group-hover:opacity-100">
            <?= $value ?>
        </div>

    </div>
<?php endfor; ?>
</div>

<div class="flex justify-between mt-3 text-xs text-gray-500">
<?php for ($i=1; $i<=12; $i++): ?>
    <span>T<?= $i ?></span>
<?php endfor; ?>
</div>

</div>
<div class="bg-white p-8 rounded-3xl border shadow-sm flex flex-col items-center">

<h3 class="font-bold text-xl mb-6">Tỷ lệ đạt mục tiêu</h3>

<?php
// chống lỗi chia 0 + clamp 0-100
$overallRate = max(0, min(100, $overallRate));
?>

<div class="relative w-44 h-44">

<svg class="w-full h-full -rotate-90" viewBox="0 0 36 36">

<!-- nền -->
<path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831"
fill="none" stroke="#eee" stroke-width="4"/>

<!-- progress -->
<path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831"
fill="none"
stroke="#bb0025"
stroke-width="4"
stroke-linecap="round"
stroke-dasharray="<?= $overallRate ?>, 100"/>
</svg>

<div class="absolute inset-0 flex flex-col items-center justify-center">
    <div class="text-3xl font-bold text-red-600">
        <?= $overallRate ?>%
    </div>
    <div class="text-xs text-gray-500">Tổng đạt</div>
</div>

</div>

<!-- detail -->
<div class="mt-6 w-full space-y-3">

<div class="flex justify-between">
    <span>IELTS</span>
    <span class="font-bold"><?= $ieltsRate ?? 0 ?>%</span>
</div>

<div class="flex justify-between">
    <span>TOEIC</span>
    <span class="font-bold"><?= $toeicRate ?? 0 ?>%</span>
</div>

</div>

</div>
</section>
<!-- Table Section - Full Width -->
<section class="bg-white rounded-3xl border border-surface-container-high shadow-sm overflow-hidden mb-gutter">
<div class="px-8 py-5 border-b border-surface-container-low flex justify-between items-center">
<h3 class="font-headline-md text-xl font-bold text-on-surface">Lớp học trong ngày</h3>
</div>
<div class="overflow-x-auto">
<table class="w-full text-left border-collapse">
<thead>
<tr class="bg-surface-container-low/20 text-secondary border-b border-surface-container-low">
<th class="pl-8 pr-4 py-5 font-bold text-[11px] uppercase tracking-widest">Tên lớp học</th>
<th class="px-4 py-5 font-bold text-[11px] uppercase tracking-widest">Giáo viên</th>
<th class="px-4 py-5 font-bold text-[11px] uppercase tracking-widest">Thời gian</th>
<th class="px-4 py-5 font-bold text-[11px] uppercase tracking-widest">Phòng</th>
<th class="px-4 py-5 font-bold text-[11px] uppercase tracking-widest">Sĩ số</th>
<th class="pl-4 pr-8 py-5 font-bold text-[11px] uppercase tracking-widest text-right">Trạng thái</th>
</tr>
</thead>
<tbody class="divide-y divide-surface-container-low/30">

<?php if ($lophoc_trong_ngay && $lophoc_trong_ngay->num_rows > 0): ?>
    <?php while($row = $lophoc_trong_ngay->fetch_assoc()): ?>
<tr class="hover:bg-primary/5 transition-colors">

    <!-- Tên lớp -->
    <td class="pl-8 pr-4 py-5">
        <span class="font-bold text-on-surface">
            <?= htmlspecialchars($row['TenLop']) ?>
        </span>
    </td>

    <!-- Giáo viên -->
    <td class="px-4 py-5 text-sm text-secondary">
        <?= htmlspecialchars($row['TenGiaoVien']) ?>
    </td>

    <!-- Thời gian -->
    <td class="px-4 py-5 text-sm text-secondary">
    <?= ($row['ThoiGianBatDau'] ?? '---') . ' - ' . ($row['ThoiGianKetThuc'] ?? '---') ?>
</td>

    <!-- Phòng (DB chưa có nên để placeholder) -->
    <td class="px-4 py-5 text-sm text-secondary">
        <?= $row['TenPhong'] ?? 'Chưa có phòng' ?>
    </td>

    <!-- Sĩ số -->
    <td class="px-4 py-5 text-sm text-secondary">
        <?= $row['SiSoHienTai'] ?>/<?= $row['SiSoToiDa'] ?>
    </td>

    <!-- Trạng thái -->
    <td class="pl-4 pr-8 py-5 text-right">
        <span class="px-3 py-1 rounded-full text-xs bg-surface-container-low text-secondary">
            Đang học
        </span>
    </td>

</tr>
    <?php endwhile; ?>
<?php else: ?>
<tr>
    <td colspan="6" class="text-center py-6 text-secondary">
        Không có lớp học hôm nay
    </td>
</tr>
<?php endif; ?>

</tbody>
</table>
</div>
</section></div>
</main>
<script>
        // Lightweight interactions
        document.querySelectorAll('nav a').forEach(link => {
            link.addEventListener('click', function(e) {
                // In a real app, this would handle routing
                if (!this.classList.contains('active-nav-item')) {
                    const activeItem = document.querySelector('.active-nav-item');
                    if (activeItem) {
                        activeItem.classList.remove('active-nav-item');
                        activeItem.classList.add('text-secondary', 'hover:text-primary', 'hover:bg-primary/5');
                    }
                    this.classList.add('active-nav-item');
                    this.classList.remove('text-secondary', 'hover:text-primary', 'hover:bg-primary/5');
                }
            });
        });


        // Search Bar Focus Effect
        const searchInput = document.querySelector('input[type="text"]');
        searchInput.parentElement.addEventListener('click', () => searchInput.focus());
        searchInput.addEventListener('focus', () => searchInput.parentElement.classList.add('border-primary', 'ring-1', 'ring-primary/20'));
        searchInput.addEventListener('blur', () => searchInput.parentElement.classList.remove('border-primary', 'ring-1', 'ring-primary/20'));
    </script>








<div id="snapdom-sandbox" data-snapdom-sandbox="true" aria-hidden="true" style="position: absolute; left: -9999px; top: -9999px; width: 0px; height: 0px; overflow: hidden;"></div>


</body></html>








