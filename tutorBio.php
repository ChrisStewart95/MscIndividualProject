<?php
	session_start();
	
	include('../conn.php');
	
	if (!isset($_SESSION['40126571_userName'])){
		//Throwing the user back to the website index page
		header('Location: ../index.html');
	}
	
	// Grabbing the dynamic session variables established in the login.php page and saving them to more intuitively named variables for ease of use
	$dynamicUsername = $_SESSION['40126571_userName'];
	$dynamicUserID = $_SESSION['40126571_userID'];
	$dyanmicUserType = $_SESSION['40126571_userType'];
	
	// If the current session does not belong to a tutor, kick them out.
	if ($dyanmicUserType != 'Tutor'){
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
		<div class="row" id="bannerTutor">
			<div class="col-10">
				<h1><strong><em>Acoustica</strong> <span id="tutorBanner">Tutor</span></em></h1>
			</div>
			
			<!-- The div holding the Login link -->
			<div class="col-1 btnCenter">
				<a class="btn btn-light" href="editTuAccount.php"><strong>Edit Account Details</strong></a>
			</div>
			
			<!-- The div holding the Register link -->
			<div class="col-1 btnCenter">
				<a class="btn btn-light" href="userIndex.php"><strong>Home</strong></a>
			</div>
		</div>
	</div>
	
	<!-- ADD BIO FORM - A container holding a row with one column -->
	<div class="container" id="formWidth">
		<div class="row paddingTop">
			<div class="col-12 text-center">
				<form action='tutorBio.php' method='POST' enctype='multipart/form-data'>
					<div class="form-group">
						<div class="form-group">
							<label for="postBio">Your Biography</label>
							​<textarea id="txtArea" rows="5" cols="70" name="postBio" id="postBio"  maxlength="500" placeholder="Your biography here. Keep it short and sweet!" required></textarea>
							<!--<input type="text" class="form-control" name="postBio" id="postBio"  maxlength="500" placeholder="Your biography here. Keep it short and sweet!" required>-->
						</div>
					</div>
					
					<button type="submit" name="submitBtn" class="btn btn-light" id="forgotBtn"><strong>Submit Bio</strong></button>
				</form>
			</div>
		</div>
	</div>
	
	
<?php

if(isset($_POST['submitBtn'])){
	// Getting the form data 
	$tutorBio = $conn->real_escape_string($_POST['postBio']);
	
	// Reading to check if the tutor already has a bio
	$checkBio = "SELECT * FROM AC_TutorBio WHERE tutorID = '$dynamicUserID'";
		
	$resultCheck = $conn->query($checkBio);
	
	if(!$resultCheck){
		echo $conn->error;
	}
		
	$numExisting = $resultCheck->num_rows;
	
	if ($numExisting == 0){
		
	
		// If the var holding the info from the bio field is null, stop the upload
		if($tutorBio == null) {
			echo "<div class='container' id='formWidth'>
				<div class='row paddingTop'>
					<div class='col-12'>
						<div class='alert alert-warning alert-dismissible fade show' role='alert'>
							You haven't written anything!
							  <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
								<span aria-hidden='true'>&times;</span>
							</button>
						</div>
					</div>
				</div>
			</div>";
		} else {
				$insertBio = "INSERT INTO AC_TutorBio (tutorID, bio) 
				VALUES ('$dynamicUserID', '$tutorBio')";
				
				$resultInsert = $conn->query($insertBio);
				
				if(!$resultInsert){
					echo $conn->error;
				} else {
				
					echo "<div class='container' id='formWidth'>
							<div class='row paddingTop'>
								<div class='col-12'>
									<div class='alert alert-success alert-dismissible fade show' role='alert'>
										Congratulations, biography added!
										<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
											<span aria-hidden='true'>&times;</span>
										</button>
									</div>
								</div>
							</div>
						</div>";
				}
		}
	} else if ($numExisting == 1){
			
		// If the var holding the info from the bio field is null, stop the upload
		if($tutorBio == null) {
			echo "<div class='container' id='formWidth'>
				<div class='row paddingTop'>
					<div class='col-12'>
						<div class='alert alert-warning alert-dismissible fade show' role='alert'>
							You haven't written anything!
							  <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
								<span aria-hidden='true'>&times;</span>
							</button>
						</div>
					</div>
				</div>
			</div>";
		} else {
		
				$updateBio = "UPDATE AC_TutorBio SET bio = '$tutorBio' WHERE tutorID='$dynamicUserID'";
				
				$resultUpdate = $conn->query($updateBio);
				
				if(!$resultUpdate){
					echo $conn->error;
				} else {
				
					echo "<div class='container' id='formWidth'>
							<div class='row paddingTop'>
								<div class='col-12'>
									<div class='alert alert-success alert-dismissible fade show' role='alert'>
										Congratulations, biography updated!
										<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
											<span aria-hidden='true'>&times;</span>
										</button>
									</div>
								</div>
							</div>
						</div>";
				}
		}
	}
}
	
?>



<!-- Enabling bootstraps JS to function. This must stay at the bottom of the html, just before the </body> tag -->
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	
	
</body>
</html>