<?php
require('../inc/db.config.php');
require('../inc/essentials.php');
adminLogin();

// Kiểm tra Nếu dữ liệu POST gửi lên có chứa add_feature thì thực hiện đoạn mã bên trong.
if (isset($_POST['add_feature'])) {
    $frm_data = filteration($_POST);    // Lọc dữ liệu đầu vào
    $q = "INSERT INTO features (name) VALUES (?)";
    $values = [$frm_data['name']];
    $datatypes = 's';
    $res = insert($q, $values, $datatypes);
    echo $res;  // Trả về 1 (chèn thành công), 0 nếu thất bại
}

// Kiểm tra Nếu dữ liệu POST gửi lên có chứa get_features thì thực hiện đoạn mã bên trong.
if (isset($_POST['get_features'])) {
    $res = selectAll('features');       // Trả về mysqli_result object hoặc false nếu lỗi
    $i = 1;
    while ($row = mysqli_fetch_assoc($res)) {
        echo <<<data
            <tr>
                <td>$i</td>
                <td>{$row['name']}</td>
                <td>
                    <button type="button" onclick="rem_feature({$row['id']})" class="btn btn-sm btn-danger shadow-none">
                        <i class="bi bi-trash"></i> Xóa
                    </button>
                </td>
            </tr>
        data;
        $i++;
    }

}

// Kiểm tra Nếu dữ liệu POST gửi lên có chứa rem_feature thì thực hiện đoạn mã bên trong.
if (isset($_POST['rem_feature'])) {     // rem_feature chứa id tính năng muốn xóa (vd: 1)
    $frm_data = filteration($_POST);

    // Tạo câu SQL SELECT để kiểm tra tính năng này có đang được gán cho phòng nào không
    $q1 = "SELECT * FROM room_features WHERE features_id = ?";
    $values = [$frm_data['rem_feature']];
    $datatypes = 'i';

    // Gọi hàm select chạy sql, kq là 1 result set từ db
    $check_q = select($q1, $values, $datatypes);

    if (mysqli_num_rows($check_q) == 0) {   // Kiểm tra số dòng trả về
        $q2 = "DELETE FROM features WHERE id = ?";
        $res = delete($q2, $values, $datatypes);
        echo $res;    // Trả về 1 (xóa thành công), 0 nếu thất bại
    } else {    // Nếu tính năng này vẫn đang được gán cho ít nhất 1 phòng => k thể xóa
        echo 'room_added';
    }
}

// Kiểm tra Nếu dữ liệu POST gửi lên có chứa add_facility thì thực hiện đoạn mã bên trong.
if (isset($_POST['add_facility'])) {
    $frm_data = filteration($_POST);

    $img_r = uploadSVGImage($_FILES['icon'], FACILITIES_FOLDER);

    if ($img_r == 'inv_img') {
        echo $img_r;
    } else if ($img_r == 'inv_size') {
        echo $img_r;
    } else if ($img_r == 'upd_failed') {
        echo $img_r;
    } else {
        $q = "INSERT INTO facilities (icon, name, description) VALUES (?,?,?)";
        $values = [$img_r, $frm_data['name'], $frm_data['desc']];
        $datatypes = 'sss';
        $res = insert($q, $values, $datatypes);
        echo $res;
    }

}

// Kiểm tra Nếu dữ liệu POST gửi lên có chứa  get_facilities thì thực hiện đoạn mã bên trong.
if (isset($_POST['get_facilities'])) {
    $res = selectAll('facilities');     // Trả về mysqli_result object hoặc false nếu lỗi
    $i = 1;
    $path = FACILITIES_IMG_PATH;
    while ($row = mysqli_fetch_assoc($res)) {
        echo <<<data
            <tr class="align-middle">
                <td>$i</td>
                <td><img src="$path{$row['icon']}" width="50px"></td>
                <td>{$row['name']}</td>
                <td>{$row['description']}</td>
                <td>
                    <button type="button" onclick="rem_facility({$row['id']})" class="btn btn-sm btn-danger shadow-none">
                        <i class="bi bi-trash"></i> Xóa
                    </button>
                </td>
            </tr>
        data;
        $i++;
    }

}

// Kiểm tra Nếu dữ liệu POST gửi lên có chứa rem_facility thì thực hiện đoạn mã bên trong.
if (isset($_POST['rem_facility'])) {     // rem_facility chứa id tiện nghi muốn xóa (vd: 1)
    $frm_data = filteration($_POST);

    $q1 = "SELECT * FROM room_facilities WHERE facilities_id = ?";
    $values = [$frm_data['rem_facility']];
    $datatypes = 'i';

    // Tạo câu SQL SELECT để kiểm tra tiện nghi này có đang được gán cho phòng nào không
    $check_q = select($q1, $values, $datatypes);

    if (mysqli_num_rows($check_q) == 0) {   // Nếu k gán cho phòng nào thì có thể xóa
        $pre_q = "SELECT * FROM facilities WHERE id = ?";
        $res = select($pre_q, $values, $datatypes);
        $img = mysqli_fetch_assoc($res);

        if (deleteImage($img['icon'], FACILITIES_FOLDER)) {     // Gọi hàm xóa file ảnh khỏi folder
            $q = "DELETE FROM facilities WHERE id = ?";
            $res = delete($q, $values, $datatypes);
            echo $res;  // Trả về 1 (xóa thành công), 0 nếu thất bại
        } else {
            echo 0;
        }
    } else {      // Nếu tiện nghi đang được gán cho ít nhất 1 phòng => k thể xóa
        echo 'room_added';
    }

}

?>