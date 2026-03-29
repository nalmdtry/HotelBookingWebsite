<?php
require('admin/inc/db.config.php');
require('admin/inc/essentials.php');

if (isset($_GET['email_confirmation'])) {
    $data = filteration($_GET);

    $query = select(
        "SELECT * FROM user_cred WHERE email = ? AND token = ? LIMIT 1",
        [$data['email'], $data['token']],
        'ii'
    );

    if (mysqli_num_rows($query) == 1) {
        $fetch = mysqli_fetch_assoc($query);

        if ($fetch['is_verified'] == 1) {
            echo "<script>alert('Email đã được xác minh!')</script>";
        } else {
            $update = update(
                "UPDATE user_cred SET is_verified = ? WHERE id = ?",
                [1, $fetch['id']],
                'ii'
            );
            if ($update) {
                echo "<script>alert('Xác minh email thành công!')</script>";
            } else {
                echo "<script>alert('Xác minh email không thành công!')</script>";
            }
            redirect('index.php');
        }
        redirect('index.php');
    } else {
        echo "<script>alert('Liên kết không hợp lệ!')</script>";
        redirect('index.php');
    }
}
?>