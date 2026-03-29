<?php
require('../admin/inc/db.config.php');
require('../admin/inc/essentials.php');
date_default_timezone_set("Asia/Ho_Chi_Minh");

// Bắt đầu phiên làm việc
session_start();

// Kiểm tra Nếu dữ liệu GET gửi lên có chứa fetch_rooms thì thực hiện đoạn mã bên trong
if (isset($_GET['fetch_rooms'])) {

    // Check availability data decode, chuyển chuỗi json về đối tượng php để xử lý, có true: trở thành mảng kết hợp để truy cập vào index
    $chk_avail = json_decode($_GET['chk_avail'], true);

    // Xác thực bộ lọc checkin và checkout
    if ($chk_avail['checkin'] != '' && $chk_avail['checkout'] != '') {
        // Chuyển chuỗi ngày tháng về đối tượng DateTime để gọi các phương thức xử lý tính toán số ngày,...
        $today_date = new DateTime(date("Y-m-d"));
        $checkin_date = new DateTime($chk_avail['checkin']);
        $checkout_date = new DateTime($chk_avail['checkout']);

        // Kiểm tra tính xác thực của checkin và checkout
        if ($checkin_date == $checkout_date) {
            echo "<h3 class='text-center text-danger'>Ngày nhập không hợp lệ!</h3>";
            exit;
        } else if ($checkin_date > $checkout_date) {
            echo "<h3 class='text-center text-danger'>Ngày nhập không hợp lệ!</h3>";
            exit;
        } else if ($checkin_date < $today_date) {
            echo "<h3 class='text-center text-danger'>Ngày nhập không hợp lệ!</h3>";
            exit;
        }
    }

    // Guests data decode, chuyển chuỗi json về đối tượng php để xử lý, có true: đối tượng trả về trở thành mảng kết hợp để truy cập vào index
    $guests = json_decode($_GET['guests'], true);
    $adults = ($guests['adults'] != '') ? $guests['adults'] : 0;
    $children = ($guests['children'] != '') ? $guests['children'] : 0;

    // Facilities data decode, chuyển chuỗi json về đối tượng php để xử lý, có true: đối tượng trả về trở thành mảng kết hợp để truy cập vào index
    $facility_list = json_decode($_GET['facility_list'], true);



    // Đếm số phòng và biến output để lưu trữ thẻ phòng
    $count_rooms = 0;
    $output = "";

    // Kiểm tra xem trang web đã shutdown hay chưa
    $settings_q = "SELECT * FROM settings WHERE sr_no = 1";
    $settings_r = mysqli_fetch_assoc(mysqli_query($conn, $settings_q));

    // Truy vấn thông tin phòng với bộ lọc guests , kèm trạng thái hoạt động + removed = 0 (chưa xóa)
    $room_res = select("SELECT * FROM rooms WHERE adult >= ? AND children >= ? 
        AND status = ? AND removed = ?", [$adults, $children, 1, 0], 'iiii');

    while ($room_data = mysqli_fetch_assoc($room_res)) {    // Lặp từng phòng (Lấy từng hàng kết quả dưới dạng mảng kết hợp)

        // Kiểm tra bộ lọc phòng trống
        if ($chk_avail['checkin'] != '' && $chk_avail['checkout'] != '') {
            // Chạy truy vấn để kiểm tra phòng trống hay không

            // Truy vấn số lượng đơn đặt phòng đã được xác nhận (booked) cho loại phòng hiện tại (room_id) 
            // mà có sự chồng chéo về ngày với ngày khách hàng chọn (A ko kết thúc trước khi B bắt đầu, A ko bắt đầu sau khi B kết thúc)

            $tb_query = "SELECT COUNT(*) AS total_bookings FROM booking_order 
            WHERE booking_status = ? AND room_id = ? AND check_out > ? AND check_in < ?";
            $values = ['booked', $room_data['id'], $chk_avail['checkin'], $chk_avail['checkout']];
            $datatypes = 'siss';

            $res = select($tb_query, $values, $datatypes);
            $tb_fetch = mysqli_fetch_assoc($res);       // Trả về tổng số lượng phòng đã bị chiếm dụng (Total Bookings)

            // Nếu tổng số lượng phòng - Tổng số phòng đã bị chiếm dụng thì update status = ko có sẵn
            if (($room_data['quantity'] - $tb_fetch['total_bookings']) == 0) {
                continue;
            }
        }

        // Lấy tiện nghi của phòng với bộ lọc filter
        // Lấy tên tiện nghi của phòng, join giữa facilities và room_facilities để lấy name của facilities cho room cụ thể 
        $fac_count = 0;     // Biến chứa số lượng facilities
        $fac_q = mysqli_query($conn, "SELECT f.name, f.id FROM facilities f 
                    INNER JOIN room_facilities rfac ON f.id = rfac.facilities_id 
                    WHERE rfac.room_id = {$room_data['id']}");

        $facilities_data = "";
        // Lặp qua mỗi facilities
        while ($fac_row = mysqli_fetch_assoc($fac_q)) {
            // Kiểm tra nếu facilities trong db có nằm trong chuỗi facilities user chọn ko
            if (in_array($fac_row['id'], $facility_list['facilities'])) {
                $fac_count++;   // Tăng số lượng fac lên 1
            }
            $facilities_data .= "<span class='badge rounded-pill bg-light text-dark text-wrap me-1 mb-1'>
                                    {$fac_row['name']}
                                </span>";
        }

        // Kiểm tra nếu số lượng faci trong danh sách user chọn ko bằng số lượng fac có trong db
        if (count($facility_list['facilities']) != $fac_count) {
            continue;   // Kết thúc vòng lặp while hiện tại và chuyển sang vòng lặp while tiếp theo
        }


        // Lấy tên tính năng của phòng, join giữa features và room_features để lấy name của feature cho room cụ thể   
        $fea_q = mysqli_query($conn, "SELECT f.name FROM features f 
                    INNER JOIN room_features rfea ON f.id = rfea.features_id 
                    WHERE rfea.room_id = {$room_data['id']}");

        $features_data = "";
        while ($fea_row = mysqli_fetch_assoc($fea_q)) {
            $features_data .= "<span class='badge rounded-pill bg-light text-dark text-wrap me-1 mb-1'>
                                    {$fea_row['name']}
                                </span>";
        }
        // echo $features_data;



        // Lấy thumbnail của hình ảnh phòng
        $room_thumb = ROOMS_IMG_PATH . "thumbnail.jpg";     // Mặc định room_thumb là thumbnail.jpg chung
        $thumb_q = mysqli_query($conn, "SELECT * FROM room_images 
                    WHERE room_id = {$room_data['id']} AND thumb = 1");

        // Kiểm tra số hàng trả về từ truy vấn
        if (mysqli_num_rows($thumb_q) > 0) {
            $thumb_res = mysqli_fetch_assoc($thumb_q);
            $room_thumb = ROOMS_IMG_PATH . $thumb_res['image'];     // Nếu có kết quả, set $room_thumb thành file trong DB
        }

        // Format giá dấu phân cách hàng nghìn
        $price = number_format($room_data['price'], 0, ',', '.');

        // Nếu trạng thái shutdown = 1 thì đóng trang, ko hiện nút đặt phòng
        $book_btn = "";
        if (!$settings_r['shutdown'] == 1) {
            // Kiểm tra người dùng login chưa
            $login = 0;
            if (isset($_SESSION['login']) && $_SESSION['login'] == true) {
                $login = 1;
            }

            // Gọi hàm kiểm tra đăng nhập trước khi book
            $book_btn = "<button onclick='checkLoginToBook($login, {$room_data['id']})' class='btn btn-sm w-100 text-white custome-bg shadow-none mb-2'>Đặt phòng ngay</button>";
        }

        // In thẻ phòng
        $output .= " 
            <div class='card mb-4 border-0 shadow'>
                            <div class='row g-0 p-3 align-items-center'>
                                <div class='col-md-5 mb-lg-0 mb-md-0 mb-3'>
                                    <img src='$room_thumb' class='img-fluid rounded'>
                                </div>
                                <div class='col-md-5 px-lg-3 px-md-3 px-0'>
                                    <h5 class='mb-3'>{$room_data['name']}</h5>

                                    <!-- Phần hiện các tính năng của phòng -->
                                    <div class='features mb-3'>
                                        <h6 class='mb-1'>Tính năng</h6>
                                        $features_data
                                    </div>

                                    <!-- Phần hiện các tiện nghi của phòng -->
                                    <div class='facilities mb-3'>
                                        <h6 class='mb-1'>Tiện nghi</h6>
                                        $facilities_data
                                    </div>

                                    <!-- Phần hiện guests-->
                                    <div class='guests'>
                                        <h6 class='mb-1'>Khách</h6>
                                        <span class='badge rounded-pill bg-light text-dark text-wrap'>
                                        {$room_data['adult']} Người lớn
                                        </span>
                                        <span class='badge rounded-pill bg-light text-dark text-wrap'>
                                        {$room_data['children']} Trẻ em
                                        </span>
                                    </div>
                                </div>

                                <!-- Phần hiện nút hành động -->
                                <div class='col-md-2 mt-lg-0 mt-md-0 mt-4 text-center'>
                                    <div class='card-body'>
                                        <h6 class='mb-4'>$price ₫/đêm</h6>
                                        $book_btn
                                        <a href='room_details.php?id={$room_data['id']}' class='btn btn-sm w-100 btn-outline-dark shadow-none'>Xem chi tiết</a>
                                    </div>
                                </div>
                            </div>
                        </div>
        ";

        $count_rooms++;
    }

    // Kiểm tra nếu số phòng > 0 thì trả về output về js
    if ($count_rooms > 0) {
        echo $output;
    } else {
        echo "<h3 class='text-center text-danger'>Không có phòng nào để hiển thị!</h3>";
    }
}

?>