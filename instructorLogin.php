<?php
    session_start();
    //Destroy any running sessions
    if (isset($_SESSION['logger'])){
        session_destroy();
    }
    //Connect to database
    $con=mysqli_connect("localhost", "root", "", "onlinelearning");
    if (!$con){
        die("Sorry! Failed to connect: ". mysqli_connect_error());
    }
    //Login function
    if (isset($_POST['login'])){
        $email = $_POST['email'];
        $passwd = $_POST['passwd'];
        $sql = "SELECT * FROM `instructor` WHERE Email = '" .$email. "' AND Password = '" . $passwd . "'";
        $result = mysqli_query($con, $sql);
        $num=mysqli_num_rows($result);
        if($num==0){
            echo "<p>wrong email or password.</p>";
        }
        else{
            $row=mysqli_fetch_assoc($result);
            $_SESSION['logger'] = $row['InstructorID'];
            header("Location: instructorDashboard.php");
            exit();
        }
        mysqli_close($con);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Learning System</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <h1>Instructor Login</h1>
    <!-- Instructor login form -->
    <form action="instructorLogin.php" method="POST">
        <input type="text" placeholder="Email" name="email">
        <input type="password" placeholder="Password" name="passwd">
        <button type="submit" name="login">Login</button>
    </form>
    <a href="index.php">Student Login</a>
    <a href="instructorRegister.php">Instructor Register</a>
</body>
</html>
