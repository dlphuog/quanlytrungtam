<?php
session_start();

if(!isset($_SESSION['MaTaiKhoan'])){
    header("Location: ../login.php");
    exit();
}

if($_SESSION['role'] != 'GV'){
    header("Location: ../login.php");
    exit();
}

$conn = new mysqli("localhost","root","","quanlytrungtam");

if($conn->connect_error){
    die("Lỗi kết nối");
}

$conn->set_charset("utf8");

$maTaiKhoan = $_SESSION['MaTaiKhoan']; // Giả sử mã tài khoản đã được lưu trong session sau khi đăng nhập

$sqlGV = "
SELECT nd.HoTen
FROM giaovien gv
INNER JOIN taikhoan tk
    ON gv.MaTaiKhoan = tk.MaTaiKhoan
INNER JOIN nguoidung nd
    ON tk.MaNguoiDung = nd.MaNguoiDung
WHERE gv.MaTaiKhoan = '$maTaiKhoan'
";

$giaovien = $conn->query($sqlGV)->fetch_assoc();

$maLop = !empty($_GET['lop']) ? $_GET['lop'] : 'LH001';

$sqlHocVien = "
SELECT
    hv.MaHocVien,
    nd.HoTen,
    kq.NhanXet
FROM chitietlophoc ct

INNER JOIN hocvien hv
    ON ct.MaHocVien = hv.MaHocVien

INNER JOIN taikhoan tk
    ON hv.MaTaiKhoan = tk.MaTaiKhoan

INNER JOIN nguoidung nd
    ON tk.MaNguoiDung = nd.MaNguoiDung

LEFT JOIN ketquahoctap kq
    ON hv.MaHocVien = kq.MaHocVien
    AND kq.MaLopHoc = '$maLop'

WHERE ct.MaLopHoc = '$maLop'

ORDER BY hv.MaHocVien
";

$sqlLop = "
SELECT TenLop
FROM lophoc
WHERE MaLopHoc = '$maLop'
";

$lop = $conn->query($sqlLop)->fetch_assoc();
if(!$lop){
    $lop = [
        'TenLop' => 'Không xác định'
    ];
}

$resultHocVien = $conn->query($sqlHocVien);

$maHocVien = !empty($_GET['hv']) ? $_GET['hv'] : null;

if(!$maHocVien){

    $first = $resultHocVien->fetch_assoc();

    if($first){
        $maHocVien = $first['MaHocVien'];
        mysqli_data_seek($resultHocVien,0);
    }
}

$sqlChiTiet = "
SELECT
    hv.MaHocVien,
    nd.HoTen,
    kq.DiemSo,
    kq.NhanXet

FROM hocvien hv

INNER JOIN taikhoan tk
    ON hv.MaTaiKhoan = tk.MaTaiKhoan

INNER JOIN nguoidung nd
    ON tk.MaNguoiDung = nd.MaNguoiDung

LEFT JOIN ketquahoctap kq
    ON hv.MaHocVien = kq.MaHocVien
    AND kq.MaLopHoc = '$maLop'

WHERE hv.MaHocVien = '$maHocVien'

LIMIT 1
";

$hocvien = $conn->query($sqlChiTiet)->fetch_assoc();
if(!$hocvien){
    $hocvien = [
        'HoTen' => 'Chưa chọn học viên',
        'MaHocVien' => '',
        'DiemSo' => ''
    ];
}

