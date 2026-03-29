<?php
// Kết nối tới cơ sở dữ liệu
$conn = mysqli_connect('localhost', 'root', '', 'hbwebsite');
if (!$conn) {
    die("Không thể kết nối tới Cơ sở dữ liệu" . mysqli_connect_error());
}

// Hàm lọc dữ liệu đầu vào
function filteration($data)
{
    // Duyệt qua toàn bộ phần tử trong mảng data, key là tên ô input, value là giá trị người dùng nhập
    foreach ($data as $key => $value) {
        $value = trim($value);  // Loại bỏ khoảng trắng ở đầu và cuối chuỗi.
        $value = stripcslashes($value); // Loại bỏ các backslash escape (ví dụ "O\'Reilly" → "O'Reilly"
        $value = strip_tags($value); // Loại bỏ mọi thẻ HTML
        $value = htmlspecialchars($value); // Chuyển các ký tự đặc biệt HTML thành thực thể (ví dụ < → &lt;, " → &quot;)
        $data[$key] = $value;
    }
    return $data;
}

// Hàm Select CSDL
function select($sql, $values, $datatypes)
{
    // Lấy biến kết nối MySQL từ phạm vi toàn cục
    $conn = $GLOBALS['conn'];
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, $datatypes, ...$values);
        if (mysqli_stmt_execute($stmt)) {
            $res = mysqli_stmt_get_result($stmt);
            // Trả về một object kiểu mysqli_result, k phải true false hoặc 1 0
            mysqli_stmt_close($stmt);
            return $res;
        } else {
            mysqli_stmt_close($stmt);
            die("Không thể thực thi câu truy vấn - Select");
        }
    } else {
        die("Không thể chuẩn bị câu truy vấn - Select");
    }
}

// Hàm SelectAll CSDL
function selectAll($table)
{
    // $GLOBALS là array chứa tất cả biến global trong PHP.
    $conn = $GLOBALS['conn'];
    $sql = "SELECT * FROM $table";
    $res = mysqli_query($conn, $sql);
    // Trả về mysqli_result object hoặc false nếu lỗi
    return $res;
}

// Hàm Update CSDL
function update($sql, $values, $datatypes)
{
    $conn = $GLOBALS['conn'];
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, $datatypes, ...$values);
        if (mysqli_stmt_execute($stmt)) {
            $res = mysqli_stmt_affected_rows($stmt);
            // Trả về số dòng bị ảnh hưởng (1: có thay đổi, 0: ko thay đổi)

            mysqli_stmt_close($stmt);
            return $res;
            // Trả kết quả về cho nơi gọi (JavaScript nhận được 1 hay 0).
        } else {
            mysqli_stmt_close($stmt);
            die("Không thể thực thi câu truy vấn - Update");
        }
    } else {
        die("Không thể chuẩn bị câu truy vấn - Update");
    }
}

// Hàm Insert CSDL
function insert($sql, $values, $datatypes)
{
    $conn = $GLOBALS['conn'];
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, $datatypes, ...$values);
        if (mysqli_stmt_execute($stmt)) {
            $res = mysqli_stmt_affected_rows($stmt);
            // Trả về số dòng bị ảnh hưởng (1: có thay đổi, 0: ko thay đổi)
            mysqli_stmt_close($stmt);
            return $res;
        } else {
            mysqli_stmt_close($stmt);
            die("Không thể thực thi câu truy vấn - Insert");
        }
    } else {
        die("Không thể chuẩn bị câu truy vấn - Insert");
    }
}

// Hàm xóa dữ liệu
function delete($sql, $values, $datatypes)
{
    $conn = $GLOBALS['conn'];
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, $datatypes, ...$values);
        if (mysqli_stmt_execute($stmt)) {   // True nếu thành công, false nếu lỗi
            $res = mysqli_stmt_affected_rows($stmt);
            // Trả về số dòng bị ảnh hưởng (1: có thay đổi, 0: ko thay đổi)

            mysqli_stmt_close($stmt); // Đóng tài nguyên, giải phóng bộ nhớ
            return $res;
        } else {
            mysqli_stmt_close($stmt);
            die("Không thể thực thi câu truy vấn - DELETE");
        }
    } else {
        die("Không thể chuẩn bị câu truy vấn - DELETE");
    }
}

?>