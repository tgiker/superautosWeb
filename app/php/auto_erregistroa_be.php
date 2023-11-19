<?php

    session_start();

    include 'konexioa_be.php';

	//X-Frame-Options konfigurazioa
	header('X-Frame-Options: DENY');
	//Anti-Clickjaking konfigurazioa
	header("Content-Security-Policy: frame-ancestors 'self'");

    //Konprobatzen dugu POST metodoa erabili dela
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        //POST metodoarekin anti-CSRF token-a lortzen dugu.
        $tokenBidalita = isset($_POST['csrf_token']) ? $_POST['csrf_token'] : '';

        //erabiltzailea gordetako anti-CSRF token-a lortzen dugu.
        $tokenGordeta = isset($_SESSION['csrf_token']) ? $_SESSION['csrf_token'] : '';

        //Bi tokenak konparatu
        if (hash_equals($tokenGordeta, $tokenBidalita)) {

            //Autoaren datuak hartu

            $irudia = $_POST['irudia'];
            $marka = $_POST['marka'];
            $izena = $_POST['izena'];
            $potentzia = $_POST['potentzia'];
            $prezioa = $_POST['prezioa'];
            
            $query = "INSERT INTO autoak(irudia, marka, izena, prezioa, potentzia) 
                    VALUES(?, ?, ?, ?, ?)";

            //Konprobatu autoaren marka eta izena ez direla errepikatzen datu basean

            $konprobatu_markaIzena_q = "SELECT * FROM autoak WHERE marka=? and izena=? ";

            $konprobatu_markaIzena_stmt = $konexioa->prepare($konprobatu_markaIzena_q);
            $konprobatu_markaIzena_stmt->bind_param("ss", $marka, $izena);
            $konprobatu_markaIzena_stmt->execute();
            $konprobatu_markaIzena = $konprobatu_markaIzena_stmt->get_result();

            $konprobatu_markaIzena_stmt->close();

            if (mysqli_num_rows($konprobatu_markaIzena) > 0){
                echo '
                <script>
                    alert("Ezin da autoa modifikatu. Autoa jadanik erregistratuta zegoen.");
                    window.location = "../autoaSartu.php";
                </script>
                ';
                exit();
            }

            //Autoa erregistratu

            $stmt = $konexioa->prepare($query);

            $stmt->bind_param("sssii", $irudia, $marka, $izena, $prezioa, $potentzia);

            $stmt->execute();

            if ($stmt){
                echo '
                <script>
                    alert("Autoa erregistratu da!");
                    window.location = "../hasiera.php";
                </script>
                ';
            }
            else{
                echo '
                <script>
                    alert("Ezin da autoa erregistratu. Saiatu berriro geroago");
                    window.location = "../login.php";
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