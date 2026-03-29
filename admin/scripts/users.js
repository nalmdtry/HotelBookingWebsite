// Hàm hiển thị all người dùng
function get_users() {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/users_crud.php", true);
    xhr.setRequestHeader('Content-Type',
        'application/x-www-form-urlencoded'); // Dạng key1=value1&key2=value2 giống gửi form truyền thống

    // Khi nhận dc phản hồi từ sever
    xhr.onload = function () {
        document.getElementById('users-data').innerHTML = this.responseText; // Gán nội dung HTML trả về từ sever
    }

    xhr.send('get_users'); // Gửi yêu cầu HTTP POST tới sever
}


// Hàm chuyển đổi trạng thái nút status (1 hoạt động, 2 ko hoạt động) 
function toggle_status(id, val) {
    // Tạo đối tượng XMLHttpRequest để gửi request AJAX (không load lại trang)
    let xhr = new XMLHttpRequest();

    // Mở kết nối đến file PHP rooms_crud.php, gửi dữ liệu qua POST và gửi bất đồng bộ (k chặn giao diện)
    xhr.open("POST", "ajax/users_crud.php", true);

    // Thiết lập kiểu dữ liệu gửi đi dạng form truyền thống (key=value&key=value)
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    // Khi nhận xong phản hồi từ server, hàm này sẽ chạy
    xhr.onload = function () {
        if (this.responseText == 1) { // this.responseText → nội dung PHP trả về (1 hoặc 0)
            alert('success', 'Đã chuyển đổi trạng thái!')
            get_users();    // Gọi hàm hiển thị all user
        } else {
            alert('error', 'Hành động thất bại!')
        }
    }

    // Gửi dữ liệu POST đến server
    xhr.send('toggle_status=' + id + '&value=' + val);
}


// Hàm xóa user
function remove_user(user_id) {
    // Hiển thị hộp thoại xác nhận xuất hiện trên trình duyệt (OK=true, cancel=false)
    if (confirm("Bạn có chắc chắn muốn xóa người dùng này không?")) {
        let data = new FormData();
        data.append('user_id', user_id);
        data.append('remove_user', '');

        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/users_crud.php", true);

        xhr.onload = function () {
            if (this.responseText == 1) {
                alert('success', 'Đã xóa người dùng thành công!');
                get_users();
            } else {
                alert('error', 'Xóa người dùng thất bại!');
            }
        }

        // Gửi dữ liệu POST đến server
        xhr.send(data);
    }
}

// Hàm tìm kiếm người dùng bằng username
function search_user(username) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/users_crud.php", true);
    xhr.setRequestHeader('Content-Type',
        'application/x-www-form-urlencoded'); // Dạng key1=value1&key2=value2 giống gửi form truyền thống

    // Khi nhận dc phản hồi từ sever
    xhr.onload = function () {
        document.getElementById('users-data').innerHTML = this.responseText; // Gán nội dung HTML trả về từ sever
    }

    xhr.send('search_user&name=' + username); // Gửi yêu cầu HTTP POST tới sever
}

// Khi toàn bộ trang web đã được tải hoàn chỉnh thì thực thi các hàm bên trong
window.onload = function () {
    get_users();
}