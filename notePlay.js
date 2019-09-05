// Instantiating vars
var score = 0;
var notes = ['A', 'D', 'G', 'B', 'E4'];
var numOfGameLoops = 0;
var noteDetected = false;
var desiredNote = "";
var scoreElem = document.getElementById("scoreElement");
var countdown = 0; 
var startBtnEasy = document.getElementById("btnStartEasy");
var startBtnNorm = document.getElementById("btnStartNorm");
var startBtnAdv = document.getElementById("btnStartAdv");


// The function which executes when a user chooses to play an intermediate game
function playGameNorm() {

// Declaring vars
var roundTimer = 0;
var roundTimerSQL = "";
var notesPlayed = "";
var numOfRounds = 0;
var speedSelected = 0;
var difficulty = "";

// Disabling the start button so that a user cannot have two exercises running at once
startBtnNorm.disabled = true;
	
	difficulty = "Intermediate";
	roundTimer = 5000;
	
	// Setting the speed of the rounds
	if (roundTimer == 6000) {
		roundTimerSQL = "Slow";
		speedSelected = 5000;
	} else if (roundTimer == 5000) {
		roundTimerSQL = "Normal";
		speedSelected = 4000;
	} else if (roundTimer == 4000) {
		roundTimerSQL = "Fast";
		speedSelected = 3000;
	}
	notesPlayed = "E2,A,D,G,B,E4";
	numOfRounds = 5;
	
	// Beginning the countdown to the beginning of the exercise
	if (countdown == 0) {
		
		$.notify({
				// options
				message: 'Get ready!', 
			},{
				// settings
				type: 'warning',
				allow_dismiss: false,
				placement: {
					from: "top",
					align: "center"
				},
				offset: 400,
				delay: 1000,
				animate: {
					enter: 'animated lightSpeedIn',
					exit: 'animated fadeOut'
				}
			});
		
		setTimeout(function() {
			$.notify({
				// options
				message: '3', 
			},{
				// settings
				type: 'warning',
				allow_dismiss: false,
				placement: {
					from: "top",
					align: "center"
				},
				offset: 400,
				delay: 1000,
				animate: {
					enter: 'animated lightSpeedIn',
					exit: 'animated fadeOut'
				}
			});
		}, 1000);

		setTimeout(function() {
			$.notify({
				// options
				message: '2', 
			},{
				// settings
				type: 'warning',
				allow_dismiss: false,
				placement: {
					from: "top",
					align: "center"
				},
				offset: 400,
				delay: 1000,
				animate: {
					enter: 'animated lightSpeedIn',
					exit: 'animated fadeOut'
				}
			});
		}, 2000);

		setTimeout(function() {
			$.notify({
				// options
				message: '1', 
			},{
				// settings
				type: 'warning',
				allow_dismiss: false,
				placement: {
					from: "top",
					align: "center"
				},
				offset: 400,
				delay: 1000,
				animate: {
					enter: 'animated lightSpeedIn',
					exit: 'animated fadeOut'
				}
			});
		}, 3000);
	}
	
	console.log(countdown);
	countdown++;
	
	setTimeout(function () {  
		
			// Resetting detector
			noteElement.innerHTML = "~";
		
			// Generating the random note which must be played
			desiredNote = notes[Math.floor(Math.random() * 5)];
			
			// Display to user the required note to be played for a point
			$.notify({
				// options
				message: 'Play the ' + desiredNote + ' string!', 
			},{
				// settings
				type: 'info',
				allow_dismiss: false,
				placement: {
					from: "top",
					align: "center"
				},
				offset: 400,
				delay: 1000,
				animate: {
					enter: 'animated jello',
					exit: 'animated fadeOut'
				}
			});
		
			// Beginning the exercise 
			setTimeout(function () {
				if (noteElement.innerHTML == desiredNote){
					score++;
					
					var successMessage = '';
					var randomNumberBetween1and5 = Math.floor((Math.random() * 5) + 1);
					
					// Generating a random success message based off of the above random num generator
					if (randomNumberBetween1and5 == 1){
						successMessage = 'You got it!';
					} else if (randomNumberBetween1and5 == 2){
						successMessage = 'Well done!';
					} else if (randomNumberBetween1and5 == 3) {
						successMessage = 'Spot on!';
					} else if(randomNumberBetween1and5 == 4) {
						successMessage = 'Bingo!';
					} else if (randomNumberBetween1and5 == 5){
						successMessage = 'Nailed it!';
					}
					
					$.notify({
						// options
						message: successMessage,
					},{
						// settings
						type: 'success',
						allow_dismiss: false,
						placement: {
							from: "top",
							align: "center"
						},
						offset: 400,
						delay: 1000,
						animate: {
							enter: 'animated rubberBand',
							exit: 'animated zoomOutUp'
						}
					});
					
					noteElement.innerHTML = "~";
					
				} else if (noteElement.innerHTML == "~"){
					$.notify({
						// options
						message: 'You have to play something! Anything!',
					},{
						// settings
						type: 'danger',
						allow_dismiss: false,
						placement: {
							from: "top",
							align: "center"
						},
						offset: 400,
						delay: 1000,
						animate: {
							enter: 'animated pulse',
							exit: 'animated pulse'
						}
					});
					
					noteElement.innerHTML = "~";
				
				} else {
					$.notify({
						// options
						message: 'Wrong note!',
					},{
						// settings
						type: 'danger',
						allow_dismiss: false,
						placement: {
							from: "top",
							align: "center"
						},
						offset: 400,
						delay: 1000,
						animate: {
							enter: 'animated rotateInDownLeft',
							exit: 'animated hinge'
						}
					});
					
					noteElement.innerHTML = "~";
					
				}
				
				numOfGameLoops++;
				// When num of game loops != number of rounds, go for another round, else, end the game
				if (numOfGameLoops < numOfRounds) {
					playGameNorm();
				} else if (numOfGameLoops == numOfRounds) {
					startBtnNorm.disabled = false;
					
					// present user with option to save the exercise details
					$.notify({
						// options
						message: 'Congratulations! Your score is: ' + score + ' out of ' + numOfRounds + '!<br>' + 
						'<form action="NotePlayNorm.php" method="post"><input type="hidden" value="'+score+'" name="hiddenScore">' +
						'<input type = "hidden" value="'+numOfRounds+'" name="hiddenNumRounds">' + 
						'<input type = "hidden" value="'+notesPlayed+'" name="hiddenNotesPlayed">' + 
						'<input type = "hidden" value="'+roundTimerSQL+'" name="hiddenSpeed">' + 
						'<input type = "hidden" value="'+difficulty+'" name="hiddenDiff">' + 
						'<button type="submit" name="submitBtn" class="btn btn-light btnPadding"><strong>Save your score</strong></button></form>',
					},{
						// settings
						type: 'success',
						allow_dismiss: true,
						placement: {
							from: "top",
							align: "center"
						},
						offset: 400,
						delay: 0
					});
					
					countdown = 0;
					numOfGameLoops = 0;
					score = 0;
					
				}
			}, speedSelected)
		
	}, roundTimer)
  
} 

