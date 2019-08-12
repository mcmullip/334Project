<?php
echo <<<_END
<html> <head> <link href="form.css" type="text/css" rel="stylesheet" /> </head> <body>
<form action="authenticate2.php" method="post">
  <label for="userid">ID</label>
  <input type="text" name="userid" id="userid" />  <br />
  <label for="password">Password</label>
  <input type="password" name="password" id="password" />  <br />
  <input type="submit" name="submit" value="Submit" />
</form><body>
_END;
?>