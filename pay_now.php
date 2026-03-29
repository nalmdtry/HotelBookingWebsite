Kiểm tra lại xem 2 file sau ổn chưa
<?php
require('admin/inc/db.config.php');
require('admin/inc/essentials.php');

// Thiết lập múi giờ mặc định cho trang
date_default_timezone_set("Asia/Ho_Chi_Minh");

// Bắt đầu phiên làm việc
session_start();

// Kiểm tra người dùng login hay chưa
if (!(isset($_SESSION['login']) && $_SESSION['login'] == true)) {
    redirect('index.php');      // Nếu chưa login thì chuyển về trang index.php
}

// Kiểm tra người dùng ấn vào nút pay_now (Thanh toán ngay) chưa
if (!isset($_POST['pay_now'])) {
    redirect('index.php');
}

// Lọc dữ liệu form
$frm = filteration($_POST);

$amount = $_SESSION['room']['payment'];         // Tổng số tiền
$orderId = 'ORD_' . $_SESSION['uId'] . random_int(11111, 9999999);             // Mã đơn hàng
$transID = 'TRANS_' . $_SESSION['uId'] . random_int(11111, 9999999);             // Mã đơn hàng

// Chèn dữ liệu vào bảng booking_order
$query1 = "INSERT INTO booking_order(
                user_id, room_id, check_in, check_out, booking_status, order_id, 
                trans_id, trans_amount, trans_status, trans_message)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$values1 = [
    $_SESSION['uId'],
    $_SESSION['room']['id'],
    $frm['checkin'],
    $frm['checkout'],
    'booked',
    $orderId,
    $transID,
    $amount,
    'success',
    'Thanh toán trực tiếp thành công'
];
$datatypes1 = 'iisssssiss';
insert($query1, $values1, $datatypes1);

// Lấy booking_id từ insert trên
$booking_id = mysqli_insert_id($conn);

// Chèn dữ liệu vào bảng booking_details
$query2 = "INSERT INTO booking_details(booking_id, room_name, price, total_pay, user_name, phonenum, address)
               VALUES (?,?,?,?,?,?,?)";

$values2 = [
    $booking_id,
    $_SESSION['room']['name'],
    $_SESSION['room']['price'],
    $amount,
    $frm['name'],
    $frm['phonenum'],
    $frm['address']
];
$datatypes2 = 'isiisss';
insert($query2, $values2, $datatypes2);

// Tự xoá session room sau khi đặt phòng thành công
if (isset($_SESSION['room'])) {
    unset($_SESSION['room']);
}

// Chuyển hướng đến trang pay_status.php kèm orderId 
redirect('pay_status.php?order=' . $orderId);
exit;
?>