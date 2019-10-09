<!DOCTYPE html>
<html lang="en">

<head>
	<title>Login</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/custom.css">
	<!-- Insert the icons -->
	<link rel="apple-touch-icon" sizes="76x76" href="/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
	<link rel="manifest" href="/site.webmanifest">
	<link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
	<meta name="msapplication-TileColor" content="#da532c">
	<meta name="theme-color" content="#ffffff">
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<form action="login.php" method="post">
					<h3><i class="material-icons">lock_open</i> Login</h3>
					<p><input type="text" placeholder="Enter Username" name="username" required></p>
					<p><input type="password" placeholder="Enter Password" name="password" required></p>
					<button type="submit"><i class="material-icons">touch_app</i> Enter</button>
				</form>
			</div>
		</div>
	</div>
</body>
</html>

