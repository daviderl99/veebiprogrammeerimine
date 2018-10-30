<?php
  //laen andmebaasi info
  require("../../../config.php");
  //echo $GLOBALS["serverUsername"];
  $database = "if18_david_er_1";
  
  //võtan kasutusele sessiooni
  session_start();
  
  	function updateprofile(){
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("UPDATE vpuserprofiles (userid, description, bgcolor, txtcolor) VALUES (?, ?, ?, ?)");
		echo $mysqli->error;
		$stmt->bind_param("isss", $userid , $mydescription, $mybgcolor, $mytxtcolor);
		$stmt->close();
		$mysqli->close();
		return $notice;
}
	function userprofiles($userid, $mybgcolor, $mytxtcolor, $mydescription){
		$totalhtml = "";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("INSERT INTO vpuserprofiles (userid, description, bgcolor, txtcolor) VALUES (?, ?, ?, ?)");
		echo $mysqli->error;
		$stmt->bind_param("isss", $userid , $mydescription, $mybgcolor, $mytxtcolor);
		if($stmt->execute()){
			$notice = "Kasutaja profiili lisamine õnnestus";
		} else{
			$notice = "Kasutaja profiili lisamisel tekkis viga" .$stmt->error;
		}			
		$stmt->close();
		$mysqli->close();
		return $notice;

	}


	function getuserprofiles($userid){
		$totalhtml = "";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT id, userid, description, bgcolor, txtcolor FROM vpuserprofiles WHERE userid = ?");// andmete küsimine andmebaasist = select
		echo $mysqli->error;
		$stmt->bind_param("i",$userid);
		$stmt->bind_result($id, $userid, $description, $bgcolor ,$txtcolor);
		if($stmt->fetch()){
			$data = ["bgcolor" =>$bgcolor, "txtcolor"=>$txtcolor, "description"=>$description];//kui kasutaja on varem sättinud ss tuleb tema valik
		} else {
			$data = ["bgcolor" =>"#FFFFFF", "txtcolor"=>"#000000", "description" => "Pole iseloomustust lisanud."];// ehks siis kui kasutaja on esimest korda siis default on see
		}
		$stmt->execute();
		$stmt->close();
		$mysqli->close();
		return $data;
	}
  
	//kõigi valideeritud sõnumite lugemine kasutajate kaupa
	function readallvalidatedmessagesbyuser() {
		$msghtml = "";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$stmt = $mysqli->prepare("SELECT id, firstname, lastname FROM vpusers");
		echo $mysqli->error;
		$stmt->bind_result($idFromDb, $firstNameFromDb, $lastNameFromDb);
		
		$stmt2 = $mysqli->prepare("SELECT message, accepted FROM vpamsg WHERE acceptedby=?");
		echo $mysqli->error;
		$stmt2->bind_param("i", $idFromDb);
		$stmt2->bind_result($msgFromDb, $acceptedFromDb);
		
		$stmt->execute();
		//et hoida andmebaasist loetud andmeid pisut kauem mälus, et saaks edasi kasutada
		$stmt->store_result();
		while($stmt->fetch()){
			$msghtml .= "<h3>" .$firstNameFromDb ." " .$lastNameFromDb ."</h3> \n";
			$stmt2->execute();
			while($stmt2->fetch()){
				$msghtml .= "<p><b>";
				if($acceptedFromDb == 1){
					$msghtml .= "Lubatud: ";
				} else {
					$msghtml .= "Keelatud: ";
				}
			$msghtml .= "</b>" .$msgFromDb ."</p> \n";
			}
		}
		$stmt2->close();
		$stmt->close();
		$mysqli->close();
		return $msghtml;
	}
  
	//kasutajate nimekiri
    function listusers(){
	$notice = "";
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $mysqli->prepare("SELECT firstname, lastname, email FROM vpusers WHERE id !=?");
	
	echo $mysqli->error;
	$stmt->bind_param("i", $_SESSION["userId"]);
	$stmt->bind_result($firstname, $lastname, $email);
	if($stmt->execute()){
	  $notice .= "<ol> \n";
	  while($stmt->fetch()){
		  $notice .= "<li>" .$firstname ." " .$lastname .", kasutajatunnus: " .$email ."</li> \n";
	  }
	  $notice .= "</ol> \n";
	} else {
		$notice = "<p>Kasutajate nimekirja lugemisel tekkis tehniline viga! " .$stmt->error;
	}
	
	$stmt->close();
	$mysqli->close();
	return $notice;
  }
  
    function allvalidmessages(){
	$html = "";
	$valid = 1;
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $mysqli->prepare("SELECT message FROM vpamsg WHERE accepted=? ORDER BY accepttime DESC");
	echo $mysqli->error;
	$stmt->bind_param("i", $valid);
	$stmt->bind_result($msg);
	$stmt->execute();
	while($stmt->fetch()){
		$html .= "<p>" .$msg ."</p> \n";
	}
	$stmt->close();
	$mysqli->close();
	if(empty($html)){
		$html = "<p>Kontrollitud sõnumeid pole.</p>";
	}
	return $html;
  }
  
  function validatemsg($editId, $validation){
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $mysqli->prepare("UPDATE vpamsg SET acceptedby=?, accepted=?, accepttime=now() WHERE id=?");
	$stmt->bind_param("iii", $_SESSION["userId"], $validation, $editId);
	if($stmt->execute()){
	  echo "Õnnestus";
	  header("Location: validatemsg.php");
	  exit();
	} else {
	  echo "Tekkis viga: " .$stmt->error;
	}
	$stmt->close();
	$mysqli->close();
  }
  
    //loen sõnumi valideerimiseks
  function readmsgforvalidation($editId){
	$notice = "";
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $mysqli->prepare("SELECT message FROM vpamsg WHERE id = ?");
	$stmt->bind_param("i", $editId);
	$stmt->bind_result($msg);
	$stmt->execute();
	if($stmt->fetch()){
		$notice = $msg;
	}
	$stmt->close();
	$mysqli->close();
	return $notice;
  }
  
  //valideerimata sõnumite lugemine
  function readallunvalidatedmessages(){
	$notice = "<ul> \n";
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $mysqli->prepare("SELECT id, message FROM vpamsg WHERE accepted IS NULL ORDER BY id DESC");
	echo $mysqli->error;
	$stmt->bind_result($id, $msg);
	$stmt->execute();
	
	while($stmt->fetch()){
		$notice .= "<li>" .$msg .'<br><a href="validatemessage.php?id=' .$id .'">Valideeri</a>' ."</li> \n";
	}
	$notice .= "</ul> \n";
	$stmt->close();
	$mysqli->close();
	return $notice;
  }
  
  //sisselogimine
  function signin($email, $password){
	$notice = "";
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    $stmt = $mysqli->prepare("SELECT id, firstname, lastname, password FROM vpusers WHERE email=?");
	$mysqli->error;
	$stmt->bind_param("s", $email);
	$stmt->bind_result($idFromDb, $firstnameFromDb, $lastnameFromDb, $passwordFromDb);
	if($stmt->execute()){
	  //kui õnnestus andmebaasist lugenine
	  if($stmt->fetch()){
		//leiti selline kasutaja
	    if(password_verify($password, $passwordFromDb)){
		  //parool õige
		  $notice = "Logisite õnnelikult sisse!";
		  $_SESSION["userId"] = $idFromDb;
		  $_SESSION["firstName"] = $firstnameFromDb;
		  $_SESSION["lastName"] = $lastnameFromDb;
		  $stmt->close();
	      $mysqli->close();
		  header("Location: main.php");
		  exit();
		  
		} else {
		  $notice = "Sisestasite vale salasõna!";
        }
	  } else {
		$notice = "Sellist kasutajat (" .$email .") ei leitud!";  
	  }		  
	} else {
	  $notice = "Sisselogimisel tekkis tehniline viga!" .$stmt->error;
	}

	$stmt->close();
	$mysqli->close();
	return $notice;
  }
  
  //kasutaja salvestamine
  function signup($name, $surname, $email, $gender, $birthDate, $password){
	$notice = "";
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	//kontrollime, ega kasutajat juba olemas pole
	$stmt = $mysqli->prepare("SELECT id FROM vpusers WHERE email=?");
	echo $mysqli->error;
	$stmt->bind_param("s",$email);
	$stmt->execute();
	if($stmt->fetch()){
		//leiti selline, seega ei saa uut salvestada
		$notice = "Sellise kasutajatunnusega (" .$email .") kasutaja on juba olemas! Uut kasutajat ei salvestatud!";
	} else {
		$stmt->close();
		$stmt = $mysqli->prepare("INSERT INTO vpusers (firstname, lastname, birthdate, gender, email, password) VALUES(?,?,?,?,?,?)");
    	echo $mysqli->error;
	    $options = ["cost" => 12, "salt" => substr(sha1(rand()), 0, 22)];
	    $pwdhash = password_hash($password, PASSWORD_BCRYPT, $options);
	    $stmt->bind_param("sssiss", $name, $surname, $birthDate, $gender, $email, $pwdhash);
	    if($stmt->execute()){
		  $notice = "ok";
	    } else {
	      $notice = "error" .$stmt->error;	
	    }
	}
	$stmt->close();
	$mysqli->close();
	return $notice;
  }

  //anonüümse sõnumi salvestamine
  function saveamsg($msg){
	$notice = "";
	//serveri ühendus (server, kasutaja, parool, andmebaas
	$mysqli = new mysqli($GLOBALS["serverHost"],$GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	//valmistan ette SQL käsu
	$stmt = $mysqli->prepare("INSERT INTO vpamsg (message) VALUES(?)");
	echo $mysqli->error;
	//asendame SQL käsus küsimargi päris infoga (andmetüüp, andmed ise)
	//s - string; i - integer; d - decimal
	$stmt->bind_param("s", $msg);
	if ($stmt->execute()){
	  $notice = 'Sõnum: "' .$msg .'" on salvestatud.';
	} else {
	  $notice = "Sõnumi salvestamisel tekkis tõrge: " .$stmt->error;
	}
	$stmt->close();
	$mysqli->close();
	return $notice;
  }
  
  function listallmessages(){
	$msgHTML = "";
    $mysqli = new mysqli($GLOBALS["serverHost"],$GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    $stmt = $mysqli->prepare("SELECT message FROM vpamsg");
	echo $mysqli->error;
	$stmt->bind_result($msg);
	$stmt->execute();
	while($stmt->fetch()){
		$msgHTML .= "<p>" .$msg ."</p> \n";
	}
	$stmt->close();
	$mysqli->close();
	return $msgHTML;
  }
  
  //tekstsisestuse kontroll
  function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }
?>