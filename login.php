<?php
session_start();
$conn = new mysqli("localhost","root","","quanlytrungtam");

if ($conn->connect_error) {
    die("Lỗi DB");
}

$error = "";

// xử lý login
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $login = trim($_POST['login'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($login == "" || $password == "") {
        $error = "Vui lòng nhập đầy đủ tài khoản và mật khẩu!";
    } else {

        $sql = "SELECT * FROM TaiKhoan 
                WHERE (Username='$login' OR Email='$login')
                AND Password='$password'
                LIMIT 1";

        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {

            $user = $result->fetch_assoc();

$_SESSION['role'] = $user['VaiTro'];
$_SESSION['MaTaiKhoan'] = $user['MaTaiKhoan'];
$_SESSION['Username'] = $user['Username'];

            $role = $user['VaiTro'];

        if ($role == 'NV') {
            header("Location: /quanlytrungtam/giaodienadmin/dashboard.php");
        } 
        elseif ($role == 'GV') {
            header("Location: /quanlytrungtam/giaodiengiaovien/tongquan.php");
        } 
        else {
            header("Location: /quanlytrungtam/giaodienhocvien/giaodienhocvien.php");
        }
        exit();

        } else {
            $error = "Sai tài khoản hoặc mật khẩu!";
        }
    }
}
?>
<!DOCTYPE html><html lang="vi" style=""><head>
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800&amp;family=Inter:wght@400;500;600&amp;display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">
<script id="tailwind-config">
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            "colors": {
                    "on-secondary": "#ffffff",
                    "tertiary-fixed": "#dae1ff",
                    "secondary-fixed-dim": "#c6c6c9",
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
                    "body-sm": ["Montserrat"],
                    "headline-sm": ["Montserrat"]
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
                    "body-sm": ["14px", {"lineHeight": "20px", "fontWeight": "400"}],
                    "headline-sm": ["20px", {"lineHeight": "28px", "fontWeight": "600"}]
            }
          },
        },
      }
    </script>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .hero-gradient {
            background: linear-gradient(rgba(187, 0, 37, 0.85), rgba(187, 0, 37, 0.95));
        }
    </style>
<style data-stitch-cursor="">*{cursor:crosshair!important}[contenteditable="true"]:focus,.cursor-text:focus{cursor:text!important}</style><style data-stitch-scroll-lock="">html,body{overflow:hidden!important}</style></head>
<body class="bg-surface text-on-surface font-body-md min-h-screen">
<main class="flex min-h-screen">
<!-- Left Side: Visual & Identity -->
<section class="hidden lg:flex lg:w-1/2 relative flex-col justify-between p-unit-xl overflow-hidden bg-primary">
<div class="absolute inset-0 z-0">
<div class="absolute inset-0 hero-gradient z-10"></div>
<div class="w-full h-full bg-cover bg-center" data-alt="A grand university library interior with high vaulted ceilings, long wooden study tables, and shelves filled with books. The lighting is warm and scholarly, creating an atmosphere of deep focus and academic excellence. Subtle red accents in the architecture align with the Athena brand colors, conveying a prestigious and traditional educational environment." style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuB9DjKLeV2FSPYoF2E2P0w9lEj0EgpxoLOCQEUdNmoPPryri111CHuOWR3W55HhyLQVgMl3IYhM6BP4NVrELa91AmZWKUbsxCn22tdH3_zCZaw4wABFUvO7KQzwNdSXlwmrIolNzYeA34lYelBo8tgzwcZj5SH0C3y5xkIgT9VScDkjdyIurMMYa11CBJ6FIkaujJNQCe4VzVahWBOeH2nIiKCASwmXcWJqdUP2fCNyNYUJEYJQwQo99ES7G49KMAFHTO0ZHZ3FhCA')"></div>
</div>
<div class="relative z-20 flex flex-col h-full justify-between">
<div>
<img alt="Athena English Center Logo" class="h-16 w-auto object-contain mb-unit-lg" src="logo.jpg">
<h1 class="font-display-lg text-display-lg text-white max-w-md">
                    Chinh Phục Ngôn Ngữ, Mở Lối Tương Lai
                </h1>
