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

$sqlGV = "
SELECT nd.HoTen
FROM giaovien gv
INNER JOIN taikhoan tk
    ON gv.MaTaiKhoan = tk.MaTaiKhoan
INNER JOIN nguoidung nd
    ON tk.MaNguoiDung = nd.MaNguoiDung
WHERE gv.MaTaiKhoan = '$maTaiKhoan'
";

$giaovien = $conn->query($sqlGV)->fetch_assoc();

$maBuoiHoc = $_GET['MaBuoiHoc'] ?? 'BH001';
$maLop = $_GET['MaLopHoc'] ?? 'LH001';

$sql = "
SELECT
    hv.MaHocVien,
    nd.HoTen,
    dd.TrangThai,
    dd.GhiChu
FROM buoihoc bh
INNER JOIN chitietlophoc ctl
    ON bh.MaLopHoc = ctl.MaLopHoc
INNER JOIN hocvien hv
    ON ctl.MaHocVien = hv.MaHocVien
INNER JOIN taikhoan tk
    ON hv.MaTaiKhoan = tk.MaTaiKhoan
INNER JOIN nguoidung nd
    ON tk.MaNguoiDung = nd.MaNguoiDung
LEFT JOIN diemdanh dd
    ON dd.MaHocVien = hv.MaHocVien
    AND dd.MaBuoiHoc = bh.MaBuoiHoc
WHERE bh.MaBuoiHoc = '$maBuoiHoc'
";

$result = $conn->query($sql);

$sqlThongKe = "
SELECT
SUM(CASE WHEN TrangThai='Co mat' THEN 1 ELSE 0 END) CoMat,
SUM(CASE WHEN TrangThai='Muon' THEN 1 ELSE 0 END) Muon,
SUM(CASE WHEN TrangThai='Vang' THEN 1 ELSE 0 END) Vang
FROM diemdanh
WHERE MaBuoiHoc='$maBuoiHoc'
";

$thongKe = $conn->query($sqlThongKe)->fetch_assoc();

$sqlBuoiHoc = "
SELECT
bh.NgayHoc,
lh.TenLop
FROM buoihoc bh
INNER JOIN lophoc lh
ON bh.MaLopHoc=lh.MaLopHoc
WHERE bh.MaBuoiHoc='$maBuoiHoc'
";

$buoiHoc = $conn->query($sqlBuoiHoc)->fetch_assoc();

$sqlDSLOP = "
SELECT
    lh.MaLopHoc,
    lh.TenLop
FROM lophoc lh
INNER JOIN giaovien gv
    ON lh.MaGiaoVien = gv.MaGiaoVien
WHERE gv.MaTaiKhoan='$maTaiKhoan'
";

$dsLop = $conn->query($sqlDSLOP);

$sqlDSBuoi = "
SELECT
    MaBuoiHoc,
    NgayHoc
FROM buoihoc
WHERE MaLopHoc='$maLop'
ORDER BY NgayHoc
";

