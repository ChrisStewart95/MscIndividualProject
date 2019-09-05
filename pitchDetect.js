// Declaring variables
var audioContext = null;
var analyser = null;
var streamSrc = null;
var noteDetectElement;
var pitchElement;
var noteElement;
var numOfBings = 0;
var startBtnEasy2 = document.getElementById("btnStartEasy");
var startBtnNorm2 = document.getElementById("btnStartNorm");
var startBtnAdv2 = document.getElementById("btnStartAdv");

// The below executes the function when the pitchDetect.html web page fully loads
window.onload = function() {
	audioContext = new AudioContext();
	// Setting the elements in the HTML to the variables declared earlier
	noteDetectElement = document.getElementById( "noteDetect" );
	pitchElement = document.getElementById( "pitch" );
	noteElement = document.getElementById( "note" );
	
}


// The below function executes when the user clicks on the appropriate button on the pitchDetect.html page
function accessMic() {
    getMicAudio();
	
}

// The below function gets the audio input from the users mic in real time 
// It also now draws the oscilloscope
function getMicAudio() {
    navigator.mediaDevices.getUserMedia({
		audio: true
	}).then(function(stream) {
		
		// Uses the audioContext to grab the audio coming from the users mic and saves it to the 
		// streamSrc variable, declared earlier
		streamSrc = audioContext.createMediaStreamSource(stream);
			   
			// Remove comment slashes below to hear the audio being captured by the mic
			// streamSrc.connect(audioContext.destination);

		// Using the audiocontext to create an analyser node
		analyser = audioContext.createAnalyser();
		analyser.fftSize = 2048;
		// Connecting the stream of audio to the analyser node
		streamSrc.connect(analyser);
		
		// Calling updatePitch() function
		updatePitch();
		
		// Calling drawOsc() function
		drawOsc();
		try {
		startBtnEasy2.disabled = false;
		} catch (err){
		}
		try {
		startBtnNorm2.disabled = false;
		} catch (err){
		}
		try {
		startBtnAdv2.disabled = false;
		} catch (err){
		}
		
	}).catch(function(err){
		console.log("An error with the getMicAudio() function: " + err)
	})
}

// The below executes when the getMicAudio() function is executed, and draws the oscilloscope from the data in the analyser
function drawOsc() {
	// Settings the canvas variable to the canvas element in the pitchDetect.html page
	var canvas = document.getElementById("oscilloscope");
	var canvasContext = canvas.getContext("2d")
	// Generating data from the FFT performed on the sound stream with the analyser node
	var buffLength = analyser.frequencyBinCount;
	// Creating an array to hold the values generated. Since FFT produces 8-bit unsigned 
	// integers,a Uint8Array must be used.
	var audioArray = new Uint8Array(buffLength);

	// Capturing waveform data, and copying it into audioArray
	analyser.getByteTimeDomainData(audioArray);
	
	// The below fills the canvas with a colour, and sets line width and colour
	canvasContext.fillStyle = "rgb(0, 0, 0)";
	canvasContext.fillRect(0, 0, canvas.width, canvas.height);
	canvasContext.lineWidth = 4;
	canvasContext.strokeStyle = "rgb(0, 255, 255)";
	canvasContext.beginPath();
	
	// Determines the width of each segment of the line to be drawn by dividing the canvas width by the array length,
	// then defines an x variable to define the position to move to for drawing each segment of the line.
	var dataWaveWidth = canvas.width * 1.0 / buffLength;
	var pointX = 0;

	// Run through a loop and define the position of a small segment of the wave for each point in the buffer at a 
	// certain height based on the data point value from the array, then moving the line across to the place where the next
	// wave segment should be drawn
	for (var canvasLoop = 0; canvasLoop < buffLength; canvasLoop++) {
		
		var variation = audioArray[canvasLoop] / 128.0;
		var pointY = variation * canvas.height / 2;
				
		if (canvasLoop === 0) {
			canvasContext.moveTo(pointX, pointY);
		} else {
			canvasContext.lineTo(pointX, pointY);
		}
	
		pointX += dataWaveWidth;
	}

	canvasContext.lineTo(canvas.width, canvas.height / 2);
	canvasContext.stroke();
	
	// The below tells the browser that I want to perform an animation and requests that the browser
	// call a specified function (in this case drawOsc()) to update an animation before the next repaint.
	// My understanding of this is that this is what constantly updates the graph, 60 times per second, 
	// giving it the appearance that its moving like a soundwave. 
	// As a note for the future, I think this could be refined by ensuring that everytime the draw() 
	// function is called, efforts aren't wasted doing the bufferlength and audioArray statements above.
	requestAnimationFrame(drawOsc);

}

