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
	
	if(isset($_POST['submitBtnAccept'])){
			// Capturing the studentID of the student that was clicked. Without getting this value from the hidden form input,
			// the value of $studentID would just be the final value in the while loop, rather than the specific one of the student chosen.
			$indStuID = $conn->real_escape_string($_POST['indStuIDacc']);
			
			
			// Create the new relationship in the AC_TutorToStudent table
			$createRelationship = "INSERT INTO AC_TutorToStudent (tutorID, studentID) VALUES ('$dynamicUserID', '$indStuID')";
			$resultCreate = $conn->query($createRelationship);
			
			if(!$resultCreate){
				echo $conn->error;
			}
			
			// Update the AC_Users table to show that the student user is now involved in a Tutor-Student relationship
			$readTutToStu = "SELECT * FROM AC_TutorToStudent WHERE studentID = '$indStuID'";
	
			$resultTutToStu = $conn->query($readTutToStu);
	
			if(!$resultTutToStu){
				echo $conn->error;
			}
		
			while($row = $resultTutToStu->fetch_assoc()){
				$TutToStuID = $row['id'];
			}
		
			$insertTutToStuID = "UPDATE AC_Users SET tutorship = '$TutToStuID' WHERE userid='$indStuID'";
			
			$resultTutToStuID = $conn -> query($insertTutToStuID);
		
			if(!$resultTutToStuID){
				echo $conn->error;
			}
			
			// Delete from the Requests table ALL requests sent by the student that now has a tutor, as a student can only have one tutor.
			$destroyRequest = "DELETE FROM AC_Requests WHERE studentID = '$indStuID'";
			$resultDestroy = $conn->query($destroyRequest);
			
			if(!$resultDestroy){
				echo $conn->error;
			}
			
		}
		
		if(isset($_POST['submitBtnDecline'])){
			
			$indStuID = $conn->real_escape_string($_POST['indStuIDdec']);
			
			$destroyRequest = "DELETE FROM AC_Requests WHERE tutorID = '$dynamicUserID' AND studentID = '$indStuID'";
			$resultDestroy = $conn->query($destroyRequest);
			
			if(!$resultDestroy){
				echo $conn->error;
			}
			
		}
	
	
	// If the current session does not belong to a tutor, kick them out.
	if ($dyanmicUserType != 'Tutor'){
		header('Location: ../index.html');
	}
	
	$read = "SELECT * FROM AC_Requests WHERE tutorID='$dynamicUserID'";
	
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
	
	<!-- Add icon library -->
	<script src="https://kit.fontawesome.com/60a2ed97ba.js"></script>
	
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
			</div>
			
			<!-- The div holding the Register link -->
			<div class="col-1 btnCenter">
				<a class="btn btn-light" href="userIndex.php"><strong>Home</strong></a>
			</div>
		</div>
	</div>
	
	<?php
		$numRequests = $result->num_rows;
		
		if ($numRequests == 0){
			echo "<div class='container-fluid'>
				<div class='row'>
					<div class='col-12 text-center'>
						<h3>You have no pending requests at the moment.</h3>
					</div>
				</div>
			</div>";
		} 
	?>
	<div class="container-fluid">
		<div class="row">
			<?php 
				while($row = $result->fetch_assoc()){
					$studentID = $row['studentID'];
						
					$readStuDetails = "SELECT * FROM AC_Users WHERE userID = '$studentID'";
					$resultStuDetails = $conn->query($readStuDetails);
					
					if(!$resultStuDetails){
						echo $conn->error;
					}
					
					while ($row2 = $resultStuDetails->fetch_assoc()){
						$studentName = $row2['username'];
						$studentPic = $row2['profilePic'];
					
						echo 	"<div class='col-4 text-center'>
									<div>
										<br>
										<img class='profileImgs' height='200' width='200' src='../imgs/$studentPic'/>
										<div class='menuTextFr'>
											<p>$studentName</p>
										</div>
									</div>
								
									
									<form action='viewRequests.php' method='POST' enctype='multipart/form-data'>
										<input type='hidden' id='indStuIDacc' name='indStuIDacc' value='$studentID'>
										<button type='submit' name='submitBtnAccept' class='btn btn-success'><i class='fas fa-check-circle'></i></button>
									
										<input type='hidden' id='indStuIDdec' name='indStuIDdec' value='$studentID'>
										<button type='submit' name='submitBtnDecline' class='btn btn-danger'><i class='fas fa-times'></i></button>
									</form>
								
								</div>";
					}
					
				}
			?>
		</div>
	</div>
	
</body>
</html>