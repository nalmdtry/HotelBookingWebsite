<?php
require('inc/essentials.php');
require('inc/db.config.php');
adminLogin();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Dashboard</title>
    <?php require('inc/links.php'); ?>
    <style>
        #dashboard-menu {
            position: fixed;
            height: 100%;
            z-index: 11;
        }

        @media screen and (max-width: 991px) {
            #dashboard-menu {
                height: auto;
                width: 100%;
            }

            #main-content {
                margin-top: 60px;
            }
        }
    </style>
</head>

<body class="bg-light">

    <?php
    require('inc/header.php');

    // Truy vấn lấy dữ liệu shutdown từ db 
    $is_shutdown = mysqli_fetch_assoc(mysqli_query($conn, "SELECT shutdown FROM settings"));

    // Truy vấn đếm số lượng phòng mới đặt (arrival = 0) và số lượng đơn đã hủy nhưng chưa được hoàn tiền từ bảng booking_order
    $current_bookings = mysqli_fetch_assoc(mysqli_query($conn, "SELECT
        COUNT(CASE WHEN booking_status = 'booked' AND arrival = 0 THEN 1 END) AS new_bookings,
        COUNT(CASE WHEN booking_status = 'cancelled' AND refund = 0 THEN 1 END) AS refund_bookings
        FROM booking_order"));

    // Truy vấn đếm số lượng phản hồi người dùng chưa đọc (seen=0)
    $unread_queries = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(sr_no) AS count
        FROM user_queries WHERE seen = 0"));

    // Truy vấn đếm số lượng đánh giá từ người dùng chưa đọc (seen=0)
    $unread_reviews = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(sr_no) AS count
        FROM rating_review WHERE seen = 0"));

    // Truy vấn đếm số lượng user đang tồn tại, status = 0 (tk chưa kích hoạt), status = 1, trạng thái xác minh = 0 từ bảng user_cred
    $current_users = mysqli_fetch_assoc(mysqli_query($conn, "SELECT
        COUNT(id) AS total,
        COUNT(CASE WHEN status = 1 THEN 1 END) AS active,
        COUNT(CASE WHEN status = 0 THEN 1 END) AS inactive,
        COUNT(CASE WHEN is_verified = 0 THEN 1 END) AS unverified
        FROM user_cred"));
    ?>

    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden">

                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h3>TỔNG QUAN</h3>
                    <?php
                    // Kiểm tra nếu trạng thái shutdown = 1
                    if ($is_shutdown['shutdown'] == 1) {
                        echo <<<data
                                <h6 class="badge bg-danger py-2 px-3 rounded">Chế độ shutdown đang hoạt động!</h6>
                            data;
                    }
                    ?>

                </div>

                <div class="row mb-4">
                    <!-- Phần hiện new bookings -->
                    <div class="col-md-3 mb-4">
                        <a href="new_bookings.php" class="text-decoration-none">
                            <div class="card text-center text-success p-3">
                                <h6>Đơn đặt phòng mới</h6>
                                <h1 class="mt-2 mb-0"><?php echo $current_bookings['new_bookings']; ?></h1>
                            </div>
                        </a>
                    </div>
                    <!-- Phần hiện refund bookings -->
                    <div class="col-md-3 mb-4">
                        <a href="refund_bookings.php" class="text-decoration-none">
                            <div class="card text-center text-warning p-3">
                                <h6>Hoàn tiền đặt phòng</h6>
                                <h1 class="mt-2 mb-0"><?php echo $current_bookings['refund_bookings']; ?></h1>
                            </div>
                        </a>
                    </div>
                    <!-- Phần hiện user queries -->
                    <div class="col-md-3 mb-4">
                        <a href="user_queries.php" class="text-decoration-none">
                            <div class="card text-center text-info p-3">
                                <h6>Phản hồi từ khách hàng</h6>
                                <h1 class="mt-2 mb-0"><?php echo $unread_queries['count'] ?></h1>
                            </div>
                        </a>
                    </div>
                    <!-- Phần hiện rating & review -->
                    <div class="col-md-3 mb-4">
                        <a href="rate_review.php" class="text-decoration-none">
                            <div class="card text-center text-info p-3">
                                <h6>Xếp hạng & Đánh giá</h6>
                                <h1 class="mt-2 mb-0"><?php echo $unread_reviews['count'] ?></h1>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Phần booking analytics -->
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h5>Phân tích đặt phòng</h5>
                    <select class="form-select shadow-none bg-light w-auto" onchange="booking_analytics(this.value)">
                        <option value="1">30 ngày qua</option>
                        <option value="2">90 ngày qua</option>
                        <option value="3">1 năm qua</option>
                        <option value="4">Mọi lúc</option>
                    </select>
                </div>

                <div class="row mb-3">
                    <!-- Phần hiện Total Bookings -->
                    <div class="col-md-3 mb-4">
                        <div class="card text-center text-primary p-3">
                            <h6>Tổng số đặt phòng</h6>
                            <h1 class="mt-2 mb-0" id="total_bookings">5</h1>
                            <h4 class="mt-2 mb-0" id="total_amount">0₫</h4>
                        </div>
                    </div>
                    <!-- Phần hiện Active Bookings -->
                    <div class="col-md-3 mb-4">
                        <div class="card text-center text-success p-3">
                            <h6>Đơn đang hoạt động</h6>
                            <h1 class="mt-2 mb-0" id="active_bookings">5</h1>
                            <h4 class="mt-2 mb-0" id="active_amount">0₫</h4>
                        </div>
                    </div>
                    <!-- Phần hiện Cancelled Bookings -->
                    <div class="col-md-3 mb-4">
                        <div class="card text-center text-danger p-3">
                            <h6>Đơn đã hủy</h6>
                            <h1 class="mt-2 mb-0" id="cancelled_bookings">5</h1>
                            <h4 class="mt-2 mb-0" id="cancelled_amount">0₫</h4>
                        </div>
                    </div>

                </div>

                <!-- Phần User, Queries, Review Analytics -->
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h5>Phân tích Người dùng - Phản hồi - Đánh giá</h5>
                    <select class="form-select shadow-none bg-light w-auto" onchange="user_analytics(this.value)">
                        <option value="1">30 ngày qua</option>
                        <option value="2">90 ngày qua</option>
                        <option value="3">1 năm qua</option>
                        <option value="4">Mọi lúc</option>
                    </select>
                </div>

                <div class="row mb-3">
                    <!-- Phần hiện Total Bookings -->
                    <div class="col-md-3 mb-4">
                        <div class="card text-center text-success p-3">
                            <h6>Đăng ký mới</h6>
                            <h1 class="mt-2 mb-0" id="total_new_reg">0</h1>
                        </div>
                    </div>
                    <!-- Phần hiện Active Bookings -->
                    <div class="col-md-3 mb-4">
                        <div class="card text-center text-primary p-3">
                            <h6>Phản hồi</h6>
                            <h1 class="mt-2 mb-0" id="total_queries">0</h1>
                        </div>
                    </div>
                    <!-- Phần hiện Cancelled Bookings -->
                    <div class="col-md-3 mb-4">
                        <div class="card text-center text-primary p-3">
                            <h6>Đánh giá</h6>
                            <h1 class="mt-2 mb-0" id="total_reviews">0</h1>
                        </div>
                    </div>

                </div>


                <!-- Phần phân tích user -->
                <h5>Người dùng</h5>
                <div class="row mb-3">
                    <!-- Phần hiện Total Users -->
                    <div class="col-md-3 mb-4">
                        <div class="card text-center text-info p-3">
                            <h6>Tổng số</h6>
                            <h1 class="mt-2 mb-0"><?php echo $current_users['total']; ?></h1>
                        </div>
                    </div>
                    <!-- Phần hiện Active User -->
                    <div class="col-md-3 mb-4">
                        <div class="card text-center text-success p-3">
                            <h6>Đang hoạt động</h6>
                            <h1 class="mt-2 mb-0"><?php echo $current_users['active']; ?></h1>
                        </div>
                    </div>
                    <!-- Phần hiện Inactive User -->
                    <div class="col-md-3 mb-4">
                        <div class="card text-center text-warning p-3">
                            <h6>Ngưng hoạt động</h6>
                            <h1 class="mt-2 mb-0"><?php echo $current_users['inactive']; ?></h1>
                        </div>
                    </div>
                    <!-- Phần hiện Unverified User -->
                    <div class="col-md-3 mb-4">
                        <div class="card text-center text-danger p-3">
                            <h6>Chưa xác minh</h6>
                            <h1 class="mt-2 mb-0"><?php echo $current_users['unverified']; ?></h1>
                        </div>
                    </div>

                </div>


            </div>
        </div>
    </div>

    <?php require('inc/scripts.php'); ?>

    <script src="scripts/dashboard.js"></script>
</body>

</html>