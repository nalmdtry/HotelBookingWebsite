// let: Khai báo biến có phạm vi khối (block scope).
let add_room_form = document.getElementById('add_room_form');

// Gắn event listener lắng nghe sự kiện submit của form
add_room_form.addEventListener('submit', function (e) {
    e.preventDefault();
    add_room();
});

// Hàm thêm phòng mới
function add_room() {
    // Tạo một FormData trống dùng để đóng gói các cặp key/value gửi tới server theo multipart/form-data
    let data = new FormData();

    data.append('add_room', '');
    data.append('name', add_room_form.elements['name'].value);
    data.append('area', add_room_form.elements['area'].value);
    data.append('price', add_room_form.elements['price'].value);
    data.append('quantity', add_room_form.elements['quantity'].value);
    data.append('adult', add_room_form.elements['adult'].value);
    data.append('children', add_room_form.elements['children'].value);
    data.append('desc', add_room_form.elements['desc'].value);

    // Tạo mảng features chứa id của checbox tính năng đã được check
    let features = [];
    add_room_form.elements['features'].forEach(
        el => { // add_room_form.elements['features'] là 1 mảng các phần tử <input> (vd: 3) có name='features' 
            // forEach: duyệt qua từng phần tử trong mảng và thực hiện 1 hành động trên từng phần tử
            if (el.checked) { // Nếu checkbox được check
                features.push(el
                    .value
                ); // el.value chứa id của ô select đã chọn và push vào mảng features, features là mảng số: ["1","3","5"]
            }
        });

    // Tạo mảng facilities chứa id của select tiện nghi đã chọn
    let facilities = [];
    add_room_form.elements['facilities'].forEach(
        el => { // add_room_form.elements['features'] là 1 mảng các phần tử <input> (vd: 3) có name='features' 
            // forEach: duyệt qua từng phần tử trong mảng và thực hiện 1 hành động trên từng phần tử
            if (el.checked) {
                facilities.push(el.value); // el.value chứa id của ô select đã chọn và push vào mảng features
            }
        });

    // Vì FormData chỉ chứa cặp key&value dạng chuỗi hoặc file, k truyền đc mảng
    // Chuyển 1 mảng JS -> 1 chuỗi theo định dạng JSON, vd ["1","3","5"] -> chuỗi ký tự '["1","3","5"]' hoặc '[1,3,5]'
    data.append('features', JSON.stringify(features));
    data.append('facilities', JSON.stringify(facilities));

    // XMLHttpRequest là API chuẩn của trình duyệt để gửi các yêu cầu HTTP (GET/POST/...) bất đồng bộ (AJAX).
    let xhr = new XMLHttpRequest();

    // Cấu hình request gửi đi
    xhr.open("POST", "ajax/rooms_crud.php", true);

    xhr.onload = function () {
        var myModal = document.getElementById('add-room');
        var modal = bootstrap.Modal.getInstance(myModal);
        modal.hide();

        if (this.responseText == 1) {
            alert('success', 'Đã thêm phòng mới!');
            add_room_form.reset();
            get_all_rooms();
        } else {
            alert('error', 'Hành động thất bại!');
        }
    }

    // Gửi request lên ajax xử lý
    xhr.send(data);
}

// Hàm hiển thị all phòng
function get_all_rooms() {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/rooms_crud.php", true);
    xhr.setRequestHeader('Content-Type',
        'application/x-www-form-urlencoded'); // Dạng key1=value1&key2=value2 giống gửi form truyền thống

    // Khi nhận dc phản hồi từ sever
    xhr.onload = function () {
        document.getElementById('room-data').innerHTML = this.responseText; // Gán nội dung HTML trả về từ sever
    }

    xhr.send('get_all_rooms'); // Gửi yêu cầu HTTP POST tới sever
}

// Phần hiển thị thông tin phòng lên modal edit
let edit_room_form = document.getElementById('edit_room_form');

