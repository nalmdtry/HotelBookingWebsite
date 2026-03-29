<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NLam Hotel - ROOMS</title>
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
    <?php
    require('inc/header.php');


    // Khởi tạo giá trị mặc định cho các trường checkin, checkout, adult và children
    $checkin_default = "";
    $checkout_default = "";
    $adult_default = "";
    $children_default = "";

    // Kiểm tra Nếu dữ liệu GET gửi lên có chứa check_availability thì thực hiện đoạn mã bên trong
    if (isset($_GET['check_availability'])) {
        // Lọc dữ liệu GET gửi lên
        $frm_data = filteration($_GET);

        // Gán các giá trị cho các trường tương ứng
        $checkin_default = $frm_data['checkin'];
        $checkout_default = $frm_data['checkout'];
        $adult_default = $frm_data['adult'];
        $children_default = $frm_data['children'];
    }
    ?>

    <div class="my-5 px-4">
        <h2 class="fw-bold h-font text-center">PHÒNG</h2>
        <div class="h-line bg-dark"></div>
    </div>

    <!-- Vùng chứa Rooms -->
    <div class="container-fluid">
        <div class="row">
            <!-- Cột 1 -->
            <div class="col-lg-3 col-md-12 mb-lg-0 mb-4 ps-4">
                <nav class="navbar navbar-expand-lg navbar-light bg-white rounded shadow">
                    <div class="container-fluid flex-lg-column align-items-stretch">
                        <h4 class="mt-2">BỘ LỌC</h4>
                        <button class="navbar-toggler shadow-none" type="button" data-bs-toggle="collapse"
                            data-bs-target="#filterDropdown" aria-controls="navbarNav" aria-expanded="false"
                            aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse flex-column align-items-stretch mt-2" id="filterDropdown">

                            <!-- Kiểm tra phòng trống khách sạn -->
                            <div class="border bg-light p-3 rounded mb-3">
                                <h5 class="d-flex align-items-center justify-content-between mb-3"
                                    style="font-size: 18px;">
                                    <span>KIỂM TRA PHÒNG TRỐNG</span>
                                    <button id="chk_avail_btn" onclick="chk_avail_clear()"
                                        class="btn btn-sm text-secondary shadow-none d-none">Đặt lại</button>
                                </h5>
                                <label class="form-label">Ngày nhận phòng</label>
                                <input type="date" id="checkin" value="<?php echo $checkin_default ?>"
                                    onchange="chk_avail_filter()" class="form-control shadow-none mb-3">
                                <label class="form-label">Ngày trả phòng</label>
                                <input type="date" id="checkout" value="<?php echo $checkout_default ?>"
                                    onchange="chk_avail_filter()" class="form-control shadow-none">
                            </div>

                            <!-- Phần tiện nghi (facility) khách sạn -->
                            <div class="border bg-light p-3 rounded mb-3">
                                <h5 class="d-flex align-items-center justify-content-between mb-3"
                                    style="font-size: 18px;">
                                    <span>TIỆN NGHI</span>
                                    <button id="facilities_btn" onclick="facilities_clear()"
                                        class="btn btn-sm text-secondary shadow-none d-none">Đặt lại</button>
                                </h5>

                                <!-- Lấy all facilites trong db để hiển thị lên giao diện -->
                                <?php
                                $facilities_q = selectAll('facilities');
                                while ($row = mysqli_fetch_assoc($facilities_q)) {
                                    echo <<<facilities
                                            <div class="mb-2">
                                                <input type="checkbox" onclick="fetch_rooms()" name="facilities" value="{$row['id']}" class="form-check-input shadow-none me-1" id="{$row['id']}">
                                                <label class="form-check-label" for="{$row['id']}">{$row['name']}</label>
                                            </div>
                                        facilities;
                                }
                                ?>
                            </div>

                            <!-- Phần khách -->
                            <div class="border bg-light p-3 rounded mb-3">
                                <h5 class="d-flex align-items-center justify-content-between mb-3"
                                    style="font-size: 18px;">
                                    <span>KHÁCH HÀNG</span>
                                    <button id="guests_btn" onclick="guests_clear()"
                                        class="btn btn-sm text-secondary shadow-none d-none">Đặt lại</button>
                                </h5>
                                <div class="d-flex">
                                    <div class="me-3">
                                        <label class="form-label">Người lớn</label>
                                        <input type="number" id="adults" value="<?php echo $adult_default ?>"
                                            oninput="guests_filter()" min="1" class="form-control shadow-none">
                                    </div>
                                    <div>
                                        <label class="form-label">Trẻ em</label>
                                        <input type="number" id="children" value="<?php echo $children_default ?>"
                                            oninput="guests_filter()" min="1" class="form-control shadow-none">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </nav>
            </div>

            <!-- Cột 2: Hiển thị thông tin từng phòng-->
            <div class="col-lg-9 col-md-12 px-4" id="rooms-data">
            </div>

        </div>
    </div>

    <script>
    let rooms_data = document.getElementById('rooms-data');
    // Phần check availabitiy
    let checkin = document.getElementById('checkin');
    let checkout = document.getElementById('checkout');
    let chk_avail_btn = document.getElementById('chk_avail_btn');

    // Phần khách
    let adults = document.getElementById('adults');
    let children = document.getElementById('children');
    let guests_btn = document.getElementById('guests_btn');

    // Phần tiện nghi
    let facilities_btn = document.getElementById('facilities_btn');

    // Hàm hiển thị phòng
    function fetch_rooms() {
        // Gói đối tượng checkin và checkout thành chuỗi JSON để gửi lên sever xử lý
        let chk_avail = JSON.stringify({
            checkin: checkin.value,
            checkout: checkout.value
        });

        // Gói đối tượng adults và children thành chuỗi JSON để gửi lên sever xử lý
        let guests = JSON.stringify({
            adults: adults.value,
            children: children.value
        });

        // Tạo đối tượng chứa danh sách các giá trị (mảng) của key facilities
        let facility_list = {
            "facilities": []
        };
        // Lấy all phần tử input có name facilites và đang được check
        let get_facilities = document.querySelectorAll('[name="facilities"]:checked');

        // Kiểm tra nếu độ dài của nodelist > 0
        if (get_facilities.length > 0) {
            // Lặp qua all phần tử 
            get_facilities.forEach((facility) => {
                // push từng value của facilites vào chuỗi facilities_list
                facility_list.facilities.push(facility.value);

            });
            // Hiển thị nút Reset
            facilities_btn.classList.remove('d-none');

        } else {
            // Ẩn nút Reset
            facilities_btn.classList.add('d-none');
        }
        // Chuyển đối tượng js sang chuỗi JSON để sever xử lý
        facility_list = JSON.stringify(facility_list);


        // Tạo request post gửi đến ajax xử lý
        let xhr = new XMLHttpRequest();
        xhr.open("GET", "ajax/rooms.php?fetch_rooms&chk_avail=" + chk_avail + "&guests=" + guests + "&facility_list=" +
            facility_list, true);

        xhr.onprogress = function() {
            rooms_data.innerHTML = `
                    <div class="spinner-border text-primary mb-3 d-block mx-auto" id="loader" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                `;
        }
        // Khi nhận dc phản hồi từ sever
        xhr.onload = function() {
            rooms_data.innerHTML = this.responseText;

        }

        xhr.send();
    }

    // Hàm lọc phòng theo checkin và checkout
    function chk_avail_filter() {
        // Nếu checkin và checkout không rỗng thì gọi hàm hiển thị phòng
        if (checkin.value != '' && checkout.value != '') {
            fetch_rooms();

            // Hiển thị nút Reset
            chk_avail_btn.classList.remove('d-none');

        }
    }

    // Hàm reset trường checkin và checkout khi ấn button reset
    function chk_avail_clear() {
        // Reset checkin và checkout
        checkin.value = '';
        checkout.value = '';

        // Ẩn nút Reset
        chk_avail_btn.classList.add('d-none');

        // Gọi hàm hiển thị phòng
        fetch_rooms();
    }



    // Hàm lọc phòng theo số lượng adults và children
    function guests_filter() {
        // Nếu adults hoặc children lớn hơn 0 thì gọi hàm hiển thị phòng
        if (adults.value > 0 || children.value > 0) {
            fetch_rooms();

            // Hiển thị nút Reset
            guests_btn.classList.remove('d-none');

        }
    }

    // Hàm reset trường checkin và checkout khi ấn button reset
    function guests_clear() {
        // Reset checkin và checkout
        adults.value = '';
        children.value = '';

        // Ẩn nút Reset
        guests_btn.classList.add('d-none');

        // Gọi hàm hiển thị phòng
        fetch_rooms();
    }

    // Hàm reset các ô checkbox của facilites
    function facilities_clear() {
        // Lấy all phần tử input có name facilites và đang được check
        let get_facilities = document.querySelectorAll('[name="facilities"]:checked');
        // Lặp qua all phần tử 
        get_facilities.forEach((facility) => {
            facility.checked = false; // Bỏ check
        });

        // Ẩn nút Reset
        facilities_btn.classList.add('d-none');

        // Gọi hàm hiển thị phòng
        fetch_rooms();
    }

    // Gọi hàm hiển thị phòng khi toàn bộ web được tải xong
    window.onload = function() {
        fetch_rooms();
    }
    </script>


    <!-- Footer -->
    <?php require('inc/footer.php'); ?>


</body>

</html>