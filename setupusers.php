<?php
//CS174 Project Final, by Victor Martinez & Justine Damolo

/*NOTE: This is only for signing up, so only the first time you sign up will you be redirected to the taskmanager page, 
IF you already have an account use the page loginpage.php to login and get access to the taskmanager page*/

require_once 'login.php';

$conn = new mysqli($host, $user, $pass);

//sql command to create database todo for a todolist
$fc = "CREATE DATABASE todo"; //create database if it hasnt yet been created
if($conn->query($fc) === TRUE) {
    echo "";
}
else{
    echo ""; 
}
//users created under db todo
$dbname = "todo";
$conn = new mysqli($host, $user, $pass, $dbname);

//sql command to create table users
//table to store the users credentials, their username, email, and password while giving them a primary ID for each user to be unique.
$query = "CREATE TABLE users (
	Username VARCHAR(32) NOT NULL UNIQUE,
	Password VARCHAR(32) NOT NULL,
    Email VARCHAR(32) NOT NULL,
    Id INT UNSIGNED NOT NULL AUTO_INCREMENT KEY
)";

if ($conn->query($query) === TRUE) {
    echo "";
}
else{
    echo "";
}

//This HTML redirects to log in page if user already has signed up through the Log In link being formed.
// Javascript is being utilized to validate the username, password and email
echo<<<_END
<head>
    <html>
    <head>
        <title>Sign Up</title>
        <script src = "validate.js"></script>
        </head>
        <body>
            <form method="post" name="validate" action="setupusers.php" enctype="multipart/form-data" onsubmit="return validateForm()">
            <h2>Sign Up</h2>
                Username: <input type = "text" minlength ="5" name = "username" placeholder ="Enter Name: "><br>
                </br>
                Password: <input type = "password" name = "password" placeholder = "Enter Password: " required><br> 
                </br>
		        Email: <input type = "email" name = "email" placeholder = "Enter Email: "><br> 
                </br>
                <input type="submit" value = "SIGNUP" name="submit">
                <p>Already have an account? <a href ="loginpage.php"> Login Here</a></p> 
            </form>
_END;

if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['email'])){
    $salt1 =  "qm&h*"; //create salts for the password
    $salt2 = "pg!@"; //crete salts for the password
    $username = get_post($conn, 'username');
    $password = get_post($conn, 'password');
    $email = get_post($conn, 'email');
    $username = filter_var($username, FILTER_SANITIZE_STRING); //sanitize the name that was acquired
    $password = filter_var($password, FILTER_SANITIZE_STRING);
    $email = filter_var($email, FILTER_SANITIZE_STRING);
    //$token = password_hash($password, PASSWORD_DEFAULT); //hash the password, when i tried using this method it wouldn't work, both login and sign up passwords would be different making the user not be able to log in
    $token = hash('ripemd128', "$salt1$password$salt2"); //hash the password with salts
    add_user($conn, $username, $token, $email);
}

function add_user($conn, $un, $pass, $el,){ //this function is created to be able to add the hash to the password
    $query = "INSERT INTO users VALUES"."('$un','$pass', '$el', NULL)"; //insert this information onto the table, set the users new variables to match the database variables.
    if (empty($un) || empty($pass) || empty($el)){ //checks to make sure user has filled out the empty fields.
        die("<br> Complete all fields.</br>");
    }
    $result = $conn->query($query);
    if (!$result){ //if values aren't inserted into the database then error would show
        echo "Error: Username is already taken! ".$conn->error; //if username has already been used by other user, send error message saying it's already taken
        echo"<br>Sign up Incomplete.</br>";
        exit();
    }
    else{
        header('location: taskmanager.php'); /*if everything works as intended and there are no errors then redirect the user into the main filecontent page. 
        Can only happen once for this page when the user signs up because then the username will already be used and will be invalid if trying to log in through the sign up page*/
    }
}
echo "</body></html>";

$query = "SELECT * FROM users"; //select the table users and insert this information inside it
$result = $conn->query($query);
if(!$result){
    die("Database access failed:" .$conn->error);
}

//close the result and connection
$result->close();
$conn->close();

function get_post($conn, $var){
    return $conn->real_escape_string($_POST[$var] ?? "");
}
?>