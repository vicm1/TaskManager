<?php // authentication.php
require_once 'login.php';

$conn = new mysqli($host, $user, $pass);
if($conn->connect_error){ //if error happens let the user know
    echo "Error with Authentication";
}

$fc = "CREATE DATABASE todo"; //create database if it hasnt yet been created
if($conn->query($fc) === TRUE) {
    echo "";
}
else{
    echo ""; 
}

$dbname = "todo";
$conn = new mysqli($host, $user, $pass, $dbname) OR die('Could not connect to my sql' . $conn->connect_error);

if(isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])){
    $username = mysql_entities_fix_string($conn, $_SERVER['PHP_AUTH_USER']); //sanitize the string
    $password = mysql_entities_fix_string($conn, $_SERVER['PHP_AUTH_PW']); //sanitize the string

    $salt1 = "qm&h*"; 
    $salt2 = "pg!@";
    //$token = password_hash($password, PASSWORD_DEFAULT); //hash the password, when i tried using this method it wouldn't work, both login and sign up passwords would be different making the user not be able to log in
    $token = hash('ripemd128', "$salt1$password$salt2"); //salt the password that was recieved again

    $query = "SELECT * FROM users WHERE Username='$username' AND Password='$token'";
    $result = mysqli_query($conn, $query);
    if (empty($username)) { //if username is empty then invalid username
        header("Invalid username/password combination, <p><a href=loginpage.php>Click here to Try again1</a></p>");
        exit();
    }else if(empty($token)){ //if password is empty then invalid password
        header("Invalid username/password combination, <p><a href=loginpage.php>Click here to Try again</a></p>");
        exit();
    if (mysqli_num_rows($result) === 1) //checks the mysql row to see if it's a match
    {
        $row = mysqli_fetch_assoc($result);
        if ($token == $row[1]){
            session_start();
            $_SESSION['username'] = $row['Username'];//make sure username matches username
            $_SESSION['password'] = $row['Password']; //make sure password matches password
            echo "Hi $row[0]";
            die ("<p><a href=setupusers.php>Click here to continue</a></p>");
        }
    }
    }
}
else  {  // if ($_SERVER['PHP_AUTH_USER’])  and  ($_SERVER['PHP_AUTH_PW’]) are not set
    header('WWW-Authenticate: Basic realm="Restricted Section"');
    header('HTTP/1.0 401 Unauthorized');
    die ("Please enter your username and password");
    }
$conn->close();
//these two functions are used as sanitization functions 
function mysql_entities_fix_string($conn, $string) {
    return htmlentities(mysql_fix_string($conn, $string));
}
function mysql_fix_string($conn, $string) {
   // if (get_magic_quotes_gpc()) $string = stripslashes($string);
    return $conn->real_escape_string($string);
}
?>