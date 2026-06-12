<?php
require_once("../config/connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $MaLopHoc = $_POST['MaLopHoc'] ?? '';
    $MaPhongHoc = $_POST['MaPhongHoc'] ?? '';
    $GioBatDau = $_POST['GioBatDau'] ?? '';
    $GioKetThuc = $_POST['GioKetThuc'] ?? '';

    $Thu = isset($_POST['Thu']) ? implode(',', $_POST['Thu']) : '';

    if (!$MaLopHoc || !$MaPhongHoc) {
        die("Thiếu dữ liệu");
    }

    $sql = "INSERT INTO lichhocchitiet
    (MaLichHoc, MaLopHoc, MaPhongHoc, ThuTrongTuan, ThoiGianBatDau, ThoiGianKetThuc)
    VALUES
    (UUID(), '$MaLopHoc', '$MaPhongHoc', '$Thu', '$GioBatDau', '$GioKetThuc')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Lưu thành công'); window.location.href='thoikhoabieu.php';</script>";
    } else {
        echo "SQL ERROR: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html><html lang="vi" style=""><head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>Athena Admin - Thêm lịch học</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&amp;display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">
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
        body { font-family: 'Montserrat', sans-serif; }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            display: inline-block;
            vertical-align: middle;
        }
    </style>
</head>
<body class="bg-background text-on-background antialiased">
<!-- SideNavBar (Predicted Component) -->
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
<!-- Main Content Canvas -->
<main class="pl-64 pt-16 min-h-screen">
<div class="max-w-screen-xl mx-auto px-gutter py-8">
<!-- Breadcrumbs -->
<nav aria-label="Breadcrumb" class="flex mb-6">
<ol class="flex items-center space-x-2 text-label-md font-label-md text-secondary">
<li class="flex items-center">
<a class="hover:text-primary transition-colors" href="#">Thời khóa biểu</a>
<span class="material-symbols-outlined text-[16px] ml-2" data-icon="chevron_right">chevron_right</span>
</li>
<li class="text-on-surface font-bold">Thêm lịch học</li>
</ol>
</nav>
<!-- Page Title -->
<div class="mb-8">
<h2 class="font-headline-lg text-on-surface text-headline-md" style="opacity: 1;">Thêm lịch học mới</h2>
<p class="text-secondary font-body-md text-body-md mt-1">Thiết lập thời gian, giảng viên và phòng học cho lớp học mới.</p>
</div>
<!-- Form Card -->
<form method="POST">

