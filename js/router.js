/** router.js *****************************************************************
 *
 * Single page app router taken from an old Heise article modernized
 * to meet my suits. This is used to avoid expensive router frameworks.
 *
 */
 
// domEntryPoint = start of app (see id in HTM file)

const startingPoint = document.getElementById('app');

/** create new router object **************************************************
 */

const myrouter = function () {
  
	// object containing all possible routes

	var routes = {};

	function handleRouting() {
		defaultRoute = '*';
		currentHash = location.hash.slice(1); // cut hash from location

		if ( routes.hasOwnProperty(currentHash) ) {
			routeHandler = routes[currentHash];
		} else { 
			routeHandler = routes[defaultRoute];
		}
		// if route handler is found, call it!

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

	return {
		addRoute: function (hashUrl, routeHandler) {
			routes[hashUrl] = routeHandler;
			}
		,
		navigateToHashUrl: function (hashUrl) {
				location.hash = hashUrl;
			}
	};

}(); // create and do the self-execution!

/** add routes and event handlers **********************************************
 */

myrouter.addRoute('list', function () {
   
	path = window.location.search.substr(1);
	globalAktMediaPath = path;

	$.ajax({
				url: "cgi-bin/dirlist.php",
				data: { pathname: path },
				dataType: "text html", // must be sent for browser to get response correctly!
				success: function(data){
					 startingPoint.innerHTML = data;
				}
	 });

}); // of myrouter.addRoute('list'...

myrouter.addRoute('*', function () {
   
	$.ajax({
		url: "cgi-bin/dirlist.php",
		data: { pathname: '/' },
		dataType: "text html", // must be sent for browser to get response correctly!
		success: function(data){
			 startingPoint.innerHTML = data;
		}
	});

}); // of myrouter.addRoute

