<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NLam Hotel - HOME</title>
    <?php require('inc/links.php'); ?>
    <style>
    .pop:hover {
        cursor: grab;
        border-top-color: var(--teal) !important;
        transform: scale(1.03);
        transition: all 0.3s;
    }

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

    <div class="my-5 px-4">
        <h2 class="fw-bold h-font text-center">TIỆN NGHI</h2>
        <div class="h-line bg-dark"></div>
        <p class="text-center mt-3">Khám phá những tiện nghi tuyệt vời mà khách sạn chúng tôi cung cấp để bạn có một kỳ
            nghỉ thoải mái và trọn vẹn. <br>
            Từ Wifi tốc độ cao, phòng tập hiện đại, đến dịch vụ phòng chu đáo – tất cả đều được chuẩn bị để bạn tận
            hưởng.
        </p>
    </div>

    <!-- Vùng chứa các tiện nghi -->
    <div class="container">
        <div class="row">
            <?php
            $res = selectAll('facilities');

            // Lấy đường dẫn thư mục icon facilities để hiển thị lên giao diện
            $path = FACILITIES_IMG_PATH;
            while ($row = mysqli_fetch_assoc($res)) {
                echo <<<data
                        <div class="col-lg-4 col-md-6 mb-5 px-4">
                            <div class="bg-white rounded shadow p-4 border-top border-4 border-dark pop">
                                <div class="d-flex align-items-center mb-2">
                                    <img src="$path{$row['icon']}" width="40px">
                                    <h5 class="m-0 ms-3">{$row['name']}</h5>
                                </div>
                                <p>{$row['description']}</p>
                            </div>
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