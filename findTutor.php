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
	
	// If the current session does not belong to a tutor, kick them out.
	if ($dyanmicUserType != 'Student'){
		header('Location: ../index.html');
	}
	
	$read = "SELECT * FROM AC_Users WHERE userType='Tutor'";
	
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
	
	<!-- Add icon library -->
	<script src="https://kit.fontawesome.com/60a2ed97ba.js"></script>
	
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
			<div class="col-12 text-center">
				<h3>Find a Tutor</h3>
			</div>
		</div>
	</div>
	
	<!--SEARCH FUNCTIONALITY HERE -->
	<div class="container">
		<div class="row">
			<nav class="navbar navbar-expand-lg navbar-light bg-light col-12">
				<div class="collapse navbar-collapse" id="navbarNavAltMarkup">
					<form class="form-inline my-2 my-lg-0" action = 'searchTutor.php' method='POST' enctype='multipart/form-data'>
						<input class="form-control" type="search" name='searchbar' placeholder="Search" aria-label="Search">
						<button class="btn btn-outline-secondary" type="submit">Search</button>
					</form>
				</div>
			</nav>
		</div>
	</div>
	
	<div class="container-fluid">
		<div class="row">
			<?php 
				while($row = $result->fetch_assoc()){
					$tutorName = $row['username'];
					$tutorID = $row['userid'];
					$tutorPic = $row['profilePic'];
								echo 	"<div class='col-1'>
								</div>
								
								<div class='col-2 text-center'>
								
										<div class='indexOptionStu menuHover'>
											<img src='../imgs/$tutorPic' class='menuImg'>
											<div class='menuTextStu'>
												<p>$tutorName</p>
											</div>
										</div>
										
										<form action='specTutor.php' method='POST' enctype='multipart/form-data'>
											<input type = 'hidden' value='$tutorID' name='hiddenTutorID'>
											<button type='submit' name='submitBtnView' class='btn btn-info btnPadding'><i class='fas fa-search'></i></button>
										</form>
								</div>
								
								<div class='col-1'>
								</div>";
				}
			?>
		</div>
	</div>
	
</body>
</html>