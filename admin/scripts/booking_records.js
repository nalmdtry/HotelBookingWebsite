// Hàm hiển thị các đơn đặt phòng kèm tìm kiếm và phân trang
function get_bookings(search = '', page = 1) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/booking_records.php", true);
    xhr.setRequestHeader('Content-Type',
        'application/x-www-form-urlencoded'); // Dạng key1=value1&key2=value2 giống gửi form truyền thống

    // Khi nhận dc phản hồi từ sever
    xhr.onload = function () {
        // Chuyển mảng json về đối tượng JS để xử lý dữ liệu
        let data = JSON.parse(this.responseText);
        document.getElementById('bookingRecords-data').innerHTML = data.table_data; // Gán nội dung HTML trả về từ sever
        document.getElementById('table_pagination').innerHTML = data.pagination;

    }

    xhr.send('get_bookings&search=' + search + '&page=' + page); // Gửi yêu cầu HTTP POST tới sever
}

// Hàm đổi trang khi ấn button next và prev (kèm giá trị của page hiện tại)
function change_page(page) {
    // Gọi hàm get_bookings truyền vào giá trị người dùng nhập và trang hiện tại
    get_bookings(document.getElementById('search_input').value, page);
}

// Hàm tạo hóa đơn pdf
function download(id) {
    window.location.href = 'generate_pdf.php?gen_pdf&id=' + id;
}



// Khi toàn bộ trang web đã được tải hoàn chỉnh thì thực thi các hàm bên trong
window.onload = function () {
    get_bookings();
}