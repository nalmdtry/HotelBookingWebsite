let carousel_s_form = document.getElementById('carousel_s_form');
let carousel_picture_inp = document.getElementById('carousel_picture_inp');

// Thêm 1 trình xử lý sự kiện 'submit' cho carousel_s_form
carousel_s_form.addEventListener('submit', function (e) {
    e.preventDefault(); // Ngăn trang reload
    add_image();
});

// Hàm thêm ảnh carousel
function add_image() {
    let data = new FormData(); // FormData: object trong JavaScript được sử dụng để tạo ra một tập hợp các cặp khóa/giá trị, 
    // tương tự như một biểu mẫu HTML, để gửi dữ liệu đi qua các yêu cầu HTTP
    // Với Content-Type theo định dạng multipart/form-data (kiểu mã hóa quan trọng cho việc gửi tệp)

    // $_FILES['picture']
    data.append('picture', carousel_picture_inp.files[0]); // files[0] là object File đầu tiên chứa .name (tên file), .type (MIME), .size (số bytes), và nội dung file.
    data.append('add_image', '');

    // Tạo một đối tượng XMLHttpRequest (XHR) để gửi request AJAX.
    let xhr = new XMLHttpRequest();

    // Cấu hình request trước khi gửi.
    xhr.open("POST", "ajax/carousel_crud.php", true);

    xhr.onload = function () {
        var myModal = document.getElementById('carousel-s');
        var modal = bootstrap.Modal.getInstance(myModal);
        modal.hide(); // ẩn modal khi submit xong

        if (this.responseText == 'inv_img') { // là chuỗi (string) server trả lại (body content), this là xhr
            alert('error', 'Chỉ cho phép hình ảnh JPG, WEBP hoặc PNG!');
        } else if (this.responseText == 'inv_size') {
            alert('error', 'Hình ảnh phải nhỏ hơn 2MB!');
        } else if (this.responseText == 'upd_failed') {
            alert('error', 'Tải hình ảnh lên không thành công!');
        } else {
            alert('success', 'Đã thêm hình ảnh mới!');

            carousel_picture_inp.value = '';
            get_carousel();
        }

    }
    // Gửi request đã cấu hình (POST đến ajax/carousel_crud.php) kèm FormData
    xhr.send(data);
}

// Hàm lấy danh sách carousel từ server và hiển thị lên trang
function get_carousel() {
    // Tạo một AJAX request mới (công cụ JS để gửi HTTP request không reload trang)
    let xhr = new XMLHttpRequest();

    // Mở kết nối tới file ajax/carousel_crud.php
    xhr.open("POST", "ajax/carousel_crud.php", true);

    // Cấu hình header dạng URL-encoded vd key=value&key2=value2
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    // Khi nhận được phản hồi từ sever
    xhr.onload = function () {
        document.getElementById('carousel-data').innerHTML = this.responseText;
    }

    // Gửi Request với POST = get_carousel
    xhr.send('get_carousel');
}

// Hàm xóa image
function rem_image(val) {   // val: id ảnh cần xóa
    // Tạo một đối tượng AJAX để gửi và nhận dữ liệu từ PHP.
    let xhr = new XMLHttpRequest();
    // Thiết lập method gửi đi là POST tới file carousel_crud.php để xử lý và gửi bất đồng bộ (ko chặn giao diện)
    xhr.open("POST", "ajax/carousel_crud.php", true);

    // Báo cho server biết dữ liệu gửi đi kiểu như form HTML bình thường (key=value&key2=value2)
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    // Khi nhận được phản hồi từ sever
    xhr.onload = function () {
        if (this.responseText == 1) {   // this.responseText là nội dung echo từ PHP, 1 nếu xóa thành công, 0 thất bại
            alert('success', 'Đã xóa hình ảnh!');

            // Gọi lại hàm load lại danh sách carousel để cập nhật UI ngay lập tức.
            get_carousel();
        } else {
            alert('error', 'Hành động thất bại!');
        }
    }

    // Gửi Request với POST = rem_image kèm id ảnh cần xóa
    xhr.send('rem_image=' + val);
}

// Khi trang tải xong, tự động gọi hàm get_carousel() 
// → hiển thị nội dung ngay lập tức.
window.onload = function () {
    get_carousel();
}