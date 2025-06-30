<?php
    //connect to database
    $con=mysqli_connect("localhost", "root", "", "onlinelearning");
    if (!$con){
        die("Sorry! Failed to connect: ". mysqli_connect_error());
    }
    session_start();
    //Function to don't let anyone unauthorized enter.
    if (!isset($_SESSION['logger'])){
        session_destroy();
        header("Location: index.php");
        exit();
    }
    
    
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Learning Portal</title>
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="sidebar.css">
</head>

<body>
    <div class="wrapper">
        <aside id="sidebar">
            <div class="d-flex">
                <button class="toggle-btn" type="button">
                    <i class="lni lni-grid-alt"></i>
                </button>
                <div class="sidebar-logo">
                    <a href="home.php">Online Learning Portal</a>
                </div>
            </div>
            <ul class="sidebar-nav">
                <li class="sidebar-item">
                    <a href="profile.php" class="sidebar-link">
                        <i class="lni lni-user"></i>
                        <span>Profile</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="#" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse"
                        data-bs-target="#auth" aria-expanded="false" aria-controls="auth">
                        <i class="lni lni-agenda"></i>
                        <span>Courses</span>
                    </a>
                    <ul id="auth" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                        <li class="sidebar-item">
                            <a href="courseList.php" class="sidebar-link">Your Courses</a>
                        </li>
                        <li class="sidebar-item">
                            <a href="courseRegistration.php" class="sidebar-link">Course Registration</a>
                        </li>
                    </ul>
                </li>
                
                <li class="sidebar-item">
                    <a href="studentPayment.php" class="sidebar-link">
                        <i class="lni lni-protection"></i>
                        <span>Your Payments</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="notifications.php" class="sidebar-link">
                        <i class="lni lni-popup"></i>
                        <span>Notifications</span>
                    </a>
                </li>
            </ul>
            <div class="sidebar-footer">
                <a href="index.php" class="sidebar-link">
                    <i class="lni lni-exit"></i>
                    <span>Logout</span>
                </a>
            </div>
        </aside>



        <div class="main p-3">
            <div class="text-center">
                <h1>Student Dashboard</h1>
<?php
    if (isset($_SESSION['logger'])){
        $sql = "SELECT * FROM `student` WHERE StudentID =".$_SESSION['logger'];
        $result = mysqli_query($con, $sql);
        $row=mysqli_fetch_assoc($result);
        echo "HELLO, ". $row['Name'];
    }
?>
        <form action="home.php" method="POST">
            <button type="submit" name="checkCertificate">Get Certificates</button>
        </form>
            </div>
        <?php
        if (isset($_POST['checkCertificate'])){
        $sql = "SELECT * FROM course 
                WHERE CourseID IN (
                    SELECT CourseID FROM registration WHERE StudentID = ".$_SESSION['logger']."
                )";
        $result = mysqli_query($con, $sql);
        while ($row = mysqli_fetch_assoc($result)){
            $course = $row['CourseID'];
    
            // Get number of clicks by the student for this course
            $subsql_clicks = "SELECT * FROM `content_clicks` WHERE StudentID = ".$_SESSION['logger']." AND ContentID IN (
                SELECT ContentID FROM `content` WHERE CourseID = ".$course."
            )";
            $subres_clicks = mysqli_query($con, $subsql_clicks);
            $count = mysqli_num_rows($subres_clicks);
    
            // Get number of contents for this course
            $subsql_content = "SELECT * FROM `content` WHERE CourseID = ".$course;
            $subres_content = mysqli_query($con, $subsql_content);
            $count2 = mysqli_num_rows($subres_content);
    
            // If all content is clicked, course is completed
            if ($count == $count2){
                header("Location: certificate.php");
                exit();
                echo $row['Title']. " completed. 🎉 Congratulations!<br>";
            }
        }
    }
    ?>
            <h1>Suggested Courses:</h1><br>
            <?php
                $sql = "SELECT * FROM course 
                    WHERE CourseID NOT IN (
                        SELECT CourseID FROM registration WHERE StudentID = ".$_SESSION['logger']."
                    )";
                $result = mysqli_query($con, $sql);
                $num = mysqli_num_rows($result);
                $sno=1;
                
                if ($num == 0) echo "Already registered  for all courses.";
                while ($row=mysqli_fetch_assoc($result)){
                    echo $sno . ". ";
                    echo $row['Title'];
                    echo "<br>";
                    $sql1 = "SELECT * FROM `instructor` WHERE InstructorID = ".$row['InstructorID'];
                    $res1 = mysqli_query($con, $sql1);
                    $x = mysqli_fetch_assoc($res1);
                    echo "Instructor: ".$x['Name'];
                    echo "<hr>";
                    $sno++;
                }
            ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
        crossorigin="anonymous"></script>
    <script src="script.js"></script>
</body>

</html>