<?php
session_start();

// Keep the users list but clear login state
unset($_SESSION['logged_in']);
unset($_SESSION['current_user']);

header('Location: signup.php');
exit;
?>
