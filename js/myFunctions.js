/** myFunctions.js ************************************************************
 *
 * GUI functions, ajax handlers etc. for the MyFileBrowser web app
 *
 */
 
var globalAktMediaPath = "/"; 
var input = document.getElementById("inputval");
var languageStrings = {};

jQuery(document).ready(function($) {

  if (window.history && window.history.pushState) {

    //window.history.pushState('forward', null, globalAktMediaPath);

    $(window).on('popstate', function() {
      //alert('Back button was pressed.');
    });

  }
});

/** Function for doing all global init stuff!!! *******************************
 ** (calls itself after declaration!)
 */

( function() {

	initFileSelector();

	// read all language strings from database into JavaScript object!

	$.ajax({
		url: "cgi-bin/actions.php",
		data: { action: "getStrings" },
		dataType: "json",
		
		success: function(response){
			languageStrings = response;
		}
	});

} ) ();

// just some functions for convenience... ;)

function basename(path) {
	return path.split(/[\\/]/).pop();
}

function dirname(path) {
	return path.match('/.*\/');
}

/******************************************************************************
 ** Assign event listeners to the buttons of the message box!
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

/******************************************************************************
 ** This code belongs to the "tuned" file input element as described in:
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

/******************************************************************************
 ** Simple message box
 */
 
