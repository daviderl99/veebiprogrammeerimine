<?php
	//kutsume välja funktsioonide faili
	require("functions.php");
	
	$firstName = "Kodanik";
	$lastName = "Tundmatu";
	$birthMonth = date("m");
	$birthDay = date("d");
	$birthYear = date("Y") - 15;
	$fullName = "";
	$monthNamesET = ["jaanuar", "veebruar", "märts", "aprill", "mai", "juuni", "juuli", "august", "september", "oktoober", "november", "detsember"];
	
	
	//kontrollime, kas kasutaja on midagi kirjutanud
	//var_dump($_POST);
	if (isset($_POST["firstName"])){
		//$firstName = $_POST["firstName"];
		$firstName = test_input($_POST["firstName"]);
	}
	if (isset($_POST["lastName"])){
		$lastName = test_input($_POST["lastName"]);
	}
	
	//täiesti mõtetu, harjutamiseks mõeldud funktsioon
	function fullname(){
		$GLOBALS["fullName"] = $GLOBALS["firstName"] ." " .$GLOBALS["lastName"];
	}
	$fullname = "";
	fullName();
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>
	<?php
		echo $firstName;
		echo " ";
		echo $lastName;
		?>, õppetöö</title>
</head>
<body>
  <h1>
	<?php
		echo $firstName ." " .$lastName;
	?>, IF18</h1>
  <p>See leht on loodud <a href="http://www.tlu.ee" target="_blank">TLÜ</a> õppetöö raames, ei pruugi parim välja näha ning kindlasti ei sisalda tõsiselt võetavat sisu!</p>
  
  <hr>
  
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <label>Eesnimi:</label>
    <input type="text" name="firstName">
    <label>Perekonnanimi:</label>
    <input type="text" name="lastName">
	<label>Sünnikuu: </label>
	<?php echo '<select name:"birthMonth">' ."n";
	for ($i = 1; $i < 13; $i ++){
		echo '<option value_"' .$i .'"';
		if ($i == $birthMonth){
			echo " selected ";
		}
		echo ">" .$monthNamesET[$i - 1] ."</option> \n";
	}
	echo "</select> \n";
		?>
	<label>Sünniaasta: </label>
	<!--<input type="number" min="1914" max="2000" value="1999" name="birthYear">-->
	<?php
	    echo '<select name="birthYear">' ."\n";
		for ($i = $birthYear; $i >= date("Y") - 100; $i --){
			echo '<option value="' .$i .'"';
			if ($i == $birthYear){
				echo " selected ";
			}
			echo ">" .$i ."</option> \n";
		}
		echo "</select> \n";
	  ?>
		
    <br>
    <input type="submit" name="submitUserData" value="Saada andmed">
  </form>
  <hr>
  
  <?php
		if (isset($_POST["firstName"])){
		  echo "<p>" .$fullName .", Olete elanud järgnevatel aastatel: </p> \n";
		  echo "<ol> \n";
		    for ($i = $_POST["birthYear"]; $i <= date("Y"); $i ++){
			  echo "<li>" .$i ."</li> \n";	
			}
		  echo "</ol> \n"; 
		}
  ?>
  	
</body>
</html>



