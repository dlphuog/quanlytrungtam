<?php
// 1. KẾT NỐI CƠ SỞ DỮ LIỆU (Bạn điều chỉnh lại cấu hình kết nối của bạn nếu cần)
$host = "localhost";
$user = "root";
$pass = "";
$db   = "quanlytrungtam"; // Thay bằng tên database thực tế của bạn

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
mysqli_set_charset($conn, 'utf8');

// 2. LẤY GIÁ TRỊ BỘ LỌC ĐƯỢC CHỌN (Mặc định lấy lớp đầu tiên)
$selected_lop  = isset($_GET['ma_lop']) ? $_GET['ma_lop'] : 'LH401';
$selected_ngay = isset($_GET['ngay_hoc']) ? $_GET['ngay_hoc'] : date('Y-m-d');
$selected_buoi = isset($_GET['ma_buoi']) ? $_GET['ma_buoi'] : '';

// Nếu chưa chọn buổi cụ thể, tự tìm buổi học của ngày đó hoặc buổi gần nhất thuộc lớp đó
if (empty($selected_buoi)) {
    $sql_check_buoi = "SELECT MaBuoiHoc FROM BuoiHoc WHERE MaLopHoc = '$selected_lop' AND NgayHoc = '$selected_ngay' LIMIT 1";
    $res_check = $conn->query($sql_check_buoi);
    if ($res_check && $res_check->num_rows > 0) {
        $selected_buoi = $res_check->fetch_assoc()['MaBuoiHoc'];
    } else {
        $sql_fallback = "SELECT MaBuoiHoc, NgayHoc FROM BuoiHoc WHERE MaLopHoc = '$selected_lop' LIMIT 1";
        $res_fallback = $conn->query($sql_fallback);
        if ($res_fallback && $res_fallback->num_rows > 0) {
            $row_fb = $res_fallback->fetch_assoc();
            $selected_buoi = $row_fb['MaBuoiHoc'];
            $selected_ngay = $row_fb['NgayHoc'];
        }
    }
}

// 3. XỬ LÝ LƯU ĐIỂM DANH KHI BẤM NÚT SUBMIT
$thong_bao_luu = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_save'])) {
    $buoi_id = $_POST['form_ma_buoi'];
    if (!empty($buoi_id) && isset($_POST['trang_thai'])) {
        foreach ($_POST['trang_thai'] as $ma_hv => $status) {
            $ghi_chu = isset($_POST['ghi_chu'][$ma_hv]) ? mysqli_real_escape_string($conn, $_POST['ghi_chu'][$ma_hv]) : '';
            
            // Kiểm tra xem học viên này đã có bản ghi điểm danh ở buổi này chưa
            $sql_check_exist = "SELECT MaDiemDanh FROM DiemDanh WHERE MaBuoiHoc = '$buoi_id' AND MaHocVien = '$ma_hv'";
            $check_res = $conn->query($sql_check_exist);
            
            if ($check_res && $check_res->num_rows > 0) {
                // Đã có -> Cập nhật (UPDATE)
                $sql_save = "UPDATE DiemDanh SET TrangThai = N'$status', GhiChu = N'$ghi_chu' WHERE MaBuoiHoc = '$buoi_id' AND MaHocVien = '$ma_hv'";
            } else {
                // Chưa có -> Tạo mới (INSERT)
                $new_id = 'DD' . rand(100, 999);
                $sql_save = "INSERT INTO DiemDanh (MaDiemDanh, MaBuoiHoc, MaHocVien, TrangThai, GhiChu) VALUES ('$new_id', '$buoi_id', '$ma_hv', N'$status', N'$ghi_chu')";
            }
            $conn->query($sql_save);
        }
        $thong_bao_luu = "<script>alert('Lưu dữ liệu điểm danh thành công!');</script>";
    }
}

// 4. LẤY DỮ LIỆU ĐỂ HIỂN THỊ THỐNG KÊ (BENTO BOX)
$tong_hv = 0; $co_mat = 0; $vang = 0; $co_phep = 0;
if (!empty($selected_buoi)) {
    // Tổng học viên thuộc lớp này
    $res_tong = $conn->query("SELECT COUNT(*) as total FROM ChiTietLopHoc WHERE MaLopHoc = '$selected_lop'");
    $tong_hv = $res_tong ? $res_tong->fetch_assoc()['total'] : 0;

    // Đếm số lượng theo trạng thái điểm danh thực tế
    $res_cm = $conn->query("SELECT COUNT(*) as total FROM DiemDanh WHERE MaBuoiHoc = '$selected_buoi' AND TrangThai = N'Có mặt'");
    $co_mat = $res_cm ? $res_cm->fetch_assoc()['total'] : 0;

    $res_v = $conn->query("SELECT COUNT(*) as total FROM DiemDanh WHERE MaBuoiHoc = '$selected_buoi' AND TrangThai = N'Vắng' AND (GhiChu NOT LIKE N'%phép%' OR GhiChu IS NULL)");
    $vang = $res_v ? $res_v->fetch_assoc()['total'] : 0;

    $res_cp = $conn->query("SELECT COUNT(*) as total FROM DiemDanh WHERE MaBuoiHoc = '$selected_buoi' AND (TrangThai = N'Có phép' OR GhiChu LIKE N'%phép%' OR GhiChu LIKE N'%xin nghỉ%') AND TrangThai = N'Vắng'");
    $co_phep = $res_cp ? $res_cp->fetch_assoc()['total'] : 0;
    // Nếu db lưu chữ vắng nhưng ghi chú có phép, ta linh hoạt gộp hiển thị
}

