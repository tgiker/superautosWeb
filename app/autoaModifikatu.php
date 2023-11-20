<?php

	//Autoaren informazioa gordeko dugu bere id erabiliz

    include 'php/konexioa_be.php';

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

	var kontsolaKontagailu= 0;

	const artxiboizena = 'log.json';
	const tokia = 'autoaModifikatu.php' 
	function alertToLog(message) {
    return {
		timestamp: new Date().toLocaleString(),
        message: message,
		tokia: tokia
        // Otros campos que desees agregar
   		};
    	
	}


	function logToFile(logObject, artxiboizena) {
    // Recupera los registros existentes del almacenamiento local
    const existingLogs = JSON.parse(localStorage.getItem(artxiboizena)) || [];

    // Agrega el nuevo registro
    existingLogs.push(logObject);

    // Guarda los registros actualizados en el almacenamiento local con indentación de dos espacios
    localStorage.setItem(artxiboizena, JSON.stringify(existingLogs, null, 2));

	// Muestra los registros en la consola con la misma indentación
	console.log(logObject);
	}


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

		if(kontsolaKontagailu >= 10){
			console.clear();
			kontsolaKontagailu = 0;
		}

        var irudia = document.getElementById("irudia").value;
        var marka = document.getElementById("marka").value;
		var izena = document.getElementById("izena").value;
        var potentzia = document.getElementById("potentzia").value;
		var prezioa = document.getElementById("prezioa").value;

        var zenbakiFormat = /[^0-9]/g;
		
        if(irudia.length == 0){
			alert("Ez duzu ezer jarri irudia zatian!");
			const alertMessage = "Ez duzu ezer jarri irudia zatian!";
			const logData = alertToLog(alertMessage);
			logToFile(logData, artxiboizena);
			kontsolaKontagailu = kontsolaKontagailu + 1;
			return false;
		}

        if(marka.length == 0){
			alert("Ez duzu ezer jarri marka zatian!");
			const alertMessage = "Ez duzu ezer jarri marka zatian!";
			const logData = alertToLog(alertMessage);
			logToFile(logData, artxiboizena);
			kontsolaKontagailu = kontsolaKontagailu + 1;
			return false;
		}

        if(izena.length == 0){
			alert("Ez duzu ezer jarri izena zatian!");
			const alertMessage = "Ez duzu ezer jarri izena zatian!";
			const logData = alertToLog(alertMessage);
			logToFile(logData, artxiboizena);
			kontsolaKontagailu = kontsolaKontagailu + 1;
			return false;
		}

        if(potentzia.length == 0){
			alert("Ez duzu ezer jarri potentzia zatian!");
			const alertMessage = "Ez duzu ezer jarri potentzia zatian!";
			const logData = alertToLog(alertMessage);
			logToFile(logData, artxiboizena);
			kontsolaKontagailu = kontsolaKontagailu + 1;
			return false;
		}
        else if(zenbakiFormat.test(potentzia)){
			alert("Ezin dira hizkiak erabili potentzia jartzeko!");
			const alertMessage = "Ezin dira hizkiak erabili potentzia jartzeko!";
			const logData = alertToLog(alertMessage);
			logToFile(logData, artxiboizena);
			kontsolaKontagailu = kontsolaKontagailu + 1;
			return false;
		}

		if(prezioa.length == 0){
			alert("Ez duzu ezer jarri prezioa zatian!");
			const alertMessage = "Ez duzu ezer jarri prezioa zatian!";
			const logData = alertToLog(alertMessage);
			logToFile(logData, artxiboizena);
			kontsolaKontagailu = kontsolaKontagailu + 1;
			return false;
		}
        else if(zenbakiFormat.test(prezioa)){
			alert("Ezin dira hizkiak erabili prezioa jartzeko!");
			const alertMessage = "Ezin dira hizkiak erabili prezioa jartzeko!";
			const logData = alertToLog(alertMessage);
			logToFile(logData, artxiboizena);
			kontsolaKontagailu = kontsolaKontagailu + 1;
			return false;
		}

		//Konprobaketak egin ondoren eta dena ondo badago, formularioa bidaliko dugu autoaren datuak aldatzeko
		
		let nireForm = document.getElementById("formularioa");
		nireForm.submit();
		const alertMessage = "Autoa modifikatu da!";
		const logData = alertToLog(alertMessage);
		logToFile(logData, artxiboizena);
		kontsolaKontagailu = kontsolaKontagailu + 1;
		return true;
	}

</script>