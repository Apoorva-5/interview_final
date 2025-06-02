<?php
session_start();
include('../dbconnection.php');
error_reporting(0);

$exam_id = $_GET['delete'];
mysqli_query($conn, "DELETE FROM exam_schedule WHERE exam_id = '$exam_id'");
header('location:scheduleTest.php');
?>