// 5. TRUY VẤN DANH SÁCH HỌC VIÊN VÀ TRẠNG THÁI ĐIỂM DANH HIỆN TẠI
$ds_hoc_vien = [];
if (!empty($selected_buoi)) {
    $sql_hv = "SELECT hv.MaHocVien, nd.HoTen, lh.TenLop, dd.TrangThai, dd.GhiChu
               FROM ChiTietLopHoc ctlh
               JOIN HocVien hv ON ctlh.MaHocVien = hv.MaHocVien
               JOIN TaiKhoan tk ON hv.MaTaiKhoan = tk.MaTaiKhoan
               JOIN NguoiDung nd ON tk.MaNguoiDung = nd.MaNguoiDung
               JOIN LopHoc lh ON ctlh.MaLopHoc = lh.MaLopHoc
               LEFT JOIN DiemDanh dd ON dd.MaHocVien = hv.MaHocVien AND dd.MaBuoiHoc = '$selected_buoi'
               WHERE ctlh.MaLopHoc = '$selected_lop'";
    $res_hv = $conn->query($sql_hv);
    if ($res_hv) {
        while ($row = $res_hv->fetch_assoc()) {
            $ds_hoc_vien[] = $row;
        }
    }
}
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
        <img alt="Athena Admin Logo" class="w-20 h-auto mx-auto mb-4" src="/quanlytrungtam/logo.jpg">
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
        <a class="flex items-center gap-3 px-4 py-2.5 bg-primary/10 text-primary rounded-lg transition-colors" href="DIEMDANH.php">
            <span class="material-symbols-outlined text-[20px]">fact_check</span>
            <span class="font-label-md text-label-md">Điểm danh</span>
        </a>
        <a class="flex items-center gap-3 px-4 py-2.5 text-secondary hover:text-primary hover:bg-primary/5 rounded-lg transition-colors" href="KETQUAHOCTAP.php">
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


<?php echo $thong_bao_luu; ?>

