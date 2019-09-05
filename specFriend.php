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
	$friendID = $_POST['hiddenFriendID'] ?? '';
	// If value is not being set for whatever reason, like a refresh, send back to myfriends.php
	if ($friendID == ''){
		header('Location: myFriends.php');
	}
	
	$read = "SELECT * FROM AC_Users WHERE userid = '$friendID'";
	// This is used for easy access to all information relating to the friend user on the SQL database, usually used in the body.
	$result = $conn->query($read);
	if(!$result){
		echo $conn->error;
	}
	
	// Grabbing the students recorded data for the Easy mode of NotePlay
	// The SQL statement to get all the data from the particular user where the difficulty was easy
	$readEasyResults = "SELECT * FROM AC_Scores WHERE StudentID = '$friendID' AND difficulty = 'Easy'";
	// Querying the SQL database
	$resultReadEasyResults = $conn -> query($readEasyResults);
	// Checking that we get a result from the above query
	if(!$resultReadEasyResults){
		echo $conn->error;
	}
	
	// Initialising vars
	$datesEasy ='';
	$scoresEasy ='';
	
	
		while ($row = $resultReadEasyResults->fetch_assoc()){
			$dateGraph = $row['date'];
			$scoreGraph = $row['score'];
			
			$datesEasy = $datesEasy.'"'.$dateGraph.'",';
			$scoresEasy = $scoresEasy.$scoreGraph.',';
		}
		
		$datesEasy = trim($datesEasy, ",");
		$scoresEasy = trim($scoresEasy, ",");
	
	// Grabbing the students recorded data for the Intermediate mode of NotePlay
	// The SQL statement to get all the data from the particular user where the difficulty was intermediate
	$readInterResults = "SELECT * FROM AC_Scores WHERE StudentID = '$friendID' AND difficulty = 'Intermediate'";
	// Querying the SQL database
	$resultReadInterResults = $conn -> query($readInterResults);
	// Checking that we get a result from the above query
	if(!$resultReadInterResults){
		echo $conn->error;
	}
	
	// Initialising vars
	$datesIntermediate ='';
	$scoresIntermediate ='';
	
	
		while ($row = $resultReadInterResults->fetch_assoc()){
			$dateGraph = $row['date'];
			$scoreGraph = $row['score'];
			
			$datesIntermediate = $datesIntermediate.'"'.$dateGraph.'",';
			$scoresIntermediate = $scoresIntermediate.$scoreGraph.',';
		}
		
		$datesIntermediate = trim($datesIntermediate, ",");
		$scoresIntermediate = trim($scoresIntermediate, ",");
	
	// Grabbing the students recorded data for the Advanced mode of NotePlay
	// The SQL statement to get all the data from the particular user where the difficulty was Advanced
	$readAdvResults = "SELECT * FROM AC_Scores WHERE StudentID = '$friendID' AND difficulty = 'Advanced'";
	// Querying the SQL database
	$resultReadAdvResults = $conn -> query($readAdvResults);
	// Checking that we get a result from the above query
	if(!$resultReadAdvResults){
		echo $conn->error;
	}
	
	// Initialising vars
	$datesAdv ='';
	$scoresAdv ='';
	
	
		while ($row = $resultReadAdvResults->fetch_assoc()){
			$dateGraph = $row['date'];
			$scoreGraph = $row['score'];
			
			$datesAdv = $datesAdv.'"'.$dateGraph.'",';
			$scoresAdv = $scoresAdv.$scoreGraph.',';
		}
		
		$datesAdv = trim($datesAdv, ",");
		$scoresAdv = trim($scoresAdv, ",");
	
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
	
	<!-- Enabling Chart.js to work -->
	<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
</head>

