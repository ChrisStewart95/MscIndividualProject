<!DOCTYPE html>
<html lang="en">
<head>
  <title>Acoustica</title>
  
  <link rel="stylesheet" href="ui.css"/>
	
	<link rel="shortcut icon" type="img/png" href="imgs/favicon.png"/>
  
  	<!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	
	<!-- Adding the Bootstrap stylesheet -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<body id="baseBackground">
	<!-- THE HEADER - A container holding a row with three columns -->
	<div class="container-fluid">
		<div class="row" id="bannerNeutral">
			<div class="col-10">
				<h1><strong><em>Acoustica</strong></em></h1>
			</div>
			
			<div class="col-1 btnCenter">
				<a class="btn btn-light" href="index.html"><strong>Home</strong></a>
			</div>
			
			<div class="col-1 btnCenter">
				<a class="btn btn-light" href="register.php"><strong>Register</strong></a>
			</div>
		</div>
	</div>
	
	<!-- WELCOME MESSAGE - A container holding a row with one column -->
	<div class="container-fluid">
		<div class="row paddingTop">
			<div class="col-12 text-center">
				<h2><strong>Welcome back</strong></h2>
				<img src="imgs/guitarImage4.jpg" height="200" width="200">
			</div>
		</div>
	</div>
	
	<!-- LINK TO REG -  A container holding a row with three column -->
	<div class="container-fluid">
		<div class="row">
			<div class="col-4">
			</div>
			
			<div class="col-4 text-center" id="bottomBorderWhite">
				New to Acoustica? <a href="register.php">Click here to register</a></p>
			</div>
			
			<div class="col-4">
			</div>
		</div>
	</div>

	<!-- LOGIN FORM - A container holding a row with one column -->
	<div class="container" id="formWidth">
		<div class="row paddingTop">
			<div class="col-12 text-center">
				<form action='login.php' method='POST' enctype='multipart/form-data'>
					<div class="form-group">
						<label for="postuser"><strong>Username</strong></label>
						<input type="text" class="form-control" name="postuser" id="postuser" maxlength="15" placeholder="Enter Username" required>
					</div>
					
					<div class="form-group">
						<label for="postpass"><strong>Password</strong></label>
						<input type="password" class="form-control" name="postpass" id="postpass" maxlength="15" placeholder="Enter Password" required>
						<small id="passHelp" class="form-text text-muted"><a href='forgotPass.php'>I forgot my password<a></small>
					</div>
					
					<button type="submit" name="submitBtn" class="btn btn-light" id="LoginBtn"><strong>Login</strong></button>
				</form>
			</div>
		</div>
	</div>
	
<?php
if(isset($_POST['submitBtn'])){
	
	session_start();

	//Include the connection to the SQL database
	include('conn.php');

	// Create vars for the name and password pushed from the form in login.php
	$user = $conn->real_escape_string($_POST['postuser']);
	$pass = $conn->real_escape_string($_POST['postpass']);
	
	// Check this username and pw against the created rows in the SQL table
	$checkuser = "SELECT * FROM AC_Users WHERE username='$user' AND password='$pass'";
	// Query the database with the above SQL statements
	$result = $conn->query($checkuser);
	// Check to make sure that the query RE: checkuser was successful, if not, throw an error
	if(!$result){
		echo $conn->error;
	}
	
	// Iterating through the rows in order to grab the userID and userType and save them to session vars
	while($row = $result->fetch_assoc()){
		$userID = $row['userid'];
		$userType = $row['userType'];
		$userPP = $row['profilePic'];
		$_SESSION['40126571_userID']=$userID;
		$_SESSION['40126571_userType']=$userType;
		$_SESSION['40126571_userProfilePic']=$userPP;
	}
	
	// This sets the num variable to the number of rows returned from the above query.
	// This will either hold ONE or ZERO, and it will ONLY return ONE if the username and password MATCH
	// an existing user account on the database.
	$num = $result->num_rows;
	
	// If successful, create a session and send the user to userIndex
	if($num == 1){
		$_SESSION['40126571_userName']=$user;
		
		if($userType == 'Student'){
			header('location: stUsers/userIndex.php');
		} else if ($userType == 'Tutor'){
			header('location: tuUsers/userIndex.php');
		} else {
			echo "<div class='container' id='formWidth'>
				<div class='row paddingTop'>
					<div class='col-12'>
						<div class='alert alert-warning alert-dismissible fade show' role='alert'>
							<p>The login details correspond to an account without a userType. Contact programmer for aid, as this really shouldnt be possible.</p>
							<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
								<span aria-hidden='true'>&times;</span>
							</button>
						</div>
					</div>
				</div>
			</div>";
		}
	} else if ($num == 0) {
		echo "<div class='container' id='formWidth'>
				<div class='row paddingTop'>
					<div class='col-12'>
						<div class='alert alert-warning alert-dismissible fade show' role='alert'>
							<p>Incorrect login details! Please try again.</p>
							<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
								<span aria-hidden='true'>&times;</span>
							</button>
						</div>
					</div>
				</div>
			</div>";
	} else {
		echo "<div class='container' id='formWidth'>
				<div class='row paddingTop'>
					<div class='col-12'>
						<div class='alert alert-warning alert-dismissible fade show' role='alert'>
							<p>Error, more than one account with those details on the database. Contact admin for assistance.</p>
							<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
								<span aria-hidden='true'>&times;</span>
							</button>
						</div>
					</div>
				</div>
			</div>";
	}
}

?>
	
	<!-- Enabling bootstraps JS to function. This must stay at the bottom of the html, just before the </body> tag -->
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

</body>
</html>