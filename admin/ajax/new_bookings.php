<?php
require('../inc/db.config.php');
require('../inc/essentials.php');
adminLogin();


// Kiểm tra Nếu dữ liệu POST gửi lên có chứa get_bookings thì thực hiện đoạn mã bên trong.
if (isset($_POST['get_bookings'])) {
    // Lấy tất cả booking từ bảng booking_order và booking_details có trạng thái booked và arrival = 0 (chưa đến nhận phòng)
    $query = "SELECT bo.*, bd.* FROM booking_order bo INNER JOIN booking_details bd
        ON bo.booking_id = bd.booking_id WHERE bo.booking_status = 'booked' AND bo.arrival = 0 ORDER BY bo.booking_id ASC";

    $res = mysqli_query($conn, $query);
    $i = 1;

    // Kiểm tra số hàng trả về từ truy vấn 
    if (mysqli_num_rows($res) == 0) {
        echo "<b>Không tìm thấy dữ liệu nào!</b>";
        exit;
    }

    while ($data = mysqli_fetch_assoc($res)) {
        // Định dạng lại date, checkin và checkout
        $date = date("d-m-Y", strtotime($data['datentime']));
        $checkin = date("d-m-Y", strtotime($data['check_in']));
        $checkout = date("d-m-Y", strtotime($data['check_out']));
        $price = number_format($data['price'], 0, ',', '.');   // Format giá phân cách hàng nghìn
        $trans_amount = number_format($data['trans_amount'], 0, ',', '.');   // Format giá phân cách hàng nghìn

        echo <<<data
            <tr>
                <td>$i</td>
                <td>
                    <span class="badge bg-primary">
                        Mã đơn hàng: {$data['order_id']}
                    </span>
                    <br>
                    <b>Họ và tên: </b> {$data['user_name']}
                    <br>
                    <b>Số điện thoại: </b> {$data['phonenum']}
                </td>

                <td>
                    <b>Phòng: </b> {$data['room_name']}
                    <br>
                    <b>Giá phòng: </b> $price ₫/đêm
                </td>
                
                <td>
                    <b>Ngày nhận phòng: </b> $checkin
                    <br>
                    <b>Ngày trả phòng: </b> $checkout
                    <br>
                    <b>Số tiền trả: </b> $trans_amount ₫
                    <br>
                    <b>Date: </b> $date
                </td>

                <td>
                    <!-- Button trigger modal -->
                    <button type="button" onclick="assign_room({$data['booking_id']})" class="btn text-white btn-sm custome-bg fw-bold shadow-none" data-bs-toggle="modal" data-bs-target="#assign-room">
                        <i class="bi bi-check2-square"></i> Sắp xếp phòng
                    </button>
                    <br>
                    <button type="button" onclick="cancel_booking({$data['booking_id']})" class="btn btn-outline-danger btn-sm mt-2 fw-bold shadow-none">
                        <i class="bi bi-trash"></i> Hủy đặt chỗ
                    </button>          
                </td>
            </tr>
        data;
        $i++;
    }
}

// Update arrival = 1 khi user đến nhận phòng và chỉ định số phòng cho user
// Kiểm tra Nếu dữ liệu POST gửi lên có chứa assign_room thì thực hiện hàm bên trong.
if (isset($_POST['assign_room'])) {
    // Lọc dữ liệu từ POST gửi lên
    $frm_data = filteration($_POST);

    // Kiểm tra xem số phòng này có đang được sử dụng bởi khách khác không
    // Điều kiện: phòng trùng, khách đã đến (arrival=1) và đơn hàng vẫn đang 'booked'
    $check_query = "SELECT * FROM booking_details bd 
                    INNER JOIN booking_order bo ON bd.booking_id = bo.booking_id 
                    WHERE bd.room_no = ? AND bo.arrival = ? AND bo.booking_status = ?";

    $check_values = [$frm_data['room_no'], 1, 'booked'];
    $check_res = select($check_query, $check_values, 'sis');

    if (mysqli_num_rows($check_res) > 0) {
        echo 'room_occupied'; // Trả về thông báo phòng đã có người
        exit;
    }

    $query = "UPDATE booking_order bo INNER JOIN booking_details bd
        ON bo.booking_id = bd.booking_id SET bo.arrival = ?, bo.rate_review = ?, bd.room_no = ? 
        WHERE bo.booking_id = ?";
    $values = [1, 0, $frm_data['room_no'], $frm_data['booking_id']];
    $datatypes = 'iisi';
    $res = update($query, $values, $datatypes);     // Trả về số hàng bị ảnh hưởng

    // Toán tử 3 ngôi, nếu res = 2 (dữ liệu update thành công 2 hàng) echo 1
    echo ($res == 2) ? 1 : 0;

}

