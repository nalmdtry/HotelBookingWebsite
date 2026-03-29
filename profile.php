<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NLam Hotel - PROFILE</title>
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

    // Lấy thông tin user
    $u_exist = select("SELECT * FROM user_cred WHERE id = ? LIMIT 1", [$_SESSION['uId']], 'i');

    // Kiểm tra số hàng trả về
    if (mysqli_num_rows($u_exist) == 0) {
        redirect('index.php');
    }

    // Lấy 1 hàng kết quả từ truy vấn dưới dạng mảng kết hợp
    $u_fetch = mysqli_fetch_assoc($u_exist);

    ?>


    <!-- Vùng chứa Rooms -->
    <div class="container">
        <div class="row">
            <!-- Phần tiêu đề -->
            <div class="col-12 my-5 px-4">
                <h2 class="fw-bold">HỒ SƠ</h2>
                <div style="font-size: 14px;">
                    <a href="index.php" class="text-secondary text-decoration-none">TRANG CHỦ</a>
                    <span class="text-secondary"> > </span>
                    <a href="#" class="text-secondary text-decoration-none">HỒ SƠ</a>
                </div>
            </div>

            <!-- Phần chỉnh sửa nội dung chung (tên, sdt, địa chỉ,...) -->
            <div class="col-12 mb-5 px-4">
                <div class="bg-white p-3 p-md-4 rounded shadow-sm">
                    <form id="info-form">
                        <h5 class="mb-3 fw-bold">Thông tin cơ bản</h5>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Họ và tên</label>
                                <input name="name" type="text" value="<?php echo $u_fetch['name'] ?>"
                                    class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Số điện thoại</label>
                                <input name="phonenum" type="number" value="<?php echo $u_fetch['phonenum'] ?>"
                                    class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Ngày sinh</label>
                                <input name="dob" type="date" value="<?php echo $u_fetch['dob'] ?>"
                                    class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Pincode</label>
                                <input name="pincode" type="number" value="<?php echo $u_fetch['pincode'] ?>"
                                    class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-8 mb-4">
                                <label class="form-label">Địa chỉ</label>
                                <textarea name="address" class="form-control shadow-none" rows="1"
                                    required><?php echo $u_fetch['address'] ?></textarea>
                            </div>
                        </div>

                        <!-- Button save change -->
                        <button type="submit" class="btn text-white custome-bg shadow-none">Lưu thay đổi</button>

                    </form>


                </div>

            </div>

            <!-- Phần chỉnh sửa ảnh đại diện  -->
            <div class="col-md-4 mb-5 px-4">
                <div class="bg-white p-3 p-md-4 rounded shadow-sm">
                    <form id="profile-form">
                        <h5 class="mb-3 fw-bold">Ảnh đại diện</h5>
                        <img src="<?php echo USERS_IMG_PATH . $u_fetch['profile'] ?>"
                            class="rounded-circle img-fluid mb-3">

                        <label class="form-label">Hình ảnh mới</label>
                        <input name="profile" type="file" accept=".jpg, .jpeg, .png, .webp"
                            class="mb-4 form-control shadow-none" required>

                        <!-- Button save change -->
                        <button type="submit" class="btn text-white custome-bg shadow-none">Lưu thay đổi</button>

                    </form>
                </div>
            </div>

            <!-- Phần nhập mật khẩu và xác nhận mk mới  -->
            <div class="col-md-8 mb-5 px-4">
                <div class="bg-white p-3 p-md-4 rounded shadow-sm">
                    <form id="pass-form">
                        <h5 class="mb-3 fw-bold">Thay đổi mật khẩu</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Mật khẩu mới</label>
                                <input name="new_pass" type="password" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Xác nhận mật khẩu</label>
                                <input name="confirm_pass" type="password" class="form-control shadow-none" required>
                            </div>
                        </div>

                        <!-- Button save change -->
                        <button type="submit" class="btn text-white custome-bg shadow-none">Lưu thay đổi</button>

                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Footer -->
    <?php require('inc/footer.php'); ?>

    <script>
        // Gán sự kiện sumbit cho info-from (xử lý thông tin chung)
        let info_form = document.getElementById('info-form');
        info_form.addEventListener('submit', function (e) {

            // Chặn hành vi mặc định của trình duyệt (reload trang)
            e.preventDefault();

            // Tạo đối tượng FormData
            let data = new FormData();
            data.append('name', info_form.elements['name'].value);
            data.append('phonenum', info_form.elements['phonenum'].value);
            data.append('address', info_form.elements['address'].value);
            data.append('pincode', info_form.elements['pincode'].value);
            data.append('dob', info_form.elements['dob'].value);
            data.append('infor_form', '');

            // Tạo đối tượng XMLHttpRequest
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "ajax/profile.php", true);

            // Khi nhận được phản hồi từ sever
            xhr.onload = function () {
                if (this.responseText == 'phone_already') {
                    alert('error', 'Số điện thoại đã được đăng ký!');
                } else if (this.responseText == 0) {
                    alert('error', 'Không có thay đổi nào được thực hiện!');
                } else {
                    alert('success', 'Đã lưu thay đổi!')
                }
            }

            // Gửi HTTP POST đến sever xử lý
            xhr.send(data);
        });



        // Gán sự kiện sumbit cho profile-form (xử lý ảnh đại diện)
        let profile_form = document.getElementById('profile-form');
        profile_form.addEventListener('submit', function (e) {

            // Chặn hành vi mặc định của trình duyệt (reload trang)
            e.preventDefault();

            // Tạo đối tượng FormData
            let data = new FormData();
            data.append('profile', profile_form.elements['profile'].files[0]);
            data.append('profile_form', '');

            // Tạo đối tượng XMLHttpRequest
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "ajax/profile.php", true);

            // Khi nhận được phản hồi từ sever
            xhr.onload = function () {
                if (this.responseText == 'inv_img') {
                    alert('error', 'Chỉ cho phép hình ảnh JPG, WEBP và PNG!');
                } else if (this.responseText == 'upd_failed') {
                    alert('error', 'Tải hình ảnh lên không thành công!');
                } else if (this.responseText == 0) {
                    alert('error', 'Cập nhật không thành công!');
                } else {
                    window.location.href = window.location.pathname;
                }
            }

            // Gửi HTTP POST đến sever xử lý
            xhr.send(data);
        });


        // Gán sự kiện sumbit cho pass-form (update mật khẩu mới)
        let pass_form = document.getElementById('pass-form');
        pass_form.addEventListener('submit', function (e) {

            // Chặn hành vi mặc định của trình duyệt (reload trang)
            e.preventDefault();

            // Kiểm tra mật khẩu và xác nhận mk có khớp ko
            let new_pass = pass_form.elements['new_pass'].value;
            let confirm_pass = pass_form.elements['confirm_pass'].value;
            if (new_pass != confirm_pass) {
                alert('error', 'Mật khẩu không khớp!');
                return false;
            }

            // Tạo đối tượng FormData
            let data = new FormData();
            data.append('new_pass', new_pass);
            data.append('confirm_pass', confirm_pass);
            data.append('pass_form', '');

            // Tạo đối tượng XMLHttpRequest
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "ajax/profile.php", true);

            // Khi nhận được phản hồi từ sever
            xhr.onload = function () {
                if (this.responseText == 'mismatch') {
                    alert('error', 'Mật khẩu không khớp!');
                } else if (this.responseText == 0) {
                    alert('error', 'Cập nhật không thành công!');
                } else {
                    alert('success', 'Đã lưu thay đổi!');
                    pass_form.reset();
                }
            }

            // Gửi HTTP POST đến sever xử lý
            xhr.send(data);
        });
    </script>

</body>

</html>