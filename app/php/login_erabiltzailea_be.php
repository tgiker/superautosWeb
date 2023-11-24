<?php

    ini_set('display_errors', 0);

    //SameSite=Strict ezarri
    session_get_cookie_params()['samesite'] = 'Strict';

    //HttpOnly ezarri erasoak saihesteko
	session_set_cookie_params(0, '/', '', false, true);
	
    include 'konexioa_be.php';

	//X-Frame-Options konfigurazioa
	header('X-Frame-Options: DENY');

    //X-Powered-By goiburua kendu informazioa ez zabaltzeko
	header_remove("X-Powered-By");

    //X-Content-Type-Options 'nosniff' ezarri
    header("X-Content-Type-Options: nosniff");

    //nonce sortu
    $nonce = base64_encode(random_bytes(16));

    //CSP konfigurazioa
    header("Content-Security-Policy: script-src 'self' 'nonce-$nonce'; style-src 'self' 'nonce-$nonce' https://fonts.googleapis.com; frame-ancestors 'self'; form-action 'self'; img-src 'self'; connect-src 'self'; frame-src 'self'; font-src 'self' https://fonts.googleapis.com https://fonts.gstatic.com; media-src 'self'; object-src 'self'; manifest-src 'self';");

    try{

        session_start();

        //Konprobatzen dugu POST metodoa erabili dela
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            //POST metodoarekin anti-CSRF token-a lortzen dugu.
            $tokenBidalita = isset($_POST['csrf_token']) ? $_POST['csrf_token'] : '';

            //erabiltzailea gordetako anti-CSRF token-a lortzen dugu.
            $tokenGordeta = isset($_SESSION['csrf_token']) ? $_SESSION['csrf_token'] : '';

            //Bi tokenak konparatu
            if (hash_equals($tokenGordeta, $tokenBidalita)) {
                //Erabiltzailearen datuak hartu

                $erabiltzaileIzena = $_POST['erabiltzaileIzena'];
                $pasahitza = $_POST['pasahitza'];

                //Konprobatuko dugu erabiltzailea eta pasahitza bat etortzen diren

                $pasahitza_hash_q = "SELECT pasahitza FROM erabiltzaileak WHERE erabiltzaileIzena=?";
                $stmt = $konexioa->prepare($pasahitza_hash_q);
                $stmt->bind_param("s", $erabiltzaileIzena);
                $stmt->execute();
                $stmt->bind_result($pasahitza_hash);

                if ($stmt->fetch()) {
                    if (password_verify($pasahitza, $pasahitza_hash)){
                        //Saio-identifikatzailea leheneratzen du. Horrela segurtasun arriskuak saihesten ditugu
                        session_regenerate_id(true);
                        $_SESSION['erabiltzaile'] = $erabiltzaileIzena;
                        echo "
                        <script nonce='$nonce'>
                            window.location = '../hasiera.php';
                        </script>
                        ";
                    }
                    else{
                        $_SESSION['aurrekoLogeatuKont'] = 1;
                        echo "
                        <script nonce='$nonce'>
                            alert('Erabiltzaile izena eta pasahitza ez datoz bat');
                            window.location = '../hasiera.php';
                        </script>
                        ";
                        
                    }
                } else {
                    $_SESSION['aurrekoLogeatuKont'] = 1;
                    echo "
                        <script nonce='$nonce'>
                            alert('Erabiltzaile izena eta pasahitza ez datoz bat');
                            window.location = '../hasiera.php';
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

    } catch (Exception $e) {
        echo "Error. Mesedez saiatu berriro geroago";
        //500 errorea adierazi
        header("HTTP/1.1 500 Internal Server Error");
        include("error500.html");
        exit;
    }

?>