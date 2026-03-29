// Hàm hiển thị các đơn đặt phòng mới
function get_bookings() {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/new_bookings.php", true);
    xhr.setRequestHeader('Content-Type',
        'application/x-www-form-urlencoded'); // Dạng key1=value1&key2=value2 giống gửi form truyền thống

    // Khi nhận dc phản hồi từ sever
    xhr.onload = function () {
        document.getElementById('newbookings-data').innerHTML = this.responseText; // Gán nội dung HTML trả về từ sever
    }

    xhr.send('get_bookings'); // Gửi yêu cầu HTTP POST tới sever
}


let assign_room_form = document.getElementById('assign_room_form');

// Hàm gán booking_id vào trường input hidden
function assign_room(id) {
    assign_room_form.elements['booking_id'].value = id;
}

// Khi ấn sumbit form thì thực hiện hàm bên trong
assign_room_form.addEventListener('submit', function (e) {
    e.preventDefault();

    // Gói dữ liệu qua FormData
    let data = new FormData();
    data.append('room_no', assign_room_form.elements['room_no'].value);
    data.append('booking_id', assign_room_form.elements['booking_id'].value);
    data.append('assign_room', '');

    // Tạo Request POST gửi tới ajax xử lý
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/new_bookings.php", true);

    // Khi nhận được phản hồi từ sever
    xhr.onload = function () {
        var myModal = document.getElementById('assign-room');
        var modal = bootstrap.Modal.getInstance(myModal);

        if (this.responseText == 'room_occupied') {
            alert('error', 'Phòng này hiện đang có khách ở! Vui lòng chọn số phòng khác.');
        } else if (this.responseText == 1) {
            alert('success', 'Đã phân bổ số phòng, Hoàn tất việc đặt phòng!');
            assign_room_form.reset();
            get_bookings();
        } else {
            alert('error', 'Hành động thất bại!');
        }
        modal.hide();
    }

    // Gửi yêu cầu HTTP POST tới sever
    xhr.send(data);
});

// Hàm hủy đơn đặt phòng khi ấn vào button cancel booking
function cancel_booking(id) {
    if (confirm("Bạn có chắc chắn muốn hủy đơn đặt phòng này không")) {
        let data = new FormData();
        data.append('booking_id', id);
        data.append('cancel_booking', '');

        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/new_bookings.php", true);

        xhr.onload = function () {
            if (this.responseText == 1) {
                alert('success', 'Đặt chỗ đã bị hủy!');
                get_bookings();
            } else {
                alert('error', 'Hành động thất bại!');
            }
        }

        xhr.send(data);
    }

}

// Hàm tìm kiếm đơn đặt phòng
function search_booking(value) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/new_bookings.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        console.log(this.responseText);
        document.getElementById('newbookings-data').innerHTML = this.responseText;
    }

    xhr.send('search_booking=' + value);
}

// Khi toàn bộ trang web đã được tải hoàn chỉnh thì thực thi các hàm bên trong
window.onload = function () {
    get_bookings();
}