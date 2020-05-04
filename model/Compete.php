<?php

require_once 'Rating.php';

class Compete {

	public function __construct($dbconn, $id, $win, $lose, $idk) {
     
      if(!$dbconn) return;
      $query = "SELECT rating, wins, ties FROM restaurant WHERE name=$1;";
      $result = pg_prepare($dbconn, "", $query);
      $result = pg_execute($dbconn, "", array($win));
      if ($row = pg_fetch_array($result)) {
        $win_rating = $row[0];
        $winCount = $row[1];
        $r1TieCount = $row[2];
      }
      
      $query = "SELECT rating, loss, ties FROM restaurant WHERE name=$1;";
      $result = pg_prepare($dbconn, "", $query);
      $result = pg_execute($dbconn, "", array($lose));
      if ($row = pg_fetch_array($result)) {
        $lose_rating = $row[0];
        $lossCount = $row[1];
        $r2TieCount = $row[2];
      }
      
      if ($idk == false) {
        $ratingSystem = new Rating\Rating($win_rating, $lose_rating, Rating\Rating::WIN, Rating\Rating::LOST);
        $ratings = $ratingSystem->getNewRatings();
        
        $query = "UPDATE restaurant SET rating=$1, wins=$2 WHERE name=$3;";
        $result = pg_prepare($dbconn, "", $query);
        $result = pg_execute($dbconn, "", array($ratings['a'], $winCount+1, $win));
        
        $query = "UPDATE restaurant SET rating=$1, loss=$2 WHERE name=$3;";
        $result = pg_prepare($dbconn, "", $query);
        $result = pg_execute($dbconn, "", array($ratings['b'], $lossCount+1, $lose));
      } else {
        $ratingSystem = new Rating\Rating($win_rating, $lose_rating, Rating\Rating::DRAW, Rating\Rating::DRAW);
        $ratings = $ratingSystem->getNewRatings();
        
        $query = "UPDATE restaurant SET rating=$1, ties=$2 WHERE name=$3;";
        $result = pg_prepare($dbconn, "", $query);
        $result = pg_execute($dbconn, "", array($ratings['a'], $r1TieCount+1, $win));
        
        $query = "UPDATE restaurant SET rating=$1, ties=$2 WHERE name=$3;";
        $result = pg_prepare($dbconn, "", $query);
        $result = pg_execute($dbconn, "", array($ratings['b'], $r2TieCount+1, $lose));
      }
  }
}
?>