$dsBuoi = $conn->query($sqlDSBuoi);
?>
<!DOCTYPE html><html class="light" lang="vi" style=""><head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>Điểm danh - Athena Teacher</title>
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
        };
    </script>
    <style>
        body { font-family: 'Montserrat', sans-serif; background-color: #f8f9fa; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        .chart-bar-grow { transition: height 1s ease-out; }
        .sidebar-active { background-color: #ffdad6; color: #93000e; font-weight: 700; border-radius: 0.5rem; }
            .sidebar-active .material-symbols-outlined { font-variation-settings: 'FILL' 1, 'wght' 700, 'GRAD' 0, 'opsz' 24; }
            .no-scrollbar::-webkit-scrollbar { display: none; }
            .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        .tonal-card{
            background:#fff;
            border:1px solid #e5e7eb;
            box-shadow:0 1px 3px rgba(0,0,0,.08);
        }
    </style>
</head>
<body class="text-on-surface">
<!-- Fixed SideNavBar - Shell from SCREEN_10 -->
<aside class="fixed left-0 top-0 h-full w-[260px] bg-surface-container-lowest shadow-sm flex flex-col p-4 border-r border-outline-variant z-50">
<div class="px-6 pt-8 pb-4 flex flex-col items-center">          
    <img
src="/quanlytrungtam/logo.jpg"
alt="Athena Logo"
class="w-20 h-20 mx-auto rounded-3xl shadow-2xl">

    <h1 class="mt-4 text-[20px] font-bold text-primary text-center">
        Athena Teacher
</h1>
    </div>
<nav class="flex-1 space-y-2 overflow-y-auto no-scrollbar">
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
<!-- Active state: Điểm danh -->
<a class="flex items-center gap-3 bg-primary-fixed text-primary rounded-lg px-4 py-3 active-nav transition-colors" href="diemdanh.php">
<span class="material-symbols-outlined" data-icon="fact_check" style="font-variation-settings: 'FILL' 1;">fact_check</span>
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
<!-- Main Content Area -->
<!-- Main Canvas -->
<main class="ml-[260px] min-h-screen flex flex-col overflow-y-auto">
<header class="flex justify-between items-center h-16 px-6 sticky top-0 z-40 bg-surface/80 backdrop-blur-md border-b border-surface-container-highest">
    <div class="flex items-center">
        <h2 class="font-headline-md text-headline-md font-bold text-on-surface">
            Điểm danh
        </h2>
    </div>
</header>

<!-- Breadcrumb & Header -->
<div class="p-6">

<div class="flex items-center gap-4 mb-6">
<div class="h-8 w-[1px] bg-outline-variant mx-unit-sm"></div>
<div class="flex gap-3 flex-wrap">

    <!-- Chọn lớp -->
    <div class="relative min-w-[260px]">

        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-primary">
            groups
        </span>

 <select
class="w-64 bg-white border border-gray-200 rounded-xl pl-12 pr-4 py-3 shadow-sm font-medium"
onchange="
location='?MaLopHoc='+this.value
">

            <?php while($lop = $dsLop->fetch_assoc()){ ?>

            <option
                value="<?= $lop['MaLopHoc'] ?>"
                <?= ($maLop==$lop['MaLopHoc'])?'selected':'' ?>>

                <?= htmlspecialchars($lop['TenLop']) ?>

            </option>

            <?php } ?>

        </select>

    </div>

    <!-- Chọn buổi học -->
    <div class="relative min-w-[220px]">

        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-primary">
            event
        </span>

    <select
class="w-64 bg-white border border-gray-200 rounded-xl pl-12 pr-4 py-3 shadow-sm font-medium"
onchange="
location='?MaLopHoc=<?= $maLop ?>&MaBuoiHoc='+this.value
">

            <?php while($buoi = $dsBuoi->fetch_assoc()){ ?>

            <option
                value="<?= $buoi['MaBuoiHoc'] ?>"
                <?= ($maBuoiHoc==$buoi['MaBuoiHoc'])?'selected':'' ?>>

                Buổi ngày
                <?= date('d/m/Y',strtotime($buoi['NgayHoc'])) ?>

            </option>

            <?php } ?>

        </select>

    </div>
</div>

</div>
<!-- Session Statistics (Modular Card Layout like Dashboard) -->
<section class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

    <!-- Card thông tin buổi học -->
    <div class="tonal-card p-6 rounded-xl flex justify-between items-center lg:col-span-2">

        <div>
            <p class="text-xs text-secondary font-bold uppercase mb-2">
                CHI TIẾT BUỔI HỌC
            </p>

            <h3 class="text-3xl font-bold text-primary">
                <?= htmlspecialchars($buoiHoc['TenLop']) ?>
                —
                <?= date('d/m/Y', strtotime($buoiHoc['NgayHoc'])) ?>
            </h3>
        </div>

        <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center">
            <span class="material-symbols-outlined">
                calendar_today
            </span>
        </div>

    </div>

    <!-- Card thống kê -->
    <div class="tonal-card p-6 rounded-xl">

        <p class="text-xs text-secondary font-bold uppercase mb-4">
            THỐNG KÊ ĐIỂM DANH
        </p>

        <div class="flex justify-between items-center">

            <div class="flex gap-8">

                <div>
                    <div class="text-green-600 font-bold text-xl">
                        <?= $thongKe['CoMat'] ?? 0 ?>
                    </div>
                    <div class="text-sm text-gray-500">
                        Hiện diện
                    </div>
                </div>

                <div>
                    <div class="text-amber-500 font-bold text-xl">
                        <?= $thongKe['Muon'] ?? 0 ?>
                    </div>
                    <div class="text-sm text-gray-500">
                        Muộn
                    </div>
                </div>

                <div>
                    <div class="text-red-600 font-bold text-xl">
                        <?= $thongKe['Vang'] ?? 0 ?>
                    </div>
                    <div class="text-sm text-gray-500">
                        Vắng mặt
                    </div>
                </div>

            </div>

            <div class="flex gap-2">

                <a href="xuatdiemdanh.php?MaBuoiHoc=<?= $maBuoiHoc ?>"
                   class="p-2 hover:bg-gray-100 rounded">
                    <span class="material-symbols-outlined">
                        file_download
                    </span>
                </a>

                <a href="guimail.php?MaBuoiHoc=<?= $maBuoiHoc ?>"
                   class="p-2 hover:bg-gray-100 rounded">
                    <span class="material-symbols-outlined">
                        mail
                    </span>
                </a>

            </div>

        </div>

    </div>

</section>
<!-- Student List Table (Elevated White Card) -->
<form action="luudiemdanh.php" method="POST">
<input type="hidden" name="MaBuoiHoc" value="<?= $maBuoiHoc ?>">
<section class="tonal-card rounded-xl overflow-hidden mb-32" style="transform: translateY(0px);">
<div class="overflow-x-auto">
<table class="w-full table-fixed border-collapse text-left">
<colgroup>
    <col class="w-16">
    <col class="w-72">
    <col>
    <col class="w-96">
</colgroup>
<thead>
</thead>
<tbody class="divide-y divide-outline-variant/20">
<?php
$stt = 1;
$tongHocVien = $result->num_rows;
while($row = $result->fetch_assoc()){
?>
<tr class="hover:bg-surface-container-low transition-colors group">

    <td class="py-5 px-6 font-body-md text-on-surface-variant">
        <?= str_pad($stt++,2,"0",STR_PAD_LEFT) ?>
    </td>

    <td class="py-5 px-6">
        <div class="flex items-center gap-4">
            <span class="font-label-md text-on-surface">
                <?= htmlspecialchars($row['HoTen']) ?>
            </span>
        </div>
    </td>

    <td class="py-5 px-6">
        <div class="flex justify-center items-center gap-8">

            <label class="flex items-center gap-2 cursor-pointer">
    <input
        type="radio"
        name="status[<?= $row['MaHocVien'] ?>]"
        value="Co mat"
        <?= ($row['TrangThai']=="Co mat") ? "checked" : "" ?>
    >
    <span>Có mặt</span>
</label>

<label class="flex items-center gap-2 cursor-pointer">
    <input
        type="radio"
        name="status[<?= $row['MaHocVien'] ?>]"
        value="Vang"
        <?= ($row['TrangThai']=="Vang") ? "checked" : "" ?>
    >
    <span>Vắng mặt</span>
</label>

<label class="flex items-center gap-2 cursor-pointer">
    <input
        type="radio"
        name="status[<?= $row['MaHocVien'] ?>]"
        value="Muon"
        <?= ($row['TrangThai']=="Muon") ? "checked" : "" ?>
    >
    <span>Muộn</span>
</label>

        </div>
    </td>

    <td class="py-5 px-6">
        <input
            type="text"
            name="ghichu[<?= $row['MaHocVien'] ?>]"
            value="<?= htmlspecialchars($row['GhiChu']) ?>"
            placeholder="Thêm ghi chú..."
            class="w-full bg-transparent border-b border-outline-variant/30 focus:border-primary py-1 font-body-sm text-on-surface focus:outline-none transition-colors">
    </td>

</tr>
<?php } ?>
</tbody>
</table>
</div>
</section>
<!-- Sticky Bottom Action Bar -->
<div class="fixed bottom-0 left-[260px] right-0
            h-20 bg-white
            border-t border-gray-200
            flex items-center justify-between
            px-6 z-40">
    <div class="flex items-center gap-2 text-gray-500 flex-shrink-0">
<span class="material-symbols-outlined text-sm" data-icon="info">info</span>
<span class="font-label-sm">Tự động lưu lúc 10:45 • Tổng: <span class="font-bold text-on-surface"><?= $tongHocVien ?></span></span>
</div>
<div class="flex gap-3 ml-auto">
<button
    type="button"
    onclick="location.reload()"
<button
type="submit"
class="px-8 py-3 rounded-xl
bg-primary
text-white
font-semibold
shadow-md
hover:opacity-90">
    Hủy thay đổi
</button>
<button
type="submit"
class="px-8 py-3 rounded-xl
bg-primary
text-white
font-semibold
shadow-md
hover:opacity-90">
    <span class="material-symbols-outlined text-sm">save</span>
    Lưu điểm danh
</button>
</div>
</div>
</form>
</div>
</main>
<script>
        // Micro-interactions for radio buttons to highlight rows
      document.querySelectorAll('input[type="radio"]').forEach(radio => {

    radio.addEventListener('change', (e) => {

        const row = e.target.closest('tr');

        row.classList.remove(
            'bg-red-50',
            'bg-green-50',
            'bg-amber-50'
        );

        if(e.target.value === 'Co mat'){
            row.classList.add('bg-green-50');
        }

        if(e.target.value === 'Vang'){
            row.classList.add('bg-red-50');
        }

        if(e.target.value === 'Muon'){
            row.classList.add('bg-amber-50');
        }

    });

});

        // Hover effects for cards
        document.querySelectorAll('.tonal-card').forEach(card => {
            card.addEventListener('mousedown', () => {
                card.style.transform = 'scale(0.99)';
            });
            card.addEventListener('mouseup', () => {
                card.style.transform = 'translateY(0)';
            });
        });
    </script>


</body></html>