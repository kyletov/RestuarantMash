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
			<ul id="nav">
        <li> <a href="?operation=compete">Compete</a>
        <li> <a href="?operation=results">Results</a>
        <li> <a href="?operation=profile">Profile</a>
        <li> <a href="?operation=logout">Logout</a>
    </ul>
		</nav>
		<main>
      <h1>Compete</h1>
      <h2>Which restaurant would you rather go to?</h2> 

      <form action="index.php" method="post">
        <div> 
          
          <button class="choice" type="submit" name="res" value="<?php echo($_SESSION['res1'])?>"> <?php echo($_SESSION['res1'])?> </button>
          or 
          <button class="choice" type="submit" name="res" value="<?php echo($_SESSION['res2'])?>"> <?php echo($_SESSION['res2'])?> </button>
          or 
          <button class="choice" type="submit" name="res" value="I don't know">I don't know</button>
 
        </div>
      </form>
		</main>
		<footer>
		</footer>
	</body>
</html>


