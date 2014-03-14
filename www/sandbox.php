<?php
$password = "1a9KLLASjrahr";
$iterations = 1000;
$salt = mcrypt_create_iv(24, MCRYPT_DEV_URANDOM);
echo bin2hex($salt) . "\n";
$hashed = hash_pbkdf2("sha512", $password, $salt, $iterations, 128, false);
echo $hashed;
?>
