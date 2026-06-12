<?php
include("../config/connect.php");

if (isset($_POST['them'])) {
    // 1. Kiểm tra dữ liệu rỗng trước khi xử lý
    if (empty($_POST['hoten']) || empty($_POST['sdt'])) {
        echo "<script>alert('Vui lòng nhập Họ tên và Số điện thoại!'); window.history.back();</script>";
        exit();
    }

    // Bắt đầu Transaction để đảm bảo tính toàn vẹn dữ liệu
    mysqli_begin_transaction($conn);

    try {
        $maND = "ND" . time();
        $maTK = "TK" . time();
        $maHV = "HV" . time();

        // 2. Chèn vào bảng Người dùng
        $sqlND = "INSERT INTO nguoidung (MaNguoiDung, HoTen, GioiTinh, NgaySinh, SDT, DiaChi) 
                  VALUES ('$maND', '{$_POST['hoten']}', '{$_POST['gioitinh']}', '{$_POST['ngaysinh']}', '{$_POST['sdt']}', '{$_POST['diachi']}')";
        mysqli_query($conn, $sqlND);

        // 3. Chèn vào bảng Tài khoản
        $sqlTK = "INSERT INTO taikhoan (MaTaiKhoan, MaNguoiDung, Username, Password, VaiTro, TrangThai) 
                  VALUES ('$maTK', '$maND', 'hv_" . time() . "', '123456', 'HV', 'HoatDong')";
        mysqli_query($conn, $sqlTK);

        // 4. Chèn vào bảng Học viên
        $sqlHV = "INSERT INTO hocvien (MaHocVien, MaTaiKhoan, TrinhDoHienTai, MucTieuHocTap) 
                  VALUES ('$maHV', '$maTK', '{$_POST['trinhdo']}', '{$_POST['muctieu']}')";
        mysqli_query($conn, $sqlHV);

        // Nếu mọi câu lệnh đều thành công, thực hiện lưu (Commit)
        mysqli_commit($conn);

        echo "<script>alert('Thêm học viên thành công!'); window.location='hocvien.php';</script>";
    } catch (Exception $e) {
        // Nếu có lỗi, hủy bỏ tất cả các bước đã thực hiện (Rollback)
        mysqli_rollback($conn);
        echo "<script>alert('Lỗi hệ thống: " . $e->getMessage() . "'); window.history.back();</script>";
    }
}
?>
<!DOCTYPE html><html class="light" lang="vi" style=""><head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>Thêm học viên | Athena English</title>
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
<body class="bg-surface font-body-md text-on-surface">
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
<nav class="flex-1 overflow-y-auto py-2 px-4 space-y-0.5">


    <a class="flex items-center gap-3 px-4 py-2.5 text-secondary hover:text-primary hover:bg-primary/5 rounded-lg transition-colors"
       href="dashboard.php">
        <span class="material-symbols-outlined text-[20px]">dashboard</span>
        <span class="font-label-md text-label-md">Dashboard</span>
    </a>


    <a class="flex items-center gap-3 px-4 py-2.5 bg-primary/10 text-primary rounded-lg transition-colors"
       href="hocvien.php">
        <span class="material-symbols-outlined text-[20px] fill">group</span>
        <span class="font-label-md text-label-md">Học viên</span>
    </a>


    <a class="flex items-center gap-3 px-4 py-2.5 text-secondary hover:text-primary hover:bg-primary/5 rounded-lg transition-colors"
       href="lophoc.php">
        <span class="material-symbols-outlined text-[20px]">school</span>
        <span class="font-label-md text-label-md">Lớp học</span>
    </a>


    <a class="flex items-center gap-3 px-4 py-2.5 text-secondary hover:text-primary hover:bg-primary/5 rounded-lg transition-colors"
       href="giaovien.php">
        <span class="material-symbols-outlined text-[20px]">record_voice_over</span>
        <span class="font-label-md text-label-md">Giáo viên</span>
    </a>


    <a class="flex items-center gap-3 px-4 py-2.5 text-secondary hover:text-primary hover:bg-primary/5 rounded-lg transition-colors"
       href="thoikhoabieu.php">
        <span class="material-symbols-outlined text-[20px]">calendar_today</span>
        <span class="font-label-md text-label-md">Thời khóa biểu</span>
    </a>


    <a class="flex items-center gap-3 px-4 py-2.5 text-secondary hover:text-primary hover:bg-primary/5 rounded-lg transition-colors"
       href="diemdanh.php">
        <span class="material-symbols-outlined text-[20px]">fact_check</span>
        <span class="font-label-md text-label-md">Điểm danh</span>
    </a>


    <a class="flex items-center gap-3 px-4 py-2.5 text-secondary hover:text-primary hover:bg-primary/5 rounded-lg transition-colors"
       href="ketquahoctap.php">
        <span class="material-symbols-outlined text-[20px]">analytics</span>
        <span class="font-label-md text-label-md">Kết quả học tập</span>
    </a>


    <a class="flex items-center gap-3 px-4 py-2.5 text-secondary hover:text-primary hover:bg-primary/5 rounded-lg transition-colors"
       href="baocao.php">
        <span class="material-symbols-outlined text-[20px]">assessment</span>
        <span class="font-label-md text-label-md">Báo cáo</span>
    </a>


    <a class="flex items-center gap-3 px-4 py-2.5 text-secondary hover:text-primary hover:bg-primary/5 rounded-lg transition-colors"
       href="caidat.php">
        <span class="material-symbols-outlined text-[20px]">settings</span>
        <span class="font-label-md text-label-md">Cài đặt</span>
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
<!-- Content Wrapper -->
<div class="flex-1 md:ml-64 flex flex-col min-h-screen">
<!-- Main Form Content -->
<main class="p-margin-mobile md:p-margin-desktop flex-1">
<div class="max-w-5xl mx-auto space-y-gutter">
<!-- Page Breadcrumbs -->
<nav class="flex items-center gap-2 text-label-sm font-label-sm text-on-secondary-container">
<span class="hover:text-primary cursor-pointer"> Học viên</span>
<span class="material-symbols-outlined text-[16px]">chevron_right</span>
<span class="text-on-surface">Thêm học viên mới</span>
</nav>
<form method="POST">
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

