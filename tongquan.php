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

/* Giáo viên */
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

/* Tổng lớp */
$sqlLop="
SELECT COUNT(*) TongLop
FROM lophoc lh
INNER JOIN giaovien gv
ON lh.MaGiaoVien=gv.MaGiaoVien
WHERE gv.MaTaiKhoan='$maTaiKhoan'
";

$tongLop = $conn->query($sqlLop)->fetch_assoc();

/* Tổng học viên */
$sqlHV="
SELECT COUNT(DISTINCT ct.MaHocVien) TongHV
FROM chitietlophoc ct
INNER JOIN lophoc lh
ON ct.MaLopHoc=lh.MaLopHoc
INNER JOIN giaovien gv
ON lh.MaGiaoVien=gv.MaGiaoVien
WHERE gv.MaTaiKhoan='$maTaiKhoan'
";

$tongHV = $conn->query($sqlHV)->fetch_assoc();

/* Giờ dạy tháng */

$sqlGioDay = "
SELECT
SUM(
TIMESTAMPDIFF(
HOUR,
lc.ThoiGianBatDau,
lc.ThoiGianKetThuc
)
*
lh.SoBuoiDaHoc
) TongGio

FROM lichhocchitiet lc

INNER JOIN lophoc lh
ON lc.MaLopHoc=lh.MaLopHoc

INNER JOIN giaovien gv
ON lh.MaGiaoVien=gv.MaGiaoVien

WHERE gv.MaTaiKhoan='$maTaiKhoan'
";

$gioDay = $conn->query($sqlGioDay)->fetch_assoc();
$thu = date('N');

$sqlLich="
SELECT
lh.TenLop,
ph.TenPhong,
lc.ThoiGianBatDau,
lc.ThoiGianKetThuc,

COUNT(DISTINCT ctl.MaHocVien) AS SiSoThucTe

FROM lichhocchitiet lc

INNER JOIN lophoc lh
ON lc.MaLopHoc=lh.MaLopHoc

INNER JOIN phonghoc ph
ON lc.MaPhongHoc=ph.MaPhongHoc

INNER JOIN giaovien gv
ON lh.MaGiaoVien=gv.MaGiaoVien

LEFT JOIN chitietlophoc ctl
ON lh.MaLopHoc=ctl.MaLopHoc

WHERE gv.MaTaiKhoan='$maTaiKhoan'
AND lc.ThuTrongTuan='$thu'

GROUP BY
lh.MaLopHoc,
lh.TenLop,
ph.TenPhong,
lc.ThoiGianBatDau,
lc.ThoiGianKetThuc

ORDER BY lc.ThoiGianBatDau
";

$lichDay = $conn->query($sqlLich);

$sqlCaHomNay = "
SELECT COUNT(*) TongCa
FROM lichhocchitiet lc
INNER JOIN lophoc lh
ON lc.MaLopHoc = lh.MaLopHoc
INNER JOIN giaovien gv
ON lh.MaGiaoVien = gv.MaGiaoVien
WHERE gv.MaTaiKhoan='$maTaiKhoan'
AND lc.ThuTrongTuan='".date('N')."'
";

$caHomNay = $conn->query($sqlCaHomNay)->fetch_assoc();

$sqlCC = "
SELECT
ROUND(
SUM(
CASE
WHEN dd.TrangThai='Co mat'
THEN 1
ELSE 0
END
)
/ COUNT(*) * 100,
1
) TyLe

FROM diemdanh dd

INNER JOIN buoihoc bh
ON dd.MaBuoiHoc = bh.MaBuoiHoc

INNER JOIN lophoc lh
ON bh.MaLopHoc = lh.MaLopHoc

INNER JOIN giaovien gv
ON lh.MaGiaoVien = gv.MaGiaoVien

WHERE gv.MaTaiKhoan='$maTaiKhoan'
";

$cc = $conn->query($sqlCC)->fetch_assoc();

$sqlTB = "
SELECT *
FROM thongbao
ORDER BY MaThongBao DESC
LIMIT 5
";

$thongBao = $conn->query($sqlTB);

$sqlTyLeLop = "
SELECT
lh.TenLop,

