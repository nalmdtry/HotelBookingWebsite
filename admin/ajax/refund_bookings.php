<?php
require('../inc/db.config.php');
require('../inc/essentials.php');
adminLogin();


// Kiểm tra Nếu dữ liệu POST gửi lên có chứa get_bookings thì thực hiện đoạn mã bên trong.
if (isset($_POST['get_bookings'])) {
    // Lấy tất cả booking từ bảng booking_order và booking_details có trạng thái cancelled và refund = 0
    $query = "SELECT bo.*, bd.* FROM booking_order bo INNER JOIN booking_details bd
        ON bo.booking_id = bd.booking_id WHERE bo.booking_status = 'cancelled' AND bo.refund = 0 ORDER BY bo.booking_id ASC";

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
                    <b>Ngày nhận phòng: </b> $checkin
                    <br>
                    <b>Ngày trả phòng: </b> $checkout
                    <br>
                    <b>Date: </b> $date
                </td>

                <td>
                    <b>$trans_amount ₫</b> 
                </td>

                <td>
                    <button type="button" onclick="refund_booking({$data['booking_id']})" class="btn btn-success btn-sm fw-bold shadow-none">
                        <i class="bi bi-cash-stack"></i> Refund
                    </button>          
                </td>
            </tr>
        data;
        $i++;
    }
}

// Hoàn tiền cho user (update cột refund = 1)
// Kiểm tra Nếu dữ liệu POST gửi lên có chứa refund_booking thì thực hiện hàm bên trong.
if (isset($_POST['refund_booking'])) {
    // Lọc dữ liệu từ POST gửi lên
    $frm_data = filteration($_POST);

    // Update cột booking_status và refund (hoàn tiền)
    $query = "UPDATE booking_order SET refund = ? WHERE booking_id = ?";
    $values = [1, $frm_data['booking_id']];
    $datatypes = 'ii';
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
        AND (bo.booking_status = ? AND bo.refund = ?) ORDER BY bo.booking_id ASC";
    $values = [
        "%{$frm_data['search_booking']}%",
        "%{$frm_data['search_booking']}%",
        "%{$frm_data['search_booking']}%",
        'cancelled',
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
                    <b>Ngày nhận phòng: </b> $checkin
                    <br>
                    <b>Ngày trả phòng: </b> $checkout
                    <br>
                    <b>Date: </b> $date
                </td>

                <td>
                    <b>$trans_amount ₫</b> 
                </td>

                <td>
                    <button type="button" onclick="refund_booking({$data['booking_id']})" class="btn btn-success btn-sm fw-bold shadow-none">
                        <i class="bi bi-cash-stack"></i> Refund
                    </button>          
                </td>
            </tr>
        data;
        $i++;
    }
}
?>