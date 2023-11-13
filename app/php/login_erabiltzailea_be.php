<?php

    session_start();

    include 'konexioa_be.php';

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
            $_SESSION['erabiltzaile'] = $erabiltzaileIzena;
            echo '
            <script>
                window.location = "../hasiera.php";
            </script>
            ';
        }
        else{
            echo '
            <script>
                alert("Erabiltzaile izena eta pasahitza ez datoz bat");
                window.location = "../hasiera.php";
            </script>
            ';
            
        }
    } else {
        echo '
            <script>
                alert("Erabiltzaile izena eta pasahitza ez datoz bat");
                window.location = "../hasiera.php";
            </script>
            ';
    }

    $stmt->close();
    $konexioa->close();

    exit();

?>