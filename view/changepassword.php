<?php
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="style.css" />
		<title>RestaurantMash</title>
	</head>
	<body>
		<header><h1>RestaurantMash</h1></header>
		<nav>
			<ul>
        <li> <a href="?operation=compete">Compete</a>
        <li> <a href="?operation=results">Results</a>
        <li> <a href="?operation=profile">Profile</a>
        <li> <a href="?operation=logout">Logout</a>
    </ul>
		</nav>
		<main>
      <h1>Change Password</h1> 
			
      <form action="index.php" method="post">
				<fieldset>
				<legend>Change Password</legend>
				<table>
					<tr><th><label for="password">Old Password</label></th><td> <input type="password" name="oldpassword" /></td></tr>
					<tr><th><label for="password">New Password</label></th><td> <input type="password" name="newpassword" /></td></tr>                
				  <tr><th><label for="password">New Password Confirm</label></th><td> <input type="password" name="newpassword2" /></td></tr>
          <tr><th>&nbsp;</th><td><input type="submit" name="submit" value="Change Password" />
          <tr><th>&nbsp;</th><td><?php echo(view_errors($errors)); ?></td></tr>      
		</main>
		<footer>
		</footer>
	</body>
</html>


