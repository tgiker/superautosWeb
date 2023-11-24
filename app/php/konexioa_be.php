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
        //Datu basearekin konektatuko gara
        include 'konexio_aldagaiak.php';

        $konexioa = mysqli_connect($db_config['host'], $db_config['erabiltzailea'], $db_config['pasahitza'], $db_config['datubase_izena']);

    } catch (Exception $e) {
        echo "Error. Mesedez saiatu berriro geroago";
        //500 errorea adierazi
        header("HTTP/1.1 500 Internal Server Error");
        include("error500.html");
        exit;
    }

?>