<?php
require('../admin/inc/db.config.php');
require('../admin/inc/essentials.php');
date_default_timezone_set("Asia/Ho_Chi_Minh");

// Xử lý chỉnh sửa thông tin user
// Kiểm tra Nếu dữ liệu POST gửi lên có chứa infor_form thì thực hiện đoạn mã bên trong
if (isset($_POST['infor_form'])) {
    // Lọc dữ liệu gửi lên từ POST
    $frm_data = filteration($_POST);

    // Bắt đầu phiên làm việc 
    session_start();

    // Kiểm tra sđt mới đã tồn tại trong db hay chưa
    $u_exist = select(
        "SELECT * FROM user_cred WHERE phonenum = ? AND id != ? LIMIT 1",
        [$frm_data['phonenum'], $_SESSION['uId']],
        'si'
    );

    // Nếu số hàng trả về khác 0 thì sdt đã tồn tại
    if (mysqli_num_rows($u_exist) != 0) {
        echo 'phone_already';
        exit;
    }

    // Update dữ liệu
    $upd_query = "UPDATE user_cred SET name = ?, address = ?, phonenum = ?, pincode = ?, dob = ?
        WHERE id = ? LIMIT 1";
    $values = [
        $frm_data['name'],
        $frm_data['address'],
        $frm_data['phonenum'],
        $frm_data['pincode'],
        $frm_data['dob'],
        $_SESSION['uId']
    ];
    $datatypes = 'sssisi';
    $res = update($upd_query, $values, $datatypes);

    if ($res == 1) {    // Nếu update dữ liệu thành công
        // Lưu name user mới vào session
        $_SESSION['uName'] = $frm_data['name'];
        echo 1;
    } else {
        echo 0;
    }
}

// Xử lý chỉnh sửa ảnh đại diện user
// Kiểm tra Nếu dữ liệu POST gửi lên có chứa infor_form thì thực hiện đoạn mã bên trong
if (isset($_POST['profile_form'])) {
    // Bắt đầu phiên làm việc 
    session_start();

    // Upload hình ảnh user lên sever
    $img = uploadUserImage($_FILES['profile']);
    if ($img == 'inv_img') {
        echo 'inv_img';
        exit;
    } else if ($img == 'upd_failed') {
        echo 'upd_failed';
        exit;
    }

    // Truy vấn lấy hình ảnh cũ và xóa nó
    $u_exist = select(
        "SELECT profile FROM user_cred WHERE id = ? LIMIT 1",
        [$_SESSION['uId']],
        'i'
    );
    $u_fetch = mysqli_fetch_assoc($u_exist);

    // Gọi hàm xóa hình ảnh khỏi sever
    deleteImage($u_fetch['profile'], USERS_FOLDER);

    // Upload ảnh mới vào db
    $query = "UPDATE user_cred SET profile = ? WHERE id = ? LIMIT 1";
    $values = [$img, $_SESSION['uId']];
    $datatypes = 'si';

    if (update($query, $values, $datatypes)) {
        // Lưu ảnh mới vào session
        $_SESSION['uPic'] = $img;
        echo 1;
    } else {
        echo 0;
    }
}


// Xử lý đổi mật khẩu
// Kiểm tra Nếu dữ liệu POST gửi lên có chứa pass_form thì thực hiện đoạn mã bên trong
if (isset($_POST['pass_form'])) {
    // Lọc dữ liệu gửi lên từ POST
    $frm_data = filteration($_POST);

    // Bắt đầu phiên làm việc 
    session_start();

    // Kiểm tra xác nhận mật khẩu có trùng với mật khẩu ko
    if ($frm_data['new_pass'] != $frm_data['confirm_pass']) {
        echo 'mismatch';
        exit;
    }

    // Mã hóa mật khẩu
    $enc_pass = password_hash($frm_data['new_pass'], PASSWORD_BCRYPT);


    // Update mật khẩu mới
    $upd_query = "UPDATE user_cred SET password = ? WHERE id = ? LIMIT 1";
    $values = [$enc_pass, $_SESSION['uId']];
    $datatypes = 'si';
    $res = update($upd_query, $values, $datatypes);

    if ($res == 1) {    // Nếu update dữ liệu thành công
        echo 1;
    } else {
        echo 0;
    }
}

?>