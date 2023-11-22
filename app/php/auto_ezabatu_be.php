<?php

    session_start();

    include 'konexioa_be.php';

	//X-Frame-Options konfigurazioa
	header('X-Frame-Options: DENY');

    //nonce sortu
    $nonce = base64_encode(random_bytes(16));

    //CSP konfigurazioa
    header("Content-Security-Policy: script-src 'self' 'nonce-$nonce'; style-src 'self' 'nonce-$nonce' https://fonts.googleapis.com; frame-ancestors 'self'; form-action 'self'; img-src 'self'; connect-src 'self'; frame-src 'self'; font-src 'self' https://fonts.googleapis.com https://fonts.gstatic.com; media-src 'self'; object-src 'self'; manifest-src 'self';");

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
            
            $query = "DELETE FROM autoak WHERE id = ?";

            $stmt = $konexioa->prepare($query);

            $stmt->bind_param("i", $autoId);

            $stmt->execute();

            //Autoa ezabatu

            if ($stmt){
                echo "
                <script nonce='$nonce'>
                    alert('Autoa ezabatu da!');
                    window.location = '../hasiera.php';
                </script>
                ";
            }
            else{
                echo "
                <script nonce='$nonce'>
                    alert('Ezin da autoa ezabatu. Saiatu berriro geroago');
                    window.location = '../autoaModifikatu.php';
                </script>
                ";
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