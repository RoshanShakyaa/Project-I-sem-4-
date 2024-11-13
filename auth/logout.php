<?php
    session_start();
    session_destroy();
    
    // Clear the remember me cookie
    setcookie('user_id', '', time() - 3600, "/");
    setcookie('remember_me', '', time() - 3600, "/");
    
    // Redirect to login
    header("Location: login.php");
    exit();
    

?>