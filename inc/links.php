<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<link
    href="https://fonts.googleapis.com/css2?family=Merienda:wght@300..900&family=Poppins:wght@400;500;600&display=swap"
    rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="css/common.css">
<?php
// Bắt đầu phiên làm việc khi user đăng nhập thành công
session_start();

// Thiết lập múi giờ mặc định cho tất cả các hàm xử lý ngày giờ trong một script, gọi bằng date("Y-m-d")
date_default_timezone_set("Asia/Ho_Chi_Minh");

require('admin/inc/db.config.php');
require('admin/inc/essentials.php');


// Dữ liệu chung contact và settings chung để xử lý dữ liệu cho các trang liên quan
$contact_q = "SELECT * FROM contact_details WHERE sr_no = ?";
$settings_q = "SELECT * FROM settings WHERE sr_no = ?";

$values = [1];
$datatypes = 'i';

$res1 = select($contact_q, $values, $datatypes);
$res2 = select($settings_q, $values, $datatypes);
$contact_r = mysqli_fetch_assoc($res1);  // ([sr_no] => 1 [address] => Duy Trinh,...)
$settings_r = mysqli_fetch_assoc($res2);

// Kiểm tra nút shutdown (nếu = 1 thì đóng trang web, user ko đặt phòng được
if ($settings_r['shutdown'] == 1) {
    echo <<<alertbar
        <div class="bg-danger text-center p-2 fw-bold">
            <i class="bi bi-exclamation-triangle-fill"></i>
            Việc đặt chỗ hiện đã đóng!
        </div>
    alertbar;
}



?>