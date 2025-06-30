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
        header("Location: instructorLogin.php");
        exit();
    }
    if (isset($_POST['addQuestion'])){
        $sql = "SELECT * FROM `questions`";
        $result= mysqli_query($con, $sql);
        $num= mysqli_num_rows($result);

        $sql= "INSERT INTO `questions` (ExamID, Question, A, B, C, D, Correct)
        VALUES (".$_SESSION['currExam'].", '".$_POST['question']."', '".$_POST['A']."', '".$_POST['B']."', '".$_POST['C']."', '".$_POST['D']."', '".$_POST['correct']."')";
        $result= mysqli_query($con, $sql);
        echo "Question added successfully.";
    }
    if (isset($_POST['update'])){
        $sql = "UPDATE `questions` 
        SET Question='".$_POST['question']."', 
        A='".$_POST['A']."', 
        B='".$_POST['B']."', 
        C='".$_POST['C']."', 
        D='".$_POST['D']."', 
        Correct='".$_POST['correct']."'
        WHERE QuestionID=".$_POST['update'];
        $result=mysqli_query($con, $sql);
        echo "Updated successfully.";
    }
    if (isset($_POST['delete'])){
        $sql = "DELETE FROM `questions` WHERE QuestionID =". $_POST['delete'];
        $result=mysqli_query($con, $sql);
        echo "Deleted Successfully.";
    }
    if (isset($_POST['addDuration'])){
        $sql = "UPDATE `exam` 
        SET duration='".$_POST['duration']."'
        WHERE ExamID=".$_SESSION['currExam'];
        $result=mysqli_query($con, $sql);
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Learning System</title>
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
                    <a href="adminDashboard.php">Online Learning System</a>
                </div>
            </div>
            <ul class="sidebar-nav">
                <li class="sidebar-item">
                    <a href="instructorProfile.php" class="sidebar-link">
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
                            <a href="addCourse.php" class="sidebar-link">Add a new Course</a>
                        </li>
                        <li class="sidebar-item">
                            <a href="manageCourse.php" class="sidebar-link">Manage an existing Course</a>
                        </li>
                    </ul>
                </li>
                
                <li class="sidebar-item">
                    <a href="instructorPayment.php" class="sidebar-link">
                        <i class="lni lni-protection"></i>
                        <span>Payments</span>
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
        <h1>Admin Dashboard</h1>
<?php
    if (isset($_SESSION['logger'])){
        $sql = "SELECT * FROM `instructor` WHERE `InstructorID` =".$_SESSION['logger'];
        $result = mysqli_query($con, $sql);
        $row=mysqli_fetch_assoc($result);
        echo "HELLO, ". $row['Name'];
    }
    if (isset($_SESSION['currExam'])){
        $sql = "SELECT * FROM `exam` WHERE `ExamID` =".$_SESSION['currExam'];
        $result = mysqli_query($con, $sql);
        $row=mysqli_fetch_assoc($result);
        echo "<h1>". $row['title'] ."</h1>";
        echo "<br> Duration: ";
        if ($row['duration']==NULL){
            echo "<form action='manageExam.php' method='POST'>
                <input type='number' name='duration' placeholder='Enter duration' required>
                <button type='submit' name='addDuration'>Add Duration</button><br>
            </form>";
        }else {
            echo $row['duration'];
        }
    }
?>
            <form action="manageExam.php" method="POST">
                <input type="text" name="question" placeholder="Enter the question" required><br>
                <input type="text" name="A" placeholder="Option A" required><br>
                <input type="text" name="B" placeholder="Option B" required><br>
                <input type="text" name="C" placeholder="Option C" required><br>
                <input type="text" name="D" placeholder="Option D" required><br>
                <input type="text" name="correct" placeholder="A/B/C/D" required><br>
                <button type="submit" name="addQuestion">Add this question</button><br>
            </form>
            <?php
                $sql= "SELECT * FROM `questions` WHERE ExamID =".$_SESSION['currExam'];
                $result = mysqli_query($con, $sql);
                $sno=1;
                while ($row=mysqli_fetch_assoc($result)){
                    if (!isset($_POST['edit'])){
                        echo $sno." ".$row['Question']."<br>A. ";
                        echo $row['A']."<br>B. ";
                        echo $row['B']."<br>C. ";
                        echo $row['C']."<br>D. ";
                        echo $row['D']."<br><br>";
                        echo "Correct: ".$row['Correct']."<br>";
                        echo "
                            <form action='manageExam.php' method='POST'>
                                <button type='submit' name='edit' value='".$row['QuestionID']."'>Edit Question</button>
                                <button type='submit' name='delete' value='".$row['QuestionID']."'>Delete Question</button>
                            </form>";
                    }
                    else if ($_POST['edit']!=$row['question_id']){
                        echo $sno." ".$row['Question']."<br>A. ";
                        echo $row['A']."<br>B. ";
                        echo $row['B']."<br>C. ";
                        echo $row['C']."<br>D. ";
                        echo $row['D']."<br><br>";
                        echo "Correct: ".$row['Correct']."<br>";
                        echo "
                            <form action='manageExam.php' method='POST'>
                                <button type='submit' name='edit' value='".$row['QuestionID']."'>Edit Question</button>
                                <button type='submit' name='delete' value='".$row['QuestionID']."'>Delete Question</button>
                            </form>";
                    }
                    else{
                        echo "<form action='manageExam.php' method='POST'>";
                        echo "Question: <input type='text' name='question' value='".$row['Question']."'> <br>";
                        echo "Option A: <input type='text' name='A' value='".$row['A']."'> <br>";
                        echo "Option B: <input type='text' name='B' value='".$row['B']."'> <br>";
                        echo "Option C: <input type='text' name='C' value='".$row['C']."'> <br>";
                        echo "Option D: <input type='text' name='D' value='".$row['D']."'> <br>";
                        echo "correct option: <input type='text' name='correct' value='".$row['Correct']."' <br>";
                        echo "  <button type='submit' name='update' value='".$row['QuestionID']."'>Update Question</button>
                                <button type='submit' name='delete' value='".$row['QuestionID']."'>Delete Question</button>";
                        echo "</form>";
                    }

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