?>
<!DOCTYPE html>
<html lang="vi">
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>Đánh giá &amp; Nhận xét - Athena Teacher</title>
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
        .chart-bar-grow { transition: height 1s ease-out; }
        .sidebar-active { background-color: #ffdad6; color: #93000e; font-weight: 700; border-radius: 0.5rem; }
    </style>
</head>
<body class="bg-background text-on-surface">

<?php if(isset($_GET['success'])){ ?>
<div class="fixed top-5 right-5 bg-green-600 text-white px-4 py-2 rounded-lg shadow-lg z-50">
    ✅ Lưu bản nháp thành công
</div>
<?php } ?>

<?php if(isset($_GET['error'])){ ?>
<div class="fixed top-5 right-5 bg-red-600 text-white px-4 py-2 rounded-lg shadow-lg z-50">
    ❌ Lưu nhận xét thất bại
</div>
<?php } ?>    
<!-- SideNavBar -->
<aside class="fixed left-0 top-0 h-full w-sidebar-width bg-surface-container-lowest shadow-sm flex flex-col p-unit-md border-r border-outline-variant z-50">
<div class="px-gutter pt-8 pb-4 flex flex-col items-center">          
    <img
src="/quanlytrungtam/logo.jpg"
alt="Athena Logo"
class="w-20 h-20 mx-auto rounded-3xl shadow-2xl">

    <h1 class="mt-4 text-[20px] font-bold text-primary text-center">
        Athena Teacher
</h1>
    </div>
<nav class="flex-1 space-y-2">
<a class="flex items-center gap-3 text-secondary hover:bg-surface-container rounded-lg px-4 py-3 transition-colors" href="tongquan.php">
<span class="material-symbols-outlined">dashboard</span>
<span class="font-label-md text-label-md">Tổng quan</span>
</a>
<a class="flex items-center gap-3 text-secondary hover:bg-surface-container rounded-lg px-4 py-3 transition-colors" href="lichgiangday.php">
<span class="material-symbols-outlined">calendar_month</span>
<span class="font-label-md text-label-md">Lịch giảng dạy</span>
</a>
<a class="flex items-center gap-3 text-secondary hover:bg-surface-container rounded-lg px-4 py-3 transition-colors" href="quanlylophoc.php">
<span class="material-symbols-outlined">groups</span>
<span class="font-label-md text-label-md">Quản lý lớp học</span>
</a>
<a class="flex items-center gap-3 text-secondary hover:bg-surface-container rounded-lg px-4 py-3 transition-colors" href="diemdanh.php">
<span class="material-symbols-outlined">fact_check</span>
<span class="font-label-md text-label-md">Điểm danh</span>
</a>
<a class="flex items-center gap-3 text-secondary hover:bg-surface-container rounded-lg px-4 py-3 transition-colors" href="nhanxetdanhgia.php">
<span class="material-symbols-outlined">grade</span>
<span class="font-label-md text-label-md">Quản lý điểm số</span>
</a>
<!-- Active: Đánh giá & Nhận xét -->
<a class="flex items-center gap-3 active-nav rounded-lg px-4 py-3 transition-colors" href="#">
<span class="material-symbols-outlined" style="font-variation-settings: &quot;FILL&quot; 1;">rate_review</span>
<span class="font-label-md text-label-md">Đánh giá &amp; Nhận xét</span>
</a>
</nav>
<div class="mt-auto border-t border-outline-variant pt-4">

    <div class="flex items-center justify-between px-3 py-2">

        <div class="flex items-center gap-3">

            <div
            class="w-10 h-10 rounded-full
                   bg-primary text-white
                   flex items-center justify-center
                   font-bold text-sm">

                <?= strtoupper(substr($giaovien['HoTen'],0,1)) ?>

            </div>

            <div>

                <p class="font-bold text-sm">
                    <?= htmlspecialchars($giaovien['HoTen']) ?>
                </p>

                <p class="text-xs text-secondary">
                    Giáo viên
                </p>

            </div>

        </div>

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
</aside>
<!-- TopNavBar -->
<main class="ml-sidebar-width min-h-screen flex flex-col overflow-y-auto">
        <header class="flex justify-between items-center h-16 px-gutter sticky top-0 z-40 bg-surface/80 backdrop-blur-md border-b border-surface-container-highest">
        <div class="flex items-center">
            <h2 class="font-headline-md text-headline-md font-bold text-on-surface">Nhận xét & Đánh giá</h2>
        </div>
        
    </header>
<!-- Main Content Wrapper -->
<!-- Canvas Area -->
<main class="p-unit-lg flex-1 overflow-y-auto">
<div class="max-w-7xl mx-auto flex flex-col gap-unit-lg">
<!-- Page Header Info -->
<div class="flex justify-between items-end">
<div>
<p class="font-body-sm text-body-sm text-secondary">Đang đánh giá lớp: <span class="font-bold"> <?= htmlspecialchars($lop['TenLop']) ?></span></p>
</div>
<div class="flex gap-unit-sm">
<a
href="xuatpdf.php?lop=<?= $maLop ?>&hv=<?= $maHocVien ?>"
target="_blank"
class="px-unit-md py-unit-sm border border-outline-variant rounded-lg font-label-md text-label-md text-secondary hover:bg-surface-container-low transition-all"
>
    Xuất báo cáo PDF
</a>

</div>
</div>
<!-- Two-Panel Layout -->
<div class="grid grid-cols-12 gap-gutter h-[calc(100vh-240px)] min-h-[600px]">
<!-- Left Panel: Student List -->
<div class="col-span-12 lg:col-span-4 xl:col-span-3 flex flex-col gap-unit-md">
<div class="bg-surface-container-lowest border border-outline-variant rounded-xl flex flex-col overflow-hidden shadow-[0_4px_12px_rgba(0,0,0,0.03)] h-full">
<div class="p-unit-md border-b border-outline-variant bg-surface-container-low">
<div class="flex justify-between items-center">
<h3 class="font-label-md text-label-md text-on-surface"><p class="font-body-sm text-body-sm text-secondary">Đang đánh giá lớp: <span class="font-bold"> <?= htmlspecialchars($lop['TenLop']) ?></span></p>
</h3>
<span class="px-2 py-0.5 bg-primary/10 text-primary text-[11px] font-bold rounded-full"><?= $resultHocVien->num_rows ?> Học viên</span>
</div>
</div>
<div class="flex-1 overflow-y-auto scrollbar-hide">
<div class="divide-y divide-outline-variant">
<?php while($hv = $resultHocVien->fetch_assoc()){ ?>

<a
href="?lop=<?= $maLop ?>&hv=<?= $hv['MaHocVien'] ?>"
class="block">

<div class="
p-unit-md
<?= $hv['MaHocVien']==$maHocVien
? 'bg-primary/5 border-r-4 border-primary'
: 'hover:bg-surface-container-low'
?>">

<p class="font-label-md">

<?= htmlspecialchars($hv['HoTen']) ?>

</p>

<p class="text-label-sm text-secondary">

<?= empty($hv['NhanXet'])
? 'Chưa nhận xét'
: 'Đã nhận xét' ?>

</p>

</div>

</a>

<?php } ?>

</div>
</div>
</div>
</div>
<!-- Right Panel: Review Form -->
<div class="col-span-12 lg:col-span-8 xl:col-span-9">
<div class="bg-surface-container-lowest border border-outline-variant rounded-xl flex flex-col h-full shadow-[0_4px_12px_rgba(0,0,0,0.03)] overflow-hidden">
<!-- Selected Student Detail Header -->
<div class="p-unit-lg border-b border-outline-variant flex justify-between items-center bg-white">
<div class="flex items-center gap-unit-lg">
<div class="w-16 h-16 rounded-2xl overflow-hidden border-2 border-primary/20 ring-4 ring-primary/5">

</div>
<div>
<div class="flex items-center gap-2">
<span class="text-label-sm font-bold text-secondary uppercase tracking-wider">Học viên đang chọn</span>
</div>
<h3 class="font-headline-sm text-headline-sm text-on-surface">
<?= htmlspecialchars($hocvien['HoTen']) ?>
</h3><div class="flex items-center gap-unit-md mt-1">
<span class="px-2 py-0.5 bg-surface-container-high rounded-full font-label-sm text-label-sm text-secondary">ID: <?= $hocvien['MaHocVien'] ?></span>
<span class="flex items-center gap-1 text-primary">
<span class="material-symbols-outlined text-[16px]" style="font-variation-settings: &quot;FILL&quot; 1;">star</span>
<span class="font-label-sm text-label-sm"> Điểm: <?= $hocvien['DiemSo'] ?? 'Chưa có' ?></span>
</span>
</div>
</div>
</div>
<div class="text-right">
<p class="font-label-sm text-label-sm text-secondary uppercase tracking-wider">Kỳ đánh giá</p>
<p class="font-label-md text-label-md text-on-surface font-bold">
<?= htmlspecialchars($lop['TenLop']) ?> (<?= $maLop ?>)</p>
</p>
</div>
</div>
<!-- Scrollable Form Body -->
 <form action="luunhanxet.php" method="POST">
<div class="flex-1 overflow-y-auto p-unit-lg space-y-unit-lg">
<!-- Assessment Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-unit-lg">
<!-- Section 1 -->
<div class="space-y-unit-sm">
<label class="flex items-center gap-2 font-label-md text-label-md text-on-surface">
<span class="material-symbols-outlined text-primary text-[20px]">trending_up</span>
                                            Tiến độ học tập
                                        </label>
<textarea id="tiendo" name="tiendo" class="w-full h-32 p-unit-md bg-surface-container-low border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary focus:border-primary font-body-sm text-body-sm resize-none transition-all outline-none" placeholder="Nhập kết quả học tập, điểm thi và các cải thiện chuyên môn..."></textarea>
</div>
<!-- Section 2 -->
<div class="space-y-unit-sm">
<label class="flex items-center gap-2 font-label-md text-label-md text-on-surface">
<span class="material-symbols-outlined text-primary text-[20px]">psychology</span>
                                            Thái độ &amp; Chuyên cần
                                        </label>
<textarea id="thaido" name="thaido" class="w-full h-32 p-unit-md bg-surface-container-low border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary focus:border-primary font-body-sm text-body-sm resize-none transition-all outline-none" placeholder="Mô tả mức độ tham gia lớp học, hành vi và tính chủ động..."></textarea>
</div>
<!-- Section 3 -->
<div class="space-y-unit-sm">
<label class="flex items-center gap-2 font-label-md text-label-md text-on-surface">
<span class="material-symbols-outlined text-primary text-[20px]">assignment_late</span>
                                            Điểm cần cải thiện
                                        </label>
<textarea id="canthien" name="canthien" class="w-full h-32 p-unit-md bg-surface-container-low border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary focus:border-primary font-body-sm text-body-sm resize-none transition-all outline-none" placeholder="Xác định các lĩnh vực cụ thể mà học sinh cần hỗ trợ thêm..."></textarea>
</div>
<!-- Section 4 -->
<div class="space-y-unit-sm">
<label class="flex items-center gap-2 font-label-md text-label-md text-on-surface">
<span class="material-symbols-outlined text-primary text-[20px]">verified_user</span>
                                            Lời khuyên của giáo viên
                                        </label>
<textarea id="loikhuyen" name="loikhuyen" class="w-full h-32 p-unit-md bg-surface-container-low border border-outline-variant rounded-xl focus:ring-2 focus:ring-primary focus:border-primary font-body-sm text-body-sm resize-none transition-all outline-none" placeholder="Lời khuyên cho phụ huynh và kế hoạch hành động cho tháng tới..."></textarea>
</div>
</div>
<!-- Quick Tags / Mood -->
<div class="bg-surface-container-low rounded-xl p-unit-md">
<p class="font-label-sm text-label-sm text-secondary uppercase tracking-wider mb-unit-sm">Xếp loại tổng quan</p>
<div class="flex flex-wrap gap-unit-sm">
<button class="px-4 py-2 bg-white border border-outline-variant rounded-full font-label-md text-label-md flex items-center gap-2 hover:border-primary hover:text-primary transition-all">
<span class="w-2 h-2 rounded-full bg-green-500"></span> Xuất sắc
                                        </button>
<button class="px-4 py-2 bg-white border border-primary text-primary rounded-full font-label-md text-label-md flex items-center gap-2">
<span class="w-2 h-2 rounded-full bg-blue-500"></span> Ổn định
                                        </button>
<button class="px-4 py-2 bg-white border border-outline-variant rounded-full font-label-md text-label-md flex items-center gap-2 hover:border-primary hover:text-primary transition-all">
<span class="w-2 h-2 rounded-full bg-yellow-500"></span> Cần cố gắng
                                        </button>
<button class="px-4 py-2 bg-white border border-outline-variant rounded-full font-label-md text-label-md flex items-center gap-2 hover:border-primary hover:text-primary transition-all">
<span class="w-2 h-2 rounded-full bg-red-500"></span> Yếu
                                        </button>
</div>
</div>
</div>
<!-- Sticky Form Footer -->
<div class="p-unit-lg bg-surface-container-lowest border-t border-outline-variant flex justify-between items-center">
<div class="flex items-center gap-unit-md text-secondary">
<span class="material-symbols-outlined">cloud_done</span>
<span class="font-body-sm text-body-sm italic">Đã lưu tự động 2 phút trước</span>
</div>
<div class="flex gap-unit-md">
<script>
function taoNhanXet(){

    let nhanXet =
        "Tiến độ học tập:\n" + document.getElementById("tiendo").value + "\n\n" +
        "Thái độ & Chuyên cần:\n" + document.getElementById("thaido").value + "\n\n" +
        "Điểm cần cải thiện:\n" + document.getElementById("canthien").value + "\n\n" +
        "Lời khuyên:\n" + document.getElementById("loikhuyen").value;

    document.getElementById("nhanXetTong").value = nhanXet;

    return true;
}
</script>
<input type="hidden" name="maHocVien" value="<?= $maHocVien ?>">
<input type="hidden" name="maLop" value="<?= $maLop ?>">
<input type="hidden" id="nhanXetTong" name="nhanXetTong" value="">
<button
    type="submit"
    name="luuNhanXet"
    onclick="return taoNhanXet();"
    class="px-unit-xl py-unit-md border border-outline rounded-lg"
>
    Lưu bản nháp
</button>
</form>

</div>
</div>
</div>
</div>
</div>
</div>
</main>
</div>
<!-- Micro-interactions Script -->
<script>
        // Student list interaction
        document.querySelectorAll('.lg\\:col-span-4 button').forEach(button => {
            button.addEventListener('click', function() {
                document.querySelectorAll('.lg\\:col-span-4 button').forEach(b => {
                    b.classList.remove('bg-primary/5', 'border-r-4', 'border-primary', 'text-primary');
                    b.classList.add('opacity-80');
                    const p = b.querySelector('p:first-child');
                    if(p) {
                        p.classList.remove('text-primary');
                        p.classList.add('text-on-surface');
                    }
                });
                this.classList.add('bg-primary/5', 'border-r-4', 'border-primary', 'text-primary');
                this.classList.remove('opacity-80');
                const p = this.querySelector('p:first-child');
                if(p) {
                    p.classList.add('text-primary');
                    p.classList.remove('text-on-surface');
                }
            });
        });

        // Performance sentiment tag interaction
        document.querySelectorAll('.flex-wrap button').forEach(tag => {
            tag.addEventListener('click', function() {
                document.querySelectorAll('.flex-wrap button').forEach(t => {
                    t.classList.remove('border-primary', 'text-primary');
                    t.classList.add('border-outline-variant', 'text-on-surface');
                });
                this.classList.add('border-primary', 'text-primary');
                this.classList.remove('border-outline-variant', 'text-on-surface');
            });
        });
    </script>

<script>
setTimeout(() => {

    const tb = document.querySelector('.fixed.top-5.right-5');

    if(tb){
        tb.remove();
    }

},3000);
</script>
</body>
</html>