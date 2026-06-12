<?php
// 1. Khởi động Session và Kiểm tra đăng nhập
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Nếu chưa có session đăng nhập, giả lập tài khoản 'TK005' (Mã Admin trong DB mới của bạn) để kiểm thử dữ liệu
if (!isset($_SESSION['MaTaiKhoan'])) {
    $_SESSION['MaTaiKhoan'] = 'TK005'; 
    $_SESSION['Username'] = 'admin_athena';
    $_SESSION['VaiTro'] = 'NV';
}

$maTaiKhoan = $_SESSION['MaTaiKhoan'];

// 2. Kết nối Cơ sở dữ liệu `athena_db`
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Quanlytrungtam"; // Đảm bảo tên database trùng với tên trong MySQL của bạn

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Kết nối database thất bại: " . $conn->connect_error);
}
$conn->set_charset("utf8");

$error_msg = "";
$success_msg = "";

// 3. TRUY VẤN LẤY THÔNG TIN ĐỂ HIỂN THỊ LÊN FORM
$hoTen = ""; $gioiTinh = ""; $ngaySinh = ""; $sdt = ""; $diaChi = ""; $email = ""; $vaiTroText = "";

$sql_user = "SELECT tk.Email, tk.VaiTro, nd.HoTen, nd.GioiTinh, nd.NgaySinh, nd.SDT, nd.DiaChi 
             FROM TaiKhoan tk
             JOIN NguoiDung nd ON tk.MaNguoiDung = nd.MaNguoiDung
             WHERE tk.MaTaiKhoan = '$maTaiKhoan'";

$result_user = $conn->query($sql_user);
if ($result_user && $result_user->num_rows > 0) {
    $user_data = $result_user->fetch_assoc();
    $hoTen     = $user_data['HoTen'];
    $gioiTinh  = $user_data['GioiTinh'];
    $ngaySinh  = $user_data['NgaySinh'];
    $sdt       = $user_data['SDT'];
    $diaChi    = $user_data['DiaChi'];
    $email     = $user_data['Email'];
    
    // Hiển thị tên vai trò người dùng
    if ($user_data['VaiTro'] == 'NV') $vaiTroText = "Quản trị viên (Admin)";
    elseif ($user_data['VaiTro'] == 'GV') $vaiTroText = "Giáo viên";
    else $vaiTroText = "Học viên";
}

// 4. XỬ LÝ LƯU DỮ LIỆU KHI BẤM NÚT "LƯU THAY ĐỔI"
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'update_profile') {
    $input_hoten    = mysqli_real_escape_string($conn, $_POST['profile_name']);
    $input_sdt      = mysqli_real_escape_string($conn, $_POST['profile_phone']);
    $input_diachi   = mysqli_real_escape_string($conn, $_POST['profile_address']);
    $input_gioitinh = mysqli_real_escape_string($conn, $_POST['profile_gender']);
    $input_ngaysinh = mysqli_real_escape_string($conn, $_POST['profile_dob']);
    
    // Câu lệnh cập nhật ngược lại bảng NguoiDung liên kết với bảng TaiKhoan
    $sql_update = "UPDATE NguoiDung nd
                   JOIN TaiKhoan tk ON tk.MaNguoiDung = nd.MaNguoiDung
                   SET nd.HoTen = N'$input_hoten', 
                       nd.SDT = '$input_sdt', 
                       nd.DiaChi = N'$input_diachi', 
                       nd.GioiTinh = N'$input_gioitinh', 
                       nd.NgaySinh = '$input_ngaysinh'
                   WHERE tk.MaTaiKhoan = '$maTaiKhoan'";
                   
    if ($conn->query($sql_update) === TRUE) {
        $success_msg = "Cập nhật thông tin cá nhân thành công!";
        // Làm mới lại dữ liệu hiển thị trên form sau khi lưu thành công
        $hoTen = $input_hoten; $sdt = $input_sdt; $diaChi = $input_diachi; $gioiTinh = $input_gioitinh; $ngaySinh = $input_ngaysinh;
    } else {
        $error_msg = "Có lỗi xảy ra: " . $conn->error;
    }
}