<p class="font-body-lg text-body-lg text-white/90 mt-unit-md max-w-md">
                    Chào mừng bạn đến với Trung tâm Anh ngữ Athena - Nơi nuôi dưỡng niềm đam mê học tập và phát triển kỹ năng tiếng Anh chuyên nghiệp.
                </p>
</div>
<div class="flex items-center gap-unit-md">
<div class="flex -space-x-3">
<img class="h-10 w-10 rounded-full border-2 border-primary" data-alt="Close up portrait of a smiling female student wearing a white academic shirt, looking confident and happy. Professional studio lighting with a soft blurred educational background in the Athena primary color palette." src="https://lh3.googleusercontent.com/aida-public/AB6AXuCRnoYnlEEnl4KdKa9CUjA3oa-UBhU9U3IWtZVu-oXMfjeVZlhvpalaHq6owqA7ICDtypTTdOWnMH_XyosfpMt29oScURN3RGkBRyDUMhyunz_LuSynEPN8uQLvpih2DnjSvxmn5CRBEgw2ukP3uo9C1bxxfMwRrONJf6MqAVBvVMMLsjcMUCb7y47oDSAD59r_Zm6_VbwRT1BSMSlGKumwZj2qSQTdS3XxN095S8Eu-7SKP2PTnKxTzmkGRlzwriHV1indY4-eO_I">
<img class="h-10 w-10 rounded-full border-2 border-primary" data-alt="Close up portrait of a young male professional student smiling, representing success and ambition. High-end modern photography style with clean composition and corporate academic aesthetic." src="https://lh3.googleusercontent.com/aida-public/AB6AXuCQU2pks83WQmHJn7v3aDMIJr4AM6pnsM66sF1dZ4xuBugYc6M6wwyivT95XR6JqVkoub3ee40TxAnjUusyCPHOnDmPF4YaD-r0X3dQr-AbLgjzcEuU6_DVz4TdOez2TP5dj61eV37uUeQJ6GTzCR39APQeue1d_zBnUGkZn-U5lg_czYTdQomS6yx7r3DIvwhyetJPX40FFO4KTtS3OLirZqxIIldlvLuf9msR8Sa4YIabsxCeuR92eOWbya73av7_sOfAjnf2Yxc">
<img class="h-10 w-10 rounded-full border-2 border-primary" data-alt="Portrait of a diverse student smiling warmly, representing the inclusive and elite learning community of Athena English Center. Soft lighting and professional academic environment." src="https://lh3.googleusercontent.com/aida-public/AB6AXuCfS1SCgowcOVjXcniLA_veKnbjDTkWu1Imt-jg6yr6StqY2WATHMojyrIe22h1aHy8aPz4xkUAJdqAD2jveU6FuIGsHh306hpMueSNFN5gJMVLXXWea6Xw2R57TGtcfDxMf-7lMoS_kA7o2H90bLI23512pCV15hIJ_77xioYnEkHKF4puonw-tamOZSaTVtl1b-1CuMBXWqIrKR3ln0xOzFmygbjvbsg-4JwDWfeoKHbY6Y6JAqdzfrVzqVXm46XrmDnNqyH84V4">
</div>
<span class="font-label-md text-white">Hơn 5,000 học viên đã tham gia</span>
</div>
</div>
</section>
<!-- Right Side: Login Form -->
<section class="w-full lg:w-1/2 flex items-center justify-center p-margin-mobile md:p-margin-desktop bg-surface-container-lowest">
<div class="w-full max-w-[440px]">
<div class="mb-unit-xl">
<div class="lg:hidden mb-unit-lg">
<img alt="Athena English Center Logo" class="h-12 w-auto mx-auto lg:mx-0" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDfDjuviO4fOXpeDUmHofBT1YXisL2dy9i3M4SXaq4pA5dy2YjzwG1ppNHtEysq-IDxMpveZEHid0pI_mssjzPPYI_Irz6avzW8pDMKjVvIZ7oUoxb-E_2VXmazd8-1iUzvjopa2fsnixJvAdtvv8JrXw600ywbWLqlSXC20x1RgYDvVePoYVhJhbR23jHJebIhbVxDoCR7GWDzPDx-dhbn2Jmr5R63IPLlqFwXbOwKJjh_fj9GvDtFJ_WFSv5HpxCNiHom23hdeL8">
</div>
<h2 class="font-headline-lg text-headline-lg text-primary mb-unit-xs">Chào mừng trở lại</h2>
<?php if (!empty($error)): ?>
    <div class="mb-4 text-red-500 font-semibold">
        <?= $error ?>
    </div>
