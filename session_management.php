<?php
session_start();

function check_session() {
    if (!isset($_SESSION['identity']) && !isset($_SESSION['role'])) {
        echo '<script>window.location.href="login.php"</script>';
        exit();
    }
}

function clear_session() {
    session_unset();
    session_destroy();
}

// Only call clear_session when you explicitly want to log out the user
// clear_session();

check_session();
?>