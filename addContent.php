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
    //addContent function
    if (isset($_POST['addContent'])){
        $sql = "INSERT INTO `content` (`CourseID`, `Title`, `Type`, `LinkOrPath`) 
                VALUES (".$_SESSION['CourseID'].", '".$_POST['title']."', '".$_POST['contentType']."', '".$_POST['link']."')";
        $result = mysqli_query($con, $sql);
        if ($result && $_POST['contentType'] == 'Exam') {
            // Get the last inserted ContentID
            $contentID = mysqli_insert_id($con);
            // Now insert into exam table
            $sql = "INSERT INTO `exam` (`ExamID`, `ContentID`, `Title`) 
                    VALUES ('$contentID', '$contentID', '".$_POST['title']."')";
            mysqli_query($con, $sql);
        }
    }
    //deleteContent function
    if (isset($_POST['deleteContent'])){
        $sql = "DELETE FROM `content` 
                WHERE `ContentID` = ".$_POST['deleteContent'];
        $result = mysqli_query($con, $sql);
    }
    if (isset($_POST['updateLink'])){
        $sql = "UPDATE `content` SET `LinkOrPath` = '".$_POST['link']."' 
                WHERE ContentID = ".$_POST['updateLink'];
        $result = mysqli_query($con, $sql);
    }
    //manageExam function
    if (isset($_POST['manageExam'])){
        $_SESSION['currExam'] = $_POST['manageExam'];
        header("Location: manageExam.php");
        exit();
    }
    if (isset($_POST['addAnnouncement'])){
        $sql = "INSERT INTO `announcement` (`CourseID`, `Message`)
        VALUES(".$_SESSION['CourseID'].", '".$_POST['announcement']."')";
        $result = mysqli_query($con, $sql);
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
    <hr>
    <p>Add Content Form</p>
    <form action="addContent.php" method='post'>
        <input type="radio" name="contentType" value="Video" required> Video
        <input type="radio" name="contentType" value="File"> File
        <input type="radio" name="contentType" value="Exam"> Exam<br>
        <input type="text" placeholder="Enter Link/Path" name='link'><br>
        <input type="text" placeholder="Enter Title" name='title' required><br>
    <button type="submit" name='addContent'>Add Content</button>
    </form><hr>

    <p>Make an announcement</p>
    <form action="addContent.php" method='post'>
        <input type="text" placeholder="Add an announcement" name='announcement' required><br>
    <button type="submit" name='addAnnouncement'>Add Announcement</button>
    </form>

    <hr>
            <div class="text-center">
                
                <table class="table" id="myTable" border="1" cellpadding="10" cellspacing="0">
                    <thead>
                        <tr>
                            <th scope="col">S.No</th>
                            <th scope="col">Content Type</th>
                            <th scope="col">Content Title</th>
                            <th scope="col">Link/Path</th>
                            <th scope="col">Upload Date</th>
                            <th scope="col">Operations</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $sql = "SELECT * FROM `content` WHERE `CourseID` = ".$_SESSION['CourseID'];
                            $result = mysqli_query($con, $sql);
                            
                            $sno=1;
                            while ($row = mysqli_fetch_assoc($result)) {   
                                echo "<tr>";
                                echo "<td>" . $sno . "</td>";
                                echo "<td>" . $row['Type'] . "</td>";
                                echo "<td>" . $row['Title'] . "</td>";
                                if ($row['Type']=='Exam'){    
                                    echo "<td><a href='" . $row['LinkOrPath'] . "'>" . $row['LinkOrPath'] . "</a></td>";
                                    echo "<td>" . $row['UploadDate'] . "</td>";
                                    echo "<td>
                                    <form action='addContent.php' method='post'>
                                        <button type='submit' name='manageExam' value=".$row['ContentID'].">Manage Questions</button>
                                    </form>
                                    </td>";
                                    echo "<td>
                                    <form action='addContent.php' method='post'>
                                        <button type='submit' name='deleteContent' value='".$row['ContentID']."'>Delete</button>
                                    </form>
                                    </td>";
                                }else{
                                    if (isset($_POST['editContent']) && $_POST['editContent']==$row['ContentID']){
                                        echo "<form action='addContent.php' method='post'>
                                            <td><input type='text' placeholder='Link/Path' name='link' value='" . $row['LinkOrPath'] . "'></td>
                                            <td>" . $row['UploadDate'] . "</td>
                                            <td><button type='submit' name='updateLink' value='".$row['ContentID']."'>Update Link</button></td>
                                        </form>";
                                    }
                                    else{
                                        echo "<td><a href='" . $row['LinkOrPath'] . "'>" . $row['LinkOrPath'] . "</a></td>";
                                        echo "<td>" . $row['UploadDate'] . "</td>";
                                    }
                                    echo "<td>
                                    <form action='addContent.php' method='post'>
                                        <button type='submit' name='editContent' value=".$row['ContentID'].">Edit Link</button>
                                    </form>
                                    </td>";
                                    echo "<td>
                                    <form action='addContent.php' method='post'>
                                        <button type='submit' name='deleteContent' value='".$row['ContentID']."'>Delete</button>
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