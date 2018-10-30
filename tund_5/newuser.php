<?php
	//kutsume välja funktsioonide faili
	require("functions.php");
	$notice = "";
	$firstName = "";
	$lastName = "";
	$birthMonth = null;
	$birthDay = null;
	$birthYear = null;
	$birthDate = null;
	$gender = null;
	$email = "";
	
	$firstNameError = "";
	$lastNameError = "";
	$birthMonthError = "";
	$birthDayError = "";
	$birthDateError = "";
	$genderError = "";
	$emailError = "";
	$passwordError = "";
	
	$monthNamesET = ["jaanuar", "veebruar", "märts", "aprill", "mai", "juuni", "juuli", "august", "september", "oktoober", "november", "detsember"];
	
	
	//kontrollime, kas kasutaja on midagi kirjutanud
	if(isset($_POST["submitUserData"])){
	//var_dump($_POST);
	if (isset($_POST["firstName"]) and !empty($_POST["firstName"])){
		//$firstName = $_POST["firstName"];
		$firstName = test_input($_POST["firstName"]);
	} else {
		$firstNameError = "&nbsp Palun sisesta oma eesnimi!";
	}
	if (isset($_POST["lastName"]) and !empty($_POST["lastName"])){
		$lastName = test_input($_POST["lastName"]);
	} else {
		$lastNameError = "&nbsp Palun sisesta oma perekonnanimi!";
	}
	if(isset($_POST["gender"]) and !empty($_POST["gender"])){
		$gender = intval($_POST["gender"]);
	} else {
		$genderError ="Palun määra sugu!";
	}
	if (isset($_POST["email"]) and !empty($_POST["email"])){
		$email = test_input($_POST["email"]);
	} else {
		$emailError = "&nbsp Palun sisesta oma emaili aadress!";
	}
	if (isset($_POST["password"]) and !empty($_POST["password"])){
		$password = test_input($_POST["password"]);
	} else {
		$passwordError = "&nbsp Palun sisesta oma parool!";
	}
	
	//kui kuu, päev ja aasta on olemas, kontrollitud
	//võiks ju hoopis kontrollida kas kuupäevadega seotud error muutujad on endiselt tühjad
	if(isset($_POST["birthDay"]) and ($_POST["birthMonth"]) and ($_POST["birthYear"])){
		//kas oodatav kuupäev on üldse võimalik
		//checkdate(kuu, päev, aasta) tahab täisarve
		if(checkdate(intval($_POST["birthMonth"]), intval($_POST["birthDay"]), intval($_POST["birthYear"]))){
		//kui on võimalik, teeme kuupäevaks
		$birthDate = date_create($_POST["birthMonth"] ."/" .$_POST["birthDay"] ."/" .$_POST["birthYear"]);
		$birthDate = date_format($birthDate, "Y-m-d");
		//echo $birthDate;
		} else {
		  $birthDateError = "Palun valige võimalik kuupäev!";
		}
	}
	
	//kui kõik on korras, siis salvestan kasutaja
	if(empty($firstNameError) and empty($lastNameError) and empty($birthMonthError) and empty($birthDayError) and empty($birthDateError) and empty($genderError) and empty($emailError) and empty($passwordError)){
	  $notice = signup($firstName, $lastName, $birthDate, $gender, $_POST["email"], $_POST["password"]);
	}

	}//Kas vajutati nuppu - lõpp
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Uue kasutaja loomine</title>
</head>
<body>
  <h1>Sisesta oma andmed, loo kasutaja.</h1>
  <p>See leht on loodud <a href="http://www.tlu.ee" target="_blank">TLÜ</a> õppetöö raames, ei pruugi parim välja näha ning kindlasti ei sisalda tõsiselt võetavat sisu!</p>
  
  <hr>
  
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <label>Eesnimi:</label><br>
    <input type="text" name="firstName" value="<?php echo $firstName; ?>"><span><?php echo $firstNameError; ?></span><br>
    <label>Perekonnanimi:</label><br>
    <input type="text" name="lastName"  value="<?php echo $lastName; ?>"><span><?php echo $lastNameError; ?></span><br><br>
	  <label>Sünnipäev: </label>
		 <?php
	    echo '<select name="birthDay">' ."\n";
		for ($i = 1; $i < 32; $i ++){
			echo '<option value="' .$i .'"';
			if ($i == $birthDay){
				echo " selected ";
			}
			echo ">" .$i ."</option> \n";
		}
		echo "</select> \n";
	  ?>
	  	<label>Sünnikuu: </label>
	<?php
	    echo '<select name="birthMonth">' ."\n";
		for ($i = 1; $i < 13; $i ++){
			echo '<option value="' .$i .'"';
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
		for ($i = date("Y") - 15; $i >= date("Y") - 100; $i --){
			echo '<option value="' .$i .'"';
			if ($i == $birthYear){
				echo " selected ";
			}
			echo ">" .$i ."</option> \n";
		}
		echo "</select> \n";
	  ?>	
    <br><br>
	
	<input type="radio" name="gender" value="2" <?php if ($gender == 2) {echo "checked";} ?>><label>Naine</label><br>
	<input type="radio" name="gender" value="1" <?php if ($gender == 1) {echo "checked";} ?>><label>Mees</label><br>
	<span><?php echo $genderError; ?></span>
	<br>
	
	<label>E-posti aadress (kasutajatunnuseks):</label><br>
	<input name="email" type="email"><br><br>
	
	<label>Salasõna (min 8 märki):</label><br>
	<input name="password" type="password">
	<br><br>

    <input type="submit" name="submitUserData" value="Loo kasutaja">
  </form>
  <hr>
  <p><?php echo $notice; ?></p>
  
</body>
</html>



