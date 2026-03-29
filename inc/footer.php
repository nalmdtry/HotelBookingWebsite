<style>
.custome-alert {
    position: fixed;
    top: 80px;
    right: 25px;
    z-index: 1100;
}
</style>
<!-- Footer -->
<div class="container-fluid bg-white mt-5">
    <div class="row">
        <div class="col-lg-4 p-4">
            <h3 class="h-font fw-bold fs-3 mb-2">
                <?php echo $settings_r['site_title']; ?>
            </h3>
            <p>
                <?php echo $settings_r['site_about']; ?>
            </p>
        </div>
        <div class="col-lg-4 p-4">
            <h5 class="mb-3">Liên kết</h5>
            <a href="index.php" class="d-inline-block mb-2 text-dark text-decoration-none">Trang chủ</a><br>
            <a href="rooms.php" class="d-inline-block mb-2 text-dark text-decoration-none">Phòng</a><br>
            <a href="facilities.php" class="d-inline-block mb-2 text-dark text-decoration-none">Tiện nghi</a><br>
            <a href="contact.php" class="d-inline-block mb-2 text-dark text-decoration-none">Liên hệ</a><br>
            <a href="about.php" class="d-inline-block mb-2 text-dark text-decoration-none">Giới thiệu</a>
        </div>
        <div class="col-lg-4 p-4">
            <h5 class="mb-3">Kết nối với chúng tôi</h5>
            <a href="<?php echo $contact_r['fb'] ?>" target="_blank"
                class="d-inline-block text-dark text-decoration-none mb-2">
                <i class="bi bi-facebook me-1"></i> Facebook
            </a><br>
            <a href="<?php echo $contact_r['insta'] ?>" target="_blank"
                class="d-inline-block text-dark text-decoration-none mb-2">
                <i class="bi bi-instagram me-1"></i> Instagram
            </a><br>
            <?php
            $tw = $contact_r['tw'];
            if ($tw != '') {
                echo <<<data
                    <a href="$tw" target="_blank" class="d-inline-block text-dark text-decoration-none">
                        <i class="bi bi-twitter me-1"></i> Twitter
                    </a>
                data;
            }
            ?>

        </div>
    </div>
</div>

<h6 class="text-center bg-dark text-white p-3 m-0">Thiết kế và phát triển bởi Nlam WEBDEV</h6>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
</script>

<script>
// Hàm js kiểm tra login trước khi book
function checkLoginToBook(status, room_id) {
    if (status == 1) { // Nếu người dùng đã đăng nhập (login=1)
        window.location.href = 'confirm_booking.php?id=' + room_id;
    } else {
        alert('error', 'Vui lòng đăng nhập để đặt phòng!');
    }
}

