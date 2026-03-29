<?php
require('../inc/db.config.php');
require('../inc/essentials.php');
adminLogin();

// Kiểm tra Nếu dữ liệu POST gửi lên có chứa add-room thì thực hiện đoạn mã bên trong.
if (isset($_POST['add_room'])) {
    // Mảng các ID tính năng
    $features = filteration(json_decode($_POST['features']));   // Chuyển chuỗi JSON vd '[1,3,5]' -> mảng PHP [1,3,5]
    // Mảng các ID tiện nghi
    $facilities = filteration(json_decode($_POST['facilities']));

    $frm_data = filteration($_POST);

    // Tạo flag để theo dõi trạng thái thêm phòng 
    $flag = 0;

    // Chèn dữ liệu vào bảng rooms
    $q1 = "INSERT INTO rooms(name, area, price, quantity, adult, children, description) 
    VALUES (?,?,?,?,?,?,?)";
    $values = [$frm_data['name'], $frm_data['area'], $frm_data['price'], $frm_data['quantity'], $frm_data['adult'], $frm_data['children'], $frm_data['desc']];
    $datatypes = 'siiiiis';

    if (insert($q1, $values, $datatypes)) {
        // Nếu insert thành công thì đặt cờ = 1
        $flag = 1;
    }

    // Lấy id tự tăng (AUTO_INCREMENT) của bảng rooms vừa được thêm trong câu insert trước có cùng $conn, vd rooms.id=1 thì room_facilities.room_id=1
    $room_id = mysqli_insert_id($conn);

    // Thêm tiện nghi cho phòng có id trên
    $q2 = "INSERT INTO room_facilities (room_id, facilities_id) VALUES (?,?)";
    if ($stmt = mysqli_prepare($conn, $q2)) {
        foreach ($facilities as $f) {   // Duyệt từng ID tiện nghi trong mảng vs [1,3,5]
            mysqli_stmt_bind_param($stmt, 'ii', $room_id, $f);
            mysqli_stmt_execute($stmt);     // Thực thi lệnh insert cho từng tiện nghi
        }
        mysqli_stmt_close($stmt);
    } else {
        $flag = 0;
        die('Không thể chuẩn bị câu truy vấn - Insert');
    }

    // Thêm tính năng cho phòng có id trên
    $q3 = "INSERT INTO room_features (room_id, features_id) VALUES (?,?)";
    if ($stmt = mysqli_prepare($conn, $q3)) {
        foreach ($features as $f) {
            mysqli_stmt_bind_param($stmt, 'ii', $room_id, $f);
            mysqli_stmt_execute($stmt);
        }
        mysqli_stmt_close($stmt);
    } else {
        $flag = 0;
        die('Không thể chuẩn bị câu truy vấn - Insert');
    }

    if ($flag) {
        echo 1;
    } else {
        echo 0;
    }

}

// Kiểm tra Nếu dữ liệu POST gửi lên có chứa get_all_rooms thì thực hiện đoạn mã bên trong.
if (isset($_POST['get_all_rooms'])) {

    // Hiển thị dữ liệu của các phòng có trạng thái removed = 0 
    $q = "SELECT * FROM rooms WHERE removed = ?";
    $values = [0];
    $datatypes = 'i';

    $res = select($q, $values, $datatypes);     // Trả về result set

    $i = 1;
    while ($row = mysqli_fetch_assoc($res)) {      // Lấy một hàng (row) từ result set dưới dạng mảng kết hợp

        // Kiểm tra trạng thái nút status (kích hoạt = 1)
        if ($row['status'] == 1) {
            $status = "<button onclick='toggle_status({$row['id']}, 0)' class='btn btn-primary btn-sm shadow-none'>Hoạt động</button>";
        } else {
            $status = "<button onclick='toggle_status({$row['id']}, 1)' class='btn btn-warning btn-sm shadow-none'>Không hoạt động</button>";
        }

        echo <<<data
            <tr class="align-middle">
                <td>$i</td>
                <td>{$row['name']}</td>
                <td>{$row['area']} m²</td>
                <td>
                    <span class="badge rounded-pill bg-light text-dark">
                        Số người lớn: {$row['adult']}
                    </span> <br>
                    <span class="badge rounded-pill bg-light text-dark">
                        Số trẻ em: {$row['children']}
                    </span>
                </td>
                <td>{$row['price']} ₫/đêm</td>
                <td>{$row['quantity']}</td>
                <td>$status</td>
                <td>
                    <button type="button" onclick="edit_details({$row['id']})" class="btn btn-dark btn-sm shadow-none" data-bs-toggle="modal" data-bs-target="#edit-room">
                        <i class="bi bi-pencil-square"></i>Chỉnh sửa
                    </button>

                    <button type="button" onclick="room_images({$row['id']}, '{$row['name']}')" class="btn btn-info btn-sm shadow-none" data-bs-toggle="modal" data-bs-target="#room-images">
                        <i class="bi bi-images"></i>
                    </button>

                    <button type="button" onclick="remove_room({$row['id']})" class="btn btn-danger btn-sm shadow-none">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            </tr>
        data;
        $i++;
    }
}

