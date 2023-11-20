<?php

    session_start();

    include 'konexioa_be.php';

    //Erabiltzailearen datuak hartu

    $erabiltzaileIzena = $_POST['erabiltzaileIzena'];
    $pasahitza = $_POST['pasahitza'];

    //Konprobatuko dugu erabiltzailea eta pasahitza bat etortzen diren

    $login_konprobatu = mysqli_query($konexioa, "SELECT * FROM erabiltzaileak WHERE erabiltzaileIzena='$erabiltzaileIzena' and pasahitza='$pasahitza' ");

    if (mysqli_num_rows($login_konprobatu) > 0){
        $_SESSION['erabiltzaile'] = $erabiltzaileIzena;
        echo '
        <script>
            window.location = "../hasiera.php";
        </script>
        ';
        exit();
    }
    else{
        echo '
        <script>
            alert("Erabiltzaile izena eta pasahitza ez datoz bat");
            window.location = "../hasiera.php";
        </script>
        ';
        exit();
    }
?>