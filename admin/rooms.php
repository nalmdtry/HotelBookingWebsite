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
    <title>Admin Panel - Rooms</title>
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
                <h3 class="mb-4">PHÒNG</h3>

                <!-- Phần rooms setting -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">

                        <div class="text-end mb-4">
                            <button type="button" class="btn btn-dark shadow-none btn-sm" data-bs-toggle="modal"
                                data-bs-target="#add-room">
                                <i class="bi bi-plus-square"></i> Thêm
                            </button>
                        </div>

                        <div class="table-responsive-lg" style="height: 450px; overflow-y: scroll;">
                            <table class="table table-hover border text-center">
                                <!-- Phần header table -->
                                <thead>
                                    <tr class="bg-dark text-light">
                                        <th scope="col">STT</th>
                                        <th scope="col">Tên</th>
                                        <th scope="col">Diện tích</th>
                                        <th scope="col">Khách</th>
                                        <th scope="col">Giá</th>
                                        <th scope="col">Số lượng</th>
                                        <th scope="col">Trạng thái</th>
                                        <th scope="col">Hành động</th>
                                    </tr>
                                </thead>

                                <!-- Phần body table -->
                                <tbody id="room-data">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal thêm phòng-->
    <div class="modal fade" id="add-room" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form id="add_room_form" autocomplete="false">
                <div class="modal-content">
                    <!-- Phần header modal -->
                    <div class="modal-header">
                        <h5 class="modal-title">Thêm phòng</h5>
                    </div>

                    <!-- Phần body modal -->
                    <div class="modal-body">
                        <div class="row">
                            <!-- Phần hiển thị các input nhập liệu chung -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Tên</label>
                                <input type="text" name="name" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Diện tích</label>
                                <input type="number" min="1" name="area" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Giá</label>
                                <input type="number" min="1" name="price" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Số lượng</label>
                                <input type="number" min="1" name="quantity" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Số người lớn (tối đa)</label>
                                <input type="number" min="1" name="adult" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Số trẻ em (tối đa)</label>
                                <input type="number" min="1" name="children" class="form-control shadow-none" required>
                            </div>

                            <!-- Phần hiển thị checkbox tính năng -->
                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold">Tính năng</label>
                                <div class="row">
                                    <?php
                                    $res = selectAll('features');
                                    while ($opt = mysqli_fetch_assoc($res)) {
                                        echo <<<data
                                        <div class="col-md-3 mb-1">
                                            <label>
                                                <input type="checkbox" name="features" value="{$opt['id']}" class="form-check-input shadow-none">
                                                 {$opt['name']}
                                                </input>
                                            </label>
                                        </div>
                                    data;

                                    }
                                    ?>
                                </div>
                            </div>

                            <!-- Phần hiển thị checkbox tiện nghi -->
                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold">Tiện nghi</label>
                                <div class="row">
                                    <?php
                                    $res = selectAll('facilities');
                                    while ($opt = mysqli_fetch_assoc($res)) {
                                        echo <<<data
                                        <div class="col-md-3 mb-1">
                                            <label>
                                                <input type="checkbox" name="facilities" value="{$opt['id']}" class="form-check-input shadow-none">
                                                 {$opt['name']}
                                                </input>
                                            </label>
                                        </div>
                                    data;

                                    }
                                    ?>
                                </div>
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold">Mô tả</label>
                                <textarea name="desc" rows="4" class="form-control shadow-none" required></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Phần footer modal -->
                    <div class="modal-footer">
                        <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal">CANCEL</button>
                        <button type="submit" class="btn custome-bg text-white shadow-none">SUBMIT</button>
                    </div>
                </div>
            </form>

        </div>
    </div>

    <!-- Modal chỉnh sửa phòng-->
    <div class="modal fade" id="edit-room" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form id="edit_room_form" autocomplete="false">
                <div class="modal-content">
                    <!-- Phần header modal -->
                    <div class="modal-header">
                        <h5 class="modal-title">Chỉnh sửa</h5>
                    </div>

                    <!-- Phần body modal -->
                    <div class="modal-body">
                        <div class="row">
                            <!-- Phần hiển thị các input nhập liệu chung -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Tên</label>
                                <input type="text" name="name" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Diện tích</label>
                                <input type="number" min="1" name="area" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Giá</label>
                                <input type="number" min="1" name="price" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Số lượng</label>
                                <input type="number" min="1" name="quantity" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Số người lớn (tối đa)</label>
                                <input type="number" min="1" name="adult" class="form-control shadow-none" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Số trẻ em (tối đa)</label>
                                <input type="number" min="1" name="children" class="form-control shadow-none" required>
                            </div>

                            <!-- Phần hiển thị checkbox tính năng -->
                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold">Tính năng</label>
                                <div class="row">
                                    <?php
                                    $res = selectAll('features');
                                    while ($opt = mysqli_fetch_assoc($res)) {
                                        echo <<<data
                                        <div class="col-md-3 mb-1">
                                            <label>
                                                <input type="checkbox" name="features" value="{$opt['id']}" class="form-check-input shadow-none">
                                                 {$opt['name']}
                                                </input>
                                            </label>
                                        </div>
                                    data;

                                    }
                                    ?>
                                </div>
                            </div>

                            <!-- Phần hiển thị checkbox tiện nghi -->
                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold">Tiện nghi</label>
                                <div class="row">
                                    <?php
                                    $res = selectAll('facilities');
                                    while ($opt = mysqli_fetch_assoc($res)) {
                                        echo <<<data
                                        <div class="col-md-3 mb-1">
                                            <label>
                                                <input type="checkbox" name="facilities" value="{$opt['id']}" class="form-check-input shadow-none">
                                                 {$opt['name']}
                                                </input>
                                            </label>
                                        </div>
                                    data;

                                    }
                                    ?>
                                </div>
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label fw-bold">Mô tả</label>
                                <textarea name="desc" rows="4" class="form-control shadow-none" required></textarea>
                            </div>

                            <input type="hidden" name="room_id">
                        </div>
                    </div>

                    <!-- Phần footer modal -->
                    <div class="modal-footer">
                        <button type="reset" class="btn btn-secondary" data-bs-dismiss="modal">CANCEL</button>
                        <button type="submit" class="btn custome-bg text-white shadow-none">SUBMIT</button>
                    </div>
                </div>
            </form>

        </div>
    </div>

    <!-- Modal quản lý hình ảnh phòng -->
    <div class="modal fade" id="room-images" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <!-- Phần header modal -->
                <div class="modal-header">
                    <h5 class="modal-title">Tên phòng</h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <!-- Phần body modal -->
                <div class="modal-body">
                    <!-- Phần hiển thị cấu hình alert theo id trên modal tải hình ảnh phòng -->
                    <div id="image-alert"></div>

                    <!-- Phần hiển thị form chọn hình ảnh tải lên -->
                    <div class="border-bottom border-3 pb-3 mb-3">
                        <form id="add_image_form">
                            <label class="form-label fw-bold">Thêm hình ảnh</label>
                            <input type="file" name="image" accept=".jpg, .png, .webp, .jpeg"
                                class="form-control shadow-none mb-3" required>
                            <button type="submit" class="btn custome-bg text-white shadow-none">THÊM</button>
                            <input type="hidden" name="room_id">
                        </form>
                    </div>

                    <!-- Phần hiển thị thông tin hình ảnh tải lên -->
                    <div class="table-responsive-lg" style="height: 350px; overflow-y: scroll;">
                        <table class="table table-hover border text-center">
                            <!-- Phần header table -->
                            <thead>
                                <tr class="bg-dark text-light sticky-top">
                                    <th scope="col" width="60%">Hình ảnh</th>
                                    <th scope="col">Thumb</th>
                                    <th scope="col">Xóa</th>
                                </tr>
                            </thead>

                            <!-- Phần body table -->
                            <tbody id="room-image-data">
                                <!-- Xử lý dữ liệu lên table từ db bằng JS -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <?php require('inc/scripts.php'); ?>
    <script src="scripts/rooms.js"></script>

</body>

</html>