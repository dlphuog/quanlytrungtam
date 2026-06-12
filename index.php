<?php
// include("config/connect.php");
?>
<!DOCTYPE html><html lang="vi" style=""><head> 
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>Anh Ngữ Athena - Hệ thống luyện thi TOEIC, IELTS hàng đầu</title>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&amp;display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: '#bb0025',
            secondary: '#333333',
            light: '#f8f9fa',
            brandRed: '#ff2d46',
          },
          fontFamily: {
            montserrat: ['Montserrat', 'sans-serif'],
          },
        }
      }
    }
  </script>
<style data-purpose="base-styling">
    body {
      font-family: 'Montserrat', sans-serif;
      color: #333;
    }
    .section-title {
      @apply text-2xl md:text-3xl font-bold text-center uppercase mb-8;
      color: #bb0025;
    }
    .course-card {
      @apply bg-white border border-gray-100 rounded-lg overflow-hidden transition-all duration-300 hover:shadow-lg;
    }
  </style>
<style data-stitch-cursor=""></style><style data-stitch-scroll-lock=""></style><style data-stitch-cursor=""></style><style data-stitch-scroll-lock=""></style><style data-stitch-cursor=""></style><style data-stitch-scroll-lock=""></style></head>
<body class="bg-white">
<!-- BEGIN: New Header Style based on IMAGE_7 -->
<header class="bg-white">
<!-- Top White Bar -->
<!-- Bottom Search/Contact Bar -->
<div class="border-gray-100 py-3 shadow-sm">
<div class="container mx-auto px-4 flex flex-wrap justify-between items-center gap-4"><div class="flex items-center space-x-2"><img alt="Athena Main Logo" class="h-12" src="/quanlytrungtam/logo.jpg"><div class="flex flex-col"><span class="font-bold text-primary leading-none text-lg uppercase">Anh Ngữ Athena</span><span class="text-[10px] text-gray-500 uppercase tracking-tighter">Học là giỏi, thi là đỗ</span></div></div><nav class="hidden md:flex items-center space-x-8 text-sm font-semibold"></nav><div class="flex items-center space-x-4">
<a class="text-sm font-bold text-primary" href="login.php">
Đăng nhập
</a>
<a class="bg-primary text-white px-6 py-2 rounded-full font-bold text-sm hover:bg-red-800 transition shadow-sm" href="register.php">
Đăng ký
</a>
</div></div>
</div>
</header>
<!-- END: New Header Style -->
<!-- Red Navigation Subbar -->
<!-- BEGIN: HeroBanner -->
<section class="relative bg-red-50">
<div class="container mx-auto px-4 py-12 md:flex items-center">
<div class="md:w-1/2 mb-8 md:mb-0">
<h2 class="text-primary font-bold text-xl mb-2">ANH NGỮ ATHENA</h2>
<h1 class="text-3xl md:text-5xl font-extrabold text-secondary leading-tight mb-6">Hệ thống luyện thi TOEIC, IELTS hàng đầu</h1>
<p class="text-gray-600 mb-8 max-w-lg">Giúp hàng nghìn học viên đạt mục tiêu chứng chỉ với phương pháp học hiện đại và lộ trình tinh gọn.</p>
<div class="flex gap-4">
<a class="bg-primary text-white px-8 py-3 rounded-full font-bold hover:bg-red-800 transition" href="#">Tư vấn chương trình</a>
</div>
</div>
<div class="md:w-1/2">
<img alt="Athena Hero" class="rounded-lg shadow-xl w-full" src="athenahero.png">
</div>
</section>
<!-- END: HeroBanner -->
<!-- BEGIN: FounderSection -->
<section class="py-16 bg-white" id="founder">
<div class="container mx-auto px-4">
<div class="text-center mb-12 flex flex-col items-center">
<h2 class="text-3xl md:text-4xl font-extrabold text-primary uppercase mb-3">Founder Anh Ngữ Athena</h2>
<div class="w-20 h-1 bg-primary rounded-full"></div>
</div>
<div class="flex flex-col md:flex-row items-center gap-12 max-w-5xl mx-auto">
<div class="md:w-1/3">
<div class="relative">
<img alt="Founder Ms. Van" class="rounded-lg shadow-2xl relative z-10" src="https://lh3.googleusercontent.com/aida-public/AB6AXuC_NGYI-ec-A24qCOvxzrkTwIzFiXHD0kUhg9ZVB5u9ce9fWCIi3LoryGVB54_rTIEY0xdY0FdzAwADMXXkvLcfTgvtdoG66gblH90qKLMLHofncZ5117NZRyblPVzoplNLSZnwE4vd7r0dVybZ03fWJvhe6_AuRim1eYM3n0ps2wQzZ8tHe82DEmJrjF4OGMnPwLcrW85uw8Dig5Zx0K6VTTokPyd_vntIF8OE5dggYb0ER7e1TPdLDJxH7Dab48G-vVJ7SNOmOfcO">
<div class="absolute -bottom-4 -right-4 w-full h-full border-4 border-primary rounded-lg -z-0"></div>
</div>
</div>
<div class="md:w-2/3 space-y-6">
<h3 class="text-2xl font-bold text-primary uppercase">Ms. Vân Anh</h3>
<p class="text-gray-700 leading-relaxed italic border-l-4 border-primary pl-4">
            "Với hơn 10 năm kinh nghiệm giảng dạy và tâm huyết xây dựng lộ trình học tinh gọn nhất, tôi cam kết giúp bạn chinh phục đỉnh cao Anh ngữ một cách dễ dàng và tiết kiệm thời gian nhất."
          </p>
