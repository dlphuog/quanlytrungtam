<?php 
// 1. KẾT NỐI DATABASE
$conn = new mysqli("localhost", "root", "", "quanlytrungtam");
if ($conn->connect_error) {
    die("Kết nối Database thất bại: " . $conn->connect_error);
}
// Ép kiểu mã hóa kết nối nhận diện tiếng Việt chính xác
$conn->set_charset("utf8mb4");

// 2. LẤY BỘ LỌC TỪ URL (?ma_lop=...)
$ma_lop = isset($_GET['ma_lop']) ? $_GET['ma_lop'] : 'all';

// Xây dựng điều kiện lọc WHERE dựa trên thiết kế mức vật lý (d.MaLopHoc)
$where_str = "";
if ($ma_lop !== 'all') {
    $where_str = "WHERE d.MaLopHoc = '" . $conn->real_escape_string($ma_lop) . "'";
}

// 3. TRUY VẤN SỐ LIỆU TỔNG QUAN (KPIs)
$sql_kpi = "SELECT 
                AVG(d.DiemSo) as DiemTB,
                COUNT(DISTINCT d.MaHocVien) as TongHV,
                SUM(CASE WHEN d.DiemSo >= 5.0 THEN 1 ELSE 0 END) as SoHVDat,
                SUM(CASE WHEN d.DiemSo >= 9.0 THEN 1 ELSE 0 END) as SoHVXuatSac
            FROM Ketquahoctap d
            $where_str";

$res_kpi = $conn->query($sql_kpi);

// BẪY KIỂM TRA LỖI: Nếu câu lệnh SQL lỗi, in ngay lý do ra màn hình để xử lý
if (!$res_kpi) {
    die("Lỗi truy vấn SQL tại Thẻ số liệu KPI: " . $conn->error . "<br>Vui lòng kiểm tra lại chính xác tên bảng 'Kết quả học tập' trong CSDL PHPMyAdmin!");
}
$kpi = $res_kpi->fetch_assoc();

$diem_tb_lop = isset($kpi['DiemTB']) ? round($kpi['DiemTB'], 1) : 0;
$tong_hv = $kpi['TongHV'] ?? 0;
$ty_le_dat = $tong_hv > 0 ? round(($kpi['SoHVDat'] / $tong_hv) * 100) : 0;
$hv_xuat_sac = $kpi['SoHVXuatSac'] ?? 0;

// 4. TRUY VẤN THỐNG KÊ PHỔ ĐIỂM
$sql_pho_diem = "SELECT 
                    SUM(CASE WHEN d.DiemSo < 5 THEN 1 ELSE 0 END) as moc_yeu,
                    SUM(CASE WHEN d.DiemSo >= 5 AND d.DiemSo < 6.5 THEN 1 ELSE 0 END) as moc_tb,
                    SUM(CASE WHEN d.DiemSo >= 6.5 AND d.DiemSo < 8.0 THEN 1 ELSE 0 END) as moc_kha,
                    SUM(CASE WHEN d.DiemSo >= 8.0 THEN 1 ELSE 0 END) as moc_gioi
                 FROM ketquahoctap d
                 $where_str";
$res_pho = $conn->query($sql_pho_diem);
if (!$res_pho) {
    die("Lỗi truy vấn SQL tại Phổ điểm: " . $conn->error);
}
$pho = $res_pho->fetch_assoc();

