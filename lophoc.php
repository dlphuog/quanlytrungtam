<?php
include("../config/connect.php");

// Tổng số lớp
$sql1 = "SELECT COUNT(*) AS TongLop FROM LopHoc";
$result1 = mysqli_query($conn, $sql1);
$row1 = mysqli_fetch_assoc($result1);
$tongLop = $row1['TongLop'] ?? 0;

// Lớp đang hoạt động
$sql2 = "SELECT COUNT(*) AS DangHoatDong FROM LopHoc WHERE TrangThai='DangHoc'";
$result2 = mysqli_query($conn, $sql2);
$row2 = mysqli_fetch_assoc($result2);
$dangHoatDong = $row2['DangHoatDong'] ?? 0;

// Tổng học viên
$sql3 = "SELECT COUNT(*) AS TongHocVien FROM HocVien";
$result3 = mysqli_query($conn, $sql3);
$row3 = mysqli_fetch_assoc($result3);
$tongHocVien = $row3['TongHocVien'] ?? 0;
?>
<!DOCTYPE html><html class="light" lang="vi"><head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>Quản lý lớp học - Athena Admin</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
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
                         montserrat: ["Montserrat"],
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
         body{
        font-family: 'Montserrat', sans-serif;
        }
    </style>
</head>
<body class="text-on-surface bg-background">
<!-- SideNavBar Shell -->
<aside class="h-full w-64 fixed left-0 top-0 bg-white border-r border-outline-variant flex flex-col z-50">
    <!-- Brand Identity -->
    <div class="px-gutter pt-10 pb-6 flex flex-col">
        <img
            alt="Athena Admin Logo"
            class="w-20 h-auto mx-auto mb-4"
            src="/quanlytrungtam/logo.jpg"
        >
        <h1 class="font-montserrat text-[20px] font-bold text-primary leading-none text-center">
            Athena Admin
        </h1>
    </div>

    <!-- Navigation Menu -->
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
    <a class="flex items-center gap-3 px-4 py-2.5 bg-primary/10 text-primary rounded-lg transition-colors"
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
<!-- TopAppBar Shell -->
<header class="fixed top-0 right-0 left-64 h-20 flex justify-between items-center px-6 z-40 bg-[#f8f9fa] border-b border-gray-300">


    <!-- Title -->
    <div class="flex items-center">
        <h2 class="font-montserrat text-headline-md font-bold text-on-surface">
            Quản lý lớp học
        </h2>
    </div>
    
</header>
<!-- Content Canvas -->
<main class="ml-[280px] pt-20 min-h-screen">
<div class="px-4 md:px-8 py-10">
<!-- Action Button Header -->
<div class="flex justify-end items-center mb-4">
<button
    onclick="window.location.href='themlophoc.php'"
    class="bg-primary text-white px-6 py-3 rounded-2xl font-bold text-sm flex items-center gap-2 hover:opacity-90 transition-all"
>
    <span class="material-symbols-outlined text-[20px]">add</span>
    Thêm lớp học
</button>
</div>
<!-- Summary Metrics Cards -->
<!-- Summary Metrics Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-10">

    <!-- Tổng số lớp -->
    <div class="bg-white p-8 rounded-3xl border border-outline-variant shadow-sm flex items-center gap-6">
        <div class="w-16 h-16 rounded-2xl bg-red-50 flex items-center justify-center text-[#a31d1d]">
            <span class="material-symbols-outlined text-[36px]">school</span>
        </div>
        <div>
            <p class="font-label-sm text-label-sm text-secondary uppercase tracking-widest mb-1.5">
                Tổng số lớp
            </p>
            <p class="text-[36px] font-bold text-on-surface leading-none">
                <?= $tongLop ?>
            </p>
        </div>
    </div>

    <!-- Đang hoạt động -->
    <div class="bg-white p-8 rounded-3xl border border-outline-variant shadow-sm flex items-center gap-6">
        <div class="w-16 h-16 rounded-2xl bg-green-50 flex items-center justify-center text-green-600">
            <span class="material-symbols-outlined text-[36px]">sync</span>
        </div>
        <div>
            <p class="font-label-sm text-label-sm text-secondary uppercase tracking-widest mb-1.5">
                Đang hoạt động
            </p>
            <p class="text-[36px] font-bold text-on-surface leading-none">
                <?= $dangHoatDong ?>
            </p>
        </div>
    </div>

    <!-- Tổng học viên -->
    <div class="bg-white p-8 rounded-3xl border border-outline-variant shadow-sm flex items-center gap-6">
        <div class="w-16 h-16 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-600">
            <span class="material-symbols-outlined text-[36px]">group</span>
        </div>
        <div>
            <p class="font-label-sm text-label-sm text-secondary uppercase tracking-widest mb-1.5">
                Tổng học viên
            </p>
            <p class="text-[36px] font-bold text-on-surface leading-none">
                <?= $tongHocVien ?>
            </p>
        </div>
    </div>