<div class="bg-white rounded-2xl border border-outline-variant shadow-sm overflow-hidden">


    <div class="px-8 py-5 border-b border-outline-variant bg-surface-container-low">
        <h2 class="text-lg font-bold text-primary flex items-center gap-2">
            <span class="material-symbols-outlined">calendar_month</span>
            Tạo lịch học
        </h2>
        <p class="text-sm text-secondary mt-1">Thiết lập lớp học, phòng và thời gian giảng dạy</p>
    </div>

    <!-- BODY -->
    <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8">

        <!-- LEFT -->
        <div class="space-y-6">

            <h3 class="text-primary font-semibold flex items-center gap-2">
                <span class="material-symbols-outlined">info</span>
                Thông tin cơ bản
            </h3>

            <!-- Lớp học -->
            <div class="space-y-2">
                <label class="text-sm text-secondary">Lớp học</label>
                <select name="MaLopHoc"
                    class="w-full h-11 px-4 rounded-xl border border-outline-variant focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none bg-white">

                    <option value="">Chọn lớp học...</option>

                    <?php
                    $resLop = mysqli_query($conn, "SELECT MaLopHoc, TenLop FROM lophoc ORDER BY TenLop");
                    while($row = mysqli_fetch_assoc($resLop)){
                        echo "<option value='{$row['MaLopHoc']}'>{$row['TenLop']}</option>";
                    }
                    ?>

                </select>
            </div>

            <!-- Phòng học -->
            <div class="space-y-2">
                <label class="text-sm text-secondary">Phòng học</label>
                <select name="MaPhongHoc"
                    class="w-full h-11 px-4 rounded-xl border border-outline-variant focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none bg-white">

                    <option value="">Chọn phòng...</option>

                    <?php
                    $resPhong = mysqli_query($conn, "SELECT MaPhongHoc, TenPhong FROM phonghoc ORDER BY TenPhong");
                    while($row = mysqli_fetch_assoc($resPhong)){
                        echo "<option value='{$row['MaPhongHoc']}'>{$row['TenPhong']}</option>";
                    }
                    ?>

                </select>
            </div>

        </div>

        <!-- RIGHT -->
        <div class="space-y-6">

            <h3 class="text-primary font-semibold flex items-center gap-2">
                <span class="material-symbols-outlined">schedule</span>
                Thời gian
            </h3>

            <!-- DATE -->
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="text-sm text-secondary">Ngày bắt đầu</label>
                    <input type="date" name="NgayBatDau"
                        class="w-full h-11 px-4 rounded-xl border border-outline-variant focus:border-primary focus:ring-1 focus:ring-primary/20">
                </div>

                <div class="space-y-2">
                    <label class="text-sm text-secondary">Ngày kết thúc</label>
                    <input type="date" name="NgayKetThuc"
                        class="w-full h-11 px-4 rounded-xl border border-outline-variant focus:border-primary focus:ring-1 focus:ring-primary/20">
                </div>
            </div>

            <!-- TIME -->
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="text-sm text-secondary">Giờ bắt đầu</label>
                    <input type="time" name="GioBatDau"
                        class="w-full h-11 px-4 rounded-xl border border-outline-variant focus:border-primary focus:ring-1 focus:ring-primary/20">
                </div>

                <div class="space-y-2">
                    <label class="text-sm text-secondary">Giờ kết thúc</label>
                    <input type="time" name="GioKetThuc"
                        class="w-full h-11 px-4 rounded-xl border border-outline-variant focus:border-primary focus:ring-1 focus:ring-primary/20">
                </div>
            </div>

            <!-- THỨ -->
            <div class="space-y-2">
                <label class="text-sm text-secondary">Lặp lại hàng tuần</label>

                <div class="flex flex-wrap gap-2">

                    <?php
                    $thuList = [
                        2 => "T2",
                        3 => "T3",
                        4 => "T4",
                        5 => "T5",
                        6 => "T6",
                        7 => "T7",
                        8 => "CN"
                    ];

                    foreach ($thuList as $value => $label) {
                        echo "
                        <label>
                            <input type='checkbox' name='Thu[]' value='$value' class='hidden peer'>

                            <div class='w-11 h-11 rounded-xl border border-outline-variant flex items-center justify-center
                                text-secondary cursor-pointer select-none
                                peer-checked:bg-primary peer-checked:text-white peer-checked:border-primary
                                transition'>
                                $label
                            </div>
                        </label>
                        ";
                    }
                    ?>

                </div>
            </div>

        </div>

        <!-- NOTE -->
        <div class="md:col-span-2 space-y-2">

            <label class="text-sm text-secondary">Ghi chú</label>

            <textarea name="GhiChu" rows="4"
                class="w-full p-4 rounded-xl border border-outline-variant focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none resize-none"
                placeholder="Nhập lưu ý..."></textarea>

        </div>

    </div>

    <!-- FOOTER -->
    <div class="px-8 py-5 border-t border-outline-variant flex justify-end gap-4 bg-surface-container-low">

        <button type="button"
            onclick="window.location.href='thoikhoabieu.php'"
            class="px-6 py-2.5 rounded-xl border border-outline-variant text-secondary hover:bg-surface-container">
            Hủy
        </button>

        <button type="submit"
            class="px-6 py-2.5 rounded-xl bg-primary text-white font-semibold hover:brightness-110 shadow-md flex items-center gap-2">
            <span class="material-symbols-outlined text-[18px]">save</span>
            Lưu lịch học
        </button>

    </div>

</div>

</form>

</div>
</div>
</main>


</body></html>