function confirmDialog(title, message, okFunction, closeFunction) {

	// Hide and show necessary window elements

	$("#ModalTitle").html(title);
	$("#inputval").addClass("hidden");
	$("#edittext").addClass("hidden");
	$("#upload").addClass("hidden");
	$("#ModalClose").removeClass("hidden");
	$("#ModalOk").removeClass("hidden");
	$("#ModalContent").removeClass("hidden"); 
	$("#ModalContent").html(message);
	$("#ModalMessage").modal();

	// give ModalOk button a function if defined!
	document.getElementById("ModalOk").onclick = function () {
		if (okFunction !== undefined) {
			okFunction();
			//location.reload(true);// should not be called here, but in okFunction to work with Firefox!!!!!!!
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

/******************************************************************************
 ** Simple message box without buttons
 */
 
function messageWindow(title, message) {

	// Hide and show necessary window elements

	$("#ModalTitle").html(title);
	$("#inputval").addClass("hidden");
	$("#edittext").addClass("hidden");
	$("#upload").addClass("hidden");
	$("#ModalClose").addClass("hidden");
	$("#ModalOk").addClass("hidden");
	$("#ModalContent").html(message);
	$("#ModalContent").removeClass("hidden"); 
	$("#ModalMessage").modal();
 
} // of function messageWindow(title, message)

/******************************************************************************
 ** Simple text edit box
 */
 
function textDialog(message, okFunction, closeFunction) {

	// Hide and show necessary window elements!

	$("#ModalTitle").html(message);
	$("#edittext").removeClass("hidden");
	$("#ModalClose").removeClass("hidden");
	$("#ModalOk").removeClass("hidden");
	$("#ModalMessage").modal();
	$("#ModalContent").addClass("hidden"); // erase old text content!
	$("#upload").addClass("hidden");

	document.getElementById("ModalOk").onclick = function () {
		if (okFunction !== undefined) {
			okFunction();
		}
		$("#edittext").addClass("hidden");
		//location.reload(true);// should not be called here, but in okFunction to work with Firefox!!!!!!!
	};

	if (closeFunction !== undefined) {
		document.getElementById("ModalClose").style.visibility = "visible";
		document.getElementById("ModalClose").onclick = function () {
			closeFunction();
			$("#edittext").addClass("hidden");
			location.reload(true);
		};
	} else {
		//document.getElementById("ModalClose").style.visibility = "hidden";
	}
  
} // of function textDialog(message, okFunction, closeFunction)
					//location.reload(true);
/******************************************************************************
 ** Simple input box
 */
 
function inputDialog(message, okFunction, closeFunction) {

	// Hide and show necessary window elements!

	$("#ModalTitle").html(message);
	$("#inputval").removeClass("hidden");
	$("#edittext").addClass("hidden");
	$("#ModalClose").removeClass("hidden");
	$("#ModalOk").removeClass("hidden");
	$("#inputval").val("Eingabetext");
	$("#ModalMessuploadage").modal();
	$("#ModalContent").addClass("hidden"); // erase old text content!
	$("#upload").addClass("hidden");

	document.getElementById("ModalOk").onclick = function () {
		if (okFunction !== undefined) {
			okFunction();
		}
		$("#inputval").addClass("hidden");
		//location.reload(true);// should not be called here, but in okFunction to work with Firefox!!!!!!!
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
					//location.reload(true);
/******************************************************************************
 ** Upload dialog
 */
 
function uploadDialog(message) {

	if ( (languageStrings["userrights"] & 8) == 0 ) {
		confirmDialog("Message","<div class='info'>You have no user rights to upload files!</br>Keine Benutzerrechte zum hochladen von Daten!</div>",function(){});
		return;
	}

	document.getElementById("upload").classList.remove("hidden");
	$("#ModalTitle").html("<span class='material-icons'>create_new_folder</span>&nbsp;" + message);
	$("#inputval").addClass("hidden");
	$("#edittext").addClass("hidden");
	$("#ModalClose").removeClass("hidden");
	$("#inputval").val("Eingabetext");
	$("#upload").removeClass("hidden");
	$("#ModalContent").addClass("hidden"); // erase old text content!
	$("#ModalMessage").modal();

	document.getElementById("ModalOk").onclick = function () {

		document.getElementById("uploadDir").value=globalAktMediaPath;
           
		/* trigger the upload form and execute uploadPOST.php!!!!
		  Below the old fallback if the new ajax version doesn't work satisfactory!

		  document.getElementById("upload").submit();*/
		//===========================================

		// here the new inserted ajax version!

		var formData = new FormData(document.getElementById("upload"));
		$("footer").removeClass("hidden");
		$('progress').attr({
			value: 0,
			max: 100,
		});
		
		$.ajax({
		  url: 'cgi-bin/actions.php',
		  type: 'POST',
		  data: formData,
		  // jQuery MUST NOT process cache, contentType, processData!
		  cache: false,
		  contentType: false,
		  processData: false,
	/*		beforeSend: window.onbeforeunload = function(e) {
				// Cancel the event
				//e.preventDefault();
				// Chrome requires returnValue to be set
				e.returnValue = 'Must not be empty';
			},*/
		  // Custom XMLHttpRequest
		  xhr: function() {
				var myXhr = $.ajaxSettings.xhr();
				if (myXhr.upload) {

					 myXhr.upload.addEventListener('load', function(e) {
						console.log("Upload finished!");
						// Inserting a message dialog into dom at this place didn't work in Chrome for short uploads
						// but for Firefox only. So a dumb reload must do...
						location.reload(true);
					 }); // of myXhr.upload.addEventListener('load', function(e) 

					 // For handling the progress of the upload
					 myXhr.upload.addEventListener('progress', function(e) {
						  if (e.lengthComputable) {
						  value = Math.round(e.loaded/e.total*1000)/10;
								if (value < 100 ) {
									$("#progress-bar").text(value + "%");
									$("#progress-bar").css("width", value + "%");
								} else {
									$("#progress-bar").text(languageStrings["upload_completed_wait"] +  value + "%");
									$("#progress-bar").css("width", value + "%");
								}
						  }
					 } , false);
				} // of if (myXhr.upload)...

				return myXhr;
		  }
		}); // of $.ajax({...

		// end of inserted ajax version.

		// Don't uncomment, uploads won't work on Chrome based browsers then!!!
		//location.reload(true); // NEVER UNCOMMENT THIS!!!!
	}

} // of function uploadDialog(message, okFunction, closeFunction)

/******************************************************************************
 ** Edit notice
 */

function editNotice() {

	// first get content of actual notizen.txt
	$.ajax({
				url: "cgi-bin/actions.php",
				data: { objectname: globalAktMediaPath, action: "readNotice" },
				success: function(response){
					$("textarea#edittext").val(response);
				},
				error: function (response) {
					$("textarea#edittext").val("Fehla!");
			}
	});

	if ( (languageStrings["userrights"] & 8) == 0 ) {
		textDialog("<span class='material-icons'>assignment</span>&nbsp;Notiz einsehen:",
			function() {}	
		);

	} else {
		textDialog("<span class='material-icons'>assignment</span>&nbsp;Notiz bearbeiten:",
			function() {
				var noticeText = $("textarea#edittext").val();
				$.ajax({
					url: "cgi-bin/actions.php",
					data: { content: noticeText, pathname: globalAktMediaPath, action: "saveNotice" },
					success: function(){
						location.reload(true); // call it here and not in button click event to work with Firefox!!!
					},
					error: function (response) {
						alert(response);
				}
				});
			},
			function(){} // declared to see cancel button
		);
	} // of else...

} // of function editNotice(path)

/******************************************************************************
 ** Create folder
 */
 
function createFolder() {

	if ( (languageStrings["userrights"] & 16) == 0 ) {
		confirmDialog("Message","<div class='info'>You have no user rights to create folders!</br>Keine Benutzerrechte zum erstellen von Ordnern!</div>",function(){});
		return;
	}

	inputDialog("<span class='material-icons'>create_new_folder</span>&nbsp;Neuer Ordner:",
		function() {
			var folder = globalAktMediaPath +"/" + $("input#inputval").val();
			//alert("Before: " + folder);
			$.ajax({
				url: "cgi-bin/actions.php",
				data: { objectname: folder, action: "createFolder" },
				success: function(){
					location.reload(true); // call it here and not in button click event to work with Firefox!!!
				},
				error: function (response) {
			}
			});
		},
		function(){} // declared to see cancel button
	);
} // of function createFolder(path)

/******************************************************************************
 ** Show info dialog
 */
 
function infoDialog() {

	$.ajax({
		url: "cgi-bin/actions.php",
		data: { action: "showInfo" },
		dataType: "html", // NOT BOTH(!!!) text/html, but only text or html to get response correctly!!!!
		success: function(response){
			messageWindow("<span class='material-icons'>info</span>&nbsp;"+languageStrings["progname"], response);
		}
	});

} // of function infoDialog(path)

/******************************************************************************
 ** Show PHP info dialog
 */
 
function phpInfo() {

    $.ajax({
		url: "cgi-bin/actions.php",
		data: { action: "phpinfo" },
		dataType: "html",
		success: function(data){
			document.getElementById("app").innerHTML = data;
			//location.reload(true);
		}
	}); 

} // of function phpInfo(path)

/******************************************************************************
 ** Delete marked files to from remote machine
 */

function deleteFiles() {
	if ( (languageStrings["userrights"] & 1) == 0 ) {
		confirmDialog("Message","<div class='info'>You have no user rights to delete files!</br>Keine Benutzerrechte zum löschen von Daten!</div>",function(){});
		return;
	}
	confirmDialog("<span class='material-icons'>report_problem</span>&nbsp;ACHTUNG!!!",
		"<div class='info'>Datei(en) wirklich löschen?</br><b>Es gibt keinen Papierkorb!</b></div>",
		function() {
			
			// No matter what I do, can't erase last element in Firefox, strange...
			// Chrome, Opera work without problem :(

			$('input[name="fileaction"]:checked').each(function() {
				var filename = this.value;
				//alert("Delete: " + filename);
				$.ajax({
					url: "cgi-bin/actions.php",
					data: { objectname: filename, action: "deleteFile" },
					dataType: "text", // must be sent for browser to get response correctly!
					success: function(response) {
						if (response == "error") {
							alert("You have no user rights to delete files!\nKeine Benutzerrechte zum löschen von Daten!");
						}
						location.reload(true);
					},
					error: function(response) {
						alert("deleteFiles ERROR, warum? Noch Unterverzeichnisse drin? Rechte fehlerhaft?");
						location.reload(true);
					}

				}); // of $.ajax(...
			}); // of .each()..

		}, // of function () ...
		function(){} // declared to see cancel button
	); // of confirmDialog(..

} // of function deleteFiles() ...

/******************************************************************************
 ** Copy marked files to clipboard
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
			messageWindow("<span class='material-icons'>assignment</span>&nbsp;"+languageStrings["clipboard"], response);
		},
		error: function(response) {
			alert("copyToClipboard: Puh, Why this?\n"+response);
		}
	});

} // of function copyFiles() ...

/******************************************************************************
 ** Clear clipboard
 */

function clearClipboard() {

	$.ajax({
		url: "cgi-bin/actions.php",  // first zip files on server
		data: { action: "clearClipboard" },
		dataType: "text",  // must be sent for browser to get response correctly!
		success: function(response) {
			//alert(response);
			messageWindow("<span class='material-icons'>info</span>&nbsp;Info!",response);		
			//location.reload(true);
		},
		error: function(response) {
			alert("clearClipboard: Puh, Why this?\n"+response);
		}
	});

} // of function clearClipboard() ...

/******************************************************************************
 ** Show clipboard
 */

function showClipboard() {

	$.ajax({
		url: "cgi-bin/actions.php",  // first zip files on server
		data: { action: "showClipboard" },
		dataType: "text",  // must be sent for browser to get response correctly!
		success: function(response) {
			messageWindow("<span class='material-icons'>assignment</span>&nbsp;"+languageStrings["clipboard"], response);
			//location.reload(true);
		},
		error: function(response) {
			alert("clearClipboard: Puh, Why this?\n"+response);
		}
	});

} // of function showClipboard() ...

/******************************************************************************
 ** Paste clipboard to current location
 */

function pasteFiles() {
	if ( (languageStrings["userrights"] & 4) == 0 ) {
		confirmDialog("Message","<div class='info'>You have no user rights to paste files!</br>Keine Benutzerrechte zum einfügen von Daten!</div>",function(){});
		return;
	}

	path = window.location.search.substr(1);
	$.ajax({
		url: "cgi-bin/actions.php", 
		data: { uploadDir : globalAktMediaPath, action: "pasteFiles" },
		dataType: "text",  // must be sent for browser to get response correctly!
		success: function(response) {
			if (response == "error") {
				alert("You have no user rights to paste files!\nKeine Benutzerrechte zum einfügen von Daten!");
			}
			//alert(response);
			location.reload(true);
		},
		error: function(response) {
			if (response == "error") {
							alert("You have no user rights to paste files!\nKeine Benutzerrechte zum einfügen von Daten!");
			}
			alert("pasteFiles: Puh, Why this?\n"+response);
		}
	});

} // of function pasteFiles() ...

/******************************************************************************
 ** Zip and download marked files to local machine
 */

function downloadFiles() {

	var filesData = [];

	// Get all checked files (=checked checkboxes with name 'fileaction') to filesData array
	$('input[name="fileaction"]:checked').each(function() {
		var filename = this.value;
		filesData.push(filename);
	});

	var zipFileName =  basename(filesData[0]); // zip name is basename of first file

	messageWindow("Bitte warten...","<div class='info'>Erstelle zip für Download...</div>");
	$.ajax({
		type: "POST",
		url: "cgi-bin/actions.php",  // first zip files on server
		data: { action: "zipFiles", postData : filesData, filename: zipFileName },
		dataType: "text",  // must be sent for browser to get response correctly!
		processData: true, // must be true to send JSON array!
		success: function(response) {
			// after zipping hide the message div with 'click' on unvisible close button...
			$("#ModalMessage .close").click(); // must do this 2 times to get it always working!
			// and start download script (which deletes zip file from server afterwards)
			window.location.href = "/cgi-bin/actions.php?objectname=/zipfiles/" + response + "&action=downloadZipAndDelete";
			// after zipping hide the message div with 'click' on unvisible close button...
			$("#ModalMessage .close").click(); // must do this 2 times to get it always working!
		},
		error: function(response) {
			alert("zipFiles: Puh, Why this?\n"+response);
		}
	});

} // of function downloadFiles() ...

/******************************************************************************
 ** Zip and download files from clipboard to local machine
 */

function downloadFilesFromClipboard() {

	messageWindow("Bitte warten...","<div class='info'>Erstelle zip für Download...</div>");
	$.ajax({
		type: "POST",
		url: "cgi-bin/actions.php",  // first zip files on server
		data: { action: "zipClipboardFiles" },
		dataType: "text",  // must be sent for browser to get response correctly!
		success: function(response) {
			// after zipping hide the message div with 'click' on unvisible close button...
			$("#ModalMessage .close").click(); // must do this 2 times to get it always working!
			// and start download script (which deletes zip file from server afterwards)
			window.location.href = "/cgi-bin/actions.php?objectname=/zipfiles/" + response + "&action=downloadZipAndDelete";
			// after zipping hide the message div with 'click' on unvisible close button...
			$("#ModalMessage .close").click(); // must do this 2 times to get it always working!
		},
		error: function(response) {
			alert("downloadClipboardFiles: Puh, Why this?\n"+response);
		}
	});

} // of function downloadFilesFromClipboard() ...

/******************************************************************************
 ** Change language
 */

function changeLanguage(language) {

	$.ajax({
		url: "cgi-bin/actions.php",  // first zip files on server
		data: { action: "changeLanguage", language: language },
		dataType: "text",  // must be sent for browser to get response correctly!
		success: function(response) {
			///messageWindow("Antwort", response);
			location.reload(true);
		},
		error: function(response) {
			alert("changeLanguage: Puh, Why this?\n"+response);
		}
	});

} // of function changeLanguage(language) ...

/** Open/close navbar at side (=sidenav)
 *  by changing width of object
 */
function openNav() {
    var obj = document.getElementById("mySidenav");
    obj.style.width = "23em";
    obj.className = "sidenav sidenav_rand";
}

function closeNav() {
    var obj = document.getElementById("mySidenav");
    obj.style.width = "0";
    obj.className = "sidenav";
}

/** Add animated scroll functionality for the PageUp button
 */
function pageUp() {
   var scrollVal = window.pageYOffset*0.2;
   //console.log(scrollVal);
   $('html,body').animate({scrollTop:0}, scrollVal);
}