<div class="space-y-3 text-gray-600">
<div class="flex items-start">
<span class="text-primary mr-2">✔</span>
<span class="">Chuyên gia luyện thi TOEIC/IELTS hàng đầu.</span>
</div>
<div class="flex items-start">
<span class="text-primary mr-2">✔</span>
<span class="">Người sáng lập phương pháp học Reflexive Learning.</span>
</div>
<div class="flex items-start">
<span class="text-primary mr-2">✔</span>
<span class="">Đã giúp hơn 30.000 học viên đạt mục tiêu.</span>
</div>
</div>
<div class="pt-4">
<button class="bg-primary text-white px-10 py-4 rounded-full font-bold uppercase tracking-wider hover:bg-red-800 transition-all shadow-lg hover:shadow-primary/50" data-purpose="founder-cta">
              Tìm hiểu thêm về Founder
            </button>
</div>
</div>
</div>
</div>
</section>
<!-- END: FounderSection -->
<!-- BEGIN: OnlineCourses -->
<section class="py-16 bg-gray-50">
<div class="container mx-auto px-4">
<div class="text-center mb-12 flex flex-col items-center">
<h2 class="text-3xl md:text-4xl font-extrabold text-primary uppercase mb-3">CÁC KHÓA HỌC ONLINE</h2>
<div class="w-20 h-1 bg-primary rounded-full"></div>
</div>
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
<!-- Course 1 -->
<div class="course-card p-4">
<img alt="TOEIC 500-750" class="w-full h-auto mb-4" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBKiO7F5xYUq4I30S9WQkFNg-HhhdSMdH3DGe8e56ABxnAE4EoBCkmZE_wzvVDa0rEcdZyfoRyJmCGcfuySRcTAFe4oey2gmwp5CZAwq2DWorwQE-fx_ZkSKpvloVwrNEr6Umg6jl40vX3zu7WFvQyTW1FhmLbC2AhbRmXYcDtkf44QDPmEwdqmLz8jMjOAdhpY56fstYOi8JJNcp0ZlLSm6zXzViyU0nW-uRfDWGFk75YVUztE7556wCjuH3cpFSvXJj7_S7F0Bp5q">
<div class="space-y-2">
<h3 class="font-bold text-lg text-gray-800">TOEIC từ mất gốc đạt 500-750+</h3>
<div class="flex items-center text-sm text-gray-600">
<span class="material-symbols-outlined mr-2 text-gray-800">account_circle</span>
              Giáo viên: 950+ TOEIC
            </div>
