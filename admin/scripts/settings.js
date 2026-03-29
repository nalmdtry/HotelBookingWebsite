// Khai báo biến toàn cục để lưu dữ liệu JSON nhận được từ server
let general_data, contacts_data;

// Lấy phần tử HTML theo id và gán vào biến tương ứng
let general_s_form = document.getElementById('general_s_form');
let site_title_inp = document.getElementById('site_title_inp');
let site_about_inp = document.getElementById('site_about_inp');

// Lấy form modal theo id.
let contacts_s_form = document.getElementById('contacts_s_form');

let team_s_form = document.getElementById('team_s_form');
let member_name_inp = document.getElementById('member_name_inp');
let member_picture_inp = document.getElementById('member_picture_inp');

// Hàm lấy dữ liệu general settings (site_title, site_about và shutdown)
function get_general() {
    let site_title = document.getElementById('site_title');
    let site_about = document.getElementById('site_about');

    let shutdown_toggle = document.getElementById('shutdown-toggle');


    // Tạo đối tượng AJAX (XMLHttpRequest) để gửi yêu cầu HTTP tới server và nhận 
    // phản hồi mà ko cần tải lại trang
    let xhr = new XMLHttpRequest();

    // Mở một yêu cầu HTTP kiểu POST đến file PHP ajax/settings_crud.php.
    // true = gửi bất đồng bộ (không chặn giao diện).
    xhr.open("POST", "ajax/settings_crud.php", true);

    // Thiết lập kiểu dữ liệu gửi đi là form data (giống như gửi từ <form> HTML
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    // Là một phương thức của đối tượng XHR dùng để thêm một cặp tên-giá trị vào phần header của yêu cầu HTTP

    // Khi server phản hồi xong (onload)
    xhr.onload = function () {
        // Xử lý dữ liệu trả về
        // Chuyển chuỗi JSON thành đối tượng JS
        general_data = JSON.parse(this.responseText); // this.responseText: nội dung trả về (chuỗi JSON)
        // console.log(general_data);

        // Gán dữ liệu
        site_title.innerText = general_data.site_title;
        site_about.innerText = general_data.site_about;

        site_title_inp.value = general_data.site_title;
        site_about_inp.value = general_data.site_about;

        if (general_data.shutdown == 0) {
            shutdown_toggle.checked = false;
            shutdown_toggle.value = 0;
        } else {
            shutdown_toggle.checked = true;
            shutdown_toggle.value = 1;
        }
    }

    // Gửi request đến server với POST body = "get_general" 
    // → PHP nhận được $_POST['get_general']
    xhr.send('get_general');
}

// Thêm 1 trình xử lý sự kiện 'submit', khi form dc submit thì chạy hàm bên trong
general_s_form.addEventListener('submit', function (e) {
    e.preventDefault(); // e là đối tượng sk đại diện cho hành động submit
    // preventDefault() ngăn chặn hành động mặc định của trình duyệt (ko tải lại trang)

    // Gọi hàm
    upd_general(site_title_inp.value, site_about_inp.value);
});

// Hàm update dữ liệu general settings (site_title và site_about)
function upd_general(site_title_val, site_about_val) {
    // Tạo đối tượng XHR để gửi yêu cầu HTTP không cần tải lại trang.
    let xhr = new XMLHttpRequest();

    // Mở một yêu cầu HTTP kiểu POST đến file PHP ajax/settings_crud.php.
    // true = gửi bất đồng bộ (không chặn giao diện).
    xhr.open("POST", "ajax/settings_crud.php", true);

    // Thiết lập kiểu dữ liệu gửi đi là form data (giống như gửi từ <form> HTML
    // (key=value&key=value)
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    // Là một phương thức của đối tượng XHR dùng để thêm một cặp tên-giá trị vào phần header của yêu cầu HTTP

    // Khi server phản hồi xong (onload), trình duyệt gọi hàm này để xử lý kết quả
    xhr.onload = function () {
        var myModal = document.getElementById('general-s');
        var modal = bootstrap.Modal.getInstance(myModal); // Lấy thể hiện (instance) của modal Bootstrap để điều khiển.

        // Đóng modal
        modal.hide();
        // console.log(this.responseText); (trả về 1 hoặc 0)

        // this ở đây chính là đối tượng XMLHttpRequest (xhr).
        // responseText là thuộc tính chứa toàn bộ nội dung phản hồi dạng chuỗi (string).
        if (this.responseText == 1) {
            alert('success', 'Đã lưu thay đổi!');
            get_general();
        } else {
            alert('error', 'Không có thay đổi nào được thực hiện!');
        }
    }

    // Gửi request đến server với 2 tham số và POST body = "upd_general" 
    // → PHP nhận được $_POST['upd_general']
    xhr.send('site_title=' + site_title_val + '&site_about=' + site_about_val + '&upd_general');
}

