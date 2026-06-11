<?php 
// File: baocao.php
$conn = new mysqli("localhost", "root", "", "quanlytrungtam");
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

$nam_hien_tai = 2026;

// Lấy tháng cần lọc từ Dropdown URL (?thang_loc=...)
$thang_loc = isset($_GET['thang_loc']) ? $_GET['thang_loc'] : 'all';

// Khởi tạo các biến số lượng mặc định
$tong_hoc_vien = 0;
$lop_dang_chay = 0;
$tong_giao_vien = 0;

// Xây dựng điều kiện lọc thời gian theo cột NgayBatDau của bảng LopHoc
if ($thang_loc == 'all') {
    // Nếu xem cả năm 2026
    $where_condition = "WHERE YEAR(l.NgayBatDau) = $nam_hien_tai";
} else {
    // Nếu lọc theo tháng cụ thể trong năm 2026
    $thang_so = intval($thang_loc);
    $where_condition = "WHERE YEAR(l.NgayBatDau) = $nam_hien_tai AND MONTH(l.NgayBatDau) = $thang_so";
}

// =========================================================
// 1. TRUY VẤN TỔNG SỐ HỌC VIÊN THAM GIA HỌC
// =========================================================
$sql_tong_hv = "SELECT COUNT(DISTINCT ctl.MaHocVien) as total 
                FROM ChiTietLopHoc ctl
                INNER JOIN LopHoc l ON ctl.MaLopHoc = l.MaLopHoc 
                $where_condition";

$res_tong_hv = $conn->query($sql_tong_hv);
if ($res_tong_hv === false) {
    die("Lỗi đếm Học viên: " . $conn->error);
}
$row_tong_hv = $res_tong_hv->fetch_assoc();
$tong_hoc_vien = $row_tong_hv['total'] ?? 0;

// =========================================================
// 2. TRUY VẤN TỔNG SỐ LỚP HỌC MỞ TRONG THỜI GIAN LỌC
// =========================================================
$sql_lop_act = "SELECT COUNT(*) as total FROM LopHoc l $where_condition";

$res_lop_act = $conn->query($sql_lop_act);
if ($res_lop_act === false) {
    die("Lỗi đếm Lớp học: " . $conn->error);
}
$row_lop_act = $res_lop_act->fetch_assoc();
$lop_dang_chay = $row_lop_act['total'] ?? 0;

// =========================================================
// 3. TRUY VẤN TỔNG SỐ GIÁO VIÊN THAM GIA GIẢNG DẠY
// =========================================================
$sql_gv = "SELECT COUNT(DISTINCT l.MaGiaoVien) as total FROM LopHoc l $where_condition";

$res_gv = $conn->query($sql_gv);
if ($res_gv === false) {
    die("Lỗi đếm Giáo viên: " . $conn->error);
}
$row_gv = $res_gv->fetch_assoc();
$tong_giao_vien = $row_gv['total'] ?? 0;
// =========================================================
// 4. TRUY VẤN PHÂN BỔ HỌC VIÊN THEO KHÓA HỌC (CHO BIỂU ĐỒ)
// =========================================================
$sql_phando = "SELECT kh.TenKhoaHoc, COUNT(DISTINCT ctl.MaHocVien) as SoLuong
               FROM KhoaHoc kh
               INNER JOIN LopHoc l ON kh.MaKhoaHoc = l.MaKhoaHoc
               LEFT JOIN ChiTietLopHoc ctl ON l.MaLopHoc = ctl.MaLopHoc
               $where_condition
               GROUP BY kh.MaKhoaHoc";

$res_phando = $conn->query($sql_phando);
$data_chart = [];
$tong_so_trong_chart = 0;

if ($res_phando && $res_phando->num_rows > 0) {
    while ($row = $res_phando->fetch_assoc()) {
        $data_chart[] = $row;
        $tong_so_trong_chart += $row['SoLuong'];
    }
}

// Danh sách mã màu đại diện cho các khóa học trên biểu đồ
$colors = ['#bb0025', '#005ac2', '#e67e22', '#2ecc71', '#9b59b6', '#34495e'];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Báo cáo - Athena Admin</title>
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
        <a class="flex items-center gap-3 px-4 py-2.5 text-secondary hover:text-primary hover:bg-primary/5 rounded-lg transition-colors" href="ketquahoctap.php">
            <span class="material-symbols-outlined text-[20px]">analytics</span>
            <span class="font-label-md text-label-md">Kết quả học tập</span>
        </a>
        
         <a class="flex items-center gap-3 px-4 py-2.5 bg-primary/10 text-primary rounded-lg transition-colors" href="baocao.php">
            <span class="material-symbols-outlined text-[20px]">assessment</span>
            <span class="font-label-md text-label-md">Báo cáo</span>
        </a>
        <a class="flex items-center gap-3 px-4 py-2.5 text-secondary hover:text-primary hover:bg-primary/5 rounded-lg transition-colors" href="CAIDAT.php">
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


