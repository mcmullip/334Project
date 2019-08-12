<?php
echo <<<_END
<html> <title>Seaside Hotel - Login</title><head> <link href="form.css" type="text/css" rel="stylesheet" /> </head> <body>
<img class="logo" src="../pictures/logo.png" alt="Seaside Hotel logo">
<form action="authenticate2.php" method="post">
  <label for="userid">ID</label>
  <input type="text" name="userid" id="userid" />  <br />
  <label for="password">Password</label>
  <input type="password" name="password" id="password" />  <br />
  <input type="submit" name="submit" value="Submit" />
</form><body>
_END;
?>
