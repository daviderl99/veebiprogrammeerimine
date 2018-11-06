<?php
  require("functions.php");
  
  //kui ple sisse loginud siis logimise lehele
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
  
  //piltide laadimise osa
	$target_dir = "../vp_pic_uploads/";
	$uploadOk = 1;

	// Check if image file is an actual image or fake image
	if(isset($_POST["submitImage"])) {
		if(!empty($_FILES["fileToUpload"]["name"])){
		
		$imageFileType = strtolower(pathinfo(basename($_FILES["fileToUpload"]["name"]),PATHINFO_EXTENSION));
		
		//$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
		//$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
		$timeStamp = microtime(1) * 10000;
		
		$target_file = $target_dir ."vp_" .$timeStamp ."." .$imageFileType;
		
		
		$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
		if($check !== false) {
			echo "Fail on - " . $check["mime"] . " pilt.";
			//$uploadOk = 1;
		} else {
			echo "Fail ei ole pilt.";
			$uploadOk = 0;
		}
		}
	}//siin lõppeb nupu vajutuse kontroll
	
  //lehe päise laadimine
  $pageTitle = "Fotode üleslaadimine";
  require("header.php");
  
?>

	<p>See leht on valminud <a href="http://www.tlu.ee" target="_blank">TLÜ</a> õppetöö raames ja ei oma mingisugust, mõtestatud või muul moel väärtuslikku sisu.</p>
	<hr>
	<p>Oled sisse loginud nimega: <?php echo $_SESSION["firstName"] ." " .$_SESSION["lastName"] ."."; ?></p>
	<ul>
      <li><a href="?logout=1">Logi välja!</a></li>
	  <li>Tagasi <a href="main.php">pealehele</a>.</li>
	</ul>
	<hr>
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">
    <label>Vali üleslaetav pildifail (soovitavalt mahuga kuni 2,5MB):</label><br>
    <input type="file" name="fileToUpload" id="fileToUpload"><br>
    <input type="submit" value="Lae pilt üles" name="submitImage">
</form>
	
  </body>
</html>