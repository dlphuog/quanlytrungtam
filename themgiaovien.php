<?php
include("../config/connect.php");
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $hoTen     = $_POST['hoTen'] ?? '';
    $gioiTinh  = $_POST['gioiTinh'] ?? '';
    $ngaySinh  = $_POST['ngaySinh'] ?? '';
    $soDT      = $_POST['SDT'] ?? '';
    $diaChi    = $_POST['diaChi'] ?? '';
    $chuyenMon = $_POST['chuyenMon'] ?? '';
    $chungChi  = $_POST['chungChi'] ?? '';

    if ($hoTen == '') {
        die("<script>alert('Thiếu họ tên'); history.back();</script>");
    }

    // =========================
    // 1. TẠO MÃ NGƯỜI DÙNG
    // =========================
    $rs = mysqli_query($conn, "
        SELECT MaNguoiDung 
        FROM nguoidung 
        ORDER BY MaNguoiDung DESC 
        LIMIT 1
    ");
    $row = mysqli_fetch_assoc($rs);

    if ($row) {
        $num = (int)substr($row['MaNguoiDung'], 2);
        $maND = "ND" . str_pad($num + 1, 3, "0", STR_PAD_LEFT);
    } else {
        $maND = "ND001";
    }

    mysqli_query($conn, "
        INSERT INTO nguoidung 
        (MaNguoiDung, HoTen, GioiTinh, NgaySinh, SDT, DiaChi)
        VALUES
        ('$maND', '$hoTen', '$gioiTinh', '$ngaySinh', '$soDT', '$diaChi')
    ");

    // =========================
    // 2. TẠO USERNAME + EMAIL TEACHER
    // =========================
    $rs = mysqli_query($conn, "
        SELECT Username
        FROM taikhoan
        WHERE VaiTro='GV'
        ORDER BY MaTaiKhoan DESC
        LIMIT 1
    ");
    $row = mysqli_fetch_assoc($rs);

    if ($row && preg_match('/teacher(\d+)/', $row['Username'], $m)) {
        $num = (int)$m[1] + 1;
    } else {
        $num = 1;
    }

    $username = "teacher" . str_pad($num, 3, "0", STR_PAD_LEFT);
    $email = $username . "@athena.edu.vn";

    // =========================
    // 3. TẠO MÃ TÀI KHOẢN
    // =========================
    $rs = mysqli_query($conn, "
        SELECT MaTaiKhoan 
        FROM taikhoan 
        ORDER BY MaTaiKhoan DESC 
        LIMIT 1
    ");
    $row = mysqli_fetch_assoc($rs);

    if ($row) {
        $numTK = (int)substr($row['MaTaiKhoan'], 2);
        $maTK = "TK" . str_pad($numTK + 1, 3, "0", STR_PAD_LEFT);
    } else {
        $maTK = "TK001";
    }

    // =========================
    // INSERT TÀI KHOẢN
    // =========================
    mysqli_query($conn, "
        INSERT INTO taikhoan 
        (MaTaiKhoan, MaNguoiDung, Username, Password, VaiTro, TrangThai, Email)
        VALUES
        ('$maTK', '$maND', '$username', '123', 'GV', 'HoatDong', '$email')
    ");

    // =========================
    // 4. TẠO GIÁO VIÊN
    // =========================
    $rs = mysqli_query($conn, "
        SELECT MaGiaoVien 
        FROM giaovien 
        ORDER BY MaGiaoVien DESC 
        LIMIT 1
    ");
    $row = mysqli_fetch_assoc($rs);

    if ($row) {
        $numGV = (int)substr($row['MaGiaoVien'], 2);
        $maGV = "GV" . str_pad($numGV + 1, 3, "0", STR_PAD_LEFT);
    } else {
        $maGV = "GV001";
    }

    mysqli_query($conn, "
        INSERT INTO giaovien 
        (MaGiaoVien, MaTaiKhoan, ChuyenMon, ChungChi)
        VALUES
        ('$maGV', '$maTK', '$chuyenMon', '$chungChi')
    ");

    echo "<script>
        alert('Thêm giáo viên thành công');
        window.location.href = 'giaovien.php';
    </script>";
    exit;
}
?>
<!DOCTYPE html>
<html class="light" lang="vi" style=""><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Thêm giáo viên - Athena Admin</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&amp;display=swap" rel="stylesheet"/>
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
        }
        .material-symbols-outlined[data-weight="fill"] {
            font-variation-settings: 'FILL' 1;
        }
        body {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
    </style>
</head>
<body class="bg-surface font-body-md text-on-surface">
<!-- Sidebar - Styled per IMAGE_4 -->
<aside class="fixed h-full w-64 left-0 top-0 bg-surface-primary border-r border-border-subtle shadow-sm flex flex-col z-50">
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
<div class="p-6">
<div class="flex items-center justify-center gap-3 bg-surface-container-low/30 py-3 rounded-xl">
<div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center text-[10px] font-bold text-white">AD</div>
<div class="flex items-center gap-2">
<p class="font-label-md text-label-md text-on-surface font-semibold">Admin User</p>
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
<!-- Content Wrapper -->
<div class="flex-1 md:ml-64 flex flex-col min-h-screen">

<!-- Main Form Content -->
<main class="p-margin-mobile md:p-margin-desktop flex-1">
<div class="max-w-5xl mx-auto space-y-gutter">
<!-- Page Breadcrumbs -->
<nav class="flex items-center gap-2 text-label-sm font-label-sm text-on-secondary-container">
<span class="hover:text-primary cursor-pointer"> Giáo viên</span>
<span class="material-symbols-outlined text-[16px]">chevron_right</span>
<span class="text-on-surface">Thêm giáo viên mới</span>
</nav>
<form method="POST" action="themgiaovien.php" class="max-w-4xl mx-auto">

<div class="mb-6">
    <h2 class="text-2xl font-bold text-primary flex items-center gap-2">
        <span class="material-symbols-outlined">person_add</span>
        Thêm giáo viên
    </h2>
    <p class="text-sm text-secondary">Nhập đầy đủ thông tin giáo viên vào hệ thống</p>
</div>

<!-- CARD -->
<div class="bg-white border border-outline-variant rounded-2xl shadow-sm p-6">

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    <!-- Họ tên -->
    <div class="space-y-2">
        <label class="text-sm text-secondary">Họ tên *</label>
        <input name="hoTen"
            class="w-full h-11 px-4 rounded-xl border border-outline-variant focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none"
            placeholder="Nhập họ tên">
    </div>

    <!-- Giới tính -->
    <div class="space-y-2">
        <label class="text-sm text-secondary">Giới tính</label>
        <select name="gioiTinh"
            class="w-full h-11 px-4 rounded-xl border border-outline-variant focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none bg-white">
            <option value="">Chọn giới tính</option>
            <option value="Nam">Nam</option>
            <option value="Nữ">Nữ</option>
        </select>
    </div>

    <!-- Ngày sinh -->
    <div class="space-y-2">
        <label class="text-sm text-secondary">Ngày sinh</label>
        <input type="date" name="ngaySinh"
            class="w-full h-11 px-4 rounded-xl border border-outline-variant focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none">
    </div>

    <!-- SĐT -->
    <div class="space-y-2">
        <label class="text-sm text-secondary">Số điện thoại</label>
        <input name="SDT"
            class="w-full h-11 px-4 rounded-xl border border-outline-variant focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none"
            placeholder="09xxxxxxxx">
    </div>

    <!-- Chuyên môn -->
    <div class="space-y-2 md:col-span-2">
        <label class="text-sm text-secondary">Chuyên môn</label>
        <input name="chuyenMon"
            class="w-full h-11 px-4 rounded-xl border border-outline-variant focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none"
            placeholder="VD: IELTS, TOEIC, Giao tiếp...">
    </div>

    <!-- Địa chỉ -->
    <div class="space-y-2 md:col-span-2">
        <label class="text-sm text-secondary">Địa chỉ</label>
        <textarea name="diaChi" rows="3"
            class="w-full p-4 rounded-xl border border-outline-variant focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none resize-none"
            placeholder="Nhập địa chỉ..."></textarea>
    </div>

    <!-- Chứng chỉ -->
    <div class="space-y-2 md:col-span-2">
        <label class="text-sm text-secondary">Chứng chỉ</label>
        <textarea name="chungChi" rows="3"
            class="w-full p-4 rounded-xl border border-outline-variant focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none resize-none"
            placeholder="Ví dụ: TESOL, IELTS 8.0..."></textarea>
    </div>

</div>

<!-- BUTTON -->
<div class="flex justify-end gap-4 mt-6 pt-6 border-t border-outline-variant">

    <button type="button"
        class="px-6 py-2.5 rounded-xl border border-outline-variant text-secondary hover:bg-surface-container-low transition">
        Hủy
    </button>

    <button type="submit"
        class="px-6 py-2.5 rounded-xl bg-primary text-white font-semibold hover:brightness-110 shadow-md flex items-center gap-2">
        <span class="material-symbols-outlined text-[18px]">save</span>
        Lưu giáo viên
    </button>

</div>

</div>

</form>
<!-- Footer Spacer -->
<div class="h-12"></div>
</div>
</main>
</div>
<!-- Mobile Navigation Shell -->
<div class="md:hidden fixed bottom-0 left-0 right-0 bg-surface-primary border-t border-border-subtle h-16 flex items-center justify-around px-4 z-50">
<div class="flex flex-col items-center text-on-secondary-container">
<span class="material-symbols-outlined">grid_view</span>
<span class="text-[10px]">Dashboard</span>
</div>
<div class="flex flex-col items-center text-on-secondary-container">
<span class="material-symbols-outlined">school</span>
<span class="text-[10px]">Học viên</span>
</div>
<div class="flex flex-col items-center text-primary">
<span class="material-symbols-outlined" data-weight="fill">record_voice_over</span>
<span class="text-[10px] font-bold">Giáo viên</span>
</div>
<div class="flex flex-col items-center text-on-secondary-container">
<span class="material-symbols-outlined">logout</span>
<span class="text-[10px]">Thoát</span>
</div>
</div>
</body></html>