</div>
<!-- Bento Filter Section -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-10">
<div class="col-span-3 bg-white p-8 rounded-3xl border border-outline-variant shadow-sm">
<div class="flex items-center justify-between mb-6">
<p class="font-label-sm text-label-sm text-secondary uppercase tracking-widest">Khóa học</p>
</div>
<div class="flex flex-wrap gap-4">
<button class="px-7 py-2.5 rounded-full bg-[#a31d1d] text-white font-label-md text-label-md shadow-lg shadow-red-900/20 transition-all">Tất cả</button>
<button class="px-7 py-2.5 rounded-full border border-outline-variant hover:border-[#a31d1d] hover:text-[#a31d1d] transition-all text-secondary font-label-md text-label-md bg-white">TOEIC</button>
<button class="px-7 py-2.5 rounded-full border border-outline-variant hover:border-[#a31d1d] hover:text-[#a31d1d] transition-all text-secondary font-label-md text-label-md bg-white">IELTS</button>
</div>
</div>
<div class="bg-white p-8 rounded-3xl border border-outline-variant shadow-sm">
<p class="font-label-sm text-label-sm text-secondary uppercase tracking-widest mb-6">Trạng thái</p>
<div class="relative">
<select class="w-full bg-surface-container-low border border-outline-variant rounded-2xl py-3 px-5 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary appearance-none font-label-md text-label-md text-on-surface cursor-pointer">
<option>Tất cả trạng thái</option>
<option selected="">Đang hoạt động</option>
<option>Lớp đã đầy</option>
<option>Đã kết thúc</option>
</select>
<span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-secondary" data-icon="expand_more">expand_more</span>
</div>
</div>
</div>
<!-- Data Table Section -->
<div class="bg-white rounded-3xl border border-outline-variant shadow-sm overflow-hidden">
<div class="overflow-x-auto">
<table class="w-full text-left border-collapse">
<thead>
<tr class="bg-surface-bright border-b border-outline-variant">
<th class="py-6 font-label-md text-[13px] text-secondary text-center uppercase tracking-wider whitespace-nowrap px-4">Mã lớp</th>
<th class="py-6 font-label-md text-[13px] text-secondary text-center uppercase tracking-wider whitespace-nowrap px-4" style="width: 250px;">Tên lớp</th>
<th class="py-6 font-label-md text-[13px] text-secondary text-center uppercase tracking-wider whitespace-nowrap px-4">Khóa học</th>
<th class="py-6 font-label-md text-[13px] text-secondary text-center uppercase tracking-wider whitespace-nowrap px-4" data-stitch-orig-opacity="0">Sĩ số</th>
<th class="py-6 font-label-md text-[13px] text-secondary text-center uppercase tracking-wider whitespace-nowrap px-4">Giáo viên</th>
<th class="py-6 font-label-md text-[13px] text-secondary text-center uppercase tracking-wider whitespace-nowrap px-4">Lịch học</th>
<th class="py-6 font-label-md text-[13px] text-secondary text-center uppercase tracking-wider whitespace-nowrap px-4">Trạng thái</th>
<th class="py-6 font-label-md text-[13px] text-secondary text-center uppercase tracking-wider whitespace-nowrap px-4"></th>
</tr>
</thead>
<tbody class="divide-y divide-outline-variant">

<?php
$sql = mysqli_query($conn,"SELECT * FROM LopHoc");