// The function which executes when a user chooses to play an easy game
function playGameEasy() {
	
// Declaring vars
var roundTimer = 0;
var roundTimerSQL = "";
var notesPlayed = "";
var numOfRounds = 0;
var speedSelected = 0;
var difficulty = "";

	// Disabling the start button so that a user cannot have two exercises running at once
	startBtnEasy.disabled = true;
	
	difficulty = "Easy";
	
	roundTimer = 6000;
	
	// Setting speed of this exercise
	if (roundTimer == 6000) {
		roundTimerSQL = "Slow";
		speedSelected = 5000;
	} else if (roundTimer == 5000) {
		roundTimerSQL = "Normal";
		speedSelected = 4000;
	} else if (roundTimer == 4000) {
		roundTimerSQL = "Fast";
		speedSelected = 3000;
	}
	
	roundTimerSQL = "Slow";
	numOfRounds = 5;
	
	// Beginning the countdown to the start of the exercise
	if (countdown == 0) {
		
		$.notify({
				// options
				message: 'Get ready!', 
			},{
				// settings
				type: 'warning',
				allow_dismiss: false,
				placement: {
					from: "top",
					align: "center"
				},
				offset: 400,
				delay: 1000,
				animate: {
					enter: 'animated lightSpeedIn',
					exit: 'animated fadeOut'
				}
			});
		
		setTimeout(function() {
			$.notify({
				// options
				message: '3', 
			},{
				// settings
				type: 'warning',
				allow_dismiss: false,
				placement: {
					from: "top",
					align: "center"
				},
				offset: 400,
				delay: 1000,
				animate: {
					enter: 'animated lightSpeedIn',
					exit: 'animated fadeOut'
				}
			});
		}, 1000);

		setTimeout(function() {
			$.notify({
				// options
				message: '2', 
			},{
				// settings
				type: 'warning',
				allow_dismiss: false,
				placement: {
					from: "top",
					align: "center"
				},
				offset: 400,
				delay: 1000,
				animate: {
					enter: 'animated lightSpeedIn',
					exit: 'animated fadeOut'
				}
			});
		}, 2000);

		setTimeout(function() {
			$.notify({
				// options
				message: '1', 
			},{
				// settings
				type: 'warning',
				allow_dismiss: false,
				placement: {
					from: "top",
					align: "center"
				},
				offset: 400,
				delay: 1000,
				animate: {
					enter: 'animated lightSpeedIn',
					exit: 'animated fadeOut'
				}
			});
		}, 3000);
	}
	
	countdown++;
		
	// Beginning the exercise
	setTimeout(function () {  
	
		// Resetting detector
		noteElement.innerHTML = "~";
		
		// Display to user the required note to be played for a point
		$.notify({
			// options
			message: 'Play the ' + stringSelected + ' string!', 
		},{
			// settings
			type: 'info',
			allow_dismiss: false,
			placement: {
				from: "top",
				align: "center"
			},
			offset: 400,
			delay: 1000,
			animate: {
				enter: 'animated jello',
				exit: 'animated fadeOut'
			}
		});
	
		setTimeout(function () {
				if (noteElement.innerHTML == stringSelected){
					score++;
					
					var successMessage = '';
					var randomNumberBetween1and5 = Math.floor((Math.random() * 5) + 1);
					// Generating a random success message based off of the above random num generator
					if (randomNumberBetween1and5 == 1){
						successMessage = 'You got it!';
					} else if (randomNumberBetween1and5 == 2){
						successMessage = 'Well done!';
					} else if (randomNumberBetween1and5 == 3) {
						successMessage = 'Spot on!';
					} else if(randomNumberBetween1and5 == 4) {
						successMessage = 'Bingo!';
					} else if (randomNumberBetween1and5 == 5){
						successMessage = 'Nailed it!';
					}
					
					$.notify({
						// options
						message: successMessage,
					},{
						// settings
						type: 'success',
						allow_dismiss: false,
						placement: {
							from: "top",
							align: "center"
						},
						offset: 400,
						delay: 1000,
						animate: {
							enter: 'animated rubberBand',
							exit: 'animated zoomOutUp'
						}
					});
					
					noteElement.innerHTML = "~";
					
				} else if (noteElement.innerHTML == "~"){
					$.notify({
						// options
						message: 'You have to speed up a bit!',
					},{
						// settings
						type: 'danger',
						allow_dismiss: false,
						placement: {
							from: "top",
							align: "center"
						},
						offset: 400,
						delay: 1000,
						animate: {
							enter: 'animated pulse',
							exit: 'animated pulse'
						}
					});
					
					noteElement.innerHTML = "~";
					
				} else {
					$.notify({
						// options
						message: 'Wrong note!',
					},{
						// settings
						type: 'danger',
						allow_dismiss: false,
						placement: {
							from: "top",
							align: "center"
						},
						offset: 400,
						delay: 1000,
						animate: {
							enter: 'animated rotateInDownLeft',
							exit: 'animated hinge'
						}
					});
					
					noteElement.innerHTML = "~";
				}
				
				numOfGameLoops++;
				if (numOfGameLoops < numOfRounds) {
					playGameEasy();
				} else if (numOfGameLoops == numOfRounds) {
					startBtnEasy.disabled = false;
					
					$.notify({
						// options
						message: 'Congratulations! Your score is: ' + score + ' out of ' + numOfGameLoops + '!<br>' + 
						'<form action="NotePlayEasy.php" method="post"><input type="hidden" value="'+score+'" name="hiddenScore">' +
						'<input type = "hidden" value="'+numOfRounds+'" name="hiddenNumRounds">' + 
						'<input type = "hidden" value="'+stringSelected+'" name="hiddenNotesPlayed">' + 
						'<input type = "hidden" value="'+roundTimerSQL+'" name="hiddenSpeed">' + 
						'<input type = "hidden" value="'+difficulty+'" name="hiddenDiff">' + 
						'<button type="submit" name="submitBtn" class="btn btn-light btnPadding"><strong>Save your score</strong></button></form>',
					},{
						// settings
						type: 'success',
						allow_dismiss: true,
						placement: {
							from: "top",
							align: "center"
						},
						offset: 400,
						delay: 0
					});
					
					countdown = 0;
					numOfGameLoops = 0;
					score = 0;
					
				}
			}, speedSelected)
		
	}, roundTimer)
	
	
}