<form id="attendanceForm" method="POST" action="">
    <input type="hidden" name="form_ma_buoi" value="<?php echo htmlspecialchars($selected_buoi); ?>">

    <main class="ml-64 flex-grow min-h-screen flex flex-col overflow-y-auto p-8 gap-6">
        
         <header class="flex justify-between items-center border-b pb-4">
            <h2 class="text-2xl font-bold text-on-surface"> Điểm danh</h2>
            <button type="submit" name="btn_save"
                class="px-4 py-1.5 bg-[#bb0025] text-white rounded-xl font-bold flex items-center gap-2 hover:opacity-90 transition-all shadow-md text-sm">
                <span class="material-symbols-outlined text-base">save</span>
                    Lưu điểm danh
            </button>
        </header>

        <section class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-outline-variant">
                <p class="text-sm font-semibold text-secondary uppercase mb-1">Tổng học viên lớp</p>
                <h3 class="text-3xl font-bold"><?php echo $tong_hv; ?></h3>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-sm border border-outline-variant">
                <p class="text-sm font-semibold text-green-600 uppercase mb-1">Đã có mặt</p>
                <h3 class="text-3xl font-bold text-green-600"><?php echo $co_mat; ?></h3>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-sm border border-outline-variant">
                <p class="text-sm font-semibold text-red-600 uppercase mb-1">Vắng mặt (Không phép)</p>
                <h3 class="text-3xl font-bold text-red-600"><?php echo $vang; ?></h3>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-sm border border-outline-variant">
                <p class="text-sm font-semibold text-orange-600 uppercase mb-1">Vắng có phép</p>
                <h3 class="text-3xl font-bold text-orange-600"><?php echo $co_phep; ?></h3>
            </div>
        </section>

        <div class="bg-white p-6 rounded-xl shadow-sm border border-outline-variant">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <div class="space-y-2">
                    <label class="text-sm font-bold">Chọn lớp học</label>
                    <select onchange="location.href='diemdanh.php?ma_lop=' + this.value" class="w-full p-2.5 bg-gray-50 border rounded-xl outline-none">
                        <?php
                        $lops = $conn->query("SELECT MaLopHoc, TenLop FROM LopHoc");
                        while($l = $lops->fetch_assoc()) {
                            $sel = ($l['MaLopHoc'] == $selected_lop) ? 'selected' : '';
                            echo "<option value='{$l['MaLopHoc']}' $sel>{$l['MaLopHoc']} - {$l['TenLop']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-bold">Ngày học của lớp</label>
                    <input type="date" value="<?php echo $selected_ngay; ?>" onchange="location.href='diemdanh.php?ma_lop=<?php echo $selected_lop; ?>&ngay_hoc=' + this.value" class="w-full p-2.5 bg-gray-50 border rounded-xl outline-none" />
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-bold">Mã buổi học đang chọn</label>
                    <select onchange="location.href='diemdanh.php?ma_lop=<?php echo $selected_lop; ?>&ma_buoi=' + this.value" class="w-full p-2.5 bg-gray-50 border rounded-xl outline-none font-bold text-primary">
                        <?php
                        $buois = $conn->query("SELECT MaBuoiHoc, NgayHoc FROM BuoiHoc WHERE MaLopHoc = '$selected_lop'");
                        if($buois && $buois->num_rows > 0) {
                            while($b = $buois->fetch_assoc()) {
                                $sel = ($b['MaBuoiHoc'] == $selected_buoi) ? 'selected' : '';
                                echo "<option value='{$b['MaBuoiHoc']}' $sel>{$b['MaBuoiHoc']} (Ngày {$b['NgayHoc']})</option>";
                            }
                        } else {
                            echo "<option value=''>-- Không có buổi học nào --</option>";
                        }
                        ?>
                    </select>
                </div>

            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-outline-variant flex flex-col">
            <div class="px-6 py-4 border-b flex justify-between items-center bg-gray-50">
                <h4 class="font-bold text-lg">Danh sách học viên lớp này</h4>
                <span class="px-3 py-1 bg-red-100 text-primary rounded-full font-bold text-xs"><?php echo count($ds_hoc_vien); ?> Học viên</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-100 border-b">
                            <th class="px-6 py-3 font-bold text-sm text-secondary uppercase">Mã HV</th>
                            <th class="px-6 py-3 font-bold text-sm text-secondary uppercase">Học viên</th>
                            <th class="px-6 py-3 font-bold text-sm text-secondary uppercase">Tên lớp</th>
                            <th class="px-6 py-3 font-bold text-sm text-secondary uppercase text-center">Trạng thái điểm danh</th>
                            <th class="px-6 py-3 font-bold text-sm text-secondary uppercase">Ghi chú từ Giáo viên</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        <?php if(empty($ds_hoc_vien)): ?>
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-gray-400">Không có dữ liệu học viên cho bộ lọc này. Hãy thử chọn lớp khác hoặc buổi khác!</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach($ds_hoc_vien as $hv): 
                                $status = !empty($hv['TrangThai']) ? $hv['TrangThai'] : 'Có mặt'; // Mặc định tích sẵn Có mặt
                            ?>
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 text-sm font-semibold text-secondary"><?php echo $hv['MaHocVien']; ?></td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-primary text-white font-bold flex items-center justify-center text-xs">
                                                <?php echo substr(strrchr($hv['HoTen'], " "), 1, 2) ?: substr($hv['HoTen'], 0, 2); ?>
                                            </div>
                                            <span class="font-bold text-sm"><?php echo $hv['HoTen']; ?></span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-xs font-bold text-gray-500"><?php echo $hv['TenLop']; ?></td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-center gap-6">
                                            <label class="flex items-center cursor-pointer gap-2">
                                                <input type="radio" name="trang_thai[<?php echo $hv['MaHocVien']; ?>]" value="Có mặt" <?php echo ($status == 'Có mặt') ? 'checked' : ''; ?> class="text-green-600 focus:ring-green-500">
                                                <span class="text-sm font-medium text-green-700">Có mặt</span>
                                            </label>
                                            
                                            <label class="flex items-center cursor-pointer gap-2">
                                                <input type="radio" name="trang_thai[<?php echo $hv['MaHocVien']; ?>]" value="Vắng" <?php echo ($status == 'Vắng') ? 'checked' : ''; ?> class="text-red-600 focus:ring-red-500">
                                                <span class="text-sm font-medium text-red-600">Vắng</span>
                                            </label>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <input type="text" name="ghi_chu[<?php echo $hv['MaHocVien']; ?>]" value="<?php echo htmlspecialchars($hv['GhiChu'] ?? ''); ?>" placeholder="Nhập ghi chú (VD: Vào muộn 15p...)" class="w-full bg-transparent border-b border-gray-200 focus:border-primary outline-none text-sm py-1 transition-all">
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</form>
</body>
</html>