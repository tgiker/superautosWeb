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

	var kontsolaKontagailu= 0;

	const artxiboizena = 'log.json'; 
	const tokia = 'autoaSartu.php'
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
		
	function validate() {		

		//Funtzio honetan konprobatuko dugu formatu guztiak betetzen direla. Horretarako informazioa gordeko ditugu lehenengo eta ondoren konprobaketak egingo ditugu

        var irudia = document.getElementById("irudia").value;
        var marka = document.getElementById("marka").value;
		var izena = document.getElementById("izena").value;
        var potentzia = document.getElementById("potentzia").value;
		var prezioa = document.getElementById("prezioa").value;

        var zenbakiFormat = /[^0-9]/g;

		if(kontsolaKontagailu >= 10){
			console.clear();
			kontsolaKontagailu = 0;
		}
		
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
		
		//Konprobaketak egin ondoren eta dena ondo badago, formularioa bidaliko dugu autoa erregistratzeko

		let nireForm = document.getElementById("formularioa");
		nireForm.submit();

		return true;
	}

</script>