<?php

	ini_set('display_errors', 0);

	//HttpOnly ezarri erasoak saihesteko
	session_set_cookie_params(0, '/', '', false, true);
	
	//nonce sortu
	$nonce = base64_encode(random_bytes(16));

	//CSP konfigurazioa
	header("Content-Security-Policy: script-src 'self' 'nonce-$nonce'; style-src 'self' 'nonce-$nonce' https://fonts.googleapis.com; frame-ancestors 'self'; form-action 'self'; img-src 'self'; connect-src 'self'; frame-src 'self'; font-src 'self' https://fonts.googleapis.com https://fonts.gstatic.com; media-src 'self'; object-src 'self'; manifest-src 'self';");
	
	try{

		//Autoaren informazioa gordeko dugu bere id erabiliz

		include 'php/konexioa_be.php';

		session_start();

		//Konprobatzen dugu administratzailea bagara
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

	} catch (Exception $e) {
		echo "Error. Mesedez saiatu berriro geroago";
        //500 errorea adierazi
		header("HTTP/1.1 500 Internal Server Error");
		include("error500.html");
		exit;
	}

	//anti-CSRF token sortu
	$csrfToken = bin2hex(random_bytes(32));

	//anti-CSRF token gorde sesioan
	$_SESSION['csrf_token'] = $csrfToken;

	//X-Frame-Options konfigurazioa
	header('X-Frame-Options: DENY');

	//X-Powered-By goiburua kendu informazioa ez zabaltzeko
	header_remove("X-Powered-By");

    //X-Content-Type-Options 'nosniff' ezarri
    header("X-Content-Type-Options: nosniff");
	
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
			
					<center> <h1> AUTOA MODIFIKATU </h1> </center> <br>

					<!-- Formularioa egingo dugu autoaren datuak aldatzeko -->
					
					<form id="formularioa" action="php/auto_modifikatu_be.php" method="POST">
					<input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">
						<none><input name="autoId" id="autoId" value="<?php echo $autoId; ?>"></input></none>
						IRUDIA: <input type="text" id="irudia" placeholder="Sartu irudiaren URL berria" name="irudia" value="<?php echo $resultIrudia; ?>"> <br>
						MARKA: <input type="text" id="marka" placeholder="Autoaren marka berria jarri" name="marka" value="<?php echo $resultMarka; ?>"> <br>
						IZENA: <input type="text" id="izena" placeholder="Autoaren izen berria jarri" name="izena" value="<?php echo $resultIzena; ?>"> <br>
						POTENTZIA: <input type="number" id="potentzia" placeholder="Autoaren potentzia berria jarri" name="potentzia" value="<?php echo $resultPotentzia; ?>"> <br>
						PREZIOA: <input type="number" id="prezioa" placeholder="Autoaren prezioa berria jarri" name="prezioa" value="<?php echo $resultPrezioa; ?>"> <br> <br>
						
						<button id="buttonEginda" type="button"> AUTOA MODIFIKATU </button>
						<button id="buttonHasiera" type="button"> HASIERARA BUELTATU </button>
					
					</form>

                    <form id="autoEzabaketaForm" action="php/auto_ezabatu_be.php" method="POST">
						<input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">	
						<none><input name="autoId" id="autoId" value="<?php echo $autoId; ?>"></input></none>
                        <button id="buttonEzabatu" type="button"> AUTOA EZABATU </button>
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
		const tokia = 'autoaModifikatu.php'

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

			//Konprobaketak egin ondoren eta dena ondo badago, formularioa bidaliko dugu autoaren datuak aldatzeko
			
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
			var buttonEzabatu = document.getElementById('buttonEzabatu');

			if (buttonEzabatu) {
				buttonEzabatu.addEventListener('click', function () {
					baieztatu();
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