<?php
require('inc/essentials.php');
adminLogin();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Carousel</title>
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
    </style>
</head>

<body class="bg-light">

    <?php require('inc/header.php'); ?>
    <!-- Phần setting -->
    <div class="container-fluid" id="main-content">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden">
                <h3 class="mb-4">TRÌNH CHIẾU ẢNH (CAROUSEL)</h3>

                <!-- Phần Carousel settings -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h5 class="card-title m-0">Hình ảnh</h5>
                            <button type="button" class="btn btn-dark shadow-none btn-sm" data-bs-toggle="modal"
                                data-bs-target="#carousel-s">
                                <i class="bi bi-plus-square"></i> Thêm
                            </button>
                        </div>

                        <!-- Các thẻ member -->
                        <div class="row" id="carousel-data">
                            <!-- Xử lý bằng hàm get_carousel() -->
                        </div>
                    </div>
                </div>

                <!-- Carousel modal-->
                <div class="modal fade" id="carousel-s" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1"
                    aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <form id="carousel_s_form">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Thêm hình ảnh</h5>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Hình ảnh</label>
                                        <input type="file" name="carousel_picture" id="carousel_picture_inp"
                                            accept=".jpg, .png, .webp, .jpeg" class="form-control shadow-none" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" onclick="carousel_picture.value = ''"
                                        class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn custome-bg text-white shadow-none">Submit</button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>


            </div>
        </div>
    </div>

    <?php require('inc/scripts.php'); ?>
    <script src="scripts/carousel.js"></script>

</body>

</html>