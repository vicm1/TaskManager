<?php
//My login page, need to create an account from the setupusers.php page first to be able to utilize this as intended.
//Makes sure user and password are correct to the same credentials as sign up.
include "login.php";

$conn = new mysqli($host, $user, $pass);

//db is connected, users created under db filecontent
$dbname = "todo";
$conn = new mysqli($host, $user, $pass, $dbname);

//sql command to create users
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

// Javascript is being utilized to validate the username and the password
echo<<<_END
<head>
    <html>
    <head>
        <title>Log In</title>
        <script src = "validate.js"></script>
        </head>
        <body>
            <form action="loginpage.php" name ="validate" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
            <h2>Log In</h2>
                Username: <input type = "text" name = "username" placeholder ="Enter Name: "><br>
                </br>
                Password: <input type = "password" name = "password" placeholder = "Enter Password: "><br> 
                </br>
                <button type="submit">Login</button>
                <p>Don't have an account? <a href ="setupusers.php"> Sign up here!</a>.</p> 
            </form>
_END; //If user does not have an account then they get redirected to the sign up page to create one.

if(isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])){
if (isset($_POST['username']) && isset($_POST['password'])) { //validates the data, in other ways it sanitizes the incoming inputs
    function validate($data){
       $data = trim($data);
       $data = stripslashes($data);
       $data = htmlspecialchars($data);
       return $data;
    }
    $salt1 =  "qm&h*"; //create salts for the password
    $salt2 = "pg!@"; //crete salts for the password
    $username = mysql_entities_fix_string($conn, $_SERVER['PHP_AUTH_USER']); //sanitize the string
    $password = mysql_entities_fix_string($conn, $_SERVER['PHP_AUTH_PW']); //sanitize the string
    $username = validate($_POST['username']);
    $password = validate($_POST['password']);
    //$token = password_hash($password, PASSWORD_DEFAULT); //hash the password, when i tried using this method it wouldn't work, both login and sign up passwords would be different making the user not be able to log in
    $token = hash('ripemd128', "$salt1$password$salt2"); //hash the password with salts
    if (empty($username)) {
        header("Location: loginpage.php?error=Username is required");
        exit();
    }else if(empty($token)){
        header("Location: loginpage.php?error=Password is required");
        exit();
    }else{
        $query = "SELECT * FROM users WHERE Username='$username' AND Password='$token'"; //checks to make sure that the usernames match also same for the passwoords or for this method check the "token" since the password was hashed to a token
        $result = mysqli_query($conn, $query);
        if (mysqli_num_rows($result) === 1) { //the first row that contains, the name and user to see if they're the same
            $row = mysqli_fetch_assoc($result);
            if ($row['Username'] === $username && $row['Password'] === $token) { //checks rows to make sure mysql username/pass match with the username/pass the user inputted
                $_SESSION['username'] = $row['Username']; //make sure username matches username
                $_SESSION['password'] = $row['Password']; //make sure password matches password
                session_start(); //session starts
                header("Location: taskmanager.php"); //if everything is matched then they're in the main page where the file content is located.
                exit();
            }else{
                echo"Incorect Username or password";
                exit();
            }
        }else{
            echo"Incorect Username or password";
            exit();
        }
    }
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
function mysql_entities_fix_string($conn, $string) {
    return htmlentities(mysql_fix_string($conn, $string));
}
function mysql_fix_string($conn, $string) {
   // if (get_magic_quotes_gpc()) $string = stripslashes($string);
    return $conn->real_escape_string($string);
}

?>