<?php endif; ?>
<p class="font-body-md text-on-surface-variant">Vui lòng đăng nhập vào tài khoản của bạn để tiếp tục học tập.</p>
</div>
<form action="login.php" method="POST">
<!-- Login Field (Email / Username) -->
<div class="space-y-unit-xs">
    <label class="font-label-md text-on-surface-variant block" for="login">
        Email hoặc Username
    </label>

    <div class="relative group">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-on-secondary-container group-focus-within:text-primary transition-colors">
            <span class="material-symbols-outlined text-[20px]">person</span>
        </div>

        <input 
            class="w-full bg-surface-container-lowest border border-outline-variant rounded-lg py-3 pl-10 pr-4 text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"
            id="login"
            name="login"
            placeholder="Email hoặc username"
            type="text">
    </div>
</div>
<!-- Password Field -->
<div class="space-y-unit-xs">
<div class="flex justify-between items-center">
<label class="font-label-md text-on-surface-variant block" for="password">Mật khẩu</label>
<a class="font-label-md text-primary hover:underline transition-all" href="#">Quên mật khẩu?</a>
</div>
<div class="relative group">
<div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-on-secondary-container group-focus-within:text-primary transition-colors">
<span class="material-symbols-outlined text-[20px]">lock</span>
</div>
<input class="w-full bg-surface-container-lowest border border-outline-variant rounded-lg py-3 pl-10 pr-4 text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all placeholder:text-on-secondary-container/50" id="password" name="password" placeholder="••••••••" type="password">
<button class="absolute inset-y-0 right-0 pr-3 flex items-center text-on-secondary-container hover:text-primary transition-colors" type="button">
<span class="material-symbols-outlined text-[20px]">visibility</span>
</button>
</div>
</div>
<!-- Remember Me -->
<div class="flex items-center">
<input class="w-4 h-4 text-primary border-outline-variant rounded focus:ring-primary focus:ring-offset-0 transition-all cursor-pointer" id="remember" type="checkbox">
<label class="ml-2 font-body-sm text-on-surface-variant cursor-pointer select-none" for="remember">Ghi nhớ đăng nhập</label>
</div>
<!-- Submit Button -->
<button 
class="w-full bg-primary-container text-white font-headline-sm py-4 rounded-lg shadow-sm hover:opacity-90 active:scale-[0.98] transition-all flex items-center justify-center gap-unit-sm"
type="submit">
<span>Đăng Nhập</span>
<span class="material-symbols-outlined text-[20px]">
arrow_forward
</span>
</button>
</form>
<!-- Divider -->
<div class="relative my-unit-xl">
<div class="absolute inset-0 flex items-center">
<div class="w-full border-t border-outline-variant"></div>
</div>
<div class="relative flex justify-center text-body-sm">
<span class="px-4 bg-surface-container-lowest text-on-secondary-container">Hoặc đăng nhập với</span>
</div>
</div>
<!-- Footer Text -->
<div class="mt-unit-xl text-center">
<p class="font-body-sm text-on-surface-variant">
    Chưa có tài khoản? 
    <a class="text-primary font-bold hover:underline transition-all" href="register.php">
        Đăng ký ngay
    </a>
</p>
</div>
</div>
</section>
</main>
<!-- Simple Footer -->
<footer class="fixed bottom-0 w-full lg:w-1/2 right-0 bg-transparent py-unit-md px-margin-desktop hidden lg:block">
<div class="flex justify-between items-center text-body-sm text-on-secondary-container">
<span class="">© 2026 Athena English Center.</span>
<div class="flex gap-unit-md">
<a class="hover:text-primary transition-colors" href="#">Hỗ trợ</a>
<a class="hover:text-primary transition-colors" href="#">Điều khoản</a>
<a class="hover:text-primary transition-colors" href="#">Quyền riêng tư</a>
</div>
</div>
</footer>
</body></html>
