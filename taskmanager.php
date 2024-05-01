<?php
//Main page where all the tasks are stored, users have to make an account to be able to access this.

//this connects taskmanager.php to login.php to be able to obtain the right database information and connect to it
require_once 'login.php';
//Uses authentication to make sure the user is the same.
include "authentication.php";

//Connecting to the MySql and checks to make sure it's connected
$conn = new mysqli($host, $user, $pass) OR die('Could not connect to my sql' . $conn->connect_error);

//we reconnect to mysqli but this time we connect with the database, dbname
$dbname = "todo";
$conn = new mysqli($host, $user, $pass, $dbname);

//sql command to create tasks table
//Table contents created for the database todo
$c = "CREATE TABLE tasks (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT KEY,
    task VARCHAR(255) NOT NULL
)";

//if statement to show if the content table was created successfully
//the else is if the table is already created then send no message because tables are already created
if ($conn->query($c) === TRUE) {
    echo "";
}
else{
    echo "";
}

$empty = "";

    //add task to the db
    if(isset($_POST['submit'])){
        $task = $_POST['task'];
        if (empty($task)){ //if tasks input is empty, error is thrown. Users have to input something for the task to be recorded
            $empty = "Fill in the task!";
        }
        else{
            mysqli_query($conn, "INSERT INTO tasks (task) VALUES ('$task')");
            header('location: taskmanager.php'); //refresh the page to show tasks have been added
        }
    }

    //delete task from db
    if (isset($_GET['del_task'])) {
        $id = $_GET['del_task'];
        mysqli_query($conn, "DELETE FROM tasks WHERE id =$id");
        header('location: taskmanager.php'); //refresh page to show tasks have been deleted
    }

    $tasks = mysqli_query($conn, "SELECT * FROM tasks");

    //close the result and connection
$result->close();
$conn->close();

function get_post($conn, $var){
    return $conn->real_escape_string($_POST[$var] ?? "");
}

?>

<!DOCTYPE html>
<html><head>
    <title>Task Master!</title>
    <link rel = "stylesheet" type="text/css" href="style2.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</head>
<body class="p-3 mb-2 bg-dark text-white">
    <div class ="heading">
        <h2>Task Master!</h2>
        <form action="logout.php" method="get">
            <input class="w-10 btn btn-sm btn-primary" type="submit" value="Logout">
            </form>
    </div>
    <form method = "POST" action = "taskmanager.php">
        <?php if (isset($empty)) { ?>
            <p><?php echo $empty; ?></p>
        <?php } ?>
        
        <input type ="text" name ="task" class = "taskIN">
        <button type = "submit" class="w-10 btn btn-sm btn-primary" name="submit">Add Task</button>
    </form>
    <table>
        <thead>
            <tr>
                <th>N</th>
                <th>Task</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php $i=1; while ($row = mysqli_fetch_array($tasks)) { ?>
                <tr>
                    <td><?php echo $i; ?></td>
                    <td class = "task"><?php echo $row['task']; ?></td>
                    <td class = "delete">
                        <a href="taskmanager.php?del_task=<?php echo $row['id']; ?>">x</a>
                    </td>
                </tr>

            <?php $i++; } ?>

        </tbody>
    </table>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
</body>
</html>
