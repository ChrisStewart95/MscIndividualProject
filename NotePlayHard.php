<?php
	session_start();
	
	include('../conn.php');
	
	if (!isset($_SESSION['40126571_userName'])){
		//Throwing the user back to the website index page (not userindex)
		header('Location: ../index.html');
	}
	
	$dynamicUsername = $_SESSION['40126571_userName'];
	$dynamicUserID = $_SESSION['40126571_userID'];
	$dyanmicUserType = $_SESSION['40126571_userType'];
	
	// If the current session does not belong to a Student, kick them out.
	if ($dyanmicUserType != 'Student'){
		header('Location: ../index.html');
	}
?>

<!DOCTYPE html>

<head>
	<title>Pitch Detection</title>
	
	<link rel="stylesheet" type="text/css" href="pitchDetect.css">
	<link rel="stylesheet" href="../ui.css"/>
	<link rel="shortcut icon" type="img/png" href="../imgs/favicon.png"/>
	
		<!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	
	<!-- Add icon library -->
	<script src="https://kit.fontawesome.com/60a2ed97ba.js"></script>
	
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
				<a class="btn btn-light" href="chooseDiff.php"><strong>Difficulties</strong></a>
			</div>
			
			<!-- The div holding the Register link -->
			<div class="col-1 btnCenter">
				<a class="btn btn-light" href="userIndex.php"><strong>Home</strong></a>
			</div>
		</div>
	</div>
	
	<div class='notifications bottom-right'></div>
	
	<div class="container-fluid">
		<div class="row rowPad">
			<div class="col-12 text-center">
				<!-- A button which when pressed will trigger the accessMic() function on the pitchDetect.js page -->
				<button id="btnMic" onclick="accessMic()"><i class="fas fa-microphone fa-1x"></i> Mic Access</button>
			</div>
			<div class="col-12 text-center">
				<br>
				<!-- Starts the game by invoking the appropriate function from notePlay.js -->
				<button class="btn btn-secondary btnCenter" onclick="playGameHard()" disabled id="btnStartAdv">Start NotePlay!</button>
			</div>
		</div>
		
		<div class="row rowPad">
			<div class="col-5"></div>
			
			<!-- The div tag which will display feedback to the user -->
			<!-- Such as the pitch in Hz of the noise being detected, the corresponding note, etc --> 
			<div id="noteDetect" class="col-2 center noFeedback">
				<div><span id="note">~</span></div>
				
				<div id="pitchTextColour"><span id="pitch">~</span>Hz</div>
			</div>
			
			<div class="col-5"></div>
			
		</div>
		
		
		<div class="row rowPad">
			<div class="col-12 text-center">
				<!-- adding a canvas -->
				<canvas id="oscilloscope"></canvas>
			</div>
		</div>
	</div>
	
	<div class="container-fluid">
		<div class="row rowPad">
			<div class="col-12 text-center">
				<!-- Corresponding php to iterate through the array of selected strings -->
				<?php
					$stringSelectionArray = $_POST['String'];

					if (isset($_POST['String'])) {

						

					} else {
						header('Location: chooseStringsTime.php');
					}
					
					$timeSelected = $_POST['Timer'];
					if (isset($_POST['Timer'])){
					} else {
						header ('Location: chooseStringsTime.php');
						
					}
					
					$roundsSelected = $_POST['numOfRounds'];
					if (isset($_POST['numOfRounds'])){
					} else {
						header ('Location: chooseStringsTime.php');
						
					}
					
				?>
				
			</div>
		</div>
	</div>
	
	<!-- JSON to convert the php array to a javascript array -->
	<script type="text/javascript">
		var stringsSelected = <?php echo json_encode($stringSelectionArray) ?>;
		
		var speedSelected = '<?php echo $timeSelected; ?>';
		console.log(speedSelected);
		
		var numOfRounds = '<?php echo $roundsSelected; ?>';
		console.log(numOfRounds);
	</script>
	
	<?php
	
	if(isset($_POST['submitBtn'])){
		// Getting the score value from the hidden form input in notePlay.js when the user finishes a game of notePlay
		$score = $conn->real_escape_string($_POST['hiddenScore']);
		// Getting the number of rounds
		$rounds = $conn->real_escape_string($_POST['hiddenNumRounds']);
		// Getting the notes played
		$notes = $conn->real_escape_string($_POST['hiddenNotesPlayed']);
		// Getting the speed of the game
		$speed = $conn->real_escape_string($_POST['hiddenSpeed']);
		// Getting the current date
		$finddate = date("Y-m-d");
		// Getting the difficulty mode selected by user
		$diff = $conn->real_escape_string($_POST['hiddenDiff']);
		
		// SQL query to read through the existing values in the table to see if the student already has a score saved for that day, for that difficulty
		$check = "SELECT * FROM AC_Scores WHERE studentID = '$dynamicUserID' AND date = '$finddate' AND difficulty = '$diff'";
		
		// Querying the SQL databse
		$result = $conn->query($check);
		
		// This sets the num var to the num of rows returned from the above query. Should always be either 1 or 0.
		$num = $result->num_rows;
		
		if($num == 0){
			// if there is no saved score for the current student on the current day, INSERT a new entry
			$insert = "INSERT INTO AC_Scores (StudentID, date, notesPlayed, numOfRounds, speed, score, difficulty) VALUES ('$dynamicUserID', '$finddate', '$notes', '$rounds', '$speed', '$score', '$diff')";
			
			$resultinsert = $conn -> query($insert);
			
			if (!$resultinsert){
				echo $conn->error;
			} else {
				echo "<div class='container' id='formWidth'>
							<div class='row paddingTop'>
								<div class='col-12'>
									<div class='alert alert-success alert-dismissible fade show' role='alert'>
											Score saved!
										<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
											<span aria-hidden='true'>&times;</span>
										</button>
									</div>
								</div>
							</div>
						</div>";
			}
		} else if ($num == 1){
			// Else if there already is a previously saved score on that day, UPDATE it with the newest score IF the difficulty is the same
			$update = "UPDATE AC_Scores SET StudentID = '$dynamicUserID', date = '$finddate', notesPlayed = '$notes', numOfRounds = '$rounds', speed = '$speed', score = '$score', difficulty = '$diff' WHERE StudentID = '$dynamicUserID' AND date = '$finddate' AND difficulty = '$diff'";
			
			$resultupdate = $conn -> query($update);
			
			if (!$resultupdate) {
				echo $conn->error;
			} else {
				echo "<div class='container' id='formWidth'>
							<div class='row paddingTop'>
								<div class='col-12'>
									<div class='alert alert-success alert-dismissible fade show' role='alert'>
											Score of the day updated!
										<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
											<span aria-hidden='true'>&times;</span>
										</button>
									</div>
								</div>
							</div>
						</div>";
			}
		} else {
			echo "Not possible, more than one row of results belonging to the same student. Shouldnt be possible.";
		}
	}
	
	?>
	
	
	
	<!-- Linking this html page to the corresponding javascript page -->
	<script src="pitchDetect.js"></script>
	<!--<script src="noteAnnouncer.js"></script> -->
	<script src="notePlay.js"></script>
	
		<!-- Enabling bootstraps JS to function. This must stay at the bottom of the html, just before the </body> tag -->
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src='../plugins/bootstrapNotify/bootstrapNotify.js'></script>


</body>
</html>