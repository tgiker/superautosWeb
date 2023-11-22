<?php

	//lehenengo sesioa hasiko dugu eta ondoren konprobatuko dugu erabiltze bat sartuta dagoen

	include 'php/konexioa_be.php';

	session_start();

	//nonce sortu
	$nonce = base64_encode(random_bytes(16));

	//CSP konfigurazioa
	header("Content-Security-Policy: script-src 'self' 'nonce-$nonce'; style-src 'self' 'nonce-$nonce' https://fonts.googleapis.com; frame-ancestors 'self'; form-action 'self'; img-src 'self'; connect-src 'self'; frame-src 'self'; font-src 'self' https://fonts.googleapis.com https://fonts.gstatic.com; media-src 'self'; object-src 'self'; manifest-src 'self';");

	//Konprobatzen dugu erabiltzailea saioa hasi duela
	if (!isset($_SESSION['erabiltzaile']))
	{
		echo"
			<script nonce='$nonce'>
				alert('Mesedez saioa hasi');
				window.location = 'hasiera.php';
			</script>
		";
		session_destroy();
		die();
	}
	else
	{
		//erabiltzailea sartuta badago bere erabiltzaile izena hartuko dugu eta ondoren bere informazioa gordeko dugu aldagai ezberdinetan

		$username = $_SESSION['erabiltzaile'];

		$resultErabiltzaile_q = "SELECT * FROM erabiltzaileak WHERE erabiltzaileIzena = ? ";

		$resultErabiltzaile_stmt = $konexioa->prepare($resultErabiltzaile_q);
		$resultErabiltzaile_stmt->bind_param("s", $username);
		$resultErabiltzaile_stmt->execute();

		$rows = $resultErabiltzaile_stmt->get_result();

		foreach ($rows as $row){
			$resultIzen_Abizen = $row['izen_abizenak'] ?? '';
			$resultTelefonoa = $row['telefonoa'] ?? '';
			$resultJaiotze_data = $row['jaiotze_data'] ?? '';
			$resultEmail = $row['email'] ?? '';
			$resultNan = $row['nan'] ?? '';
		}

		$resultErabiltzaile_stmt->close();

		//zenbat aldiz saiatu den erabiltzailearen informazioa aldatzen kontagailua hartuko dugu eta ondoren bere informazioa gordeko dugu aldagai ezberdinetan

		if (!isset($_SESSION['kontagailua'])){
			$_SESSION['kontagailua'] = 0;
		}

		$pasahitzaKontagailua = $_SESSION['kontagailua'];
		echo "<div id='kont' kontagailua='$pasahitzaKontagailua'></div>";

    	$konexioa->close();
	}
	
	//anti-CSRF token sortu
	$csrfToken = bin2hex(random_bytes(32));

	//anti-CSRF token gorde sesioan
	$_SESSION['csrf_token'] = $csrfToken;

	//X-Frame-Options konfigurazioa
	header('X-Frame-Options: DENY');

?>

<!DOCTYPE html>
<html>

	<head>
	
		<link rel="stylesheet" href="loginStyles.css">
		
		<title>SUPERAUTOS</title>
		
		<meta http-equiv="Content-Security-Policy" 
		content="default-src 'self'; script-src 'self' 'nonce-<?php echo $nonce; ?>'; 
		style-src 'self' 'nonce-<?php echo $nonce; ?>' https://fonts.googleapis.com; 
		font-src 'self' https://fonts.googleapis.com https://fonts.gstatic.com; 
		img-src 'self'; 
		form-action 'self';">

		<style nonce="<?php echo $nonce; ?>">

		inline {display: inline;}
		none {display: none;}

		</style>

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
					<input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">
						<none><input name="erabId" id="erabId" value="<?php echo $username ?? '';?> "></input></none>
						IZEN-ABIZENAK: <input type="text" id="izen_abizenak" placeholder="Sartu zure izen abizenak" name="izen_abizenak" value="<?php echo $resultIzen_Abizen ?? '';?>"> <br>
						NAN: <input type="text" id="nan" placeholder="NAN-a jarri" name="nan" value="<?php echo $resultNan ?? '';?>"> (Adib:11111111-Z) <br>
						TELEFONOA: <input type="number" id="telefonoa" placeholder="Telefono zenbakia sartu" name="telefonoa" value="<?php echo $resultTelefonoa ?? '';?>"> (bakarrik 9 zenbaki) <br>
						JAIOTZE-DATA: <input type="text" id="jaiotze_data" placeholder="Sartu zure jaiotze data" name="jaiotze_data" value="<?php echo $resultJaiotze_data ?? '';?>"> (Formatua: uuuu-hh-ee. Adib:2004-03-11) <br>
						EMAIL: <input type="text" id="emaila" placeholder="Emaila jarri" name="emaila" value="<?php echo $resultEmail ?? '';?>"> (Formatua:adibidea@zerbitzaria.extentsioa) <br> <br>

						ERABILTZAILE IZENA: <?php echo $username ?? ''; ?> <br>
						AURREKO PASAHITZA: <input type="password" id="pasahitzaAurrekoa" placeholder="Aurreko pasahitza jarri" name="pasahitzaAurrekoa"> (min: 8 karaktere, max: 16 karaktere) <br>
						PASAHITZA BERRIA: <input type="password" id="pasahitza" placeholder="Pasahitza berria jarri" name="pasahitza"> (min: 8 karaktere, max: 16 karaktere) <br>
						
						<button id="buttonEginda" type="button"> EGINDA </button>
						<button id="buttonHasiera" type="button"> HASIERARA BUELTATU </button>
					
					</form>
				
				</td>
			
			</tr>
		
		</table>
		
		</center>		
		
	</body>