$tong_phodiem = max(1, (($pho['moc_yeu'] ?? 0) + ($pho['moc_tb'] ?? 0) + ($pho['moc_kha'] ?? 0) + ($pho['moc_gioi'] ?? 0)));
$p_yeu = round((($pho['moc_yeu'] ?? 0) / $tong_phodiem) * 100, 1);
$p_tb = round((($pho['moc_tb'] ?? 0) / $tong_phodiem) * 100, 1);
$p_kha = round((($pho['moc_kha'] ?? 0) / $tong_phodiem) * 100, 1);
$p_gioi = round((($pho['moc_gioi'] ?? 0) / $tong_phodiem) * 100, 1);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Cài đặt - Athena Admin</title>
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
                        "2xl": "1rem",
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
        .tab-content { display: none; }
        .tab-content.active { display: block; }
        .inner-nav-active { background-color: #ffdad6; color: #93000e; font-weight: 700; }
    </style>
</head>
<body class="text-on-surface">

<aside class="h-full w-64 fixed left-0 top-0 bg-white border-r border-outline-variant flex flex-col z-50">
    <div class="px-gutter pt-10 pb-6 flex flex-col">
        <img alt="Athena Admin Logo" class="w-20 h-auto mx-auto mb-4" src="https://lh3.googleusercontent.com/aida-public/AB6AXuA9uCEtRrfZi1OYmIF4rJvGQQKWpBcvDCR7wvV_WolGNAy0NfYY-Ug0EfTJjgQOAOaz0PyjhED_RU8ur7rZnigd-zQ_Lm2fPJWPFEuGCLWOFezOf6yu6TZWDJReGL30qRwqQxN9JC2EjxqqptcRQxQFDs9Dp0GDfXMHOOl1k3OIK7dETtW7ywhvmvf-UHS8jC82WmCv79uJALyb6wrznTjfnu2-xR-4oafSZSybfAc8qqmLckg40qlrKXwe6E5mHkykVoRQKgVZr1g">
        <h1 class="font-headline-md text-[20px] font-bold text-primary leading-none text-center">Athena Admin</h1>
    </div>

    <nav class="flex-1 overflow-y-auto py-2 px-4 space-y-0.5 scrollbar-hide">
        <a class="flex items-center gap-3 px-4 py-2.5 text-secondary hover:text-primary hover:bg-primary/5 rounded-lg transition-colors" href="dashboard.php">
            <span class="material-symbols-outlined text-[20px]">dashboard</span>
            <span class="font-label-md text-label-md">Dashboard</span>
        </a>
        <a class="flex items-center gap-3 px-4 py-2.5 text-secondary hover:text-primary hover:bg-primary/5 rounded-lg transition-colors" href="hocvien.php">
            <span class="material-symbols-outlined text-[20px]">group</span>
            <span class="font-label-md text-label-md">Học viên</span>
        </a>
        <a class="flex items-center gap-3 px-4 py-2.5 text-secondary hover:text-primary hover:bg-primary/5 rounded-lg transition-colors" href="lophoc.php">
            <span class="material-symbols-outlined text-[20px]">school</span>
            <span class="font-label-md text-label-md">Lớp học</span>
        </a>
        <a class="flex items-center gap-3 px-4 py-2.5 text-secondary hover:text-primary hover:bg-primary/5 rounded-lg transition-colors" href="giaovien.php">
            <span class="material-symbols-outlined text-[20px]">record_voice_over</span>
            <span class="font-label-md text-label-md">Giáo viên</span>
        </a>
        <a class="flex items-center gap-3 px-4 py-2.5 text-secondary hover:text-primary hover:bg-primary/5 rounded-lg transition-colors" href="thoikhoabieu.php">
            <span class="material-symbols-outlined text-[20px]">calendar_today</span>
            <span class="font-label-md text-label-md">Thời khóa biểu</span>
        </a>
        <a class="flex items-center gap-3 px-4 py-2.5 text-secondary hover:text-primary hover:bg-primary/5 rounded-lg transition-colors" href="diemdanh.php">
            <span class="material-symbols-outlined text-[20px]">fact_check</span>
            <span class="font-label-md text-label-md">Điểm danh</span>
        </a>
        <a class="flex items-center gap-3 px-4 py-2.5 bg-primary/10 text-primary rounded-lg transition-colors" href="KETQUAHOCTAP.php">
            <span class="material-symbols-outlined text-[20px]">analytics</span>
            <span class="font-label-md text-label-md">Kết quả học tập</span>
        </a>
        <a class="flex items-center gap-3 px-4 py-2.5 text-secondary hover:text-primary hover:bg-primary/5 rounded-lg transition-colors" href="baocao.php">
            <span class="material-symbols-outlined text-[20px]">assessment</span>
            <span class="font-label-md text-label-md">Báo cáo</span>
        </a>
        <a class="flex items-center gap-3 px-4 py-2.5 text-secondary hover:text-primary hover:bg-primary/5 rounded-lg transition-colors" href="caidat.php">
            <span class="material-symbols-outlined text-[20px]">settings</span>
            <span class="font-label-md text-label-md">Cài đặt</span>
        </a>
    </nav>

    <div class="p-6">
        <div class="flex items-center justify-center gap-3 bg-surface-container-low/30 py-3 rounded-xl">
            <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center text-[10px] font-bold text-white">AD</div>
            <div class="flex items-center gap-2">
                <p class="font-label-md text-label-md text-on-surface font-semibold">Admin User</p>
                <button class="text-secondary hover:text-primary transition-colors flex items-center">
                    <span class="material-symbols-outlined text-[18px]">logout</span>
                </button>
            </div>
        </div>
    </div>
</aside>
<main class="ml-64 min-h-screen flex flex-col">
<header class="flex justify-between items-center h-16 px-gutter sticky top-0 z-40 bg-surface/80 backdrop-blur-md border-b border-surface-container-highest">
<div class="flex items-center"><h2 class="font-headline-md text-headline-md font-bold text-on-surface">Kết quả học tập</h2></div>
</header>
<div class="p-gutter flex flex-col gap-8 max-w-[1280px] mx-auto w-full">
<section class="bg-white p-4 rounded-2xl border border-surface-container-high shadow-sm w-max">
    <div class="flex items-center gap-4">
        <form method="GET" action="ketquahoctap.php" id="lopFilterForm" class="flex items-center gap-4 m-0">
            <div class="flex w-64 bg-surface-container-lowest border border-outline-variant rounded-lg p-1 items-center gap-2">
                <span class="font-label-md text-on-surface-variant px-2 text-sm font-medium whitespace-nowrap">Lớp học:</span>
                <select name="ma_lop" onchange="document.getElementById('lopFilterForm').submit();" class="bg-transparent border-none focus:ring-0 font-label-md text-primary font-semibold cursor-pointer py-1 pr-8 pl-1 w-full text-sm">
                    <option value="all" <?= $ma_lop == 'all' ? 'selected' : '' ?>>Tất cả lớp học</option>
                    <?php 
                    $res_lop = $conn->query("SELECT MaLopHoc, TenLop FROM lophoc");
                    if ($res_lop) {
                        while($lop = $res_lop->fetch_assoc()) {
                            $sel = ($ma_lop == $lop['MaLopHoc']) ? 'selected' : '';
                            echo "<option value='{$lop['MaLopHoc']}' {$sel}>" . htmlspecialchars($lop['TenLop']) . "</option>";
                        }
                    }
                    ?>
                </select>
            </div>
        </form>
    </div>
</section>

<section class="grid grid-cols-1 md:grid-cols-3 gap-6">
<div class="bg-white p-6 rounded-2xl border border-surface-container-high shadow-sm flex items-center gap-4">
<div class="p-3 bg-secondary/10 rounded-xl text-secondary"><span class="material-symbols-outlined text-[24px]">analytics</span></div>
<div>
<p class="text-[10px] text-secondary font-bold uppercase tracking-wider mb-1">Điểm TB cả lớp</p>
<span class="font-headline-lg text-[32px] font-extrabold text-on-surface"><?= $diem_tb_lop ?></span>
</div>
</div>
<div class="bg-white p-6 rounded-2xl border border-surface-container-high shadow-sm flex items-center gap-4">
<div class="p-3 bg-green-100 text-green-700"><span class="material-symbols-outlined text-[24px]">check_circle</span></div>
<div>
<p class="text-[10px] text-secondary font-bold uppercase tracking-wider mb-1">Tỷ lệ đạt môn</p>
<span class="font-headline-lg text-[32px] font-extrabold text-on-surface"><?= $ty_le_dat ?>%</span>
</div>
</div>
<div class="bg-primary p-6 rounded-2xl shadow-lg text-on-primary flex items-center gap-4">
<div class="p-3 bg-white/20 rounded-xl"><span class="material-symbols-outlined text-[24px] text-white">military_tech</span></div>
<div>
<p class="text-[10px] text-white/70 font-bold uppercase tracking-wider mb-1">Học viên xuất sắc (>=9.0)</p>
<span class="font-headline-lg text-[32px] font-extrabold text-white"><?= $hv_xuat_sac ?> <span class="text-xs font-medium text-white/80">Học viên</span></span>
</div>
</div>
</section>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
<section class="lg:col-span-2 bg-white rounded-3xl border border-surface-container-high shadow-sm overflow-hidden flex flex-col">
<div class="px-8 py-5 border-b border-surface-container-low flex justify-between items-center">
<h3 class="font-headline-md text-xl font-bold text-on-surface">Danh sách điểm số</h3>
</div>
<div class="overflow-x-auto">
<table class="w-full text-left border-collapse">
<thead>
<tr class="bg-surface-container-low/20 text-secondary border-b border-surface-container-low">
<th class="pl-8 pr-4 py-5 font-bold text-[11px] uppercase tracking-wider">Mã HV</th>
<th class="px-4 py-5 font-bold text-[11px] uppercase tracking-wider">Họ tên</th>
<th class="px-4 py-5 font-bold text-[11px] uppercase tracking-wider text-center">Loại KT</th>
<th class="px-4 py-5 font-bold text-[11px] uppercase tracking-wider text-center">Ngày KT</th>
<th class="px-4 py-5 font-bold text-[11px] uppercase tracking-wider text-center">Điểm số</th>
<th class="pl-4 pr-8 py-5 font-bold text-[11px] uppercase tracking-wider text-right">Trạng thái</th>
</tr>
</thead>
<tbody class="divide-y divide-surface-container-low/30">
<?php 
$sql_list = "SELECT 
                d.MaHocVien, 
                n.HoTen, 
                d.DiemSo,
                d.LoaiKiemTra,
                d.NgayKiemTra
             FROM ketquahoctap d
             LEFT JOIN hocvien hv ON d.MaHocVien = hv.MaHocVien
             LEFT JOIN taikhoan tk ON hv.MaTaiKhoan = tk.MaTaiKhoan
             LEFT JOIN nguoidung n ON tk.MaNguoiDung = n.MaNguoiDung
             $where_str";
$res_list = $conn->query($sql_list);

if ($res_list && $res_list->num_rows > 0) {
    while($row = $res_list->fetch_assoc()) {
        $score = $row['DiemSo'];
        if ($score >= 8.0) {
            $st_text = "Giỏi"; $st_class = "bg-green-50 text-green-700";
        } elseif ($score >= 5.0) {
            $st_text = "Đạt"; $st_class = "bg-blue-50 text-tertiary";
        } else {
            $st_text = "Yếu"; $st_class = "bg-red-50 text-error";
        }
?>
<tr class="hover:bg-primary/5 transition-colors cursor-pointer">
<td class="pl-8 pr-4 py-5 font-bold text-on-surface text-sm"><?= htmlspecialchars($row['MaHocVien']) ?></td>
<td class="px-4 py-5 text-sm font-medium text-on-surface"><?= htmlspecialchars($row['HoTen'] ?? 'Học viên trung tâm') ?></td>
<td class="px-4 py-5 text-sm font-medium text-secondary text-center"><?= htmlspecialchars($row['LoaiKiemTra'] ?? 'Định kỳ') ?></td>
<td class="px-4 py-5 text-sm font-medium text-secondary text-center"><?= $row['NgayKiemTra'] ?></td>
<td class="px-4 py-5 text-sm font-bold text-primary text-center"><?= $score ?></td>
<td class="pl-4 pr-8 py-5 text-right">
<span class="px-4 py-1.5 <?= $st_class ?> rounded-full text-[11px] font-bold uppercase tracking-wider"><?= $st_text ?></span>
</td>
</tr>
<?php 
    }
} else {
    echo "<tr><td colspan='6' class='text-center py-8 text-secondary text-sm'>Không tìm thấy dữ liệu điểm phù hợp cho lớp này.</td></tr>";
}
?>
</tbody>
</table>
</div>
</section>

<div class="flex flex-col gap-6">
<section class="bg-white p-8 rounded-3xl border border-surface-container-high shadow-sm flex flex-col justify-between">
<div class="mb-6"><h3 class="font-headline-md text-xl font-bold text-on-surface">Phổ điểm học phần</h3></div>
<div class="flex flex-col gap-5">
<div>
<div class="flex justify-between items-center text-xs font-bold text-on-surface mb-2"><span>Giỏi / Xuất sắc (>= 8.0)</span><span><?= $p_gioi ?>%</span></div>
<div class="w-full bg-surface-container rounded-full h-2.5"><div class="bg-primary h-2.5 rounded-full" style="width: <?= $p_gioi ?>%"></div></div>
</div>
<div>
<div class="flex justify-between items-center text-xs font-bold text-on-surface mb-2"><span>Khá (6.5 - 7.9)</span><span><?= $p_kha ?>%</span></div>
<div class="w-full bg-surface-container rounded-full h-2.5"><div class="bg-primary/70 h-2.5 rounded-full" style="width: <?= $p_kha ?>%"></div></div>
</div>
<div>
<div class="flex justify-between items-center text-xs font-bold text-on-surface mb-2"><span>Trung bình (5.0 - 6.4)</span><span><?= $p_tb ?>%</span></div>
<div class="w-full bg-surface-container rounded-full h-2.5"><div class="bg-secondary h-2.5 rounded-full" style="width: <?= $p_tb ?>%"></div></div>
</div>
<div>
<div class="flex justify-between items-center text-xs font-bold text-on-surface mb-2"><span>Yếu (< 5.0)</span><span><?= $p_yeu ?>%</span></div>
<div class="w-full bg-surface-container rounded-full h-2.5"><div class="bg-surface-container-high h-2.5 rounded-full" style="width: <?= $p_yeu ?>%"></div></div>
</div>
</div>
</section>

<section class="bg-white p-8 rounded-3xl border border-surface-container-high shadow-sm">
<h3 class="font-headline-md text-xl font-bold text-on-surface mb-6">Học viên tiêu biểu</h3>
<div class="flex flex-col gap-5">
<?php 
$sql_top = "SELECT 
                d.MaHocVien, 
                n.HoTen, 
                d.DiemSo as GpaTB
            FROM ketquahoctap d
            LEFT JOIN hocvien hv ON d.MaHocVien = hv.MaHocVien
            LEFT JOIN taikhoan tk ON hv.MaTaiKhoan = tk.MaTaiKhoan
            LEFT JOIN nguoidung n ON tk.MaNguoiDung = n.MaNguoiDung
            $where_str
            ORDER BY GpaTB DESC
            LIMIT 2";
$res_top = $conn->query($sql_top);
$rank = 1;

if ($res_top && $res_top->num_rows > 0) {
    while($top = $res_top->fetch_assoc()) {
        $short_name = implode('', array_map(function($v) { return mb_substr($v, 0, 1, 'UTF-8'); }, explode(' ', $top['HoTen'])));
        $bg_avatar = ($rank == 1) ? 'bg-primary' : 'bg-secondary';
?>
<div class="flex items-center gap-3">
<span class="font-extrabold text-primary italic text-lg"><?= $rank ?>.</span>
<div class="w-10 h-10 rounded-full <?= $bg_avatar ?> flex items-center justify-center text-white font-bold text-xs shadow-sm">
    <?= htmlspecialchars(mb_strtoupper(mb_substr($short_name, -2, 2, 'UTF-8'))) ?>
</div>
<div>
<p class="font-label-md text-sm font-semibold text-on-surface"><?= htmlspecialchars($top['HoTen']) ?></p>
<p class="text-[11px] text-secondary font-bold mt-0.5">Điểm: <?= $top['GpaTB'] ?></p>
</div>
</div>
<?php 
        $rank++;
    }
}
?>
</div>
</section>
</div>
</div>
</div>
</main>
</body>
</html>