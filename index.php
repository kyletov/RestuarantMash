<?php
	ini_set('display_errors', 'On');
	require_once "lib/lib.php";
	require_once "model/Compete.php";
	$restaurantsAll = "dev/restaurants.txt";
 
	session_save_path("sess");
	session_start(); 

	$dbconn = db_connect();

	$errors = array();
	$view = "";
  
  $_SESSION['restaurants'] = file($restaurantsAll, FILE_IGNORE_NEW_LINES);
  
  function generateCompetitor($dbconn, $opponent1){
    $query = "SELECT rating FROM restaurant WHERE name=$1;";
    $result = pg_prepare($dbconn, "", $query);
    $result = pg_execute($dbconn, "", array($opponent1));
    if ($row = pg_fetch_array($result, NULL, PGSQL_ASSOC)){
      $base_rating = $row['rating'];
    }
    
    $query = "SELECT name FROM restaurant WHERE rating>=$1 AND rating<=$2 AND name!=$3;";
    $result = pg_prepare($dbconn, "", $query);
    $result = pg_execute($dbconn, "", array($base_rating-25, $base_rating+25, $opponent1));
    $table = pg_fetch_all($result);
    
    return $table[rand(0, count($table) - 1)]['name'];
  }
 
	/* controller code */
	if (!isset($_SESSION['state'])){
		$_SESSION['state'] = 'login';
	}

	switch($_SESSION['state']){
		case "unavailable":
			$view = "unavailable.php";
			break;
  
		case "login":
			// the view we display by default
			$view = "login.php";
      
      // Check to see if the state is new member, if so change view and state
      if (isset($_REQUEST['operation'])){
        if ($_REQUEST['operation'] == "newmember"){
				  $_SESSION['state'] = 'newmember';
          $view = "newmember.php";
          break;
        }
      }
      
			// check if submit or not
			if (empty($_REQUEST['submit']) || $_REQUEST['submit'] != "login"){
				break;
			}

			// validate and set errors
			if (empty($_REQUEST['user'])){
				$errors[] = 'user is required';
			}
			if (empty($_REQUEST['password'])){
				$errors[] = 'password is required';
			}
			if (!empty($errors))break;
           

			// perform operation, switching state and view if necessary
			if (!$dbconn) return;
			$query = "SELECT * FROM appuser WHERE id=$1 and password=$2;";
      $result = pg_prepare($dbconn, "", $query);

      $result = pg_execute($dbconn, "", array($_REQUEST['user'], $_REQUEST['password']));
      if ($row = pg_fetch_array($result, NULL, PGSQL_ASSOC)){
				$_SESSION['state'] = 'compete';
				$view = "compete.php";
        $_SESSION['user'] = $row['id'];// ADDED SESSION VARIABLEE
        $_SESSION['gender'] = $row['gender'];
        $_SESSION['birthday'] = $row['birthday'];
        $_SESSION['timesvoted'] = $row['timesvoted'];
        $_SESSION['bio'] = $row['bio'];
        $_SESSION['res1'] = $_SESSION['restaurants'][rand(0, count($_SESSION['restaurants']) - 1)];
        $_SESSION['res2'] = generateCompetitor($dbconn, $_SESSION['res1']);
			} else {
				$errors[] = "invalid login";
			}
			break;
    
    case "newmember":
      // the view we display by default
			$view="newmember.php";

			// check if submit or not
			if (empty($_REQUEST['submit']) || $_REQUEST['submit'] != "Sign Up"){ // DO WE NEED TO CHANGE THIS??
				if ($_REQUEST['submit'] == "Cancel") {
          $_SESSION['state'] = 'login';
		      $view = "login.php";
        }
        break;
			}

			// validate and set errors
			if (empty($_REQUEST['user'])){
				$errors[] = 'user is required';
			}
			if (empty($_REQUEST['password'])){
				$errors[] = 'password is required';
			}
      
      // error for if password confirmation is wrong
      if ($_REQUEST['password'] != $_REQUEST['password2']){
        $errors[] = 'password confirmation is wrong';
      }
      
			if (!empty($errors))break;
      
			// perform operation, switching state and view if necessary
			if (!$dbconn) return;
      // check if username exists before attempting to insert values into the appuser table
      $query = "SELECT * FROM appuser WHERE id=$1;";
      $result = pg_prepare($dbconn, "", $query);
      $result = pg_execute($dbconn, "", array($_REQUEST['user']));
      if ($row = pg_fetch_array($result, NULL, PGSQL_ASSOC)){
				$errors[] = "user already exists";
			} else {
				$query2 = "INSERT INTO appuser VALUES($1, $2, $3, $4);";
  	    $result2 = pg_prepare($dbconn, "", $query2);
        $result2 = pg_execute($dbconn, "", array($_REQUEST['user'], $_REQUEST['password'], $_REQUEST['gender'], $_REQUEST['date']));
        
        // NEED TO ADD A PROPER ERROR CHECK FOR WHEN IT DOESN'T WORK
        $query3 = "SELECT * FROM appuser WHERE id=$1 and password=$2 and gender=$3 and birthday=$4;";
        $result3 = pg_prepare($dbconn, "", $query3);
        $result3 = pg_execute($dbconn, "", array($_REQUEST['user'], $_REQUEST['password'], $_REQUEST['gender'], $_REQUEST['date']));
        if ($row = pg_fetch_array($result3, NULL, PGSQL_ASSOC)){
  				$_SESSION['state']='login';
  				$view = "login.php";
  			} else {
  				$errors[] = "Insert was not successful!";
  			}
			}
			break;
    
    case "profile":
      $view = "profile.php";
      if (isset($_REQUEST['operation'])){
        if ($_REQUEST['operation'] == "compete"){
				  $_SESSION['state'] = 'compete';
			    $view = "compete.php";
        } else if ($_REQUEST['operation'] == "editprofile"){
				  $_SESSION['state'] = 'editprofile';
          $view = "editprofile.php";
        } else if ($_REQUEST['operation'] == "changepassword"){
				  $_SESSION['state'] = 'changepassword';
          $view = "changepassword.php";
        } else if ($_REQUEST['operation'] == "results"){
				  $_SESSION['state'] = 'result';
          $view = "result.php";
        } else if ($_REQUEST['operation'] == "logout"){
				  session_destroy();
          $view = "login.php";
        }
      }
      break;
      
    case "changepassword":
      $view = "changepassword.php";
      
      if (isset($_REQUEST['operation'])){
        if ($_REQUEST['operation'] == "compete"){
				  $_SESSION['state'] = 'compete';
			    $view = "compete.php";
        } else if ($_REQUEST['operation'] == "results"){
				  $_SESSION['state'] = 'result';
          $view = "result.php";
        } else if ($_REQUEST['operation'] == "profile"){
				  $_SESSION['state'] = 'profile';
          $view = "profile.php";
        } else if ($_REQUEST['operation'] == "logout"){
				  session_destroy();
          $view = "login.php";
        }
      }      
      
       // check if submit or not
			if (empty($_REQUEST['submit']) || $_REQUEST['submit'] != "Change Password"){
				break;
			}
      
			// validate and set errors
			if (empty($_REQUEST['newpassword']) || empty($_REQUEST['newpassword2']) || empty($_REQUEST['oldpassword'])){
				$errors[] = 'missing field';
			}
      
      if ($_REQUEST['newpassword'] != $_REQUEST['newpassword2']){
        $errors[] = 'confirmation password wrong';
      }
      
      if (!empty($errors))break; 
      
			// perform operation, switching state and view if necessary
			if (!$dbconn) return;
      // Updating profile related values into appuser
      $query = "SELECT password FROM appuser WHERE id=$1;";
      $result = pg_prepare($dbconn, "", $query);
      $result = pg_execute($dbconn, "", array($_SESSION['user']));
      if ($row = pg_fetch_array($result, NULL, PGSQL_ASSOC)){
        if ($_REQUEST['oldpassword'] != $row['password']){
          $errors[] = 'incorrect old password';
        } else if ($_REQUEST['newpassword'] == $row['password']){
          $errors[] = 'new password cannot be the same as the previous password';
        }
      }
      if (!empty($errors))break;
      
      $query = "UPDATE appuser SET password=$1 WHERE id=$2;";
      $result = pg_prepare($dbconn, "", $query);
      $result = pg_execute($dbconn, "", array($_REQUEST['newpassword'], $_SESSION['user']));      
      
      $query = "SELECT password FROM appuser WHERE id=$1;";
      $result = pg_prepare($dbconn, "", $query);
      $result = pg_execute($dbconn, "", array($_SESSION['user']));
      if ($row = pg_fetch_array($result, NULL, PGSQL_ASSOC)){
        if ($_REQUEST['newpassword'] != $row['password']) {      
          $errors[] = 'password change was not successful';
        }
      }    
      
      if (!empty($errors))break;  
      $_SESSION['state'] = 'profile';
      $view = "profile.php";    
            
      break;
      
    case "editprofile":
      $view = "editprofile.php";
      if (isset($_REQUEST['operation'])){
        if ($_REQUEST['operation'] == "results"){
				  $_SESSION['state'] = 'result';
          $view = "result.php";
        }else if ($_REQUEST['operation'] == "compete"){
				  $_SESSION['state'] = 'compete';
			    $view = "compete.php";
        } else if ($_REQUEST['operation'] == "profile"){
				  $_SESSION['state'] = 'profile';
          $view = "profile.php";
        } else if ($_REQUEST['operation'] == "results"){
				  $_SESSION['state'] = 'result';
          $view = "result.php";
        } else if ($_REQUEST['operation'] == "logout"){
				  session_destroy();
          $view = "login.php";
        }
      }
      
      // check if submit or not
			if (empty($_REQUEST['submit']) || $_REQUEST['submit'] != "Save Profile"){
				break;
			}

			// validate and set errors
			if (empty($_REQUEST['gender'])){
				$errors[] = 'invalid gender';
			}
			if (empty($_REQUEST['date'])){
				$errors[] = 'date not selected';
			}
			if (empty($_REQUEST['bio'])){
				$errors[] = 'bio empty';
			}
			if (!empty($errors))break;
           
      
			// perform operation, switching state and view if necessary
			if (!$dbconn) return;
      // Updating profile related values into appuser
      $query = "UPDATE appuser SET gender=$1, birthday=$2, bio=$3 WHERE id=$4;";
      $result = pg_prepare($dbconn, "", $query);
      $result = pg_execute($dbconn, "", array($_REQUEST['gender'], $_REQUEST['date'], $_REQUEST['bio'], $_SESSION['user']));
      
      $query = "SELECT * FROM appuser WHERE id=$1;";
      $result = pg_prepare($dbconn, "", $query);
      $result = pg_execute($dbconn, "", array($_SESSION['user']));
      if ($row = pg_fetch_array($result, NULL, PGSQL_ASSOC)){
        if ($row['gender'] == $_REQUEST['gender'] && $row['birthday'] == $_REQUEST['date'] && $row['bio'] == $_REQUEST['bio']){
          $_SESSION['gender'] = $row['gender'];
          $_SESSION['birthday'] = $row['birthday'];
          $_SESSION['bio'] = $row['bio'];
        }
      }
      
      $_SESSION['state'] = 'profile';
      $view = "profile.php";      
      
      break;
      
    case "compete":
      $view = "compete.php";
      
      if (isset($_REQUEST['operation'])){
        if ($_REQUEST['operation'] == "results"){
				  $_SESSION['state'] = 'result';
          $view = "result.php";
        } else if ($_REQUEST['operation'] == "profile"){
				  $_SESSION['state'] = 'profile';
          $view = "profile.php";
        } else if ($_REQUEST['operation'] == "logout"){
				  session_destroy();
          $view = "login.php";
        } 
        break;
      }
            
      // COMPETE
      if (isset($_REQUEST['res'])){
        if ($_REQUEST['res'] == $_SESSION['res1']){
          $competing = new Compete($dbconn, $_SESSION['user'], $_SESSION['res1'], $_SESSION['res2'], false);
        } else if ($_REQUEST['res'] == $_SESSION['res2']){
          $competing = new Compete($dbconn, $_SESSION['user'], $_SESSION['res2'], $_SESSION['res1'], false);
        } else if ($_REQUEST['res'] == "I don't know"){
          $competing = new Compete($dbconn, $_SESSION['user'], $_SESSION['res1'], $_SESSION['res2'], true);
        }
        $query = "UPDATE appuser SET timesvoted=$1 WHERE id=$2;";
        $result = pg_prepare($dbconn, "", $query);
        $result = pg_execute($dbconn, "", array($_SESSION['timesvoted'] + 1, $_SESSION['user']));
        
        $query = "SELECT * FROM appuser WHERE id=$1 and timesvoted=$2;";
        $result = pg_prepare($dbconn, "", $query);
        $result = pg_execute($dbconn, "", array($_SESSION['user'], $_SESSION['timesvoted'] + 1));
        if ($row = pg_fetch_array($result, NULL, PGSQL_ASSOC)){
          $_SESSION['timesvoted'] = $row['timesvoted'];
        }
        
      }
      
      // RESETING SESSION VARIABLE
      $_SESSION['res1'] = $_SESSION['restaurants'][rand(0, count($_SESSION['restaurants']) - 1)];
      $_SESSION['res2'] = generateCompetitor($dbconn, $_SESSION['res1']);
      
      break;
    
    case "result":
      $view = "result.php";
      if (isset($_REQUEST['operation'])){
        if ($_REQUEST['operation'] == "compete"){
				  $_SESSION['state'] = 'compete';
			    $view = "compete.php";
        } else if ($_REQUEST['operation'] == "profile"){
				  $_SESSION['state'] = 'profile';
          $view = "profile.php";
        } else if ($_REQUEST['operation'] == "logout"){
          session_destroy();
          $view = "login.php";
        } 
      }
      break;
    
	}
	require_once "view/$view";
?>
