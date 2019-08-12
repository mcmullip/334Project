<?php // guestview.php
session_start();
/* 
TODO NEXT
Store all guest info in session variables
When you get the form, check through all, and if not null then overwrite
then have the update query always update every column

Do the same thing for updating rooms for management, but you don't need sessions (just pull room values out and update with form stuff if not null
*/


  require_once 'login.php';
  $conn = new mysqli($hn, $un, $pw, $db);
  if ($conn->connect_error) die($conn->connect_error);

$_SESSION["guest_uid"] = "1";
//echo "Hello " . $_SESSION["fname"] . " " . $_SESSION["lname"] ."\n";
$guest_uid = $_SESSION["guest_uid"];

echo <<<_END
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<div class="container">
  <div class="row">
    <div class="col-sm-4">
      <form action="guestcontainer.php" method="post" id="updateinfo">
      Name: <input type="text" name="guestname" id="guestname">
      <br>
      DOB: <input type="text" name="guestdob" id="guestdob">
      <br>
      Address: <input type="text" name="guestaddress" id="guestaddress">
      <br>
      Payment Info: <input type="text" name="guestpaymentinfo" id="guestpaymentinfo">
      <br>
      <input type="submit" name="updateinfobutton" value="Update your information">
      </form>

    </div>
    <div class="col-sm-4">
<form action="guestcontainer.php" method="post" id="viewrooms">
Room Type: 
<select name="roomtype">
<option value="x"></option>
<option value="Room">Room</option>
<option value="Suite">Suite</option>
</select>
<br>
Number of Beds: 
<select name="bednumber">
<option value="-1"></option>
<option value="1">1</option>
<option value="2">2</option>
<option value="3">3</option>
<option value="4">4</option>
</select>
<br>

Bed Size: 
<select name="bedsize">
<option value="x"></option>
<option value="King">King</option>
<option value="Queen">Queen</option>
<option value="Double">Double</option>
</select>
<br>
Smoking? 
<select name="smoking">
<option value="x"></option>
<option value="Yes">Yes</option>
<option value="No">No</option>
</select>
<br>
Accessible? 
<select name="accessible">
<option value="x"></option>
<option value="Yes">Yes</option>
<option value="No">No</option></select>
<br><br>
Price from (number, no currency sign)
<br>
<input type="text" name="minprice" id="minprice"> to <input type="text" name="maxprice" id="maxprice">
<br><br>
Free from (YYYY-MM-DD)
<br>
<input type="text" name="startdate" id="startdate" required> to <input type="text" name="enddate" id="enddate" required>
<br>
<input type="submit" name="viewroomsbutton" value="View Rooms">
</form>
    </div>
    <div class="col-sm-4">
<form action="guestcontainer.php" method="post" id="getreservations">
<input type="submit" name="showreservationsbutton" value="Show My Reservations">
</form>
    </div>
  </div>
</div>



_END;

