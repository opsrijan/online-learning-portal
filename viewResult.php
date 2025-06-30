<?php
    session_start();
    //Function to not let anyone unauthorized enter.
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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Portal</title>
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="sidebar.css">
    <link rel="stylesheet" href="examList.css">
</head>

<body>
    <div class="wrapper">
        <!-- sidebar starts -->
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
        <!-- sidebar ends -->


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
            </div>
            <?php
                //Score
                $sql1 = "SELECT * FROM `exam` WHERE ExamID=". $_SESSION['currExam'];
                $result1 = mysqli_query($con, $sql1);
                $row = mysqli_fetch_assoc($result1);
                echo "<h2>The results of your exam '".$row['title']."' are:</h2>";

                $sql= "SELECT * FROM `evaluations` WHERE ExamID=".$_SESSION['currExam']." AND StudentID=".$_SESSION['logger'];
                $result = mysqli_query($con, $sql);
                $row=mysqli_fetch_assoc($result);
                echo "Score=". $row['Score'];

                $sql= "SELECT * FROM `questions` WHERE ExamID=".$_SESSION['currExam'];
                $result = mysqli_query($con, $sql);
                $num = mysqli_num_rows($result);
                echo "/".$num;
                //Rank
                $sql2="SELECT * FROM `evaluations` WHERE ExamID=".$_SESSION['currExam']." AND score>".$row['Score'];
                $res=mysqli_query($con, $sql2);
                $rank = 1+mysqli_num_rows($res);
                echo "<br>Rank=". $rank;
                $sql2="SELECT * FROM `evaluations` WHERE ExamID=".$_SESSION['currExam'];
                $res=mysqli_query($con, $sql2);
                $total = mysqli_num_rows($res);
                echo "/".$total."<br>";
                //percentile
                echo "Percentile= ".($total-$rank+1)*100/($total);
            ?>

            
            <?php
                echo "<hr>";
                $sql= "SELECT * FROM `questions` WHERE ExamID=".$_SESSION['currExam'];
                $result = mysqli_query($con, $sql);
                $sno=1;
                while ($row=mysqli_fetch_assoc($result)){
                    echo $sno.". Question: ".$row['Question']."<br>";
                    echo "A. ".$row['A']."<br>";
                    echo "B. ".$row['B']."<br>";
                    echo "C. ".$row['C']."<br>";
                    echo "D. ".$row['D']."<br>";
                    echo "Correct Option: ".$row['Correct']."<br>";
                    $sql2 = "SELECT * FROM `student_answers` WHERE question_id=".$row['QuestionID']." AND user_id=".$_SESSION['logger'];
                    $res = mysqli_query($con, $sql2);
                    $x = mysqli_fetch_assoc($res);
                    $num=mysqli_num_rows($res);
                    if ($num!=0){
                        echo "Selected Option: ".$x['selected'];
                    }
                    else{
                        echo "Selected Option: None";
                    }
                    echo "<br><hr>";
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