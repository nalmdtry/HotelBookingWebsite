<?php
require('../inc/db.config.php');
require('../inc/essentials.php');
adminLogin();


// Kiểm tra Nếu dữ liệu POST gửi lên có chứa get_bookings thì thực hiện đoạn mã bên trong.
if (isset($_POST['get_bookings'])) {
    // Lọc dữ liệu từ POST gửi lên 
    $frm_data = filteration($_POST);

    // Phân trang
    $limit = 5;
    $page = $frm_data['page'];      // Số trang hiện tại
    $start = ($page - 1) * $limit;

    // Lấy tất cả booking từ bảng booking_order và booking_details theo từng cặp dữ liệu
    $query = "SELECT bo.*, bd.* FROM booking_order bo 
        INNER JOIN booking_details bd ON bo.booking_id = bd.booking_id 
        WHERE ((bo.booking_status='booked' AND bo.arrival = 1)
        OR (bo.booking_status='cancelled' AND bo.refund = 1)
        OR (bo.booking_status='payment failed'))
        AND (bo.order_id LIKE ? OR bd.phonenum LIKE ? OR bd.user_name LIKE ?)
        ORDER BY bo.booking_id DESC";

    $values = [
        "%{$frm_data['search']}%",
        "%{$frm_data['search']}%",
        "%{$frm_data['search']}%",
    ];
    $datatypes = 'sss';

    $res = select($query, $values, $datatypes);

    // Truy vấn limit (hiển thị bản ghi trong 1 trang)
    $limit_query = $query . " LIMIT $start, $limit";
    $limit_res = select($limit_query, $values, $datatypes);

    // Kiểm tra số hàng trả về từ truy vấn 
    $total_rows = mysqli_num_rows($res);
    if ($total_rows == 0) {
        // Trả về chuỗi json
        $output = json_encode(["table_data" => "<b>Không tìm thấy dữ liệu nào!</b>", "pagination" => '']);
        echo $output;
        exit;
    }

    $table_data = "";
    $i = $start + 1;

    // Lặp qua mỗi hàng dữ liệu từ truy vấn $limit_res, mỗi trang chỉ hiển thị 1 dữ liệu (tùy $limit)
    while ($data = mysqli_fetch_assoc($limit_res)) {
        // Định dạng lại date, checkin và checkout
        $date = date("d-m-Y", strtotime($data['datentime']));
        $checkin = date("d-m-Y", strtotime($data['check_in']));
        $checkout = date("d-m-Y", strtotime($data['check_out']));
        $price = number_format($data['price'], 0, ',', '.');   // Format giá phân cách hàng nghìn
        $trans_amount = number_format($data['trans_amount'], 0, ',', '.');   // Format giá phân cách hàng nghìn

        // Trả về trạng thái badge khác nhau tùy booking_status
        if ($data['booking_status'] == 'booked') {
            $status_bg = 'bg-success';
        } else if ($data['booking_status'] == 'cancelled') {
            $status_bg = 'bg-danger';
        } else {
            $status_bg = 'bg-warning text-dark';
        }

        $table_data .= "
            <tr>
                <td>$i</td>
                <td>
                    <span class='badge bg-primary'>
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
                    <b>Tổng số tiền: </b> $trans_amount ₫
                    <br>
                    <b>Ngày đặt phòng: </b> $date
                </td>

                <td>
                    <span class='badge $status_bg'>{$data['booking_status']}</span>
                </td>
                <td>
                    <!-- Button trigger modal -->
                    <button type='button' onclick='download({$data['booking_id']})' class='btn btn-success btn-sm fw-bold shadow-none'>
                        <i class='bi bi-filetype-pdf'></i>
                    </button>          
                </td>
            </tr>
            ";

        $i++;
    }

    // Tạo dấu phân trang
    $pagination = "";

    // Nếu tổng số hàng trả về từ truy vấn (vd 5) > $limit (giới hạn dữ liệu xuất hiện trên mỗi trang)
    if ($total_rows > $limit) {
        $total_pages = ceil($total_rows / $limit);     // (tổng trang = tổng hàng dữ liệu / giới hạn mỗi trang) vd 3/1=3 trang

        // Hiển thị button đầu
        if ($page != 1) {       // Nút không xuất hiện trên trang đầu tiên
            $pagination .= "<li class='page-item'>
            <button onclick='change_page(1)' class='page-link shadow-none'>Đầu</button>
        </li>";
        }

        // Tạo button prev
        // Nếu trang hiện tại = 1 thì ko hiện nút prev
        $disabled = ($page == 1) ? "disabled" : "";
        $prev = $page - 1;
        $pagination .= "<li class='page-item $disabled'>
            <button onclick='change_page($prev)' class='page-link shadow-none'>Trước</button>
        </li>";


        // Tạo button next
        // Nếu trang hiện tại = trang cuối thì ko hiện nút next
        $disabled = ($page == $total_pages) ? "disabled" : "";
        $next = $page + 1;
        $pagination .= "<li class='page-item $disabled'>
            <button onclick='change_page($next)' class='page-link shadow-none'>Sau</button>
        </li>";

        // Hiển thị button Cuối
        if ($page != $total_pages) {    // Nút không xuất hiện trên trang cuối
            $pagination .= "<li class='page-item'>
            <button onclick='change_page($total_pages)' class='page-link shadow-none'>Cuối</button>
        </li>";
        }
    }



    // Chuyển đối tượng PHP thành mảng JSON
    $output = json_encode(["table_data" => $table_data, "pagination" => $pagination]);
    echo $output;
}

?>