// 5. XỬ LÝ ĐỔI MẬT KHẨU (Tab Bảo mật)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'change_password') {
    $current_password = $_POST['current_password'];
    $new_password     = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    if ($new_password !== $confirm_password) {
        $error_msg = "Mật khẩu mới và xác nhận mật khẩu không khớp!";
    } else {
        $sql_pass = "SELECT Password FROM TaiKhoan WHERE MaTaiKhoan = '$maTaiKhoan'";
        $res_pass = $conn->query($sql_pass);
        if ($res_pass && $res_pass->num_rows > 0) {
            $row_p = $res_pass->fetch_assoc();
            if ($current_password !== $row_p['Password']) {
                $error_msg = "Mật khẩu hiện tại không chính xác!";
            } else {
                $sql_up_pass = "UPDATE TaiKhoan SET Password = '$new_password' WHERE MaTaiKhoan = '$maTaiKhoan'";
                if ($conn->query($sql_up_pass) === TRUE) {
                    $success_msg = "Đổi mật khẩu thành công!";
                } else {
                    $error_msg = "Lỗi cập nhật mật khẩu: " . $conn->error;
                }
            }
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
        <a class="flex items-center gap-3 px-4 py-2.5 text-secondary hover:text-primary hover:bg-primary/5 rounded-lg transition-colors" href="diemdanh.php">
            <span class="material-symbols-outlined text-[20px]">fact_check</span>
            <span class="font-label-md text-label-md">Điểm danh</span>
        </a>
        <a class="flex items-center gap-3 px-4 py-2.5 text-secondary hover:text-primary hover:bg-primary/5 rounded-lg transition-colors" href="ketquahoctap.php">
            <span class="material-symbols-outlined text-[20px]">analytics</span>
            <span class="font-label-md text-label-md">Kết quả học tập</span>
        </a>
        <a class="flex items-center gap-3 px-4 py-2.5 text-secondary hover:text-primary hover:bg-primary/5 rounded-lg transition-colors" href="baocao.php">
            <span class="material-symbols-outlined text-[20px]">assessment</span>
            <span class="font-label-md text-label-md">Báo cáo</span>
        </a>
        <a class="flex items-center gap-3 px-4 py-2.5 bg-primary/10 text-primary rounded-lg transition-colors" href="caidat.php">
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

<main class="ml-sidebar-width min-h-screen">
    <header class="flex justify-between items-center h-16 px-gutter sticky top-0 z-40 bg-surface/80 backdrop-blur-md border-b border-surface-container-highest">
        <div class="flex items-center">
            <h2 class="font-headline-md text-headline-md font-bold text-on-surface">Cài đặt hệ thống</h2>
        </div>
  
            <div class="flex items-center gap-4">
                
                
            </div>
        </div>
    </header>

    <div class="p-unit-lg">
        
        <?php if(!empty($error_msg)): ?>
            <div class="mb-5 p-4 bg-red-50 border-l-4 border-error text-error rounded-xl flex items-center gap-2 shadow-sm">
                <span class="material-symbols-outlined">error</span>
                <span class="font-label-md"><?php echo htmlspecialchars($error_msg); ?></span>
            </div>
        <?php endif; ?>

        <?php if(!empty($success_msg)): ?>
            <div class="mb-5 p-4 bg-green-50 border-l-4 border-green-600 text-green-800 rounded-xl flex items-center gap-2 shadow-sm">
                <span class="material-symbols-outlined">check_circle</span>
                <span class="font-label-md"><?php echo htmlspecialchars($success_msg); ?></span>
            </div>
        <?php endif; ?>

        <div class="flex flex-col lg:flex-row gap-gutter">
            <aside class="w-full lg:w-64 shrink-0">
                <nav class="flex flex-row lg:flex-col gap-1 bg-surface-container-lowest p-2 rounded-xl border border-outline-variant overflow-x-auto lg:overflow-x-visible">
                    <button onclick="switchTab('profile')" data-tab="profile" class="nav-item inner-nav-active flex items-center gap-3 px-4 py-2.5 rounded-lg text-label-md transition-all whitespace-nowrap text-left w-full">
                        <span class="material-symbols-outlined text-[20px]">account_circle</span>
                        Thông tin cá nhân
                    </button>
                    <button onclick="switchTab('security')" data-tab="security" class="nav-item text-secondary hover:text-primary flex items-center gap-3 px-4 py-2.5 rounded-lg text-label-md transition-all whitespace-nowrap text-left w-full">
                        <span class="material-symbols-outlined text-[20px]">lock</span>
                        Mật khẩu & Bảo mật
                    </button>
                    <button onclick="switchTab('history')" data-tab="history" class="nav-item text-secondary hover:text-primary flex items-center gap-3 px-4 py-2.5 rounded-lg text-label-md transition-all whitespace-nowrap text-left w-full">
                        <span class="material-symbols-outlined text-[20px]">history</span>
                        Lịch sử đăng nhập
                    </button>
                </nav>
            </aside>

            <div class="flex-1 bg-surface-container-lowest rounded-xl border border-outline-variant p-card-padding shadow-sm">
                
                <section id="profile" class="tab-content active space-y-6">
                   <section class="bg-white dark:bg-surface-container rounded-2xl border border-outline-variant p-6 shadow-sm flex flex-col gap-6">
    <div class="flex flex-col gap-1">
        <h3 class="text-title-md font-bold text-on-surface">Thông tin tài khoản</h3>
        <p class="text-body-sm text-secondary">Cập nhật thông tin cá nhân và thông tin liên hệ của bạn tại đây.</p>
    </div>
    
    <form method="POST" action="">
        <input type="hidden" name="action" value="update_profile">
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="flex flex-col gap-2">
                <label class="text-label-md font-semibold text-secondary" for="profile_name">Họ và tên</label>
                <input class="w-full px-4 py-2.5 rounded-xl border border-outline bg-surface text-on-surface focus:outline-none focus:border-primary font-medium text-body-medium" 
                       id="profile_name" name="profile_name" type="text" value="<?php echo htmlspecialchars($hoTen); ?>" required />
            </div>

            <div class="flex flex-col gap-2">
                <label class="text-label-md font-semibold text-secondary" for="profile_email">Địa chỉ Email</label>
                <input class="w-full px-4 py-2.5 rounded-xl border border-outline bg-gray-100 text-gray-500 cursor-not-allowed font-medium text-body-medium" 
                       id="profile_email" type="email" value="<?php echo htmlspecialchars($email); ?>" readonly />
            </div>

            <div class="flex flex-col gap-2">
                <label class="text-label-md font-semibold text-secondary" for="profile_phone">Số điện thoại</label>
                <input class="w-full px-4 py-2.5 rounded-xl border border-outline bg-surface text-on-surface focus:outline-none focus:border-primary font-medium text-body-medium" 
                       id="profile_phone" name="profile_phone" type="tel" value="<?php echo htmlspecialchars($sdt); ?>" required />
            </div>

            <div class="flex flex-col gap-2">
                <label class="text-label-md font-semibold text-secondary" for="profile_dob">Ngày sinh</label>
                <input class="w-full px-4 py-2.5 rounded-xl border border-outline bg-surface text-on-surface focus:outline-none focus:border-primary font-medium text-body-medium" 
                       id="profile_dob" name="profile_dob" type="date" value="<?php echo htmlspecialchars($ngaySinh); ?>" />
            </div>

            <div class="flex flex-col gap-2">
                <label class="text-label-md font-semibold text-secondary" for="profile_gender">Giới tính</label>
                <select class="w-full px-4 py-2.5 rounded-xl border border-outline bg-surface text-on-surface focus:outline-none focus:border-primary font-medium text-body-medium" 
                        id="profile_gender" name="profile_gender">
                    <option value="Nam" <?php if($gioiTinh == 'Nam') echo 'selected'; ?>>Nam</option>
                    <option value="Nữ" <?php if($gioiTinh == 'Nữ') echo 'selected'; ?>>Nữ</option>
                    <option value="Khác" <?php if($gioiTinh == 'Khác') echo 'selected'; ?>>Khác</option>
                </select>
            </div>

            <div class="flex flex-col gap-2">
                <label class="text-label-md font-semibold text-secondary">Vai trò hệ thống</label>
                <input class="w-full px-4 py-2.5 rounded-xl border border-outline bg-gray-100 text-gray-500 cursor-not-allowed font-bold text-body-medium" 
                       type="text" value="<?php echo $vaiTroText; ?>" readonly />
            </div>
        </div>

        <div class="flex flex-col gap-2 mb-6">
            <label class="text-label-md font-semibold text-secondary" for="profile_address">Địa chỉ hiện tại</label>
            <input class="w-full px-4 py-2.5 rounded-xl border border-outline bg-surface text-on-surface focus:outline-none focus:border-primary font-medium text-body-medium" 
                   id="profile_address" name="profile_address" type="text" value="<?php echo htmlspecialchars($diaChi); ?>" />
        </div>

        <div class="flex justify-end gap-4">
            <button class="px-6 py-2.5 bg-[#bb0025] text-white font-bold rounded-xl hover:opacity-90 transition-all shadow-md flex items-center gap-2 text-label-lg" type="submit">
                <span class="material-symbols-outlined text-[18px]">save</span> Lưu thay đổi
            </button>
        </div>
    </form>
</section>
                </section>

                <section id="security" class="tab-content space-y-6">
                    <div class="border-b border-outline-variant pb-4">
                        <h4 class="font-headline-sm text-headline-sm text-on-surface">Mật khẩu & Bảo mật</h4>
                        <p class="text-body-sm text-on-surface-variant mt-0.5">Thay đổi mật khẩu hệ thống định kỳ để bảo vệ toàn vẹn dữ liệu hệ thống.</p>
                    </div>
                    <form action="caidat.php" method="POST" class="space-y-4 max-w-md">
                        <input type="hidden" name="action" value="change_password">
                        <div>
                            <label class="block text-label-sm font-semibold text-on-surface-variant">Mật khẩu hiện tại</label>
                            <input name="current_password" type="password" required class="mt-1.5 block w-full border border-outline-variant rounded-lg py-2 px-3 shadow-sm focus:ring-primary focus:border-primary text-body-sm"/>
                        </div>
                        <div>
                            <label class="block text-label-sm font-semibold text-on-surface-variant">Mật khẩu mới</label>
                            <input name="new_password" type="password" required class="mt-1.5 block w-full border border-outline-variant rounded-lg py-2 px-3 shadow-sm focus:ring-primary focus:border-primary text-body-sm"/>
                        </div>
                        <div>
                            <label class="block text-label-sm font-semibold text-on-surface-variant">Xác nhận mật khẩu mới</label>
                            <input name="confirm_password" type="password" required class="mt-1.5 block w-full border border-outline-variant rounded-lg py-2 px-3 shadow-sm focus:ring-primary focus:border-primary text-body-sm"/>
                        </div>
                        <div class="pt-2">
                            <button class="inline-flex justify-center py-2 px-5 border border-transparent shadow-sm text-label-md font-bold rounded-lg text-white bg-primary hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all" type="submit">
                                Lưu thay đổi
                            </button>
                        </div>
                    </form>
                </section>

                <section id="history" class="tab-content space-y-6">
                    <div class="border-b border-outline-variant pb-4">
                        <h4 class="font-headline-sm text-headline-sm text-on-surface">Lịch sử truy cập</h4>
                        <p class="text-body-sm text-on-surface-variant mt-0.5">Giám sát hoạt động và các phiên kết nối gần nhất của tài khoản này.</p>
                    </div>
                    <div class="overflow-x-auto border border-outline-variant rounded-xl">
                        <table class="w-full text-left">
                            <thead class="bg-surface-container-low border-b border-outline-variant">
                                <tr>
                                    <th class="px-6 py-3.5 font-label-md text-on-surface-variant">HÀNH ĐỘNG HỆ THỐNG</th>
                                    <th class="px-6 py-3.5 font-label-md text-on-surface-variant">ĐỊA CHỈ IP</th>
                                    <th class="px-6 py-3.5 font-label-md text-on-surface-variant">THỜI GIAN GHI NHẬN</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-outline-variant">
                                <tr class="hover:bg-surface-container transition-colors">
                                    <td class="px-6 py-4 text-body-sm text-on-surface">Đăng nhập cổng Admin thành công</td>
                                    <td class="px-6 py-4 text-body-sm font-mono text-secondary">127.0.0.1</td>
                                    <td class="px-6 py-4 text-label-sm font-bold text-primary">Vừa xong</td>
                                </tr>
                                <tr class="hover:bg-surface-container transition-colors">
                                    <td class="px-6 py-4 text-body-sm text-on-surface">Khởi tạo phiên làm việc dữ liệu</td>
                                    <td class="px-6 py-4 text-body-sm font-mono text-secondary">localhost</td>
                                    <td class="px-6 py-4 text-body-sm text-on-surface-variant">11/06/2026 13:44:11</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        </div>
    </div>
</main>

<script>
    // Hàm xử lý chuyển đổi tab mượt mà
    function switchTab(tabId) {
        document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));
        document.querySelectorAll('.nav-item').forEach(item => {
            item.classList.remove('inner-nav-active');
            item.classList.add('text-secondary');
        });
        
        const activeTab = document.getElementById(tabId);
        if (activeTab) activeTab.classList.add('active');
        
        const activeNavItem = document.querySelector(`.nav-item[data-tab="${tabId}"]`);
        if (activeNavItem) {
            activeNavItem.classList.add('inner-nav-active');
            activeNavItem.classList.remove('text-secondary');
        }
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
</script>
</body>
</html>
<?php $conn->close(); ?>