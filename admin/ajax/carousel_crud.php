<?php
require('../inc/db.config.php');
require('../inc/essentials.php');
adminLogin();

// Kiểm tra Nếu dữ liệu POST gửi lên có chứa add_image thì thực hiện đoạn mã bên trong.
if (isset($_POST['add_image'])) {
    // $_FILES: Mảng chứa thông tin các file được tải lên qua form.
    $img_r = uploadImage($_FILES['picture'], CAROUSEL_FOLDER); //

    // echo vì AJAX trong JS (xhr.onload) sẽ đọc this.responseText, nên server gửi về gì thì client nhận được đúng chuỗi đó.
    if ($img_r == 'inv_img') {
        echo $img_r;
    } else if ($img_r == 'inv_size') {
        echo $img_r;
    } else if ($img_r == 'upd_failed') {
        echo $img_r;
    } else {
        $q = "INSERT INTO carousel (image) VALUES (?)";
        $values = [$img_r];
        $datatypes = 's';

        $res = insert($q, $values, $datatypes);
        echo $res; // 1 thành công, 0 thất bại
    }
}

// Kiểm tra Nếu dữ liệu POST gửi lên có chứa get_carousel thì thực hiện đoạn mã bên trong.
if (isset($_POST['get_carousel'])) {
    $res = selectAll('carousel');

    while ($row = mysqli_fetch_assoc($res)) { // Duyệt từng bản ghi, trả về mảng kết hợp ['sr_no => 1, 'picture' => ...]

        // Lấy đường dẫn thư mục đến carousel
        $path = CAROUSEL_IMG_PATH;

        // Heredoc syntax: cho phép in HTML nhiều dòng dễ dàng.
        echo <<<data
            <div class="col-md-4 mb-3">
                <div class="card bg-dark text-white">
                    <img src="$path{$row['image']}" class="card-img">
                        <div class="card-img-overlay text-end">
                            <button type="button" onclick= "rem_image({$row['sr_no']})" class="btn btn-danger btn-sm shadow-none">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </div>
                </div>
            </div>
        data;
    }
}

// Kiểm tra Nếu dữ liệu POST gửi lên có chứa rem_image thì thực hiện đoạn mã bên trong.
if (isset($_POST['rem_image'])) {
    $frm_data = filteration($_POST);

    $pre_q = "SELECT * FROM carousel WHERE sr_no = ?";
    $values = [$frm_data['rem_image']];
    $datatypes = 'i';
    $res = select($pre_q, $values, $datatypes);

    // Lấy dữ liệu ảnh
    $img = mysqli_fetch_assoc($res);

    // Xóa file ảnh trong thư mục
    if (deleteImage($img['image'], CAROUSEL_FOLDER)) {
        // Xóa khỏi db
        $q = "DELETE FROM carousel WHERE sr_no = ?";
        $res = delete($q, $values, $datatypes);
        echo $res;  // Trả về 1 (nếu xóa thành công)
    } else {
        echo 0;
    }
}
?>