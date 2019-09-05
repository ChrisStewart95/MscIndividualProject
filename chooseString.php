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
				<a class="btn btn-light" href="chooseDiff.php"><strong>Back</strong></a>
			</div>
			
			<!-- The div holding the Register link -->
			<div class="col-1 btnCenter">
				<a class="btn btn-light" href="userIndex.php"><strong>Home</strong></a>
			</div>
		</div>
	</div>
	
	
	<div class="container-fluid">
	
		<div class="row">
		
			<div class="col-3 text-center">
			</div>
			<div class="col-6 text-center paddingTop" id="bottomBorderWhite">
				<h1><strong>NotePlay Easy Mode<strong></h1>
			</div>
			<div class="col-3 text-center">
			</div>
		</div>
		
		<div class="row">
			<div class="col-12 text-center" id="diffPad">
				<p><em>On Easy Mode you will be presented with the note of your choosing, and given six seconds to play it. This mode lasts for five rounds.</em></p>
			</div>
		</div>
		
		<div class="row"  id="diffPad">
			<div class="col-4 text-center">
			</div>
			
			<div class="col-4 text-center" id="bottomBorderWhite">
				<h4><strong>Choose a note to practice!</strong></h4>
			</div>
			
			<div class="col-4 text-center">
			</div>
		</div>
		
		<div class="row">
			<div class="col-4 text-center">
			</div>
			
			<div class="col-4 text-center paddingTop"">
				<form action='NotePlayEasy.php' method='POST' enctype='multipart/form-data'>
				<div class="form-group">
					<label class="radio-inline">
						<input type="radio" name="string" id="string1" value="E2"> E<sup>2</sup> 
					</label>
					<label class="radio-inline">
						<input type="radio" name="string" id="string2" value="A"> A 
					</label>
					<label class="radio-inline">
						<input type="radio" name="string" id="string3" value="D"> D 
					</label>
					<label class="radio-inline">
						<input type="radio" name="string" id="string4" value="G"> G 
					</label>
					<label class="radio-inline">
						<input type="radio" name="string" id="string5" value="B"> B 
					</label>
					<label class="radio-inline">
						<input type="radio" name="string" id="string1" value="E4"> E<sup>4</sup> 
					</label>
				</div>
					
					<button type="submit" name="submitBtnString" class="btn btn-light"><strong>Take me to NotePlay</strong></button>
				</form>
			</div>
			
			<div class="col-4 text-center">
			</div>
		</div>
	</div>
	
		
</body>
</html>