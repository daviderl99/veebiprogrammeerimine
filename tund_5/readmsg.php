<?php
	//kutsume välja funktsioonide faili
	require("functions.php");
	
	$notice = listAllMessages();
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Anonüümsete sõnumite lugemine</title>
</head>
<body>
  <h1>Sõnumid</h1>
  <p>See leht on loodud <a href="http://www.tlu.ee" target="_blank">TLÜ</a> õppetöö raames, ei pruugi parim välja näha ning kindlasti ei sisalda tõsiselt võetavat sisu!</p>
  <hr>
  <?php
	echo $notice;
  ?>

</body>
</html>



