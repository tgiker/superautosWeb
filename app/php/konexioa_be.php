<?php
    //Datu basearekin konektatuko gara

    include 'konexio_aldagaiak.php';

    $konexioa = mysqli_connect($db_config['host'], $db_config['erabiltzailea'], $db_config['pasahitza'], $db_config['datubase_izena']);

?>