<main class="ml-sidebar-width min-h-screen">
    <header class="flex justify-between items-center h-16 px-gutter sticky top-0 z-40 bg-surface/80 backdrop-blur-md border-b border-surface-container-highest">
        <div class="flex items-center">
            <h2 class="font-headline-md text-headline-md font-bold text-on-surface">Báo cáo</h2>
        </div>
        
    </header>

    <div class="p-unit-lg">
        <div class="flex flex-wrap items-center justify-between mb-unit-lg gap-4">
            <div class="flex items-center space-x-3">
              <form method="GET" action="baocao.php" id="filterForm" class="bg-surface-container-lowest border border-outline-variant rounded-lg p-1 flex items-center gap-2">
    <label for="thang_loc" class="font-label-md text-on-surface-variant px-2 text-sm">Xem theo thời gian:</label>
    <select name="thang_loc" id="thang_loc" onchange="document.getElementById('filterForm').submit();" class="bg-transparent border-none focus:ring-0 font-label-md text-primary font-semibold cursor-pointer py-1 pr-8 pl-2">
        <option value="all" <?= ($thang_loc == 'all') ? 'selected' : '' ?>>Cả năm 2026</option>
        <option value="1" <?= ($thang_loc == '1') ? 'selected' : '' ?>>Tháng 1/2026</option>
        <option value="2" <?= ($thang_loc == '2') ? 'selected' : '' ?>>Tháng 2/2026</option>
        <option value="3" <?= ($thang_loc == '3') ? 'selected' : '' ?>>Tháng 3/2026</option>
        <option value="4" <?= ($thang_loc == '4') ? 'selected' : '' ?>>Tháng 4/2026</option>
        <option value="5" <?= ($thang_loc == '5') ? 'selected' : '' ?>>Tháng 5/2026</option>
        <option value="6" <?= ($thang_loc == '6') ? 'selected' : '' ?>>Tháng 6/2026</option>
    </select>
