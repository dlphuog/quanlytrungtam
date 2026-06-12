<?php
require_once __DIR__ . "/../config/connect.php";

if (isset($_POST['them'])) {
    $tenLop = $_POST['TenLop'] ?? '';
    $trinhDo = $_POST['TrinhDo'] ?? '';
    $maGiaoVien = $_POST['MaGiaoVien'] ?? '';
    $siSoToiDa = $_POST['SiSoToiDa'] ?? 0;
    $ngayBD = $_POST['NgayBatDau'] ?? date('Y-m-d');
    $ngayKT = $_POST['NgayKetThucDuKien'] ?? null;

    // Kiểm tra dữ liệu bắt buộc
    if (empty($tenLop) || empty($trinhDo) || empty($siSoToiDa)) {
        echo "<script>alert('Vui lòng điền đầy đủ các trường bắt buộc!'); window.history.back();</script>";
        exit;
    }

    // FIX cứng
    $maKhoaHoc = "KH001";
    $soBuoiDaHoc = 0;
    $siSoHienTai = 0;
    $trangThai = "DangHoc";
    $ngayKetThucThucTe = null;

    // ===== TỰ SINH MÃ LỚP LHxxx (PHẢI ĐẶT TRƯỚC INSERT) =====
    $result = $conn->query("
        SELECT MaLopHoc 
        FROM LopHoc 
        ORDER BY CAST(SUBSTRING(MaLopHoc, 3) AS UNSIGNED) DESC 
        LIMIT 1
    ");

    $last = $result->fetch_assoc();

    if ($last && !empty($last['MaLopHoc'])) {
        $num = (int) substr($last['MaLopHoc'], 2);
        $num++;
        $maLop = "LH" . str_pad($num, 3, "0", STR_PAD_LEFT);
    } else {
        $maLop = "LH001";
    }

    // SQL
    $sql = "INSERT INTO LopHoc
    (MaLopHoc, MaKhoaHoc, MaGiaoVien, TenLop, TrinhDo, SoBuoiDaHoc, SiSoToiDa, SiSoHienTai, TrangThai, NgayBatDau, NgayKetThucDuKien, NgayKetThucThucTe)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "sssssiisssss",
        $maLop,
        $maKhoaHoc,
        $maGiaoVien,
        $tenLop,
        $trinhDo,
        $soBuoiDaHoc,
        $siSoToiDa,
        $siSoHienTai,
        $trangThai,
        $ngayBD,
        $ngayKT,
        $ngayKetThucThucTe
    );

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>
            alert('Thêm lớp học thành công! Mã lớp: $maLop');
            window.location.href='lophoc.php';
        </script>";
        exit;
    } else {
        echo "<script>alert('Lỗi thêm lớp!');</script>";
    }
}
?>
<!DOCTYPE html>
<html class="light" lang="vi" style=""><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Tạo lớp học - Athena Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
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
        body { font-family: 'Montserrat', sans-serif; background-color: #f9f9ff; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2beba; border-radius: 10px; }
    </style>
</head>
<body class="bg-surface-container-low text-on-surface">
<!-- Side Navigation Bar -->
<aside class="fixed h-full w-64 left-0 top-0 bg-white border-r border-border-subtle shadow-sm flex flex-col z-50">
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
<main class="ml-[280px] pt-28 pb-12 px-8 min-h-screen flex flex-col relative">
<div class="max-w-6xl mx-auto flex flex-col flex-1 w-full justify-center">
<!-- Breadcrumbs -->
<div class="flex items-center gap-2 text-secondary font-label-sm text-label-sm mb-6">
<a class="hover:text-primary transition-colors" href="#">Lớp học</a>
<span class="material-symbols-outlined text-sm" data-icon="chevron_right">chevron_right</span>
<span class="text-primary font-bold">Thêm lớp học mới</span>
</div>
<!-- Main Form Card -->
<div class="bg-white rounded-3xl border border-outline-variant shadow-sm overflow-hidden flex flex-col lg:grid lg:grid-cols-[1fr_320px] mb-12">
<!-- Form Section -->
<div class="p-8 lg:p-10 border-b lg:border-b-0 lg:border-r border-outline-variant">
<div class="mb-8">
<h3 class="font-headline-lg text-2xl text-primary flex items-center gap-3">
<span class="material-symbols-outlined text-4xl" data-icon="add_card">add_card</span>
                            Thêm lớp học mới
                        </h3>
<p class="text-secondary font-body-md text-body-md mt-2">Thiết lập thông tin và xếp lịch cho lớp học mới trong hệ thống.</p>
</div>
<form method="POST" action="">

<!-- ROW 1 -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    <!-- Tên lớp -->
    <div class="space-y-2">
        <label class="font-label-md text-label-md text-on-surface">
            Tên lớp <span class="text-error">*</span>
        </label>
        <input name="TenLop"
            class="w-full px-5 py-3.5 rounded-xl border border-outline-variant
                   focus:border-primary focus:ring-1 focus:ring-primary/20
                   font-body-md outline-none transition-all"
            placeholder="VD: TOEIC Breakthrough A1"
            type="text"/>
    </div>

    <!--Sĩ số tối đa-->
    <div class="space-y-2">
    <label class="font-label-md text-label-md text-on-surface">
        Sĩ số tối đa <span class="text-error">*</span>
    </label>
    <input name="SiSoToiDa"
        class="w-full px-5 py-3.5 rounded-xl border border-outline-variant
               focus:border-primary focus:ring-1 focus:ring-primary/20
               font-body-md outline-none transition-all"
        placeholder="VD: 30"
        type="number" required />
</div>

    <!-- Khóa học -->
    <div class="space-y-2">
        <label class="font-label-md text-label-md text-on-surface">
            Khóa học <span class="text-error">*</span>
        </label>
        <select name="TrinhDo" required
    class="w-full px-5 py-3.5 rounded-xl border border-outline-variant
           focus:border-primary focus:ring-1 focus:ring-primary/20
           font-body-md outline-none appearance-none bg-white transition-all">
        <option value="" disabled selected>Chọn trình độ</option>
        <option value="TOEIC">TOEIC</option>
        <option value="IELTS">IELTS</option>
        <option value="Giao tiep">Giao tiếp căn bản</option>
    </select>
    </div>

    <!-- Giáo viên -->
    <div class="space-y-2">
        <label class="font-label-md text-label-md text-on-surface">
            Giáo viên <span class="text-error">*</span>
        </label>
        <select name="MaGiaoVien"
            class="w-full px-5 py-3.5 rounded-xl border border-outline-variant
                   focus:border-primary focus:ring-1 focus:ring-primary/20
                   font-body-md outline-none appearance-none bg-white transition-all">
            <option disabled selected>Chọn giáo viên</option>
            <option value="GV001">Nguyễn Văn A</option>
            <option value="GV002">Trần Thị B</option>
            <option value="GV003">Lê Văn C</option>
        </select>
    </div>

</div>

<!-- ROW 2 -->
<div class="mt-6">
    <label class="font-label-md text-label-md text-on-surface mb-2 block">
        Lịch học <span class="text-error">*</span>
    </label>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- Thứ -->
        <div class="space-y-2">
            <label class="font-label-sm text-secondary">Thứ trong tuần</label>
            <select name="ThuTrongTuan"
                class="w-full px-5 py-3.5 rounded-xl border border-outline-variant
                       focus:border-primary focus:ring-1 focus:ring-primary/20
                       font-body-md outline-none transition-all">
                <option value="2">Thứ 2</option>
                <option value="3">Thứ 3</option>
                <option value="4">Thứ 4</option>
                <option value="5">Thứ 5</option>
                <option value="6">Thứ 6</option>
                <option value="7">Thứ 7</option>
                <option value="8">Chủ nhật</option>
            </select>
        </div>

        <!-- Phòng -->
        <div class="space-y-2">
            <label class="font-label-sm text-secondary">Phòng học</label>
            <select name="MaPhongHoc"
                class="w-full px-5 py-3.5 rounded-xl border border-outline-variant
                       focus:border-primary focus:ring-1 focus:ring-primary/20
                       font-body-md outline-none transition-all bg-white">

                <?php
                $phong = $conn->query("SELECT * FROM PhongHoc");
                while($p = $phong->fetch_assoc()):
                ?>
                    <option value="<?= $p['MaPhongHoc'] ?>">
                        <?= $p['TenPhong'] ?>
                    </option>
                <?php endwhile; ?>

            </select>
        </div>

        <!-- Giờ bắt đầu -->
        <div class="space-y-2">
            <label class="font-label-sm text-secondary">Giờ bắt đầu</label>
            <input type="time" name="ThoiGianBatDau"
                class="w-full px-5 py-3.5 rounded-xl border border-outline-variant
                       focus:border-primary focus:ring-1 focus:ring-primary/20
                       font-body-md outline-none transition-all">
        </div>

        <!-- Giờ kết thúc -->
        <div class="space-y-2">
            <label class="font-label-sm text-secondary">Giờ kết thúc</label>
            <input type="time" name="ThoiGianKetThuc"
                class="w-full px-5 py-3.5 rounded-xl border border-outline-variant
                       focus:border-primary focus:ring-1 focus:ring-primary/20
                       font-body-md outline-none transition-all">
        </div>

    </div>
</div>

<!-- ROW 3 -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">

    <!-- Ngày bắt đầu -->
    <div class="space-y-2">
        <label class="font-label-md text-label-md text-on-surface">
            Ngày bắt đầu
        </label>
        <input name="NgayBatDau" type="date"
            class="w-full px-5 py-3.5 rounded-xl border border-outline-variant
                   focus:border-primary focus:ring-1 focus:ring-primary/20
                   font-body-md outline-none transition-all">
    </div>

    <!-- Ngày kết thúc -->
    <div class="space-y-2">
        <label class="font-label-md text-label-md text-on-surface">
            Ngày kết thúc (Dự kiến)
        </label>
        <input name="NgayKetThucDuKien" type="date"
            class="w-full px-5 py-3.5 rounded-xl border border-outline-variant
                   focus:border-primary focus:ring-1 focus:ring-primary/20
                   font-body-md outline-none transition-all">
    </div>

</div>

<!-- ACTION -->
<div class="pt-8 border-t border-outline-variant flex justify-end gap-3 mt-6">

    <button type="button"
        class="px-6 py-3 rounded-xl border border-outline text-secondary font-bold hover:bg-surface-container-low transition">
        Hủy bỏ
    </button>

    <button name="them" type="submit"
        class="px-8 py-3 rounded-xl bg-primary text-white font-bold hover:brightness-110 shadow-lg shadow-primary/30 transition">
        Thêm lớp học
    </button>

</div>

</form>
</div>
<!-- Info Sidebar -->
<div class="bg-surface-container-low/50 p-8 lg:p-10 flex flex-col gap-8">
<div>
<h4 class="font-label-md text-label-md text-primary mb-6 uppercase tracking-wider">Xem trước thông tin</h4>
<div class="space-y-6">
<div class="flex items-center gap-4">
<div class="w-10 h-10 rounded-xl bg-white border border-outline-variant flex items-center justify-center text-primary shadow-sm">
<span class="material-symbols-outlined text-2xl" data-icon="groups">groups</span>
</div>
<div>
<p class="text-[10px] text-secondary font-bold uppercase tracking-tight">Sĩ số dự kiến</p>
<p class="font-label-md text-label-md text-on-surface">20-25 Học viên</p>
</div>
</div>
<div class="flex items-center gap-4">
<div class="w-10 h-10 rounded-xl bg-white border border-outline-variant flex items-center justify-center text-primary shadow-sm">
<span class="material-symbols-outlined text-2xl" data-icon="assignment">assignment</span>
</div>
<div>
<p class="text-[10px] text-secondary font-bold uppercase tracking-tight">Tài liệu</p>
<p class="font-label-md text-label-md text-on-surface">Curriculum v.2.4</p>
</div>
</div>
</div>
</div>
<div class="mt-auto p-5 bg-white rounded-2xl border border-outline-variant shadow-sm">
<p class="font-body-sm text-body-sm text-secondary italic leading-relaxed">"Việc thiết lập mã lớp chính xác sẽ giúp tự động hóa quá trình xuất báo cáo cuối khóa."</p>
</div>
</div>
</div>

<!-- Success Toast (Hidden) -->
<div class="fixed bottom-10 right-10 bg-inverse-surface text-inverse-on-surface px-8 py-5 rounded-2xl shadow-2xl flex items-center gap-5 translate-y-24 opacity-0 transition-all duration-500 z-[100]" id="success-toast">
<span class="material-symbols-outlined text-green-400 text-3xl" data-icon="check_circle" style="font-variation-settings: 'FILL' 1;">check_circle</span>
<div>
<p class="font-bold text-lg">Lớp học đã được tạo!</p>
<p class="text-sm text-surface-variant">Đang chuyển hướng đến danh sách lớp...</p>
</div>
</div>
<script>
        // Micro-interactions
        const form = document.querySelector('form');
        const toast = document.getElementById('success-toast');


        form.addEventListener('submit', (e) => {
            // Show success animation
            toast.classList.remove('translate-y-24', 'opacity-0');
            toast.classList.add('translate-y-0', 'opacity-100');
           
            setTimeout(() => {
                toast.classList.add('translate-y-24', 'opacity-0');
                toast.classList.remove('translate-y-0', 'opacity-100');
            }, 3000);
        });


        // Focus interactions
        document.querySelectorAll('input, select').forEach(el => {
            el.addEventListener('focus', () => {
                const label = el.closest('.space-y-2, .space-y-3')?.querySelector('label');
                if(label) label.classList.add('text-primary');
            });
            el.addEventListener('blur', () => {
                const label = el.closest('.space-y-2, .space-y-3')?.querySelector('label');
                if(label) label.classList.remove('text-primary');
            });
        });
    </script>
</body></html>