<div class="flex items-center text-sm text-gray-600">
<span class="material-symbols-outlined mr-2 text-gray-800">monetization_on</span>
              Học phí: <span class="text-primary font-bold ml-1">3.990.000đ</span>
</div>
<div class="text-xs text-gray-400 italic pl-7">Giá cũ: 8.000.000đ</div>
</div>
</div>
<!-- Course 2 -->
<div class="course-card p-4">
<img alt="Tự học TOEIC 500" class="w-full h-auto mb-4" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDQNr9HR6a-WFbrZQjvzZE-OUVRfKan_gTLt_NUPG7I3O248O3hdRCXlWwEvIdh8GjSsRphqK4pqR6kvQiQLggm6UpOBqm2NROsKkgqhVXbRg26I4O9yrW1nA5wxBito12YrIX0IYr2h7KO4OgvaIZjP6HgBUfh1d6IMoL3S6ii-AfH8pNg7bxewvvRwMz0EFguR-HEUBqHhNpO7u2W5Nzp4GRpzDf3tfpRRNBkvL-CmDesruse1a8mIYtcw_TIWAq45gnUtDbRNGXM">
<div class="space-y-2">
<h3 class="font-bold text-lg text-gray-800">Tự học TOEIC đạt 500+ từ mất gốc</h3>
<div class="flex items-center text-sm text-gray-600">
<span class="material-symbols-outlined mr-2 text-gray-800">account_circle</span>
              Giáo viên: ThS. Đỗ Vân Anh
            </div>
<div class="flex items-center text-sm text-gray-600">
<span class="material-symbols-outlined mr-2 text-gray-800">monetization_on</span>
              Học phí: <span class="text-primary font-bold ml-1">990.000đ</span>
</div>
<div class="text-xs text-gray-400 italic pl-7">Giá cũ: 4.000.000đ</div>
</div>
</div>
<!-- Course 3 -->
<div class="course-card p-4">
<img alt="TOEIC Cấp tốc" class="w-full h-auto mb-4" src="https://lh3.googleusercontent.com/aida-public/AB6AXuApglLQzKbZ9skCO7FxoKlPRHFTj1GdcbWlWpx1K0vK7lZGYr-aES5cSRJAI-L1HmsVUGinfO38ENSBDDEIXq5i8UI1P-Ju3vv97CBQ9mFkLuvEapqO40h5lELIXVDpnMBzSs9iFExNV5qSCp1rTfpb43p37tlxTSHpqY0CLbUHPhFB_K-yxa4sQoTf1LTRwTciSOqPHNAEZDItTaBXpcAkmVC-dul2YF3dyixG-8qpKYloI4giX46zRGKgB9rnoiWlf7l8hj3a_0d8">
<div class="space-y-2">
<h3 class="font-bold text-lg text-gray-800">TOEIC cấp tốc - 1 tháng TĂNG 200 điểm</h3>
<div class="flex items-center text-sm text-gray-600">
<span class="material-symbols-outlined mr-2 text-gray-800">account_circle</span>
              Giáo viên: ThS. Đỗ Vân Anh
            </div>
<div class="flex items-center text-sm text-gray-600">
<span class="material-symbols-outlined mr-2 text-gray-800">monetization_on</span>
              Học phí: <span class="text-primary font-bold ml-1">499.000đ</span>