</form>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-gutter mb-unit-lg">
            <div class="bg-surface-container-lowest border border-outline-variant p-card-padding rounded-xl shadow-sm hover:shadow-md transition-all">
                <div class="flex justify-between items-start mb-unit-md">
                    <div class="p-3 rounded-lg bg-blue-100 text-blue-700">
                        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">group</span>
                    </div>
                </div>
                <p class="text-on-surface-variant font-label-sm uppercase tracking-wider mb-1">TỔNG HỌC VIÊN</p>
                <h3 class="font-display-lg text-display-lg text-on-surface"><?= $tong_hoc_vien ?></h3>
                <p class="text-on-surface-variant font-body-sm mt-2">Học viên trong hệ thống</p>
            </div>

            <div class="bg-surface-container-lowest border border-outline-variant p-card-padding rounded-xl shadow-sm hover:shadow-md transition-all">
                <div class="flex justify-between items-start mb-unit-md">
                    <div class="p-3 rounded-lg bg-red-100 text-primary">
                        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">school</span>
                    </div>
                </div>
                <p class="text-on-surface-variant font-label-sm uppercase tracking-wider mb-1">LỚP ĐANG HOẠT ĐỘNG</p>
                <h3 class="font-display-lg text-display-lg text-on-surface"><?= $lop_dang_chay ?></h3>
                <p class="text-on-surface-variant font-body-sm mt-2">Các lớp chưa kết thúc</p>
            </div>

            <div class="bg-surface-container-lowest border border-outline-variant p-card-padding rounded-xl shadow-sm hover:shadow-md transition-all">
                <div class="flex justify-between items-start mb-unit-md">
                    <div class="p-3 rounded-lg bg-yellow-100 text-yellow-700">
                        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">record_voice_over</span>
                    </div>
                </div>
                <p class="text-on-surface-variant font-label-sm uppercase tracking-wider mb-1">TỔNG GIÁO VIÊN</p>
                <h3 class="font-display-lg text-display-lg text-on-surface"><?= $tong_giao_vien ?></h3>
                <p class="text-on-surface-variant font-body-sm mt-2">Giảng viên thuộc trung tâm</p>
            </div>
        </div>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-gutter items-start">
    
    <div class="bg-surface-container-lowest border border-outline-variant p-card-padding rounded-xl shadow-sm col-span-1">
        <h4 class="font-headline-sm text-headline-sm text-on-surface mb-unit-lg">Phân bổ theo khóa học</h4>
        <div class="flex flex-col items-center justify-center">
            <div class="relative w-48 h-48 mb-unit-lg">
                <svg class="w-full h-full transform -rotate-90" viewbox="0 0 36 36">
                    <circle cx="18" cy="18" fill="transparent" r="16" stroke="#e1e3e4" stroke-width="4"></circle>
                    <?php 
                    $stroke_offset = 0;
                    if (isset($tong_so_trong_chart) && $tong_so_trong_chart > 0):
                        foreach ($data_chart as $index => $item):
                            $percent = ($item['SoLuong'] / $tong_so_trong_chart) * 100;
                            $dash_array = $percent . " " . (100 - $percent);
                            $current_color = $colors[$index % count($colors)];
                    ?>
                        <circle cx="18" cy="18" fill="transparent" r="16" stroke="<?= $current_color ?>" stroke-width="4" stroke-dasharray="<?= $dash_array ?>" stroke-dashoffset="-<?= $stroke_offset ?>"></circle>
                    <?php 
                            $stroke_offset += $percent;
                        endforeach;
                    endif; 
                    ?>
                </svg>
                <div class="absolute inset-0 flex flex-col items-center justify-center">
                    <span class="font-display-lg text-on-surface font-bold text-2xl"><?= $tong_so_trong_chart ?? 0 ?></span>
                    <span class="font-label-sm text-on-surface-variant text-xs">Lượt học viên</span>
                </div>
            </div>

            <div class="w-full space-y-3 max-h-40 overflow-y-auto">
                <?php if (!empty($data_chart)): ?>
                    <?php foreach ($data_chart as $index => $item): 
                        $current_color = $colors[$index % count($colors)];
                    ?>
                        <div class="flex items-center justify-between text-sm">
                            <div class="flex items-center min-w-0 mr-2">
                                <span class="w-3 h-3 rounded-full mr-2 flex-shrink-0" style="background-color: <?= $current_color ?>;"></span>
                                <span class="truncate"><?= htmlspecialchars($item['TenKhoaHoc']) ?></span>
                            </div>
                            <span class="font-semibold flex-shrink-0"><?= $item['SoLuong'] ?> HV</span>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-center text-xs text-on-surface-variant">Không có dữ liệu</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl shadow-sm overflow-hidden lg:col-span-2">
        <table class="w-full text-left">
            <thead class="bg-surface-container-low border-b border-outline-variant">
                <tr>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">TÊN LỚP / KHÓA HỌC</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant text-center">HỌC VIÊN</th>
                    <th class="px-6 py-4 font-label-md text-on-surface-variant">TRẠNG THÁI</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant">
                <?php
                // Thực hiện kết nối SQL động theo bộ lọc thời gian
                $sql = "SELECT l.TenLop, nd.HoTen as TenGV, COUNT(ctl.MaHocVien) as SiSoThucTe, l.NgayKetThucThucTe 
                        FROM LopHoc l 
                        LEFT JOIN GiaoVien gv ON l.MaGiaoVien = gv.MaGiaoVien
                        LEFT JOIN TaiKhoan tk ON gv.MaTaiKhoan = tk.MaTaiKhoan
                        LEFT JOIN NguoiDung nd ON tk.MaNguoiDung = nd.MaNguoiDung
                        LEFT JOIN ChiTietLopHoc ctl ON l.MaLopHoc = ctl.MaLopHoc
                        $where_condition
                        GROUP BY l.MaLopHoc";
                $result = $conn->query($sql);

                if ($result && $result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        $is_active = (empty($row['NgayKetThucThucTe']) || $row['NgayKetThucThucTe'] == '0000-00-00');
                        $status_text = $is_active ? "Hoạt động" : "Kết thúc";
                        $status_class = $is_active ? "bg-green-100 text-green-700" : "bg-surface-container-high text-on-surface-variant";
                ?>
                <tr class="hover:bg-surface-container transition-colors">
                    <td class="px-6 py-4">
                        <p class="font-label-md text-on-surface"><?= htmlspecialchars($row['TenLop'] ?? 'Chưa rõ tên') ?></p>
                        <p class="text-on-surface-variant text-xs">GV: <?= htmlspecialchars($row['TenGV'] ?? 'Chưa phân công') ?></p>
                    </td>
                    <td class="px-6 py-4 text-center"><?= $row['SiSoThucTe'] ?></td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 <?= $status_class ?> rounded-full text-xs font-bold"><?= $status_text ?></span>
                    </td>
                </tr>
                <?php 
                    }
                } else {
                    echo "<tr><td colspan='3' class='text-center py-6 text-on-surface-variant'>Chưa có dữ liệu lớp học trong thời gian này.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

</div> 
            </div>
        </div>
    </div>
</main>

</body>
</html>
<?php $conn->close(); ?>