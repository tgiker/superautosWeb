<?php

    include 'konexioa_be.php';

    //Erabiltzailearen datuak hartu

    $izena_abizenak = $_POST['izen_abizenak'];
    $nan = $_POST['nan'];
    $telefonoa = $_POST['telefonoa'];
    $jaiotze_data = $_POST['jaiotze_data'];
    $emaila = $_POST['emaila'];
    $pasahitza = $_POST['pasahitza'];
    $erabiltzaileIzena = $_POST['erabiltzaileIzena'];
    
    $query = "INSERT INTO erabiltzaileak(izen_abizenak, nan, telefonoa, jaiotze_data, email, pasahitza, erabiltzaileIzena) 
              VALUES('$izena_abizenak', '$nan', '$telefonoa', '$jaiotze_data', '$emaila', '$pasahitza', '$erabiltzaileIzena')";

    //Konprobatu erabiltzaile izena, email-a eta NAN ez direla errepikatzen datu basean

    $konprobatu_erabiltzaileIzena = mysqli_query($konexioa, "SELECT * FROM erabiltzaileak WHERE erabiltzaileIzena='$erabiltzaileIzena' ");
    $konprobatu_emaila = mysqli_query($konexioa, "SELECT * FROM erabiltzaileak WHERE email='$emaila' ");
    $konprobatu_nan = mysqli_query($konexioa, "SELECT * FROM erabiltzaileak WHERE nan='$nan' ");

    if (mysqli_num_rows($konprobatu_erabiltzaileIzena) > 0){
        echo '
        <script>
            alert("Ezin da erabiltzailea erregistratu. Erabiltzaile izena jadanik erregistratuta zegoen. Sartu beste erabiltzaile izen bat mesedez.");
            window.location = "../login.php";
        </script>
        ';
        exit();
    }

    if (mysqli_num_rows($konprobatu_emaila) > 0){
        echo '
        <script>
            alert("Ezin da erabiltzailea erregistratu. Email-a jadanik erregistratuta zegoen. Sartu beste email bat mesedez.");
            window.location = "../login.php";
        </script>
        ';
        exit();
    }

    if (mysqli_num_rows($konprobatu_nan) > 0){
        echo '
        <script>
            alert("Ezin da erabiltzailea erregistratu. NAN-a jadanik erregistratuta zegoen. Sartu beste NAN bat mesedez.");
            window.location = "../login.php";
        </script>
        ';
        exit();
    }

    //Erabiltzailea erregistratu

    $exekutatu = mysqli_query($konexioa, $query);

    if ($exekutatu){
        echo '
        <script>
            alert("Erabiltzailea erregistratu da!");
            window.location = "../hasiera.php";
        </script>
        ';
    }
    else{
        echo '
        <script>
            alert("Ezin da erabiltzailea erregistratu. Saiatu berriro geroago");
            window.location = "../login.php";
        </script>
        ';
    }

    mysqli_close($konexioa);

?>