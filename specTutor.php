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

	//Using POST to get the specific tutor selected from findTutor.php
	$tutorID = $_POST['hiddenTutorID'] ?? '';
	// If value is not being set for whatever reason, like a refresh, send back to findTutor.php
	if ($tutorID == ''){
		header('Location: findTutor.php');
	}
	
	
	$read = "SELECT * FROM AC_Users WHERE userid = '$tutorID'";
	// This is used for easy access to all information relating to the tutor user on the SQL database, usually used in the body.
	$result = $conn->query($read);
	if(!$result){
		echo $conn->error;
	}
	
	// Using tutorID to get the tutors bio from the AC_TutorBio table
	$readTutorBio = "SELECT * FROM AC_TutorBio WHERE tutorID = '$tutorID'";
	$resultTutorBio = $conn->query($readTutorBio);
	if (!$resultTutorBio){
		echo $conn->error;
	}
	$row = $resultTutorBio->fetch_assoc();
	$bio = $row['bio'];
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

	<!-- Add icon library -->
	<script src="https://kit.fontawesome.com/60a2ed97ba.js"></script>
	
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
				<a class="btn btn-light" href="findTutor.php"><strong>Back</strong></a>
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
					$tutorName = $row['username'];
					$tutorPic = $row['profilePic'];
					
					echo 	"<div class='col-12 text-center' id='formWidth'>
									
									<br>
									<h3>$tutorName</h3>
									<img src='../imgs/$tutorPic' height='200' width='200'>
									
									<form action='specTutor.php' method='POST' enctype='multipart/form-data'>
									<input type = 'hidden' value='$tutorID' name='hiddenTutorID'>
									<br>
										<button type='submit' name='submitBtn' class='btn btn-info'><i class='far fa-paper-plane'></i><strong>Send Request</strong></button>
									</form>
									
							</div>";
					
				}
			?>
			
			
		</div>
	</div>
	
		<div class="container-fluid">
		<div class="row">
		<?php
		
			$bioExist = $resultTutorBio->num_rows;
			if ($bioExist == 0){
				
			} else {
				echo "<div class='col-4 text-center'>
					
				</div>
				
				<div class='col-4 text-center'>
				<br>
					<h4>Tutor Bio</h4>
					<p>$bio</p>
				</div>
				
				<div class='col-4 text-center'>
					
				</div>";
			}
		?>
		</div>
	</div>
	
		
<?php
	if(isset($_POST['submitBtn'])){
		
		$checkExisting = "SELECT * FROM AC_Users WHERE userID = '$dynamicUserID' AND tutorship IS NOT NULL";
		$resultExisting = $conn->query($checkExisting);
		
		if(!$resultExisting){
			echo $conn->error;
		}
		
		$numExisting = $resultExisting->num_rows;
		
		if ($numExisting == 0){
		
			$checkDuplicate = "SELECT * FROM AC_Requests WHERE tutorID = '$tutorID' AND studentID = '$dynamicUserID'";
			// Querying the SQL databse
			$resultDuplicate = $conn->query($checkDuplicate);
			// This sets the num var to the num of rows returned from the above query. Should always be either 1 or 0.
			$num = $resultDuplicate->num_rows;
		
			if($num == 0){
	
				$insert = "INSERT INTO AC_Requests (tutorID, studentID)
					VALUES ('$tutorID', '$dynamicUserID')";
			
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
									You have already sent a request to this tutor!
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
							<div class='alert alert-danger alert-dismissible fade show' role='alert'>
									You already have a tutor!
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