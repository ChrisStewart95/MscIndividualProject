<?php
	session_start();
	
	include('../conn.php');
	
	if (!isset($_SESSION['40126571_userName'])){
		//Throwing the user back to the website index page (not userindex)
		header('Location: ../index.html');
	}
	
	// Grabbing the dynamic session variables established in the login.php page and saving them to more intuitively named variables for ease of use
	$dynamicUsername = $_SESSION['40126571_userName'];
	$dynamicUserID = $_SESSION['40126571_userID'];
	$dyanmicUserType = $_SESSION['40126571_userType'];
	
	// If the current session does not belong to a Student, kick them out.
	if ($dyanmicUserType != 'Student'){
		header('Location: ../index.html');
	}
	
	$read = "SELECT * FROM AC_Users WHERE userid='$dynamicUserID'";
	
	$result = $conn->query($read);
	
	if(!$result){
		echo $conn->error;
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Acoustica</title>
  
  <link rel="stylesheet" href="../ui.css"/>
  <link rel="shortcut icon" type="img/png" href="../imgs/favicon.png"/>
  
  	<!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	
	<!-- Adding the Bootstrap stylesheet -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<body id="baseBackground">
	<div class="container-fluid">
		<div class="row" id="banner">
			<div class="col-10">
				<h1><strong><em>Acoustica</strong> <span id="studentBanner">Student</span></em></h1>
			</div>
			
			<!-- The div holding the Login link -->
			<div class="col-1 btnCenter">
			
			</div>
			
			<!-- The div holding the Register link -->
			<div class="col-1 btnCenter">
				<a class="btn btn-light" href="userIndex.php"><strong>Home</strong></a>
			</div>
		</div>
	</div>
	
	
	<div class="container-fluid">
		<div class="row">
			<div class="col-3">
				<a href="friendsMenu.php">
					<div class="indexOption menuHover">
						<img src="../imgs/handshake.jpg" class='menuImg'>
							<div class="menuText">
								<h2><strong>Add Friend</strong></h2>
								<br>
								<h5><em>Add a new guitarist to your friendslist</em></h5>
							</div>
					</div>
				</a>
			</div>
			
			<div class="col-6">
				<a href="myFriends.php">
					<div class="indexOption menuHover">
							<img src="../imgs/myFriendsImage1Free.jpg" class='menuImg'>
							<div class="menuText">
								<h2><strong>Social Hub</strong></h2>
								<br>
								<h5><em>See how your friends are getting on in Acoustica!</em></h5>
							</div>	
					</div>
				</a>
			</div>
			
			<div class="col-3">
				<a href="myTutor.php">
					<div class="indexOption menuHover">
						<img src="../imgs/sheetMusic.jpg" class='menuImg'>
							<div class="menuText">
								<h2><strong>Your Tutor</strong></h2>
								<br>
								<h5><em>View your tutors profile</em></h5>
							</div>
					</div>
				</a>
			</div>
			
		</div>
	</div>
</body>
</html>