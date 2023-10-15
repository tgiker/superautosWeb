<?php

	include 'php/konexioa_be.php';

	//Sesioa hasiko dugu eta ondoren konprobatuko dugu erabiltzailea web sisteman sartuta dagoen edo ez.
	session_start();

	if (!isset($_SESSION['erabiltzaile'])){
		echo '		
			<script>
				deslogeatu();
			</script>
		';
	}
	else{
		$username = $_SESSION['erabiltzaile'];
		echo "<div id='erabil' data-izena='$username'></div>";
		echo '		
			<script>
				logeatu();
			</script>
		';
	}

	//Auto guztien informazioa gordeko ditugu

	$autoa = mysqli_query($konexioa, "SELECT * FROM autoak");

	$rows = mysqli_fetch_all($autoa, MYSQLI_ASSOC);

?>
<!DOCTYPE html>
<html>

	<head>
	
		<link rel="stylesheet" href="styles.css">
	
		<link href="https://fonts.googleapis.com/css2?family=Open+Sans" rel="stylesheet" type="text/css" />
	
		<title>SUPERAUTOS</title>

		<script type="text/javascript"> 

			function logeatu() {

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

				//Funtzio honekin adieraziko diogu erabiltzaileari ez dagoela sartuta web sisteman
				let logeatugabe = document.getElementById("logeatugabe");
				let logeatuta = document.getElementById("logeatuta");
				
				logeatugabe.style.display = 'inline';
				logeatuta.style.display = 'none';

				return true;
			}
			
		</script>
		
	</head>

	<body>

		<table>
		
			<tr height=5%>
		
				<td width=99%>
		
					<center> 
					<h1> <font color=white size=8> SUPERAUTOS </font> </h1>
					<h2> <font color=black size=4> HASIERA </font> </h2> 
					</center>
		
				</td>
		
				<td class="eskuinekoZutabeak" width=10%>
		
					<form action="php/login_erabiltzailea_be.php" method="POST" id="logeatugabe" style="display:inline">
					
						<!-- Erabiltzailea sisteman sartzeko formularioa da. Erabiltzailea bere erabiltzaile izena eta pasahitza sartu beharko du eta es badago erregistratuta "ERREGISTRATU" botoiari emango dio -->
						<center> <font color=white size=8> SARBIDEA </font> </center> <br>					
						
						<input type="text" name="erabiltzaileIzena" placeholder="Erabiltzaile izena jarri"> <font color=white> </font> <br>
						<input type="password" name="pasahitza" placeholder="Pasahitza jarri"> <br>
						
						<button type="submit"> SARTU </button> <br>
						
						<center> <font color=white size=4> EZ ZAUDE ERREGISTRATUTA? </font> <br>
						
						<button onclick="window.location.href = 'login.php';" type="button"> ERREGISTRATU </button> </center>
					
					</form>

					<div  id="logeatuta" style="display:none">

						<!-- Erabiltzailea sisteman sartuta dagoela adierazteko formularioa da. Hemen saioa itxi edo area pertsonalera joan ahalko du-->
						<form action="php/deslogeatu_erabiltzailea_be.php" method="POST">
				
							<center> 
							
							<font color=white size=8 id="logeatutaText" value="LOGEATUTA"> LOGEATUTA </font>  <br> 				
							
							<button type="submit"> SAIOA ITXI </button>
							
							</center>
						
						</form>

						<form action="areaPertsonala.php" method="POST">
							<center> <button type="submit"> AREA PERTSONALA </button> <br> </center>
						</form>

					</div>
		
				</td>
		
			</tr>
		
		</table>

		<table class="eskuinekoZutabeak" id="taulaDefault" width=100% height=50%>
			<!-- Taula honetan datu basean sartutako auto guztiak adieraziko diran web orrian eta bere informazioa adieraziko dugu. -->
			<tr>
				<th> <center> <font color=white size=8> Irudia </font> </center> </th>
				<th> <center> <font color=white size=8> Marka </font> </center> </th>
				<th> <center> <font color=white size=8> Izena </font> </center> </th>
				<th> <center> <font color=white size=8> Prezioa (€) </font> </center> </th>
				<?php
					//Konprobatuko dugu erabiltzailea saioa ireki duen edo ez. Erabiltzailea administratzailea bada auto berriak erregistratuko ahalko ditu eta erabiltzailea admin ez bada Erosi textua agertuko zaio.
					//Erabiltzailea ez bada sisteman sartu ez saio ezer agertuko. 
					if (isset($_SESSION['erabiltzaile'])){
						if ($_SESSION['erabiltzaile'] != "admin"){
							echo '<th> <center> <font color=white size=8> Erosi </font> </center> </th>';
						}
						else{
							echo '<th> <button type="button" onclick="window.location.href = \'autoaSartu.php\';">AUTOA ERREGISTRATU</button> </th>';
						}
					}
				?>
				
			</tr>

			<?php foreach ($rows as $row): ?>
			<tr>
			<form action="autoaModifikatu.php" method="POST">
				<input name="autoId" id="autoId" value="<?php echo $row['id']; ?>" style="display:none"></input>
				<input name="autoMarka" id="autoMarka" value="<?php echo $row['marka']; ?>" style="display:none"></input>
				<input name="autoIzena" id="autoIzena" value="<?php echo $row['izena']; ?>" style="display:none"></input>
				<td width=17% height=50%><center><img width=100% height=100% src="<?php echo $row['irudia']; ?>" /></center></td>
				<td width=16% height=50%><center><font color=white size=6><?php echo $row['marka']; ?></center></font></td>
				<td width=16% height=50%><center><font color=white size=6><?php echo $row['izena']; ?></center></font></td>
				<td width=16% height=50%><center><font color=white size=6><?php echo $row['prezioa']; ?></center></font></td>
				<?php
					//Konprobatuko dugu erabiltzailea saioa ireki duen edo ez. Erabiltzailea administratzailea bada auto modifikatu ahalko ditu eta erabiltzailea admin ez bada Erosi botoia agertuko zaio.
					//Erabiltzailea ez bada sisteman sartu ez saio ezer agertuko. 
					if (isset($_SESSION['erabiltzaile'])){
						if ($_SESSION['erabiltzaile'] != "admin"){
							echo '<td width="16%" height="50%"><center><button type="button" onclick="location.href=\'' . $row["irudia"] . '\'">EROSI</button></center></td>';
						}
						else{
							echo '<td width=16% height=50%><center><button type="submit">MODIFIKATU</button></center></td>';
						}
					}
				?>
			</form>
			</tr>
			<?php endforeach; ?>

		</table>

	</body>

</html>