// Hàm hiển thị thông tin phòng lên modal edit
function edit_details(id) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/rooms_crud.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        // console.log(JSON.parse(this.responseText));
        let data = JSON.parse(this
            .responseText
        ); // Chuyển chuỗi JSON (vd {"roomdata":{...},"features":[...],"facilities":[...]}) về đối tượng JS để xử lý dữ liệu

        // Gán từng dữ liệu vào ô input trong modal edit-room 
        edit_room_form.elements['name'].value = data.roomdata.name;
        edit_room_form.elements['area'].value = data.roomdata.area;
        edit_room_form.elements['price'].value = data.roomdata.price;
        edit_room_form.elements['quantity'].value = data.roomdata.quantity;
        edit_room_form.elements['adult'].value = data.roomdata.adult;
        edit_room_form.elements['children'].value = data.roomdata.children;
        edit_room_form.elements['desc'].value = data.roomdata.description;
        edit_room_form.elements['room_id'].value = data.roomdata.id;

        // elements['features'] đại diện cho tập hợp các input checkbox có name="features"
        edit_room_form.elements['features'].forEach(
            el => { // forEach lặp qua từng phần tử (mỗi el là một <input type="checkbox" value="...">)
                if (data.features.includes(Number(el
                    .value
                ))) { // data.features là mảng số (theo PHP đã encode), vd [1,3,5]; el.value là chuỗi (vd: '3'), Number(el.value) chuyển thành số (3) 
                    el.checked = true;
                }
            });

        // elements['facilities'] đại diện cho tập hợp các input checkbox có name="facilities"
        edit_room_form.elements['facilities'].forEach(el => {
            if (data.facilities.includes(Number(el.value))) {
                el.checked = true;
            }
        });
    }

    xhr.send('get_room=' + id);
}

// Thêm event listener cho sự kiện submit của form
edit_room_form.addEventListener('submit', function (e) {
    e.preventDefault();
    submit_edit_room();
});

// Hàm submit phòng đã chỉnh sửa
function submit_edit_room() {
    let data = new FormData();
    data.append('edit_room', '');
    data.append('room_id', edit_room_form.elements['room_id'].value);
    data.append('name', edit_room_form.elements['name'].value);
    data.append('area', edit_room_form.elements['area'].value);
    data.append('price', edit_room_form.elements['price'].value);
    data.append('quantity', edit_room_form.elements['quantity'].value);
    data.append('adult', edit_room_form.elements['adult'].value);
    data.append('children', edit_room_form.elements['children'].value);
    data.append('desc', edit_room_form.elements['desc'].value);

    // Khởi tạo mảng JS để chứa id của các checkbox features được tick.
    let features = [];
    edit_room_form.elements['features'].forEach(
        el => { // elements['features'] trả về một danh sách các control có name="features"
            if (el.checked) {
                features.push(el.value);
            }
        });

    let facilities = [];
    edit_room_form.elements['facilities'].forEach(el => {
        if (el.checked) {
            facilities.push(el.value);
        }
    });

    // Chuyển mảng thành chuỗi JSON vd '[1,3,5]'
    data.append('features', JSON.stringify(features));
    data.append('facilities', JSON.stringify(facilities));

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/rooms_crud.php", true);

    xhr.onload = function () {
        var myModal = document.getElementById('edit-room');
        var modal = bootstrap.Modal.getInstance(myModal);
        modal.hide();

        if (this.responseText == 1) {
            alert('success', 'Đã chỉnh sửa dữ liệu phòng');
            edit_room_form.reset();
            get_all_rooms();

        } else {
            alert('error', 'Hành động thất bại');
        }
    }

    xhr.send(data);

}

// Hàm chuyển đổi trạng thái nút status 
function toggle_status(id, val) {
    // Tạo đối tượng XMLHttpRequest để gửi request AJAX (không load lại trang)
    let xhr = new XMLHttpRequest();

    // Mở kết nối đến file PHP rooms_crud.php, gửi dữ liệu qua POST và gửi bất đồng bộ (k chặn giao diện)
    xhr.open("POST", "ajax/rooms_crud.php", true);

    // Thiết lập kiểu dữ liệu gửi đi dạng form truyền thống (key=value&key=value)
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    // Khi nhận xong phản hồi từ server, hàm này sẽ chạy
    xhr.onload = function () {
        if (this.responseText == 1) { // this.responseText → nội dung PHP trả về (1 hoặc 0)
            alert('success', 'Đã chuyển đổi trạng thái!')
            get_all_rooms();
        } else {
            alert('error', 'Hành động thất bại!')
        }
    }

    // Gửi dữ liệu POST đến server
    xhr.send('toggle_status=' + id + '&value=' + val);
}

// Phần thêm hình ảnh
let add_image_form = document.getElementById('add_image_form');

add_image_form.addEventListener('submit', function (e) {
    e.preventDefault();
    add_image();
});

