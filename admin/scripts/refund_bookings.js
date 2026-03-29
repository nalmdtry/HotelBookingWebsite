// Hàm hiển thị các đơn đặt phòng
function get_bookings() {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/refund_bookings.php", true);
    xhr.setRequestHeader('Content-Type',
        'application/x-www-form-urlencoded'); // Dạng key1=value1&key2=value2 giống gửi form truyền thống

    // Khi nhận dc phản hồi từ sever
    xhr.onload = function () {
        document.getElementById('refundBookings-data').innerHTML = this.responseText; // Gán nội dung HTML trả về từ sever
    }

    xhr.send('get_bookings'); // Gửi yêu cầu HTTP POST tới sever
}


// Hàm hoàn tiền đơn đặt phòng khi ấn vào button refund
function refund_booking(id) {
    if (confirm("Hoàn tiền cho đơn đặt phòng này?")) {
        let data = new FormData();
        data.append('booking_id', id);
        data.append('refund_booking', '');

        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/refund_bookings.php", true);

        xhr.onload = function () {
            if (this.responseText == 1) {
                alert('success', 'Tiền đã được hoàn lại!');
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
    xhr.open("POST", "ajax/refund_bookings.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        console.log(this.responseText);
        document.getElementById('refundBookings-data').innerHTML = this.responseText;
    }

    xhr.send('search_booking=' + value);
}

// Khi toàn bộ trang web đã được tải hoàn chỉnh thì thực thi các hàm bên trong
window.onload = function () {
    get_bookings();
}