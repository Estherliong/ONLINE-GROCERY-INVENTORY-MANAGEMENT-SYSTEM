<?php 
include("cryptography.php");
include("connection.php");

$Email = "estherliong24@gmail.com";
$Fname = "Esther";
$Lname = "Liong";
$Phone = "0123456789";
$Pass = "Esther_8";

$Encrypted_Email = encrypt_data($Email);
$Encrypted_Phone = encrypt_data($Phone);

$hashed_password = password_hash($Pass, PASSWORD_DEFAULT);

mysqli_query($connect,"INSERT INTO admin (email,pass,fname,lname,phone) VALUES ('$Encrypted_Email','$hashed_password','$Fname','$Lname','$Encrypted_Phone')");

?>