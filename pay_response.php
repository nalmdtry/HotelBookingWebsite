<?php
require('admin/inc/db.config.php');
require('admin/inc/essentials.php');
date_default_timezone_set("Asia/Ho_Chi_Minh");
session_start();

// Tự xoá session room (nếu bạn muốn)
if (isset($_SESSION['room'])) {
    unset($_SESSION['room']);
}

// Hàm tạo session nếu mất
function regenerate_session($uid)
{
    global $conn;
    $user_q = $conn->prepare("SELECT id, name, profile, phonenum FROM user_cred WHERE id = ? LIMIT 1");
    $user_q->bind_param("i", $uid);
    $user_q->execute();
    $res = $user_q->get_result();
    if ($row = $res->fetch_assoc()) {
        $_SESSION['login'] = true;
        $_SESSION['uId'] = $row['id'];
        $_SESSION['uName'] = $row['name'];
        $_SESSION['uPic'] = $row['profile'];
        $_SESSION['uPhone'] = $row['phonenum'];
    }
}

// Nhận payload
$input = file_get_contents("php://input");
$data = json_decode($input, true);

// Nếu MoMo redirect về bằng GET (user redirected), fallback
if (!$data && !empty($_GET)) {
    $data = $_GET;
}

// Nếu vẫn rỗng thì dừng (và log)
if (!$data) {
    file_put_contents(__DIR__ . '/momo_callback.log', date('c') . " - NO DATA\n", FILE_APPEND);
    die("No data received");
}

// Log toàn bộ payload để debug
file_put_contents(__DIR__ . '/momo_callback.log', date('c') . " - PAYLOAD: " . print_r($data, true) . PHP_EOL, FILE_APPEND);

// Lấy thông tin từ MoMo (kiểm tra tồn tại trước)
$partnerCode = isset($data['partnerCode']) ? $data['partnerCode'] : '';
$orderId = isset($data['orderId']) ? $data['orderId'] : '';
$requestId = isset($data['requestId']) ? $data['requestId'] : '';
$amount = isset($data['amount']) ? $data['amount'] : '';
$resultCode = isset($data['resultCode']) ? $data['resultCode'] : '';
$transId = isset($data['transId']) ? $data['transId'] : '';
$signature = isset($data['signature']) ? $data['signature'] : '';
$extraData = isset($data['extraData']) ? $data['extraData'] : '';
$message = isset($data['message']) ? $data['message'] : '';
$orderInfo = isset($data['orderInfo']) ? $data['orderInfo'] : '';
$payType = isset($data['payType']) ? $data['payType'] : '';
$responseTime = isset($data['responseTime']) ? $data['responseTime'] : '';

// Giải mã extraData nếu cần
$extra = json_decode($extraData, true);

// MoMo keys
$accessKey = "klm05TvNBzhg7h7j";
$secretKey = "at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa";

// **IMPORTANT**: build rawHash theo đúng spec MoMo callback.
// Nếu MoMo gửi thêm các trường như message, orderInfo, payType, responseTime
// bạn phải include CHÍNH XÁC theo thứ tự MoMo doc.
// Dưới đây là rawHash phổ biến cho IPN (cập nhật theo payload bạn log được):

$rawHash = "accessKey=$accessKey"
    . "&amount=$amount"
    . "&extraData=$extraData"
    . "&message=$message"
    . "&orderId=$orderId"
    . "&orderInfo=$orderInfo"
    . "&orderType=" . (isset($data['orderType']) ? $data['orderType'] : '')
    . "&partnerCode=$partnerCode"
    . "&payType=$payType"
    . "&requestId=$requestId"
    . "&responseTime=$responseTime"
    . "&resultCode=$resultCode"
    . "&transId=$transId";

// Tạo chữ ký kiểm tra
$check = hash_hmac("sha256", $rawHash, $secretKey);

// Nếu chữ ký sai → log + redirect (không xử lý DB)
if ($check !== $signature) {
    file_put_contents(__DIR__ . '/momo_callback.log', date('c') . " - SIGNATURE MISMATCH\nRawHash: {$rawHash}\nCalcSig: {$check}\nRecvSig: {$signature}\n", FILE_APPEND);
    redirect('index.php');
    exit;
}

// Tìm order trong DB bằng order_id dùng prepared stmt
$stmt = $conn->prepare("SELECT booking_id, user_id, booking_status FROM booking_order WHERE order_id = ? LIMIT 1");
$stmt->bind_param("s", $orderId);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    // Không tìm thấy order -> log và redirect
    file_put_contents(__DIR__ . '/momo_callback.log', date('c') . " - ORDER NOT FOUND: {$orderId}\n", FILE_APPEND);
    redirect('index.php');
    exit;
}

$row = $res->fetch_assoc();
$booking_id = $row['booking_id'];
$user_id = $row['user_id'];

// Nếu session mất thì tạo lại
if (!(isset($_SESSION['login']) && $_SESSION['login'] === true)) {
    regenerate_session($user_id);
}

// Chuẩn bị update
if ($resultCode == 0) {
    $new_booking_status = 'booked';
    $new_trans_status = 'success';
} else {
    $new_booking_status = 'payment failed';
    $new_trans_status = 'failed';
}

// Update an toàn bằng prepared statement
$upd = $conn->prepare("UPDATE booking_order SET booking_status = ?, trans_id = ?, trans_amount = ?, trans_status = ?, trans_message = ? WHERE booking_id = ?");
$upd->bind_param("ssissi", $new_booking_status, $transId, $amount, $new_trans_status, $message, $booking_id);
$upd->execute();

// Log update result
file_put_contents(__DIR__ . '/momo_callback.log', date('c') . " - UPDATED booking_id={$booking_id} resultCode={$resultCode}\n", FILE_APPEND);

// Redirect user hiển thị trạng thái (user redirect hoặc IPN)
redirect('pay_status.php?order=' . urlencode($orderId));
exit;
?>