// Hàm thêm hình ảnh phòng
function add_image() {
    let data = new FormData();
    data.append('image', add_image_form.elements['image'].files[0]);
    data.append('room_id', add_image_form.elements['room_id'].value);
    data.append('add_image', '');

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/rooms_crud.php", true);

    xhr.onload = function () {
        if (this.responseText == 'inv_img') { // là chuỗi (string) server trả lại (body content), this là xhr
            alert('error', 'Chỉ cho phép hình ảnh JPG, WEBP hoặc PNG!', 'image-alert');
        } else if (this.responseText == 'inv_size') {
            alert('error', 'Hình ảnh phải nhỏ hơn 2MB!', 'image-alert');
        } else if (this.responseText == 'upd_failed') {
            alert('error', 'Tải hình ảnh lên không thành công!', 'image-alert');
        } else {
            alert('success', 'Đã thêm hình ảnh mới!', 'image-alert');

            // Gọi hàm hiển thị dữ liệu lên modal sau khi thêm hình ảnh thành công
            room_images(add_image_form.elements['room_id'].value, document.querySelector(
                "#room-images .modal-title").innerText);

            // Reset dữ liệu trong form 
            add_image_form.reset();

        }
    }

    xhr.send(data);
}

// Hàm hiển thị room_id và tên của phòng cần thêm ảnh lên modal thêm ảnh
function room_images(id, rname) {
    document.querySelector("#room-images .modal-title").innerText = rname;  // querySelector: hàm tìm phần tử HTML đầu tiên khớp với css selector
    add_image_form.elements['room_id'].value = id;
    add_image_form.elements['image'].value = '';

    // Tạo đối tượng XMLHttpRequest để gửi request AJAX (không load lại trang)
    let xhr = new XMLHttpRequest();

    // Mở kết nối đến file PHP rooms_crud.php, gửi dữ liệu qua POST và gửi bất đồng bộ (k chặn giao diện)
    xhr.open("POST", "ajax/rooms_crud.php", true);

    // Thiết lập kiểu dữ liệu gửi đi dạng form truyền thống (key=value&key=value)
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    // Khi nhận xong phản hồi từ server, hàm này sẽ chạy
    xhr.onload = function () {
        // Hiển thị dữ liệu lên table của modal thêm hình ảnh phòng
        document.getElementById('room-image-data').innerHTML = this
            .responseText; // this.responseText → nội dung PHP trả về
    }
    // Gửi dữ liệu POST đến server
    xhr.send('get_room_images=' + id);
}

// Hàm xóa hình ảnh phòng
function rem_image(img_id, room_id) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/rooms_crud.php", true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    xhr.onload = function () {
        if (this.responseText == 1) { // là chuỗi (string) server trả lại (body content), this là xhr
            alert('success', 'Đã xóa hình ảnh thành công!', 'image-alert');

            // Gọi hàm hiển thị dữ liệu lên modal sau khi xóa hình ảnh thành công
            room_images(room_id, document.querySelector("#room-images .modal-title").innerText);
        } else {
            alert('error', 'Hành động thất bại!', 'image-alert');
        }
    }

    xhr.send('img_id=' + img_id + '&room_id=' + room_id + '&rem_image');

}

// Hàm thay đổi thumbnail hình ảnh
function thumb_image(img_id, room_id) {
    let data = new FormData();
    data.append('img_id', img_id);
    data.append('room_id', room_id);
    data.append('thumb_image', '');

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/rooms_crud.php", true);

    xhr.onload = function () {
        if (this.responseText == 1) {
            alert('success', 'Thumbnail của hình ảnh đã thay đổi!', 'image-alert');
            room_images(room_id, document.querySelector("#room-images .modal-title").innerText);
        } else {
            alert('error', 'Cập nhật thumbnail thất bại!', 'image-alert');
        }
    }

    xhr.send(data);
}

// Hàm xóa phòng
function remove_room(room_id) {
    if (confirm("Bạn có chắc chắn muốn xóa phòng này không?")) {
        let data = new FormData();
        data.append('room_id', room_id);
        data.append('remove_room', '');

        let xhr = new XMLHttpRequest();
        xhr.open("POST", "ajax/rooms_crud.php", true);

        xhr.onload = function () {
            if (this.responseText == 1) {
                alert('success', 'Đã xóa phòng thành công!');
                get_all_rooms();
            } else {
                alert('error', 'Xóa phòng thất bại!');
            }
        }
        xhr.send(data);
    }
}

// Khi toàn bộ trang web đã được tải hoàn chỉnh thì thực thi các hàm bên trong
window.onload = function () {
    get_all_rooms();
}