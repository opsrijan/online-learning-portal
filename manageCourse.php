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
    if (isset($_POST['updateCost'])){
        $sql = "UPDATE `course` SET `Cost` = ".$_POST['update']." WHERE `CourseID` = ".$_POST['updateCost'];
        $result = mysqli_query($con, $sql);
    }
    if (isset($_POST['addContent'])){
        $_SESSION['CourseID'] = $_POST['addContent'];
        header("Location: addContent.php");
        exit();
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
?>
            <div class="text-center">
                
                <table class="table" id="myTable" border="1" cellpadding="10" cellspacing="0">
                    <thead>
                        <tr>
                            <th scope="col">S.No</th>
                            <th scope="col">Title</th>
                            <th scope="col">Category</th>
                            <th scope="col">Cost</th>
                            <th scope="col">Operations</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $sql = "SELECT * FROM `course` WHERE `InstructorID` = '".$_SESSION['logger']."'";
                            $result = mysqli_query($con, $sql);
                            
                            $sno=1;
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td>" . $sno . "</td>";
                                echo "<td>" . $row['Title'] . "</td>";
                                echo "<td>" . $row['Category'] . "</td>";
                                if (isset($_POST['editCost']) && $_POST['editCost']==$row['CourseID']){
                                    echo "
                                    <form action='manageCourse.php' method='post'>
                                        <td><input type='int' placeholder='Update Cost' name='update' value='".$row['Cost']."'></td>
                                        <td><button type='submit' name='updateCost' value='".$row['CourseID']."'>Update Cost</button></td>
                                    </form>";
                                }else{
                                    echo "<td>" . $row['Cost'] . "</td>";
                                    echo "<td>
                                    <form action='manageCourse.php' method='post'>
                                        <button type='submit' name='editCost' value='".$row['CourseID']."'>Edit Cost</button>
                                    </form>
                                    </td>";
                                    echo "<td>
                                    <form action='manageCourse.php' method='post'>
                                        <button type='submit' name='addContent' value='".$row['CourseID']."'>Add/Manage Content</button>
                                    </form>
                                    </td>";
                                }
                                
                                echo "</tr>";
                                $sno++;
                            }
                            mysqli_close($con);
                        ?>
                    </tbody>
                </table>
                <hr>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
        crossorigin="anonymous"></script>
    <script src="script.js"></script>
</body>

</html>