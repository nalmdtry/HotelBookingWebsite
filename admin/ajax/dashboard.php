<?php
require('../inc/db.config.php');
require('../inc/essentials.php');
adminLogin();


// Kiểm tra Nếu dữ liệu POST gửi lên có chứa booking_analytics thì thực hiện đoạn mã bên trong.
if (isset($_POST['booking_analytics'])) {
    // Lọc dữ liệu từ POST gửi lên 
    $frm_data = filteration($_POST);

    // Lọc thời gian hiển thị dữ liệu tương ứng với period (1: 30 ngày, 2: 90 ngày, 3: 1 năm, 4: mọi lúc)
    $condition = "";
    if ($frm_data['period'] == 1) {     // 30 ngày
        $condition = "WHERE datentime BETWEEN NOW() - INTERVAL 30 DAY AND NOW()";   // Ngày hiện tại đến 30 ngày trước
    } else if ($frm_data['period'] == 2) {     // 30 ngày
        $condition = "WHERE datentime BETWEEN NOW() - INTERVAL 90 DAY AND NOW()";   // Ngày hiện tại đến 90 ngày trước
    } else if ($frm_data['period'] == 3) {     // 30 ngày
        $condition = "WHERE datentime BETWEEN NOW() - INTERVAL 1 YEAR AND NOW()";   // Ngày hiện tại đến 1 năm trước
    }

    // Truy vấn đếm tổng số booking và tổng tiền (ko bao gồm trạng thái pending và payment failed), số booking active (status=1) và tổng tiền, số booking đã hủy (refund = 1) và tổng tiền từ bảng booking_order
    $current_bookings = mysqli_query($conn, "SELECT
        COUNT(CASE WHEN booking_status != 'pending' AND booking_status != 'payment failed' THEN 1 END) AS total_bookings,
        SUM(CASE WHEN booking_status != 'pending' AND booking_status != 'payment failed' THEN trans_amount END) AS total_amount,
        
        COUNT(CASE WHEN booking_status = 'booked' AND arrival = 1 THEN 1 END) AS active_bookings,
        SUM(CASE WHEN booking_status = 'booked' AND arrival = 1 THEN trans_amount END) AS active_amount,

        COUNT(CASE WHEN booking_status = 'cancelled' AND refund = 1 THEN 1 END) AS cancelled_bookings,
        SUM(CASE WHEN booking_status = 'cancelled' AND refund = 1 THEN trans_amount END) AS cancelled_amount

        FROM booking_order $condition");
    $res = mysqli_fetch_assoc($current_bookings);

    // Chuyển đối tượng thành chuỗi json
    $output = json_encode($res);
    echo $output;

}


// Kiểm tra Nếu dữ liệu POST gửi lên có chứa user_analytics thì thực hiện đoạn mã bên trong.
if (isset($_POST['user_analytics'])) {
    // Lọc dữ liệu từ POST gửi lên 
    $frm_data = filteration($_POST);

    // Lọc thời gian hiển thị dữ liệu tương ứng với period (1: 30 ngày, 2: 90 ngày, 3: 1 năm, 4: mọi lúc)
    $condition = "";
    if ($frm_data['period'] == 1) {     // 30 ngày
        $condition = "WHERE datentime BETWEEN NOW() - INTERVAL 30 DAY AND NOW()";   // Ngày hiện tại đến 30 ngày trước
    } else if ($frm_data['period'] == 2) {     // 30 ngày
        $condition = "WHERE datentime BETWEEN NOW() - INTERVAL 90 DAY AND NOW()";   // Ngày hiện tại đến 90 ngày trước
    } else if ($frm_data['period'] == 3) {     // 30 ngày
        $condition = "WHERE datentime BETWEEN NOW() - INTERVAL 1 YEAR AND NOW()";   // Ngày hiện tại đến 1 năm trước
    }

    // Truy vấn đếm số lượng phản hồi người dùng chưa đọc (seen=0)
    $total_reviews = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(sr_no) AS count
        FROM rating_review $condition"));
    // Truy vấn đếm số lượng phản hồi người dùng chưa đọc (seen=0)
    $total_queries = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(sr_no) AS count
        FROM user_queries $condition"));
    // Truy vấn đếm số lượng phản hồi người dùng chưa đọc (seen=0)
    $total_new_reg = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(id) AS count
        FROM user_cred $condition"));

    // Đưa các dữ liệu vào mảng
    $output = [
        'total_new_reg' => $total_new_reg['count'],
        'total_queries' => $total_queries['count'],
        'total_reviews' => $total_reviews['count']
    ];

    // Chuyển mảng về chuỗi json
    $output = json_encode($output);
    echo $output;

}
?>