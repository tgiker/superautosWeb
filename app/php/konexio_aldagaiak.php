<?php

    //X-Frame-Options konfigurazioa
	header('X-Frame-Options: DENY');

    //nonce sortu
    $nonce = base64_encode(random_bytes(16));

    //CSP konfigurazioa
    header("Content-Security-Policy: script-src 'self' 'nonce-$nonce'; style-src 'self' 'nonce-$nonce' https://fonts.googleapis.com; frame-ancestors 'self'; form-action 'self'; img-src 'self'; connect-src 'self'; frame-src 'self'; font-src 'self' https://fonts.googleapis.com https://fonts.gstatic.com; media-src 'self'; object-src 'self'; manifest-src 'self';");

    $db_config = [
        'host' => 'localhost',
        'erabiltzailea' => 'root',
        'pasahitza' => '',
        'datubase_izena' => 'database',
    ];

?>