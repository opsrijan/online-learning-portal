<?php
    $insert=false;
    $delete=false;
    $edit=false;

    if (isset($_POST['delete'])) {
        echo "deleted.";
        $con = mysqli_connect("localhost", "root", "", "test");
        if (!$con) {
            die("Sorry, failed to connect: " . mysqli_connect_error());
        }
        $sql = "DELETE FROM `student` WHERE sno=". $_POST['delete'];
        $result = mysqli_query($con, $sql);
    }

    if (isset($_POST['edit'])) {
        echo "updated.";
        $con = mysqli_connect("localhost", "root", "", "test");
        if (!$con) {
            die("Sorry, failed to connect: " . mysqli_connect_error());
        }
        $sql = "DELETE FROM `student` WHERE sno=". $_POST['delete'];
        $result = mysqli_query($con, $sql);
    }

    if (isset($_POST['roll'])) {
        echo "inserted.";
        $con = mysqli_connect("localhost", "root", "", "test");
        if (!$con) {
            die("Sorry, failed to connect: " . mysqli_connect_error());
        }
        
        $roll = $_POST['roll'];
        $name = $_POST['name'];
        $DOB = $_POST['DOB'];
        $branch = $_POST['branch'];
        $phone = $_POST['phone'];
        $GPA = $_POST['GPA'];
        $hostel = $_POST['hostel'];
        $num=rand();
        $sql = "INSERT INTO `student` (`sno`, `roll`, `name`, `DOB`, `branch`, `phone`, `GPA`, `hostel`) 
                VALUES ('$num', '$roll', '$name', '$DOB', '$branch', '$phone', '$GPA', '$hostel')";
        $result = mysqli_query($con, $sql);
        mysqli_close($con);
    }
?>

<!doctype html>
<html lang="en">

<head>
    <title>IITG Student Database</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <h1>IITG Student Database</h1>

    <form action="index.php" method="post">
        <input type="text" name="search" placeholder="Search here" required>
        <button type='submit' class="btn">Search</button>
    </form>

    <hr><hr>

    <form action="index.php" method="post">
        <input type="text" name="roll" placeholder="Enter your roll here" required>
        <input type="text" name="name" placeholder="Enter your name here" required>
        <input type="date" name="DOB" placeholder="Enter your DOB here" required>
        <input type="text" name="branch" placeholder="Enter your branch here" required>
        <input type="text" name="phone" placeholder="Enter your phone here" required>
        <input type="text" name="GPA" placeholder="Enter your GPA here" required>
        <input type="text" name="hostel" placeholder="Enter your hostel here" required>
        <button type='submit' class="btn">Submit</button>
    </form>

    <hr><hr>
    <table class="table" id="myTable" border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th scope="col">S.No</th>
                <th scope="col">Roll</th>
                <th scope="col">Name</th>
                <th scope="col">DOB</th>
                <th scope="col">Branch</th>
                <th scope="col">Phone</th>
                <th scope="col">GPA</th>
                <th scope="col">Hostel</th>
            </tr>
        </thead>
        <tbody>
            <?php
                if (isset($_POST['search'])) {
                    echo "searched.";
                    $con = mysqli_connect("localhost", "root", "", "test");
                    if (!$con) {
                        die("Sorry, failed to connect: " . mysqli_connect_error());
                    }
                    $sql = "SELECT * FROM `student` WHERE CONCAT_WS('', roll, name, DOB, branch, phone, GPA, hostel) LIKE '%". $_POST['search'] ."%'";
                    $result = mysqli_query($con, $sql);
                }
                else{
                    $con = mysqli_connect("localhost", "root", "", "test");
                    if (!$con) {
                        die("Connection failed: " . mysqli_connect_error());
                    }

                    $sql = "SELECT * FROM `student`";
                    $result = mysqli_query($con, $sql);
                }
                $sno=1;
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $sno . "</td>";
                    echo "<td>" . $row['roll'] . "</td>";
                    echo "<td>" . $row['name'] . "</td>";
                    echo "<td>" . $row['DOB'] . "</td>";
                    echo "<td>" . $row['branch'] . "</td>";
                    echo "<td>" . $row['phone'] . "</td>";
                    echo "<td>" . $row['GPA'] . "</td>";
                    echo "<td>" . $row['hostel'] . "</td>";
                    echo "<td>
                    <form action='index.php' method='post'>
                        <button type='submit' name='delete' value='" . $row['sno'] . "'>Delete</button>
                    </form>
                    </td>";
                    echo "<td>
                    <form action='index.php' method='post'>
                        <button type='submit' name='edit' value='" . $row['sno'] . "'>Edit</button>
                    </form>
                    </td>";
                    echo "</tr>";
                    $sno++;
                }

                mysqli_close($con);
            ?>
        </tbody>
    </table>
    <hr>
</body>

</html>