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
	
	// If the current session does not belong to a Student, kick them out.
	if ($dyanmicUserType != 'Student'){
		header('Location: ../index.html');
	}
	
	// Reading all students from the AC_Friends table which have an existing friendship with the logged in user
	$readStudentIDs = "SELECT * FROM AC_Friends WHERE studentOneID='$dynamicUserID' OR studentTwoID='$dynamicUserID'";
	
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
	
	<!-- Adding the Bootstrap stylesheet -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	
	<!-- Add icon library -->
	<script src="https://kit.fontawesome.com/60a2ed97ba.js"></script>
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
			<div class="col-4">
			</div>
			
			<div class="col-4 text-center" id="bottomBorderWhite">
				<h3>Friendslist</h3>
			</div>
			
			<div class="col-4">
			</div>
		</div>
	</div>
	
	
	<div class="container-fluid">
		<div class="row">
			<?php 
				while($row = $resultStudentIDs->fetch_assoc()){
					$findStudentOneID = $row['studentOneID'];
					$findStudentTwoID = $row['studentTwoID'];
					
					// Determine which ID belongs to the user that is NOT logged in currently, thus getting the ID of the other student in the friendship.
					if ($findStudentOneID == $dynamicUserID){
						$theFriendID = $findStudentTwoID;
					} else if ($findStudentTwoID == $dynamicUserID){
						$theFriendID = $findStudentOneID;
					} else {
						echo "Error: the ID of the 'friend' is returning as a value it should not be. Check myFriends.php.";
					}
					
					$readStudentInfo = "SELECT * FROM AC_Users WHERE userid='$theFriendID'";
	
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
									
										<div class='indexOptionStu'>
											<img src='../imgs/$studentPic' class='menuImg'>
												<div class='menuTextStu'>
													<p>$studentName</p>
											</div>
										</div>
										<form action='specFriend.php' method='POST' enctype='multipart/form-data'>
												<input type = 'hidden' value='$studentID' name='hiddenFriendID'>
												
												<button type='submit' name='submitBtnView' class='btn btn-info btnPadding'><i class='fas fa-search'></i></button></form>
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