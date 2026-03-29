<?php

// Frontend purpose data (hiển thị dữ liệu)
define('SITE_URL', 'http://127.0.0.1/');
define('ABOUT_IMG_PATH', SITE_URL . 'images/about/');   // Đường dẫn công khai (dùng để hiển thị dữ liệu)
define('CAROUSEL_IMG_PATH', SITE_URL . 'images/carousel/');
define('FACILITIES_IMG_PATH', SITE_URL . 'images/facilities/');
define('ROOMS_IMG_PATH', SITE_URL . 'images/rooms/');
define('USERS_IMG_PATH', SITE_URL . 'images/users/');

// Backend upload process needs this data (ghi, xóa, di chuyển ...)
// Định nghĩa hằng số (ko thay đổi trong suốt chương trình (tên hằng, giá trị))
define('UPLOAD_IMAGE_PATH', $_SERVER['DOCUMENT_ROOT'] . '/images/'); // $_SERVER['DOCUMENT_ROOT']: đường dẫn tuyệt đối đến thư mục gốc của website
define('ABOUT_FOLDER', 'about/');   // Đường dẫn nội bộ (dùng để xử lý dữ liệu)
define('CAROUSEL_FOLDER', 'carousel/');
define('FACILITIES_FOLDER', 'facilities/');
define('ROOMS_FOLDER', 'rooms/');
define('USERS_FOLDER', 'users/');

// Hàm kiểm tra admin đã đăng nhập chưa, nếu chưa thì về trang index
function adminLogin()
{
    session_start();
    if (!(isset($_SESSION['adminLogin']) && $_SESSION['adminLogin'] == true)) {
        echo "<script>
            window.location.href='index.php';
        </script>";
        exit;
    }
    // Tạo session_id mới, xóa session_id cũ, dữ liệu trên ss cũ vẫn còn
    // session_regenerate_id(true);
}


// Hàm chuyển trang
function redirect($url)
{
    echo "<script>
        window.location.href='$url';
    </script>";
    exit;
}


// Hàm hiện hộp thoại thông báo
function alert($type, $msg)
{
    $bs_class = ($type == "success") ? "alert-success" : "alert-danger";

    echo <<<alert
            <div class="alert $bs_class alert-dismissible fade show custome-alert" role="alert">
                <strong class="me-3">$msg</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        alert;
}

// Hàm upload ảnh jpeg, png, webb
function uploadImage($image, $folder) // (file upload($_FILES['picture']), tên thư mục, $_FILES['picture'] chứa name, type, tmp_name, size, error )
{
    $valid_mime = ['image/jpeg', 'image/png', 'image/webp']; // Mảng các MIME type hợp lệ cho ảnh, chỉ cho phép .jpg hoặc .jpeg, .webp
    $img_mime = $image['type']; // Lấy loại MIME của file upload, $image['type'] đến từ $_FILES['picture']['type']
    // Ví dụ: file avatar.png → 'image/png'

    if (!in_array($img_mime, $valid_mime)) { // Kiểm tra giá trị $img_mime có nằm trong mảng $valid_mime ko
        return 'inv_img'; // Invalid image mime or format
    } else if (($image['size'] / (1024 * 1024)) > 2) { // byte -> mb
        return 'inv_size'; // Invalid size greater than 2mb
    } else {
        $ext = pathinfo($image['name'], PATHINFO_EXTENSION);    // lấy đuôi file từ tên gốc, ví dụ jpg
        $rname = 'IMG_' . random_int(11111, 99999) . ".$ext";

        // Tạo đường dẫn đích để lưu file ảnh
        $img_path = UPLOAD_IMAGE_PATH . $folder . $rname;
        if (move_uploaded_file($image['tmp_name'], $img_path)) {
            // Hàm PHP chuẩn để di chuyển file từ thư mục tạm ($_FILES['picture']['tmp_name']) sang thư mục đích ($img_path)
            return $rname;
        } else {
            return 'upd_failed';
        }
    }
}

// Hàm upload ảnh svg
function uploadSVGImage($image, $folder)
{
    $valid_mime = ['image/svg+xml'];
    $img_mime = $image['type'];

    if (!in_array($img_mime, $valid_mime)) {
        return 'inv_img';   // invalid image mime or format
    } else if (($image['size'] / (1024 * 1024)) > 1) {
        return 'inv_size';  // invalid size greater than 1mb
    } else {
        $ext = pathinfo($image['name'], PATHINFO_EXTENSION);
        $rname = 'IMG_' . random_int(11111, 99999) . ".$ext";

        $img_path = UPLOAD_IMAGE_PATH . $folder . $rname;
        if (move_uploaded_file($image['tmp_name'], $img_path)) {
            return $rname;  // Nếu thành công thì trả về tên hình ảnh
        } else {
            return 'upd_failed';
        }
    }
}

// Hàm upload ảnh user
function uploadUserImage($image)
{
    // Tạo một mảng (array) chứa các kiểu MIME được chấp nhận cho file ảnh (JPEG, PNG, WebP).
    $valid_mime = ['image/jpeg', 'image/png', 'image/webp'];
    // Lấy kiểu MIME ảnh người dùng tải lên
    $img_mime = $image['type'];

    if (!in_array($img_mime, $valid_mime)) {
        return 'inv_img';
    } else {
        $ext = pathinfo($image['name'], PATHINFO_EXTENSION);
        $rname = 'IMG_' . random_int(11111, 99999) . ".jpeg";

        $img_path = UPLOAD_IMAGE_PATH . USERS_FOLDER . $rname;

        if ($ext == 'png' || $ext == 'PNG') {
            // Lấy tệp ảnh PNG tạm thời được tải lên (với đường dẫn trong $image['tmp_name']) 
            // và tạo một đối tượng hình ảnh từ nó để có thể xử lý tiếp
            $img = imagecreatefrompng($image['tmp_name']);
        } else if ($ext == 'webp' || $ext == 'WEBP') {
            $img = imagecreatefromwebp($image['tmp_name']);
        } else {
            $img = imagecreatefromjpeg($image['tmp_name']);
        }

        // Hàm ghi (lưu) đối tượng ảnh $img vào tệp tin tại $img_path dưới định dạng JPEG
        if (imagejpeg($img, $img_path, 75)) {
            return $rname;
        } else {
            return 'upd_failed';
        }
    }
}

// Hàm xóa ảnh khỏi hệ thống (chỉ xóa tệp ko xóa thư mục) 
function deleteImage($image, $folder)
{
    // Tạo đường dẫn file
    $path = UPLOAD_IMAGE_PATH . $folder . $image;
    if (unlink($path)) {    // unlink() là hàm PHP để xóa file vật lý trên ổ đĩa, true nếu thành công
        return true;
    } else {
        return false;
    }
}
?>