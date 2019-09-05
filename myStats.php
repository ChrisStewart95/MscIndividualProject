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
	
	$read = "SELECT * FROM AC_Users WHERE userid='$dynamicUserID'";
	
	$result = $conn->query($read);
	
	if(!$result){
		echo $conn->error;
	}
	
	// Grabbing the students recorded data for the Easy mode of NotePlay
	// The SQL statement to get all the data from the particular user where the difficulty was easy
	$readEasyResults = "SELECT * FROM AC_Scores WHERE StudentID = '$dynamicUserID' AND difficulty = 'Easy'";
	// Querying the SQL database
	$resultReadEasyResults = $conn -> query($readEasyResults);
	// Checking that we get a result from the above query
	if(!$resultReadEasyResults){
		echo $conn->error;
	}
	
	// Grabbing the students recorded data for the Intermediate mode of NotePlay
	// The SQL statement to get all the data from the particular user where the difficulty was intermediate
	$readInterResults = "SELECT * FROM AC_Scores WHERE StudentID = '$dynamicUserID' AND difficulty = 'Intermediate'";
	// Querying the SQL database
	$resultReadInterResults = $conn -> query($readInterResults);
	// Checking that we get a result from the above query
	if(!$resultReadInterResults){
		echo $conn->error;
	}
	
	// Grabbing the students recorded data for the Advanced mode of NotePlay
	// The SQL statement to get all the data from the particular user where the difficulty was Advanced
	$readAdvResults = "SELECT * FROM AC_Scores WHERE StudentID = '$dynamicUserID' AND difficulty = 'Advanced'";
	// Querying the SQL database
	$resultReadAdvResults = $conn -> query($readAdvResults);
	// Checking that we get a result from the above query
	if(!$resultReadAdvResults){
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
				<a class="btn btn-light" href="userIndex.php"><strong>Home</strong></a>
			</div>
		</div>
	</div>
	
	
	<div class="container-fluid">
		<div class="row">
			<div class="col-4">
			</div>
			
			
			<div class='col-4 text-center' id='formWidth'>
				<br>
				
				
					<?php 
						echo "<h5 id='bottomBorderWhite'><strong>Scores in NotePlay easy mode: </strong></h5>";
						
									$numRequests = $resultReadEasyResults->num_rows;
		
									if ($numRequests == 0){
										echo "
												<p>You have not yet saved any scores on NotePlay easy.</p>
											";
									} else {
										echo "<br>";
										while ($row2 = $resultReadEasyResults->fetch_assoc()){
											$date = $row2['date'];
											$score = $row2['score'];
											$notePlayed = $row2['notesPlayed'];
										
											echo "<p>Scored $score practicing the $notePlayed note on $date</p>";
										}
									}
									
									echo "<h5 id='bottomBorderWhite'><strong>Scores in NotePlay intermediate mode: </strong></h5>";
									
									$numRequests2 = $resultReadInterResults->num_rows;
		
									if ($numRequests2 == 0){
										echo "
												<p>You have not yet saved any scores on NotePlay intermediate.</p>
											";
									} else {
										echo "<br>";
										while ($row3 = $resultReadInterResults->fetch_assoc()){
											$date = $row3['date'];
											$score = $row3['score'];
											echo "<p>Scored $score practicing all notes on $date</p>";
										}
									}
									
									echo "<h5 id='bottomBorderWhite'><strong>Scores in NotePlay advanced mode: </strong></h5>";
									
									$numRequests3 = $resultReadAdvResults->num_rows;
		
									if ($numRequests3 == 0){
										echo "
												<p>You have not yet saved any scores on NotePlay advanced.</p>
											";
									} else {
										echo "<br>";
										while ($row4 = $resultReadAdvResults->fetch_assoc()){
											$date = $row4['date'];
											$score = $row4['score'];
											$notePlayed = $row4['notesPlayed'];
											$speed = $row4['speed'];
											$numOfRounds = $row4['numOfRounds'];
											echo "<p>Scored $score practicing the $notePlayed notes for $numOfRounds rounds at $speed speed on $date</p>";
										}
									}
					?>
	
			</div>
			
			
			<div class="col-4">
			</div>
		</div>
	</div>
	
</body>
</html>