<body id="baseBackground">
	<div class="container-fluid">
		<div class="row" id="banner">
			<div class="col-10">
				<h1><strong><em>Acoustica</strong> <span id="studentBanner">Student</span></em></h1>
			</div>
			
			<!-- The div holding the Login link -->
			<div class="col-1 btnCenter">
				<a class="btn btn-light" href="myFriends.php"><strong>Back</strong></a>
			</div>
			
			<!-- The div holding the Register link -->
			<div class="col-1 btnCenter">
				<a class="btn btn-light" href="userIndex.php"><strong>Home</strong></a>
			</div>
		</div>
	</div>
	
	<?php
		if(isset($_POST['submitBtn'])){
			echo "<div class='container-fluid' id='formWidth'>
						<div class='row paddingTop'>
							<div class='col-12 text-center'>
								<div class='alert alert-warning fade show' role='alert'>
									Are you sure you want to remove this friend?
									
									<form action='specFriend.php' method='POST' enctype='multipart/form-data'>
									<input type = 'hidden' value='$friendID' name='hiddenFriendID'>
										<button type='submit' name='submitBtnAccept' class='btn btn-light'><strong>Yes, remove this friend</strong></button>
									</form>
									
									<form action='specFriend.php' method='POST' enctype='multipart/form-data'>
									<input type = 'hidden' value='$friendID' name='hiddenFriendID'>
										<button type='submit' name='submitBtnDecline' class='btn btn-light'><strong>No, keep this friend</strong></button>
									</form>
								</div>
							</div>
						</div>
					</div>";
		}
		
		if(isset($_POST['submitBtnAccept'])){
			
			$delete = "DELETE FROM AC_Friends WHERE studentOneID = '$dynamicUserID' AND studentTwoID = '$friendID' OR studentOneID = '$friendID' AND studentTwoID = '$dynamicUserID'";
			$resultDelete = $conn->query($delete);
			
			if(!$resultDelete){
				echo $conn->error;
			}
			
			header("Location: myFriends.php");
			
			
			
		}
		
		if(isset($_POST['submitBtnDecline'])){
			header("Location: specFriend.php?specificFriend=$friendID");
		}
		
		
		
	?>
	
	
	<div class="container">
		<div class="row">
			<?php 
				while($row1 = $result->fetch_assoc()){
					$studentName = $row1['username'];
					$studentPic = $row1['profilePic'];
					
					echo 	"<div class='col-12 text-center' id='formWidth'>
										
									<br>
									<h3>$studentName</h3>
									<img src='../imgs/$studentPic' height='200' width='200'>
								
								</div>
								
								
								<div class='col-12 text-center' id='formWidth'>
									<h4 class='paddingTop' id='borderWhiteTop'><strong>Scores in NotePlay easy mode: </strong></h4><br>";
									
									
									
									$resultReadEasyResults2 = $conn -> query($readEasyResults);
									if(!$resultReadEasyResults2){
										echo $conn->error;
									}
									
									$numRequests = $resultReadEasyResults2->num_rows;
		
									if ($numRequests == 0){
										echo "<div class='container-fluid'>
											<div class='row'>
												<div class='col-12 text-center'>
													<p>Student has not yet saved any scores on NotePlay easy.</p>
												</div>
											</div>
										</div>";
									} else {
									
										while ($row = $resultReadEasyResults2->fetch_assoc()){
											$date = $row['date'];
											$score = $row['score'];
											$notePlayed = $row['notesPlayed'];
											echo "<p>Scored $score practicing the $notePlayed note on $date</p>";
										}
										echo "<br><canvas id='myChart'></canvas><br><p>---</p>";
									}
									
									echo "<h4 class='paddingTop' id='borderWhiteTop'><strong>Scores in NotePlay intermediate mode: </strong></h4><br>";
									
									$resultReadInterResults2 = $conn -> query($readInterResults);
									if(!$resultReadInterResults2){
										echo $conn->error;
									}
									
									$numRequestsInter = $resultReadInterResults2->num_rows;
		
									if ($numRequestsInter == 0){
										echo "<div class='container-fluid'>
											<div class='row'>
												<div class='col-12 text-center'>
													<p>Student has not yet saved any scores on NotePlay intermediate.</p>
												</div>
											</div>
										</div>";
									} else {
									
										while ($row3 = $resultReadInterResults2->fetch_assoc()){
											$date = $row3['date'];
											$score = $row3['score'];
											echo "<p>Scored $score practicing all notes on $date</p>";
										}
										
										echo "<br>
										<canvas id='myChart2'></canvas><br><p>---</p>";
									}
									
									echo "<h4 class='paddingTop' id='borderWhiteTop'><strong>Scores in NotePlay advanced mode: </strong></h4><br>";
									
									$resultReadAdvResults2 = $conn -> query($readAdvResults);
									if(!$resultReadAdvResults2){
										echo $conn->error;
									}
									
									$numRequestsAdv = $resultReadAdvResults2->num_rows;
		
									if ($numRequestsAdv == 0){
										echo "<div class='container-fluid'>
											<div class='row'>
												<div class='col-12 text-center'>
													<p>Student has not yet saved any scores on NotePlay advanced.</p>
												</div>
											</div>
										</div>";
									} else {
									
										while ($row4 = $resultReadAdvResults2->fetch_assoc()){
											$date = $row4['date'];
											$score = $row4['score'];
											$notePlayed = $row4['notesPlayed'];
											$speed = $row4['speed'];
											$numOfRounds = $row4['numOfRounds'];
											echo "<p>Scored $score practicing the $notePlayed notes for $numOfRounds rounds at $speed speed on $date</p>";
										}
										echo "<br><canvas id='myChart3'></canvas><br><p>---</p>";
									}
							echo "</div>";
							
							echo "<div class='col-12 text-center'>
									<div>
										<form action='specFriend.php' method='POST' enctype='multipart/form-data'>
										<input type = 'hidden' value='$friendID' name='hiddenFriendID'>
											<button type='submit' name='submitBtn' class='btn btn-light'><strong>Remove Student</strong></button>
										</form>
									</div>
								</div>";
					
				}
				
			?>
			
			
		</div>
	</div>
	
	
	<script>
	var ctx = document.getElementById('myChart').getContext('2d');
	
	var chart = new Chart(ctx, {
	// The type of chart we want to create
	type: 'line',

	// The data for our dataset
	data: {
		labels: [<?php echo $datesEasy ?>],
		datasets: [{
			label: 'Easy Mode Score',
			backgroundColor: 'rgb(0, 255, 255)',
			borderColor: 'rgb(255, 255, 255)',
			data: [<?php echo $scoresEasy ?>]
		}]
	},

	// Configuration options go here
	options: {
		title: {
			display: true,
			text: 'Easy Mode'
		},
		legend: {
			display: false,
			position: 'right'
		},
		scales: {
			xAxes: [{
				display: true,
				scaleLabel: {
					display: true,
					labelString: 'Date'
				}
			}],
			yAxes: [{
				display: true,
				scaleLabel: {
					display: true,
					labelString: 'Score'
				}
			}]
		}
	}
	});
	</script>
	
	<script>
	
	var ctx = document.getElementById('myChart2').getContext('2d');
	
	var chart = new Chart(ctx, {
	// The type of chart we want to create
	type: 'line',

	// The data for our dataset
	data: {
		labels: [<?php echo $datesIntermediate ?>],
		datasets: [{
			label: 'Intermediate Mode Score',
			backgroundColor: 'rgb(0, 255, 255)',
			borderColor: 'rgb(255, 255, 255)',
			data: [<?php echo $scoresIntermediate ?>]
		}]
	},

	// Configuration options go here
	options: {
		title: {
			display: true,
			text: 'Intermediate Mode'
		},
		legend: {
			display: false,
			position: 'right'
		},
		scales: {
			xAxes: [{
				display: true,
				scaleLabel: {
					display: true,
					labelString: 'Date'
				}
			}],
			yAxes: [{
				display: true,
				scaleLabel: {
					display: true,
					labelString: 'Score'
				}
			}]
		}
	}
	});
	</script>
	<script>
	var ctx = document.getElementById('myChart3').getContext('2d');
	
	var chart = new Chart(ctx, {
	// The type of chart we want to create
	type: 'line',

	// The data for our dataset
	data: {
		labels: [<?php echo $datesAdv ?>],
		datasets: [{
			label: 'Advanced Mode Score',
			backgroundColor: 'rgb(0, 255, 255)',
			borderColor: 'rgb(255, 255, 255)',
			data: [<?php echo $scoresAdv ?>]
		}]
	},

	// Configuration options go here
	options: {
		title: {
			display: true,
			text: 'Advanced Mode'
		},
		legend: {
			display: false,
			position: 'right'
		},
		scales: {
			xAxes: [{
				display: true,
				scaleLabel: {
					display: true,
					labelString: 'Date'
				}
			}],
			yAxes: [{
				display: true,
				scaleLabel: {
					display: true,
					labelString: 'Score'
				}
			}]
		}
	}
	});
	</script>
	
	
		<!-- Enabling bootstraps JS to function. This must stay at the bottom of the html, just before the </body> tag -->
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src='../plugins/bootstrapNotify/bootstrapNotify.js'></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>