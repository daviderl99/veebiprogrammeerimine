<?php
  require("functions.php");
  
    if(!isset($_SESSION["userId"])){
	header("Location: index_1.php");
	exit(); 
  }
  
  //logime välja
  if(isset($_GET["logout"])){
	session_destroy();
	header("Location: index_1.php");
	exit();
  }
  
  $messages = readallvalidatedmessagesbyuser();
  
  $pageTitle = "Anonüümsed sõnumid";
  require("header.php");
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Anonüümsed sõnumid</title>
</head>
<body>
  <p>Siin on minu <a href="http://www.tlu.ee">TLÜ</a> õppetöö raames valminud veebilehed. Need ei oma mingit sügavat sisu ja nende kopeerimine ei oma mõtet.</p>
  <hr>
  <ul>

	<li><a href="main.php">Tagasi</a> pealehele!</li>
	<li><a href="?logout=1">Logi välja!</a></li>
  </ul>
  <hr>
  <h2>Valideerimata sõnumid</h2>
  <?php
	
	echo $messages;
	?>



</body>
</html>