<!-- LEFT -->
<div class="lg:col-span-2 space-y-6">

<!-- Thông tin cá nhân -->
<div class="bg-white border border-outline-variant rounded-2xl overflow-hidden shadow-sm">

<div class="px-6 py-4 border-b border-outline-variant bg-surface-container-low">
<h3 class="font-headline-md text-[15px] text-primary flex items-center gap-2">
<span class="material-symbols-outlined text-[20px]">person</span>
Thông tin cá nhân
</h3>
</div>

<div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">

<div class="space-y-2">
<label class="font-label-md text-label-sm text-secondary">Họ tên *</label>
<input name="hoten"
class="w-full h-11 px-4 rounded-xl border border-outline-variant focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none"
placeholder="VD: Nguyễn Văn A" type="text">
</div>

<div class="space-y-2">
<label class="font-label-md text-label-sm text-secondary">Giới tính</label>
<select name="gioitinh"
class="w-full h-11 px-4 rounded-xl border border-outline-variant focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none bg-white">
<option value="">Chọn giới tính</option>
<option value="Nam">Nam</option>
<option value="Nữ">Nữ</option>
<option value="Khác">Khác</option>
</select>
</div>

<div class="space-y-2 md:col-span-2">
<label class="font-label-md text-label-sm text-secondary">Ngày sinh</label>
<input name="ngaysinh" type="date"
class="w-full h-11 px-4 rounded-xl border border-outline-variant focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none">
</div>

</div>
</div>

<!-- Thông tin liên lạc -->
<div class="bg-white border border-outline-variant rounded-2xl overflow-hidden shadow-sm">

<div class="px-6 py-4 border-b border-outline-variant bg-surface-container-low">
<h3 class="font-headline-md text-[15px] text-primary flex items-center gap-2">
<span class="material-symbols-outlined text-[20px]">contact_page</span>
Thông tin liên lạc
</h3>
</div>

