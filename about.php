<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Link Swiper's CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <title>NLam Hotel - ABOUT</title>
    <?php require('inc/links.php'); ?>
    <style>
    .h-line {
        width: 150px;
        margin: 0 auto;
        height: 1.7px;
    }

    .box {
        border-top-color: var(--teal) !important;
    }

    .box:hover {
        cursor: grab;
        transform: scale(1.03);
        transition: 0.3s
    }
    </style>
</head>

<body class="bg-light">
    <!-- Header (Thanh navbar, modal đăng kí, modal đăng nhập) -->
    <?php require('inc/header.php'); ?>

    <!-- Phần tiêu đề của trang about-->
    <div class="my-5 px-4">
        <h2 class="fw-bold h-font text-center">GIỚI THIỆU</h2>
        <div class="h-line bg-dark"></div>
        <p class="text-center mt-3">Chúng tôi là khách sạn mang đến trải nghiệm nghỉ dưỡng sang trọng, thoải mái và tiện
            nghi. <br>
            Cam kết mang đến dịch vụ tận tâm và không gian thư giãn cho mọi khách hàng.
        </p>
    </div>

    <!-- Phần chứa nội dung của trang about-->
    <div class="container">
        <div class="row justify-content-between align-items-center">
            <div class="col-lg-6 col-md-5 mb-4 order-lg-1 order-md-1 order-2">
                <h3 class="mb-3">Chất lượng dịch vụ hàng đầu</h3>
                <p>
                    Với hơn 10 năm kinh nghiệm, chúng tôi tự hào mang đến cho khách hàng những phòng nghỉ tiện nghi,
                    không gian sang trọng và dịch vụ chuyên nghiệp.
                    Đội ngũ nhân viên tận tâm luôn sẵn sàng hỗ trợ để bạn có một kỳ nghỉ trọn vẹn.
                </p>
            </div>

            <!-- Chèn ảnh -->
            <div class="col-lg-5 col-md-5 mb-4 order-lg-2 order-md-2 order-1">
                <img src="images/users/khachsannlam.jpg" class="img-fluid rounded">
            </div>
        </div>
    </div>

    <!-- Thống kê nổi bật của khách sạn -->
    <div class="container my-5">
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-4 px-2">
                <div class="bg-white rounded shadow p-4 border-top border-4 text-center box">
                    <img src="images/about/hotel.svg" width="70px">
                    <h4 class="mt-3">100+ PHÒNG</h4>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4 px-2">
                <div class="bg-white rounded shadow p-4 border-top border-4 text-center box">
                    <img src="images/about/customers.svg" width="70px">
                    <h4 class="mt-3">200+ KHÁCH</h4>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4 px-2">
                <div class="bg-white rounded shadow p-4 border-top border-4 text-center box">
                    <img src="images/about/rating.svg" width="70px">
                    <h4 class="mt-3">150+ ĐÁNH GIÁ </h4>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4 px-2">
                <div class="bg-white rounded shadow p-4 border-top border-4 text-center box">
                    <img src="images/about/staff.svg" width="70px">
                    <h4 class="mt-3">200+ NHÂN VIÊN</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Phần management team -->
    <h3 class="my-5 fw-bold h-font text-center">ĐỘI NGŨ QUẢN LÝ</h3>
    <div class="container px-4">
        <!-- Swiper -->
        <div class="swiper mySwiper">
            <div class="swiper-wrapper mb-5">
                <?php
                // Lấy all dữ liệu từ bảng team_details để lấy thông tin những người quản lý
                $about_r = selectAll('team_details');

                // Lấy đường dẫn thư mục hình ảnh người quản lý để hiển thị lên giao diện
                $path = ABOUT_IMG_PATH;
                while ($row = mysqli_fetch_assoc($about_r)) {
                    echo <<<data
                        <div class="swiper-slide bg-white text-center overflow-hidden rounded">
                            <img src="$path$row[picture]" class="w-100">
                            <h5 class="mt-2">$row[name]</h5>
                        </div>
                    data;
                }
                ?>
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </div>


    <!-- Footer -->
    <?php require('inc/footer.php'); ?>

    <!-- Phần Js -->
    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <!-- Initialize Swiper -->
    <script>
    var swiper = new Swiper(".mySwiper", {
        spaceBetween: 40,
        pagination: {
            el: ".swiper-pagination",
        },
        breakpoints: {
            320: {
                slidesPerView: 1,
            },
            640: {
                slidesPerView: 2,
            },
            768: {
                slidesPerView: 3,
            },
            1024: {
                slidesPerView: 4,
            },
        }
    });
    </script>
</body>


</body>

</html>