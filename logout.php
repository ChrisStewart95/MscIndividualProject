<?php

session_start();
include('../conn.php');

if (!isset($_SESSION['40126571_userName'])){
		//Throwing the user back to the website index page (not userindex)
		header('Location: ../index.html');
	} else {
		unset($_SESSION['40126571_userID']);
		unset($_SESSION['40126571_userType']);
		unset($_SESSION['40126571_userProfilePic']);
		unset($_SESSION['40126571_userName']);
		
		header('Location: index.html');
	}
	
?>