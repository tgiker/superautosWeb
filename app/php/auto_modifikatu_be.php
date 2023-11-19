<?php

    session_start();

    include 'konexioa_be.php';

	//X-Frame-Options konfigurazioa
	header('X-Frame-Options: DENY');

    //Konprobatzen dugu POST metodoa erabili dela
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        //POST metodoarekin anti-CSRF token-a lortzen dugu.
        $tokenBidalita = isset($_POST['csrf_token']) ? $_POST['csrf_token'] : '';

        //erabiltzailea gordetako anti-CSRF token-a lortzen dugu.
        $tokenGordeta = isset($_SESSION['csrf_token']) ? $_SESSION['csrf_token'] : '';

        //Bi tokenak konparatu
        if (hash_equals($tokenGordeta, $tokenBidalita)) {

            //Autoaren datuak hartu

            $autoId = $_POST['autoId'];
            $irudia = $_POST['irudia'];
            $marka = $_POST['marka'];
            $izena = $_POST['izena'];
            $potentzia = $_POST['potentzia'];
            $prezioa = $_POST['prezioa'];
            
            $query = "UPDATE autoak SET irudia = ?, marka = ?, izena = ?, potentzia = ?, prezioa = ? WHERE id = ?";

            $stmt = $konexioa->prepare($query);

            $stmt->bind_param("sssiii", $irudia, $marka, $izena, $potentzia, $prezioa, $autoId);

            $stmt->execute();

            //Autoa modifikatu

            if ($stmt){
                echo '
                <script>
                    alert("Autoa modifikatu da!");
                    window.location = "../hasiera.php";
                </script>
                ';
            }
            else{
                echo '
                <script>
                    alert("Ezin da autoa modifikatu. Saiatu berriro geroago");
                    window.location = "../autoaModifikatu.php";
                </script>
                ';
            }

            $stmt->close();
            $konexioa->close();

            unset($_SESSION['csrf_token']);

            exit();

        } else {
            //tokenak ez dira berdinak beraz CSRF eraso bat izan daiteke
            http_response_code(403);
            die('Ezin da prozesatu');
        }
    }

?>