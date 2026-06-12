<?php
$conn = new mysqli("localhost", "root", "", "quanlytrungtam");

$limit = 9;

// lấy page từ URL
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

$start = ($page - 1) * $limit;

// tổng số học viên
$total_result = $conn->query("SELECT COUNT(*) AS total FROM HocVien");
$total = $total_result->fetch_assoc()['total'] ?? 0;

$total_pages = ceil($total / $limit);

// lấy data thật
$hocvien_list = $conn->query("
    SELECT * FROM HocVien
    LIMIT $start, $limit
");

if ($conn->connect_error) {
    die("Lỗi kết nối: " . $conn->connect_error);
}

$sql = "
SELECT 
    hv.MaHocVien,
    nd.HoTen,
    nd.SDT,
    tk.Email,
    hv.TrinhDoHienTai,
    hv.MucTieuHocTap
FROM HocVien hv
JOIN TaiKhoan tk ON hv.MaTaiKhoan = tk.MaTaiKhoan
JOIN NguoiDung nd ON tk.MaNguoiDung = nd.MaNguoiDung
";

$result = $conn->query($sql);

if (!$result) {
    die("Lỗi query: " . $conn->error);
}
function getCount($conn, $sql) {
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    return $row['total'] ?? 0;
}

$total = getCount($conn,"
SELECT COUNT(*) AS total
FROM HocVien hv
JOIN TaiKhoan tk ON hv.MaTaiKhoan = tk.MaTaiKhoan
");

$danghoc = getCount($conn,"
SELECT COUNT(*) AS total
FROM HocVien hv
JOIN TaiKhoan tk ON hv.MaTaiKhoan = tk.MaTaiKhoan
WHERE tk.TrangThai = 'HoatDong'
");

$baoluu = getCount($conn,"
SELECT COUNT(*) AS total
FROM HocVien hv
JOIN TaiKhoan tk ON hv.MaTaiKhoan = tk.MaTaiKhoan
WHERE tk.TrangThai = 'BaoLuu'
");

$nghihoc = getCount($conn,"
SELECT COUNT(*) AS total
FROM HocVien hv
JOIN TaiKhoan tk ON hv.MaTaiKhoan = tk.MaTaiKhoan
WHERE tk.TrangThai = 'NghiHoc'
");
?>
<!DOCTYPE html><html lang="vi" style=""><head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>Athena English - Học viên</title>
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
<body class="bg-background text-on-background font-body-md min-h-screen flex">
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
    <a class="flex items-center gap-3 px-4 py-2.5 bg-primary/10 text-primary rounded-lg transition-colors"
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
<!-- Main Content -->
<main class="ml-64 w-[calc(100%-16rem)] min-h-screen flex flex-col overflow-y-auto">
<!-- Topbar -->
<header class="flex justify-between items-center h-16 px-gutter sticky top-0 z-40 bg-surface/80 backdrop-blur-md border-b border-surface-container-highest">
    <div class="flex items-center">
        <h2 class="font-headline-md text-headline-md font-bold text-on-surface">
            Quản lý học viên
        </h2>
    </div>
</header>
<!-- Content Area -->
<div class="flex-1 overflow-y-auto p-6 md:p-8 bg-surface">
<div class="w-full flex flex-col gap-6">
<!-- Filters & Actions -->
<div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 bg-surface-container-lowest p-6 rounded-xl border border-outline-variant shadow-sm">
<!-- Status Filters -->
<div class="flex items-center gap-3 flex-nowrap overflow-x-auto">

<!-- TẤT CẢ -->
<button class="px-5 py-2.5 rounded-lg bg-primary-container/10 text-[#bb0025] font-label-md text-label-md flex items-center gap-2 transition-colors border border-transparent">
    Tất cả
    <span class="bg-[#bb0025] text-on-primary px-2 py-0.5 rounded-full text-[10px]">
        <?= $total ?>
    </span>
</button>

<!-- ĐANG HỌC -->
<button class="px-5 py-2.5 rounded-lg bg-surface hover:bg-surface-container text-on-surface-variant border border-outline-variant font-label-md text-label-md flex items-center gap-2 transition-colors">
    Đang học
    <span class="bg-surface-variant text-on-surface-variant px-2 py-0.5 rounded-full text-[10px]">
        <?= $danghoc ?>
    </span>
</button>

<!-- BẢO LƯU -->
<button class="px-5 py-2.5 rounded-lg bg-surface hover:bg-surface-container text-on-surface-variant border border-outline-variant font-label-md text-label-md flex items-center gap-2 transition-colors">
    Bảo lưu
    <span class="bg-surface-variant text-on-surface-variant px-2 py-0.5 rounded-full text-[10px]">
        <?= $baoluu ?>
    </span>
</button>

<!-- NGHỈ HỌC -->
<button class="px-5 py-2.5 rounded-lg bg-surface hover:bg-surface-container text-on-surface-variant border border-outline-variant font-label-md text-label-md flex items-center gap-2 transition-colors">
    Nghỉ học
    <span class="bg-surface-variant text-on-surface-variant px-2 py-0.5 rounded-full text-[10px]">
        <?= $nghihoc ?>
    </span>
</button>

</div>
<!-- Actions -->
<div class="flex items-center gap-3 w-full lg:w-auto mt-4 lg:mt-0 pr-8">
<button onclick="window.location.href='themhocvien.php'"
        class="flex-1 lg:flex-none flex items-center justify-center gap-2 px-5 py-2.5 bg-[#bb0025] hover:bg-[#99001e] text-on-primary rounded-lg font-label-md text-label-md transition-colors shadow-sm">
    <span class="material-symbols-outlined">add</span>
    <a href="themhocvien.php">
    Thêm học viên
    </a>
</button>
</div>
</div>
<!-- Data Table Container -->
<div class="bg-surface-container-lowest rounded-xl border border-outline-variant shadow-sm overflow-hidden flex flex-col">
<div class="overflow-x-auto">
<table class="w-full text-left border-collapse">
<thead>
<tr class="border-b border-outline-variant bg-surface-container-low/30">
<th class="px-6 py-4 font-label-md text-label-md text-on-surface-variant font-semibold w-[100px] text-center">Mã HV</th>
<th class="px-6 py-4 font-label-md text-label-md text-on-surface-variant font-semibold min-w-[250px] text-center">Học viên</th>
<th class="px-6 py-4 font-label-md text-label-md text-on-surface-variant font-semibold min-w-[200px] text-center">SĐT / Email</th>
<th class="px-6 py-4 font-label-md text-label-md text-on-surface-variant font-semibold min-w-[150px] text-left">&nbsp; &nbsp; Khóa học</th>
<th class="px-6 py-4 font-label-md text-label-md text-on-surface-variant font-semibold w-[160px] text-left">&nbsp; &nbsp;Trạng thái</th>
</tr>
</thead>
<tbody class="divide-y divide-outline-variant">

<?php while($row = $result->fetch_assoc()) { ?>

<tr>
    <td class="px-6 py-4">
        <?= $row['MaHocVien'] ?>
    </td>

    <td class="px-6 py-4">
        <?= $row['HoTen'] ?>
    </td>

    <td class="px-6 py-4">
        <?= $row['SDT'] ?>
    </td>

    <td class="px-6 py-4">
        <?= $row['TrinhDoHienTai'] ?>
    </td>

    <td class="px-6 py-4">
        Đang học
    </td>

    <td class="px-6 py-4">
        <?= $row['Email'] ?>
    </td>

    <td class="px-6 py-4">

    <div class="flex items-center gap-4">

        <a href="suahocvien.php?id=<?= $row['MaHocVien'] ?>" 
           class="font-semibold"
           style="color:#ba1a1a;">
            Sửa
        </a>

        <a href="xoahocvien.php?id=<?= $row['MaHocVien'] ?>" 
           onclick="return confirm('Bạn có chắc muốn xóa học viên này?')"
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
<div class="px-6 py-4 border-t border-outline-variant bg-surface-container-lowest flex items-center justify-between">

<div class="text-sm text-gray-500">
    Hiển thị <?= $start + 1 ?> - <?= min($start + $limit, $total) ?> của <?= $total ?> học viên
</div>

<div class="flex items-center gap-1">

<!-- Prev -->
<?php if($page > 1): ?>
<a href="?page=<?= $page - 1 ?>" class="p-1 hover:bg-surface-container rounded-md">
    <span class="material-symbols-outlined text-[20px]">chevron_left</span>
</a>
<?php endif; ?>

<!-- Pages -->
<?php for($i = 1; $i <= $total_pages; $i++): ?>
<a href="?page=<?= $i ?>"
   class="w-8 h-8 flex items-center justify-center font-bold transition-all
   <?= ($i == $page) 
        ? 'bg-primary text-white rounded-full shadow-sm' 
        : 'text-on-surface-variant hover:bg-surface-container rounded-md' ?>">
    <?= $i ?>
</a>
<?php endfor; ?>

<!-- Next -->
<?php if($page < $total_pages): ?>
<a href="?page=<?= $page + 1 ?>" class="p-1 hover:bg-surface-container rounded-md">
    <span class="material-symbols-outlined text-[20px]">chevron_right</span>
</a>
<?php endif; ?>

</div>
</div>
</div>
</div>
</main>
</body></html>
























































































