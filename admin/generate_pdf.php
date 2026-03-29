<?php
require('inc/essentials.php');
require('inc/db.config.php');
require('../vendor/autoload.php');  // Thư viện của mpdf
adminLogin();

// Kiểm tra nếu dữ liệu GET chứa gen_pdf và id thì thực hiện hàm bên trong
if (isset($_GET['gen_pdf']) && isset($_GET['id'])) {
    // Lọc dữ liệu GET
    $frm_data = filteration($_GET);

    // Truy vấn lấy dữ liệu từ bảng booking_order, booking_details và email từ user_cred
    $query = "SELECT bo.*, bd.*, uc.email FROM booking_order bo 
        INNER JOIN booking_details bd ON bo.booking_id = bd.booking_id 
        INNER JOIN user_cred uc ON bo.user_id = uc.id
        WHERE ((bo.booking_status = 'booked' AND bo.arrival = 1) OR (bo.booking_status = 'cancelled' AND bo.refund = 1)
            OR (bo.booking_status = 'payment failed')) AND bo.booking_id = '{$frm_data['id']}'";
    $res = mysqli_query($conn, $query);

    // Kiểm tra số hàng trả về từ truy vấn
    $total_rows = mysqli_num_rows($res);
    if ($total_rows == 0) {
        header('Location: dashboard.php');
        exit;
    }

    // Lấy 1 hàng dữ liệu từ truy vấn
    $data = mysqli_fetch_assoc($res);

    // Định dạng date
    $date = date("H:i:s | d-m-Y", strtotime($data['datentime']));
    $checkin = date("d-m-Y", strtotime($data['check_in']));
    $checkout = date("d-m-Y", strtotime($data['check_out']));

    // Định dạng số tiền phòng và tổng tiền
    $price = number_format($data['price'], 0, ',', '.');   // Format giá phân cách hàng nghìn
    $trans_amount = number_format($data['trans_amount'], 0, ',', '.');   // Format giá phân cách hàng nghìn

    // Định nghĩa CSS cơ bản cho hóa đơn
    $header_style = "background-color: #f2f2f2; font-weight: bold; padding: 10px; text-align: center;";
    $row_style = "padding: 8px; border-bottom: 1px solid #ddd; font-size: 14px;";
    $title_style = "color: #333333; font-size: 24px; margin-bottom: 20px; text-align: center;";
    $thank_you_style = "margin-top: 20px; text-align: center; font-style: italic; color: #555555; font-size: 14px;";

    $table_data = "
    <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ccc;'>
        <h2 style='$title_style'>BIÊN LAI ĐẶT PHÒNG</h2>
        
        <table border='1' cellpadding='10' cellspacing='0' style='width: 100%; border-collapse: collapse; border: 1px solid #ccc;'>
            <tr style='$header_style'>
                <td style='width: 50%;'>Mã đơn hàng: {$data['order_id']}</td>
                <td style='width: 50%;'>Ngày đặt phòng: $date</td>
            </tr>
            <tr>
                <td colspan='2' style='$row_style background-color: #e9ecef; font-weight: bold;'>Tình trạng đặt phòng: {$data['booking_status']}</td>
            </tr>
            <tr>
                <td style='$row_style'>Họ và tên: {$data['user_name']}</td>
                <td style='$row_style'>Email: {$data['email']}</td>
            </tr>
            <tr>
                <td style='$row_style'>Số điện thoại: {$data['phonenum']}</td>
                <td style='$row_style'>Địa chỉ: {$data['address']}</td>
            </tr>
            <tr>
                <td style='$row_style background-color: #f8f9fa;'>Tên phòng: {$data['room_name']}</td>
                <td style='$row_style background-color: #f8f9fa; font-weight: bold; color: #007bff;'>Giá phòng: $price đ/đêm</td>
            </tr>
            <tr>
                <td style='$row_style'>Ngày nhận phòng: $checkin</td>
                <td style='$row_style'>Ngày trả phòng: $checkout</td>
            </tr>
    ";

    // Kiểm tra tình trạng phòng là cancelled thì echo thêm tổng tiền đã thanh toán và trạng thái hoàn tiền
    if ($data['booking_status'] == 'cancelled') {
        // Kiểm tra trạng thái refund
        $refund = ($data['refund'] == 1) ? "<span style='color: green; font-weight: bold;'>Số tiền đã được hoàn lại</span>" : "<span style='color: orange; font-weight: bold;'>Chưa được hoàn tiền</span>";
        $table_data .= "
            <tr>
                <td style='$row_style color: #dc3545;'>Số tiền đã thanh toán: $trans_amount đ</td>
                <td style='$row_style'>Trạng thái hoàn tiền: $refund</td>
            </tr>
        ";
        // Kiểm tra tình trạng phòng là payment failed thì echo thêm số tiền giao dịch và phản hồi
    } else if ($data['booking_status'] == 'payment failed') {
        $table_data .= "
            <tr>
                <td style='$row_style color: #dc3545;'>Số tiền giao dịch: $trans_amount đ</td>
                <td style='$row_style color: #dc3545;'>Phản hồi thất bại: {$data['trans_message']}</td>
            </tr>
        ";
        // Kiểm tra tình trạng phòng là booked thì echo thêm số phòng và tổng tiền đã thanh toán
    } else { // booked
        $table_data .= "
            <tr>
                <td style='$row_style background-color: #d4edda; font-weight: bold; color: #155724;'>Số phòng: {$data['room_no']}</td>
                <td style='$row_style background-color: #d4edda; font-weight: bold; color: #155724;'>Số tiền đã thanh toán: $trans_amount đ</td>
            </tr>
        ";
    }

    // Đóng thẻ table
    $table_data .= "</table>";

    // Thêm lời cảm ơn
    $table_data .= "
        <p style='$thank_you_style'>Cảm ơn quý khách đã tin tưởng và lựa chọn dịch vụ của chúng tôi. Chúc quý khách có một kỳ nghỉ tuyệt vời!</p>
    </div>
    ";


    // Tạo một thể hiện của lớp
    $mpdf = new \Mpdf\Mpdf();

    // Write some HTML code:
    $mpdf->WriteHTML($table_data);

    // Output a PDF file directly to the browser
    //$mpdf->Output($data['order_id'] . '.pdf', 'D');
    $mpdf->Output($data['order_id'] . '.pdf', 'I');

} else {
    header('Location: dashboard.php');
}
?>