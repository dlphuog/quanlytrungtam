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

if($conn->connect_error){
    die("Lỗi kết nối");
}

$sqlLop = "
SELECT
    lh.MaLopHoc,
    lh.TenLop,
    lh.SoBuoiDaHoc,
    kh.TongSoBuoi,
COUNT(DISTINCT ctl.MaHocVien) AS SiSoThucTe
FROM lophoc lh

INNER JOIN giaovien gv
    ON lh.MaGiaoVien = gv.MaGiaoVien

LEFT JOIN khoahoc kh
    ON lh.MaKhoaHoc = kh.MaKhoaHoc

LEFT JOIN lichhocchitiet lct
    ON lh.MaLopHoc = lct.MaLopHoc

LEFT JOIN chitietlophoc ctl
ON lh.MaLopHoc = ctl.MaLopHoc

WHERE gv.MaTaiKhoan='$maTaiKhoan'

GROUP BY
    lh.MaLopHoc,
    lh.TenLop,
    lh.SoBuoiDaHoc,
    kh.TongSoBuoi,
    lct.ThuTrongTuan,
    lct.ThoiGianBatDau,
    lct.ThoiGianKetThuc
";

$resultLop = $conn->query($sqlLop);
$soLop = $resultLop->num_rows;

$dsLop = [];
while($row = $resultLop->fetch_assoc()){
    $dsLop[] = $row;
}

$soLop = count($dsLop);
$maLop = $_GET['lop'] ?? ($dsLop[0]['MaLopHoc'] ?? null);

$resultHV = null;
$soHocVien = 0;

if($maLop){

    $sqlHV = "
SELECT
    hv.MaHocVien,
    nd.HoTen,
    nd.SDT,

    AVG(kq.DiemSo) AS DiemTB,

    ROUND(
        (
            SUM(
                CASE
                    WHEN dd.TrangThai='Co mat'
                    THEN 1
                    ELSE 0
                END
            )
            /
            NULLIF(COUNT(dd.MaBuoiHoc),0)
        ) * 100
    ,0) AS ChuyenCan

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

LEFT JOIN diemdanh dd
    ON hv.MaHocVien = dd.MaHocVien
    AND dd.MaBuoiHoc IN (
        SELECT MaBuoiHoc
        FROM buoihoc
        WHERE MaLopHoc = ctl.MaLopHoc
    )

WHERE ctl.MaLopHoc='$maLop'

GROUP BY
    hv.MaHocVien,
    nd.HoTen,
    nd.SDT
";

$resultHV = null;
$soHocVien = 0;

if($maLop){

    $resultHV = $conn->query($sqlHV);

    if($resultHV){
        $soHocVien = $resultHV->num_rows;
    }
}

    $resultHV = $conn->query($sqlHV);

    $soHocVien = $resultHV->num_rows;
}
?>

<!DOCTYPE html>

<html class="light" lang="vi"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Athena Teacher - Quản lý lớp học</title>
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
<a class="flex items-center gap-3 text-secondary hover:bg-surface-container rounded-lg px-4 py-3 transition-colors" href="lichgiangday.php">
<span class="material-symbols-outlined" data-icon="calendar_month">calendar_month</span>
<span class="font-label-md text-label-md">Lịch giảng dạy</span>
</a>
<!-- Active: Quản lý lớp học -->
<a class="flex items-center gap-3 bg-primary-fixed text-primary rounded-lg px-4 py-3 active-nav transition-colors" href="quanlylophoc.php">
<span class="material-symbols-outlined" data-icon="groups" style="font-variation-settings: 'FILL' 1;">groups</span>
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
<main class="ml-[260px] min-h-screen overflow-y-auto">  
  <header class ="bg-surface-container border-b border-outline-variant/30">
  <div class="max-w-7xl mx-auto px-8 py-6">
<div class="flex items-center">
        <h2 class="font-headline-md text-headline-md font-bold text-on-surface">
           Quản lý lớp học
        </h2>
    </div>
</div>
</header>
<!-- Main Content Canvas -->
<!-- Section Header -->
<div class="p-6">

    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
<div>
<p class="text-body-md text-secondary">Theo dõi tiến độ và quản lý thông tin các lớp học đang phụ trách.</p>
</div>
</div>
<div class="max-w-7xl mx-auto grid grid-cols-12 gap-6">
<!-- Left: Class List -->
<section class="col-span-12 lg:col-span-5 space-y-4">
<div class="flex items-center justify-between mb-2">
<h3 class="font-headline-sm text-headline-sm flex items-center gap-2">
<span class="material-symbols-outlined text-primary" data-icon="list">list</span>
                        Lớp học đang dạy
                    </h3>
