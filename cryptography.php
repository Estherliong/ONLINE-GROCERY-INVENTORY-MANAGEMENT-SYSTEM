<?php
function encrypt_data($data) {
    $encryption_key = 'flower'; 
    $iv = '1234567891200000';
    $encrypted = openssl_encrypt($data, 'aes-256-cbc', $encryption_key, 0, $iv);
    return base64_encode($encrypted . '::' . $iv);
}

function decrypt_data($data) {
    $encryption_key = 'flower'; 
    $iv = '12345678912';
    $data = base64_decode($data);
    if (strpos($data, '::') !== false) {
        list($encrypted_data, $iv) = explode('::', $data, 2);
        return openssl_decrypt($encrypted_data, 'aes-256-cbc', $encryption_key, 0, $iv);
    }
    return false; 
}
?>