<?php

	ini_set('display_errors', 0);

	//HttpOnly ezarri erasoak saihesteko
	session_set_cookie_params(0, '/', '', false, true);
	
	//nonce sortu
	$nonce = base64_encode(random_bytes(16));

	//CSP konfigurazioa
	header("Content-Security-Policy: script-src 'self' 'nonce-$nonce'; style-src 'self' 'nonce-$nonce' https://fonts.googleapis.com; frame-ancestors 'self'; form-action 'self'; img-src 'self'; connect-src 'self'; frame-src 'self'; font-src 'self' https://fonts.googleapis.com https://fonts.gstatic.com; media-src 'self'; object-src 'self'; manifest-src 'self';");

	//X-Frame-Options konfigurazioa
	header('X-Frame-Options: DENY');

	//X-Powered-By goiburua kendu informazioa ez zabaltzeko
	header_remove("X-Powered-By");

	//X-Content-Type-Options 'nosniff' ezarri
	header("X-Content-Type-Options: nosniff");
	
	try{
		//Konprobatzen dugu administratzailea bagara

		session_start();

		if (!isset($_SESSION['erabiltzaile']) || $_SESSION['erabiltzaile'] != 'admin')
		{
			echo"
				<script nonce='$nonce'>
					alert('Ez dituzu pribilegiorik hemen egoteko');
					window.location = 'hasiera.php';
				</script>
			";
			session_destroy();
			die();
		}

		//anti-CSRF token sortu
		$csrfToken = bin2hex(random_bytes(32));

		//anti-CSRF token gorde sesioan
		$_SESSION['csrf_token'] = $csrfToken;

	} catch (Exception $e) {
		echo "Error. Mesedez saiatu berriro geroago";
        //500 errorea adierazi
		header("HTTP/1.1 500 Internal Server Error");
		include("error500.html");
		exit;
	}

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
						<input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">
						IRUDIA: <input type="text" id="irudia" placeholder="Sartu irudiaren URL-a" name="irudia"> <br>
						MARKA: <input type="text" id="marka" placeholder="Autoaren marka jarri" name="marka"> <br>
						IZENA: <input type="text" id="izena" placeholder="Autoaren izena jarri" name="izena"> <br>
						POTENTZIA: <input type="number" id="potentzia" placeholder="Autoaren potentzia jarri" name="potentzia"> <br>
						PREZIOA: <input type="number" id="prezioa" placeholder="Autoaren prezioa jarri" name="prezioa"> <br> <br>
						
						<button id="buttonEginda" type="button"> AUTOA ERREGISTRATU </button>
						<button id="buttonHasiera" type="button"> HASIERARA BUELTATU </button>
					
					</form>
				
				</td>
			
			</tr>
		
		</table>
		
		</center>

	</body>

</html>

<script nonce="<?php echo $nonce; ?>">

	try{

		var kontsolaKontagailu = 0;

		const artxiboizena = 'log.json'; 
		const tokia = 'autoaSartu.php'

		function alertToLog(message) {
			//Erregistroak kontsolan erakusten ditu 
			console.log(" | Time: " + new Date().toLocaleString() + "\n | Mezua: " + message + "\n | Tokia: " + tokia);
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
				const alertMessage = "Ez duzu ezer jarri irudia zatian!";
				alert(alertMessage);
				logToFile(alertToLog(alertMessage), artxiboizena);
				kontsolaKontagailu++;
				return false;
			}

			if(marka.length == 0){
				const alertMessage = "Ez duzu ezer jarri marka zatian!";
				alert(alertMessage);
				logToFile(alertToLog(alertMessage), artxiboizena);
				kontsolaKontagailu++;
				return false;
			}

			if(izena.length == 0){
				const alertMessage = "Ez duzu ezer jarri izena zatian!";
				alert(alertMessage);
				logToFile(alertToLog(alertMessage), artxiboizena);
				kontsolaKontagailu++;
				return false;
			}

			if(potentzia.length == 0){
				const alertMessage = "Ez duzu ezer jarri potentzia zatian!";
				alert(alertMessage);
				logToFile(alertToLog(alertMessage), artxiboizena);
				kontsolaKontagailu++;
				return false;
			}
			else if(zenbakiFormat.test(potentzia)){
				const alertMessage = "Ezin dira hizkiak erabili potentzia jartzeko!";
				alert(alertMessage);
				logToFile(alertToLog(alertMessage), artxiboizena);
				kontsolaKontagailu++;
				return false;
			}

			if(prezioa.length == 0){
				const alertMessage = "Ez duzu ezer jarri prezioa zatian!";
				alert(alertMessage);
				logToFile(alertToLog(alertMessage), artxiboizena);
				kontsolaKontagailu++;
				return false;
			}
			else if(zenbakiFormat.test(prezioa)){
				const alertMessage = "Ezin dira hizkiak erabili prezioa jartzeko!";
				alert(alertMessage);
				logToFile(alertToLog(alertMessage), artxiboizena);
				kontsolaKontagailu++;
				return false;
			}
			
			//Konprobaketak egin ondoren eta dena ondo badago, formularioa bidaliko dugu autoa erregistratzeko

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

	} catch {
		System.err.println("Error. Saiatu berriro geroago mesedez.");
	}

</script>