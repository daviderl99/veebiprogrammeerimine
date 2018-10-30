<?php
  require("functions.php");
  //kui pole sisse loginud
  if(!isset($_SESSION["userId"])){
	  header("Location: index_1.php");
	  exit();
  } else {
		$mybgcolor = $_SESSION["bgcolor"];
		$mytxtcolor = $_SESSION["txtcolor"];
		$mydescription = $_SESSION["description"];
	}
  
  //väljalogimine
  if(isset($_GET["logout"])){
	session_destroy();
	header("Location: index_1.php");
	exit();
  }
	if (!isset($_POST["bgcolor"])){
		$notice = "error";
	}
	else if (!isset($_POST["txtcolor"])){
		$notice = "error";
	}
	else if (!isset($_POST["description"])){
		$notice = "error";
	} else {
		$mybgcolor = $_POST["bgcolor"];
		$mytxtcolor = $_POST["txtcolor"];
		$mydescription = $_POST["description"];
		userprofiles($_SESSION["userId"], $mybgcolor, $mytxtcolor, $mydescription);
		echo $mybgcolor;
	}
	if(isset($_GET["bgcolor"])){
		echo $_SESSION["bgcolor"];
	}

  $mydescription = ("Minu tutvustus.");
  
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
	<title>
	  <?php
	    echo $_SESSION["firstName"];
		echo " ";
		echo $_SESSION["lastName"];
	  ?>
, õppetöö</title>

<style>
	body{background-color: #FFFFFF; 
	color: #000000} 
</style>

  </head>
  <body>
    <h1>
	  <?php
	    echo $_SESSION["firstName"] ." " .$_SESSION["lastName"];
	  ?>
	</h1>
	<p>See leht on valminud <a href="http://www.tlu.ee" target="_blank">TLÜ</a> õppetöö raames ja ei oma mingisugust, mõtestatud või muul moel väärtuslikku sisu.</p>
	<hr>
	<ul>
	  <li><a href="?logout=1">Logi välja</a>!</li>
	  <li><a href="main.php">Tagasi pealehele</a></li>
	</ul>
	<hr>
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	<textarea rows="10" cols="80" name="description"><?php echo $mydescription; ?></textarea>
	<hr>
	<label>Minu valitud taustavärv: </label><input name="bgcolor" type="color" value="<?php echo $mybgcolor; ?>"><br>
	<label>Minu valitud tekstivärv: </label><input name="txtcolor" type="color" value="<?php echo $mytxtcolor; ?>"><br>
	<input type="submit" name="submitProfile" value="Salvesta profiil">
	</form>
	<hr>
  </body>
</html>