// Hàm hiển thị hộp thoại alert tùy chỉnh
function alert(type, msg, position = 'body') {
    let bs_class = (type == 'success') ? 'alert-success' : 'alert-danger';
    // Tạo thẻ div mới(chưa thêm vào HTML)
    let element = document.createElement('div');
    // Gán nội dung HTML cho div
    element.innerHTML = `
            <div class="alert ${bs_class} alert-dismissible fade show" role="alert">
                <strong class="me-3">${msg}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;

    // Kiểm tra position có phải là chuỗi body không
    if (position == 'body') {
        // Thêm <div> chứa alert vào cuối thẻ <body>, hiển thị ra màn hình.
        document.body.append(element);
        // Gán class custome-alert cho element để định dạng kiểu dáng hiển thị alert
        element.classList.add('custome-alert');
    } else {
        // Tìm phần tử HTML có ID trùng với giá trị của biến position, thêm element mới vào bên trong phần tử đó
        document.getElementById(position).appendChild(element);
    }

    // Hẹn giờ để xóa alert sau 2s
    setTimeout(remAlert, 3000); // Sau 2000ms (2s) sẽ gọi hàm remAlert()
}

// Hàm xóa alert
function remAlert() {
    document.getElementsByClassName('alert')[0].remove(); // Xóa phần tử có class alert đầu tiên trong trang
}

// Hàm tự động highlight menu tương ứng với trang hiện tại
function setActive() {
    let navbar = document.getElementById('nav-bar'); // DOM method trả về phần tử có id="nav-bar"
    let a_tags = navbar.getElementsByTagName(
        'a'); // Trả về HTMLCollection (live collection) gồm tất cả phần tử <a> bên trong navbar
    // console.log(a_tags); (Thanh nav-bar trả về 6 phần tử <a>)

    console.log(a_tags[0]); // Thẻ <a class ="nav-...." href ="index.php"></a>
    console.log(a_tags[0].href); // http://localhost/index.php
    console.log(a_tags[0].href.split('/')); // Tách chuỗi trên theo dấu '/' thành mảng con 4 phần tử
    console.log(a_tags[0].href.split('/').pop()); // Lấy phần tử con cuối cùng (index.php)
    console.log(a_tags[0].href.split('/').pop().split('.')[0]); // Tách theo dấu '.' thành ['index', 'php'], lấy index

    for (i = 0; i < a_tags.length; i++) { //(0 < 6)

        let file = a_tags[i].href.split('/').pop();
        let file_name = file.split('.')[0];

        if (document.location.href.indexOf(file_name) >=
            0) { // kiểm tra chuỗi file_name có xuất hiện trong URL hiện tại (absolute) của trang không.
            // Trả về số dương nếu có, -1 nếu không có

            a_tags[i].classList.add(
                'active'); // Thêm class active vào thẻ <a> đó, Bootstrap style .nav-link.active sẽ highlight menu.
        }
    }
}

// Gọi lại hàm để chạy logic highlight menu.
setActive();



// Phần js xử lí đăng kí
let register_form = document.getElementById('register_form');
register_form.addEventListener('submit', function(e) {
    e.preventDefault(); // Ngăn chặn hành động mặc định của trình duyệt (load lại trang)
    user_register();
});

function user_register() {
    // Tạo đối tượng FormData gửi dữ liệu biểu mẫu HTML (bao gồm cả tệp) đến máy chủ thông qua AJAX
    let data = new FormData();
    // Thêm dữ liệu vào từng đối tượng biểu mẫu
    data.append('name', register_form.elements['name'].value);
    data.append('email', register_form.elements['email'].value);
    data.append('phonenum', register_form.elements['phonenum'].value);
    data.append('address', register_form.elements['address'].value);
    data.append('pincode', register_form.elements['pincode'].value);
    data.append('dob', register_form.elements['dob'].value);
    data.append('pass', register_form.elements['pass'].value);
    data.append('cpass', register_form.elements['cpass'].value);
    data.append('profile', register_form.elements['profile'].files[0]);
    data.append('register', '');

    var myModal = document.getElementById('registerModal');
    var modal = bootstrap.Modal.getOrCreateInstance(myModal);

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/login_register.php", true);

    xhr.onload = function() {
        if (this.responseText == 'pass_mismatch') {
            alert('error', 'Mật khẩu không khớp!');
        } else if (this.responseText == 'email_already') {
            alert('error', 'Email đã được đăng ký!');
        } else if (this.responseText == 'phone_already') {
            alert('error', 'Số điện thoại đã được đăng ký!');
        } else if (this.responseText == 'inv_img') {
            alert('error', 'Chỉ cho phép hình ảnh JPG, WEBP và PNG!');
        } else if (this.responseText == 'upd_failed') {
            alert('error', 'Tải hình ảnh lên không thành công!');
        } else if (this.responseText == 'mail_failed') {
            alert('error', 'Không thể gửi email xác nhận!');
        } else if (this.responseText == 'insert_failed') {
            alert('error', 'Đăng ký tài khoản không thành công!');
        } else {
            alert('success', 'Đăng ký tài khoản thành công. Đã gửi liên kết xác nhận đến email!');
            register_form.reset();
        }
        modal.hide();
    }

    xhr.send(data);
}




// Phần js xử lý đăng nhập
let login_form = document.getElementById('login_form');

login_form.addEventListener('submit', function(e) {
    e.preventDefault();
    user_login();
});

function user_login() {
    let data = new FormData();
    data.append('email_mob', login_form.elements['email_mob'].value);
    data.append('pass', login_form.elements['pass'].value);
    data.append('login', '');

    var myModal = document.getElementById('loginModal');
    var modal = bootstrap.Modal.getOrCreateInstance(myModal);


    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/login_register.php", true);

    xhr.onload = function() {
        console.log(this.responseText);
        if (this.responseText == 'inv_email_mob') {
            alert('error', 'Email không hợp lệ!');
        } else if (this.responseText == 'not_verified') {
            alert('error', 'Email chưa được xác minh!');
        } else if (this.responseText == 'inactive') {
            alert('error', 'Tài khoản đã bị tạm ngưng! Vui lòng liên hệ Admin!');
        } else if (this.responseText == 'invalid_pass') {
            alert('error', 'Mật khẩu không đúng!');
        } else {
            let fileurl = window.location.href.split('/').pop().split('?')
                .shift(); // vd: room_details.php?id=2, lấy room_details.php
            if (fileurl == 'room_details.php') {
                window.location = window.location.href; // url đầy đủ     
            } else {
                window.location = window.location.pathname; // url ko bao gồm ?...
            }


        }
        modal.hide();
    }

    xhr.send(data);
}



// Phần js xử lý quên mật khẩu
let forgot_form = document.getElementById('forgot_form');
forgot_form.addEventListener('submit', function(e) {
    e.preventDefault();
    forgot_pass();
});

// Hàm quên mật khẩu
function forgot_pass() {
    let data = new FormData();
    data.append('email', forgot_form.elements['email'].value);
    data.append('forgot_pass', '');

    var myModal = document.getElementById('forgotModal');
    var modal = bootstrap.Modal.getOrCreateInstance(myModal);


    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/login_register.php", true);

    xhr.onload = function() {
        console.log(this.responseText);
        if (this.responseText == 'inv_email') {
            alert('error', 'Email không hợp lệ!');
        } else if (this.responseText == 'not_verified') {
            alert('error', 'Email chưa được xác minh!');
        } else if (this.responseText == 'inactive') {
            alert('error', 'Tài khoản đã bị tạm ngưng! Vui lòng liên hệ Admin!');
        } else if (this.responseText == 'mail_failed') {
            alert('error', 'Không thể gửi email!');
        } else if (this.responseText == 'upd_failed') {
            alert('error', 'Khôi phục tài khoản không thành công!');
        } else {
            alert('success', 'Liên kết đặt lại mật khẩu đã được gửi đến email!');
            forgot_form.reset();
        }
        modal.hide();
    }

    xhr.send(data);
}
</script>