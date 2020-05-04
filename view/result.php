<?php
  require_once "lib/lib.php";

  function displayResults() {
    $dbconn = db_connect();
    if(!$dbconn) return;
    $query = "SELECT * FROM restaurant ORDER BY rating DESC;";
    $result = pg_prepare($dbconn, "", $query);
    $result = pg_execute($dbconn, "", array());
    
    echo("<table style='width:100%'>");
    echo("<tr>\n<th>Restaurant</th>\n<th>Rating</th>\n<th>Wins</th>\n<th>Ties</th>\n<th>Loss</th></tr>\n");
    
    while ($row = pg_fetch_array($result)) {
      echo("<tr>\n<td>$row[0]</td>\n<td align='center'>$row[1]</td>\n<td align='center'>$row[2]</td>\n<td align='center'>$row[3]</td>\n<td align='center'>$row[4]</td></tr>\n");
    }
    echo("</tr>\n</table>");
  }
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
      <h1>Results</h1>
      <?php displayResults() ?>
		</main>
		<footer>
		</footer>
	</body>
</html>


