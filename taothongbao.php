<?php

$conn = new mysqli("localhost","root","","quanlytrungtam");
$conn->set_charset("utf8");

if(isset($_POST['them'])){

    $sqlMax = "SELECT MAX(MaThongBao) AS MaMax FROM thongbao";
    $rowMax = $conn->query($sqlMax)->fetch_assoc();

    $so = (int) substr($rowMax['MaMax'], 2) + 1;
    $maTB = "TB" . str_pad($so, 3, "0", STR_PAD_LEFT);

    $tieuDe = $_POST['tieude'];
    $noiDung = $_POST['noidung'];

    $sql = "
    INSERT INTO thongbao
    VALUES(
        '$maTB',
        '$tieuDe',
        '$noiDung'
    )
    ";

    if($conn->query($sql)){
        echo "
        <script>
            alert('Tạo thông báo thành công!');
            window.location='tongquan.php';
        </script>
        ";
    }
}

?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Tạo thông báo</title>

<script src="https://cdn.tailwindcss.com"></script>

<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
body{
    font-family:'Be Vietnam Pro',sans-serif;
}
</style>

</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center">

<div class="bg-white w-full max-w-2xl rounded-2xl shadow-lg p-8">

    <div class="mb-8">
        <h1 class="text-3xl font-bold text-red-700">
            Tạo thông báo mới
        </h1>

        <p class="text-gray-500 mt-2">
            Gửi thông báo tới học viên và giáo viên trong hệ thống.
        </p>
    </div>

    <form method="post">

        <div class="mb-5">

            <label class="block mb-2 font-semibold">
                Tiêu đề
            </label>

            <input
                type="text"
                name="tieude"
                required
                class="w-full border border-gray-300 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-red-600"
                placeholder="Nhập tiêu đề thông báo..."
            >

        </div>

        <div class="mb-6">

            <label class="block mb-2 font-semibold">
                Nội dung
            </label>

            <textarea
                name="noidung"
                rows="6"
                required
                class="w-full border border-gray-300 rounded-xl p-3 focus:outline-none focus:ring-2 focus:ring-red-600"
                placeholder="Nhập nội dung thông báo..."
            ></textarea>

        </div>

        <div class="flex gap-3">

            <button
                type="submit"
                name="them"
                class="bg-red-700 text-white px-6 py-3 rounded-xl font-semibold hover:bg-red-800 transition"
            >
                Lưu thông báo
            </button>

            <a
                href="tongquan.php"
                class="px-6 py-3 rounded-xl border border-gray-300 hover:bg-gray-100 transition"
            >
                Quay lại
            </a>

        </div>

    </form>

</div>

</body>
</html>