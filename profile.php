<?php
    session_start();
    //to prevent unauthorized entry
    if (!isset($_SESSION['logger'])){
        session_destroy();
        header("Location: index.php");
        mysqli_close($con);
        exit();
    }
    //connect to database
    $con=mysqli_connect("localhost", "root", "", "onlinelearning");
    if (!$con){
        die("Sorry! Failed to connect: ". mysqli_connect_error());
    }

    //update function
    if (isset($_POST['update'])){
        $name = $_POST['name'];
        $email = $_POST['email'];
        $passwd = $_POST['passwd'];
        $sql = "UPDATE `student` SET `name`='".$name."', `email`='".$email."', `password`='".$passwd."' WHERE studentID = ".$_SESSION['logger'];
        $result = mysqli_query($con, $sql);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Portal</title>
    <link rel="stylesheet" href="profile.css">
</head>
<body>
    <h1>Student Profile</h1>
    <img src='dp.webp' alt="Profile pic" style="width: 300px; height: 200px; border-radius: 10px;">
    
    <?php
        $sql = "SELECT * FROM `student` WHERE `studentID` = ".$_SESSION['logger'];
        $result= mysqli_query($con, $sql);
        $row=mysqli_fetch_assoc($result);
    ?>
    <?php
        echo "Roll no.: " .$_SESSION['logger'];
    ?>
    <br>
    <?php
    if (isset($_POST['edit'])){
        echo "<form action='profile.php' method='POST'>
                Name: <input type='text' name='name' placeholder='Name' value='".$row['Name']."'><br>
                Email: <input type='text' name='email' placeholder='Email' value='".$row['Email']."'><br>
                Password: <input type='text' name='passwd' placeholder='Change Password'  value='".$row['Password']."'><br>
                <button type='submit' name='update'>Update</button>
            </form>";
    }
    else{
        echo "Name: " .$row['Name'];
        echo "<br>";
        echo "Email: " .$row['Email'];
        echo "<br>";
        echo "Password: " .$row['Password'];
        echo "<br>";
        echo "<form action='profile.php' method='POST'>
                <button type='submit' name='edit'>Edit Details</button>
            </form>";
    }
    echo "<a href='home.php'>Dashboard</a>"
    ?>
</body>
</html>
