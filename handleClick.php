<?php
session_start();
if (!isset($_SESSION['logger'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}

if (!isset($_GET['contentID'])) {
    die("No content selected.");
}

$studentID = $_SESSION['logger'];
$contentID = intval($_GET['contentID']);

$con = mysqli_connect("localhost", "root", "", "onlinelearning");
if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Log the click
$sql = "INSERT INTO content_clicks (StudentID, ContentID) VALUES ($studentID, $contentID)";
mysqli_query($con, $sql);

// Fetch the actual link to redirect
$sql = "SELECT LinkOrPath FROM content WHERE ContentID = $contentID";
$result = mysqli_query($con, $sql);
$row = mysqli_fetch_assoc($result);

if ($row) {
    $link = $row['LinkOrPath'];
    header("Location: $link");
    exit();
} else {
    echo "Content not found.";
}
?>
