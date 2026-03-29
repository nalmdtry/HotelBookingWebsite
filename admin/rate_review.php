<?php
require('inc/essentials.php');
require('inc/db.config.php');
adminLogin();

// Xử lí phần seen message (1: đã xem, 0: chưa xem)
if (isset($_GET['seen'])) {     // Kiểm tra param seen có tồn tại trong URL hay không (ví dụ ?seen=1 hoặc ?seen=all)
    $frm_data = filteration($_GET);
    if ($frm_data['seen'] == 'all') {   // $frm_data['seen'] là id (vd ?seen=1)
        $q = "UPDATE rating_review SET seen = ?";
        $values = [1];
        $datatypes = 'i';
        if (update($q, $values, $datatypes) == 1) { // 1 -> true, 0 -> false
            alert('success', 'Đã đánh dấu tất cả là đã đọc!');
        } else {
            alert('error', 'Hành động thất bại!');
        }
    } else {
        $q = "UPDATE rating_review SET seen = ? WHERE sr_no = ?";
        $values = [1, $frm_data['seen']];
        $datatypes = 'ii';
        if (update($q, $values, $datatypes) == 1) {
            alert('success', 'Đã đánh dấu là đã đọc!');
        } else {
            alert('error', 'Hành động thất bại!');
        }

    }
}

// Xử lí phần delete message
if (isset($_GET['del'])) {
    $frm_data = filteration($_GET);
    if ($frm_data['del'] == 'all') {
        $q = "DELETE FROM rating_review";
        if (mysqli_query($conn, $q)) {  // mysqli_query trả về chỉ trả về true hoặc false (k trả về 1 hoặc 0) khi câu truy vấn là INSERT, UPDATE, DELETE hoặc các câu lệnh không trả về tập kết quả.
            alert('success', 'Tất cả dữ liệu đã bị xóa!');
        } else {
            alert('error', 'Hành động thất bại!');
        }
    } else {
        $q = "DELETE FROM rating_review WHERE sr_no = ?";
        $values = [$frm_data['del']];   // $frm_data['del'] là id (vd ?del=1)
        $datatypes = 'i';
        if (delete($q, $values, $datatypes) == 1) {
            alert('success', 'Dữ liệu đã bị xóa!');
        } else {
            alert('error', 'Hành động thất bại!');
        }

    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Ratings and Reviews</title>
    <?php require('inc/links.php'); ?>
    <style>
    .custome-alert {
        position: fixed;
        top: 80px;
        right: 25px;
    }

    #dashboard-menu {
        position: fixed;
        height: 100%;
        z-index: 11;
    }

    @media screen and (max-width: 991px) {
        #dashboard-menu {
            height: auto;
            width: 100%;
        }
    }

    /* width */
    ::-webkit-scrollbar {
        width: 10px;
    }

    /* Track */
    ::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    /* Handle */
    ::-webkit-scrollbar-thumb {
        background: rgb(36, 36, 36);
    }

    /* Handle on hover */
    ::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
    </style>
</head>

<body class="bg-light">

    <?php require('inc/header.php'); ?>

    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden">
                <h3 class="mb-4">XẾP HẠNG VÀ ĐÁNH GIÁ</h3>

                <!-- Phần Carousel settings -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <div class="text-end mb-4">
                            <a href="?seen=all" class="btn btn-sm btn-primary rounded-pill shadow-none"><i
                                    class="bi bi-check-all"></i> Đánh dấu đã đọc tất
                                cả</a>
                            <a href="?del=all" class="btn btn-sm btn-danger rounded-pill shadow-none"><i
                                    class="bi bi-trash"></i> Xóa tất cả</a>
                        </div>

                        <div class="table-responsive-md">
                            <table class="table table-hover border">
                                <!-- Phần header table -->
                                <thead>
                                    <tr class="bg-dark text-light">
                                        <th scope="col">STT</th>
                                        <th scope="col">Tên phòng</th>
                                        <th scope="col">Tên người dùng</th>
                                        <th scope="col">Xếp hạng</th>
                                        <th scope="col" width="30%">Đánh giá</th>
                                        <th scope="col">Ngày gửi</th>
                                        <th scope="col">Hành động</th>
                                    </tr>
                                </thead>

                                <!-- Phần body table -->
                                <tbody>
                                    <?php
                                    // Truy vấn lấy all dữ liệu từ bảng rating_review, tên phòng trong bảng rooms và tên user trong bảng user_cred
                                    $q = "SELECT rr.*, uc.name AS uname, r.name AS rname FROM rating_review rr
                                        INNER JOIN user_cred uc ON rr.user_id = uc.id
                                        INNER JOIN rooms r ON rr.room_id = r.id
                                        ORDER BY sr_no DESC";
                                    $res = mysqli_query($conn, $q);
                                    $i = 1;
                                    while ($row = mysqli_fetch_assoc($res)) {
                                        // Định dạng date
                                        $date = date('d-m-Y', strtotime($row['datentime']));

                                        $seen = '';
                                        if ($row['seen'] != 1) {
                                            $seen = "<a href='?seen={$row['sr_no']}' class='btn btn-sm rounded-pill btn-primary mb-2'>Đánh dấu là đã đọc</a> <br>";
                                        }
                                        $seen .= "<a href='?del={$row['sr_no']}' class='btn btn-sm rounded-pill btn-danger'>Xóa</a>"; // .= nối chuối
                                        echo <<<data
                                            <tr>
                                                <td>$i</td> 
                                                <td>{$row['rname']}</td> 
                                                <td>{$row['uname']}</td> 
                                                <td>{$row['rating']}</td> 
                                                <td>{$row['review']}</td> 
                                                <td>$date</td> 
                                                <td>$seen</td> 
                                            </tr>
                                        data;
                                        $i++;
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require('inc/scripts.php'); ?>

</body>

</html>