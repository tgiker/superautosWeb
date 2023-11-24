<?php

	ini_set('display_errors', 0);

	// Configurar SameSite=Strict
    session_get_cookie_params()['samesite'] = 'Strict';

	//HttpOnly ezarri erasoak saihesteko
	session_set_cookie_params(0, '/', '', false, true);

	include 'php/konexioa_be.php';

	try{

		//Saioa hasiko dugu
		session_start();

		//Auto guztien informazioa gordeko ditugu

		$autoa = mysqli_query($konexioa, "SELECT * FROM autoak");

		$rows = mysqli_fetch_all($autoa, MYSQLI_ASSOC);

		if (!isset($_SESSION['aurrekoLogeatuKont'])){
			$_SESSION['aurrekoLogeatuKont'] = 0;
		}

		if (!isset($_SESSION['aurrekoErregistro'])){
			$_SESSION['aurrekoErregistro'] = 0;
		}

		if (!isset($_SESSION['aurrekoErabModifikatu'])){
			$_SESSION['aurrekoErabModifikatu'] = 0;
		}

		if (!isset($_SESSION['aurrekoAutoaErregistratu'])){
			$_SESSION['aurrekoAutoaErregistratu'] = 0;
		}

		if (!isset($_SESSION['aurrekoAutoaModifikatu'])){
			$_SESSION['aurrekoAutoaModifikatu'] = 0;
		}

		if (!isset($_SESSION['aurrekoAutoaEzabatu'])){
			$_SESSION['aurrekoAutoaEzabatu'] = 0;
		}

	} catch (Exception $e) {
		echo "Error. Mesedez saiatu berriro geroago";
        //500 errorea adierazi
		header("HTTP/1.1 500 Internal Server Error");
		include("error500.html");
		exit;
	}

	//nonce sortu
	$nonce = base64_encode(random_bytes(16));

	//CSP konfigurazioa
	header("Content-Security-Policy: script-src 'self' 'nonce-$nonce'; style-src 'self' 'nonce-$nonce' https://fonts.googleapis.com; frame-ancestors 'self'; form-action 'self'; img-src 'self' data: https://*; connect-src 'self'; frame-src 'self'; font-src 'self' https://fonts.googleapis.com https://fonts.gstatic.com; media-src 'self'; object-src 'self'; manifest-src 'self';");

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
	
		<link rel="stylesheet" href="styles.css">
	
		<link href="https://fonts.googleapis.com/css2?family=Open+Sans" rel="stylesheet" type="text/css" />

		<meta http-equiv="Content-Security-Policy" 
		content="default-src 'self'; script-src 'self' 'nonce-<?php echo $nonce; ?>'; 
		style-src 'self' 'nonce-<?php echo $nonce; ?>' https://fonts.googleapis.com; 
		font-src 'self' https://fonts.googleapis.com https://fonts.gstatic.com; 
		img-src 'self' data: https://*; 
		form-action 'self';">
	
		<title>SUPERAUTOS</title>

		<style nonce="<?php echo $nonce; ?>">

		inline {display: inline;}
		none {display: none;}

		</style>

		<?php

			$aurrekoLogeatuKont = $_SESSION['aurrekoLogeatuKont'];
			echo "<div id='kontLog' kontagailua='$aurrekoLogeatuKont'></div>";

			$aurrekoErregistroKont = $_SESSION['aurrekoErregistro'];
			echo "<div id='kont' kontagailua='$aurrekoErregistroKont'></div>";

			$aurrekoErabModifikatu = $_SESSION['aurrekoErabModifikatu'];
			echo "<div id='kontErabMod' kontagailua='$aurrekoErabModifikatu'></div>";

			$aurrekoAutoaErregistratu = $_SESSION['aurrekoAutoaErregistratu'];
			echo "<div id='kontAutoSartu' kontagailua='$aurrekoAutoaErregistratu'></div>";

			$aurrekoAutoaModifikatu = $_SESSION['aurrekoAutoaModifikatu'];
			echo "<div id='kontAutoMod' kontagailua='$aurrekoAutoaModifikatu'></div>";

			$aurrekoAutoaEzabatu = $_SESSION['aurrekoAutoaEzabatu'];
			echo "<div id='kontAutoEzab' kontagailua='$aurrekoAutoaEzabatu'></div>";
			
		?>

		<script nonce="<?php echo $nonce; ?>" type="text/javascript"> 

		try {

			var kontsolaKontagailu = 0;

			const artxiboizena = 'log.json'; 
			const tokia = 'areaPertsonala.php'
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

			if (document.getElementById('kontLog').getAttribute('kontagailua') >= 1) {
				const alertMessage = "Erabiltzaile izena eta pasahitza ez datoz bat";
				logToFile(alertToLog(alertMessage), artxiboizena);
				kontsolaKontagailu++;
				<?php
					$_SESSION['aurrekoLogeatuKont'] = 0;
				?>
			}

			if (document.getElementById('kont').getAttribute('kontagailua') >= 1) {
				const alertMessage = "Erabiltzailea erregistratu da.";
				logToFile(alertToLog(alertMessage), artxiboizena);
				kontsolaKontagailu++;
				<?php
					$_SESSION['aurrekoErregistro'] = 0;
				?>
			}

			if (document.getElementById('kontErabMod').getAttribute('kontagailua') >= 1) {
				const alertMessage = "Erabiltzailea modifikatu da.";
				logToFile(alertToLog(alertMessage), artxiboizena);
				kontsolaKontagailu++;
				<?php
					$_SESSION['aurrekoErabModifikatu'] = 0;
				?>
			}

			if (document.getElementById('kontAutoSartu').getAttribute('kontagailua') >= 1) {
				const alertMessage = "Autoa erregistratu da.";
				logToFile(alertToLog(alertMessage), artxiboizena);
				kontsolaKontagailu++;
				<?php
					$_SESSION['aurrekoAutoaErregistratu'] = 0;
				?>
			}

			if (document.getElementById('kontAutoMod').getAttribute('kontagailua') >= 1) {
				const alertMessage = "Autoa modifikatu da.";
				logToFile(alertToLog(alertMessage), artxiboizena);
				kontsolaKontagailu++;
				<?php
					$_SESSION['aurrekoAutoaModifikatu'] = 0;
				?>
			}

			if (document.getElementById('kontAutoEzab').getAttribute('kontagailua') >= 1) {
				const alertMessage = "Autoa ezabatu da.";
				logToFile(alertToLog(alertMessage), artxiboizena);
				kontsolaKontagailu++;
				<?php
					$_SESSION['aurrekoAutoaEzabatu'] = 0;
				?>
			}

			function logeatu() {

				const alertMessage = "Logeatu zara";
				logToFile(alertToLog(alertMessage), artxiboizena);
				kontsolaKontagailu++;

				//Funtzio honekin adieraziko diogu erabiltzaileari sartuta dagoela web sisteman
			
				let logeatugabe = document.getElementById("logeatugabe");
				let logeatuta = document.getElementById("logeatuta");
				
				logeatugabe.style.display = 'none';
				logeatuta.style.display = 'inline';

				//Erabiltzaile izena lortuko dugu
				var username = document.getElementById('erabil').getAttribute('data-izena');

				window.onload = function() { 
					let logeatutaText = document.getElementById("logeatutaText");
					
					logeatutaText.innerHTML = "Logeatuta zaude " + username + "!";
				}

				return true;
			}

			function deslogeatu() {

				const alertMessage = "Ez zaude logeatuta";
				logToFile(alertToLog(alertMessage), artxiboizena);
				kontsolaKontagailu++;

				//Funtzio honekin adieraziko diogu erabiltzaileari ez dagoela sartuta web sisteman
				let logeatugabe = document.getElementById("logeatugabe");
				let logeatuta = document.getElementById("logeatuta");
				
				logeatugabe.style.display = 'inline';
				logeatuta.style.display = 'none';

				return true;
			}

			document.addEventListener('DOMContentLoaded', function () {
				var buttonErregistratu = document.getElementById('buttonErregistratu');

				if (buttonErregistratu) {
					buttonErregistratu.addEventListener('click', function () {
						window.location.href = 'login.php';
					});
				}
			});
			
			document.addEventListener('DOMContentLoaded', function () {
				var buttonAutoaErregistratu = document.getElementById('buttonAutoaErregistratu');

				if (buttonAutoaErregistratu) {
					buttonAutoaErregistratu.addEventListener('click', function () {
						window.location.href = 'autoaSartu.php';
					});
				}
			});

		} catch {
            System.err.println("Error. Saiatu berriro geroago mesedez.");
        }
			
		</script>
		
	</head>

	<body>

		<table nonce="<?php echo $nonce; ?>" >
		
			<tr nonce="<?php echo $nonce; ?>"  height=5%>
		
				<td  nonce="<?php echo $nonce; ?>" width=99%>
		
					<center> 
					<h1> <font color=white size=8> SUPERAUTOS </font> </h1>
					<h2> <font color=black size=4> HASIERA </font> </h2> 
					</center>
		
				</td>
		
				<td  nonce="<?php echo $nonce; ?>" class="eskuinekoZutabeak" width=10%>
		
					<form  nonce="<?php echo $nonce; ?>" action="php/login_erabiltzailea_be.php" method="POST">
					<inline id="logeatugabe">
						<!-- Erabiltzailea sisteman sartzeko formularioa da. Erabiltzailea bere erabiltzaile izena eta pasahitza sartu beharko du eta es badago erregistratuta "ERREGISTRATU" botoiari emango dio -->
						<center> <font color=white size=8> SARBIDEA </font> </center> <br>					
						
						<input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">
						<input type="text" name="erabiltzaileIzena" placeholder="Erabiltzaile izena jarri"> <font color=white> </font> <br>
						<input type="password" name="pasahitza" placeholder="Pasahitza jarri"> <br>
						
						<button type="submit"> SARTU </button> <br>
						
						<center> <font color=white size=4> EZ ZAUDE ERREGISTRATUTA? </font> <br>
						
						<button nonce="<?php echo $nonce; ?>" id="buttonErregistratu" type="button"> ERREGISTRATU </button> </center>
					</inline>
					</form>

					<div  nonce="<?php echo $nonce; ?>">
					<none id="logeatuta">
						<!-- Erabiltzailea sisteman sartuta dagoela adierazteko formularioa da. Hemen saioa itxi edo area pertsonalera joan ahalko du-->
						<form nonce="<?php echo $nonce; ?>"  action="php/deslogeatu_erabiltzailea_be.php" method="POST">
				
							<center> 
							<input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">
							<font color=white size=8 id="logeatutaText" value="LOGEATUTA"> LOGEATUTA </font>  <br> 				
							
							<button type="submit"> SAIOA ITXI </button>
							
							</center>
						
						</form>

						<form  nonce="<?php echo $nonce; ?>" action="areaPertsonala.php" method="POST">
							<input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">
							<center> <button type="submit"> AREA PERTSONALA </button> <br> </center>
						</form>
					</none>
					</div>
		
				</td>
		
			</tr>
		
		</table>

		<table nonce="<?php echo $nonce; ?>"  class="eskuinekoZutabeak" id="taulaDefault" width=100% height=50%>
			<!-- Taula honetan datu basean sartutako auto guztiak adieraziko diran web orrian eta bere informazioa adieraziko dugu. -->
			<tr nonce="<?php echo $nonce; ?>" >
				<th nonce="<?php echo $nonce; ?>" > <center> <font color=white size=8> Irudia </font> </center> </th>
				<th nonce="<?php echo $nonce; ?>" > <center> <font color=white size=8> Marka </font> </center> </th>
				<th nonce="<?php echo $nonce; ?>" > <center> <font color=white size=8> Izena </font> </center> </th>
				<th nonce="<?php echo $nonce; ?>" > <center> <font color=white size=8> Prezioa (€) </font> </center> </th>
				<?php
					try{
						//Konprobatuko dugu erabiltzailea saioa ireki duen edo ez. Erabiltzailea administratzailea bada auto berriak erregistratuko ahalko ditu eta erabiltzailea admin ez bada Erosi textua agertuko zaio.
						//Erabiltzailea ez bada sisteman sartu ez saio ezer agertuko. 
						if (isset($_SESSION['erabiltzaile'])){
							if ($_SESSION['erabiltzaile'] != "admin"){
								echo "<th nonce='$nonce' class='td-custom'> <center> <font color=white size=8> Erosi </font> </center> </th>";
							}
							else{
								echo "<th nonce='$nonce' class='td-custom'><button nonce='$nonce' type='button' id='buttonAutoaErregistratu'>AUTOA ERREGISTRATU</button></th>";
							}
						}
					} catch (Exception $e) {
						echo "Error. Mesedez saiatu berriro geroago";
        				//500 errorea adierazi
						header("HTTP/1.1 500 Internal Server Error");
						include("error500.html");
						exit;
					}
				?>
				
			</tr>

			<?php foreach ($rows as $row): ?>
			<tr nonce="<?php echo $nonce; ?>" >
			<form nonce="<?php echo $nonce; ?>"  action="autoaModifikatu.php" method="POST">
				<input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8'); ?>">
				<none><input nonce="<?php echo $nonce; ?>"  name="autoId" value="<?php echo $row['id']; ?>"></input></none>
				<none><input nonce="<?php echo $nonce; ?>" name="autoMarka" value="<?php echo $row['marka']; ?>"></input></none>
				<none><input nonce="<?php echo $nonce; ?>" name="autoIzena" value="<?php echo $row['izena']; ?>"></input></none>
				<td  nonce="<?php echo $nonce; ?>" width=17% height=50%><center><img width=100% height=100% src="<?php echo $row['irudia']; ?>" /></center></td>
				<td nonce="<?php echo $nonce; ?>"  width=16% height=50%><center><font color=white size=6><?php echo $row['marka']; ?></center></font></td>
				<td nonce="<?php echo $nonce; ?>"  width=16% height=50%><center><font color=white size=6><?php echo $row['izena']; ?></center></font></td>
				<td nonce="<?php echo $nonce; ?>"  width=16% height=50%><center><font color=white size=6><?php echo $row['prezioa']; ?></center></font></td>
				<?php
					//Konprobatuko dugu erabiltzailea saioa ireki duen edo ez. Erabiltzailea administratzailea bada auto modifikatu ahalko ditu eta erabiltzailea admin ez bada Erosi botoia agertuko zaio.
					//Erabiltzailea ez bada sisteman sartu ez saio ezer agertuko. 

					try{
						if (isset($_SESSION['erabiltzaile'])){
							if ($_SESSION['erabiltzaile'] != "admin"){
								echo "<td nonce='$nonce' class='td-custom'> <center> <button nonce='$nonce' type='button' id=\"" . $row["irudia"] . "\">EROSI</button> </center> </td>";
								echo "<script nonce='$nonce'>
								document.addEventListener('DOMContentLoaded', function () {
									var buttonErosi = document.getElementById(\"" . $row["irudia"] . "\");
					
									if (buttonErosi) {
										buttonErosi.addEventListener('click', function () {
											window.location.href = \"" . $row["irudia"] . "\";
										});
									}
								});
								</script>
								";
							}
							else{
								echo "<td nonce='$nonce' class='td-custom'> <center> <button nonce='$nonce' type='submit'>MODIFIKATU</button> </center> </td>";
							}
						}
					} catch (Exception $e) {
						echo "Error. Mesedez saiatu berriro geroago";
        				//500 errorea adierazi
						header("HTTP/1.1 500 Internal Server Error");
						include("error500.html");
						exit;
					}
				?>
			</form>
			</tr>
			<?php endforeach; ?>

		</table>

		<?php
			try{
				// Konprobatuko dugu erabiltzailea web sisteman sartuta dagoen edo ez.
				if (!isset($_SESSION['erabiltzaile'])){
					echo "		
						<script nonce='$nonce' >
							deslogeatu();
						</script>
						";
				}
				else{
					$username = $_SESSION['erabiltzaile'];
					echo "<div nonce='$nonce'  id='erabil' data-izena='$username'></div>";
					echo "		
						<script nonce='$nonce' >
							logeatu();
						</script>
						";
				}
			} catch (Exception $e) {
				echo "Error. Mesedez saiatu berriro geroago";
        		//500 errorea adierazi
				header("HTTP/1.1 500 Internal Server Error");
				include("error500.html");
				exit;
			}			
		?>

	</body>

</html>