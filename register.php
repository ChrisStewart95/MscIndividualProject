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
	<!-- THE HEADER -  A container holding a row with three columns -->
	<div class="container-fluid">
		<div class="row" id="bannerNeutral">
			<div class="col-10">
				<h1><strong><em>Acoustica</strong></em></h1>
			</div>
			
			<div class="col-1 btnCenter">
				<a class="btn btn-light" href="login.php"><strong>Login</strong></a>
			</div>
			
			<div class="col-1 btnCenter">
				<a class="btn btn-light" href="index.html"><strong>Home</strong></a>
			</div>
		</div>
	</div>
	
	<!-- THE WELCOME MESSAGE + IMAGE - A container holding a row with one column -->
	<div class="container-fluid">
		<div class="row paddingTop">
			<div class="col-12 text-center">
				<h2><strong>Get started with a free account</strong></h2>
				<img src="imgs/guitarImage5.jpg" height="200" width="200">
			</div>
		</div>
	</div>
	
	<!-- ADDITIONAL TEXT -  A container holding a row with three columns -->
	<div class="container-fluid">
		<div class="row">
			<div class="col-4">
			</div>
			
			<div class="col-4 text-center" id="bottomBorderWhite">
				<p>You'll be practicing in no time!</p>
			</div>
			
			<div class="col-4">
			</div>
		</div>
	</div>
	
	<!-- THE REGISTER FORM - A container holding a row with one column -->
	<div class="container" id="formWidth">
		<div class="row paddingTop">
			<div class="col-12 text-center">
				<form action = 'register.php' method='POST' enctype='multipart/form-data'>
					<div class="form-group">
						<label for="uName">Username</label>
						<input type="text" class="form-control" name="uName" id="uName"  maxlength="15" placeholder="Enter Username" required>
					</div>
					
					<div class="form-group">
						<label for="pWord">Password</label>
						<input type="password" class="form-control" name ="pWord" id="pWord" maxlength="15" aria-describedby="passHelp" placeholder="Enter Password" required>
						<small id="passHelp" class="form-text text-muted">Please use a mixture of numbers and letters to create a secure password.</small>
					</div>
					
					<div class="form-group">
						<label for="em">Email address</label>
						<input type="email" class="form-control" name="em" id="em" maxlength="30" placeholder="Enter email" required>
					</div>
					
					<div class="form-group">
						<label for="profilePic">Profile Picture</label>
						<input type="file" class="form-control-file text-center" name="profilePic" id="profilePic" required>
						<small id="passHelp" class="form-text text-muted">Please ensure images submitted are of .jpg, .jpeg, or .png format.</small>
					</div>
					
					<div class="form-group">
						<label for="userType">Are you a student or a tutor?</label>
						<select class="form-control" name="userType" id="userType">
							<option>Student</option>
							<option>Tutor</option>
						</select>
					</div>
			
					<button type="submit" name="submitBtn" class="btn btn-light" id="LoginBtn"><strong>Create Account</strong></button>
				</form>
			</div>
		</div>
	</div>
	
