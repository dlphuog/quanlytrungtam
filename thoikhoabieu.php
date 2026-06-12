<?php
include("../config/connect.php");

// map thứ: 2 = Thứ 2 ... 8 = CN
function colFromThu($thu) {
    return max(0, min(6, $thu - 2));
}

// convert giờ -> pixel (mỗi 1h = 100px)
function timeToTop($time) {
    if (empty($time)) return 0;

    $parts = explode(':', $time);

    $hour = isset($parts[0]) ? (int)$parts[0] : 8;
    $minute = isset($parts[1]) ? (int)$parts[1] : 0;

    // giờ bắt đầu khung giờ
    $startHour = 8;

    $totalMinutes = ($hour - $startHour) * 60 + $minute;

    // chặn không cho âm
    if ($totalMinutes < 0) $totalMinutes = 0;

    return ($totalMinutes / 60) * 100;
}

$lopFilter = isset($_GET['lop']) ? mysqli_real_escape_string($conn, $_GET['lop']) : 'all';

$sql = "SELECT lh.*, l.TenLop, p.TenPhong 
        FROM lichhocchitiet lh
        JOIN lophoc l ON lh.MaLopHoc = l.MaLopHoc
        JOIN phonghoc p ON lh.MaPhongHoc = p.MaPhongHoc";

// Nếu chọn một lớp cụ thể (không phải 'all'), thêm điều kiện WHERE
if ($lopFilter !== 'all') {
    $sql .= " WHERE lh.MaLopHoc = '$lopFilter'";
}

$result = mysqli_query($conn, $sql);
if (!$result) die("SQL lỗi: " . mysqli_error($conn));

$lichhoc = [];
while ($row = mysqli_fetch_assoc($result)) {
    $lichhoc[] = $row;
}
?>
<!DOCTYPE html>
<html lang="vi"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&amp;family=Inter:wght@400;500;600&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
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
            display: inline-block;
            vertical-align: middle;
        }
        .time-slot {
            min-height: 100px;
            border-bottom: 1px solid #e1e3e4;
        }
    </style>
</head>
<body class="bg-surface font-body-md text-on-background">
<!-- Sidebar Navigation -->
<aside class="h-full w-64 fixed left-0 top-0 bg-white border-r border-outline-variant flex flex-col z-50">

    <!-- Brand -->
    <div class="px-gutter pt-10 pb-6 flex flex-col">
        <img
            alt="Athena Admin Logo"
            class="w-20 h-auto mx-auto mb-4"
            src="/quanlytrungtam/logo.jpg"
        >

        <h1 class="font-headline-md text-[20px] font-bold text-primary leading-none text-center">
            Athena Admin
        </h1>
    </div>

    <!-- Menu -->
    <!-- Navigation -->
<nav class="flex-1 overflow-y-auto py-2 px-4 space-y-0.5 scrollbar-hide">


    <!-- Dashboard -->
    <a class="flex items-center gap-3 px-4 py-2.5 text-secondary hover:text-primary hover:bg-primary/5 rounded-lg transition-colors"
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
    <a class="flex items-center gap-3 px-4 py-2.5 bg-primary/10 text-primary rounded-lg transition-colors"
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


    <!-- Footer -->
    <div class="p-6">
        <div class="flex items-center justify-center gap-3 bg-surface-container-low/30 py-3 rounded-xl">


            <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center text-[10px] font-bold text-white">
                AD
            </div>

            <div class="flex items-center gap-2">
                <p class="font-label-md text-label-md text-on-surface font-semibold">
                    Admin User
                </p>


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

<!-- Top App Bar -->
<header class="fixed top-0 left-64 w-[calc(100%-16rem)] h-16 bg-[#f8f9fa] border-b border-gray-200 flex items-center px-6 z-20">

    <!-- LEFT -->
    <div class="flex items-center gap-6 flex-1">

        <!-- Title -->
        <h2 class="text-[22px] font-bold text-on-surface leading-none -ml-2">
            Thời khóa biểu
        </h2>
    </div>