// Hiển thị dữ liệu phòng lên modal edit phòng
if (isset($_POST['get_room'])) {
    $frm_data = filteration($_POST);
    $q1 = "SELECT * FROM rooms WHERE id = ?";
    $q2 = "SELECT * FROM room_features WHERE room_id = ?";
    $q3 = "SELECT * FROM room_facilities WHERE room_id = ?";

    $values = [$frm_data['get_room']]; // Mảng giá trị tương ứng với placeholders ?, key get_room chứa id phòng
    $datatypes = 'i';                  // Dùng cho hàm select giả định sẽ bind param với kiểu i

    $res1 = select($q1, $values, $datatypes);
    $res2 = select($q2, $values, $datatypes);
    $res3 = select($q3, $values, $datatypes);

    // Lấy một hàng (associative array) từ result set rooms
    $roomdata = mysqli_fetch_assoc($res1);

    // Mảng các id tính năng
    $features = [];
    if (mysqli_num_rows($res2) > 0) {   // Trả về số lượng hàng của câu truy vấn select res2
        while ($row = mysqli_fetch_assoc($res2)) {
            array_push($features, $row['features_id']);
        }
    }

    // Mảng các id tiện nghi
    $facilities = [];
    if (mysqli_num_rows($res3) > 0) {
        while ($row = mysqli_fetch_assoc($res3)) {
            array_push($facilities, $row['facilities_id']);
        }
    }

    // Tạo mảng kết hợp (associative array) chứa dữ liệu full để trả cho client
    $data = ["roomdata" => $roomdata, "features" => $features, "facilities" => $facilities];

    // Chuyển mảng PHP sang chuỗi JSON vd {"roomdata": { "id": 3,...}, "features": [1,3], "facilities": [2,4,5]}
    $data = json_encode($data);

    echo $data;     // response body của request AJAX, Client (JS) sẽ nhận responseText và JSON.parse để lấy object.
}

