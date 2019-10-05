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
				<table>
				<th style="text-align:center" colspan="2"><h3>Login</h3></th>
				<tr>
					<td style="text-align:right"><b>Username</b></td>
					<td><input type="text" placeholder="Enter Username" name="username" required></td>
				</tr>
				<tr>
					<td style="text-align:right"><b>Password</b></td>
					<td><input type="password" placeholder="Enter Password" name="password" required></td>
				</tr>
				<tr><td></td><td><button type="submit">Login</button></td></tr>
				</table>
				</form>
			</div>
		</div>
	</div>
</body>
</html>

