<?php
	$user = $_SESSION['user'];
  $timesvoted = $_SESSION['timesvoted'];
  $gender = $_SESSION['gender'];
  $birthday = $_SESSION['birthday'];
  $bio = $_SESSION['bio'];
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="style.css" />
		<title>RestaurantMash</title>
    <style>
      table, th, td {
        border: 1px solid black;
        padding: 5px;

      }
    </style>
   
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
      <h1> Profile </h1>
      
      <table>
        <tr>
          <td> Username: </td>
          <td><?php echo($user) ?> </td>
        </tr>
        <tr>
          <td>Gender: </td>
          <td><?php echo($gender) ?> </td>
        </tr>
        <tr>
          <td>Birthday: </td>
          <td><?php echo($birthday) ?> </td>
        </tr>
        <tr>
          <td>Number of times voted: </td>
          <td><?php echo($timesvoted) ?> </td>
        </tr>
        <tr>
          <td>Bio: </td>
          <td><?php echo($bio) ?> </td>
        </tr>
      </table>
      </br>
      
      <nav>
			  <ol>
          <li><a href="?operation=editprofile">Edit Profile</a> <a href="?operation=changepassword">Change Password</a></li>
        </ol>
      </nav>
     
		</main>
		<footer>
		</footer>
	</body>
</html>


