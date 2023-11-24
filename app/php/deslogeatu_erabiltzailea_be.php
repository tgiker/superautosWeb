<?php

	ini_set('display_errors', 0);

	//HttpOnly ezarri erasoak saihesteko
	session_set_cookie_params(0, '/', '', false, true);

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
                //Erabiltzailearen saioa ixteko
                header("location: ../hasiera.php");
                unset($_SESSION['csrf_token']);
                session_destroy();
                //Saio-identifikatzailea leheneratzen du. Horrela segurtasun arriskuak saihesten ditugu
                session_regenerate_id(true);
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