ROUND(
SUM(
CASE
WHEN dd.TrangThai='Co mat'
THEN 1
ELSE 0
END
)
/ COUNT(*) * 100,
0
) TyLe

FROM diemdanh dd

INNER JOIN buoihoc bh
ON dd.MaBuoiHoc = bh.MaBuoiHoc

INNER JOIN lophoc lh
ON bh.MaLopHoc = lh.MaLopHoc

GROUP BY lh.MaLopHoc
";

$tyLeLop = $conn->query($sqlTyLeLop);

?>
<!DOCTYPE html><html class="light" lang="vi" style=""><head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>Athena Teacher - Tổng quan</title>
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
<!-- Active: Tổng quan -->
<a class="flex items-center gap-3 bg-primary-fixed text-primary rounded-lg px-4 py-3 active-nav transition-colors" href="tongquan.php">
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
<a class="flex items-center gap-3 text-secondary hover:bg-surface-container rounded-lg px-4 py-3 transition-colors" href="quanlydiemso.php">
<span class="material-symbols-outlined" data-icon="grade">grade</span>
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
<main class="ml-[260px] min-h-screen flex flex-col overflow-y-auto px-8 py-6">
    <header class="mb-6">
    <h2 class="font-headline-md text-headline-md font-bold">
        Tổng quan
    </h2>
</header>
<!-- Main Content Canvas -->
<!-- Welcome Header -->
<div class="mb-unit-xl">
<p class="text-body-md text-secondary">
Hôm nay bạn có <?= $caHomNay['TongCa'] ?> ca giảng dạy.
</p>
</div>
<!-- 1. Thẻ thống kê nhanh (Bento Layout) -->
<section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-gutter mb-unit-xl">
<div class="tonal-card p-card-padding rounded-xl flex items-center justify-between">
<div>
<p class="text-label-sm text-secondary font-bold tracking-wider uppercase mb-2">TỔNG SỐ LỚP</p>
<p class="text-display-lg font-display-lg text-on-surface">
<?= $tongLop['TongLop'] ?>
</p>
</div>
<div class="w-12 h-12 rounded-lg bg-secondary-container flex items-center justify-center text-on-secondary-container">
<span class="material-symbols-outlined text-2xl" data-icon="school">school</span>
</div>
</div>
<div class="tonal-card p-card-padding rounded-xl flex items-center justify-between">
<div>
<p class="text-label-sm text-secondary font-bold tracking-wider uppercase mb-2">HỌC VIÊN</p>
<p class="text-display-lg font-display-lg text-on-surface">
<?= $tongHV['TongHV'] ?>
</p>
</div>
<div class="w-12 h-12 rounded-lg bg-tertiary-fixed flex items-center justify-center text-on-tertiary-fixed">
<span class="material-symbols-outlined text-2xl" data-icon="person">person</span>
</div>
</div>
<div class="tonal-card p-card-padding rounded-xl flex items-center justify-between">
<div>
<p class="text-label-sm text-secondary font-bold tracking-wider uppercase mb-2">GIỜ DẠY THÁNG</p>
<p class="text-display-lg font-display-lg text-on-surface">
<?= $gioDay['TongGio'] ?>h
</p>
</div>
<div class="w-12 h-12 rounded-lg bg-primary-fixed flex items-center justify-center text-primary">
<span class="material-symbols-outlined text-2xl" data-icon="timer">timer</span>
</div>
</div>
<div class="tonal-card p-card-padding rounded-xl">
<div class="flex justify-between items-start mb-2">
<p class="text-label-sm text-secondary font-bold tracking-wider uppercase">CHUYÊN CẦN</p>
<span class="text-label-sm text-primary font-bold">+2.4%</span>
</div>
<p class="text-display-lg font-display-lg text-on-surface">
<?= $cc['TyLe'] ?? 0 ?>%
</p>
<div class="w-full bg-surface-container rounded-full h-1.5 mt-2">
<div
class="bg-primary h-1.5 rounded-full"
style="width: <?= $cc['TyLe'] ?? 0 ?>%">
</div>
</div>
</div>
</section>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-gutter">
<!-- 2. Lịch dạy hôm nay (Main Focus) -->
<section class="lg:col-span-2 space-y-gutter">
<div class="tonal-card rounded-xl p-card-padding h-full">
<div class="flex justify-between items-center mb-unit-lg">
<div class="flex items-center gap-2">
<span class="material-symbols-outlined text-primary" data-icon="event">event</span>
<h3 class="font-headline-sm text-headline-sm">Lịch dạy hôm nay</h3>
</div>
<span class="text-label-md text-secondary">
<?= date('d/m/Y') ?>
</span>
</div>
<div class="space-y-4">

