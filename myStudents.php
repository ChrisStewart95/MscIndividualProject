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
	if ($dyanmicUserType != 'Tutor'){
		header('Location: ../index.html');
	}
	
	$readStudentIDs = "SELECT studentID FROM AC_TutorToStudent WHERE tutorID='$dynamicUserID'";
	
	$resultStudentIDs = $conn->query($readStudentIDs);
	
	if(!$resultStudentIDs){
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
	
	<div class="container-fluid">
		<div class="row">
			<div class="col-4">
			</div>
			
			<div class="col-4 text-center" id="bottomSG">
				<h3>Current Students</h3>
			</div>
			
			<div class="col-4">
			</div>
		</div>
	</div>
	
	
	<div class="container-fluid">
		<div class="row">
			<?php 
				while($row = $resultStudentIDs->fetch_assoc()){
					$findStudentID = $row['studentID'];
					
					$readStudentInfo = "SELECT * FROM AC_Users WHERE userid='$findStudentID'";
	
					$resultStudentInfo = $conn->query($readStudentInfo);
	
					if(!$resultStudentInfo){
						echo $conn->error;
					} 
					
					while($row2 = $resultStudentInfo->fetch_assoc()){
						$studentName = $row2['username'];
						$studentID = $row2['userid'];
						$studentEmail = $row2['email'];
						$studentPic = $row2['profilePic'];
						
								echo 	"<div class='col-1'>
								</div>
								
								<div class='col-2 text-center'>
								
										<div class='indexOptionStu menuHoverTutor'>
											<img src='../imgs/$studentPic' class='menuImg'>
												<div class='menuTextStu'>
													<p>$studentName</p>
												</div>
										</div>
										<form action='specStudent.php' method='POST' enctype='multipart/form-data'>
												<input type = 'hidden' value='$studentID' name='hiddenStudentID'>
												
												<button type='submit' name='submitBtnView' class='btn btn-success btnPadding'><i class='fas fa-search'></i></button></form>
										</form>
									
								</div>
								
								<div class='col-1'>
								</div>";
						
						
						
					
					
					}
					
				}
			?>
		</div>
	</div>


	
	
</body>
</html>