// Hàm update nút shutdown
function upd_shutdown(val) { // val luôn là giá trị trước khi click (khi bật ct thì value vẫn là 0)
    // Tạo một đối tượng AJAX (XMLHttpRequest) để gửi yêu cầu lên server mà không cần reload trang.
    let xhr = new XMLHttpRequest();

    // Mở một yêu cầu HTTP POST tới file PHP xử lý (settings_crud.php)
    // true = gửi bất đồng bộ (asynchronous)
    xhr.open("POST", "ajax/settings_crud.php", true);

    // Xác định dạng dữ liệu gửi đi giống như khi submit form HTML (key=value&key2=value2)
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    // Khi server xử lý xong và phản hồi, hàm này được gọi tự động.
    xhr.onload = function () {
        if (this.responseText == 1 && general_data.shutdown == 0) {  // Nếu server trả về 1 và trước đó trạng thái shutdown đang là 0
            alert('success', 'Site has been shutdown!');
        } else {
            alert('success', 'Shutdown mode off!');
        }
        get_general();
    }

    // Gửi dữ liệu lên server, dạng POST body upd_shutdown
    xhr.send('upd_shutdown=' + val);
}


// Hàm lấy dữ liệu Contact từ server (PHP) và hiển thị lên giao diện.
function get_contacts() {

    let contacts_p_id = ['address', 'gmap', 'pn1', 'pn2', 'email', 'fb', 'insta', 'tw']; // Mảng 8 các id
    let iframe = document.getElementById('iframe');
    // console.log(contacts_p_id);

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/settings_crud.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    // Hàm tự động chạy sau khi server trả dữ liệu thành công
    xhr.onload = function () {
        // this.responseText (hoặc xhr.responseText) là chuỗi JSON trả từ server(ajax).  '{"sr_no":"1","address":"",...}'
        contacts_data = JSON.parse(this.responseText); // Chuyển chuỗi JSON -> Object JS   {sr_no:"1",address:"",...}
        contacts_data = Object.values(contacts_data); // Lấy mảng các giá trị (bỏ qua tên cột trong DB)  [1, "address", "..."]

        for (i = 0; i < contacts_p_id.length; i++) {
            document.getElementById(contacts_p_id[i]).innerText = contacts_data[i + 1];     // innerText: thẻ hiển thị (p, span, div)
        }
        iframe.src = contacts_data[9];

        // Gọi hàm hiển thị dữ liệu sẵn lên các input trong modal
        contacts_inp(contacts_data);

    }

    xhr.send('get_contacts');
}

// Hàm lấy dữ liệu từ CSDL gán vào các ô input tương ứng trong modal contacts
function contacts_inp(data) {
    let contacts_inp_id = ['address_inp', 'gmap_inp', 'pn1_inp', 'pn2_inp', 'email_inp', 'fb_inp', 'insta_inp', 'tw_inp', 'iframe_inp']; // Mảng các id của ô input trong modal
    for (i = 0; i < contacts_inp_id.length; i++) {
        document.getElementById(contacts_inp_id[i]).value = data[i + 1];    // value: thuộc tính chỉ dùng cho input, select, textarea
    }
}

// Thêm 1 trình xử lý sự kiện 'submit' cho contacts_s_form
contacts_s_form.addEventListener('submit', function (e) {
    e.preventDefault();
    upd_contacts();
});

// Hàm update dữ liệu contacts
function upd_contacts() {
    let index = ['address', 'gmap', 'pn1', 'pn2', 'email', 'fb', 'insta', 'tw', 'iframe']; // Mảng name trong input (key)
    let contacts_inp_id = ['address_inp', 'gmap_inp', 'pn1_inp', 'pn2_inp', 'email_inp', 'fb_inp', 'insta_inp', 'tw_inp', 'iframe_inp']; // Mảng id của input (value)
    let data_str = "";

    for (i = 0; i < index.length; i++) {
        data_str += index[i] + "=" + document.getElementById(contacts_inp_id[i]).value + "&";   // address=abc&gmap=xyz&email=test@gmail.com&upd_contacts
    }
    // Cờ phân biệt request UPDATE
    data_str += "upd_contacts";

    // Tạo đối tượng Ajax
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/settings_crud.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');  // Set header báo PHP cách đọc $_POST

    // Chạy khi PHP trả về kết quả
    xhr.onload = function () {
        var myModal = document.getElementById('contacts-s');
        var modal = bootstrap.Modal.getInstance(myModal); // Lấy thể hiện (instance) của modal Bootstrap để điều khiển.

        // Đóng modal
        modal.hide();

        if (this.responseText == 1) {
            alert('success', 'Đã lưu thay đổi');

            // Gọi hàm Load lại dữ liệu mới lên giao diện
            get_contacts();
        } else {
            alert('error', 'Không có thay đổi nào được thực hiện');
        }
    }

    xhr.send(data_str);
}

