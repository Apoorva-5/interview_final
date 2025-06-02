<?php
session_start();
include('../dbconnection.php');
error_reporting(0);
		$id=$_GET['delete'];
	    mysqli_query($conn,"delete from question_bank where id = '$id'");
	    header('location:question.php');
?>