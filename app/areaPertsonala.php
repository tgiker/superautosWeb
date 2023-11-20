<?php

	//lehenengo sesioa hasiko dugu eta ondoren konprobatuko dugu erabiltze bat sartuta dagoen

	include 'php/konexioa_be.php';

	session_start();
	if (isset($_SESSION['erabiltzaile'])){

		//erabiltzailea sartuta badago bere erabiltzaile izena hartuko dugu eta ondoren bere informazioa gordeko dugu bariable ezberdinetan

		$username = $_SESSION['erabiltzaile'];
		$pasahitzaKontagailua = $_SESSION['kontagailua'];
		echo "<div id='kont' kontagailua='$pasahitzaKontagailua'></div>";

		$resultErabiltzaile = mysqli_query($konexioa, "SELECT * FROM erabiltzaileak WHERE erabiltzaileIzena = '$username' ");
		$pasahitza_hash_q = "SELECT pasahitza FROM erabiltzaileak WHERE erabiltzaileIzena=?";
		$stmt = $konexioa->prepare($pasahitza_hash_q);
		$stmt->bind_param("s", $username);
		$stmt->execute();
		$stmt->bind_result($pasahitza_hash);
		if ($stmt->fetch()) { 
			echo "<div id='password' pasahitzaAurreko='$pasahitza_hash'></div>";
		}

		$rows = mysqli_fetch_all($resultErabiltzaile, MYSQLI_ASSOC);

		foreach ($rows as $row){
			$resultIzen_Abizen = $row['izen_abizenak'] ?? '';
			$resultTelefonoa = $row['telefonoa'] ?? '';
			$resultJaiotze_data = $row['jaiotze_data'] ?? '';
			$resultEmail = $row['email'] ?? '';
			$resultNan = $row['nan'] ?? '';
		}
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

					<center> 
					<h1> AREA PERTSONALA </h1> 
					<h2> HEMEN ZURE DATUAK ALDATU AHAL DITUZU </h2> 
					</center> <br>

					<!-- Formularioa egingo dugu erabiltzailearen datuak aldatzeko -->
					
					<form id="formularioa" action="php/erabiltzailea_modifikatu_be.php" method="POST">
					
						<input name="erabId" id="erabId" value="<?php echo $username ?? '';?> " style="display:none"></input>
						IZEN-ABIZENAK: <input type="text" id="izen_abizenak" placeholder="Sartu zure izen abizenak" name="izen_abizenak" value="<?php echo $resultIzen_Abizen ?? '';?>"> <br>
						NAN: <input type="text" id="nan" placeholder="NAN-a jarri" name="nan" value="<?php echo $resultNan ?? '';?>"> (Adib:11111111-Z) <br>
						TELEFONOA: <input type="number" id="telefonoa" placeholder="Telefono zenbakia sartu" name="telefonoa" value="<?php echo $resultTelefonoa ?? '';?>"> (bakarrik 9 zenbaki) <br>
						JAIOTZE-DATA: <input type="text" id="jaiotze_data" placeholder="Sartu zure jaiotze data" name="jaiotze_data" value="<?php echo $resultJaiotze_data ?? '';?>"> (Formatua: uuuu-hh-ee. Adib:2004-03-11) <br>
						EMAIL: <input type="text" id="emaila" placeholder="Emaila jarri" name="emaila" value="<?php echo $resultEmail ?? '';?>"> (Formatua:adibidea@zerbitzaria.extentsioa) <br> <br>

						ERABILTZAILE IZENA: <?php echo $username ?? ''; ?> <br>
						AURREKO PASAHITZA: <input type="password" id="pasahitzaAurrekoa" placeholder="Aurreko pasahitza jarri" name="pasahitzaAurrekoa"> (min: 8 karaktere, max: 16 karaktere) <br>
						PASAHITZA BERRIA: <input type="password" id="pasahitza" placeholder="Pasahitza berria jarri" name="pasahitza"> (min: 8 karaktere, max: 16 karaktere) <br>
						
						<button onclick="validate();" type="button"> EGINDA </button>
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
	const tokia = 'areaPertsonala.php'
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
	
		//Funtzio honetan konprobatuko dugu formatu guztiak betetzen direla. Horretarako formatuak eta informazioa gordeko ditugu lehenengo eta ondoren konprobaketak egingo ditugu

		var izena = document.getElementById("izen_abizenak").value;
		var izenaFormat = /[^0-9]/g;
	
		var zenb, letr, letra;
		var nanFormat = /^[XYZ]?\d{5,8}-[A-Z]$/;
		var nan = document.getElementById("nan").value;
		nan = nan.toUpperCase();
	
		var telefonoa = document.getElementById("telefonoa").value;
		
		var date = document.getElementById("jaiotze_data").value;
		var datePattern = /^([12]\d{3}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01]))/;;
		
		var mail = document.getElementById("emaila").value;
		var mailFormat = /\S+@\S+\.\S+/;

		var pasahitza = document.getElementById("pasahitza").value;
		var pasahitzaFormat = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&])([A-Za-z\d$@$!%*?&]|[^ ]){8,15}$/;

		var pasahitzaAurrekoa = document.getElementById("pasahitzaAurrekoa").value;
		
		if(kontsolaKontagailu >= 10){
			console.clear();
			kontsolaKontagailu = 0;
		}

		if(izena.length == 0){
			alert("Ez duzu ezer jarri izen-abizenak zatian!");
			const alertMessage = "Ez duzu ezer jarri izen-abizenak zatian!";
			const logData = alertToLog(alertMessage);
			logToFile(logData, artxiboizena);
			kontsolaKontagailu = kontsolaKontagailu + 1;
			return false;
		}
		else if(!izenaFormat.test(izena)){
			alert("Ezin dira zenbakiak erabili izen-abizenak jartzeko!");
			const alertMessage = "Ezin dira zenbakiak erabili izen-abizenak jartzeko!";
			const logData = alertToLog(alertMessage);
			logToFile(logData, artxiboizena);
			kontsolaKontagailu = kontsolaKontagailu + 1;
			return false;
		}
		
		if(nanFormat.test(nan)){
			zenb = nan.substr(0,nan.length-2);
			zenb = zenb.replace("X", 0);
			zenb = zenb.replace("Y", 1);
			zenb = zenb.replace("Z", 2);
			letr = nan.substr(nan.length-1, 1);
			zenb = zenb % 23;
			letra = "TRWAGMYFPDXBNJZSQVHLCKET";
			letra = letra.substring(zenb, zenb+1);
			if (letra != letr) {
				alert("NAN zenbakia txarto dago!");
				const alertMessage = "NAN zenbakia txarto dago!";
				const logData = alertToLog(alertMessage);
				logToFile(logData, artxiboizena);
				kontsolaKontagailu = kontsolaKontagailu + 1;
				return false;
			}
			
		}else{
			alert("NAN ez du balio!");
			const alertMessage = "NAN ez du balio!";
			const logData = alertToLog(alertMessage);
			logToFile(logData, artxiboizena);
			kontsolaKontagailu = kontsolaKontagailu + 1;
			return false;
		}
		
		if (telefonoa.length != 9){
			alert("Telefono zenbakiak bakarrik 9 zenbaki dituzte!");
			const alertMessage = "Telefono zenbakiak bakarrik 9 zenbaki dituzte!";
			const logData = alertToLog(alertMessage);
			logToFile(logData, artxiboizena);
			kontsolaKontagailu = kontsolaKontagailu + 1;
			return false;
		}
		if (izenaFormat.test(telefonoa)){
			alert("Bakarrik zenbakiak erabili ahal dira!");
			const alertMessage = "Bakarrik zenbakiak erabili ahal dira!";
			const logData = alertToLog(alertMessage);
			logToFile(logData, artxiboizena);
			kontsolaKontagailu = kontsolaKontagailu + 1;
			return false;
		}
		if (telefonoa < 0){
			alert("Bakarrik zenbaki positiboak erabili ahal dira!");
			const alertMessage = "Bakarrik zenbaki positiboak erabili ahal dira!";
			const logData = alertToLog(alertMessage);
			logToFile(logData, artxiboizena);
			kontsolaKontagailu = kontsolaKontagailu + 1;
			return false;
		}
		
		var matchArray = date.match(datePattern);
		if (matchArray == null) {
			alert("Ez da dataren formatua!");
			const alertMessage = "Ez da dataren formatua!";
			const logData = alertToLog(alertMessage);
			logToFile(logData, artxiboizena);
			kontsolaKontagailu = kontsolaKontagailu + 1;
			return false;
		}

		var dateString = date.replace(/\D/g, ""); 

		var year = parseInt(dateString.substr(0, 4));
		var month = parseInt(dateString.substr(4, 2));
		var day = parseInt(dateString.substr(6, 2));
		
		var daysInMonth = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];

		if (year % 400 == 0 || (year % 100 != 0 && year % 4 == 0)) {
			daysInMonth[1] = 29;
		}

		if (month < 1 || month > 12 || day < 1 || day > daysInMonth[month - 1]) {
			alert("Ez da dataren formatua!");
			const alertMessage = "Ez da dataren formatua!";
			const logData = alertToLog(alertMessage);
			logToFile(logData, artxiboizena);
			kontsolaKontagailu = kontsolaKontagailu + 1;
			return false;
		}
		
		if (!mailFormat.test(mail)) {
			alert("Emaila ez du balio!");
			const alertMessage = "Emaila ez du balio!";
			const logData = alertToLog(alertMessage);
			logToFile(logData, artxiboizena);
			kontsolaKontagailu = kontsolaKontagailu + 1;
			return false;
		}

		if (pasahitzaFormat.test(pasahitza)) {
			alert("Pasahitza karaktere arraroak ditu!");
			const alertMessage = "Pasahitza karaktere arraroak ditu!";
			const logData = alertToLog(alertMessage);
			logToFile(logData, artxiboizena);
			kontsolaKontagailu = kontsolaKontagailu + 1;
			return false;
		}
		if (pasahitza.length < 8){
			alert("Pasahitza laburregia da!");
			const alertMessage = "Pasahitza laburregia da!";
			const logData = alertToLog(alertMessage);
			logToFile(logData, artxiboizena);
			kontsolaKontagailu = kontsolaKontagailu + 1;
			return false;
		}
		if (pasahitza.length > 16){
			alert("Pasahitza luzeegia da!");
			const alertMessage = "Pasahitza luzeegia da!";
			const logData = alertToLog(alertMessage);
			logToFile(logData, artxiboizena);
			kontsolaKontagailu = kontsolaKontagailu + 1;
			return false;
		}
		if (pasahitzaAurrekoa != document.getElementById('password').getAttribute('pasahitzaAurreko')){
			alert("Aurreko pasahitza gaizki jarri duzu!");
			const alertMessage = "Aurreko pasahitza gaizki jarri duzu!";
			const logData = alertToLog(alertMessage);
			logToFile(logData, artxiboizena);
			kontsolaKontagailu = kontsolaKontagailu + 1;
			<?php
			$_SESSION['kontagailua'] = $_SESSION['kontagailua'] + 1;
			?>
			var passwordElement = document.getElementById('kont');
			var pasahitzaAurrekoValue = passwordElement.getAttribute('kontagailua');
			// Convertir a número y verificar si es un número válido
			var nuevoValor = parseInt(pasahitzaAurrekoValue) + 1;
			passwordElement.setAttribute('kontagailua', nuevoValor);
			
				if(document.getElementById('kont').getAttribute('kontagailua') >= 5){
					const alertMessage = "Hainbatetan sartzen saiatu dira. KONTUZ, AKTIBITATE SUSMAGARRIA!!";
					const logData = alertToLog(alertMessage);
					logToFile(logData, artxiboizena);
					kontsolaKontagailu = kontsolaKontagailu + 1 ;
					return false;
				}
			return false;
		}

		//Konprobaketak egin ondoren eta dena ondo badago, formularioa bidaliko dugu erabiltzailearen datuak aldatzeko
		
		let nireForm = document.getElementById("formularioa");
		<?php
		$_SESSION['kontagailua'] = 0;
		?>
		nireForm.submit();

		return true;
	}
</script>