// Xóa đơn đặt phòng
// Kiểm tra Nếu dữ liệu POST gửi lên có chứa cancel_booking thì thực hiện hàm bên trong.
if (isset($_POST['cancel_booking'])) {
    // Lọc dữ liệu từ POST gửi lên
    $frm_data = filteration($_POST);

    // Update cột booking_status và refund (hoàn tiền)
    $query = "UPDATE booking_order SET booking_status = ?, refund = ? WHERE booking_id = ?";
    $values = ['cancelled', 0, $frm_data['booking_id']];
    $datatypes = 'sii';
    $res = update($query, $values, $datatypes);   // Trả về số hàng bị ảnh hưởng

    echo $res;
}

// Tìm kiếm đơn đặt phòng
// Kiểm tra Nếu dữ liệu POST gửi lên có chứa search_booking thì thực hiện đoạn mã bên trong.
if (isset($_POST['search_booking'])) {
    // Lọc dữ liệu từ POST gửi lên
    $frm_data = filteration($_POST);    // Chứa key search_booking có value = người dùng nhập

    // Hiển thị thông tin booking có giá trị LIKE admin tìm kiếm (order_id, phonenum hoặc user_name)
    $query = "SELECT bo.*, bd.* FROM booking_order bo 
        INNER JOIN booking_details bd
        ON bo.booking_id = bd.booking_id 
        WHERE (bo.order_id LIKE ? OR bd.phonenum LIKE ? OR bd.user_name LIKE ?)
        AND (bo.booking_status = ? AND bo.arrival = ?) ORDER BY bo.booking_id ASC";
    $values = [
        "%{$frm_data['search_booking']}%",
        "%{$frm_data['search_booking']}%",
        "%{$frm_data['search_booking']}%",
        'booked',
        0
    ];
    $datatypes = 'ssssi';

    $res = select($query, $values, $datatypes);
    $i = 1;

    // Kiểm tra số hàng trả về từ truy vấn 
    if (mysqli_num_rows($res) == 0) {
        echo "<b>Không tìm thấy dữ liệu nào!</b>";
        exit;
    }

    while ($data = mysqli_fetch_assoc($res)) {
        // Định dạng lại date, checkin và checkout
        $date = date("d-m-Y", strtotime($data['datentime']));
        $checkin = date("d-m-Y", strtotime($data['check_in']));
        $checkout = date("d-m-Y", strtotime($data['check_out']));
        $price = number_format($data['price'], 0, ',', '.');   // Format giá phân cách hàng nghìn
        $trans_amount = number_format($data['trans_amount'], 0, ',', '.');   // Format giá phân cách hàng nghìn

        echo <<<data
            <tr>
                <td>$i</td>
                <td>
                    <span class="badge bg-primary">
                        Mã đơn hàng: {$data['order_id']}
                    </span>
                    <br>
                    <b>Họ và tên: </b> {$data['user_name']}
                    <br>
                    <b>Số điện thoại: </b> {$data['phonenum']}
                </td>

                <td>
                    <b>Phòng: </b> {$data['room_name']}
                    <br>
                    <b>Giá phòng: </b> $price ₫/đêm
                </td>
                
                <td>
                    <b>Ngày nhận phòng: </b> $checkin
                    <br>
                    <b>Ngày trả phòng: </b> $checkout
                    <br>
                    <b>Số tiền trả: </b> $trans_amount ₫
                    <br>
                    <b>Date: </b> $date
                </td>

                <td>
                    <!-- Button trigger modal -->
                    <button type="button" onclick="assign_room({$data['booking_id']})" class="btn text-white btn-sm custome-bg fw-bold shadow-none" data-bs-toggle="modal" data-bs-target="#assign-room">
                        <i class="bi bi-check2-square"></i> Sắp xếp phòng
                    </button>
                    <br>
                    <button type="button" onclick="cancel_booking({$data['booking_id']})" class="btn btn-outline-danger btn-sm mt-2 fw-bold shadow-none">
                        <i class="bi bi-trash"></i> Hủy đặt chỗ
                    </button>          
                </td>
            </tr>
        data;
        $i++;
    }
}
?>