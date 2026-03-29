<?php
    require('inc/essentials.php');
    
    session_start();
    session_destroy();
    // Gọi hàm chuyển trang
    redirect('index.php');
?>