function playGameHard() {	

// Declaring vars
var roundTimer = 0;
var roundTimerSQL = "";
var notesPlayed = "";
var difficulty = "";

// Disabling button so user cannot have two instances of game running at same time
startBtnAdv.disabled = true;
	
	// stringsSelected is an array created from the users form input at chooseStringsTime.php and saved to a var at NotePlayHard.php
	var arrayLength = stringsSelected.length;
	notesPlayed = stringsSelected.toString();
	
	difficulty = "Advanced";
	// speedSelected is a var taken from the users form input at chooseStringsTime.php and saved to a var at NotePlayHard.php
	if (speedSelected == 3000){
		roundTimer = 4000;
		roundTimerSQL = "Fast";
	} else if (speedSelected == 4000) {
		roundTimer = 5000;
		roundTimerSQL = "Normal";
	} else if (speedSelected == 5000){
		roundTimer = 6000;
		roundTimerSQL = "Slow";
	} else {
		console.log('speedSelected variable is a value that it should not be. Check notePlay.js, NotePlayHard.php and chooseStringsTime.php for errors.'
		+ '\n speedSelected var set to 4000 in the meantime.');
		speedSelected = 4000;
		roundTimer = 5000;
		roundTimerSQL = "Error detected notePlay.js";
	}
	
	// numOfRounds is a var taken from the users form input at chooseStringsTime.php and saved to a var at NotePlayHard.php
	if (numOfRounds == 5 || numOfRounds == 10 || numOfRounds == 20){
		
	} else {
		console.log('numOfRounds var is a value that it should not be. Check notePlay.js, NotePlayHard.php and chooseStringsTime.php for errors.'
		+ '/n numOfRounds set to 5 in the meantime.');
		numOfRounds = 5;
	}
	
	// Beginning GET READY countdown
	if (countdown == 0) {
		
		setTimeout(function() {
			$.notify({
				// options
				message: '3', 
			},{
				// settings
				type: 'warning',
				allow_dismiss: false,
				placement: {
					from: "top",
					align: "center"
				},
				offset: 400,
				delay: 1000,
				animate: {
					enter: 'animated lightSpeedIn',
					exit: 'animated fadeOut'
				}
			});
		}, 1000);

		setTimeout(function() {
			$.notify({
				// options
				message: '2', 
			},{
				// settings
				type: 'warning',
				allow_dismiss: false,
				placement: {
					from: "top",
					align: "center"
				},
				offset: 400,
				delay: 1000,
				animate: {
					enter: 'animated lightSpeedIn',
					exit: 'animated fadeOut'
				}
			});
		}, 2000);

		setTimeout(function() {
			$.notify({
				// options
				message: '1', 
			},{
				// settings
				type: 'warning',
				allow_dismiss: false,
				placement: {
					from: "top",
					align: "center"
				},
				offset: 400,
				delay: 1000,
				animate: {
					enter: 'animated lightSpeedIn',
					exit: 'animated fadeOut'
				}
			});
		}, 3000);
	}
	
	countdown++;
	
	setTimeout(function () {  
		
		// Resetting detector
			noteElement.innerHTML = "~";
		
		// Generating the strings which must be played as per user selection
		desiredString = stringsSelected[Math.floor(Math.random() * (arrayLength))];
	
		// Display to user the required note to be played for a point
		$.notify({
			// options
			message: 'Play the ' + desiredString + ' string!', 
		},{
			// settings
			type: 'info',
			allow_dismiss: false,
			placement: {
				from: "top",
				align: "center"
			},
			offset: 400,
			delay: 1000,
			animate: {
				enter: 'animated jello',
				exit: 'animated fadeOut'
			}
		});
	
		setTimeout(function () {
				if (noteElement.innerHTML == desiredString){
					score++;
					
					var successMessage = '';
					var randomNumberBetween1and5 = Math.floor((Math.random() * 5) + 1);
					// Generating a random success message based off of the above random num generator
					if (randomNumberBetween1and5 == 1){
						successMessage = 'You got it!';
					} else if (randomNumberBetween1and5 == 2){
						successMessage = 'Well done!';
					} else if (randomNumberBetween1and5 == 3) {
						successMessage = 'Spot on!';
					} else if(randomNumberBetween1and5 == 4) {
						successMessage = 'Bingo!';
					} else if (randomNumberBetween1and5 == 5){
						successMessage = 'Nailed it!';
					} else {
						successMessage = 'Nailed it!';
					}
					
					$.notify({
						// options
						message: successMessage,
					},{
						// settings
						type: 'success',
						allow_dismiss: false,
						placement: {
							from: "top",
							align: "center"
						},
						offset: 400,
						delay: 1000,
						animate: {
							enter: 'animated rubberBand',
							exit: 'animated zoomOutUp'
						}
					});
					
					noteElement.innerHTML = "~";
					
				}  else if (noteElement.innerHTML == "~"){
					
					var missedMessage = '';
					var randomNumberBetween1and5 = Math.floor((Math.random() * 2) + 1);
					
					if (randomNumberBetween1and5 == 1){
						missedMessage = 'You have to speed up!';
					} else if (randomNumberBetween1and5 == 2){
						missedMessage = 'Play something, play anything!';
					} else {
						missedMessage = 'Play something, play anything!';
					}
					
					$.notify({
						// options
						message: missedMessage,
					},{
						// settings
						type: 'danger',
						allow_dismiss: false,
						placement: {
							from: "top",
							align: "center"
						},
						offset: 400,
						delay: 1000,
						animate: {
							enter: 'animated pulse',
							exit: 'animated pulse'
						}
					});
					
					noteElement.innerHTML = "~";
					
				} else {
					
					var incorrectMessage = '';
					var randomNumberBetween1and5 = Math.floor((Math.random() * 3) + 1);
					
					if (randomNumberBetween1and5 == 1){
						incorrectMessage = 'Try again.';
					} else if (randomNumberBetween1and5 == 2){
						incorrectMessage = 'Thats the wrong note!';
					} else if (randomNumberBetween1and5 == 3) {
						incorrectMessage = 'You thought THAT was the '+desiredString+' string? Come on...';
					} else {
						incorrectMessage = 'No point for you.';
					}
					
					$.notify({
						// options
						message: incorrectMessage,
					},{
						// settings
						type: 'danger',
						allow_dismiss: false,
						placement: {
							from: "top",
							align: "center"
						},
						offset: 400,
						delay: 1000,
						animate: {
							enter: 'animated rotateInDownLeft',
							exit: 'animated hinge'
						}
					});
					
					noteElement.innerHTML = "~";
				}
				
				numOfGameLoops++;
				if (numOfGameLoops < numOfRounds) {
					playGameHard();
				} else if (numOfGameLoops == numOfRounds) {
					startBtnAdv.disabled = false;
					
					$.notify({
						// options
						message: 'Congratulations! Your score is: ' + score + ' out of ' + numOfRounds + '!<br>' + 
						'<form action="NotePlayHard.php" method="post"><input type="hidden" value="'+score+'" name="hiddenScore">' +
						'<input type = "hidden" value="'+numOfRounds+'" name="hiddenNumRounds">' + 
						'<input type = "hidden" value="'+notesPlayed+'" name="hiddenNotesPlayed">' + 
						'<input type = "hidden" value="'+roundTimerSQL+'" name="hiddenSpeed">' + 
						'<input type = "hidden" value="'+difficulty+'" name="hiddenDiff">' + 
						'<button type="submit" name="submitBtn" class="btn btn-light btnPadding"><strong>Save your score</strong></button></form>',
					},{
						// settings
						type: 'success',
						allow_dismiss: true,
						placement: {
							from: "top",
							align: "center"
						},
						offset: 400,
						delay: 0
					});
					
					countdown = 0;
					numOfGameLoops = 0;
					score = 0;
					
				}
			}, speedSelected)
		
	}, roundTimer)
	
	
}