while($row = mysqli_fetch_assoc($sql)){
?>

<tr class="hover:bg-surface-container-low transition-colors">

<td class="py-6 text-center px-4">
    <span class="font-bold text-[#a31d1d] bg-red-50 px-4 py-2 rounded-xl border border-red-100">
        <?= $row['MaLopHoc'] ?>
    </span>
</td>

<td class="py-6 px-4">
    <span class="font-bold text-on-surface">
        <?= $row['TenLop'] ?>
    </span>
</td>

<td class="py-6 px-4">
    <span class="text-secondary">
        <?= $row['TrinhDo'] ?>
    </span>
</td>

<td class="py-6 px-4">
    <div class="flex items-center justify-center gap-3">
        <span>
            <?= $row['SiSoHienTai'] ?>/<?= $row['SiSoToiDa'] ?>
        </span>
    </div>
</td>

<td class="py-6 px-4">
    <span class="font-label-md">
        <?= $row['MaGiaoVien'] ?>
    </span>
</td>

<td class="py-6 px-4">
    <span>
        <?= $row['NgayBatDau'] ?>
    </span>
</td>

<td class="py-6 text-center px-4">

<?php
if($row['TrangThai']=="Dang hoc"){
    echo '<span class="px-4 py-1.5 rounded-full bg-green-50 text-green-700 text-[11px] font-bold border border-green-200">Đang hoạt động</span>';
}
elseif($row['TrangThai']=="Chua hoc"){
    echo '<span class="px-4 py-1.5 rounded-full bg-yellow-50 text-yellow-700 text-[11px] font-bold border border-yellow-200">Chưa bắt đầu</span>';
}
else{
    echo '<span class="px-4 py-1.5 rounded-full bg-gray-100 text-gray-700 text-[11px] font-bold border border-gray-200">Đã kết thúc</span>';
}
?>

</td>

<td class="py-6 px-4">

    <div class="flex justify-end items-center gap-4 text-sm">

        <a href="/quanlytrungtam/giaodienadmin/sualophoc.php?id=<?= $row['MaLopHoc'] ?>"
           class="font-semibold"
           style="color:#ba1a1a;">
            Sửa
        </a>

        <a href="/quanlytrungtam/giaodienadmin/xoalophoc.php?id=<?= $row['MaLopHoc'] ?>"
           onclick="return confirm('Bạn có chắc muốn xóa lớp này?')"
           class="font-semibold"
           style="color:#ba1a1a;">
            Xóa
        </a>

    </div>

</td>

</tr>

<?php } ?>

</tbody>
</table>
</div>
<!-- Pagination -->
<div class="px-10 py-6 bg-surface-bright flex items-center justify-between border-t border-outline-variant">
<p class="font-body-md text-body-md text-secondary">Hiển thị <span class="font-bold text-on-surface">1 - 4</span> trong <span class="font-bold text-on-surface">24</span> lớp học</p>
<div class="flex items-center gap-3">
<button class="w-11 h-11 flex items-center justify-center rounded-2xl border border-outline-variant text-secondary hover:border-[#a31d1d] hover:text-[#a31d1d] transition-all disabled:opacity-30" disabled="">
<span class="material-symbols-outlined text-[24px]" data-icon="chevron_left">chevron_left</span>
</button>
<button class="w-11 h-11 flex items-center justify-center rounded-2xl bg-[#a31d1d] text-white font-bold shadow-lg shadow-red-900/20">1</button>
<button class="w-11 h-11 flex items-center justify-center rounded-2xl border border-outline-variant text-secondary hover:border-[#a31d1d] hover:text-[#a31d1d] transition-all font-bold">2</button>
<button class="w-11 h-11 flex items-center justify-center rounded-2xl border border-outline-variant text-secondary hover:border-[#a31d1d] hover:text-[#a31d1d] transition-all font-bold">3</button>
<button class="w-11 h-11 flex items-center justify-center rounded-2xl border border-outline-variant text-secondary hover:border-[#a31d1d] hover:text-[#a31d1d] transition-all">
<span class="material-symbols-outlined text-[24px]" data-icon="chevron_right">chevron_right</span>
</button>
</div>
</div>
</div>
</div>
</main>
<script>
    // Micro-interaction for hover effects on table rows
    document.querySelectorAll('tbody tr').forEach(row => {
        row.addEventListener('mouseenter', () => {
            row.style.backgroundColor = '#f8fafc';
            row.style.transition = 'all 0.2s ease-out';
        });
        row.addEventListener('mouseleave', () => {
            row.style.backgroundColor = 'transparent';
        });
    });
    // Basic Search Filter Simulation
    const searchInput = document.querySelector('input[type="text"]');
    searchInput.addEventListener('input', (e) => {
        const query = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('tbody tr');
        rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            row.style.display = text.includes(query) ? '' : 'none';
        });
    });
</script>
</body></html>