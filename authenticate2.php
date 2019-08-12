<?php // authenticate2.php
  require_once 'login.php';
  $connection = new mysqli($hn, $un, $pw, $db);

  if ($connection->connect_error) die($connection->connect_error);

  $rec_un = $_REQUEST['userid'];
  $rec_pw = $_REQUEST['password'];

  if (isset($_REQUEST['userid']) &&
      isset($_REQUEST['password']))
    {  
    $un_temp = mysql_entities_fix_string($connection, $rec_un);
    $pw_temp = mysql_entities_fix_string($connection, $rec_pw);
    $query = "SELECT * FROM users WHERE username ='$un_temp'";
     $result = $connection->query($query);
     if (!$result) die($ection->error);
    elseif ($result->num_rows)
	{
		$row = $result->fetch_array(MYSQLI_NUM); 
		$result->close();
		$salt1 = "qm&h*";
		$salt2 = "pg!@";
        $token = hash('ripemd128', "$salt1$pw_temp$salt2");
	/*	  echo 'token is ' .$token;
                  echo '<br>'. 'row[0] (firstname) is ' .$row[0];
                  echo '<br>'. 'row[1] (lastname) is ' .$row[1];
                  echo '<br>'. 'row[2] (user rank) is ' .$row[2];
                  echo '<br>'. 'row[3] (user id) is ' .$row[3];
                  echo '<br>'. 'row[4] (username) is ' .$row[4];
                  echo '<br>'. 'row[5] (password) is ' .$row[5];*/

                  if ($token == $row[5]) 
		{
			session_start();
			$_SESSION['username'] = $un_temp;
			$_SESSION['password'] = $pw_temp;
			$_SESSION['fname'] = $row[0];
			$_SESSION['lname']  = $row[1];
                        $_SESSION['rank'] = $row[2];
			echo "$row[0] $row[1] : Hi $row[0],
				you are now logged in as '$row[5]'";
                                if ($_SESSION['rank'] == 1)
				    header('Location:guestcontainer.php');
                                else if ($_SESSION['rank'] == 2)
                                    header('Location:frontdeskcontainer.php');
		}
		else die("Invalid username/password combination");
	}
		else die("Invalid username/password combination");
	}
	  else
      {
     	header('Location:form.php');    
  
       $connection->close();
      }
function mysql_entities_fix_string($connection, $string)
  {
    return htmlentities(mysql_fix_string($connection, $string));
  }	

  function mysql_fix_string($connection, $string)
  {
    if (get_magic_quotes_gpc()) $string = stripslashes($string);
    return $connection->real_escape_string($string);
  }
?> 