// Kiểm tra Nếu dữ liệu POST gửi lên có chứa edit_room thì thực hiện đoạn mã bên trong.
if (isset($_POST['edit_room'])) {
    // Chuyển chuỗi JSON vd '[1,3,5]' -> mảng PHP [1,3,5]
    $features = filteration(json_decode($_POST['features']));
    $facilities = filteration(json_decode($_POST['facilities']));

    // Gọi hàm lọc dữ liệu form gửi lên
    $frm_data = filteration($_POST);

    // Tạo flag để theo dõi trạng thái chỉnh sửa phòng
    $flag = 0;

    // Tạo câu truy vấn update
    $q1 = "UPDATE rooms SET name = ?, area = ?, price = ?, quantity = ?, adult = ?,
    children = ?, description = ? WHERE id = ?";

    // Tạo mảng bind giá trị vào các dấu ?
    $values = [
        $frm_data['name'],
        $frm_data['area'],
        $frm_data['price'],
        $frm_data['quantity'],
        $frm_data['adult'],
        $frm_data['children'],
        $frm_data['desc'],
        $frm_data['room_id']
    ];

    // Kiểu dữ liệu truyền vào ?
    $datatypes = 'siiiiisi';

    // Nếu update thành công (true)
    if (update($q1, $values, $datatypes)) {
        $flag = 1;
    }

    // Xóa hết tính năng (features) và tiện nghi (facilities) của phòng trước khi thêm mới các mục đã chỉnh sửa
    $del_features = delete("DELETE FROM room_features WHERE room_id = ?", [$frm_data['room_id']], 'i');
    $del_facilities = delete("DELETE FROM room_facilities WHERE room_id = ?", [$frm_data['room_id']], 'i');

    // Nếu 1 trong 2 hoặc cả 2 xóa ko thành công
    if (!($del_features && $del_facilities)) {
        $flag = 0;
    }

    // Thêm các tính năng cho phòng có id trên
    $q2 = "INSERT INTO room_features (room_id, features_id) VALUES (?, ?)";
    if ($stmt = mysqli_prepare($conn, $q2)) {
        foreach ($features as $f) {     // Lặp từng f trong $features (các id feature)
            mysqli_stmt_bind_param($stmt, 'ii', $frm_data['room_id'], $f);
            mysqli_stmt_execute($stmt);
        }
        mysqli_stmt_close($stmt);
        $flag = 1;
    } else {
        $flag = 0;
        die('Không thể chuẩn bị câu truy vấn - Insert');

    }

    // Thêm các tiện nghi cho phòng có id trên
    $q3 = "INSERT INTO room_facilities (room_id, facilities_id) VALUES (?, ?)";
    if ($stmt = mysqli_prepare($conn, $q3)) {
        foreach ($facilities as $f) {
            mysqli_stmt_bind_param($stmt, 'ii', $frm_data['room_id'], $f);
            mysqli_stmt_execute($stmt);
        }
        $flag = 1;
        mysqli_stmt_close($stmt);
    } else {
        $flag = 0;
        die('Không thể chuẩn bị câu truy vấn - Insert');
    }

    if ($flag) {
        echo 1;
    } else {
        echo 0;
    }


}

// Kiểm tra Nếu dữ liệu POST gửi lên có chứa toggle_status thì thực hiện đoạn mã bên trong.
if (isset($_POST['toggle_status'])) {
    $frm_data = filteration($_POST);

    $q = "UPDATE rooms SET status = ? WHERE id = ?";
    $values = [$frm_data['value'], $frm_data['toggle_status']];     // Mảng chứa giá trị bind vào dấu ? trong câu SQL
    $datatypes = 'ii';                                              // Chỉ định kiểu dữ liệu bind

    if (update($q, $values, $datatypes)) {
        echo 1;
    } else {
        echo 0;
    }
}

// Kiểm tra Nếu dữ liệu POST gửi lên có chứa add_image thì thực hiện đoạn mã bên trong.
if (isset($_POST['add_image'])) {
    $frm_data = filteration($_POST);

    // $_FILES: Mảng chứa thông tin các file được tải lên qua form.
    $img_r = uploadImage($_FILES['image'], ROOMS_FOLDER);

    // echo vì AJAX trong JS (xhr.onload) sẽ đọc this.responseText, nên server gửi về gì thì client nhận được đúng chuỗi đó.
    if ($img_r == 'inv_img') {
        echo $img_r;
    } else if ($img_r == 'inv_size') {
        echo $img_r;
    } else if ($img_r == 'upd_failed') {
        echo $img_r;
    } else {
        $q = "INSERT INTO room_images (room_id, image) VALUES (?, ?)";
        $values = [$frm_data['room_id'], $img_r];
        $datatypes = 'is';

        $res = insert($q, $values, $datatypes);
        echo $res;  // 1 chèn thành công, 0 thất bại
    }
}

