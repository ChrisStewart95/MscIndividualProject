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
	
	// If the current session does not belong to a student, kick them out.
	if ($dyanmicUserType != 'Student'){
		header('Location: ../index.html');
	}
	

	//Using POST to get the specific friend
	$studentID = $_POST['hiddenFriendID'] ?? '';
	// If value is not being set for whatever reason, like a refresh, send back to findfriends.php
	if ($studentID == ''){
		header('Location: findFriends.php');
	}
	
	$read = "SELECT * FROM AC_Users WHERE userid = '$studentID'";
	// This is used for easy access to all information relating to the student user on the SQL database, usually used in the body.
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

	<!-- Adding animation -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.2/animate.min.css">
</head>

<body id="baseBackground">
	<div class="container-fluid">
		<div class="row" id="banner">
			<div class="col-10">
				<h1><strong><em>Acoustica</strong> <span id="studentBanner">Student</span></em></h1>
			</div>
			
			<!-- The div holding the Login link -->
			<div class="col-1 btnCenter">
				<a class="btn btn-light" href="findFriends.php"><strong>Back</strong></a>
			</div>
			
			<!-- The div holding the Register link -->
			<div class="col-1 btnCenter">
				<a class="btn btn-light" href="userIndex.php"><strong>Home</strong></a>
			</div>
		</div>
	</div>
	
	
	<div class="container-fluid">
		<div class="row">
			<?php 
				while($row = $result->fetch_assoc()){
					$studentName = $row['username'];
					$studentPic = $row['profilePic'];
					
					echo 	"<div class='col-12 text-center' id='formWidth'>
									
									<br>
									<h3>$studentName</h3>
									<img src='../imgs/$studentPic' height='200' width='200'>
									
									<form action='reqSpecFriend.php' method='POST' enctype='multipart/form-data'>
										<input type = 'hidden' value='$studentID' name='hiddenFriendID'>
										<button type='submit' name='submitBtn' class='btn btn-light'><strong>Send Request</strong></button>
									</form>
									
							</div>";
					
				}
			?>
			
			
		</div>
	</div>
	
		
<?php
	if(isset($_POST['submitBtn'])){
		
		// Checking to make sure the student logged in and the student selected aren't already friends.
		$checkExisting = "SELECT * FROM AC_Friends WHERE studentOneID = '$dynamicUserID' AND studentTwoID = '$studentID' OR studentOneID ='$studentID' AND studentTwoID = '$dynamicUserID'";
		$resultExisting = $conn->query($checkExisting);
		
		if(!$resultExisting){
			echo $conn->error;
		}
		
		$numExisting = $resultExisting->num_rows;
		
		if ($numExisting == 0){
		
			// Checking to ensure that neither student has already sent the other a friend request, preventing a duplicate from being created.
			$checkDuplicate = "SELECT * FROM AC_FriendRequests WHERE requesterID = '$studentID' AND requesteeID = '$dynamicUserID' OR requesterID = '$dynamicUserID' AND requesteeID = '$studentID' ";
			// Querying the SQL databse
			$resultDuplicate = $conn->query($checkDuplicate);
			// This sets the num var to the num of rows returned from the above query. Should always be either 1 or 0.
			$num = $resultDuplicate->num_rows;
		
			if($num == 0){
	
				$insert = "INSERT INTO AC_FriendRequests (requesterID, requesteeID)
					VALUES ('$dynamicUserID', '$studentID')";
			
				$resultinsert = $conn->query($insert);
			
				if(!$resultinsert){
					echo $conn->error;
				} else {
					echo "<div class='container-fluid' id='formWidth'>
						<div class='row paddingTop'>
						<div class='col-12'>
							<div class='alert alert-success alert-dismissible fade show' role='alert'>
									Request sent!
								<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
									<span aria-hidden='true'>&times;</span>
								</button>
							</div>
						</div>
						</div>
					</div>";
				}
			
			} else {
				echo "<div class='container-fluid' id='formWidth'>
						<div class='row paddingTop'>
						<div class='col-12'>
							<div class='alert alert-warning alert-dismissible fade show' role='alert'>
									You have already have a pending request with this person!
								<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
									<span aria-hidden='true'>&times;</span>
								</button>
							</div>
						</div>
						</div>
					</div>";
			}
			
		} else {
			echo "<div class='container-fluid' id='formWidth'>
						<div class='row paddingTop'>
						<div class='col-12'>
							<div class='alert alert-warning alert-dismissible fade show' role='alert'>
									You are already friends!
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
	<script src='../plugins/bootstrapNotify/bootstrapNotify.js'></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>