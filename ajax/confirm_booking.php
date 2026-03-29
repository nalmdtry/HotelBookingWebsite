<?php
require('../admin/inc/db.config.php');
require('../admin/inc/essentials.php');
date_default_timezone_set("Asia/Ho_Chi_Minh");

// Kiểm tra Nếu dữ liệu POST gửi lên có chứa check_availabitity thì thực hiện đoạn mã bên trong
if (isset($_POST['check_availabitity'])) {
    // Lọc dữ liệu POST gửi lên
    $frm_data = filteration($_POST);
    $status = "";   // Biến chứa dữ liệu nếu checkin hoặc checkout ko hợp lệ
    $result = "";

    // Chuyển chuỗi ngày tháng về đối tượng DateTime để gọi các phương thức xử lý tính toán số ngày,...
    $today_date = new DateTime(date("Y-m-d"));
    $checkin_date = new DateTime($frm_data['check_in']);
    $checkout_date = new DateTime($frm_data['check_out']);

    // Kiểm tra tính xác thực của checkin và checkout
    if ($checkin_date == $checkout_date) {
        $status = 'check_in_out_equal';
        $result = json_encode(["status" => $status]);     // Chuyển về chuỗi json
    } else if ($checkin_date > $checkout_date) {
        $status = 'check_out_earlier';
        $result = json_encode(["status" => $status]);
    } else if ($checkin_date < $today_date) {
        $status = 'check_in_earlier';
        $result = json_encode(["status" => $status]);
    }

    // Kiểm tra tình trạng đặt phòng nếu trạng thái trống ($status =''), nếu không trả về lỗi
    if ($status != '') {
        echo $result;   // In lỗi nếu trạng thái không trống
    } else {
        // Bắt đầu phiên làm việc để lấy dữ liệu phòng và xử lý các dữ liệu liên quan (số ngày, price,..)
        session_start();

        // Chạy truy vấn để kiểm tra phòng trống hay không

        // Truy vấn số lượng đơn đặt phòng đã được xác nhận (booked) cho loại phòng hiện tại (room_id) 
        // mà có sự chồng chéo về ngày với ngày khách hàng chọn (A ko kết thúc trước khi B bắt đầu, A ko bắt đầu sau khi B kết thúc)

        $tb_query = "SELECT COUNT(*) AS total_bookings FROM booking_order 
            WHERE booking_status = ? AND room_id = ? AND check_out > ? AND check_in < ?";
        $values = ['booked', $_SESSION['room']['id'], $frm_data['check_in'], $frm_data['check_out']];
        $datatypes = 'siss';
        $res = select($tb_query, $values, $datatypes);
        $tb_fetch = mysqli_fetch_assoc($res);       // Trả về tổng số lượng phòng đã bị chiếm dụng (Total Bookings)

        // Truy vấn lấy số lượng phòng có sẵn(quantity) của loại phòng hiện tại
        $rq_res = select("SELECT quantity FROM rooms WHERE id = ?", [$_SESSION['room']['id']], 'i');
        $rq_fetch = mysqli_fetch_assoc($rq_res);


        // Nếu tổng số lượng phòng - Tổng số phòng đã bị chiếm dụng thì update status = ko có sẵn
        if (($rq_fetch['quantity'] - $tb_fetch['total_bookings']) == 0) {
            $status = 'unavailable';
            $result = json_encode(["status" => $status]);
            echo $result;
            exit;
        }

        $count_days = date_diff($checkin_date, $checkout_date)->days;   // Trả về số ngày chênh lệch giữa ngày checkin và checkout

        // Tổng tiền = giá phòng * số ngày
        $payment = $_SESSION['room']['price'] * $count_days;

        // Lưu số tiền tính được vào session payment và đặt trạng thái có sẵn = true
        $_SESSION['room']['payment'] = $payment;
        $_SESSION['room']['available'] = true;

        // Trả chuỗi json về js xử lý
        $result = json_encode(["status" => 'available', "days" => $count_days, "payment" => $payment]);
        echo $result;


    }
}
?>