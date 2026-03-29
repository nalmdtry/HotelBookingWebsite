<?php
require('../admin/inc/db.config.php');
require('../admin/inc/essentials.php');
date_default_timezone_set("Asia/Ho_Chi_Minh");

//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader (created by composer, not included with PHPMailer)
require('../vendor/autoload.php');


// Hàm php gửi link xác minh tài khoản đến email
function sendemail_verify($name, $email, $token)
{
    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);
    $mail->CharSet = 'UTF-8';
    $mail->Encoding = 'base64';

    //Server settings
    //$mail->SMTPDebug = 2;                     //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->SMTPAuth = true;                                   //Enable SMTP authentication

    $mail->Host = 'smtp.gmail.com';                     //Set the SMTP server to send through
    $mail->Username = 'vongoclam0000@gmail.com';                     //SMTP username
    $mail->Password = 'jtpzxdvwgfvumdaj';                         //SMTP password

    // $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->SMTPSecure = "tls";
    $mail->Port = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom("vongoclam0000@gmail.com", "Khách sạn NLam");
    $mail->addAddress($email, $name);               //Name is optional
    //$mail->addAddress('joe@example.net', 'Joe User');     //Add a recipient

    //$mail->addReplyTo('info@example.com', 'Information');
    //$mail->addCC('cc@example.com');
    //$mail->addBCC('bcc@example.com');

    //Attachments
    //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Xác minh email từ Khách sạn Nlam';
    $email_template = "
        <h2>Bạn đã đăng ký tài khoản trên Khách sạn Nlam</h2>
        <h5>Xác minh địa chỉ email của bạn để Đăng nhập bằng liên kết bên dưới</h5>
        <br><br>
        <a href='" . SITE_URL . "email_confirm.php?email_confirmation&email=$email&token=$token" . "'> Click Me </a>
        
    ";
    $mail->Body = $email_template;

    try {
        $mail->send();
        return 1;   // gửi thành công
    } catch (Exception $e) {
        return 0;   // gửi lỗi
    }

}


// Hàm php gửi link đặt lại mật khẩu đến email
function send_password_reset($name, $email, $token)
{
    $mail = new PHPMailer(true);
    $mail->CharSet = 'UTF-8';
    $mail->Encoding = 'base64';

    //$mail->SMTPDebug = 2; 
    $mail->isSMTP();
    $mail->SMTPAuth = true;

    $mail->Host = 'smtp.gmail.com';
    $mail->Username = 'vongoclam0000@gmail.com';
    $mail->Password = 'jtpzxdvwgfvumdaj';

    $mail->SMTPSecure = "tls";
    $mail->Port = 587;

    $mail->setFrom("vongoclam0000@gmail.com", "Khách sạn NLam");
    $mail->addAddress($email, $name);

    $mail->isHTML(true);
    $mail->Subject = "Thông báo đặt lại mật khẩu";

    $email_template = "
        <h2>Xin chào</h2>
        <h3>Bạn nhận được email này vì chúng tôi đã nhận được yêu cầu đặt lại mật khẩu cho tài khoản của bạn</h3>
        <h5>Vui lòng nhấn vào liên kết bên dưới để đặt lại mật khẩu của bạn</h5>
        <br><br>
        <a href='" . SITE_URL . "index.php?account_recovery&email=$email&token=$token" . "'> Click Me</a>
    ";

    $mail->Body = $email_template;

    try {
        $mail->send();
        return 1;   // gửi thành công
    } catch (Exception $e) {
        return 0;   // gửi lỗi
    }
}


// Xử lý đăng ký người dùng
// Kiểm tra Nếu dữ liệu POST gửi lên có chứa register thì thực hiện đoạn mã bên trong
if (isset($_POST['register'])) {
    // Lọc dữ liệu đầu vào
    $data = filteration($_POST);

    // Kiểm tra trường mật khẩu có trùng với nhập lại mk ko
    if ($data['pass'] != $data['cpass']) {
        echo 'pass_mismatch';
        exit;
    }

    // Kiểm tra user có tồn tại hay không
    $u_exist = select(
        "SELECT * FROM user_cred WHERE email = ? OR phonenum = ? LIMIT 1",
        [$data['email'], $data['phonenum']],
        'ss'
    );

    // Kiểm tra số hàng trả về từ câu truy vấn trên
    if (mysqli_num_rows($u_exist) != 0) {
        $u_exist_fetch = mysqli_fetch_assoc($u_exist);
        echo ($u_exist_fetch['email'] == $data['email']) ? 'email_already' : 'phone_already';
        exit;
    }

    // Upload hình ảnh user lên sever
    $img = uploadUserImage($_FILES['profile']);

    if ($img == 'inv_img') {
        echo 'inv_img';
        exit;
    } else if ($img == 'upd_failed') {
        echo 'upd_failed';
        exit;
    }

    // Gửi link xác nhận email người dùng
    $token = md5(rand());

    if (!sendemail_verify($data['name'], $data['email'], $token)) {
        echo 'mail_failed';
        exit;
    }

    $enc_pass = password_hash($data['pass'], PASSWORD_BCRYPT);

    // Chèn dữ liệu user vào bảng
    $q = "INSERT INTO user_cred (name, email, address, phonenum, pincode, dob, profile, password, token)
            VALUES (?,?,?,?,?,?,?,?,?)";
    $values = [
        $data['name'],
        $data['email'],
        $data['address'],
        $data['phonenum'],
        $data['pincode'],
        $data['dob'],
        $img,
        $enc_pass,
        $token
    ];
    $datatypes = 'ssssissss';

    if (insert($q, $values, $datatypes)) {
        echo 1;
    } else {
        echo 'insert_failed';
    }

}