<?php while($lich = $lichDay->fetch_assoc()){ ?>

<div class="flex items-start gap-4 p-4 rounded-lg border border-outline-variant">

<div class="flex flex-col items-center justify-center min-w-[80px] py-2 bg-surface-container-low rounded-lg">

<p class="font-bold">
<?= substr($lich['ThoiGianBatDau'],0,5) ?>
</p>

<p class="text-sm text-secondary">
<?= substr($lich['ThoiGianKetThuc'],0,5) ?>
</p>

</div>

<div class="flex-1">

<h4 class="font-bold">
<?= htmlspecialchars($lich['TenLop']) ?>
</h4>

<div class="text-sm text-secondary">

Phòng <?= htmlspecialchars($lich['TenPhong']) ?>

<?= $lich['SiSoThucTe'] ?> học viên

</div>

</div>

</div>

<?php } ?>

</div>
</div>
</section>
<!-- Sidebar Widgets -->
<aside class="space-y-gutter">
<!-- 3. Thông báo mới -->
<div class="tonal-card rounded-xl p-card-padding">

    <div class="flex items-center gap-2 mb-4">
        <span class="material-symbols-outlined text-primary">
            campaign
        </span>

        <h3 class="font-headline-sm font-bold">
            Thông báo
        </h3>
    </div>

    <div class="space-y-3">

        <?php while($tb = $thongBao->fetch_assoc()){ ?>

        <div class="p-3 bg-surface-container rounded-lg">

            <p class="font-bold">
                <?= htmlspecialchars($tb['TieuDe']) ?>
            </p>

            <p class="text-sm text-secondary mt-1">
                <?= htmlspecialchars($tb['NoiDung']) ?>
            </p>

        </div>

        <?php } ?>

    </div>

</div>
<!-- 4. Biểu đồ nhỏ: Tỷ lệ chuyên cần -->
<div class="tonal-card rounded-xl p-card-padding">

    <h3 class="font-headline-sm font-bold mb-4">
        Chuyên cần theo lớp
    </h3>

    <div class="space-y-4">

        <?php while($lop = $tyLeLop->fetch_assoc()){ ?>

        <div>

            <div class="flex justify-between mb-1">

                <span>
                    <?= htmlspecialchars($lop['TenLop']) ?>
                </span>

                <span class="font-bold">
                    <?= $lop['TyLe'] ?>%
                </span>

            </div>

            <div class="w-full bg-gray-200 rounded-full h-2">

                <div
                    class="bg-red-600 h-2 rounded-full"
                    style="width: <?= $lop['TyLe'] ?>%">
                </div>

            </div>

        </div>

        <?php } ?>

    </div>

</div>
<!-- Quick Action FAB alternative -->
<a
href="taothongbao.php"
class="block text-center
bg-primary text-white
rounded-xl py-3
font-bold shadow-lg
hover:opacity-90 transition">

<span class="material-symbols-outlined">
add_circle
</span>

Tạo thông báo cho lớp

</a>
</aside>
</div>
</main>
<script>
        // Micro-interactions
        document.querySelectorAll('.tonal-card').forEach(card => {
            card.addEventListener('mousedown', () => {
                card.style.transform = 'scale(0.98)';
            });
            card.addEventListener('mouseup', () => {
                card.style.transform = 'translateY(-2px)';
            });
        });

        // Current time indicator animation (simulated)
        const timeDisplay = document.querySelector('header .text-primary');
        if(timeDisplay) {
            setInterval(() => {
                const now = new Date();
                const timeStr = now.getHours().toString().padStart(2, '0') + ":" + now.getMinutes().toString().padStart(2, '0');
                // Could update time here if needed
            }, 60000);
        }
    </script>


</body></html>