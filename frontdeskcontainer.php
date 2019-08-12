<!DOCTYPE html>
<html lang="en">
<head>
  <title>Seaside Hotel - Front Desk View</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="../css/bootstrap.min.css">
  <script src="../js/jquery.min.js"></script>
  <script src="../js/bootstrap.min.js"></script>
</head>
<body>
<img class="logo" src="../pictures/logo.png" alt="Seaside Hotels logo">
<div class="jumbotron text-center">
  <h1>Seaside Hotel</h1>
  <p>
<?php
session_start();
echo "logged in as " . $_SESSION['username'];
echo <<<_END
<form action="form.php" method="post">
<input type="submit" value="Logout">
</form>
_END;
?>
</p> 
</div>
<div class="container">
  <div class="row">
<?php 
  require_once 'frontdeskview.php';
?>  
</div>
</body> </html>
