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

$sql = "
SELECT
    bh.MaBuoiHoc,
    bh.NgayHoc,
    lh.TenLop,
    lct.ThoiGianBatDau,
    lct.ThoiGianKetThuc,
    ph.TenPhong
FROM buoihoc bh

INNER JOIN lophoc lh
ON bh.MaLopHoc = lh.MaLopHoc

INNER JOIN giaovien gv
ON lh.MaGiaoVien = gv.MaGiaoVien

INNER JOIN lichhocchitiet lct
ON lh.MaLopHoc = lct.MaLopHoc

INNER JOIN phonghoc ph
ON lct.MaPhongHoc = ph.MaPhongHoc

WHERE gv.MaTaiKhoan='$maTaiKhoan'

ORDER BY bh.NgayHoc
";

$result = $conn->query($sql);
if(!$result){
    die("Lỗi SQL: " . $conn->error);
}
$lichHoc = [];

while($row = $result->fetch_assoc()){

    $ngay = date('Y-m-d', strtotime($row['NgayHoc']));
    $lichHoc[$ngay][] = $row;
}

$sqlGV = "
SELECT nd.HoTen
FROM giaovien gv
INNER JOIN taikhoan tk
ON gv.MaTaiKhoan=tk.MaTaiKhoan
INNER JOIN nguoidung nd
ON tk.MaNguoiDung=nd.MaNguoiDung
WHERE gv.MaTaiKhoan='$maTaiKhoan'
";

$giaovien = $conn->query($sqlGV)->fetch_assoc();

$homNay = date('Y-m-d');

$sqlHomNay = "
SELECT
    bh.NgayHoc,
    lh.TenLop,
    lct.ThoiGianBatDau,
    lct.ThoiGianKetThuc,
    lh.SiSoHienTai,
    ph.TenPhong
FROM buoihoc bh

INNER JOIN lophoc lh
ON bh.MaLopHoc=lh.MaLopHoc

INNER JOIN lichhocchitiet lct
ON lh.MaLopHoc=lct.MaLopHoc

INNER JOIN phonghoc ph
ON lct.MaPhongHoc=ph.MaPhongHoc

INNER JOIN giaovien gv
ON lh.MaGiaoVien=gv.MaGiaoVien

WHERE gv.MaTaiKhoan='$maTaiKhoan'
AND bh.NgayHoc='$homNay'
";

$homNayData = $conn->query($sqlHomNay);

?>
<!DOCTYPE html>
<html class="light" lang="vi"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Lịch giảng dạy - Athena Teacher</title>
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
        .calendar-grid{
            display:grid;
            grid-template-columns:repeat(7,1fr);
        }

        .calendar-cell{
            min-height:140px;
            position:relative;
        }

        .calendar-cell:hover{
            background:#fafafa;
        }

        .tonal-card{
            background:#fff;
            border:1px solid #e5e7eb;
            box-shadow:0 1px 3px rgba(0,0,0,.08);
        }
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
<a class="flex items-center gap-3 text-secondary hover:bg-surface-container rounded-lg px-4 py-3 transition-colors" href="tongquan.php">
<span class="material-symbols-outlined" data-icon="dashboard">dashboard</span>
<span class="font-label-md text-label-md">Tổng quan</span>
</a>
<!-- Active: Lịch giảng dạy -->
<a class="flex items-center gap-3 bg-primary-fixed text-primary rounded-lg px-4 py-3 active-nav transition-colors" href="lichgiangday.php">
<span class="material-symbols-outlined" data-icon="calendar_month" style="font-variation-settings: 'FILL' 1;">calendar_month</span>
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
</div>
</aside>
<!-- TopNavBar -->
<main class="ml-[260px] min-h-screen flex flex-col overflow-y-auto">
        <header class="flex justify-between items-center h-16 px-gutter sticky top-0 z-40 bg-surface/80 backdrop-blur-md border-b border-surface-container-highest">
        <div class="flex items-center">
            <h2 class="font-headline-md text-headline-md font-bold text-on-surface">Lịch giảng dạy</h2>
        </div>
        
    </header>

