<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Link Swiper's CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <title>NLam Hotel - HOME</title>
    <?php require('inc/links.php'); ?>
    <style>
    input[type=number] {
        -moz-appearance: textfield;
    }

    .availability-form {
        margin-top: -50px;
        /*form chồng lên swipper*/
        z-index: 2;
        position: relative;
    }

    /* Hiển thị .availability-form trên màn hình m-width 575px*/
    @media screen and (max-width: 575px) {
        .availability-form {
            margin-top: 20px;
            padding: 0 35px;
            /*trên dưới: 0, phải trái 35px*/
        }
    }
    </style>
</head>

<body class="bg-light">
    <!-- Header (Thanh navbar, modal đăng kí, modal đăng nhập) -->
    <?php require('inc/header.php'); ?>

    <!-- Swiper Carousel -->
    <div class="container-fluid px-lg-4 mt-4">
        <div class="swiper swiper-container">
            <div class="swiper-wrapper">
                <?php
                $res = selectAll('carousel');

                while ($row = mysqli_fetch_assoc($res)) { // Duyệt từng bản ghi, trả về mảng kết hợp ['sr_no => 1, 'picture' => ...]
                    $path = CAROUSEL_IMG_PATH;

                    // Heredoc syntax: cho phép in HTML nhiều dòng dễ dàng.
                    echo <<<data
                        <div class="swiper-slide">
                            <img src="$path{$row['image']}" class="w-100 d-block" />
                        </div>
                    data;
                }
                ?>
            </div>
        </div>
    </div>

    <!-- Check availability form -->
    <div class="container availability-form">
        <div class="row">
            <div class="col-lg-12 bg-white shadow p-4 rounded">
                <h5 class="mb-3">Kiểm tra phòng trống</h5>
                <form action="rooms.php">
                    <div class="row align-items-end">
                        <div class="col-lg-3 mb-3">
                            <label class="form-label" style="font-weight: 500;">Ngày nhận phòng</label>
                            <input type="date" name="checkin" class="form-control shadow-none" required>
                        </div>
                        <div class="col-lg-3 mb-3">
                            <label class="form-label" style="font-weight: 500;">Ngày trả phòng</label>
                            <input type="date" name="checkout" class="form-control shadow-none" required>
                        </div>
                        <div class="col-lg-3 mb-3">
                            <label class="form-label" style="font-weight: 500;">Người lớn</label>
                            <select class="form-select shadow-none" name="adult">
                                <!-- Phần php -->
                                <?php
                                // Lấy số lượng max của adult và children của bảng rooms trong db để hiển thị lên giao diện
                                $guests_q = mysqli_query($conn, "SELECT MAX(adult) AS max_adult, MAX(children) AS max_children FROM rooms
                                        WHERE status = 1 AND removed = 0");
                                $guests_res = mysqli_fetch_assoc($guests_q);

                                // Lặp đến max_adult
                                for ($i = 1; $i <= $guests_res['max_adult']; $i++) {
                                    echo "<option value='$i'>$i</option>";
                                }
                                ?>

                            </select>
                        </div>
                        <div class="col-lg-2 mb-3">
                            <label class="form-label" style="font-weight: 500;">Trẻ em</label>
                            <select class="form-select shadow-none" name="children">
                                <?php
                                // Lặp đến max_children
                                for ($i = 1; $i <= $guests_res['max_children']; $i++) {
                                    echo "<option value='$i'>$i</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <!-- Input ẩn -->
                        <input type="hidden" name="check_availability">
                        <div class="col-lg-1 mb-lg-3 mt-2">
                            <button type="submit" class="btn text-white shadow-none custome-bg">Tìm</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Our Rooms-->
    <h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">CÁC PHÒNG CỦA CHÚNG TÔI</h2>
    <div class="container">
        <div class="row">
            <?php
            // Truy vấn thông tin phòng có trạng thái hoạt động + trạng thái removed = 0 (chưa xóa), sắp xếp giảm dần theo id và giới hạn hiển thị là 3 phòng
            $room_res = select("SELECT * FROM rooms WHERE status = ? AND removed = ? ORDER BY id DESC LIMIT 3", [1, 0], 'ii');

            while ($room_data = mysqli_fetch_assoc($room_res)) {
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
            
                // Lấy tên tiện nghi của phòng, join giữa facilities và room_facilities để lấy name của facilities cho room cụ thể
                $fac_q = mysqli_query($conn, "SELECT f.name FROM facilities f 
                    INNER JOIN room_facilities rfac ON f.id = rfac.facilities_id 
                    WHERE rfac.room_id = {$room_data['id']}");

                $facilities_data = "";
                while ($fac_row = mysqli_fetch_assoc($fac_q)) {
                    $facilities_data .= "<span class='badge rounded-pill bg-light text-dark text-wrap me-1 mb-1'>
                                    {$fac_row['name']}
                                </span>";
                }
                // echo $facilities_data;
            
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
                    $book_btn = "<button onclick='checkLoginToBook($login, {$room_data['id']})' class='btn btn-sm text-white custome-bg shadow-none'>Đặt phòng ngay</button>";
                }

                // Tính số rating trung bình cộng của từng loại phòng (20 xếp hạng mới nhất) để hiển thị lên giao diện
                $rating_q = "SELECT AVG(rating) AS avg_rating FROM rating_review WHERE room_id = {$room_data['id']}
                    ORDER BY sr_no DESC LIMIT 20";
                $rating_res = mysqli_query($conn, $rating_q);

                // Lấy 1 hàng kết quả từ truy vấn dưới dạng mảng kết hợp
                $rating_fetch = mysqli_fetch_assoc($rating_res);

                // Tạo biến chứa số sao (số rating)
                $rating_data = "";      // Mặc định số sao = nếu không có rating nào của loại phòng đó
            
                // Kiểm tra nếu rating ko rỗng (có rating của loại phòng đó) thì in số sao tùy số rating
                if ($rating_fetch['avg_rating'] != NULL) {
                    $rating_data = "<div class='rating mb-4'>
                        <h6 class='mb-1'>Đánh giá</h6>        
                        <span class='badge rounded-pill bg-light'>
                    ";

                    // Lặp qua mỗi rating trung bình cộng để tạo số sao tương ứng
                    for ($i = 0; $i < $rating_fetch['avg_rating']; $i++) {
                        $rating_data .= " <i class='bi bi-star-fill text-warning'></i>";
                    }

                    // Đóng thẻ span và div
                    $rating_data .= "</span>
                        </div>
                    ";
                }

                // In thẻ phòng
                echo <<<data
                    <div class="col-lg-4 col-md-6 my-3">
                        <div class="card border-0 shadow" style="max-width: 350px; margin: auto;">
                            <img src="$room_thumb" class="card-img-top">

                            <!-- Phần thân -->
                            <div class="card-body">
                                <h5>{$room_data['name']}</h5>
                                <h6 class="mb-4">$price ₫/đêm</h6>

                                <!-- Phần hiện các tính năng của phòng -->
                                <div class="features mb-4">
                                    <h6 class="mb-1">Tính năng</h6>
                                    $features_data
                                </div>

                                <!-- Phần hiện các tiện nghi của phòng -->
                                <div class="facilities mb-4">
                                    <h6 class="mb-1">Tiện nghi</h6>
                                    $facilities_data
                                </div>

                                <!-- Phần hiện sức chứa -->
                                <div class="guests mb-4">
                                    <h6 class="mb-1">Khách</h6>
                                    <span class="badge rounded-pill bg-light text-dark text-wrap">
                                        {$room_data['adult']} Người lớn
                                    </span>
                                    <span class="badge rounded-pill bg-light text-dark text-wrap">
                                        {$room_data['children']} Trẻ em
                                    </span>
                                </div>

                                <!-- Phần hiện đánh giá của phòng -->
                                $rating_data
                                
                                <!-- Nút hành động -->
                                <div class="d-flex justify-content-evenly mb-2">
                                    $book_btn
                                    <a href="room_details.php?id={$room_data['id']}" class="btn btn-sm btn-outline-dark shadow-none">Xem chi tiết</a>
                                    </div>
                            </div>
                        </div>
                    </div>
                data;


            }
            ?>

            <!-- Nút xem thêm phòng -->
            <div class="col-lg-12 text-center mt-5">
                <a href="rooms.php" class="btn btn-sm btn-outline-dark rounded-0 fw-bold shadow-none">Xem thêm phòng
                    >>></a>
            </div>
        </div>
    </div>

    <!-- Our Facilities -->
    <h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">CÁC TIỆN NGHI CỦA CHÚNG TÔI</h2>
    <div class="container">
        <div class="row justify-content-evenly px-lg-0 px-md-0 px-5">
            <?php
            $res = mysqli_query($conn, "SELECT * FROM facilities ORDER BY id DESC LIMIT 5");

            // Lấy đường dẫn thư mục icon facilities để hiển thị lên giao diện
            $path = FACILITIES_IMG_PATH;

            while ($row = mysqli_fetch_assoc($res)) {
                echo <<<data
                        <div class="col-lg-2 col-md-2 text-center bg-white rounded shadow py-4 my-3">
                            <img src="$path{$row['icon']}" width="50px">
                            <h5 class="mt-3">{$row['name']}</h5>
                        </div>
                    data;
            }
            ?>

            <!-- Nút xem thêm tiện nghi -->
            <div class="col-lg-12 text-center mt-5">
                <a href="facilities.php" class="btn btn-sm btn-outline-dark rounded-0 fw-bold shadow-none">Xem thêm tiện
                    nghi
                    >>></a>
            </div>
        </div>
    </div>

    <!-- Testimonials -->
    <h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">ĐÁNH GIÁ TỪ KHÁCH HÀNG</h2>
    <div class="container mt-5">
        <!-- Swiper -->
        <div class="swiper swiper-testimonials">
            <div class="swiper-wrapper mb-5">

                <?php
                // Truy vấn lấy all dữ liệu từ bảng rating_review, tên phòng trong bảng rooms và tên user và ảnh user trong bảng user_cred
                $review_q = "SELECT rr.*, uc.name AS uname, uc.profile AS uprofile, r.name AS rname FROM rating_review rr
                    INNER JOIN user_cred uc ON rr.user_id = uc.id
                    INNER JOIN rooms r ON rr.room_id = r.id
                    ORDER BY sr_no DESC LIMIT 6";

                $review_res = mysqli_query($conn, $review_q);

                // Lấy đường dẫn thư mục hình ảnh user để hiển thị lên giao diện
                $img_path = USERS_IMG_PATH;

                // Kiểm tra số hàng trả về từ truy vấn
                if (mysqli_num_rows($review_res) == 0) {
                    echo 'Chưa có đánh giá nào!';
                } else {
                    // Lặp qua mỗi hàng dữ liệu trả về
                    while ($row = mysqli_fetch_assoc($review_res)) {
                        // Tạo số sao rating
                        $stars = "<i class='bi bi-star-fill text-warning'></i>";
                        // Vòng lặp for qua mỗi rating (1-5) trong db trả về số sao tương ứng 
                        for ($i = 1; $i < $row['rating']; $i++) {
                            $stars .= " <i class='bi bi-star-fill text-warning'></i>";
                        }

                        // In khối heredoc
                        echo <<<slides
                            <div class="swiper-slide bg-white p-4">
                                <div class="profile d-flex align-items-center mb-3">
                                    <img src="$img_path{$row['uprofile']}" class="rounded-circle" loading="lazy" width="30px" />
                                    <h6 class="m-0 ms-2">{$row['uname']}</h6>
                                </div>
                                <p>{$row['review']}</p>
                                <div class="rating">
                                    $stars
                                </div>
                            </div>
                        slides;
                    }
                }
                ?>
            </div>
            <div class="swiper-pagination"></div>
        </div>

        <div class="col-lg-12 text-center mt-5">
            <a href="about.php" class="btn btn-sm btn-outline-dark rounded-0 fw-bold shadow-none">Xem thêm đánh giá
                >>></a>
        </div>
    </div>

    <!-- Reach us -->
    <h2 class="mt-5 pt-4 mb-4 text-center fw-bold h-font">LIÊN HỆ VỚI CHÚNG TÔI</h2>
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-8 p-4 mb-lg-0 mb-3 bg-white rounded">
                <iframe class="w-100 rounded mb-4" height="320px" src="<?php echo $contact_r['iframe'] ?>"
                    referrerpolicy="no-referrer-when-downgrade" loading="lazy"></iframe>
            </div>
            <div class="col-lg-4 col-md-4">
                <!-- Khối chứa thông tin liên hệ (sđt) -->
                <div class="bg-white p-4 rounded mb-4">
                    <h5>Liên hệ</h5>
                    <a href="tel:+<?php echo $contact_r['pn1'] ?>"
                        class="d-inline-block mb-2 text-decoration-none text-dark">
                        <i class="bi bi-telephone-fill"></i> +<?php echo $contact_r['pn1'] ?> <br>
                    </a>
                    <br>
                    <?php
                    if ($contact_r['pn2'] != '') {
                        echo <<<data
                                <a href="tel:+$contact_r[pn2]" class="d-inline-block mb-2 text-decoration-none text-dark">
                                    <i class="bi bi-telephone-fill"></i> +$contact_r[pn2]     
                                </a>
                            data;
                    }
                    ?>
                </div>

                <!-- Khối chứa thông tin kết nối (MXH) -->
                <div class="bg-white p-4 rounded mb-4">
                    <h5>Kết nối với chúng tôi</h5>
                    <a href="<?php echo $contact_r['fb'] ?>" class="d-inline-block mb-3" target="_blank">
                        <span class="badge bg-light text-dark fs-6 p-2">
                            <i class="bi bi-facebook me-1"></i> Facebook
                        </span>
                    </a>
                    <br>

                    <a href="<?php echo $contact_r['insta'] ?>" class="d-inline-block mb-3" target="_blank">
                        <span class="badge bg-light text-dark fs-6 p-2">
                            <i class="bi bi-instagram me-1"></i> Instagram
                        </span>
                    </a>
                    <br>

                    <?php
                    if ($contact_r['tw'] != '') {
                        echo <<<data
                            <a href="$contact_r[tw]" class="d-inline-block" target="_blank">
                                <span class="badge bg-light text-dark fs-6 p-2">
                                    <i class="bi bi-twitter me-1"></i> Twitter
                                </span>
                            </a>
                        data;
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal đặt lại mật khẩu-->
    <div class="modal fade" id="recoveryModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Form xử lý đặt lại mật khẩu mới nếu-người dùng đã ấn vào link email -->
                <form id="recovery_form">
                    <div class="modal-header">
                        <h5 class="modal-title d-flex align-items-center">
                            <i class="bi bi-shield-lock fs-3 me-2"></i>
                            Thiết lập mật khẩu mới
                        </h5>
                    </div>
                    <div class="modal-body">
                        <div class="mb-4">
                            <label class="form-label">Mật khẩu mới</label>
                            <input type="password" name="pass" required class="form-control shadow-none">
                            <input type="hidden" name="email">
                            <input type="hidden" name="token">

                        </div>
                        <div class="mb-2 text-end">
                            <button type="button" class="btn shadow-none me-2" data-bs-dismiss="modal">ĐÓNG</button>
                            <button type="submit" class="btn btn-dark shadow-none">OK</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Footer -->
    <?php require('inc/footer.php'); ?>


    <?php
    // Kiểm tra người dùng ấn vào liên kết đặt lại mật khẩu chưa, nếu có thì mở modal đặt lại mk
    if (isset($_GET['account_recovery'])) {
        // Lọc dữ liệu đầu vào
        $data = filteration($_GET); // gồm email và token
    
        // Lấy ngày hiện tại
        $t_date = date("Y-m-d");

        // Truy vấn dữ liệu từ db
        $q = "SELECT * FROM user_cred WHERE email = ? AND token = ? AND t_expire = ? LIMIT 1";
        $values = [$data['email'], $data['token'], $t_date];
        $datatypes = 'sss';

        $res = select($q, $values, $datatypes); // $res là mysqli_result
    
        // Kiểm tra kết quả trả về từ truy vấn
        if (mysqli_num_rows($res) == 1) {
            // In khối Heredoc hiện modal đặt lại mật khẩu
            echo <<<showModal
                <script>
                    var myModal = document.getElementById('recoveryModal');
                    
                    myModal.querySelector("input[name='email']").value = '{$data['email']}';
                    myModal.querySelector("input[name='token']").value = '{$data['token']}';
                    
                    var modal = bootstrap.Modal.getOrCreateInstance(myModal);
                    modal.show();
                </script>
            showModal;
        } else {
            alert('error', 'Liên kết không hợp lệ hoặc đã hết hạn!');
        }
    }
    ?>

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <!-- Initialize Swiper -->
    <script>
    // Phần js xử lý đặt lại mật khẩu
    let recovery_form = document.getElementById('recovery_form');
    recovery_form.addEventListener('submit', function(e) {
        e.preventDefault();
        recover_user();
    });

    function recover_user() {
        let data = new FormData();
        data.append('email', recovery_form.elements['email'].value);
        data.append('token', recovery_form.elements['token'].value);
        data.append('pass', recovery_form.elements['pass'].value);
        data.append('recover_user', '');

        var myModal = document.getElementById('recoveryModal');
        var modal = bootstrap.Modal.getOrCreateInstance(myModal);

        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/login_register.php", true);

        xhr.onload = function() {
            if (this.responseText == 'failed') {
                alert('error', 'Đặt lại mật khẩu không thành công!');
            } else {
                alert('success', 'Đặt lại mật khẩu thành công!');
                recovery_form.reset();
            }
            modal.hide();
        }

        xhr.send(data);
    }



    // Swiper của Carousel
    var swiper = new Swiper(".swiper-container", {
        spaceBetween: 30,
        effect: "fade",
        loop: true,
        autoplay: {
            delay: 3500,
            disableOnInteraction: false,
        }
    });

    // Swiper của Testimonials
    var swiper = new Swiper(".swiper-testimonials", {
        effect: "coverflow",
        grabCursor: true,
        centeredSlides: true,
        slidesPerView: "auto",
        slidesPerView: 3, // Số slide hiển thị cùng lúc
        coverflowEffect: {
            rotate: 50,
            stretch: 0,
            depth: 100,
            modifier: 1,
            slideShadows: false,
        },
        pagination: {
            el: ".swiper-pagination",
        },
        breakpoints: {
            320: {
                slidesPerView: 1,
            },
            640: {
                slidesPerView: 1,
            },
            768: {
                slidesPerView: 2,
            },
            1024: {
                slidesPerView: 3,
            },
        }
    });
    </script>




</body>

</html>