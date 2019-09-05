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
	
	// Getting the TutorID from reading the AC_TutorToStudent table
	$read = "SELECT tutorID FROM AC_TutorToStudent WHERE studentID = '$dynamicUserID'";
	$result = $conn->query($read);
	if(!$result){
		echo $conn->error;
	}
	
	
	$row = $result->fetch_assoc();
	$tutorID = $row['tutorID'];
	
	// Using tutorID to get all relevant info relating to the tutor from the AC_Users table
	$readTutorDetails = "SELECT * FROM AC_Users WHERE userID = '$tutorID'";
	$resultTutorDetails = $conn->query($readTutorDetails);
	if (!$resultTutorDetails){
		echo $conn->error;
	}
	$row = $resultTutorDetails->fetch_assoc();
	$tutorName = $row['username'];
	$tutorPic = $row['profilePic'];
	$tutorEmail = $row['email'];
	
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
				<a class="btn btn-light" href="socialMenu.php"><strong>Back</strong></a>
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
				
				$rowExist = $result->num_rows;
				if ($rowExist == 0){
					echo "<div class='col-12 text-center' id='formWidth'>
							<br>
							<h2>You don't currently have a tutor</h2>
							<br>
							<p>Click <a href='findTutor.php'>here</a> to browse potential tutors!</p>
					</div>";
				}
				
				else {
					echo 	"<div class='col-12 text-center' id='formWidth'>
									
									<br>
									<h3>$tutorName</h3>
									<p>$tutorEmail<p>
									<img src='../imgs/$tutorPic' height='300' width='300'>
									</div>
									
									
									
									<div class='col-12 text-center'>
										<form action='myTutor.php' method='POST' enctype='multipart/form-data'>
											<button type='submit' name='submitBtn' class='btn btn-light'><strong>Remove Tutor</strong></button>
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
			echo "<div class='container-fluid' id='formWidth'>
						<div class='row paddingTop'>
							<div class='col-12 text-center'>
								<div class='alert alert-warning fade show' role='alert'>
									Are you sure you want to remove this tutor?
									
									<form action='myTutor.php' method='POST' enctype='multipart/form-data'>
										<button type='submit' name='submitBtnAccept' class='btn btn-light'><strong>Yes, remove this tutor</strong></button>
									</form>
									
									<form action='myTutor.php' method='POST' enctype='multipart/form-data'>
										<button type='submit' name='submitBtnDecline' class='btn btn-light'><strong>No, keep this tutor</strong></button>
									</form>
								</div>
							</div>
						</div>
					</div>";
		}
		
		if(isset($_POST['submitBtnAccept'])){
			
			$update = "UPDATE AC_Users SET tutorship = NULL WHERE userid = '$dynamicUserID'";
			$resultUpdate = $conn->query($update);
			
			if(!$resultUpdate){
				echo $conn->error;
			}
			
			$delete = "DELETE FROM AC_TutorToStudent WHERE tutorID = '$tutorID' AND studentID = '$dynamicUserID'";
			$resultDelete = $conn->query($delete);
			
			if(!$resultDelete){
				echo $conn->error;
			}
			
			header("Location: myTutor.php");
			
			
			
		}
		
		if(isset($_POST['submitBtnDecline'])){
			header("Location: myTutor.php");
		}
		
		
		
	?>
</body>
</html>