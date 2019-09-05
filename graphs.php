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
	$dynamicUserPP = $_SESSION['40126571_userProfilePic'];
	
	// If the current session does not belong to a Student, kick them out.
	if ($dyanmicUserType != 'Student'){
		header('Location: ../index.html');
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
	
	// Initialising vars
	$datesEasy ='';
	$scoresEasy ='';
	
	
		while ($row = $resultReadEasyResults->fetch_assoc()){
			$date = $row['date'];
			$score = $row['score'];
			$notePlayed = $row['notesPlayed'];
			
			$datesEasy = $datesEasy.'"'.$date.'",';
			$scoresEasy = $scoresEasy.$score.',';
		}
		
		$datesEasy = trim($datesEasy, ",");
		$scoresEasy = trim($scoresEasy, ",");
		
	// Grabbing the students recorded data for the Intermediate mode of NotePlay
	// The SQL statement to get all the data from the particular user where the difficulty was intermediate
	$readInterResults = "SELECT * FROM AC_Scores WHERE StudentID = '$dynamicUserID' AND difficulty = 'Intermediate'";
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
			$date = $row['date'];
			$score = $row['score'];
			$notePlayed = $row['notesPlayed'];
			
			$datesIntermediate = $datesIntermediate.'"'.$date.'",';
			$scoresIntermediate = $scoresIntermediate.$score.',';
		}
		
		$datesIntermediate = trim($datesIntermediate, ",");
		$scoresIntermediate = trim($scoresIntermediate, ",");
		
	// Grabbing the students recorded data for the Advanced mode of NotePlay
	// The SQL statement to get all the data from the particular user where the difficulty was Advanced
	$readAdvResults = "SELECT * FROM AC_Scores WHERE StudentID = '$dynamicUserID' AND difficulty = 'Advanced'";
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
			$date = $row['date'];
			$score = $row['score'];
			$notePlayed = $row['notesPlayed'];
			
			$datesAdv = $datesAdv.'"'.$date.'",';
			$scoresAdv = $scoresAdv.$score.',';
		}
		
		$datesAdv = trim($datesAdv, ",");
		$scoresAdv = trim($scoresAdv, ",");
		
	// Grabbing the students recorded data for different types of difficulties played
	$readDiffResults = "SELECT * FROM AC_Scores WHERE StudentID = '$dynamicUserID' AND difficulty = 'Intermediate'";
	// Querying the SQL database
	$resultReadInterResults = $conn -> query($readInterResults);
	// Checking that we get a result from the above query
	if(!$resultReadInterResults){
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

	<!-- Enabling Chart.js to work -->
	<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
</head>
	
<body id="baseBackground">
	<!-- A row holding a row with three columns -->
	<div class="container-fluid">
		<div class="row" id="banner">
			<div class="col-10">
				<h1><strong><em>Acoustica</strong> <span id="studentBanner">Student</span></em></h1>
			</div>
			
			<div class="col-1 btnCenter">
				<a class="btn btn-light" href="editAcc.php"><strong>Edit Account</strong></a>
			</div>
			
			<div class="col-1 btnCenter">
				<a class="btn btn-light" href="userIndex.php"><strong>Home</strong></a>
			</div>
		</div>
	</div>
	
	<!-- A container holding a row with three columns -->
	<div class="container-fluid">
		<div class="row paddingTop">
			<div class="col-2 text-center">
		
			</div>
			
			<div class="col-8 text-center" id="bottomBorderWhite">
			<img src="../imgs/<?php echo $dynamicUserPP;?>" height="200" width="200">
				<h2>Performance in NotePlay</h2>
			</div>
			
			<div class="col-2 text-center">
		
			</div>
		</div>
	</div>
	
	<!-- A container holding a row with three column -->
	<div class="container-fluid">
		<div class="row paddingTop">
			<div class="col-3 text-center">
		
			</div>
			
			<?php
				$numRequests = $resultReadEasyResults->num_rows;
		
									if ($numRequests == 0){
										echo "<div class='col-6 text-center' id='bottomBorderWhite'>
												<p>You have not yet saved any scores on NotePlay easy.</p>
											</div>";
									} else {
										echo "<div class='col-6 text-center' id='bottomBorderWhite'>
												<canvas id='myChart'></canvas>
											</div>";
									}
			?>
			
			<div class="col-3 text-center">
				
			</div>
		</div>
	</div>
	
	<!-- A container holding a row with three column -->
	<div class="container-fluid">
		<div class="row paddingTop">
			<div class="col-3 text-center">
		
			</div>
			
			<?php
				$numRequests2 = $resultReadInterResults->num_rows;
		
									if ($numRequests2 == 0){
										echo "<div class='col-6 text-center' id='bottomBorderWhite'>
												<p>You have not yet saved any scores on NotePlay intermediate.</p>
											</div>";
									} else {
										echo "<div class='col-6 text-center' id='bottomBorderWhite'>
												<canvas id='myChart2'></canvas>
											</div>";
									}
			?>
			
			<div class="col-3 text-center">
				
			</div>
		</div>
	</div>
	
	<!-- A container holding a row with three column -->
	<div class="container-fluid">
		<div class="row paddingTop">
			<div class="col-3 text-center">
		
			</div>
			
				<?php
				$numRequests3 = $resultReadAdvResults->num_rows;
		
									if ($numRequests3 == 0){
										echo "<div class='col-6 text-center' id='bottomBorderWhite'>
												<p>You have not yet saved any scores on NotePlay advanced.</p>
											</div>";
									} else {
										echo "<div class='col-6 text-center' id='bottomBorderWhite'>
												<canvas id='myChart3'></canvas>
											</div>";
									}
			?>
			
			
			<div class="col-3 text-center">
				
			</div>
		</div>
	</div>
	
	<!-- A container holding a row with three column -->
	<div class="container-fluid">
		<div class="row paddingTop">
			<div class="col-3 text-center">
		
			</div>
			
				<?php
				$numRequests3 = $resultReadAdvResults->num_rows;
		
									if ($numRequests == 0 && $numRequests2 == 0 && $numRequests3== 0){
										
									} else {
										echo "<div class='col-6 text-center' id='bottomBorderWhite'>
												<canvas id='myChart4'></canvas>
											</div>";
									}
			?>
			
			
			<div class="col-3 text-center">
				
			</div>
		</div>
	</div>
	
	
	<script>
	var ctx = document.getElementById('myChart').getContext('2d');
	
	var chartOne = new Chart(ctx, {
	// The type of chart we want to create
	type: 'line',

	// The data for our dataset
	data: {
		labels: [<?php echo $datesEasy ?>],
		datasets: [{
			label: 'Easy Mode Score',
			backgroundColor: 'rgb(0, 255, 255)',
			borderColor: 'rgb(255, 255, 255)' ,
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
				ticks: {
                    beginAtZero: true
                },
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
	
	var chartTwo = new Chart(ctx, {
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
				ticks: {
                    beginAtZero: true
                },
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
	
	var chartThree = new Chart(ctx, {
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
				ticks: {
                    beginAtZero: true
                },
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
	
	var ctx = document.getElementById('myChart4').getContext('2d');
	
	var chartFour = new Chart(ctx, {
	// The type of chart we want to create
	type: 'pie',

	// The data for our dataset
	data: {
		labels: ['Easy', 'Intermediate', 'Advanced'],
		datasets: [{
			label: 'Exercises completed',
			backgroundColor: ['rgb(255, 255, 0)', 'rgb(255, 165, 0)', 'rgb(255, 0, 0)'],
			borderColor: 'rgb(255, 255, 255)',
			data: [ <?php echo $numRequests ?>, <?php echo $numRequests2 ?>, <?php echo $numRequests3 ?>]
		}]
	},

	// Configuration options go here
	options: {
		title: {
			display: true,
			text: 'Number of scores saved for each exercise'
		},
		legend: {
			display: false,
			position: 'right'
		},
		scales: {
			xAxes: [{
				display: false,
				scaleLabel: {
					display: false,
					labelString: 'Difficulties'
				}
			}],
			yAxes: [{
				ticks: {
                    beginAtZero: true
                },
				display: false,
				scaleLabel: {
					display: false,
					labelString: 'Number of saved attempts'
				}
			}]
		}
	}
	});
	</script>
	
	<div class="container-fluid">
		<div class="row">
			<div class="col-4 btnCenter">
			</div>
			<div class="col-4 btnCenter">
				<a class="btn btn-light" id="LoginBtn" href="myStats.php"><strong>See Detailed Stats</strong></a>
			</div>
			<div class="col-4 btnCenter">
			</div>
		</div>
	</div>
	
	
	<!-- Enabling bootstraps JS to function. This must stay at the bottom of the html, just before the </body> tag -->
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	
</body>
</html>