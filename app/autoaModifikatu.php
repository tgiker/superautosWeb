<?php

	//Autoaren informazioa gordeko dugu bere id erabiliz

    include 'php/konexioa_be.php';

	session_start();
	
	//Konprobatzen dugu administratzailea bagara
	if (!isset($_SESSION['erabiltzaile']) || $_SESSION['erabiltzaile'] != 'admin')
	{
		echo'
			<script>
				alert("Ez dituzu pribilegiorik hemen egoteko");
				window.location = "hasiera.php";
			</script>
		';
		session_destroy();
		die();
	}

    $autoId = $_POST['autoId'];
    $resultErabiltzaile = mysqli_query($konexioa, "SELECT * FROM autoak WHERE id = '$autoId' ");

	$rows = mysqli_fetch_all($resultErabiltzaile, MYSQLI_ASSOC);

	foreach ($rows as $row){
		$resultIrudia = $row['irudia'] ?? '';
		$resultMarka = $row['marka'] ?? '';
		$resultIzena = $row['izena'] ?? '';
		$resultPotentzia = $row['potentzia'] ?? '';
		$resultPrezioa = $row['prezioa'] ?? '';
	}
?>

<!DOCTYPE html>
<html>

	<head>
		
		<link rel="stylesheet" href="loginStyles.css">
		
		<title>SUPERAUTOS</title>

		<meta http-equiv="Content-Security-Policy" content="default-src 'self'; script-src 'self' https://ajax.googleapis.com">

	</head>

	<body>
	
		<center>
		
		<h1> <font color=white size=84> SUPERAUTOS </font> </h1>

		<table>
		
			<tr>
			
				<td>
			
					<center> <h1> AUTOA MODIFIKATU </h1> </center> <br>

					<!-- Formularioa egingo dugu autoaren datuak aldatzeko -->
					
					<form id="formularioa" action="php/auto_modifikatu_be.php" method="POST">

                        <input name="autoId" id="autoId" value="<?php echo $autoId; ?>" style="display:none"></input>
						IRUDIA: <input type="text" id="irudia" placeholder="Sartu irudiaren URL berria" name="irudia" value="<?php echo $resultIrudia; ?>"> <br>
						MARKA: <input type="text" id="marka" placeholder="Autoaren marka berria jarri" name="marka" value="<?php echo $resultMarka; ?>"> <br>
						IZENA: <input type="text" id="izena" placeholder="Autoaren izen berria jarri" name="izena" value="<?php echo $resultIzena; ?>"> <br>
						POTENTZIA: <input type="number" id="potentzia" placeholder="Autoaren potentzia berria jarri" name="potentzia" value="<?php echo $resultPotentzia; ?>"> <br>
						PREZIOA: <input type="number" id="prezioa" placeholder="Autoaren prezioa berria jarri" name="prezioa" value="<?php echo $resultPrezioa; ?>"> <br> <br>
						
						<button onclick="validate();" type="button"> AUTOA MODIFIKATU </button>
						<button onclick="window.location.href = 'hasiera.php';" type="button"> HASIERARA BUELTATU </button>
					
					</form>

                    <form id="autoEzabaketaForm" action="php/auto_ezabatu_be.php" method="POST">
                        <input name="autoId" id="autoId" value="<?php echo $autoId; ?>" style="display:none"></input>
                        <button onclick="baieztatu();" type="button"> AUTOA EZABATU </button>
                    </form>
				
				</td>
			
			</tr>
		
		</table>
		
		</center>

	</body>

</html>

<script>
		 
	function baieztatu() {

		//Hemen baieztatuko dugu administratzailea ziur badago autoa ezabatu nahi duela
		if (window.confirm("Ziur zaude auto hau ezabatu nahi duzula?")) {
			let nireForm = document.getElementById("autoEzabaketaForm");
			nireForm.submit();
		} else {
			alert("Ez da autoa ezabatuko");
		}
	}

	function validate() {		

		//Funtzio honetan konprobatuko dugu formatu guztiak betetzen direla. Horretarako informazioa gordeko ditugu lehenengo eta ondoren konprobaketak egingo ditugu

        var irudia = document.getElementById("irudia").value;
        var marka = document.getElementById("marka").value;
		var izena = document.getElementById("izena").value;
        var potentzia = document.getElementById("potentzia").value;
		var prezioa = document.getElementById("prezioa").value;

        var zenbakiFormat = /[^0-9]/g;
		
        if(irudia.length == 0){
			alert("Ez duzu ezer jarri irudia zatian!");
			return false;
		}

        if(marka.length == 0){
			alert("Ez duzu ezer jarri marka zatian!");
			return false;
		}

        if(izena.length == 0){
			alert("Ez duzu ezer jarri izena zatian!");
			return false;
		}

        if(potentzia.length == 0){
			alert("Ez duzu ezer jarri potentzia zatian!");
			return false;
		}
        else if(zenbakiFormat.test(potentzia)){
			alert("Ezin dira hizkiak erabili potentzia jartzeko!");
			return false;
		}

		if(prezioa.length == 0){
			alert("Ez duzu ezer jarri prezioa zatian!");
			return false;
		}
        else if(zenbakiFormat.test(prezioa)){
			alert("Ezin dira hizkiak erabili prezioa jartzeko!");
			return false;
		}

		//Konprobaketak egin ondoren eta dena ondo badago, formularioa bidaliko dugu autoaren datuak aldatzeko
		
		let nireForm = document.getElementById("formularioa");
		nireForm.submit();

		return true;
	}

</script>