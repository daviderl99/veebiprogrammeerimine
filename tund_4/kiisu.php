<?php
	require("functions.php");
	
	$notice = null;
	
	if (isset($_POST["submitMessage"])){
	if ($_POST["catname"] !="Kassi nimi" and !empty($_POST["catcolor"] !="Kassi värv") and !empty($_POST["cattail"] !="Kassi sabapikkus")){
		  $message = test_input($_POST["catname"]);
		$notice = saveamsg($message);
	  } else {
		$notice = "Palun täida kõik väljad!";
	  }
	}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Kiisude lisamine</title>
</head>
<body>
  <h1>Kiisu lisamine andmebaasi</h1>
  <p>See leht on loodud <a href="http://www.tlu.ee" target="_blank">TLÜ</a> õppetöö raames, ei pruugi parim välja näha ning kindlasti ei sisalda tõsiselt võetavat sisu!</p>
  <hr>
  
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <label>Sõnum (max 256 märki):</label>
	<br>
	<textarea rows="4" cols="25" name="catname">Kassi nimi</textarea>
	<textarea rows="4" cols="25" name="catcolor">Kassi värv</textarea>
	<textarea rows="4" cols="25" name="cattail">Kassi sabapikkus</textarea>
    <br>
    <input type="submit" name="submitMessage" value="Saada andmed">
  </form>
  <hr>
  <p><?php echo $notice; ?></p>
 
</body>
</html>