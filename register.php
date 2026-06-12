<?php
$conn = new mysqli("localhost","root","","quanlytrungtam");
if ($conn->connect_error) die("DB lỗi");

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $hoten = trim($_POST["full-name"]);
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]);
    $password = trim($_POST["password"]);
    $confirm = trim($_POST["confirm-password"]);

    if ($password != $confirm) {
        $error = "Mật khẩu xác nhận không khớp!";
    } else {

        // tạo mã
        $maNguoiDung = "ND" . rand(1000,9999);
        $maTaiKhoan  = "TK" . rand(1000,9999);

        // 1. NguoiDung
        $conn->query("
            INSERT INTO NguoiDung 
            VALUES ('$maNguoiDung', '$hoten', '', NULL, '$phone', '')
        ");

        // 2. TaiKhoan (mặc định HV)
        $conn->query("
            INSERT INTO TaiKhoan 
            VALUES ('$maTaiKhoan', '$maNguoiDung', '$email', '$password', 'HV', 'HoatDong', '$email')
        ");

        $check = $conn->query("SELECT * FROM TaiKhoan WHERE Email='$email'");
        if ($check->num_rows > 0) {
            $error = "Email đã tồn tại!";
        } else {

            $success = "Đăng ký thành công!";
            
        }
    }
}
?>
<!DOCTYPE html>
<html class="light" lang="vi"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&amp;family=Inter:wght@400;600&amp;family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script id="tailwind-config">
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            "colors": {
                    "on-secondary": "#ffffff",
                    "tertiary-fixed": "#dae1ff",
                    "secondary-fixed-dim": "#c6c6c6",
                    "on-primary": "#ffffff",
                    "on-secondary-container": "#636467",
                    "on-primary-fixed-variant": "#93001b",
                    "on-surface": "#191c1d",
                    "primary-container": "#e90031",
                    "error": "#ba1a1a",
                    "secondary-container": "#e2e2e5",
                    "error-container": "#ffdad6",
                    "on-secondary-fixed-variant": "#454749",
                    "tertiary-container": "#156aff",
                    "outline": "#936e6c",
                    "on-tertiary-fixed": "#001849",
                    "surface-container-highest": "#e1e3e4",
                    "secondary": "#5d5e61",
                    "primary-fixed": "#ffdad8",
                    "on-primary-fixed": "#410006",
                    "on-error": "#ffffff",
                    "surface-tint": "#bf0026",
                    "tertiary-fixed-dim": "#b3c5ff",
                    "surface-dim": "#d9dadb",
                    "background": "#f8f9fa",
                    "on-tertiary-fixed-variant": "#003fa4",
                    "on-tertiary-container": "#fefcff",
                    "on-error-container": "#93000a",
                    "on-background": "#191c1d",
                    "inverse-primary": "#ffb3b0",
                    "surface-container": "#edeeef",
                    "secondary-fixed": "#e2e2e5",
                    "on-primary-container": "#fffbff",
                    "surface-bright": "#f8f9fa",
                    "surface-container-low": "#f3f4f5",
                    "on-secondary-fixed": "#1a1c1e",
                    "tertiary": "#0052d1",
                    "surface-container-high": "#e7e8e9",
                    "primary-fixed-dim": "#ffb3b0",
                    "surface-variant": "#e1e3e4",
                    "inverse-surface": "#2e3132",
                    "inverse-on-surface": "#f0f1f2",
                    "primary": "#bb0025",
                    "outline-variant": "#e8bcba",
                    "on-tertiary": "#ffffff",
                    "on-surface-variant": "#5e3f3d",
                    "surface-container-lowest": "#ffffff",
                    "surface": "#f8f9fa"
            },
            "borderRadius": {
                    "DEFAULT": "0.25rem",
                    "lg": "0.5rem",
                    "xl": "0.75rem",
                    "full": "9999px"
            },
            "spacing": {
                    "margin-tablet": "32px",
                    "unit": "8px",
                    "margin-desktop": "64px",
                    "container-max-width": "1280px",
                    "gutter": "24px",
                    "margin-mobile": "16px",
                    "unit-lg": "24px",
                    "unit-sm": "8px",
                    "unit-xl": "48px",
                    "unit-xs": "4px",
                    "unit-md": "16px"
            },
            "fontFamily": {
                    "body-md": ["Montserrat"],
                    "label-sm": ["Montserrat"],
                    "label-md": ["Montserrat"],
                    "headline-lg-mobile": ["Montserrat"],
                    "headline-md": ["Montserrat"],
                    "headline-lg": ["Montserrat"],
                    "body-lg": ["Montserrat"],
                    "display-lg": ["Montserrat"],
                    "headline-sm": ["Montserrat"],
                    "body-sm": ["Montserrat"]
            },
            "fontSize": {
                    "body-md": ["16px", {"lineHeight": "24px", "fontWeight": "400"}],
                    "label-sm": ["12px", {"lineHeight": "16px", "fontWeight": "500"}],
                    "label-md": ["14px", {"lineHeight": "20px", "letterSpacing": "0.01em", "fontWeight": "600"}],
                    "headline-lg-mobile": ["28px", {"lineHeight": "36px", "fontWeight": "700"}],
                    "headline-md": ["24px", {"lineHeight": "32px", "fontWeight": "600"}],
                    "headline-lg": ["32px", {"lineHeight": "40px", "letterSpacing": "-0.01em", "fontWeight": "700"}],
                    "body-lg": ["18px", {"lineHeight": "28px", "fontWeight": "400"}],
                    "display-lg": ["48px", {"lineHeight": "56px", "letterSpacing": "-0.02em", "fontWeight": "700"}],
                    "headline-sm": ["20px", {"lineHeight": "28px", "fontWeight": "600"}],
                    "body-sm": ["14px", {"lineHeight": "20px", "fontWeight": "400"}]
            }
          },
        },
      }
    </script>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            display: inline-block;
            line-height: 1;
            text-transform: none;
            letter-spacing: normal;
            word-wrap: normal;
            white-space: nowrap;
            direction: ltr;
        }
    </style>
