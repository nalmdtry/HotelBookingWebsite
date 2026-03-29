let feature_s_form = document.getElementById('feature_s_form');
let facility_s_form = document.getElementById('facility_s_form');

// Khi ấn submit form thì thực hiện hàm bên trong
feature_s_form.addEventListener('submit', function (e) {
    e.preventDefault();     // Ngăn hành động mặc định của trình duyệt (reload trang)
    add_feature();          // Gọi hàm để thêm các tính năng
});

// Hàm thêm các tính năng mới
function add_feature() {
    let data = new FormData();      // FormData là một interface giúp đóng gói dữ liệu dạng key/value giống như form HTML khi gửi bằng multipart/form-data. Dùng được cho gửi file (image) hoặc dữ liệu text.
    data.append('name', feature_s_form.elements['feature_name'].value);     // Thêm một cặp key/value vào FormData
    data.append('add_feature', '');

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/features_facilities_crud.php", true);

    xhr.onload = function () {
        var myModal = document.getElementById('feature-s');
        var modal = bootstrap.Modal.getInstance(myModal);
        modal.hide();

        if (this.responseText == 1) {
            alert('success', 'Đã thêm tính năng mới!');
            feature_s_form.elements['feature_name'].value = '';
            get_features();
        } else {
            alert('error', 'Hành động thất bại!')
        }
    }

    xhr.send(data);
}

// Hàm lấy danh sách tính năng từ sever và hiển thị lên trang
function get_features() {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/features_facilities_crud.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        document.getElementById('features-data').innerHTML = this.responseText;
    }

    xhr.send('get_features');
}

// Hàm xóa tính năng
function rem_feature(val) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/features_facilities_crud.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        console.log(this.responseText);
        if (this.responseText == 1) {
            alert('success', 'Đã xóa tính năng!');
            get_features();
        } else if (this.responseText == 'room_added') {
            alert('error', 'Tính năng đã được thêm vào phòng, không thể xóa!');
        } else {
            alert('error', 'Hành động thất bại!');
        }
    }

    xhr.send('rem_feature=' + val);
}

// Khi ấn submit form thì thực hiện hàm bên trong
facility_s_form.addEventListener('submit', function (e) {
    e.preventDefault();     // Ngăn hành động mặc định của trình duyệt (reload trang)
    add_facility();         // Gọi hàm để thêm các tiện nghi
});

// Hàm thêm các tiện nghi mới
function add_facility() {
    let data = new FormData();
    data.append('name', facility_s_form.elements['facility_name'].value);
    data.append('icon', facility_s_form.elements['facility_icon'].files[0]);
    data.append('desc', facility_s_form.elements['facility_desc'].value);
    data.append('add_facility', '');
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/features_facilities_crud.php", true);

    xhr.onload = function () {
        var myModal = document.getElementById('facility-s');
        var modal = bootstrap.Modal.getInstance(myModal);
        modal.hide();

        if (this.responseText == 'inv_img') {
            alert('error', 'Chỉ cho phép hình ảnh SVG!');
        } else if (this.responseText == 'inv_size') {
            alert('error', 'Hình ảnh phải nhỏ hơn 1MB!');
        } else if (this.responseText == 'upd_failed') {
            alert('error', 'Tải hình ảnh không thành công!');
        } else {
            alert('success', 'Đã thêm tiện nghi mới');
            facility_s_form.reset();
            get_facilities();
        }
    }

    xhr.send(data);
}

// Hàm lấy danh sáchtiện nghi từ sever và hiển thị lên trang
function get_facilities() {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/features_facilities_crud.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        document.getElementById('facilities-data').innerHTML = this.responseText;
    }

    xhr.send('get_facilities');
}

// Hàm xóa tiện nghi
function rem_facility(val) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/features_facilities_crud.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        if (this.responseText == 1) {
            alert('success', 'Đã xóa tiện nghi!');
            get_facilities();
        } else if (this.responseText == 'room_added') {
            alert('error', 'Tiện nghi đã được thêm vào phòng, không thể xóa!');
        } else {
            alert('error', 'Hành động thất bại!');
        }
    }

    xhr.send('rem_facility=' + val);
}

// Khi toàn bộ trang web đã được tải hoàn chỉnh thì thực thi các hàm bên trong
window.onload = function () {
    get_features();
    get_facilities();
}