</html>

<script nonce="<?php echo $nonce; ?>"> 

	var kontsolaKontagailu = 0;

	const artxiboizena = 'log.json'; 
	const tokia = 'areaPertsonala.php'
	function alertToLog(message) {
	return {
		timestamp: new Date().toLocaleString(),
		message: message,
		tokia: tokia
		};

	}

	function logToFile(logObject, artxiboizena) {
	//Recupera los registros existentes del almacenamiento local 
	const existingLogs = JSON.parse(localStorage.getItem(artxiboizena)) || [];

	//Erregistro berria gehitu
	existingLogs.push(logObject);

	//Erregistro eguneratuak gordetzen ditu tokiko biltegiratzean, bi espazioekin 
	localStorage.setItem(artxiboizena, JSON.stringify(existingLogs, null, 2));

	//Erregistroak kontsolan erakusten ditu 
	console.log(logObject);

	}

	if (document.getElementById('kont').getAttribute('kontagailua') >= 5) {
		const alertMessage = "Hainbatetan sartzen saiatu dira. KONTUZ, AKTIBITATE SUSMAGARRIA!!";
		logToFile(alertToLog(alertMessage), artxiboizena);
		kontsolaKontagailu++;
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

		if(kontsolaKontagailu >= 10){
			console.clear();
			kontsolaKontagailu = 0;
		}
		
		if(izena.length == 0){
			const alertMessage = "Ez duzu ezer jarri izen-abizenak zatian!";
			alert(alertMessage);
			logToFile(alertToLog(alertMessage), artxiboizena);
			kontsolaKontagailu++;
			return false;
		}
		else if(!izenaFormat.test(izena)){
			const alertMessage = "Ezin dira zenbakiak erabili izen-abizenak jartzeko!";
			alert(alertMessage);
			logToFile(alertToLog(alertMessage), artxiboizena);
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
				const alertMessage = "NAN zenbakia txarto dago!";
				alert(alertMessage);
				logToFile(alertToLog(alertMessage), artxiboizena);
				kontsolaKontagailu++;
				return false;
			}
			
		}else{
			const alertMessage = "NAN ez du balio!";
			alert(alertMessage);
			logToFile(alertToLog(alertMessage), artxiboizena);
			kontsolaKontagailu++;
			return false;
		}
		
		if (telefonoa.length != 9){
			const alertMessage = "Telefono zenbakiak bakarrik 9 zenbaki dituzte!";
			logToFile(alertToLog(alertMessage), artxiboizena);
			kontsolaKontagailu++;
			alert(alertMessage);
			return false;
		}
		if (izenaFormat.test(telefonoa)){
			const alertMessage = "Bakarrik zenbakiak erabili ahal dira!";
			logToFile(alertToLog(alertMessage), artxiboizena);
			kontsolaKontagailu++;
			alert(alertMessage);
			return false;
		}
		if (telefonoa < 0){
			const alertMessage = "Bakarrik zenbaki positiboak erabili ahal dira!";
			logToFile(alertToLog(alertMessage), artxiboizena);
			kontsolaKontagailu++;
			alert(alertMessage);
			return false;
		}
		
		var matchArray = date.match(datePattern);
		if (matchArray == null) {
			const alertMessage = "Ez da dataren formatua!";
			logToFile(alertToLog(alertMessage), artxiboizena);
			kontsolaKontagailu++;
			alert(alertMessage);
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
			const alertMessage = "Ez da dataren formatua!";
			logToFile(alertToLog(alertMessage), artxiboizena);
			kontsolaKontagailu++;
			alert(alertMessage);
			return false;
		}
		
		if (!mailFormat.test(mail)) {
			const alertMessage = "Emaila ez du balio!";
			logToFile(alertToLog(alertMessage), artxiboizena);
			kontsolaKontagailu++;
			alert(alertMessage);
			return false;
		}

		if (!pasahitzaFormat.test(pasahitza)) {
			const alertMessage = "Pasahitza gutxienez letra larri bat," +
			"letra xehe bat, zenbaki bat, sinbolo bat, " + 
			"8 karaktere eta gehienez 16 karaktere izan behar ditu!";
			logToFile(alertToLog(alertMessage), artxiboizena);
			kontsolaKontagailu++;
			alert(alertMessage);
			return false;
		}

		//Konprobaketak egin ondoren eta dena ondo badago, formularioa bidaliko dugu erabiltzailearen datuak aldatzeko
		
		let nireForm = document.getElementById("formularioa");

		nireForm.submit();

		return true;
	}

	document.addEventListener('DOMContentLoaded', function () {
		var buttonEginda = document.getElementById('buttonEginda');

		if (buttonEginda) {
			buttonEginda.addEventListener('click', function () {
				validate();
			});
		}
	});
			
	document.addEventListener('DOMContentLoaded', function () {
		var buttonHasiera = document.getElementById('buttonHasiera');

		if (buttonHasiera) {
			buttonHasiera.addEventListener('click', function () {
				window.location.href = 'hasiera.php';
			});
		}
	});

</script>