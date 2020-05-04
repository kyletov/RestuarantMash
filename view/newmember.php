<?php
// So I don't have to deal with unset $_REQUEST['user'] when refilling the form
$_REQUEST['user']=!empty($_REQUEST['user']) ? $_REQUEST['user'] : '';
$_REQUEST['password']=!empty($_REQUEST['password']) ? $_REQUEST['password'] : '';
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

		<main>
			<h1>New Member Sign Up</h1>
			<form action="index.php" method="post">
				<fieldset>
				<legend>SIGN UP</legend>
				<table>
					<!-- Trick below to re-fill the user form field -->
					<tr><th><label for="user">User</label></th><td><input type="text" name="user" value="<?php echo($_REQUEST['user']); ?>" /></td></tr>
                         
          <tr><th><label for="gender">Gender</label></th><td><input type="radio" name="gender" value="male"> Male
                                                             <input type="radio" name="gender" value="female"> Female
                                                             <input type="radio" name="gender" value="other"> Other <br> </td></tr>
          
          <tr><th><label for="birthday">Birthday</label></th><td><input id="date" name="date" type="date"> <br> </td></tr>              
					<tr><th><label for="password">Password</label></th><td> <input type="password" name="password" /></td></tr>
				  <tr><th><label for="password">Password Confirm</label></th><td> <input type="password" name="password2" /></td></tr>
					<tr><th>&nbsp;</th><td><input type="submit" name="submit" value="Sign Up" /> <input type="submit" name="submit" value="Cancel" /></td></tr>
					<tr><th>&nbsp;</th><td><?php echo(view_errors($errors)); ?></td></tr>
				</table>
			</form>
		</main>
		<footer>
		</footer>
	</body>
</html>
