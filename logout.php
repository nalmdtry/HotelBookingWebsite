<?php
require('admin/inc/essentials.php');

session_start();
session_destroy(); // Hủy phiên đăng nhập của user
redirect('index.php');

?>