<!-- Main Content -->
<div class="grid grid-cols-12 gap-6 p-6">
<!-- Left Column: Calendar -->
<div class="col-span-12 xl:col-span-8 space-y-6">
<!-- Calendar Controls -->
<div class="flex flex-wrap items-center justify-between gap-4">
<div class="flex items-center gap-3">


</div>
<div class="flex items-center gap-2">
<div class="flex items-center bg-white border border-outline-variant rounded-lg overflow-hidden">
<button class="p-2 hover:bg-surface-container transition-colors">
<span class="material-symbols-outlined text-secondary">chevron_left</span>
</button>
<span class="px-4 text-label-md font-bold">
Tháng <?= date('m') ?>, <?= date('Y') ?>
</span>
<button class="p-2 hover:bg-surface-container transition-colors ">
<span class="material-symbols-outlined text-secondary">chevron_right</span>
</button>
</div>


</div>
</div>
<!-- Calendar Content -->
<section class="tonal-card rounded-xl overflow-hidden mt-6 mb-32">
<div class="calendar-grid bg-surface-container-low border-b border-outline-variant/30">
<div class="py-3 text-center text-label-sm font-bold text-secondary uppercase tracking-wider">Thứ 2</div>
<div class="py-3 text-center text-label-sm font-bold text-secondary uppercase tracking-wider"> Thứ 3</div>
<div class="py-3 text-center text-label-sm font-bold text-secondary uppercase tracking-wider">Thứ 4</div>
<div class="py-3 text-center text-label-sm font-bold text-secondary uppercase tracking-wider">Thứ 5</div>
<div class="py-3 text-center text-label-sm font-bold text-secondary uppercase tracking-wider">Thứ 6</div>
<div class="py-3 text-center text-label-sm font-bold text-secondary uppercase tracking-wider">Thứ 7</div>
<div class="py-3 text-center text-label-sm font-bold text-secondary uppercase tracking-wider">Chủ nhật</div>
</div>
<div class="calendar-grid">
<?php $soNgay = date('t');

for($day=1;$day<=$soNgay;$day++){ ?>

<div class="calendar-cell p-3 border-r border-b border-outline-variant/20">

    <span class="text-label-sm font-bold">
        <?= $day ?>
    </span>

    <?php
    if(isset($lichHoc[$day])){

        foreach($lichHoc[$day] as $lich){
    ?>

        <a
href="?MaBuoiHoc=<?= $lich['MaBuoiHoc'] ?>"
class="block mt-2 bg-primary-fixed
text-primary px-2 py-1 rounded
text-[10px] font-bold
border-l-4 border-primary
hover:scale-105 transition">

<?= substr($lich['ThoiGianBatDau'],0,5) ?>

-

<?= htmlspecialchars($lich['TenLop']) ?>

</a>

    <?php
        }
    }
    ?>

</div>

<?php } ?>
</div>

<!-- Legend -->
<div class="flex items-center gap-6 p-4 tonal-card rounded-xl">
<div class="flex items-center gap-2">
<span class="w-3 h-3 rounded-full bg-primary"></span>
<span class="text-label-sm text-secondary font-bold">Lịch đã dạy</span>
</div>
<div class="flex items-center gap-2">
<span class="w-3 h-3 rounded-full bg-primary-fixed border border-primary"></span>
<span class="text-label-sm text-secondary font-bold">Lịch sắp tới</span>
</div>
<div class="flex items-center gap-2">
<span class="w-3 h-3 rounded-full bg-orange-100 border border-orange-500"></span>
<span class="text-label-sm text-secondary font-bold">Chờ xác nhận</span>
</div>
</div>
</div>
<!-- Right Column: Details & Stats -->
<div class="col-span-12 xl:col-span-4 space-y-6">
<!-- Today Detail Panel -->
<div class="tonal-card rounded-xl p-card-padding">
<div class="flex justify-between items-center mb-6">
<h3 class="font-headline-sm text-headline-sm flex items-center gap-2">
<span class="material-symbols-outlined text-primary">assignment_turned_in</span>
                            Chi tiết:
                        </h3>
<span class="text-label-sm font-bold text-primary bg-primary-fixed px-3 py-1 rounded-full">
<?= date('d/m/Y') ?>
</span>
</div>
<div class="space-y-4">


