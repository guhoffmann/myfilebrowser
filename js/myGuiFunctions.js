var globalAktMediaPath = "/"; 
var input = document.getElementById("inputval");
initFileSelector();

// just some functions for convenience... ;)

function basename(path) {
	return path.split(/[\\/]/).pop();
}

function dirname(path) {
	return path.match('/.*\/');
}

/** Assign event listeners to the buttons of the message box! *****************
 */

input.addEventListener("keyup", function(event) {

	// Cancel the default action, if needed
	event.preventDefault();

	// Number 13 is the "Enter" key on the keyboard
	if (event.keyCode === 13) {

	// Trigger the button element with a click
	document.getElementById("ModalOk").click();

	} else if (event.keyCode === 27) {

	// Trigger the button element with a click

	document.getElementById("ModalClose").click();
	}
});

/** This code belongs to the "tuned" file input element as described in:
 * https://tympanus.net/codrops/2015/09/15/styling-customizing-file-inputs-smart-way/
 */
 
function initFileSelector() {
    
	var input    = document.getElementById("fileinput");
	var label	 = input.nextElementSibling;
	var labelVal = label.innerHTML;

	input.addEventListener( 'change', function( e )
	{
		var fileName = '';
		if( this.files && this.files.length > 1 )
			fileName = ( this.getAttribute( 'data-multiple-caption' ) || '' ).replace( '{count}', this.files.length );
		else
			fileName = e.target.value.split( '\\' ).pop();

		if ( fileName )
			label.innerHTML = fileName;
		else
			label.innerHTML = labelVal;
	});
	label.text="Muaaah!";

} // of function initFileSelector()

/** Simple message box ********************************************************
 */
 
function confirmDialog(title, message, okFunction, closeFunction) {

	// Hide and show necessary window elements

	$("#ModalTitle").html(title);
	$("#inputval").addClass("hidden");
	$("#upload").addClass("hidden");
	$("#ModalClose").removeClass("hidden");
	$("#ModalContent").removeClass("hidden"); 
	$("#ModalContent").html(message);
	$("#ModalMessage").modal();

	// give ModalOk button a function if defined!
	document.getElementById("ModalOk").onclick = function () {
		if (okFunction !== undefined) {
			okFunction();
			location.reload(true);
		}
	};
	// make ModalClose visible if a function is defined and assign it!
	if (closeFunction !== undefined) {
		$("#ModalClose").removeClass("hidden");
		document.getElementById("ModalClose").onclick = function () {
			closeFunction();
			location.reload(true);
		};
	} else { // hide it if no function defined!
		$("#ModalClose").addClass("hidden");
	}
 
} // of function confirmDialog(message, okFunction, closeFunction)


/** Simple message box without buttons ****************************************
 */
 
function messageWindow(title, message) {

	// Hide and show necessary window elements

	$("#ModalTitle").html(title);
	$("#inputval").addClass("hidden");
	$("#upload").addClass("hidden");
	$("#ModalClose").addClass("hidden");
	$("#ModalOk").addClass("hidden");
	$("#ModalContent").removeClass("hidden"); 
	$("#ModalContent").html(message);
	$("#ModalMessage").modal();
 
} // of function messageWindow(title, message)

/** Simple input box **********************************************************
 */
 
function inputDialog(message, okFunction, closeFunction) {

	// Hide and show necessary window elements!

	$("#ModalTitle").html(message);
	$("#inputval").removeClass("hidden");
	$("#ModalClose").removeClass("hidden");
	$("#inputval").val("Eingabetext");
	$("#ModalMessage").modal();
	$("#ModalContent").addClass("hidden"); // erase old text content!
	$("#upload").addClass("hidden");

	document.getElementById("ModalOk").onclick = function () {
		if (okFunction !== undefined) {
			okFunction();
		}
		$("#inputval").addClass("hidden");
		location.reload(true);
	};

	if (closeFunction !== undefined) {
		document.getElementById("ModalClose").style.visibility = "visible";
		document.getElementById("ModalClose").onclick = function () {
			closeFunction();
			$("#inputval").addClass("hidden");
			location.reload(true);
		};
	} else {
		//document.getElementById("ModalClose").style.visibility = "hidden";
	}
  
} // of function inputDialog(message, okFunction, closeFunction)

/** Upload dialog *************************************************************
 */
 
function uploadDialog(message) {

	document.getElementById("upload").classList.remove("hidden");
	$("#ModalTitle").html("<span class='material-icons'>create_new_folder</span>&nbsp;" + message);
	$("#inputval").addClass("hidden");
	$("#ModalClose").removeClass("hidden");
	$("#inputval").val("Eingabetext");
	$("#upload").removeClass("hidden");
	$("#ModalContent").addClass("hidden"); // erase old text content!
	$("#ModalMessage").modal();

	document.getElementById("ModalOk").onclick = function () {
		document.getElementById("uploadDir").value=globalAktMediaPath;
           
		// trigger the upload form and execute uploadPOST.php!!!!
		document.getElementById("upload").submit();
		//location.reload(true);
	}

} // of function uploadDialog(message, okFunction, closeFunction)

/** Create folder ****************************************************
 */
 