</head>
<body class="bg-surface font-body-md text-on-surface">
<!-- Auth Split Screen Layout -->
<main class="min-h-screen flex flex-col md:flex-row">
<!-- Brand/Visual Side -->
<section class="hidden md:flex md:w-1/2 bg-primary relative overflow-hidden items-center justify-center p-unit-xl">
<!-- Decorative Background Pattern -->
<div class="absolute inset-0 opacity-20 pointer-events-none" style="background-image: radial-gradient(circle at 2px 2px, rgba(255,255,255,0.2) 1px, transparent 0); background-size: 40px 40px;"></div>
<div class="relative z-10 text-center max-w-md">
<div class="mb-unit-lg flex justify-center">
<span class="material-symbols-outlined text-[64px] text-white" style="font-variation-settings: 'FILL' 1;">school</span>
</div>
<h1 class="font-headline-lg text-headline-lg text-white mb-unit-md">Athena English Center</h1>
<p class="font-body-lg text-body-lg text-primary-fixed-dim opacity-90">Hành trình chinh phục ngôn ngữ mới của bạn bắt đầu từ đây với tiêu chuẩn giáo dục hàng đầu quốc tế.</p>
<div class="mt-unit-xl grid grid-cols-2 gap-unit-md text-left">
<div class="bg-primary-container p-unit-md rounded-lg border border-outline-variant/20">
<span class="material-symbols-outlined text-white mb-2">verified</span>
<h3 class="font-headline-sm text-headline-sm text-white text-[16px]">Chất lượng 5 sao</h3>
</div>
<div class="bg-primary-container p-unit-md rounded-lg border border-outline-variant/20">
<span class="material-symbols-outlined text-white mb-2">language</span>
<h3 class="font-headline-sm text-headline-sm text-white text-[16px]">Môi trường quốc tế</h3>
</div>
</div>
</div>
<!-- Image Mask Layer -->
<div class="absolute bottom-0 right-0 w-2/3 h-1/2 opacity-30 transform translate-x-1/4 translate-y-1/4">
<img alt="A group of diverse university students laughing and studying together" class="w-full h-full object-cover rounded-full grayscale mix-blend-overlay" src="https://lh3.googleusercontent.com/aida-public/AB6AXuC6R7TqFVMFAnYDyi0iU33IgvaEw-m7OL9X18m_5Dphfz0TOPf3m6AiD9DBB6HhVV98Fb8VrHdgVqaeNMeoFtu9r6uxe1Y_wdd1caWfR1ThZ2nmIrrUyOPxfE_HilCezHGc9114qOpBo8lrsPQpMf8CMczdqZL7pt4KrZM60qumExrKY7myTT0xSLrRqbhoXN1Qn0fW6k_kqa_IIXzdynv59z4U8e7u7RJ6mqrWZ0oy4NboICMzjkHZR3L0r7VRgFb6nU-WVcqoHm4"/>
</div>
</section>
<!-- Form Side -->
<section class="w-full md:w-1/2 flex items-center justify-center bg-surface-container-lowest p-unit-lg md:p-unit-xl">
<div class="w-full max-w-[480px]">
<!-- Mobile Logo -->
<div class="md:hidden flex items-center gap-unit-sm mb-unit-lg">
<span class="material-symbols-outlined text-primary text-[32px]">school</span>
<span class="font-headline-md text-headline-md text-primary">Athena</span>
</div>
<div class="mb-unit-xl">
<h2 class="font-headline-lg text-headline-lg text-on-surface mb-unit-xs">Đăng ký tài khoản</h2>
<?php if (!empty($error)): ?>
    <div class="text-red-500 font-semibold mb-3">
        <?= $error ?>
    </div>