// Xử lý đăng nhập người dùng
// Kiểm tra Nếu dữ liệu POST gửi lên có chứa login thì thực hiện đoạn mã bên trong
if (isset($_POST['login'])) {
    // Lọc dữ liệu đầu vào
    $data = filteration($_POST);

    // Kiểm tra user có tồn tại trong db hay không
    $u_exist = select(
        "SELECT * FROM user_cred WHERE email = ? OR phonenum = ? LIMIT 1",
        [$data['email_mob'], $data['email_mob']],
        'ss'
    );

    if (mysqli_num_rows($u_exist) == 0) {
        echo 'inv_email_mob';
    } else {
        $u_fetch = mysqli_fetch_assoc($u_exist);
        if ($u_fetch['is_verified'] == 0) {
            echo 'not_verified';
        } else if ($u_fetch['status'] == 0) {
            echo 'inactive';
        } else {
            // Kiểm tra mật khẩu nhập vào có khớp với giá trị băm mật khẩu đã lưu trữ hay không
            if (!password_verify($data['pass'], $u_fetch['password'])) {
                echo 'invalid_pass';
            } else {
                session_start();    // Bắt đầu phiên làm việc session, lưu tên, id,... của user vào session
                $_SESSION['login'] = true;
                $_SESSION['uId'] = $u_fetch['id'];
                $_SESSION['uName'] = $u_fetch['name'];
                $_SESSION['uPic'] = $u_fetch['profile'];
                $_SESSION['uPhone'] = $u_fetch['phonenum'];
                echo 1;
            }
        }
    }
}

// Xử lý quên mật khẩu
// Kiểm tra Nếu dữ liệu POST gửi lên có chứa forgot_pass thì thực hiện đoạn mã bên trong
if (isset($_POST['forgot_pass'])) {
    // Lọc dữ liệu đầu vào
    $data = filteration($_POST);

    // Kiểm tra user có tồn tại hay k
    $u_exist = select("SELECT * FROM user_cred WHERE email = ? LIMIT 1", [$data['email']], 's');

    // Kiểm tra số hàng trả về từ truy vấn trên
    if (mysqli_num_rows($u_exist) == 0) {
        echo 'inv_email';
    } else {
        $u_fetch = mysqli_fetch_assoc($u_exist);    // Lấy 1 hàng kết quả từ câu truy vấn trên dưới dạng mảng kết hợp
        if ($u_fetch['is_verified'] == 0) {
            echo 'not_verified';
        } else if ($u_fetch['status'] == 0) {
            echo 'inactive';
        } else {

            $token = md5(rand());

            // Gọi hàm gửi liên kết đặt lại mật khẩu đến email
            if (!send_password_reset($u_fetch['name'], $data['email'], $token)) {
                echo 'mail_failed';
            } else {
                // Cập nhật token mới và date
                $date = date("Y-m-d");

                $q = "UPDATE user_cred SET token = ?, t_expire = ? WHERE id = ?";
                $values = [$token, $date, $u_fetch['id']];
                $datatypes = 'ssi';

                if (update($q, $values, $datatypes)) {
                    echo 1;
                } else {
                    echo 'upd_failed';
                }
            }
        }
    }
}

// Xử lý đặt lại mật khẩu 
// Kiểm tra Nếu dữ liệu POST gửi lên có chứa recover_user thì thực hiện đoạn mã bên trong
if (isset($_POST['recover_user'])) {
    // Lọc dữ liệu đầu vào
    $data = filteration($_POST);

    // Mã hóa mật khẩu mới
    $enc_pass = password_hash($data['pass'], PASSWORD_BCRYPT);

    // Thực thi lệnh update
    $q = "UPDATE user_cred SET password = ?, token = ?, t_expire = ?
        WHERE email = ? AND token = ?";
    $values = [$enc_pass, null, null, $data['email'], $data['token']];
    $datatypes = 'sssss';

    // Nếu update thành công
    if (update($q, $values, $datatypes)) {
        echo 1;
    } else {
        echo 'failed';
    }
}
?>