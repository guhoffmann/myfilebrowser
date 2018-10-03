
var globalAktMediaPath = "/"; 
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

// Get the input field
var input = document.getElementById("inputval");

// Execute a function when the user releases a key on the keyboard
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


/** spa router ****************************************************************
 */
 
// domEntryPoint = start of app (see id in HTM file)

const startingPoint = document.getElementById('app');

/** create new router object **************************************************
 */

const myrouter = function () {
  
   // Map object containing all possible routes
   
   var routes = new Map();

   // add a route to routes Map, containing hashUrl as key
   // and handler function as value
   
   function addRoute(hashUrl, routeHandler) {
      routes.set(hashUrl, routeHandler);
   }

   // make hashUrl the active hash part of current URL
   
   function navigateToHashUrl(hashUrl) {
      location.hash = hashUrl;
   }

   // route handler, 
   
   function handleRouting() {
      defaultRouteIdentifier = '*';
      currentHash = location.hash.slice(1); // cut hash from location
      // if routes map conhttps://www.google.de/?gws_rd=ssltains key "currentHash": return correspondig routeHandler,
      // else return defaultRouteIdentifier handler
      routeHandler = routes.has(currentHash) ? routes.get(currentHash) : routes.get(defaultRouteIdentifier);
      // if route handler is found, call it with parameter domEntryPoint!
      if (routeHandler) {
         routeHandler();
      }
   };
   
  // ON INITIALISATION:
  // call route handling on events 'hashchange', 'load'
  
   if (window) {
      window.addEventListener('hashchange', handleRouting);
      window.addEventListener('load', handleRouting);
   }
   
   // return object containing adRoute, navigateToHashUrl
   
   return { addRoute, navigateToHashUrl };
   
}(); // create and do the self-execution!

function printLocation() {
    loc = "</br>Protocol: " + window.location.protocol +
           "</br>Host: " + window.location.hostname +
           "</br>Path: " + window.location.pathname +
           "</br>Search: " + window.location.search +
           "</br>Hash: " + window.location.hash;
    return loc;
}

/** add routes and event handlers **********************************************
 */

myrouter.addRoute('list', function () {
   
   path = window.location.search.substr(1);
   globalAktMediaPath = path;
   
   $.ajax({
            url: "cgi-bin/dirlist.php",
            data: { pathname: path},
            dataType: "text html", // must be sent for browser to get response correctly!
            success: function(data){
                startingPoint.innerHTML = data;
            }
    });
});

myrouter.addRoute('*', function () {
   
   $.ajax({
            url: "cgi-bin/dirlist.php",
            data: { pathname: '/'},
            dataType: "text html", // must be sent for browser to get response correctly!
            success: function(data){
                startingPoint.innerHTML = data;
            }
    });
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

            if( fileName )
                label.innerHTML = fileName;
            else
                label.innerHTML = labelVal;
        });
        label.text="Scheisse!";
        
} // of function initFileSelector()

/** A kind of simple message box for web interfaces
 */
 
function confirmDialog(title, message, okFunction, closeFunction) {
    
    document.getElementById("upload").classList.add("hidden");
    document.getElementById("inputval").classList.add("hidden");
    document.getElementById("messagetitel").innerHTML = title;
    document.getElementById("messagetext").innerHTML = message;
    document.getElementById("inputval").style.visibility = "hidden";
    document.getElementById("darkendiv").style.visibility = "visible";
    document.getElementById("messagediv").style.visibility = "visible";
    
    if (okFunction !== undefined) {

	document.getElementById("okbutton").style.visibility = "visible";
  	document.getElementById("okbutton").onclick = function () {
		document.getElementById("darkendiv").style.visibility = "hidden";
		document.getElementById("messagediv").style.visibility = "hidden";
		if (okFunction !== undefined) {
			okFunction();
		}
		location.reload(true);
    	};
    }  else {
	document.getElementById("okbutton").classList.add("hidden");
    }

    if (closeFunction !== undefined) {
		
        document.getElementById("closebutton").style.visibility = "visible";
        document.getElementById("closebutton").onclick = function () {
			
            document.getElementById("darkendiv").style.visibility = "hidden";
            document.getElementById("messagediv").style.visibility = "hidden";
            closeFunction();
            location.reload(true);
        };
    } else {
	document.getElementById("closebutton").classList.add("hidden");
    }
    
} // of function confirmDialog(message, okFunction, closeFunction)

/** A kind of simple input box for web interfaces
 */
 
function inputDialog(message, okFunction, closeFunction) {
   
	//$("#ModalMessage").modal();
	$("#ModalTitle").text(message);
	$("#inputval").removeClass("hidden");
	$("#inputval").val("Eingabetext");
	$("#ModalMessage").modal();
	$("#ModalContent").text(""); // erase old text content!

	document.getElementById("ModalOk").onclick = function () {
		if (okFunction !== undefined) {
			okFunction();
		}
		location.reload(true);
	};
	if (closeFunction !== undefined) {
		document.getElementById("ModalClose").style.visibility = "visible";
		document.getElementById("ModalClose").onclick = function () {
			closeFunction();
			location.reload(true);
		};
	} else {
		document.getElementById("ModalClose").style.visibility = "hidden";
	}
  
} // of function inputDialog(message, okFunction, closeFunction)


/** A kind of simple input box for web interfaces
 */
 