<span class="bg-primary/10 text-primary text-label-sm font-bold px-2 py-0.5 rounded-full"><?= $soLop ?> lớp</span>
</div>
<!-- Selected Class Card -->
<?php foreach($dsLop as $lop){ ?>

<div class="tonal-card rounded-xl p-card-padding mb-3"
style="<?= $maLop == $lop['MaLopHoc']
    ? 'border:2px solid #dc2626;'
    : '' ?>">

    <h4 class="font-bold">
        <?= $lop['TenLop'] ?>
    </h4>

    <p>
        Sĩ số:
        <?= $lop['SiSoThucTe'] ?>
    </p>

    <p>
        Tiến độ:
        <?= $lop['SoBuoiDaHoc'] ?>
        /
        <?= $lop['TongSoBuoi'] ?>
        buổi
    </p>

    <a
        href="?lop=<?= $lop['MaLopHoc'] ?>"
        class="text-red-600 font-bold">
        Xem chi tiết
    </a>

</div>

<?php } ?>
</section>
<!-- Right: Class Detail View -->
<section class="col-span-12 lg:col-span-7">
<div class="tonal-card rounded-xl overflow-hidden flex flex-col h-full min-h-[600px]">
<!-- Detail Header -->
<div class="p-card-padding border-b border-outline-variant/30 flex justify-between items-center bg-surface-container-low/20">
<div>
<div class="flex items-center gap-2 mb-1">
<h3 class="font-headline-sm text-headline-sm">Chi tiết lớp học</h3>
</div>
<?php
$sqlTenLop = "
SELECT TenLop
FROM lophoc
WHERE MaLopHoc='$maLop'
";

$tenLop = $conn->query($sqlTenLop)->fetch_assoc();
?>

<p class="text-label-md text-primary font-bold">
<?= $tenLop['TenLop'] ?? 'Chưa chọn lớp' ?>
<?= $maLop ? "($maLop)" : '' ?>
</p>
</div>
</div>
<!-- Students List Table -->
<div class="p-card-padding flex-1 overflow-auto">
<div class="flex items-center justify-between mb-4">
<h4 class="font-label-md text-label-md text-secondary uppercase tracking-widest">Danh sách học viên (<?= $soHocVien ?>)</h4>
<button class="p-2 hover:bg-surface-container rounded-full transition-all">
<span class="material-symbols-outlined text-secondary">filter_list</span>
</button>
</div>
<table class="w-full text-left border-collapse">
<thead>
<tr class="text-label-sm text-secondary border-b border-outline-variant/30">
<th class="py-3 font-semibold">STT</th>
<th class="py-3 font-semibold">Học viên</th>
<th class="py-3 font-semibold">Số điện thoại</th>
<th class="py-3 font-semibold text-right">Chuyên cần</th>
<th class="py-3 font-semibold text-right">Điểm TB</th>
</tr>
</thead>
<tbody class="divide-y divide-outline-variant/10">

<?php
$stt = 1;

if($resultHV){
    while($hv = $resultHV->fetch_assoc()){
?>
<tr>

    <td class="py-4">
        <?= $stt++ ?>
    </td>

    <td class="py-4">
        <?= htmlspecialchars($hv['HoTen']) ?>
    </td>

    <td class="py-4">
        <?= htmlspecialchars($hv['SDT']) ?>
    </td>

    <td class="py-4 text-right font-bold">
        <?= $hv['ChuyenCan'] ?? 0 ?>%
    </td>

    <td class="py-4 text-right">
        <?= $hv['DiemTB']
            ? number_format($hv['DiemTB'],1)
            : '-' ?>
    </td>

</tr>

<?php } 
}
?>

</tbody>
</table>
</div>
<!-- Detail Footer -->


</div>
</section>
</div>
</div>
</div>
</main>
<script>
        // Micro-interactions for cards
        document.querySelectorAll('.tonal-card').forEach(card => {
            card.addEventListener('mousedown', () => {
                card.style.transform = 'scale(0.98)';
            });
            card.addEventListener('mouseup', () => {
                if (!card.classList.contains('border-primary/20')) {
                    card.style.transform = 'translateY(-2px)';
                } else {
                    card.style.transform = 'none';
                }
            });
        });
    </script>
</body></html>

