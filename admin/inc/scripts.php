<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
</script>
<script>
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

    if (position == 'body') {
        // Thêm <div> chứa alert vào cuối thẻ <body>, hiển thị ra màn hình.
        document.body.append(element);
        element.classList.add('custome-alert');
    } else {
        document.getElementById(position).appendChild(element);
    }

    // Hẹn giờ để xóa alert sau 2s
    setTimeout(remAlert, 2000); // Sau 2000ms (2s) sẽ gọi hàm remAlert()
}

// Hàm xóa alert
function remAlert() {
    document.getElementsByClassName('alert')[0].remove(); // Xóa phần tử có class alert đầu tiên trong trang
}


// Hàm tự động highlight menu tương ứng với trang hiện tại
function setActive() {
    let navbar = document.getElementById('dashboard-menu'); // DOM method trả về phần tử có id="dashboard-menu"
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
</script>