<!DOCTYPE html>
<html lang="en">
<head>
  <title>Acoustica</title>
  
  <link rel="shortcut icon" type="img/png" href="imgs/favicon.png">
  
  <link rel="stylesheet" href="ui.css"/>
  
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
				<a class="btn btn-light" href="login.php"><strong>Login</strong></a>
			</div>
		</div>
	</div>
	
	<!-- WELCOME MESSAGE - A container holding a row with one column -->
	<div class="container-fluid">
		<div class="row paddingTop">
			<div class="col-12 text-center">
				<h2><strong>Recover Password</strong></h2>
			</div>
		</div>
	</div>

	<!-- FORGOT PASSWORD FORM - A container holding a row with one column -->
	<div class="container" id="formWidth">
		<div class="row paddingTop">
			<div class="col-12 text-center">
				<form action='forgotPass.php' method='POST' enctype='multipart/form-data'>
					<div class="form-group">
						<div class="form-group">
							<label for="postUN">Username</label>
							<input type="text" class="form-control" name="postUN" id="postUN"  maxlength="15" placeholder="Enter Username" required>
						</div>
						
						<label for="postEM"><strong>Email Address</strong></label>
						<input type="email" class="form-control" name="postEM" id="postEM" maxlength="30" placeholder="Enter Email" required>
					</div>
					
					<button type="submit" name="submitBtn" class="btn btn-light" id="forgotBtn"><strong>Submit</strong></button>
				</form>
			</div>
		</div>
	</div>
	
<?php
if(isset($_POST['submitBtn'])){
	
	//Include the connection to the SQL database
	include('conn.php');
	
	// Getting the new form details from editAcc.php and setting them to variables
	$fpUsername = $conn->real_escape_string($_POST['postUN']);
	$fpEmail = $conn->real_escape_string($_POST['postEM']);
	
	function generateRandPass(){
		// Setting how long the generated password should be
		$passLength = 6;
		// Setting all the possible characters the password can consist of
		$potentialChars = "1234567890qwertyuiopasdfghjklzxcvbnm";
		// Shuffling the possible chars up with str_shuffle, and then cutting 
		// the result of the shuffled string down to the length of the password
		$shuffler = substr(str_shuffle($potentialChars), 0, $passLength);
		return $shuffler;
		
	}
	
	$tmpPass = generateRandPass();
	
	$randPass = "UPDATE AC_Users SET password = '$tmpPass' WHERE username = '$fpUsername' AND email = '$fpEmail'";
	
	$result = $conn->query($randPass);
	
	if(!$result){
		echo $conn->error;
	} else {
		echo "<div class='container' id='formWidth'>
							<div class='row paddingTop'>
								<div class='col-12 text-center'>
									<div class='alert alert-success alert-dismissible fade show' role='alert'>
											Temporary password sent, please check your emails (including junk!).
										<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
											<span aria-hidden='true'>&times;</span>
										</button>
									</div>
								</div>
							</div>
						</div>";
						
		$to = $fpEmail;
		$subject = 'Acoustica temporary password';
		$message = "<h1>Hello!</h1><p>Your new temporary password is below. Please remember to update your account with a new password upon your next login.</p><br><p>".$tmpPass."</p>";
		$header = "From: Acoustica <Acoustica@qub.com>\r\n";
		$header .= "Reply-To: Acoustica:qub.com\r\n";
		$header .= "Content-type: text/html\r\n";
		
		//Send email
		mail($to, $subject, $message, $header);
		
	}


}

?>
	
	<!-- Enabling bootstraps JS to function. This must stay at the bottom of the html, just before the </body> tag -->
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

</body>
</html>