// Kiểm tra Nếu dữ liệu POST gửi lên có chứa get_room_images thì thực hiện đoạn mã bên trong.
if (isset($_POST['get_room_images'])) {     // get_room_images là id của phòng cần thêm ảnh 
    $frm_data = filteration($_POST);
    $q = "SELECT * FROM room_images WHERE room_id = ?";
    $values = [$frm_data['get_room_images']];
    $datatypes = 'i';

    $res = select($q, $values, $datatypes);
    $path = ROOMS_IMG_PATH;
    $i = 1;

    while ($row = mysqli_fetch_assoc($res)) {

        if ($row['thumb'] == 1) {
            $thumb_btn = "<i class='bi bi-check-lg text-light bg-success px-2 py-1 rounded fs-5'></i>";
        } else {
            $thumb_btn = "<button onclick='thumb_image({$row['sr_no']}, {$row['room_id']})' class='btn btn-secondary shadow-none'>
                        <i class='bi bi-check-lg'></i>
                    </button>";
        }
        echo <<<data
            <tr class="align-middle">
                <td><img src="$path{$row['image']}" class="img-fluid"></td>
                <td>$thumb_btn</td>
                <td>
                    <button onclick="rem_image({$row['sr_no']}, {$row['room_id']})" class="btn btn-danger shadow-none">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            </tr>
        data;
        $i++;
    }
}

// Xóa hình ảnh phòng
// Kiểm tra Nếu dữ liệu POST gửi lên có chứa rem_image thì thực hiện đoạn mã bên trong.
if (isset($_POST['rem_image'])) {
    $frm_data = filteration($_POST);

    $pre_q = "SELECT * FROM room_images WHERE sr_no = ? AND room_id = ?";
    $values = [$frm_data['img_id'], $frm_data['room_id']];
    $datatypes = 'ii';

    $res = select($pre_q, $values, $datatypes);
    $img = mysqli_fetch_assoc($res);

    if (deleteImage($img['image'], ROOMS_FOLDER)) {
        $q = "DELETE FROM room_images WHERE sr_no = ? AND room_id = ?";
        $res = delete($q, $values, $datatypes);
        echo $res;
    } else {
        echo 0;
    }
}

// Kiểm tra Nếu dữ liệu POST gửi lên có chứa thumb_image thì thực hiện đoạn mã bên trong.
if (isset($_POST['thumb_image'])) {
    $frm_data = filteration($_POST);

    // Đặt tất cả hình của phòng về thumb = 0 vì mỗi phòng chỉ được phép có 1 thumbnail
    $pre_q = "UPDATE room_images SET thumb = ? WHERE room_id = ?";
    $pre_values = [0, $frm_data['room_id']];
    $pre_datatypes = 'ii';
    $pre_res = update($pre_q, $pre_values, $pre_datatypes);

    // Đặt thumbnail cho hình được chọn
    $q = "UPDATE room_images SET thumb = ? WHERE sr_no = ? AND room_id = ?";
    $values = [1, $frm_data['img_id'], $frm_data['room_id']];
    $datatypes = 'iii';
    $res = update($q, $values, $datatypes);

    echo $res;     // 1 thành công, 0 thất bại


}

// Xóa phòng
// Kiểm tra Nếu dữ liệu POST gửi lên có chứa room_id thì thực hiện đoạn mã bên trong.
if (isset($_POST['remove_room'])) {
    $frm_data = filteration($_POST);

    $values = [$frm_data['room_id']];
    $datatypes = 'i';

    // Lấy danh sách ảnh của phòng có room_id và xóa ảnh khỏi sever
    $res1 = select("SELECT * FROM room_images WHERE room_id = ?", $values, $datatypes);
    while ($row = mysqli_fetch_assoc($res1)) {   // Lặp qua từng dòng (từng ảnh).
        deleteImage($row['image'], ROOMS_FOLDER);   // Xóa file ảnh thực trong thư mục
    }

    // Xóa bản ghi ảnh trong db
    $res2 = delete("DELETE FROM room_images WHERE room_id = ?", $values, $datatypes);
    // Xóa tất cả tính năng liên kết phòng
    $res3 = delete("DELETE FROM room_features WHERE room_id = ?", $values, $datatypes);
    // Xóa tất cả tiện nghi liên kết phòng
    $res4 = delete("DELETE FROM room_facilities WHERE room_id = ?", $values, $datatypes);
    // Đánh dấu phòng đã xóa (soft delete)
    $res5 = update("UPDATE rooms SET removed = ? WHERE id = ?", [1, $frm_data['room_id']], 'ii');


    if ($res2 || $res3 || $res4 || $res5) {
        echo 1;
    } else {
        echo 0;
    }
}

?>