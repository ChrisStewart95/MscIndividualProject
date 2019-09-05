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
	$dynamicUserType = $_SESSION['40126571_userType'];
	
	// If the current session does not belong to a Student, kick them out.
	if ($dynamicUserType != 'Student'){
		header('Location: ../index.html');
	}
	
	// Accessing the logged-in users row on the SQL table
	$readacc = "SELECT * FROM AC_Users WHERE userid='$dynamicUserID'";
 
	$result = $conn->query($readacc);
	
	if(!$result){
		echo $conn->error;
	}
	
	if(isset($_POST['submitBtn'])){
	
		// Getting the new form details from editAcc.php and setting them to variables
		$nUserName = $conn->real_escape_string($_POST['newUsername']);
		$nPW = $conn->real_escape_string($_POST['newPW']);
		$nEmail = $conn->real_escape_string($_POST['newEmail']);
		
		$uID = $conn->real_escape_string($_POST['uID']);
		
		// Gets the filename from the one entered into the form in editItem.php
		$filename = $_FILES['newPP']['name'];
		// Creating a temporary filename for transport
		$filetmp = $_FILES['newPP']['tmp_name'];
		
		// Adding additional security by ensuring the file is an approved type, preventing
		// for example a javascript file being uploaded.
		$filetype = $_FILES['newPP']['type'];
		
		// If the var holding the info from the username field is null, stop the upload
		if($nUserName == null) {
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
		} else if ($nPW == null){
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
		} else if ($nEmail == null){
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
			$checkDuplicates = "SELECT * FROM AC_Users WHERE username='$nUserName' AND username != '$dynamicUsername'";
			
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
		
		if($filetype == 'image/jpeg'){
			// Uploading the imagefile to the imgs/ folder on filezilla
			move_uploaded_file($filetmp, "../imgs/".$filename);
			// And updating all the values from the form to the SQL db
			$update = "UPDATE AC_Users SET username = '$nUserName', password = '$nPW', email='$nEmail', profilePic='$filename' WHERE userID='$uID'";
			$resultupdate= $conn->query($update);
		
			if(!$resultupdate){
				echo $conn->error;
			} else {
				echo "<div class='container' id='formWidth'>
							<div class='row paddingTop'>
								<div class='col-12 text-center'>
									<div class='alert alert-success alert-dismissible fade show' role='alert'>
											Congratulations, account successfully editted!
										<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
											<span aria-hidden='true'>&times;</span>
										</button>
									</div>
								</div>
							</div>
						</div>";
						
						header('Location: ../logout.php');
			}
			
		} else if($filetype == 'image/jpg'){
			// Uploading the imagefile to the imgs/ folder on filezilla
			move_uploaded_file($filetmp, "../imgs/".$filename);
			// And updating all the values from the form to the SQL db
			$update = "UPDATE AC_Users SET username = '$nUserName', password = '$nPW', email='$nEmail', profilePic='$filename' WHERE userID='$uID'";
			$resultupdate= $conn->query($update);
		
			if(!$resultupdate){
				echo $conn->error;
			} else {
				echo "<div class='container' id='formWidth'>
							<div class='row paddingTop'>
								<div class='col-12 text-center'>
									<div class='alert alert-success alert-dismissible fade show' role='alert'>
											Congratulations, account successfully editted!
										<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
											<span aria-hidden='true'>&times;</span>
										</button>
									</div>
								</div>
							</div>
						</div>";
						header('Location: ../logout.php');
			}
		
		} else if($filetype == 'image/png'){
			// Uploading the imagefile to the imgs/ folder on filezilla
			move_uploaded_file($filetmp, "../imgs/".$filename);
			// And updating all the values from the form to the SQL db
			$update = "UPDATE AC_Users SET username = '$nUserName', password = '$nPW', email='$nEmail', profilePic='$filename' WHERE userID='$uID'";
			$resultupdate= $conn->query($update);
		
			if(!$resultupdate){
				echo $conn->error;
			} else {
				echo "<div class='container' id='formWidth'>
							<div class='row paddingTop'>
								<div class='col-12 text-center'>
									<div class='alert alert-success alert-dismissible fade show' role='alert'>
											Congratulations, account successfully editted!
										<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
											<span aria-hidden='true'>&times;</span>
										</button>
									</div>
								</div>
							</div>
						</div>";
						header('Location: ../logout.php');
			}
		
		} else if ($filetype == NULL){
			$update = "UPDATE AC_Users SET username = '$nUserName', password = '$nPW', email='$nEmail' WHERE userID='$uID'";
			$resultupdate= $conn->query($update);
		
			if(!$resultupdate){
				echo $conn->error;
			} else {
				echo "<div class='container' id='formWidth'>
							<div class='row paddingTop'>
								<div class='col-12 text-center'>
									<div class='alert alert-success alert-dismissible fade show' role='alert'>
											Congratulations, account successfully editted!
										<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
											<span aria-hidden='true'>&times;</span>
										</button>
									</div>
								</div>
							</div>
						</div>";
						header('Location: ../logout.php');
			}

		} else {
			echo "<div class='container' id='formWidth'>
					<div class='row paddingTop'>
						<div class='col-12 text-center'>
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
				<a class="btn btn-light" href="graphs.php"><strong>Back</strong></a>
			</div>
			
			<!-- The div holding the Register link -->
			<div class="col-1 btnCenter">
				<a class="btn btn-light" href="../index.html"><strong>Logout</strong></a>
			</div>
		</div>
	</div>
	
	<!-- A container holding a row with one column -->
	<div class="container" id="formWidth">
		<div class="row paddingTop">
			<div class="col-12 text-center">
			
			<form action = 'editAcc.php' method='POST' enctype='multipart/form-data'>
			
				<?php
					while($row = $result->fetch_assoc()){
						
						$userName_data = $row['username'];
						
						echo "<div class='form-group'>
						<label for='newUsername'>Username</label>
						<input type='text' class='form-control' name='newUsername' maxlength='15' id='newUsername' value='$userName_data' required>
						</div>";
						
						$pw_data = $row['password'];
						
						echo "<div class='form-group'>
						<label for='newPW'>Password</label>
						<input type='password' class='form-control' name='newPW' id='newPW' maxlength='15' value='$pw_data' required>
						<small id='newPassHelp' class='form-text text-muted'>Submit form without editing this field to retain your current password.</small>
						</div>";
						
						$email_data = $row['email'];
						
						echo "<div class='form-group'>
						<label for='newEmail'>Email</label>
						<input type='email' class='form-control' name='newEmail' id='newEmail' maxlength='30' value='$email_data' required>
						</div>";
						
						$pp_data = $row['profilePic'];
						
						echo "<div class='form-group'>
						<label for='newPP'>Profile Picture</label>
						<input type='file' class='form-control-file' name='newPP' id='newPP' value='$pp_data'>
						<small id='newImageHelp' class='form-text text-muted'>To keep your current profile image, simply click submit without editting this option.</small>
						</div>";
						
						
						$userIDNum = $row['userid'];
						echo "<input type='hidden' value='$userIDNum' name='uID'>";
						
					}
				?>
				
					<p>Editing your account will require you to login again!</p>
					<button type="submit" name="submitBtn" class="btn btn-light btnPadding"><strong>Edit Account Details</strong></button>
				</form>
			</div>
		</div>
	</div>
	

<!-- Enabling bootstraps JS to function. This must stay at the bottom of the html, just before the </body> tag -->
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	
</body>
</html>
	