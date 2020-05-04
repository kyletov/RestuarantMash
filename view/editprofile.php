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
      <h1>Edit Profile</h1>

      <form action="index.php" method="post">
        Gender:
        <input type="radio" name="gender" value="male"> Male
        <input type="radio" name="gender" value="female"> Female
        <input type="radio" name="gender" value="other"> Other <br> <br>
        Birthday:
        <input id="date" name="date" type="date"> <br><br>
        Bio:
        <textarea maxlength="150" name="bio" id="bio"> </textarea> <br> <br>
        <?php echo(view_errors($errors)); ?> <br> <br>
        <input type="submit" name="submit" value="Save Profile" />
      </form>  
      

		</main>
		<footer>
		</footer>
	</body>
</html>


