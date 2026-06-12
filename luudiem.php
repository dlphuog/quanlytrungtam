<?php

$conn = new mysqli(
    "localhost",
    "root",
    "",
    "quanlytrungtam"
);

$conn->set_charset("utf8");

$maLop = $_POST['ma_lop'] ?? '';

if($maLop == ''){
    die("Không nhận được mã lớp");
}

foreach($_POST['baitap'] as $maHV => $diem){

    $baitap = $_POST['baitap'][$maHV] ?? 0;
    $giuaky = $_POST['giuaky'][$maHV] ?? 0;
    $cuoiky = $_POST['cuoiky'][$maHV] ?? 0;

    $conn->query("
        DELETE FROM ketquahoctap
        WHERE MaHocVien='$maHV'
        AND MaLopHoc='$maLop'
    ");

    $conn->query("
        INSERT INTO ketquahoctap
        VALUES(
            CONCAT('BT', FLOOR(RAND()*100000)),
            '$maHV',
            '$maLop',
            '$baitap',
            NULL,
            'Bai tap',
            CURDATE()
        )
    ");

    $conn->query("
        INSERT INTO ketquahoctap
        VALUES(
            CONCAT('GK', FLOOR(RAND()*100000)),
            '$maHV',
            '$maLop',
            '$giuaky',
            NULL,
            'Giua ky',
            CURDATE()
        )
    ");

    $conn->query("
        INSERT INTO ketquahoctap
        VALUES(
            CONCAT('CK', FLOOR(RAND()*100000)),
            '$maHV',
            '$maLop',
            '$cuoiky',
            NULL,
            'Cuoi ky',
            CURDATE()
        )
    ");
}

header(
    "Location: quanlydiemso.php?lop=".$maLop."&success=1"
);
exit;