<!-- For Password generation   -->

<?php
$password = "pass";
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

echo $hashedPassword;
?>