if (isset($_POST['updateinfobutton']))
{

  $name = mysqli_real_escape_string($conn, $_POST['guestname']);
  $dob = mysqli_real_escape_string($conn, $_POST['guestdob']);
  $address = mysqli_real_escape_string($conn, $_POST['guestaddress']);
  $paymentinfo = mysqli_real_escape_string($conn, $_POST['guestpaymentinfo']);

$query = "UPDATE guests SET ";

if ($name != "")
{
  $query = $query . "name = '$name', ";
}

if ($dob != "")
{
  $query = $query . "dob = '$dob', ";
}

if ($address != "")
{
  $query = $query . "billing_address = '$address', ";
} 
 
if ($paymentinfo != "")
{
  $query = $query . "payment_info = '$paymentinfo', ";
}

$query = rtrim($query, ", ");

$query = $query . " WHERE guest_id = '$guest_uid'";



  $result = $conn->query($query);
  if (!$result) { die ("Database access failed: " . $conn->error);}
  else { echo "Information successfully updated.\n";}


}
if (isset($_POST['viewroomsbutton']))
{

  $roomtype = mysqli_real_escape_string($conn, $_POST['roomtype']);
  $bednumber = mysqli_real_escape_string($conn, $_POST['bednumber']);
  $bedsize = mysqli_real_escape_string($conn, $_POST['bedsize']);
  $smoking = mysqli_real_escape_string($conn, $_POST['smoking']);
  $accessible = mysqli_real_escape_string($conn, $_POST['accessible']);
  $minprice = mysqli_real_escape_string($conn, $_POST['minprice']);
  $maxprice = mysqli_real_escape_string($conn, $_POST['maxprice']);
  $startdate = mysqli_real_escape_string($conn, $_POST['startdate']);
  $enddate = mysqli_real_escape_string($conn, $_POST['enddate']);
  $_SESSION["startdate"] = $startdate;
  $_SESSION["enddate"] = $enddate;
//  echo "stored session start date is ". $_SESSION["startdate"]."\n";
//  echo "stored session end date is ". $_SESSION["enddate"]."\n";
  $query = "SELECT * FROM rooms WHERE ";

  if ($roomtype != "x")
  {
    $query = $query . "rooms.room_type = '$roomtype' AND ";
  }

  if ($bednumber != "-1")
  {
    $query = $query . "rooms.number_of_beds = $bednumber AND ";
  }

  if ($bedsize != "x")
  {
    $query = $query . "rooms.bed_size = '$bedsize' AND ";
  }

  if ($smoking != "x")
  {

    if ($smoking = "Yes")
    {
      $query = $query . "rooms.smoking=1 AND ";
    }
    if ($smoking = "No")
    {
      $query = $query . "rooms.smoking=0 AND ";
    }
  }

  if ($accessible != "x")
  {

    if ($accessible = "Yes")
    {
      $query = $query . "rooms.accessibility=1 AND ";
    }
    if ($accessible = "No")
    {
      $query = $query . "rooms.accessibility=0 AND ";
    }
  }

  if ($minprice != "")
  {
    $query = $query . "rooms.price >= $minprice AND ";
  }

  if ($maxprice != "")
  {
    $query = $query . "rooms.price <= $maxprice AND ";
  }

  $query = $query . "room_id NOT IN
(SELECT room_id from `reservations` where start_date <= '$startdate' AND end_date >= '$enddate'
UNION
SELECT room_id from `reservations` where start_date <'$enddate' AND end_date >= '$enddate'
UNION
SELECT room_id from `reservations` where end_date > '$startdate' AND start_date <= '$startdate')";
    $result = $conn->query($query);
  if (!$result) die ("Database access failed: " . $conn->error);

  $rows = $result->num_rows;
//echo $query;
    echo <<<_END
<form action="guestcontainer.php" method="post" id="reserveroom">

  <table> 
  <thead>
    <th align="left">Room No. </th>   
    <th align="left">Room type </th>  
    <th align="left">Bed #</th>
    <th align="left">Bed Size </th>   
    <th align="left">Smoking </th>   
    <th align="left">Accessible </th>
    <th align="left">Price</th> 
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
    <td>$row[0]</td>   
      <td>$row[1]</td>   
      <td>$row[2]</td>   
      <td>$row[3]</td>   
      <td>$row[4]</td>  
      <td>$row[5]</td>
      <td>$row[6]</td>  
      <td><input type="checkbox" name='colors[]' value ='$row[0]'> </td>
    </tr>
_END;
  }

  echo <<<_END
  </tbody>
  </table>
<br><input type="submit" name="reserveroomsbutton" value="Reserve room for provided dates">
</form>
_END;

}
if (isset($_POST['reserveroomsbutton']) && isset($_POST['colors']))
  {
    $_POST['colors']; //get array of checked values
    foreach($_POST['colors'] as $desiredroom) { 
/*   echo "Reserving $desiredroom for guest " . $_SESSION["guest_uid"] . " from " . $_SESSION["startdate"] . " to ". $_SESSION["enddate"];
   $query  = "INSERT INTO reservations (room_id, guest_id, start_date, end_date) VALUES ($desiredroom, " .$_SESSION["guest_uid"].", ".$_SESSION["startdate"].", ".$_SESSION["enddate"].")";
      echo $query . "\n";
      $result = $conn->query($query);
      if (!$result) echo "INSERT failed: $query<br>" .
        $conn->error . "<br><br>";
*/

      $stmt = $conn->prepare("INSERT INTO reservations(room_id, guest_id, start_date, end_date) VALUES(?, ?, ?, ?)");
      $roomid = $desiredroom;
      $guestid = $_SESSION["guest_uid"];
      $startdate = $_SESSION["startdate"];
      $enddate = $_SESSION["enddate"];

      $stmt->bind_param("ddss", $roomid, $guestid, $startdate, $enddate);
      $stmt->execute();

      if(!$stmt->error)
      {
        echo "Inserted record successfully<br><br>";
      }
      else
      {
        echo "INSERT failed".$stmt->error;
      }
 
     }
  }

if (isset($_POST['showreservationsbutton']))
{
$query = "SELECT * from reservations WHERE guest_id = " . $_SESSION["guest_uid"];
    $result = $conn->query($query);
  if (!$result) die ("Database access failed: " . $conn->error);

  $rows = $result->num_rows;
    echo <<<_END
<form action="guestcontainer.php" method="post" id="cancelreservations">

  <table> 
  <thead>
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
<br><input type="submit" name="cancelbutton" value="Cancel these reservations">
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


?>