<?php
	include('conn.php');
	
	if(isset($_POST['submitBtn'])){
	
		// Gets the username from the text entered into the form from register.php
		$uName = $conn->real_escape_string($_POST['uName']);
		
		// Gets the pw as above
		$pWord = $conn->real_escape_string($_POST['pWord']);
		
		// Gets the email as above
		$email = $conn->real_escape_string($_POST['em']);
		
		// Gets the account type as above
		$userType = $conn->real_escape_string($_POST['userType']);
		
		// Gets the filename from the one entered into the form in register.php
		$filename = $_FILES['profilePic']['name'];
		// Creating a temporary filename for transport
		$filetmp = $_FILES['profilePic']['tmp_name'];
		
		// Adding additional security by ensuring the file is an approved type, preventing
		// for example a javascript file being uploaded.
		$filetype = $_FILES['profilePic']['type'];
		
		// If the var holding the info from the username field is null, stop the upload
		if($uName == null) {
			echo "<div class='container' id='formWidth'>
				<div class='row paddingTop'>
					<div class='col-12'>
						<div class='alert alert-warning alert-dismissible fade show' role='alert'>
							Username field not filled, please ensure all text fields are completed!
							  <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
								<span aria-hidden='true'>&times;</span>
							</button>
						</div>
					</div>
				</div>
			</div>";
			
		// Likewise for password field
		} else if ($pWord == null){
			echo "<div class='container' id='formWidth'>
				<div class='row paddingTop'>
					<div class='col-12'>
						<div class='alert alert-warning alert-dismissible fade show' role='alert'>
							Password field not filled, please ensure all text fields are completed!
							  <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
								<span aria-hidden='true'>&times;</span>
							</button>
						</div>
					</div>
				</div>
			</div>";
			
		// Likewise for email field
		} else if ($email == null){
		echo "<div class='container' id='formWidth'>
				<div class='row paddingTop'>
					<div class='col-12'>
						<div class='alert alert-warning alert-dismissible fade show' role='alert'>
							Email field not filled, please ensure all text fields are completed!
							  <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
								<span aria-hidden='true'>&times;</span>
							</button>
						</div>
					</div>
				</div>
			</div>";
			
		// But if all are filled in, then...
		} else {
			
			//Check to ensure that the account being made does not use a username already existing on the database.
			$checkDuplicates = "SELECT * FROM AC_Users WHERE username='$uName'";
			
			// Query the database with the above SQL statements
			$resultDuplicates = $conn->query($checkDuplicates);
			// Check to make sure that the query RE: checkDup was successful, if not, throw an error
			if(!$resultDuplicates){
				echo $conn->error;
			}
			
			// Checking the number of results
			$numDuplicates = $resultDuplicates->num_rows;
			
			// If a duplicate username exists on database, do not let the registration proceed.
			if ($numDuplicates != 0){
				echo "<div class='container' id='formWidth'>
				<div class='row paddingTop'>
					<div class='col-12'>
						<div class='alert alert-warning alert-dismissible fade show' role='alert'>
							That username is already taken! Please try another.
							  <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
								<span aria-hidden='true'>&times;</span>
							</button>
						</div>
					</div>
				</div>
			</div>";
			// Else allow the reg process to continue
			} else {
			
			
			
			// Check to ensure the image is a jpeg, if so, upload it
			if($filetype == 'image/jpeg'){
				// Uploading the imagefile to the imgs/ folder on filezilla
				move_uploaded_file($filetmp, "imgs/".$filename);
				// And inserting all the values from the form to the SQL db
				$insert = "INSERT INTO AC_Users (username, password, email, profilePic, userType) 
				VALUES ('$uName', '$pWord', '$email', '$filename', '$userType')";
			
				$resultinsert = $conn -> query($insert);
		
				if(!$resultinsert){
					echo $conn->error;
				} else {
				
					echo "<div class='container' id='formWidth'>
							<div class='row paddingTop'>
								<div class='col-12'>
									<div class='alert alert-success alert-dismissible fade show' role='alert'>
										Congratulations, account created!
										<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
											<span aria-hidden='true'>&times;</span>
										</button>
									</div>
								</div>
							</div>
						</div>";
				}
			
			// Check to make sure the image is a jpg, if so, upload it
			} else if($filetype == 'image/jpg'){
				// Uploading the imagefile to the imgs/ folder on filezilla
				move_uploaded_file($filetmp, "imgs/".$filename);
				// And inserting all the values from the form to the SQL db
				$insert = "INSERT INTO AC_Users (username, password, email, profilePic, userType) 
				VALUES ('$uName', '$pWord', '$email', '$filename', '$userType')";
			
				$resultinsert = $conn -> query($insert);
		
				if(!$resultinsert){
					echo $conn->error;
				} else {
				
					echo "<div class='container' id='formWidth'>
							<div class='row paddingTop'>
								<div class='col-12'>
									<div class='alert alert-success alert-dismissible fade show' role='alert'>
											Congratulations, account created!
										<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
											<span aria-hidden='true'>&times;</span>
										</button>
									</div>
								</div>
							</div>
						</div>";
				}
			
			// Get to make sure the image is a png, if so, upload it
			} else if($filetype == 'image/png'){
				// Uploading the imagefile to the imgs/ folder on filezilla
				move_uploaded_file($filetmp, "imgs/".$filename);
				// And inserting all the values from the form to the SQL db
				$insert = "INSERT INTO AC_Users (username, password, email, profilePic, userType)  
				VALUES ('$uName', '$pWord', '$email', '$filename', '$userType')";
			
				$resultinsert = $conn -> query($insert);
		
				if(!$resultinsert){
					echo $conn->error;
				} else {
				
					echo "<div class='container' id='formWidth'>
							<div class='row paddingTop'>
								<div class='col-12'>
									<div class='alert alert-success alert-dismissible fade show' role='alert'>
											Congratulations, account created!
										<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
											<span aria-hidden='true'>&times;</span>
										</button>
									</div>
								</div>
							</div>
						</div>";
				}
			
			// If there is no image at all, then stop the upload and output message
			} else if($filetype == null){

				echo "<div class='container' id='formWidth'>
					<div class='row paddingTop'>
						<div class='col-12'>
							<div class='alert alert-warning alert-dismissible fade show' role='alert'>
								Image not attached, please attach an image and ensure all text fields are completed!
								<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
									<span aria-hidden='true'>&times;</span>
								</button>
							</div>
						</div>
					</div>
				</div>";
		
			// Else if its not null, but not an approve filetype, stop the upload and output message
			} else {
				echo "<div class='container' id='formWidth'>
					<div class='row paddingTop'>
						<div class='col-12'>
							<div class='alert alert-danger alert-dismissible fade show' role='alert'>
								Invalid file detected. Please only upload approved image files.
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