// The below is just the same as saying "analyser.fftSize = 1024; var buffLength = analyser.fftSize;..."
// But we have to do it this way since we're instantiating this outside of the functions in which 
// analsyer was actually instantiated
var buffLength = 1024;
var buffArray = new Float32Array(buffLength);

// This function is passed a value, called frequency, (and occupied by var pitch, written in a later function) and uses this value along with Wilson's mathematic equations to return a note
function noteFromPitch(frequency) {

	if (frequency >= 328 && frequency <= 334){
		return "E4";
	} else if (frequency >= 246 && frequency <= 251){
		return "B";
	} else if (frequency >= 185 && frequency <= 198){
		return "G";
	} else if (frequency >= 144 && frequency <= 149){
		return "D";
	} else if (frequency >= 106 && frequency <= 111){ 
		return "A";
	} else if (frequency >= 81 && frequency <= 83){
	} else {
		return "~";
	}
}


// ***   ***   ***   ***   ***   ***   ***   ***   ***   ***   ***   ***   ***   ***   ***
// Code from line 181 - 226 is based upon the work of Chris Wilson. Specifically, his mathematics in order to 
// determine correlations from frequency.
// All comments however, are my own, and have been added for readability and understanding.
/*
The MIT License (MIT)
Copyright (c) 2014 Chris Wilson
Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:
The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.
THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
*/


// Wilson's original code states that the var below will initialize when the audioContext is created.
var MIN_SAMPLES = 0;
// The below variable sets the bar for how close the correlation needs to be 
var ACCEPTABLE_CORR_RANGE = 0.9;

function correlate( buffArray, sampleRate ) {
	var SIZE = buffArray.length;
	var MAX_SAMPLES = Math.floor(SIZE/2);
	var best_offset = -1;
	var best_correlation = 0;
	var rms = 0;
	var foundGoodCorrelation = false;
	var correlations = new Array(MAX_SAMPLES);

	for (var i=0;i<SIZE;i++) {
		var val = buffArray[i];
		rms += val*val;
	}
	rms = Math.sqrt(rms/SIZE);
	if (rms<0.01)
		return -1;

	var lastCorrelation=1;
	for (var offset = MIN_SAMPLES; offset < MAX_SAMPLES; offset++) {
		var correlation = 0;

		for (var i=0; i<MAX_SAMPLES; i++) {
			correlation += Math.abs((buffArray[i])-(buffArray[i+offset]));
		}
		correlation = 1 - (correlation/MAX_SAMPLES);
		correlations[offset] = correlation; 
		if ((correlation>ACCEPTABLE_CORR_RANGE) && (correlation > lastCorrelation)) {
			foundGoodCorrelation = true;
			if (correlation > best_correlation) {
				best_correlation = correlation;
				best_offset = offset;
			}
		} else if (foundGoodCorrelation) {
			var shift = (correlations[best_offset+1] - correlations[best_offset-1])/correlations[best_offset];  
		}
		lastCorrelation = correlation;
	}
	if (best_correlation > 0.01) {
		return sampleRate/best_offset;
	}
	return -1;
}
// Code based upon Chris Wilson's mathematics ends here
// ***   ***   ***   ***   ***   ***   ***   ***   ***   ***   ***   ***   ***   ***   ***


// This function repeatedly calls itself, and if the correlation between incoming audio
// and an existing note is close enough, it will update
// the appropriate element in the HTML to display the corresponding note.
function updatePitch(time) {
	
	// User the analyser node to get the time-domain data from incoming audio 
	analyser.getFloatTimeDomainData(buffArray);
	// pass the time and sample rate data into the correlation function and save result
	// to corrResult (the correlation between frequency and a note)
	var corrResult = correlate( buffArray, audioContext.sampleRate );

	// If no sufficient correlation is found, display nothing
 	if (corrResult == -1) {
	 	pitchElement.innerText = "~";
		// If this code executes after a note has already been detected then lost, then that 
		//note shall stay displayed on the noteElement in the HTML, allowing us to access the
		//last detected note even after periods of silence.
		
	// Else, if a correlation is found, display the appropriate note
 	} else {
	 	noteDetectElement.className = "col-2 feedback";
	 	pitch = corrResult;
		// The value of the pitch is rounded to the nearest whole number and is displayed on the HTML
	 	pitchElement.innerText = Math.round(pitch) ;
		// The pitch var is entered into the noteFromPitch function and used to get the value of the note.
	 	var note =  noteFromPitch(Math.round(pitch));
		if (note != "~"){
			noteElement.innerHTML = note;
		}
		
	}
	
	// The below recalls the updatePitch function to continually update the appropriate HTML elements with the latest detected notes.
	requestAnimationFrame(updatePitch);
}



