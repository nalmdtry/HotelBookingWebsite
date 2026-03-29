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
// Kiểm tra Nếu dữ liệu POST gửi lên có chứa review_form thì thực hiện đoạn mã bên trong
if (isset($_POST['review_form'])) {
    // Lọc dữ liệu POST gửi lên
    $frm_data = filteration($_POST);

    // Update cột rate_review = 1 để đánh giá phòng
    $upd_query = "UPDATE booking_order SET rate_review = ? WHERE booking_id = ? AND user_id = ?";
    $upd_values = [1, $frm_data['booking_id'], $_SESSION['uId']];
    $upd_datatypes = 'iii';
    $upd_res = update($upd_query, $upd_values, $upd_datatypes);

    // Chèn dữ liệu vào bảng rating_review
    $ins_query = "INSERT INTO rating_review(booking_id, room_id, user_id, rating, review) 
    VALUES (?,?,?,?,?)";
    $ins_values = [$frm_data['booking_id'], $frm_data['room_id'], $_SESSION['uId'], $frm_data['rating'], $frm_data['review']];
    $ins_datatypes = 'iiiis';
    $ins_res = insert($ins_query, $ins_values, $ins_datatypes);

    echo $ins_res;    // Trả về số hàng ảnh hưởng (1 nếu 1 hàng được chèn thành công 0 nếu thất bại)
}
?>