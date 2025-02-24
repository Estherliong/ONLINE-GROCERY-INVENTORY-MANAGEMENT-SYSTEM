<?php
include('session_management.php');

    clear_session();
    unset($_SESSION['id']);
    unset($_SESSION['identity']);
    unset($_SESSION['email']);
    unset($_SESSION['name']);
    unset($_SESSION['message']);
    
    $_SESSION['message'] = "Logged out Successfully";
    header("Location: login.php");
    

?>