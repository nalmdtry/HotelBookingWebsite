// Hàm hiển thị Booking Analytics
function booking_analytics(period = 1) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/dashboard.php", true);
    xhr.setRequestHeader('Content-Type',
        'application/x-www-form-urlencoded'); // Dạng key1=value1&key2=value2 giống gửi form truyền thống

    // Khi nhận dc phản hồi từ sever
    xhr.onload = function () {
        // Chuyển mảng json về đối tượng JS để xử lý dữ liệu, truy cập bằng key
        let data = JSON.parse(this.responseText);

        // Gán nội chung từ sever trả về vào các trường input tương ứng
        document.getElementById('total_bookings').textContent = data.total_bookings;
        document.getElementById('total_amount').textContent = data.total_amount + '₫';

        document.getElementById('active_bookings').textContent = data.active_bookings;
        document.getElementById('active_amount').textContent = data.active_amount + '₫';

        document.getElementById('cancelled_bookings').textContent = data.cancelled_bookings;
        document.getElementById('cancelled_amount').textContent = data.cancelled_amount + '₫';


    }

    xhr.send('booking_analytics&period=' + period); // Gửi yêu cầu HTTP POST tới sever
}

// Hàm hiển thị User, Queries, Review Analytics
function user_analytics(period = 1) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/dashboard.php", true);
    xhr.setRequestHeader('Content-Type',
        'application/x-www-form-urlencoded'); // Dạng key1=value1&key2=value2 giống gửi form truyền thống

    // Khi nhận dc phản hồi từ sever
    xhr.onload = function () {
        // Chuyển mảng json về đối tượng JS để xử lý dữ liệu, truy cập bằng key
        let data = JSON.parse(this.responseText);

        // Gán nội chung từ sever trả về vào các trường input tương ứng
        document.getElementById('total_new_reg').textContent = data.total_new_reg;
        document.getElementById('total_queries').textContent = data.total_queries;
        document.getElementById('total_reviews').textContent = data.total_reviews;
    }

    xhr.send('user_analytics&period=' + period); // Gửi yêu cầu HTTP POST tới sever
}

// Khi toàn bộ trang web đã được tải hoàn chỉnh thì thực thi các hàm bên trong
window.onload = function () {
    booking_analytics();
    user_analytics();
}