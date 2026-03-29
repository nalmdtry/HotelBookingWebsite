<?php
require('inc/essentials.php');
require('inc/db.config.php');

// Bắt đầu phiên làm việc
session_start();
// Kiểm tra xem tồn tại phiên làm việc có key = adminLogin chưa, nếu có thì chuyển đến trang dashboard
if ((isset($_SESSION['adminLogin']) && $_SESSION['adminLogin'] == true)) {
    // Gọi hàm chuyển trang
    redirect('dashboard.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login Panel</title>
    <?php require('inc/links.php'); ?>
    <style>
        .custome-alert {
            position: fixed;
            top: 25px;
            right: 25px;
        }

        div.login-form {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 400px;
        }
    </style>
</head>

<body class="bg-light">

    <div class="login-form text-center rounded bg-white shadow overflow-hidden">
        <form method="POST">
            <h4 class="bg-dark text-white py-3">ĐĂNG NHẬP QUẢN TRỊ VIÊN</h4>
            <div class="p-4">
                <div class="mb-3">
                    <input name="admin_name" required type="text" class="form-control shadow-none text-center"
                        placeholder="Tên Admin">
                </div>
                <div class="mb-4">
                    <input name="admin_pass" required type="password" class="form-control shadow-none text-center"
                        placeholder="Mật khẩu">
                </div>
                <button name="login" type="submit" class="btn text-white custome-bg shadow-none">ĐĂNG NHẬP</button>
            </div>
        </form>
    </div>

    <!-- Phần xử lý đăng nhập admin -->
    <?php
    if (isset($_POST['login'])) {
        // Gọi hàm lọc dữ liệu và lưu vào biến $frm_data
        $frm_data = filteration($_POST); // POST chứa các trường admin_name, admin_pass và login
    
        // Truy vấn đến csdl
        $sql = "SELECT * FROM admin_cred WHERE admin_name = ? AND admin_pass = ?";
        $values = [$frm_data['admin_name'], $frm_data['admin_pass']];
        // $frm_data là mảng chứa dữ liệu admin_name và admin_pass và login đã dc lọc gửi từ form
    
        $datatypes = "ss";  // datatypes chứa kiểu dữ liệu của biến được gửi đi(admin_name và admin_pass)
    
        // Gọi hàm select
        $res = select($sql, $values, $datatypes);
        //print_r($res); res chứa num_rows, fiel count, length,...
    
        // Nếu có 1 hàng kết quả trả về
        if ($res->num_rows == 1) {
            $row = mysqli_fetch_assoc($res);
            // print_r($row); row chứa sr_no, name, pass
    
            // Cờ (flag) báo admin đã đăng nhập
            $_SESSION['adminLogin'] = true;
            $_SESSION['adminID'] = $row['sr_no'];
            // echo $_SESSION['adminID'];
    
            // Gọi hàm chuyển trang 
            redirect('dashboard.php');
        } else {
            alert('error', 'Đăng nhập không thành công - Thông tin đăng nhập không hợp lệ');
        }
    }
    ?>

    <?php require('inc/scripts.php'); ?>
</body>

</html>