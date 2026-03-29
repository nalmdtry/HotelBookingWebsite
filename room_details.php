<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NLam Hotel - ROOM DETAILS</title>
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

    <?php
    if (!isset($_GET['id'])) {      // Lấy tham số id từ URL
        redirect('rooms.php');
    }

    // Lọc dữ liệu đầu vào
    $data = filteration($_GET);

    // Truy vấn thông tin phòng
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
    ?>

    <!-- Vùng chứa Rooms -->
    <div class="container">
        <div class="row">

            <!-- Phần tiêu đề -->
            <div class="col-12 my-5 mb-4 px-4">
                <h2 class="fw-bold"><?php echo $room_data['name'] ?></h2>
                <div style="font-size: 14px;">
                    <a href="index.php" class="text-secondary text-decoration-none">Trang chủ</a>
                    <span class="text-secondary"> > </span>
                    <a href="rooms.php" class="text-secondary text-decoration-none">Phòng</a>
                </div>
            </div>

            <!-- Phần hiển thị carousel ảnh phòng (cột trái) -->
            <div class="col-lg-7 col-md-12 px-4">
                <div id="roomCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">

                        <!-- PHP -->
                        <?php
                        $path = ROOMS_IMG_PATH;     // Đường dẫn tới thư mục ảnh phòng
                        $room_img = $path . "thumbnail.jpg";
                        $img_q = mysqli_query($conn, "SELECT * FROM room_images
                            WHERE room_id = {$room_data['id']}");

                        // Kiểm tra số hàng trả về từ truy vấn
                        if (mysqli_num_rows($img_q) > 0) {
                            $active_class = 'active';

                            while ($img_res = mysqli_fetch_assoc($img_q)) {     // Lặp từng ảnh
                                echo <<<data
                                    <div class="carousel-item $active_class">
                                        <img src="$path{$img_res['image']}" class="d-block w-100 rounded">
                                    </div>
                                data;
                                $active_class = '';
                            }
                        } else {
                            echo <<<data
                                    <div class="carousel-item active">
                                        <img src="$room_img" class="d-block w-100">
                                    </div>
                                data;
                        }
                        ?>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#roomCarousel"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#roomCarousel"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>

            <!-- Phần hiển thị chi tiết phòng (cột phải)-->
            <div class="col-lg-5 col-md-12 px-4">
                <div class="card mb-4 border-0 shadow-sm rounded-3">
                    <div class="card-body">
                        <?php
                        // echo giá
                        $price = number_format($room_data['price'], 0, ',', '.');   // Format giá phân cách hàng nghìn
                        echo <<<price
                            <h4>$price ₫/đêm</h4>
                        price;


                        // Tính số rating trung bình cộng của từng loại phòng (20 xếp hạng mới nhất) để hiển thị lên giao diện
                        $rating_q = "SELECT AVG(rating) AS avg_rating FROM rating_review WHERE room_id = {$room_data['id']}
                            ORDER BY sr_no DESC LIMIT 20";
                        $rating_res = mysqli_query($conn, $rating_q);

                        // Lấy 1 hàng kết quả từ truy vấn dưới dạng mảng kết hợp
                        $rating_fetch = mysqli_fetch_assoc($rating_res);

                        // Tạo biến chứa số sao (số rating)
                        $rating_data = "";      // Mặc định số sao = nếu không có rating nào của loại phòng đó
                        
                        if ($rating_fetch['avg_rating'] != NULL) {
                            // Lặp qua mỗi rating trung bình cộng để tạo số sao tương ứng
                            for ($i = 0; $i < $rating_fetch['avg_rating']; $i++) {
                                $rating_data .= " <i class='bi bi-star-fill text-warning'></i>";
                            }
                        }

                        // echo đánh giá
                        echo <<<rating
                            <div class="mb-3">
                                $rating_data
                            </div>
                        rating;

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

                        // In block chứa tiêu đề Tính năng và danh sách badge đã build
                        echo <<<features
                            <div class="mb-3">
                                <h6 class="mb-1">Tính năng</h6>
                                $features_data 
                            </div>
                        features;

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

                        // In block chứa tiêu đề Tiện nghi và danh sách badge đã build
                        echo <<<facilities
                            <div class="mb-3">
                                <h6 class="mb-1">Tiện nghi</h6>
                                $facilities_data
                            </div>
                        facilities;

                        // echo khách
                        echo <<<guest
                            <div class="mb-3">
                                <h6 class="mb-1">Khách</h6>
                                    <span class="badge rounded-pill bg-light text-dark text-wrap">
                                        {$room_data['adult']} Người lớn
                                    </span>
                                    <span class="badge rounded-pill bg-light text-dark text-wrap">
                                        {$room_data['children']} Trẻ em
                                    </span>
                            </div>
                        guest;

                        // echo diện tích phòng
                        echo <<<area
                            <div class="mb-3">
                                <h6 class="mb-1">Diện tích</h6>
                                <span class='badge rounded-pill bg-light text-dark text-wrap me-1 mb-1'>
                                     {$room_data['area']} m<sup>2</sup>
                                 </span>                            
                            </div>
                        area;

                        // Nếu trạng thái shutdown = 1 thì đóng trang, ko hiện đặt phòng 
                        if (!$settings_r['shutdown'] == 1) {
                            // Kiểm tra người dùng login chưa
                            $login = 0;
                            if (isset($_SESSION['login']) && $_SESSION['login'] == true) {
                                $login = 1;
                            }
                            // echo nút đặt phòng ngay
                            echo <<<booknow
                                <button onclick='checkLoginToBook($login, {$room_data['id']})' class="btn w-100 text-white custome-bg shadow-none mb-1">Đặt phòng ngay</button>
                            booknow;
                        }
                        ?>
                    </div>
                </div>
            </div>

            <!-- Phần hiển thị mô tả và đánh giá phòng -->
            <div class="col-12 mt-4 px-4">
                <!-- Phần hiển thị mô tả -->
                <div class="mb-5">
                    <h5>Mô tả</h5>
                    <p>
                        <?php echo $room_data['description'] ?>
                    </p>
                </div>

                <!-- Phần hiển thị xếp hạng và đánh giá phòng -->
                <div>
                    <h5 class="mb-3">Xếp hạng và đánh giá</h5>

                    <?php
                    // Truy vấn lấy all dữ liệu từ bảng rating_review, tên phòng trong bảng rooms và tên user trong bảng user_cred
                    $review_q = "SELECT rr.*, uc.name AS uname, uc.profile AS uprofile, r.name AS rname FROM rating_review rr
                    INNER JOIN user_cred uc ON rr.user_id = uc.id
                    INNER JOIN rooms r ON rr.room_id = r.id
                    WHERE rr.room_id = {$room_data['id']} 
                    ORDER BY sr_no DESC LIMIT 15";

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
                            echo <<<reviews
                                <div class="mb-4">
                                    <div class="d-flex align-items-center mb-2">
                                        <img src="$img_path{$row['uprofile']}" class="rounded-circle" loading="lazy" width="30px" />
                                        <h6 class="m-0 ms-2">{$row['uname']}</h6>
                                    </div>
                                    <p class="mb-1">
                                        {$row['review']}
                                    </p>
                                    <div>
                                        $stars
                                    </div>
                                </div>
                            reviews;
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>


    <!-- Footer -->
    <?php require('inc/footer.php'); ?>


</body>

</html>