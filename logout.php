<?php
//Leads here when user clicks the logout link from taskmanager.php
session_destroy();//destroy the session when logging out
unset($_SESSION['username']); //unset the sessions that remembered username & password
unset($_SESSION['password']);
header('Location: loginpage.php'); //when user logs out they will be redirected to the login page
exit;
?>