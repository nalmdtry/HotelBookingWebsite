<?php
require('inc/essentials.php');
require('inc/db.config.php');
adminLogin();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Refund Bookings</title>
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
                <h3 class="mb-4">ĐƠN HOÀN TIỀN</h3>

                <!-- Phần rooms setting -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">

                        <div class="text-end mb-4">
                            <!-- Phần nhập để tìm kiếm, mỗi lần gõ chữ thì gọi hàm search_booking() -->
                            <input type="text" oninput="search_booking(this.value)"
                                class="form-control shadow-none w-25 ms-auto" placeholder="Nhập để tìm kiếm">
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover border" style="min-width: 1200px;">
                                <!-- Phần header table -->
                                <thead>
                                    <tr class="bg-dark text-light">
                                        <th scope="col">STT</th>
                                        <th scope="col">Thông tin khách hàng</th>
                                        <th scope="col">Thông tin phòng</th>
                                        <th scope="col">Số tiền hoàn lại</th>
                                        <th scope="col">Hành động</th>
                                    </tr>
                                </thead>

                                <!-- Phần body table -->
                                <tbody id="refundBookings-data">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require('inc/scripts.php'); ?>
    <script src="scripts/refund_bookings.js"></script>

</body>

</html>