function uploadDialog(message) {
   
    document.getElementById("upload").classList.remove("hidden");
    document.getElementById("inputval").classList.add("hidden");
    document.getElementById("messagetitel").innerHTML = message;
    document.getElementById("darkendiv").style.visibility = "visible";
    document.getElementById("messagediv").style.visibility = "visible";
    document.getElementById("okbutton").onclick = function () {
            document.getElementById("darkendiv").style.visibility = "hidden";
            document.getElementById("inputval").style.visibility = "hidden";
            document.getElementById("messagediv").style.visibility = "hidden";
            
            document.getElementById("uploadDir").value=globalAktMediaPath;
            
            // trigger the upload form and execute uploadPOST.php!!!!
            document.getElementById("upload").submit();
            //location.reload(true);
    }

	document.getElementById("closebutton").style.visibility = "visible";
	document.getElementById("closebutton").onclick = function () {
		document.getElementById("darkendiv").style.visibility = "hidden";
		document.getElementById("inputval").style.visibility = "hidden";
		document.getElementById("closebutton").style.visibility = "hidden";
		document.getElementById("messagediv").style.visibility = "hidden";

	}

} // of function uploadDialog(message, okFunction, closeFunction)


/** Delete file ******************************************************
 */
 
function deleteFile(filename) {
    
    confirmDialog("ACHTUNG!!!",
				basename(filename) + "</br>wirklich löschen?</br><b>Es gibt keinen Papierkorb!</b>",
                function() {
                    $.ajax({
                            url: "cgi-bin/deleteFile.php",
                            data: { filename: filename },
                            dataType: "text", // must be sent for browser to get response correctly!
                            success: function(){
								location.reload(true);
                            }
                    });
                },
                function(){}); // declared to see cancel button

} // of function deleteFile(filename)


/** Create folder ****************************************************
 */
 
function createFolder() {

	
	inputDialog("Neuer Ordner:",
                function() {
                    var folder = globalAktMediaPath +"/" + $("input#inputval").val();
                    $.ajax({
                            url: "cgi-bin/createFolder.php",
                            data: { pathname: folder },
                            dataType: "text", // must be sent for browser to get response correctly!
                            success: function(){
                                location.reload(true);
                            }
                    });
                },
                function(){}
	); // declared to see cancel button

} // of function createFolder(path)

/** Show info dialog ****************************************************
 */
 
function infoDialog() {

	$.ajax({
        	url: "cgi-bin/info.php",
                dataType: "text", // NOT!!! text/html to get response correctly!!!!
                success: function(data){
			//console.log(data);
			$("#ModalClose").hide();
			$("#ModalTitle").text("Über Dateimanager");
			$("#inputval").addClass("hidden");
			$("#ModalContent").html(data);
			$("#ModalMessage").modal();
                }
        });

} // of function infoDialog(path)

/** Show PHP info dialog ***********************************************
 */
 
function phpInfo() {

    $.ajax({
		url: "cgi-bin/phpInfo.php",
		//data: { pathname: folder },
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

	var checkedFiles = "";

    	confirmDialog("ACHTUNG!!!",
			"Datei(en) wirklich löschen?</br><b>Es gibt keinen Papierkorb!</b>",
	                function() {
 				$('input[name="fileaction"]:checked').each(function() {
					var filename = this.value;
		   			console.log(filename);
					checkedFiles += filename + "\n";
					$.ajax({
		                		url: "cgi-bin/deleteFile.php",
                		        	data: { filename: filename },
		                        	dataType: "text", // must be sent for browser to get response correctly!
                		        	success: function(){
						//location.reload(true);
                        			}
                			});

               			});
				console.log("Gelöscht: "+checkedFiles);
			},
                	function(){} // declared to see cancel button
	);
	//console.log("Gelöscht: "+checkedFiles);
}

/** Zip and download marked files to local machine **************************
 */

function downloadFiles() {

	var filesData = [];

	$('input[name="fileaction"]:checked').each(function() {
		var filename = this.value;
		filesData.push(filename);
	});

	console.log("In Javascript:");
	console.log(filesData);
	
	var zipFileName =  basename(filesData[0]);
	confirmDialog("Bitte warten...","Erstelle Zip-Datei für Download...");
        $.ajax({
		type: "POST",
      		url: "cgi-bin/zipFiles.php",  // first zip files on server
       		data: { postData : filesData, filename: zipFileName },
		dataType: "text",  // must be sent for browser to get response correctly!
		processData: true, // must be true to send JSON array!
		success: function(response) {
			// after zipping hide the message div...
			document.getElementById("messagediv").style.visibility = "hidden";
			document.getElementById("darkendiv").style.visibility = "hidden";
			// and start download script (which deletes zip file from server afterwards)
			window.location.href = "/cgi-bin/downloadAndDelete.php?filename=/zipfiles/" + response;
		},
		error: function(response) {
			alert("zipFiles: Puh, Why this?\n"+response);
		}
       });
}

function testAjaxPhp(parameter) {

	$.ajax({
		type: "POST",
       		url: "cgi-bin/testAjaxPhp.php",  // first zip files on server
        	data: { parameter: parameter },
		dataType: "text",  // must be sent for browser to get response correctly!
		processData: true, // must be true to send JSON array!
		success: function(response) {
			alert("SUCCESS: testAjaxPhp.php!\n"+response);
		},
		error: function(response) {
			alert("ERROR: testAjaxPhp > Puh, Why this?\n"+response);
		}
        });

}