function createFolder() {
	
	inputDialog("<span class='material-icons'>create_new_folder</span>&nbsp;Neuer Ordner:",
		function() {
			var folder = globalAktMediaPath +"/" + $("input#inputval").val();
			$.ajax({
				url: "cgi-bin/actions.php",
				data: { objectname: folder, action: "createFolder" },
				dataType: "text", // must be sent for browser to get response correctly!
				success: function(){
					location.reload(true);
				}
			});
		},
		function(){} // declared to see cancel button
	);
} // of function createFolder(path)

/** Show info dialog ****************************************************
 */
 
function infoDialog() {

	$.ajax({
		url: "cgi-bin/actions.php",
		data: { action: "info" },
		dataType: "text", // NOT!!! text/html to get response correctly!!!!
		success: function(data){
			//console.log(data);
			$("#ModalClose").addClass("hidden");
			$("#ModalTitle").html("<span class='material-icons'>info</span>&nbsp;Über Dateimanager");
			$("#inputval").addClass("hidden");
			$("#upload").addClass("hidden");
			$("#ModalContent").html(data);
			$("#ModalContent").removeClass("hidden");
			$("#ModalMessage").modal();
		}
	});

} // of function infoDialog(path)

/** Show PHP info dialog ***********************************************
 */
 
function phpInfo() {

    $.ajax({
		url: "cgi-bin/actions.php",
		data: { action: "phpinfo" },
		dataType: "text", // must be sent for browser to get response correctly!
		success: function(data){
			document.getElementById("app").innerHTML = data;
			//location.reload(true);
		}
	}); 

} // of function phpInfo(path)

/** Delete marked files to from remote machine **************************
 */

function deleteFiles() {

	confirmDialog("<span class='material-icons'>report_problem</span>&nbsp;ACHTUNG!!!",
		"<div class='info'>Datei(en) wirklich löschen?</br><b>Es gibt keinen Papierkorb!</b></div>",
		function() {

			// No matter what I do, can't erase last element in Firefox, strange...
			// Chrome, Opera work without problem :(
			$('input[name="fileaction"]:checked').each(function() {
				var filename = this.value;
				$.ajax({
					url: "cgi-bin/actions.php",
					data: { objectname: filename, action: "deleteFile" },
					dataType: "text", // must be sent for browser to get response correctly!
					success: function(response) {
						//alert("deleteFiles:"+response);
						//location.reload(true);
						console.log(response);
					},
					error: function(response) {
						alert("deleteFiles ERROR, why this?"+response);
						//location.reload(true);
						console.log(response);
					}

				}); // of $.ajax(...
			}); // of .each()..
		}, // of function () ...
		function(){} // declared to see cancel button
	); // of confirmDialog(..

} // of function deleteFiles() ...

/** Copy marked files to clipboard **************************************
 */

function copyFiles() {

	var filesData = [];

	$('input[name="fileaction"]:checked').each(function() {
		var filename = this.value;
		filesData.push(filename);
	});

	$.ajax({
		type: "POST",
		url: "cgi-bin/actions.php",  // first zip files on server
		data: { objectname: filesData, action: "copyToClipboard" },
		dataType: "text",  // must be sent for browser to get response correctly!
		processData: true, // must be true to send JSON array!
		success: function(response) {
			//alert(response);
			location.reload(true);
		},
		error: function(response) {
			alert("copyToClipboard: Puh, Why this?\n"+response);
		}
	});

} // of function copyFiles() ...

/** Paste clipboard to current location *********************************
 */

function pasteFiles() {
	
	path = window.location.search.substr(1);
	$.ajax({
		type: "POST",
		url: "cgi-bin/pasteFiles.php",  // first zip files on server
		data: { uploadDir : path },
		dataType: "text",  // must be sent for browser to get response correctly!
		processData: true, // must be true to send JSON array!
		success: function(response) {
			console.log(response);
			location.reload(true);
		},
		error: function(response) {
			alert("pasteFiles: Puh, Why this?\n"+response);
		}
	});

} // of function pasteFiles() ...

/** Zip and download marked files to local machine **************************
 */

function downloadFiles() {

	var filesData = [];

	$('input[name="fileaction"]:checked').each(function() {
		var filename = this.value;
		filesData.push(filename);
	});

	var zipFileName =  basename(filesData[0]);

	messageWindow("Bitte warten...","<div class='info'>Erstelle Zip-Datei für Download...</div>");
	$.ajax({
		type: "POST",
		url: "cgi-bin/zipFiles.php",  // first zip files on server
		data: { postData : filesData, filename: zipFileName },
		dataType: "text",  // must be sent for browser to get response correctly!
		processData: true, // must be true to send JSON array!
		success: function(response) {
			// after zipping hide the message div with 'click' on unvisible close button...
			$("#ModalMessage .close").click();
			// and start download script (which deletes zip file from server afterwards)
<<<<<<< HEAD
			window.location.href = "/cgi-bin/actions.php?objectname=/zipfiles/" + response + "&action=downloadZipAndDelete";
=======
			window.location.href = "/cgi-bin/actions.php?objectname=/zipfiles/" + response;
>>>>>>> 11896b4635996b8fbfc6e7ada38a5a57720ea6f2
		},
		error: function(response) {
			alert("zipFiles: Puh, Why this?\n"+response);
		}
	});

} // of function downloadFiles() ...


