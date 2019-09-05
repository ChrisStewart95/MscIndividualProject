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
				<h1><strong>NotePlay Advanced Mode<strong></h1>
			</div>
			<div class="col-3 text-center">
			</div>
		</div>
		
		<div class="row">
			<div class="col-12 text-center" id="diffPad">
				<p><em>On Advanced Mode you can configure the game however you want. You can select any number or combination of notes you wish to practice, the speed at which you are expected to play, and the number of rounds.</em></p>
			</div>
		</div>
		
		<div class="row"  id="diffPad">
			<div class="col-4 text-center">
			</div>
			
			<div class="col-4 text-center" id="bottomBorderWhite">
				<h4><strong>Configure your game!</strong></h4>
			</div>
			
			<div class="col-4 text-center">
			</div>
		</div>
		
		<!-- Code which allows a user to choose one, or multiple, strings to practice -->
		<div class="row">
			<div class="col-12 text-center paddingTop">
				<form action='NotePlayHard.php' method='POST' enctype='multipart/form-data'>
				<div class="form-group">
				<p><em>Notes</em></p>
					<label class="checkbox-inline">
						<input type="checkbox" id="inlineCheckbox1" name="String[]" value="E2"> E<sup>2</sup> String
					</label>
				
					<label class="checkbox-inline">
						<input type="checkbox" id="inlineCheckbox2" name="String[]" value="A"> A String
					</label>
				
					<label class="checkbox-inline">
						<input type="checkbox" id="inlineCheckbox3" name="String[]" value="D"> D String
					</label>

					<label class="checkbox-inline">
						<input type="checkbox" id="inlineCheckbox4" name="String[]" value="G"> G String
					</label>

					<label class="checkbox-inline">
						<input type="checkbox" id="inlineCheckbox5" name="String[]" value="B"> B String
					</label>
					
					<label class="checkbox-inline">
						<input type="checkbox" id="inlineCheckbox1" name="String[]" value="E4"> E<sup>4</sup> String
					</label>
				</div>
				
				<div class="form-group">
				<p><em>Speed</em></p>
					<label class="radio-inline">
						<input type="radio" id="inlineRadioOptions1" name="Timer" value="5000"> Slow (easiest)
					</label>
				
					<label class="radio-inline">
						<input type="radio" id="inlineRadioOptions2" name="Timer" value="4000"> Normal
					</label>
				
					<label class="radio-inline">
						<input type="radio" id="inlineRadioOptions3" name="Timer" value="3000"> Fast (hardest)
					</label>
				</div>
				
				<div class="form-group">
				<p><em>Number of Rounds</em></p>
					<label class="radio-inline">
						<input type="radio" id="inlineRadioOptions4" name="numOfRounds" value="5"> 5
					</label>
				
					<label class="radio-inline">
						<input type="radio" id="inlineRadioOptions5" name="numOfRounds" value="10"> 10
					</label>
				
					<label class="radio-inline">
						<input type="radio" id="inlineRadioOptions6" name="numOfRounds" value="20"> 20
					</label>
				</div>
					
					<button type="submit" name="submitBtnStrings" class="btn btn-light"><strong>Take me to NotePlay!</strong></button>
				</form>
			</div>
		</div>
	</div>
	
		
</body>
</html>