<?php endif; ?>

<?php if (!empty($success)): ?>
    <div class="text-green-600 font-semibold mb-3">
        <?= $success ?>
    </div>
<?php endif; ?>
<p class="text-on-surface-variant font-body-md">Tham gia cộng đồng học thuật xuất sắc của Athena ngay hôm nay.</p>
</div>
<form class="space-y-unit-md" method="POST">
<!-- Full Name Field -->
<div class="space-y-unit-xs">
<label class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider" for="full-name">Họ và tên</label>
<div class="relative group">
<span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant group-focus-within:text-primary transition-colors">person</span>
<input class="w-full pl-10 pr-4 py-3 bg-surface border border-outline-variant rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-body-md placeholder:text-on-secondary-container/50" id="full-name" name="full-name" placeholder="Ví dụ: Nguyễn Văn An" type="text"/>
</div>
</div>
<!-- Email Field -->
<div class="space-y-unit-xs">
<label class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider" for="email">Email</label>
<div class="relative group">
<span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant group-focus-within:text-primary transition-colors">mail</span>
<input class="w-full pl-10 pr-4 py-3 bg-surface border border-outline-variant rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-body-md placeholder:text-on-secondary-container/50" id="email" name="email" placeholder="email@example.com" type="email"/>
</div>
</div>
<!-- Phone Number Field -->
<div class="space-y-unit-xs">
<label class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider" for="phone">Số điện thoại</label>
<div class="relative group">
<span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant group-focus-within:text-primary transition-colors">phone_iphone</span>
<input class="w-full pl-10 pr-4 py-3 bg-surface border border-outline-variant rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-body-md placeholder:text-on-secondary-container/50" id="phone" name="phone" placeholder="09xx xxx xxx" type="tel"/>
</div>
</div>
<!-- Password Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-unit-md">
<div class="space-y-unit-xs">
<label class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider" for="password">Mật khẩu</label>
<div class="relative group">
<span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant group-focus-within:text-primary transition-colors">lock</span>
<input class="w-full pl-10 pr-4 py-3 bg-surface border border-outline-variant rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-body-md" id="password" name="password" placeholder="••••••••" type="password"/>
</div>
</div>
<div class="space-y-unit-xs">
<label class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider" for="confirm-password">Xác nhận</label>
<div class="relative group">
<span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant group-focus-within:text-primary transition-colors">shield</span>
<input class="w-full pl-10 pr-4 py-3 bg-surface border border-outline-variant rounded-lg focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-body-md" id="confirm-password" name="confirm-password" placeholder="••••••••" type="password"/>
</div>
</div>
</div>
<!-- Terms Checkbox -->
<div class="flex items-start gap-unit-sm pt-unit-xs">
<input class="mt-1 w-5 h-5 rounded border-outline-variant text-primary focus:ring-primary/30 transition-all cursor-pointer" id="terms" name="terms" type="checkbox"/>
<label class="font-body-sm text-body-sm text-on-surface-variant leading-tight cursor-pointer" for="terms">
                            Tôi đồng ý với các <a class="text-primary hover:underline font-semibold" href="#">Điều khoản &amp; Điều kiện</a> và <a class="text-primary hover:underline font-semibold" href="#">Chính sách Bảo mật</a> của Athena English Center.
                        </label>
</div>
<!-- Action Button -->
<div class="pt-unit-md">
<button class="w-full py-unit-md bg-primary hover:bg-primary-container text-white font-headline-sm text-headline-sm rounded-lg shadow-sm hover:shadow-md active:scale-[0.98] transition-all flex items-center justify-center gap-unit-sm" type="submit">
                            Đăng ký
                            <span class="material-symbols-outlined">arrow_forward</span>
</button>
</div>
<div class="text-center pt-unit-lg">
<p class="font-body-md text-on-surface-variant">
    Bạn đã có tài khoản? 
    <a class="text-primary font-bold hover:underline ml-1" href="login.php">
        Đăng nhập ngay
    </a>
</p>
</div>
</form>
<!-- Support Footer -->
<div class="mt-unit-xl flex flex-wrap justify-center gap-unit-lg border-t border-outline-variant/30 pt-unit-lg">
<a class="flex items-center gap-unit-xs text-on-secondary-container hover:text-primary transition-colors" href="#">
<span class="material-symbols-outlined text-[18px]">help_outline</span>
<span class="font-body-sm">Hỗ trợ</span>
</a>
<a class="flex items-center gap-unit-xs text-on-secondary-container hover:text-primary transition-colors" href="#">
<span class="material-symbols-outlined text-[18px]">language</span>
<span class="font-body-sm">Tiếng Việt</span>
</a>
</div>
</div>
</section>
</main>
</body></html>