<div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">

<div class="space-y-2">
<label class="text-secondary text-sm">Số điện thoại *</label>
<input name="sdt" type="tel"
class="w-full h-11 px-4 rounded-xl border border-outline-variant focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none"
placeholder="0901 234 567">
</div>

<div class="space-y-2">
<label class="text-secondary text-sm">Email</label>
<input name="email" type="email"
class="w-full h-11 px-4 rounded-xl border border-outline-variant focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none"
placeholder="example@gmail.com">
</div>

<div class="space-y-2 md:col-span-2">
<label class="text-secondary text-sm">Địa chỉ</label>
<textarea name="diachi" rows="3"
class="w-full p-4 rounded-xl border border-outline-variant focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none resize-none"
placeholder="Số nhà, đường, phường/xã..."></textarea>
</div>

</div>
</div>

</div>

<!-- RIGHT -->
<div class="space-y-6">

<!-- Avatar -->
<div class="bg-white border border-outline-variant rounded-2xl p-6 text-center shadow-sm">

<div class="w-32 h-32 mx-auto rounded-2xl border-2 border-dashed border-outline-variant flex items-center justify-center hover:border-primary transition">
<span class="material-symbols-outlined text-3xl text-secondary">add_a_photo</span>
</div>

<p class="text-xs text-secondary mt-3">Ảnh học viên (JPG/PNG, ≤2MB)</p>
</div>

<!-- Học thuật -->
<div class="bg-white border border-outline-variant rounded-2xl overflow-hidden shadow-sm">

<div class="px-6 py-4 border-b border-outline-variant bg-surface-container-low">
<h3 class="font-headline-md text-[15px] text-primary flex items-center gap-2">
<span class="material-symbols-outlined text-[20px]">school</span>
Học thuật
</h3>
</div>

<div class="p-6 space-y-5">

<div class="space-y-2">
<label class="text-secondary text-sm">Trình độ</label>
<select name="trinhdo"
class="w-full h-11 px-4 rounded-xl border border-outline-variant focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none bg-white">
<option value="beginner">Beginner</option>
<option value="intermediate">Intermediate</option>
<option value="advanced">Advanced</option>
</select>
</div>

<div class="space-y-2">
<label class="text-secondary text-sm">Mục tiêu</label>
<input name="muctieu"
class="w-full h-11 px-4 rounded-xl border border-outline-variant focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none"
placeholder="VD: IELTS 6.5">
</div>

<div class="space-y-2">
<label class="text-secondary text-sm">Khóa học</label>
<select name="khoahoc"
class="w-full h-11 px-4 rounded-xl border border-outline-variant focus:border-primary focus:ring-1 focus:ring-primary/20 outline-none bg-white">
<option value="">Chọn khóa học</option>
<option value="toeic500">TOEIC 500+</option>
<option value="ielts">IELTS</option>
<option value="giao_tiep">Giao tiếp</option>
</select>
</div>

</div>
</div>

</div>
</div>

<!-- ACTION -->
<div class="flex justify-end gap-4 pt-6 border-t border-outline-variant">

<button type="button"
class="px-8 py-2.5 rounded-xl border border-outline-variant text-secondary hover:bg-surface-container-low transition">
Hủy
</button>

<button name="them" type="submit"
class="px-8 py-2.5 rounded-xl bg-primary text-white font-semibold hover:brightness-110 shadow-md flex items-center gap-2">
<span class="material-symbols-outlined text-[18px]">save</span>
Lưu học viên
</button>

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
<div class="flex flex-col items-center text-primary">
<span class="material-symbols-outlined" data-weight="fill">school</span>
<span class="text-[10px] font-bold">Học viên</span>
</div>
<div class="flex flex-col items-center text-on-secondary-container">
<span class="material-symbols-outlined">book_2</span>
<span class="text-[10px]">Lớp học</span>
</div>
<div class="flex flex-col items-center text-on-secondary-container">
<span class="material-symbols-outlined">logout</span>
<span class="text-[10px]">Thoát</span>
</div>
</div>








</body></html>


