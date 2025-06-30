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
                echo $row['Title']. " completed. 🎉 Congratulations!<br>";
            }
        }
?>
