<?php

	//Konprobatzen dugu administratzailea bagara

	session_start();

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
?>

<!DOCTYPE html>
<html>

	<head>
		
		<link rel="stylesheet" href="loginStyles.css">
		
		<title>SUPERAUTOS</title>

	</head>

	<body>
	
		<center>
		
		<h1> <font color=white size=84> SUPERAUTOS </font> </h1>

		<table>
		
			<tr>
			
				<td>
			
					<center> <h1> AUTO BERRIA SARTU </h1> </center> <br>

					<!-- Formularioa egingo dugu autoa gordetzeko behar diren datuak hartzeko -->
					
					<form id="formularioa" action="php/auto_erregistroa_be.php" method="POST">
					
						IRUDIA: <input type="text" id="irudia" placeholder="Sartu irudiaren URL-a" name="irudia"> <br>
						MARKA: <input type="text" id="marka" placeholder="Autoaren marka jarri" name="marka"> <br>
						IZENA: <input type="text" id="izena" placeholder="Autoaren izena jarri" name="izena"> <br>
						POTENTZIA: <input type="number" id="potentzia" placeholder="Autoaren potentzia jarri" name="potentzia"> <br>
						PREZIOA: <input type="number" id="prezioa" placeholder="Autoaren prezioa jarri" name="prezioa"> <br> <br>
						
						<button onclick="validate();" type="button"> AUTOA ERREGISTRATU </button>
						<button onclick="window.location.href = 'hasiera.php';" type="button"> HASIERARA BUELTATU </button>
					
					</form>
				
				</td>
			
			</tr>
		
		</table>
		
		</center>

	</body>

</html>

<script>
		 
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
		
		//Konprobaketak egin ondoren eta dena ondo badago, formularioa bidaliko dugu autoa erregistratzeko

		let nireForm = document.getElementById("formularioa");
		nireForm.submit();

		return true;
	}

</script>