</header>
<!-- Main Content Canvas -->
<main class="ml-[280px] pt-16 min-h-screen">
<div class="p-8">
<!-- Filters & View Switcher -->
<div class="flex items-center gap-4">
    <div class="flex items-center gap-3 flex-wrap ml-auto">
        <select onchange="window.location.href='thoikhoabieu.php?lop=' + this.value" 
            class="border-outline-variant rounded-lg font-body-sm text-body-sm focus:ring-primary/20 focus:border-primary">
            <option value="all">Tất cả Lớp học</option>
    <?php
        $sqlLop = "SELECT MaLopHoc, TenLop FROM lophoc";
        $resLop = mysqli_query($conn, $sqlLop);
        while($rowLop = mysqli_fetch_assoc($resLop)) {
            // Kiểm tra để set thuộc tính selected
            $selected = ($lopFilter == $rowLop['MaLopHoc']) ? 'selected' : '';
            echo "<option value='".$rowLop['MaLopHoc']."' $selected>".$rowLop['TenLop']."</option>";
        }
    ?>
    </select>
        <a href="themlichhoc.php" 
           class="h-11 px-5 bg-primary text-white flex items-center rounded-xl font-semibold hover:opacity-90 transition-all">
            Thêm lịch học
        </a>
    </div>
</div>
<!-- Schedule Grid -->
<div class="relative w-full bg-white rounded-2xl border border-outline-variant shadow-sm overflow-hidden">
    
    <div class="grid grid-cols-[80px_repeat(7,1fr)] border-b border-outline-variant bg-surface-container-low">
        <div class="p-4 border-r border-outline-variant"></div>
        <?php foreach(['Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7', 'CN'] as $day): ?>
            <div class="p-4 text-center border-r border-outline-variant font-bold text-sm text-secondary uppercase"><?= $day ?></div>
        <?php endforeach; ?>
    </div>

    <div class="relative h-[800px] overflow-y-auto">
        
        <div class="absolute inset-0 grid grid-cols-[80px_repeat(7,1fr)]">
            <div class="border-r border-outline-variant">
                <?php for($h=8; $h<=20; $h++): ?>
                    <div class="h-[100px] border-b border-outline-variant text-xs text-right pr-2 pt-1 text-secondary"><?= sprintf("%02d:00", $h) ?></div>
                <?php endfor; ?>
            </div>
            <?php for($c=0; $c<7; $c++): ?>
                <div class="border-r border-outline-variant">
                    <?php for($h=8; $h<=20; $h++): ?><div class="h-[100px] border-b border-outline-variant"></div><?php endfor; ?>
                </div>
            <?php endfor; ?>
        </div>

        <div class="absolute inset-0 ml-[80px] z-10">
            <?php foreach ($lichhoc as $lh): 
                $start = strtotime($lh['ThoiGianBatDau']);
                $end = strtotime($lh['ThoiGianKetThuc']);
                
                // Tính toán vị trí: mỗi giờ = 100px
                $top = (($start - strtotime('08:00:00')) / 3600) * 100;
                $height = (($end - $start) / 3600) * 100;
                $col = $lh['ThuTrongTuan'] - 2; 
            ?>
            <div class="absolute bg-white border-l-4 border-primary shadow-lg rounded-xl p-2 overflow-hidden hover:z-20 transition-all"
                 style="top: <?= $top ?>px; 
                        left: calc((100% / 7) * <?= $col ?>); 
                        width: calc((100% / 7) - 8px); 
                        height: <?= $height ?>px;">
                <h4 class="font-bold text-xs truncate text-primary">
                    <?= htmlspecialchars($lh['TenLop']) ?>
                </h4>

                <p class="text-[10px] text-secondary">
                    Phòng: <?= htmlspecialchars($lh['TenPhong']) ?>
                </p>

                <p class="text-[10px] text-secondary">
                    <?= substr($lh['ThoiGianBatDau'], 0, 5) ?> - <?= substr($lh['ThoiGianKetThuc'], 0, 5) ?>
                </p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
</main>
</body></html>