<!-- Upcoming -->
<?php if($homNayData->num_rows > 0){ ?>

<?php while($row = $homNayData->fetch_assoc()){ ?>

<div class="p-4 rounded-xl border">

    <div class="flex justify-between">

        <div>
            <b><?= substr($row['ThoiGianBatDau'],0,5) ?></b>
            -
            <?= substr($row['ThoiGianKetThuc'],0,5) ?>
        </div>

        <span class="bg-primary-fixed px-2 py-1 rounded">
            Hôm nay
        </span>

    </div>

    <h4 class="font-bold mt-2">
        <?= htmlspecialchars($row['TenLop']) ?>
    </h4>

    <p><?= htmlspecialchars($row['TenPhong']) ?></p>

    <p><?= $row['SiSoHienTai'] ?> học viên</p>

</div>

<?php } ?>

<?php }else{ ?>

<div class="p-4 rounded-xl border text-center text-secondary">
    Hôm nay không có lịch giảng dạy
</div>

<?php } ?>

<!-- Pending Request -->
<p class="text-body-sm text-orange-800/80 mb-4 leading-relaxed">Bộ phận học vụ vừa điều phối ca dạy này. Vui lòng xác nhận để hoàn tất lịch trình.</p>
<div class="flex gap-2">
<form action="/quanlytrungtam/giaodiengiaovien/xacnhanlichday.php" method="POST">          
    <input type="hidden"       
name="MaBuoiHoc"
           value="BH001">

    <button type="submit"
        class="flex-1 bg-white border border-orange-500 text-orange-700 px-4 py-2 rounded-lg text-label-sm font-bold hover:bg-orange-100 transition-colors">
        Xác nhận lịch
    </button>
</form>

<form action="/quanlytrungtam/giaodiengiaovien/tuchoilichday.php" method="POST">         
        <input type="hidden"
           name="MaBuoiHoc"
           value="BH001">

    <button type="submit"
        class="px-4 py-2 text-secondary font-bold text-label-sm hover:text-error transition-colors">
        Từ chối
    </button>
</form>
</div>
</div>
</div>
</div>
<!-- Monthly Stats Widget -->


</div>
</div>
</main>

<div id="notifyBox"
class="hidden fixed top-20 right-6 w-96 bg-white rounded-2xl shadow-2xl border z-50">

    <div class="p-4 border-b">
        <h3 class="font-bold text-lg">
            🔔 Thông báo
        </h3>
    </div>

    <div class="max-h-80 overflow-y-auto">

        <div class="p-4 hover:bg-gray-50 border-b">
            <p class="font-semibold">
                Lịch dạy mới
            </p>
            <p class="text-sm text-gray-500">
                Bạn có lớp TOEIC 700+ B lúc 18:00
            </p>
        </div>

        <div class="p-4 hover:bg-gray-50 border-b">
            <p class="font-semibold">
                Điểm danh
            </p>
            <p class="text-sm text-gray-500">
                Có 2 học viên vắng học hôm nay
            </p>
        </div>

    </div>

</div>
<div id="helpBox"
class="hidden fixed inset-0 bg-black/40 z-50 flex items-center justify-center">

<div class="bg-white w-[600px] rounded-2xl p-6">

<h2 class="text-2xl font-bold mb-4">
📘 Hướng dẫn sử dụng
</h2>

<ul class="space-y-3">

<li>
✅ Quản lý lớp học
</li>

<li>
✅ Điểm danh học viên
</li>

<li>
✅ Nhập điểm số
</li>

<li>
✅ Gửi email thông báo
</li>

<li>
✅ Xuất PDF điểm danh
</li>

</ul>

<div class="text-right mt-6">

<button onclick="toggleHelp()"
class="px-4 py-2 bg-red-600 text-white rounded-xl">

Đóng

</button>

</div>

</div>

</div>
<script>

function toggleNotify(){
    document
        .getElementById("notifyBox")
        .classList.toggle("hidden");
}

function toggleHelp(){
    document
        .getElementById("helpBox")
        .classList.toggle("hidden");
}

window.onclick = function(e){

    const help = document.getElementById("helpBox");

    if(e.target === help){
        help.classList.add("hidden");
    }
}

</script>
</body></html>

