<?php
session_start();
session_unset();
session_destroy();
// Redirect to the login page in the login folder
header("Location: home/index.php");
exit();
?>