<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NLam Hotel - CONTACT</title>
    <?php require('inc/links.php'); ?>
    <style>
    .custome-alert {
        position: fixed;
        top: 80px;
        right: 25px;
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
        <h2 class="fw-bold h-font text-center">LIÊN HỆ</h2>
        <div class="h-line bg-dark"></div>
        <p class="text-center mt-3">
            Nếu bạn có bất kỳ thắc mắc hoặc cần hỗ trợ, hãy liên hệ với chúng tôi. <br>
            Chúng tôi luôn sẵn sàng lắng nghe và hỗ trợ để mang đến trải nghiệm tốt nhất cho bạn.
        </p>
    </div>

    <!-- Vùng chứa thông tin liên hệ -->
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-6 mb-5 px-4">
                <div class="bg-white rounded shadow p-4">
                    <iframe class="w-100 rounded mb-4" height="320px" src="<?php echo $contact_r['iframe'] ?>"
                        referrerpolicy="no-referrer-when-downgrade" loading="lazy"></iframe>
                    <h5>Địa chỉ</h5>
                    <a href="<?php echo $contact_r['gmap'] ?>" target="_blank"
                        class="d-inline-block text-decoration-none text-dark mb-2">
                        <i class="bi bi-geo-alt-fill"></i> <?php echo $contact_r['address'] ?>
                    </a>
                    <h5 class="mt-4">Liên hệ</h5>
                    <a href="tel:+<?php echo $contact_r['pn1'] ?>"
                        class="d-inline-block mb-2 text-decoration-none text-dark">
                        <i class="bi bi-telephone-fill"></i> +<?php echo $contact_r['pn1'] ?>
                    </a> <br>
                    <?php
                    $pn2 = $contact_r['pn2'];
                    if ($pn2 != '') {
                        echo <<<data
                            <a href="tel:+$pn2" class="d-inline-block text-decoration-none text-dark">
                                <i class="bi bi-telephone-fill"></i> +$pn2
                            </a>
                        data;
                    }
                    ?>

                    <h5 class="mt-4">Email</h5>
                    <a href="mailto: <?php echo $contact_r['email'] ?>"
                        class="d-inline-block mb-2 text-decoration-none text-dark">
                        <i class="bi bi-envelope-fill"></i></i> <?php echo $contact_r['email'] ?>
                    </a>
                    <h5 class="mt-4">Kết nối với chúng tôi</h5>
                    <a href="<?php echo $contact_r['fb'] ?>" target="_blank" class="d-inline-block text-dark fs-5 me-2">
                        <i class="bi bi-facebook me-1"></i>
                    </a>
                    <a href="<?php echo $contact_r['insta'] ?>" target="_blank"
                        class="d-inline-block text-dark fs-5 me-2">
                        <i class="bi bi-instagram me-1"></i>
                    </a>

                    <?php
                    $tw = $contact_r['tw'];
                    if ($tw != '') {
                        echo <<<data
                            <a href="$tw" target="_blank" class="d-inline-block text-dark fs-5">
                                <i class="bi bi-twitter me-1"></i>
                            </a>
                        data;
                    }
                    ?>

                </div>
            </div>
            <div class="col-lg-6 col-md-6 px-4">
                <div class="bg-white rounded shadow p-4">
                    <form method="POST">
                        <h5>Gửi tin nhắn</h5>
                        <div class="mt-3">
                            <label class="form-label" style="font-weight: 500;">Họ và tên</label>
                            <input name="name" required type="text" class="form-control shadow-none">
                        </div>
                        <div class="mt-3">
                            <label class="form-label" style="font-weight: 500;">Email</label>
                            <input name="email" required type="email" class="form-control shadow-none">
                        </div>
                        <div class="mt-3">
                            <label class="form-label" style="font-weight: 500;">Chủ đề</label>
                            <input name="subject" required type="text" class="form-control shadow-none">
                        </div>
                        <div class="mt-3">
                            <label class="form-label" style="font-weight: 500;">Nội dung</label>
                            <textarea name="message" required class="form-control shadow-none" rows="5"
                                style="resize: none;"></textarea>
                        </div>
                        <button type="submit" name="send" class="btn text-white custome-bg mt-3">GỬI</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php
    if (isset($_POST['send'])) {
        // Lọc dữ liệu gửi lên
        $frm_data = filteration($_POST);

        // Chèn dữ liệu vào bảng user_queries
        $q = "INSERT INTO user_queries (name, email, subject, message) VALUES (?,?,?,?)";
        $values = [$frm_data['name'], $frm_data['email'], $frm_data['subject'], $frm_data['message']];
        $datatypes = 'ssss';

        // Gọi hàm insert của PHP
        $res = insert($q, $values, $datatypes);
        if ($res == 1) {
            alert('success', 'Mail sent!');
        } else {
            alert('error', 'Sever Down! Try again later.');
        }
    }
    ?>


    <!-- Footer -->
    <?php require('inc/footer.php'); ?>


</body>

</html>