</div>
<div class="text-xs text-gray-400 italic pl-7">Giá cũ: 1.000.000đ</div>
</div>
</div>
</div>
<div class="text-center mt-12">
<button class="border-2 border-primary text-primary px-8 py-2 rounded-full font-bold hover:bg-primary hover:text-white transition">Xem tất cả khóa học</button>
</div>
</div>
</section>
<!-- END: OnlineCourses -->
<!-- BEGIN: Lộ trình TOEIC -->
<section class="py-16 bg-white">
<div class="container mx-auto px-4">
<div class="text-center mb-12 flex flex-col items-center">
<h2 class="text-3xl md:text-4xl font-extrabold text-primary uppercase mb-3">LỘ TRÌNH TOEIC</h2>
<div class="w-20 h-1 bg-primary rounded-full"></div>
</div>
<div class="grid grid-cols-1 md:grid-cols-4 gap-4">
<div class="bg-red-50 p-6 rounded-xl border-t-4 border-primary text-center">
<div class="w-12 h-12 bg-primary text-white rounded-full flex items-center justify-center mx-auto mb-4 font-bold text-xl">1</div>
<h4 class="font-bold mb-2">Mất gốc - 350+</h4>
<p class="text-sm text-gray-600">Xây dựng nền tảng ngữ pháp và từ vựng cơ bản.</p>
</div>
<div class="bg-red-50 p-6 rounded-xl border-t-4 border-primary text-center">
<div class="w-12 h-12 bg-primary text-white rounded-full flex items-center justify-center mx-auto mb-4 font-bold text-xl">2</div>
<h4 class="font-bold mb-2">350 - 500+</h4>
<p class="text-sm text-gray-600">Luyện kỹ năng nghe hiểu và đọc hiểu sơ cấp.</p>
</div>
<div class="bg-red-50 p-6 rounded-xl border-t-4 border-primary text-center">
<div class="w-12 h-12 bg-primary text-white rounded-full flex items-center justify-center mx-auto mb-4 font-bold text-xl">3</div>
<h4 class="font-bold mb-2">500 - 700+</h4>
<p class="text-sm text-gray-600">Mẹo làm bài và giải đề chuyên sâu.</p>
</div>
<div class="bg-red-50 p-6 rounded-xl border-t-4 border-primary text-center">
<div class="w-12 h-12 bg-primary text-white rounded-full flex items-center justify-center mx-auto mb-4 font-bold text-xl">4</div>
<h4 class="font-bold mb-2">Target 900+</h4>
<p class="text-sm text-gray-600">Chinh phục những câu hỏi khó nhất trong đề thi.</p>
</div>
</div>
</div>
</section>
<!-- END: Lộ trình TOEIC -->
<!-- BEGIN: Lộ trình IELTS -->
<section class="py-16 bg-gray-50">
<div class="container mx-auto px-4">
<div class="text-center mb-12 flex flex-col items-center">
<h2 class="text-3xl md:text-4xl font-extrabold text-primary uppercase mb-3">CÁC KHÓA HỌC ONLINE</h2>
<div class="w-20 h-1 bg-primary rounded-full"></div>
</div>
<div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
<div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 flex flex-col items-center text-center">
<span class="material-symbols-outlined text-5xl text-primary mb-4">school</span>
<h4 class="text-xl font-bold mb-4">IELTS 3.5 - 4.5+</h4>
<p class="text-gray-600 mb-6 text-sm">Cho người mới bắt đầu hoặc mất căn bản hoàn toàn. Tập trung vào 4 kỹ năng ở mức độ làm quen.</p>
<ul class="text-left text-sm space-y-2 mb-8 text-gray-500">
<li class="flex items-center"><span class="material-symbols-outlined text-green-500 mr-2 text-sm">check_circle</span> Target: 3.5 - 4.5+</li>
<li class="flex items-center"><span class="material-symbols-outlined text-green-500 mr-2 text-sm">check_circle</span> Thời gian: 3 tháng</li>
</ul>
</div>
<div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 flex flex-col items-center text-center relative overflow-hidden">
<div class="absolute top-0 right-0 bg-primary text-white text-[10px] px-4 py-1 rotate-45 translate-x-3 translate-y-2 font-bold uppercase">Phổ biến</div>
<span class="material-symbols-outlined text-5xl text-primary mb-4">auto_stories</span>
<h4 class="text-xl font-bold mb-4">IELTS 5.5-6.5 </h4>
<p class="text-gray-600 mb-6 text-sm">Nâng cao kỹ năng làm bài, bổ sung từ vựng học thuật chuyên sâu cho từng chủ đề.</p>
<ul class="text-left text-sm space-y-2 mb-8 text-gray-500">
<li class="flex items-center"><span class="material-symbols-outlined text-green-500 mr-2 text-sm">check_circle</span> Target: 5.5 - 6.5+</li>
<li class="flex items-center"><span class="material-symbols-outlined text-green-500 mr-2 text-sm">check_circle</span> Thời gian: 4 tháng</li>
</ul>
</div>
<div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 flex flex-col items-center text-center">
<span class="material-symbols-outlined text-5xl text-primary mb-4">military_tech</span>
<h4 class="text-xl font-bold mb-4">IELTS 7.0 - 7.5+</h4>
<p class="text-gray-600 mb-6 text-sm">Chỉnh sửa lỗi sai, tối ưu hóa điểm số cho Writing &amp; Speaking để đạt mức giỏi.</p>
<ul class="text-left text-sm space-y-2 mb-8 text-gray-500">
<li class="flex items-center"><span class="material-symbols-outlined text-green-500 mr-2 text-sm">check_circle</span> Target: 7.0 - 7.5+</li>
<li class="flex items-center"><span class="material-symbols-outlined text-green-500 mr-2 text-sm">check_circle</span> Thời gian: 2 tháng</li>
</ul>
</div>
</div>
</div>
</section>
<!-- END: Lộ trình IELTS -->
<!-- BEGIN: FreeTrialSection -->
<section class="py-16 bg-white">
<div class="container mx-auto px-4">
<div class="text-center mb-12 flex flex-col items-center">
<h2 class="text-3xl md:text-4xl font-extrabold text-primary uppercase mb-3">Học thử miễn phí (YouTube)</h2>
<div class="w-20 h-1 bg-primary rounded-full"></div>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
<div class="group">
<a class="relative block overflow-hidden rounded-xl aspect-video bg-gray-200 mb-4 shadow-lg" href="https://www.youtube.com/watch?v=c7a8UqBAIqw" target="_blank"><img alt="Xử lý PART 1 TOEIC LISTENING" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDJyno1he4Jdg4Tc2oCkv4YslVYC1o2sWcIuogemnsp71oopVLoPpn6NpF8NmwVr8hFA-V2_Erv3-tjatdlFogKO_OyIjPprxlGGT2Eh5wZnpT0MuvKYtkYSny7OeqEht3SqgwZO32F08Oxkgmg71vTjLmJzeGQB1q9Hs0Ldc3HN0H_GUgWdHNFTwx0dmNjfwXqgI2-LCxs54Z60_s_lkECj6CIGpGN0JDQfwuC72foLvLxBwO00elAt_u4fr0D6lLnntOjlGFcs8bT"><div class="absolute inset-0 bg-black/30 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity"><div class="w-16 h-16 bg-red-600 rounded-full flex items-center justify-center text-white"><svg class="w-8 h-8 ml-1" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"></path></svg></div></div></a>
<h3 class="font-bold text-gray-800 mb-3 line-clamp-1">Xử lý PART 1 TOEIC LISTENING</h3>
<a class="block text-center w-full bg-red-600 text-white py-2 rounded-lg font-bold text-sm uppercase tracking-tight hover:bg-red-700 transition" href="https://www.youtube.com/watch?v=c7a8UqBAIqw" target="_blank">HỌC NGAY TRÊN YOUTUBE</a>
</div>
<div class="group">
<a class="relative block overflow-hidden rounded-xl aspect-video bg-gray-200 mb-4 shadow-lg" href="https://www.youtube.com/watch?v=V7JswbPsgeA" target="_blank"><img alt="IELTS SPEAKING PART 1, 2, 3" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBacU47Uy0N-nr4efyKQNE8QCXEqFV6KX7XwDciziG1Pw7w90w1CFCSEMHiuYoYK5dzssVeirYr80uzuIx6VCJ4tPFvH7esDzCc-TwXaUkUP7FGYfdyLnNtK6wA2pH1aNXBGGOR9ECRIx6mD6sDbAiKD4x0ElU5eYoPRboO0kwqb5UXI1Mj-SpCpnYYyTierxAoHWYFuBDn39myZYkGqCYDfelA_MEgeOfkJ6JeIL9KAbDyEhXss_GXk3_bWpFzLACFtFzg3injuCJa"><div class="absolute inset-0 bg-black/30 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity"><div class="w-16 h-16 bg-red-600 rounded-full flex items-center justify-center text-white"><svg class="w-8 h-8 ml-1" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"></path></svg></div></div></a>
<h3 class="font-bold text-gray-800 mb-3 line-clamp-1">IELTS SPEAKING PART 1, 2, 3</h3>
<a class="block text-center w-full bg-red-600 text-white py-2 rounded-lg font-bold text-sm uppercase tracking-tight hover:bg-red-700 transition" href="https://www.youtube.com/watch?v=V7JswbPsgeA" target="_blank">HỌC NGAY TRÊN YOUTUBE</a>
</div>
<div class="group">
<a class="relative block overflow-hidden rounded-xl aspect-video bg-gray-200 mb-4 shadow-lg" href="https://www.youtube.com/watch?v=KxAovJtu6H8" target="_blank"><img alt="KHÓA TOEIC MIỄN PHÍ DÀNH CHO NGƯỜI MẤT GỐC" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCoB8xjq096GbglN6hlNiFduhaHcTSGwlVgZ1vWhkEl7UyBUKrPDOFGXd_ThMo1WyEifNaxFcwUpcxqkNMxexQcPTc5fJp45X8Girqz0L88wbm4SB3mUARDbe6Czz0sPWdI-rq04xb13MpFDvD7eY0KbvtVYl0MXD4lwDWWw00MBBOTDy_8OFOoSHwpDOX-bbECpXapHcn5h_Rcv3ANtRfWlDcybKvsw_1OuanEddZYChzwRRkV-wW1Pw7fXddq_loVh_Y-r7KcVK7l"><div class="absolute inset-0 bg-black/30 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity"><div class="w-16 h-16 bg-red-600 rounded-full flex items-center justify-center text-white"><svg class="w-8 h-8 ml-1" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"></path></svg></div></div></a>
<h3 class="font-bold text-gray-800 mb-3 line-clamp-1">KHÓA TOEIC MIỄN PHÍ DÀNH CHO NGƯỜI MẤT GỐC</h3>
<a class="block text-center w-full bg-red-600 text-white py-2 rounded-lg font-bold text-sm uppercase tracking-tight hover:bg-red-700 transition" href="https://www.youtube.com/watch?v=KxAovJtu6H8" target="_blank">HỌC NGAY TRÊN YOUTUBE</a>
</div>
</div>
</div>
</section>
<!-- END: FreeTrialSection -->
<!-- BEGIN: CTASection -->
<section class="py-20 bg-primary text-white text-center">
<div class="container mx-auto px-4 max-w-4xl">
<h2 class="text-3xl md:text-5xl font-bold mb-6">Bắt đầu hành trình của bạn ngay hôm nay</h2>
<p class="text-lg md:text-xl mb-10 opacity-90">Liên hệ ngay tổng đài hotline để được tư vấn lộ trình học cá nhân hóa miễn phí và ưu đãi học phí hấp dẫn</p>
<div class="flex flex-col sm:flex-row justify-center gap-4">
<button class="border-2 border-white text-white px-10 py-4 rounded-full font-bold uppercase hover:bg-white/10 transition">Hotline: 0983662216</button>
</div>
</div>
</section>
<!-- END: CTASection -->
<!-- BEGIN: MainFooter -->
<footer class="bg-gray-900 text-gray-300 py-16">
<div class="container mx-auto px-4 grid grid-cols-1 md:grid-cols-4 gap-12">
<div class="col-span-1 md:col-span-1">
<img alt="Athena Logo Footer" class="h-10 mb-6 brightness-0 invert" data-purpose="footer-logo" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBxsNL5OjshkiOCAU5_-mpL0N-SSRhi6i-Xjen51nRvp7iNShgQkl0Po9SEIz09etTfRFrpeTVC7NP7xDjfDBUxkxr15iuw59v4XRvc84NrqBD-EI6Hvknud4nXVct44u6TCLb_5nI_DsKKb6bOe5tcDcqnyKRIku5lNJZGVwYa40f1Z_B_qPvRkxB2yT-_hP0BZAGVYd4HjRm7UgY9mqzDr3O0vH3_J9oT6ZSX2eE81e54-ccxiP00LiPVHBVfN3C_zXpJfhDmACYl">
<p class="text-sm leading-relaxed">Hệ thống Anh Ngữ Athena chuyên đào tạo TOEIC và IELTS cam kết đầu ra bằng văn bản. Chúng tôi tự hào đồng hành cùng sự nghiệp của bạn.</p>
</div>
<div>
<h4 class="text-white font-bold mb-6 uppercase text-sm tracking-widest">Về Athena</h4>
<ul class="space-y-4 text-sm">
<li class=""><a class="hover:text-primary" href="#">Về chúng tôi</a></li>
<li class=""><a class="hover:text-primary" href="#">Đội ngũ giáo viên</a></li>
<li class=""><a class="hover:text-primary" href="#">Tuyển dụng</a></li>
<li class=""><a class="hover:text-primary" href="#">Liên hệ</a></li>
</ul>
</div>
<div>
<h4 class="text-white font-bold mb-6 uppercase text-sm tracking-widest">Hỗ trợ học tập</h4>
<ul class="space-y-4 text-sm">
<li class=""><a class="hover:text-primary" href="#">Lịch khai giảng</a></li>
<li class=""><a class="hover:text-primary" href="#">Tài liệu IELTS</a></li>
<li class=""><a class="hover:text-primary" href="#">Tài liệu TOEIC</a></li>
<li class=""><a class="hover:text-primary" href="#">Kinh nghiệm học tập</a></li>
</ul>
</div>
<div>
<h4 class="text-white font-bold mb-6 uppercase text-sm tracking-widest">Đăng ký nhận tin</h4>
<div class="flex">
<input class="bg-gray-800 border-none rounded-l px-4 py-2 w-full focus:ring-1 focus:ring-primary text-sm" placeholder="Email của bạn" type="email">
<button class="bg-primary px-4 rounded-r hover:bg-red-800">
<svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M14 5l7 7m0 0l-7 7m7-7H3" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></path></svg>
</button>
</div>
<div class="flex space-x-4 mt-8">
<a class="w-8 h-8 rounded-full bg-gray-800 flex items-center justify-center hover:bg-primary transition" href="#"><span class="sr-only">Facebook</span>f</a>
<a class="w-8 h-8 rounded-full bg-gray-800 flex items-center justify-center hover:bg-primary transition" href="#"><span class="sr-only">YouTube</span>y</a>
<a class="w-8 h-8 rounded-full bg-gray-800 flex items-center justify-center hover:bg-primary transition" href="#"><span class="sr-only">Tiktok</span>t</a>
</div>
</div>
</div>
<div class="container mx-auto px-4 mt-16 pt-8 border-t border-gray-800 text-center text-xs opacity-50">
<p class="">© <?= date('Y') ?> Trung tâm Anh ngữ Athena. Chính phục ngôn ngữ, mở lối tương lai.</p>
</div>
</footer>
<!-- END: MainFooter -->


</body></html>