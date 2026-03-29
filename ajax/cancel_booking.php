<?php
require('../admin/inc/db.config.php');
require('../admin/inc/essentials.php');
date_default_timezone_set("Asia/Ho_Chi_Minh");

// Bắt đầu phiên làm việc
session_start();

// Nếu user chưa login thì chuyển hướng đến trang index.php
if (!(isset($_SESSION['login']) && $_SESSION['login'] == true)) {
    redirect('index.php');
}
// Kiểm tra Nếu dữ liệu POST gửi lên có chứa cancel_booking thì thực hiện đoạn mã bên trong
if (isset($_POST['cancel_booking'])) {
    // Lọc dữ liệu POST gửi lên
    $frm_data = filteration($_POST);

    // Update dữ liệu
    $query = "UPDATE booking_order SET booking_status = ?, refund = ? 
        WHERE booking_id = ? AND user_id = ?";
    $values = ['cancelled', 0, $frm_data['id'], $_SESSION['uId']];
    $datatypes = 'siii';

    $res = update($query, $values, $datatypes);
    echo $res;    // Trả về số hàng ảnh hưởng (1 nếu 1 hàng update thành công 0 nếu thất bại)
}
?>