// Thêm 1 trình xử lý sự kiện 'submit' cho team_s_form
team_s_form.addEventListener('submit', function (e) {
    e.preventDefault(); // Ngăn trang reload
    add_member();
});

// Hàm thêm thành viên management
function add_member() {
    let data = new FormData(); // FormData: object trong JavaScript được sử dụng để tạo ra một tập hợp các cặp khóa/giá trị, 
    // tương tự như một biểu mẫu HTML, để gửi dữ liệu đi qua các yêu cầu HTTP
    // Với Content-Type theo định dạng multipart/form-data (kiểu mã hóa quan trọng cho việc gửi tệp)

    data.append('name', member_name_inp.value); // append(): Dùng để thêm các cặp khóa/giá trị vào một đối tượng FormData
    data.append('picture', member_picture_inp.files[0]); // files[0] là object File đầu tiên chứa .name (tên file), .type (MIME), .size (số bytes), và nội dung file. PHP sẽ nhận bằng $_FILES['picture']
    // Thêm cờ flag để PHP biết đây là request ADD MEMBER
    data.append('add_member', '');

    // Tạo một đối tượng XMLHttpRequest (XHR) để gửi request AJAX.
    let xhr = new XMLHttpRequest();

    // Cấu hình request trước khi gửi.
    xhr.open("POST", "ajax/settings_crud.php", true);

    // Khi nhận được phản hồi từ sever
    xhr.onload = function () {
        var myModal = document.getElementById('team-s');
        var modal = bootstrap.Modal.getInstance(myModal);
        modal.hide(); // ẩn modal khi submit xong

        // Kiểm tra chuỗi phản hồi từ sever
        if (this.responseText == 'inv_img') { // là chuỗi (string) server trả lại (body content), this là xhr
            alert('error', 'Only JPG and PNG images are allowed!');
        } else if (this.responseText == 'inv_size') {
            alert('error', 'Image should be less than 2MB!');
        } else if (this.responseText == 'upd_failed') {
            alert('error', 'Image upload failed. Server Down!');
        } else {
            alert('success', 'New member added!');
            member_name_inp.value = '';
            member_picture_inp.value = '';
            get_members();
        }

    }
    // Gửi request đã cấu hình (POST đến ajax/settings_crud.php) kèm FormData
    xhr.send(data);
}


// Hàm lấy danh sách team từ server và hiển thị lên trang
function get_members() {
    // Tạo một AJAX request mới (công cụ JS để gửi HTTP request không reload trang)
    let xhr = new XMLHttpRequest();

    // Mở kết nối tới file ajax/settings_crud.php
    xhr.open("POST", "ajax/settings_crud.php", true);

    // Cấu hình header dạng URL-encoded vd key=value&key2=value2
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        document.getElementById('team-data').innerHTML = this.responseText;
    }

    xhr.send('get_members');
}

// Hàm xóa member
function rem_member(val) {
    // Tạo một đối tượng AJAX để gửi và nhận dữ liệu từ PHP.
    let xhr = new XMLHttpRequest();
    // Thiết lập method gửi đi là POST tới file settings_crud.php để xử lý và gửi bất đồng bộ (ko chặn giao diện)
    xhr.open("POST", "ajax/settings_crud.php", true);

    // Báo cho server biết dữ liệu gửi đi kiểu như form HTML bình thường (key=value&key2=value2)
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    // Khi nhận được phản hồi từ sever
    xhr.onload = function () {
        if (this.responseText == 1) {   // this.responseText là nội dung echo từ PHP, 1 nếu xóa thành công, 0 thất bại
            alert('success', 'Member removed!');

            // Gọi lại hàm load lại danh sách team để cập nhật UI ngay lập tức.
            get_members();
        } else {
            alert('error', 'Server down!');
        }
    }

    xhr.send('rem_member=' + val);
}

// Khi trang tải xong, tự động gọi hàm get_general() 
// → hiển thị nội dung ngay lập tức.
window.onload = function () {
    get_general();
    get_contacts();
    get_members();
}