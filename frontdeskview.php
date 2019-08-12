<?php // frontdeskview.php
session_start();

  require_once 'login.php';
  $conn = new mysqli($hn, $un, $pw, $db);
  if ($conn->connect_error) die($conn->connect_error);


//echo "<p>Hello " . $_SESSION["fname"] . " " . $_SESSION["lname"] . "</p>";
//echo "<br>";

if (isset($_POST['checkguestbutton']))
{

$guestinfo = mysqli_real_escape_string($conn, $_POST["guestinfo"]);

//echo $guestinfo;
$query = "SELECT reservations.*, guests.name FROM (reservations LEFT JOIN guests ON reservations.guest_id = guests.guest_id) WHERE reservations.guest_id = '$guestinfo' OR guests.name = '$guestinfo'";
$result = $conn->query($query);
if (!$result) die ("Database access failed: " . $conn->error);

$rows = $result->num_rows;
  
  echo "<p>Guest '$guestinfo' has '$rows' reservations</p>";

    echo <<<_END
<form action="frontdeskcontainer.php" method="post" id="alterreservations">

  <table> 
  <thead>
    <th align="left">Guest Name</th>
    <th align="left">Room #</th>   
    <th align="left">From</th>  
    <th align="left">To</th>
    <td><label for="checkall"> <input type="checkbox" id="checkall" onclick="check_all()"></label>  </td>
  </thead>
  <tbody>


_END;
  for ($j = 0 ; $j < $rows ; ++$j)
  {
    $result->data_seek($j);
    $row = $result->fetch_array(MYSQLI_NUM);

    echo <<<_END
    <tr>
    <td>$row[5]</td>
    <td>$row[2]</td>   
      <td>$row[3]</td>   
      <td>$row[4]</td>   
      <td><input type="checkbox" name='cancel[]' value ='$row[0]'> </td>
    </tr>
_END;
  }

  echo <<<_END
  </tbody>
  </table>
<br><input type="submit" name="cancelbutton" value="Check Out">
<br>
Extend Stay Until: <input type="text" name="new_enddate">
<input type="submit" name="extendbutton" value="Extend">
<br>
Move To Room: <input type="text" name="new_room">
<input type="submit" name="changeroomsbutton" value="Move Guest">
</form>
_END;
}

if (isset($_POST['cancelbutton']) && isset($_POST['cancel']))
{
  $_POST['cancel']; //get array of checked values
  foreach($_POST['cancel'] as $key=>$tocancel) 
  { 
    $query  = "DELETE FROM reservations WHERE reservation_id=$tocancel";
    $result = $conn->query($query);
    if (!$result) echo "DELETE failed: $query<br>" .
    $conn->error . "<br><br>";
  }
}

if (isset($_POST['extendbutton']) && isset($_POST['cancel']))
{
$enddate = $_POST['new_enddate'];
  $_POST['cancel']; //get array of checked values
  foreach($_POST['cancel'] as $key=>$tocancel) 
  { 
    $query  = "UPDATE reservations SET end_date = '$enddate' WHERE reservation_id=$tocancel";
    $result = $conn->query($query);
    if (!$result) echo "UPDATE failed: $query<br>" .
    $conn->error . "<br><br>";
  }

}

if (isset($_POST['changeroomsbutton']) && isset($_POST['cancel']))
{
$newroom = $_POST['new_room'];
  $_POST['cancel']; //get array of checked values
  foreach($_POST['cancel'] as $key=>$tocancel) 
  { 
    $query  = "UPDATE reservations SET room_id = '$newroom' WHERE reservation_id=$tocancel";
    $result = $conn->query($query);
    if (!$result) echo "UPDATE failed: $query<br>" .
    $conn->error . "<br><br>";
  }

}


echo <<<_END

<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="../css/gueststyle.css">
</head>
<form action = "frontdeskcontainer.php" method="post" id="getguest">
<br>
Enter guest name or guest_id: 
<input type="text" name="guestinfo" id="guestinfo">
<input type="submit" name="checkguestbutton" value="Submit">
</form>
_END;




?>