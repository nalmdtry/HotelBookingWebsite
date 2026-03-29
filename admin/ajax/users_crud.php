<?php
require('../inc/db.config.php');
require('../inc/essentials.php');
adminLogin();


// Kiểm tra Nếu dữ liệu POST gửi lên có chứa get_users thì thực hiện đoạn mã bên trong.
if (isset($_POST['get_users'])) {

    // Truy vấn lấy all user
    $res = selectAll('user_cred');     // Trả về result set
    $path = USERS_IMG_PATH;
    $i = 1;

    while ($row = mysqli_fetch_assoc($res)) {      // Lấy một hàng (row) từ result set dưới dạng mảng kết hợp

        // Nếu tài khoản chưa xác minh thì có hiển thị nút xóa, nếu xm rồi thì nút del = ''
        $del_btn = "<button type='button' onclick='remove_user({$row['id']})' class='btn btn-danger btn-sm shadow-none'>
                        <i class='bi bi-trash'></i>
                    </button>";

        // Kiểm tra tình trạng xác minh
        $verified = "<span class='badge bg-warning'><i class='bi bi-x-lg'></i></span>";
        if ($row['is_verified'] == 1) {
            $verified = "<span class='badge bg-success'><i class='bi bi-check-lg'></i></span>";
            $del_btn = "";
        }

        // Kiểm tra trạng thái nút status (kích hoạt = 1)
        $status = "<button onclick='toggle_status({$row['id']}, 0)' class='btn btn-dark btn-sm shadow-none'>Hoạt động</button>";
        if ($row['status'] == 0) {
            $status = "<button onclick='toggle_status({$row['id']}, 1)' class='btn btn-danger btn-sm shadow-none'>Không hoạt động</button>";
        }

        // date (chuyển sang định dạng chỉ có d-m-Y)
        $date = date('d-m-Y', strtotime($row['datentime']));

        echo <<<data
            <tr class="align-middle">
                <td>$i</td>
                <td>
                    <img src="$path{$row['profile']}" width="55px"> 
                    <br>
                    {$row['name']}</td>
                <td>{$row['email']}</td>
                <td>
                    {$row['phonenum']}
                </td>
                <td>{$row['address']} | {$row['pincode']}</td>
                <td>{$row['dob']}</td>
                <td>$verified</td>
                <td>$status</td>
                <td>$date</td>
                <td>$del_btn</td>
            </tr>
        data;
        $i++;
    }
}

// Chuyển đổi trạng thái nút status 
// Kiểm tra Nếu dữ liệu POST gửi lên có chứa toggle_status thì thực hiện đoạn mã bên trong.
if (isset($_POST['toggle_status'])) {
    $frm_data = filteration($_POST);

    $q = "UPDATE user_cred SET status = ? WHERE id = ?";
    $values = [$frm_data['value'], $frm_data['toggle_status']];     // Mảng chứa giá trị bind vào dấu ? trong câu SQL
    $datatypes = 'ii';                                              // Chỉ định kiểu dữ liệu bind

    if (update($q, $values, $datatypes)) {
        echo 1;
    } else {
        echo 0;
    }
}



// Xóa người dùng
// Kiểm tra Nếu dữ liệu POST gửi lên có chứa remove_user thì thực hiện đoạn mã bên trong.
if (isset($_POST['remove_user'])) {
    // Lọc dữ liệu từ POST gửi lên
    $frm_data = filteration($_POST);

    // Chỉ xóa người dùng có id được truyền vào và tính trạng xác minh = 0
    $q = "DELETE FROM user_cred WHERE id = ? AND is_verified = ?";
    $values = [$frm_data['user_id'], 0];
    $datatypes = 'ii';
    $res = delete($q, $values, $datatypes);

    // Nếu xóa thành công echo 1
    if ($res) {
        echo 1;
    } else {
        echo 0;
    }
}

// Tìm kiếm người dùng bằng username
// Kiểm tra Nếu dữ liệu POST gửi lên có chứa search_user thì thực hiện đoạn mã bên trong.
if (isset($_POST['search_user'])) {
    // Lọc dữ liệu từ POST gửi lên
    $frm_data = filteration($_POST);

    // Truy vấn những dòng có cột name giống với giá trị tìm kiếm
    $q = "SELECT * FROM user_cred WHERE name LIKE ?";
    $values = ["%{$frm_data['name']}%"];    // %name%: name có thể ở đầu, giữa hoặc cuối
    $datatypes = 's';
    $res = select($q, $values, $datatypes);     // Trả về result set

    $path = USERS_IMG_PATH;
    $i = 1;

    while ($row = mysqli_fetch_assoc($res)) {      // Lấy một hàng (row) từ result set dưới dạng mảng kết hợp

        // Nếu tài khoản chưa xác minh thì có hiển thị nút xóa, nếu xm rồi thì nút del = ''
        $del_btn = "<button type='button' onclick='remove_user({$row['id']})' class='btn btn-danger btn-sm shadow-none'>
                        <i class='bi bi-trash'></i>
                    </button>";

        // Kiểm tra tình trạng xác minh
        $verified = "<span class='badge bg-warning'><i class='bi bi-x-lg'></i></span>";
        if ($row['is_verified'] == 1) {
            $verified = "<span class='badge bg-success'><i class='bi bi-check-lg'></i></span>";
            $del_btn = "";
        }

        // Kiểm tra trạng thái nút status (kích hoạt = 1)
        $status = "<button onclick='toggle_status({$row['id']}, 0)' class='btn btn-dark btn-sm shadow-none'>Hoạt động</button>";
        if ($row['status'] == 0) {
            $status = "<button onclick='toggle_status({$row['id']}, 1)' class='btn btn-danger btn-sm shadow-none'>Không hoạt động</button>";
        }

        // date (chuyển sang định dạng chỉ có d-m-Y)
        $date = date('d-m-Y', strtotime($row['datentime']));

        echo <<<data
            <tr class="align-middle">
                <td>$i</td>
                <td>
                    <img src="$path{$row['profile']}" width="55px"> 
                    <br>
                    {$row['name']}</td>
                <td>{$row['email']}</td>
                <td>
                    {$row['phonenum']}
                </td>
                <td>{$row['address']} | {$row['pincode']}</td>
                <td>{$row['dob']}</td>
                <td>$verified</td>
                <td>$status</td>
                <td>$date</td>
                <td>$del_btn</td>
            </tr>
        data;
        $i++;
    }
}

?>