<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NLam Hotel - CONFIRM BOOKING</title>
    <?php require('inc/links.php'); ?>
    <style>
        .h-line {
            width: 150px;
            margin: 0 auto;
            height: 1.7px;
        }
    </style>
</head>

<body class="bg-light">
    <!-- Header (Thanh navbar, modal đăng kí, modal đăng nhập) -->
    <?php require('inc/header.php'); ?>

    <!-- Kiểm tra ID phòng từ URL có tồn tại hay không
         Chế độ shutdown có đang hoạt động hay không
         Người dùng đã đăng nhập hay chưa -->
    <?php

    // Kiểm tra ID phòng từ URL có tồn tại và chế độ shutdown có đang hoạt động hay không
    if (!isset($_GET['id']) || $settings_r['shutdown'] == 1) {      // Lấy tham số id từ URL
        redirect('rooms.php');
    } else if (!(isset($_SESSION['login']) && $_SESSION['login'] == true)) {    // Kiểm tra người dùng đã đăng nhập hay chưa 
        redirect('rooms.php');
    }

    // Lọc và lấy dữ liệu phòng và người dùng
    
    $data = filteration($_GET);

    // Truy vấn lấy phòng có id, status và trạng thái removed được chỉ định
    $q = "SELECT * FROM rooms WHERE id = ? AND status = ? AND removed = ?";
    $values = [$data['id'], 1, 0];
    $datatypes = 'iii';
    $room_res = select($q, $values, $datatypes);

    // Kiểm tra số hàng trả về từ truy vấn
    if (mysqli_num_rows($room_res) == 0) {
        redirect('rooms.php');
    }

    // Lấy 1 hàng kết quả dưới dạng mảng kết hợp
    $room_data = mysqli_fetch_assoc($room_res);

    // Tạo mảng session lưu thông tin của phòng
    $_SESSION['room'] = [
        "id" => $room_data['id'],
        "name" => $room_data['name'],
        "price" => $room_data['price'],
        "payment" => null,
        "available" => false,
    ];

    // Lấy dữ liệu người dùng
    $user_res = select("SELECT * FROM user_cred WHERE id = ? LIMIT 1", [$_SESSION['uId']], 'i');
    $user_data = mysqli_fetch_assoc($user_res);
    ?>

    <!-- Vùng chứa Rooms -->
    <div class="container">
        <div class="row">

            <!-- Phần tiêu đề -->
            <div class="col-12 my-5 mb-4 px-4">
                <h2 class="fw-bold">XÁC NHẬN ĐẶT PHÒNG</h2>
                <div style="font-size: 14px;">
                    <a href="index.php" class="text-secondary text-decoration-none">Trang chủ</a>
                    <span class="text-secondary"> > </span>
                    <a href="rooms.php" class="text-secondary text-decoration-none">Phòng</a>
                    <span class="text-secondary"> > </span>
                    <a href="#" class="text-secondary text-decoration-none">Xác nhận</a>
                </div>
            </div>

            <!-- Phần hiển thị thumbnail của hình ảnh phòng (cột trái) -->
            <div class="col-lg-7 col-md-12 px-4">
                <?php
                // Lấy thumbnail của hình ảnh phòng
                $room_thumb = ROOMS_IMG_PATH . "thumbnail.jpg";     // Mặc định room_thumb là thumbnail.jpg chung
                $thumb_q = mysqli_query($conn, "SELECT * FROM room_images 
                    WHERE room_id = {$room_data['id']} AND thumb = 1");

                // Kiểm tra số hàng trả về từ truy vấn
                if (mysqli_num_rows($thumb_q) > 0) {
                    $thumb_res = mysqli_fetch_assoc($thumb_q);
                    $room_thumb = ROOMS_IMG_PATH . $thumb_res['image'];     // Nếu có kết quả, set $room_thumb thành file trong DB
                }

                // Format giá phân cách hàng nghìn
                $price = number_format($room_data['price'], 0, ',', '.');
                // In khối heredoc
                echo <<<data
                    <div class="card p-3 shadow-sm rounded">
                        <img src="$room_thumb" class="img-fluid rounded mb-3">
                        <h5>{$room_data['name']}</h5>
                        <h6>$price ₫/đêm</h6>
                    </div>
                data;


                ?>
            </div>

            <!-- Phần hiển thị thông tin người đặt và nút thanh toán (cột phải)-->
            <div class="col-lg-5 col-md-12 px-4">
                <div class="card mb-4 border-0 shadow-sm rounded-3">
                    <div class="card-body">
                        <!-- <form action="inc/paytm/config_payment.php" method="POST" id="booking_form"> -->
                        <form method="POST" id="booking_form" enctype="application/x-www-form-urlencoded"
                            action="pay_now.php">
                            <h6 class="mb-3">CHI TIẾT ĐẶT PHÒNG</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Họ và tên</label>
                                    <input name="name" type="text" value="<?php echo $user_data['name']; ?>"
                                        class="form-control shadow-none" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Số điện thoại</label>
                                    <input name="phonenum" type="number" value="<?php echo $user_data['phonenum']; ?>"
                                        class="form-control shadow-none" required>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Địa chỉ</label>
                                    <textarea name="address" class="form-control shadow-none" rows="1"
                                        required><?php echo $user_data['address']; ?></textarea>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Ngày nhận phòng</label>
                                    <input name="checkin" onchange="check_availabitity()" type="date"
                                        class="form-control shadow-none" required>
                                </div>

                                <div class="col-md-6 mb-4">
                                    <label class="form-label">Ngày trả phòng</label>
                                    <input name="checkout" onchange="check_availabitity()" type="date"
                                        class="form-control shadow-none" required>
                                </div>

                                <div class="col-12">
                                    <!-- Spinner -->
                                    <div class="spinner-border text-primary mb-3 d-none" id="info_loader" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>

                                    <!-- Text hiển thị thông tin sau khi chọn check-in-out -->
                                    <h6 class="mb-3 text-danger" id="pay_info">Cung cấp ngày nhận phòng và ngày trả
                                        phòng!</h6>

                                    <!-- Nút đặt phòng ngay -->
                                    <button name="pay_now" class="btn w-100 text-white custome-bg shadow-none mb-1"
                                        disabled>Thanh toán
                                        ngay</button>
                                </div>

                            </div>
                        </form>
                        <!--</form>-->
                    </div>
                </div>
            </div>


        </div>
    </div>


    <!-- Footer -->
    <?php require('inc/footer.php'); ?>

    <!-- JS xử lý form thanh toán-->
    <script>
        let booking_form = document.getElementById('booking_form');
        let info_loader = document.getElementById('info_loader');
        let pay_info = document.getElementById('pay_info');

        // Hàm kiểm tra phòng trống khi ấn vào input checkin và checkout
        function check_availabitity() {
            // Lấy giá trị của trường checkin và checkout
            let checkin_value = booking_form.elements['checkin'].value;
            let checkout_value = booking_form.elements['checkout'].value;

            // Kiểm tra giá trị 
            if (checkin_value != '' && checkout_value != '') {
                pay_info.classList.add('d-none'); // Ẩn text
                pay_info.classList.replace('text-dark', 'text-danger'); // Đổi text đen -> đỏ
                info_loader.classList.remove('d-none'); // Xóa thuộc class d-none của spinner

                // Tạo đối tượng FormData đóng gói dữ liệu gửi lên sever xử lý
                let data = new FormData();
                data.append('check_availabitity', '');
                data.append('check_in', checkin_value);
                data.append('check_out', checkout_value);

                let xhr = new XMLHttpRequest();
                xhr.open("POST", "ajax/confirm_booking.php", true);

                xhr.onload = function () {
                    // Chuyển chuỗi json về đối tượng js
                    let data = JSON.parse(this.responseText);

                    // Định dạng tiền tệ VND
                    const formatCurrency = new Intl.NumberFormat('vi-VN', {
                        style: 'currency',
                        currency: 'VND'
                    });
                    let payment = formatCurrency.format(data.payment); // vd: Trả về "1.234.567 ₫"

                    // data là mảng js chứa status, days và payment từ sever trả về
                    if (data.status == 'check_in_out_equal') {
                        pay_info.innerText = "Bạn không thế trả phòng vào cùng ngày!";
                    } else if (data.status == 'check_out_earlier') {
                        pay_info.innerText = "Ngày trả phòng sớm hơn ngày nhận phòng!";
                    } else if (data.status == 'check_in_earlier') {
                        pay_info.innerText = "Ngày nhận phòng trễ hơn ngày hiện tại!";
                    } else if (data.status == 'unavailable') {
                        pay_info.innerText = "Không còn phòng trống vào ngày nhận phòng này!";
                    } else {
                        pay_info.innerHTML = "Số ngày: " + data.days + "<br>Tổng số tiền phải trả: " + payment;
                        pay_info.classList.replace('text-danger', 'text-dark'); // Chuyển text đỏ -> đen
                        booking_form.elements['pay_now'].removeAttribute(
                            'disabled'); // Xóa thuộc tính disable trong <button></button>   
                    }

                    // Sau khi nhận dược phản hồi từ sever thì ẩn spinner, và hiện text
                    info_loader.classList.add('d-none');
                    pay_info.classList.remove('d-none');
                }

                xhr.send(data);
            }
        }
    </script>
</body>

</html>