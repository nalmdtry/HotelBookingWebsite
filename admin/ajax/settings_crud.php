<?php
require('../inc/db.config.php');
require('../inc/essentials.php');
adminLogin();

// Kiểm tra nếu request POST có biến get_general, thì thực hiện đoạn xử lý bên trong.
if (isset($_POST['get_general'])) {
    $q = "SELECT * FROM settings WHERE sr_no = ?";
    // Mảng values chứa giá trị thay thế cho ?
    $values = [1];

    // res chứa num_rows, fiel count, length,...
    $res = select($q, $values, "i");

    // Lấy một dòng dữ liệu đầu tiên dưới dạng mảng kết hợp 
    // (['site_title'=>..., 'site_about'=>...])
    $data = mysqli_fetch_assoc($res);

    // Chuyển mảng PHP thành chuỗi JSON 
    $json_data = json_encode($data);    // {"sr_no":"1","address":"...","email":"..."}
    echo $json_data; // In JSON ra output HTTP response 
    // — client nhận responseText là chuỗi JSON
}

// Kiểm tra nếu request POST có biến upd_general, thì thực hiện đoạn xử lý bên trong.
if (isset($_POST['upd_general'])) {
    // Gọi hàm lọc dữ liệu form để loại bỏ ký tự đặc biệt
    $frm_data = filteration($_POST);

    $q = "UPDATE settings SET site_title = ?, site_about = ? WHERE sr_no = ?";
    $values = [$frm_data['site_title'], $frm_data['site_about'], 1];
    $datatypes = 'ssi';
    $res = update($q, $values, $datatypes);
    echo $res; //→ gửi kết quả (1 hoặc 0) về cho JS.
}

// Kiểm tra xem có dữ liệu upd_shutdown được gửi từ AJAX không.
// → Nếu có, tức là admin vừa bật/tắt công tắc
if (isset($_POST['upd_shutdown'])) {
    $frm_data = ($_POST['upd_shutdown'] == 0) ? 1 : 0;
    // Nếu shutdown hiện tại = 0 → tắt (1) và Nếu shutdown hiện tại = 1 → mở (0)
    // frm_data là trạng thái mới cần update

    $q = "UPDATE settings SET shutdown = ? WHERE sr_no = ?";
    $values = [$frm_data, 1];
    $datatypes = 'ii';

    $res = update($q, $values, $datatypes);
    echo $res; // 1 nếu thành công, 0 nếu lỗi
}

// Kiểm tra nếu request POST có biến get_contacts, thì thực hiện đoạn xử lý bên trong.
if (isset($_POST['get_contacts'])) {
    $q = "SELECT * FROM contact_details WHERE sr_no = ?";
    $values = [1];
    $datatypes = "i";

    $res = select($q, $values, $datatypes);

    // Lấy một hàng kết quả dưới dạng mảng kết hợp (associative array)
    // keys là tên cột, values là giá trị, í dụ: ['sr_no' => '1', 'address' => '...', 'gmap' => '...']
    $data = mysqli_fetch_assoc($res);

    $json_data = json_encode($data);    // {"sr_no":"1","address":"",...}
    echo $json_data;
}

// Kiểm tra nếu request POST có biến upd_contacts, thì thực hiện đoạn xử lý bên trong.
if (isset($_POST['upd_contacts'])) {
    $frm_data = filteration($_POST);

    $q = "UPDATE contact_details SET address = ?, gmap = ?, pn1 = ?, pn2 = ?, email = ?, fb = ?, insta = ?, tw = ?, iframe = ? WHERE sr_no = ?";
    $values = [$frm_data['address'], $frm_data['gmap'], $frm_data['pn1'], $frm_data['pn2'], $frm_data['email'], $frm_data['fb'], $frm_data['insta'], $frm_data['tw'], $frm_data['iframe'], 1];
    $datatypes = 'sssssssssi';

    $res = update($q, $values, $datatypes); // 1: thành công, 0: ko có thay đổi)
    echo $res; // trả về client (JS sẽ dùng this.responseText để kiểm tra
}

// Kiểm tra Nếu dữ liệu POST gửi lên có chứa add_member thì thực hiện đoạn mã bên trong.
if (isset($_POST['add_member'])) {
    $frm_data = filteration($_POST);

    // $_FILES: Mảng chứa thông tin các file được tải lên qua form.
    $img_r = uploadImage($_FILES['picture'], ABOUT_FOLDER);     // Hàm di chuyển file ảnh sang thư mục đích và trả về tên ảnh 

    // echo vì AJAX trong JS (xhr.onload) sẽ đọc this.responseText, nên server gửi về gì thì client nhận được đúng chuỗi đó.
    if ($img_r == 'inv_img') {
        echo $img_r;
    } else if ($img_r == 'inv_size') {
        echo $img_r;
    } else if ($img_r == 'upd_failed') {
        echo $img_r;
    } else {
        $q = "INSERT INTO team_details (name, picture) VALUES (?, ?)";
        $values = [$frm_data['name'], $img_r];
        $datatypes = 'ss';

        $res = insert($q, $values, $datatypes);
        echo $res; // 1 thành công, 0 thất bại
    }
}

// Kiểm tra Nếu dữ liệu POST gửi lên có chứa get_members thì thực hiện đoạn mã bên trong.
if (isset($_POST['get_members'])) {
    $res = selectAll('team_details');   // Lấy tất cả dữ liệu từ bảng team_details (tên, ảnh)

    while ($row = mysqli_fetch_assoc($res)) { // Duyệt từng bản ghi, trả về mảng kết hợp ['sr_no => 1, 'name' => ..., 'picture' => ...]
        $path = ABOUT_IMG_PATH;     // Lây đường dẫn ảnh

        // Heredoc syntax: cho phép in HTML nhiều dòng dễ dàng.
        echo <<<data
            <div class="col-md-2 mb-3">
                <div class="card bg-dark text-white">
                    <img src="$path$row[picture]" class="card-img">
                        <div class="card-img-overlay text-end">
                            <button type="button" onclick= "rem_member($row[sr_no])" class="btn btn-danger btn-sm shadow-none">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </div>
                    <p class="card-text text-center px-3 py-2"><small>$row[name]</small></p>
                </div>
            </div>
        data;
    }
}

// Kiểm tra Nếu dữ liệu POST gửi lên có chứa rem_member thì thực hiện đoạn mã bên trong.
if (isset($_POST['rem_member'])) {
    $frm_data = filteration($_POST);

    // Truy xuất lấy thông tin member (để lấy tên ảnh xóa khỏi db và t) 
    $pre_q = "SELECT * FROM team_details WHERE sr_no = ?";
    $values = [$frm_data['rem_member']];
    $datatypes = 'i';
    $res = select($pre_q, $values, $datatypes);

    $img = mysqli_fetch_assoc($res);

    // Xóa file ảnh member khỏi hệ thống
    if (deleteImage($img['picture'], ABOUT_FOLDER)) {
        $q = "DELETE FROM team_details WHERE sr_no = ?";
        $res = delete($q, $values, $datatypes);
        echo $res;  // Trả về 1 (nếu xóa thành công)
    } else {
        echo 0;
    }
}
?>