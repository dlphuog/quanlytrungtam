<?php
include("../config/connect.php");

// Tổng giáo viên
$sql = "SELECT COUNT(*) AS tong_gv FROM giaovien";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$tongGV = $row['tong_gv'] ?? 0;

// Lấy danh sách giáo viên
$sqlList = "
SELECT 
    gv.MaGiaoVien,
    gv.MaTaiKhoan,
    gv.ChuyenMon,
    gv.ChungChi,
    tk.Username,
    tk.Email,
    tk.VaiTro,
    tk.TrangThai,
    nd.HoTen
FROM giaovien gv
LEFT JOIN taikhoan tk ON gv.MaTaiKhoan = tk.MaTaiKhoan
LEFT JOIN nguoidung nd ON tk.MaNguoiDung = nd.MaNguoiDung
";
$resultList = mysqli_query($conn, $sqlList);
?>
<!DOCTYPE html><html lang="vi"><head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>Giáo viên - Athena English Admin</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&amp;display=swap" rel="stylesheet">
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
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #f8f9fa;
        }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        /* Custom scrollbar for sidebar */
        .sidebar-scroll::-webkit-scrollbar {
            width: 4px;
        }
        .sidebar-scroll::-webkit-scrollbar-track {
            background: transparent;
        }
        .sidebar-scroll::-webkit-scrollbar-thumb {
            background: rgba(0,0,0,0.1);
            border-radius: 10px;
        }
        /* Teacher Card Style */
        .teacher-card-container {
            width: 80px;
            height: 110px;
            position: relative;
            background-color: #be0527; /* Athena Red */
            border-radius: 4px;
            overflow: hidden;
            flex-shrink: 0;
        }
        .teacher-card-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: top center;
        }
    </style>
</head>
<body class="bg-background text-on-background min-h-screen flex">
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
    <a class="flex items-center gap-3 px-4 py-2.5 bg-primary/10 text-primary rounded-lg transition-colors"
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
<!-- Main Content Area -->
<main class="ml-64 flex-1 flex flex-col min-h-screen">
<!-- Top Navigation Bar -->
<header class="flex justify-between items-center h-16 px-gutter sticky top-0 z-40 bg-surface/80 backdrop-blur-md border-b border-surface-container-highest">
    <div class="flex items-center">
        <h2 class="font-headline-md text-headline-md font-bold text-on-surface">
            Quản lý giáo viên
        </h2>
    </div>
</header>
<div class="p-lg space-y-lg max-w-[1280px] mx-auto w-full">
<!-- Summary Stats Section -->
<div class="bg-surface-primary px-6 py-4 border border-border-subtle flex items-center gap-4">

    <div class="w-9 h-9 rounded-full bg-primary/10 flex items-center justify-center text-primary">
        <span class="material-symbols-outlined text-[18px]">group</span>
    </div>

    <div>
        <p class="text-secondary font-semibold">Tổng số giáo viên</p>
        <h1 class="text-[40px] font-bold leading-none"><?= $tongGV ?></h1>
    </div>

</div>
<!-- Table Controls & Data -->
<div class="bg-surface-primary rounded-none border border-border-subtle shadow-sm overflow-hidden">
<div class="px-6 py-4 border-b border-border-subtle flex flex-col md:flex-row justify-between items-start md:items-center gap-sm">
<div class="flex items-center gap-xs">
<span class="font-label-md text-label-md text-on-surface">Danh sách giáo viên</span>
<span class="px-2 py-0.5 bg-surface-secondary text-secondary rounded-full text-[10px] font-bold">
    <?= number_format($tongGV) ?> TOTAL
</span>
</div>
<div class="flex items-center gap-sm">
<button onclick="window.location.href='themgiaovien.php'"
    class="flex items-center gap-2 px-4 py-2 bg-primary text-on-primary rounded-lg font-label-md text-label-md hover:bg-primary-container transition-colors shadow-sm active:scale-95">

    <span class="material-symbols-outlined text-[20px]">person_add</span>
    Thêm giáo viên
</button>
</div>
</div>

<div class="overflow-x-auto">
<table class="w-full text-left border-collapse">
<thead class="bg-surface-secondary">
<tr>
<th class="px-6 py-4 font-label-md text-label-md text-secondary border-b border-border-subtle">MÃ GV</th>
<th class="px-6 py-4 font-label-md text-label-md text-secondary border-b border-border-subtle">GIÁO VIÊN</th>
<th class="px-6 py-4 font-label-md text-label-md text-secondary border-b border-border-subtle">CHUYÊN MÔN &amp; CHỨNG CHỈ</th>
<th class="px-6 py-4 font-label-md text-label-md text-secondary border-b border-border-subtle">LIÊN LẠC</th>
<th class="px-6 py-4 font-label-md text-label-md text-secondary border-b border-border-subtle">TÀI KHOẢN</th>
<th class="px-6 py-4 font-label-md text-label-md text-secondary border-b border-border-subtle text-right">THAO TÁC</th>
</tr>
</thead>
<tbody class="divide-y divide-border-subtle">

<?php while($row = mysqli_fetch_assoc($resultList)) { ?>

<tr class="hover:bg-surface-secondary/50 transition-colors group">

    <!-- MÃ GV -->
    <td class="px-6 py-4 font-bold text-primary">
        <?= $row['MaGiaoVien'] ?>
    </td>

    <!-- GIÁO VIÊN  -->
    <td>
    <div>
        <p class="font-semibold">
    <?= $row['HoTen'] ?? $row['Username'] ?? 'Chưa có tên' ?>
</p>
        <p class="text-xs text-secondary"><?= $row['Email'] ?? '' ?></p>
    </div>
    </td>

    <!-- CHUYÊN MÔN & CHỨNG CHỈ -->
    <td class="px-6 py-4">
        <div class="flex flex-col gap-1">
            <span class="font-semibold"><?= $row['ChuyenMon'] ?></span>
            <span class="text-xs text-secondary"><?= $row['ChungChi'] ?></span>
        </div>
    </td>

    <!-- LIÊN LẠC (DB không có → để trống hoặc map sau) -->
    <td class="px-6 py-4 text-secondary text-sm">
    <?= $row['Email'] ?? 'Chưa có dữ liệu' ?>
    </td>

    <!-- TÀI KHOẢN -->
    <td class="px-6 py-4 font-semibold">
        <?= $row['MaTaiKhoan'] ?>
    </td>

    <!-- THAO TÁC -->
    <td class="px-6 py-4 text-right">
    
    <a href="suagiaovien.php?id=<?= $row['MaGiaoVien'] ?>" 
       class="text-primary font-semibold">
        Sửa
    </a>

    <a href="xoagiaovien.php?id=<?= $row['MaGiaoVien'] ?>" 
       onclick="return confirm('Bạn có chắc muốn xóa giáo viên này?')"
       class="text-error ml-3 font-semibold">
        Xóa
    </a>

</td>

</tr>

<?php } ?>

</tbody>
</table></div></div></div></main>
</body></html>

















