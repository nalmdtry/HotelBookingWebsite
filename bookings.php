<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NLam Hotel - BOOKINGS</title>
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
    <?php require('inc/header.php');

    // Kiểm tra nếu user chưa login thì chuyển hướng về trang index.php
    if (!(isset($_SESSION['login']) && $_SESSION['login'] == true)) {
        redirect('index.php');
    }

    ?>


    <!-- Vùng chứa Rooms -->
    <div class="container">
        <div class="row">

            <!-- Phần tiêu đề -->
            <div class="col-12 my-5 px-4">
                <h2 class="fw-bold">PHÒNG ĐẶT</h2>
                <div style="font-size: 14px;">
                    <a href="index.php" class="text-secondary text-decoration-none">TRANG CHỦ</a>
                    <span class="text-secondary"> > </span>
                    <a href="#" class="text-secondary text-decoration-none">PHÒNG ĐẶT</a>
                </div>
            </div>

            <!-- Phần PHP -->
            <?php
            // Truy vấn lấy dữ liệu từ bảng booking_order, booking_details
            $query = "SELECT bo.*, bd.* FROM booking_order bo 
                INNER JOIN booking_details bd
                ON bo.booking_id = bd.booking_id 
                WHERE ((bo.booking_status = 'booked') OR (bo.booking_status = 'cancelled')
                    OR (bo.booking_status = 'payment failed')) 
                    AND (bo.user_id = ?)
                ORDER BY bo.booking_id DESC";
            $res = select($query, [$_SESSION['uId']], 'i');

            // Lặp qua mỗi hàng dữ liệu từ truy vấn $res
            while ($data = mysqli_fetch_assoc($res)) {
                // Định dạng lại date, checkin và checkout
                $date = date("d-m-Y", strtotime($data['datentime']));
                $checkin = date("d-m-Y", strtotime($data['check_in']));
                $checkout = date("d-m-Y", strtotime($data['check_out']));

                // Format giá phòng và tổng tiền phân cách hàng nghìn
                $price = number_format($data['price'], 0, ',', '.');
                $trans_amount = number_format($data['trans_amount'], 0, ',', '.');

                // Tạo button trạng thái để trả về trạng thái badge khác nhau tùy booking_status
                $status_bg = "";
                $btn = "";

                // Nếu booking_status = booked 
                if ($data['booking_status'] == 'booked') {
                    $status_bg = "bg-success";

                    // Nếu user đã đến nhận phòng
                    if ($data['arrival'] == 1) {
                        $btn = "
                            <a href='generate_pdf.php?gen_pdf&id={$data['booking_id']}' class='btn btn-dark btn-sm shadow-none'>
                                Tải xuống PDF
                            </a>                           
                                                 
                        ";

                        if ($data['rate_review'] == 0) {
                            $btn .= "
                                <button type='button' onclick='revew_room({$data['booking_id']}, {$data['room_id']})' class='btn btn-dark btn-sm shadow-none ms-2' data-bs-toggle='modal' data-bs-target='#reviewModal'>Xếp hạng & Đánh giá</button>
                            ";
                        }
                    } else {
                        $btn = "                           
                            <button type='button' onclick='cancel_booking({$data['booking_id']})' class='btn btn-danger btn-sm shadow-none'>Hủy đặt phòng</button>                     
                        ";
                    }
                    // Nếu booking_status = cancelled 
                } else if ($data['booking_status'] == 'cancelled') {
                    $status_bg = "bg-danger";
                    // Kiểm tra user được hoàn tiền chưa
                    if ($data['refund'] == 0) {
                        $btn = "<span class='badge bg-primary'>Đang xử lý hoàn tiền!</span>";
                    } else {
                        $btn = "                           
                            <a href='generate_pdf.php?gen_pdf&id={$data['booking_id']}' class='btn btn-dark btn-sm shadow-none'>
                                Tải xuống PDF
                            </a>                      
                        ";
                    }
                    // Nếu booking_status = payment failed 
                } else {
                    $status_bg = "bg-warning";
                    $btn = "                           
                            <a href='generate_pdf.php?gen_pdf&id={$data['booking_id']}' class='btn btn-dark btn-sm shadow-none'>
                                Tải xuống PDF
                            </a>                      
                        ";
                }


                // In khối heredoc
                echo <<<data
                    <div class="col-md-4 px-4 mb-4">
                        <div class="bg-white p-3 rounded shadow-sm">
                            <h5>{$data['room_name']}</h5>
                            <p>$price ₫/đêm </p>
                            <p>
                                <b>Ngày nhận phòng: </b> $checkin <br>
                                <b>Ngày trả phòng: </b> $checkout
                            </p>
                            <p>
                                <b>Tổng số tiền: </b> $trans_amount ₫ <br>
                                <b>Mã đơn hàng: </b> {$data['order_id']} <br>
                                <b>Ngày đặt phòng: </b> $date
                            </p>
                            <p>
                                <span class="badge $status_bg">{$data['booking_status']}</span>
                            </p>
                            $btn
                        </div>
                    </div>
                data;

            }
            ?>
        </div>
    </div>

    <!-- Modal Xếp hạng và đánh giá -->
    <div class="modal fade" id="reviewModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="review-form">
                    <div class="modal-header">
                        <h5 class="modal-title d-flex align-items-center">
                            <i class="bi bi-chat-square-heart-fill fs-3 me-2"></i>
                            Xếp hạng & Đánh giá
                        </h5>
                        <button type="reset" class="btn-close shadow-none" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Xếp hạng</label>
                            <select class="form-select shadow-none" name="rating">
                                <option value="5">Xuất sắc</option>
                                <option value="4">Tốt</option>
                                <option value="3">Trung bình</option>
                                <option value="2">Kém</option>
                                <option value="1">Tệ</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Đánh giá</label>
                            <textarea name="review" rows="3" class="form-control shadow-none" required></textarea>
                        </div>

                        <!-- 2 trường ẩn: booking_id và room_id -->
                        <input type="hidden" name="booking_id">
                        <input type="hidden" name="room_id">

                        <div class="text-end">
                            <button type="submit" class="btn custome-bg text-white shadow-none">GỬI</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <?php
    // Hiển thị alert thông báo nếu hủy đặt phòng thành công
    if (isset($_GET['cancel_status'])) {
        alert('success', 'Đơn đặt phòng đã bị hủy!');
        // Hiển thị alert thông báo nếu hủy đặt phòng thành công
    } else if (isset($_GET['review_status'])) {
        alert('success', 'Cảm ơn vì xếp hạng và đánh giá của bạn!');
    }
    ?>


    <!-- Footer -->
    <?php require('inc/footer.php'); ?>

    <script>
    // Hàm hủy đặt phòng
    function cancel_booking(id) {
        // Hiển thị hộp thoại xác nhận
        if (confirm("Bạn có chắc chắn muốn hủy đặt phòng này không?")) {
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "ajax/cancel_booking.php", true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            xhr.onload = function() {
                if (this.responseText == 1) {
                    window.location.href = "bookings.php?cancel_status=true";
                } else {
                    alert('error', 'Hủy đặt phòng không thành công!');
                }
            }

            xhr.send('cancel_booking&id=' + id);
        }
    }

    // Hàm Gán booking_id và room_id vào trường input hidden khi mở modal
    let review_form = document.getElementById('review-form');

    function revew_room(bid, rid) {
        review_form.elements['booking_id'].value = bid;
        review_form.elements['room_id'].value = rid;
    }

    // Khi ấn submit form thì hàm bên trong được thực hiện
    review_form.addEventListener('submit', function(e) {
        e.preventDefault();

        // Tạo đối tượng FormData
        let data = new FormData();
        data.append('rating', review_form.elements['rating'].value);
        data.append('review', review_form.elements['review'].value);
        data.append('booking_id', review_form.elements['booking_id'].value);
        data.append('room_id', review_form.elements['room_id'].value);
        data.append('review_form', '');


        // Tạo đối tượng XMLHttpRequest
        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/review_form.php", true);

        // Khi nhận được phản hồi từ sever
        xhr.onload = function() {
            if (this.responseText == 1) {
                window.location.href =
                    'bookings.php?review_status=true'; // Chuyển hướng về lại trang này kèm GET review_status để hiển thị alert và load lại trang          
            } else {
                var myModal = document.getElementById('reviewModal');
                var modal = bootstrap.Modal.getInstance(myModal);
                modal.hide();

                alert('error', 'Xếp hạng và đánh giá không thành công!');
            }
        }

        // Gửi HTTP POST đến sever xử lý
        xhr.send(data);
    });
    </script>

</body>

</html>