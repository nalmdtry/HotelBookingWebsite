<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NLam Hotel - BOOKING STATUS</title>
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

    <!-- Vùng chứa Rooms -->
    <div class="container">
        <div class="row">

            <!-- Phần tiêu đề -->
            <div class="col-12 my-5 mb-3 px-4">
                <h2 class="fw-bold">TRẠNG THÁI THANH TOÁN</h2>
            </div>

            <!-- Phần php xử lý hiển thị trạng thái -->
            <?php
            // Lọc dữ liệu GET gửi lên
            $frm_data = filteration($_GET);    // $_GET chứa order_id
            
            // Nếu GET gửi lên không có key order thì redirect về trang index
            if (!isset($frm_data['order'])) {
                redirect('index.php');
            }

            // Nếu user chưa đăng nhập thì redirect về trang index.php
            if (!(isset($_SESSION['login']) && $_SESSION['login'] == true)) {
                redirect('index.php');
            }

            // Lấy dữ liệu từ bảng booking_order và booking_details
            $booking_q = "SELECT bo.*, bd.* FROM booking_order bo INNER JOIN booking_details bd
                    ON bo.booking_id = bd.booking_id WHERE bo.order_id = ? AND bo.user_id = ? LIMIT 1";
            $values = [$frm_data['order'], $_SESSION['uId']];
            $datatypes = 'si';

            $booking_res = select($booking_q, $values, $datatypes);

            // Kiểm tra số hàng trả về
            if (mysqli_num_rows($booking_res) == 0) {
                redirect('index.php');
            }

            // Lấy 1 hàng kết quả từ truy vấn dưới dạng mảng kết hợp
            $booking_fetch = mysqli_fetch_assoc($booking_res);


            // Kiểm tra trạng thái giao dịch, echo heredoc dạng alert tùy trạng thái giao dịch (thành công, thất bại)
            if ($booking_fetch['trans_status'] == 'success') {
                echo <<<data
                    <div class="col-12 px-4">
                        <p class="fw-bold alert alert-success">
                            <i class="bi bi-check-circle-fill"></i>
                            Đã thanh toán xong! Đặt chỗ thành công.
                            <br></br>
                            <a href="bookings.php">Đi đến Đặt chỗ</a>
                        </p>                    
                    </div>
                data;
            } else {
                echo <<<data
                    <div class="col-12 px-4">
                        <p class="fw-bold alert alert-danger">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            Thanh toán không thành công! {$booking_fetch['trans_message']}
                            <br></br>
                            <a href="bookings.php">Đi đến Đặt chỗ</a>
                        </p>                    
                    </div>
                data;
            }
            ?>
        </div>
    </div>


    <!-- Footer -->
    <?php require('inc/footer.php'); ?>
</body>

</html>