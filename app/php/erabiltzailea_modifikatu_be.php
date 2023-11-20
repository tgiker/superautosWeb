<?php

    include 'konexioa_be.php';

    //Erabiltzailearen datuak hartu

    $izena_abizenak = $_POST['izen_abizenak'];
    $nan = $_POST['nan'];
    $telefonoa = $_POST['telefonoa'];
    $jaiotze_data = $_POST['jaiotze_data'];
    $emaila = $_POST['emaila'];
    $pasahitza = $_POST['pasahitza'];
    $erabiltzaileIzena = $_POST['erabId'];
    
    $query = "UPDATE erabiltzaileak SET izen_abizenak = '$izena_abizenak', nan = '$nan', 
    telefonoa = '$telefonoa', jaiotze_data = '$jaiotze_data', 
    email = '$emaila', pasahitza = '$pasahitza' 
    WHERE erabiltzaileIzena = '$erabiltzaileIzena'";

    //Konprobatu email-a eta NAN ez direla errepikatzen datu basean

    $konprobatu_emaila = mysqli_query($konexioa, "SELECT * FROM erabiltzaileak WHERE email='$emaila' ");
    $konprobatu_nan = mysqli_query($konexioa, "SELECT * FROM erabiltzaileak WHERE nan='$nan' ");

    $resultErabiltzaile = mysqli_query($konexioa, "SELECT * FROM erabiltzaileak WHERE erabiltzaileIzena = '$erabiltzaileIzena' ");

    $rows = mysqli_fetch_all($resultErabiltzaile, MYSQLI_ASSOC);

    foreach ($rows as $row){
        $resultEmail = $row['email'] ?? '';
        $resultNan = $row['nan'] ?? '';
    }
    
    if ($resultEmail != $emaila){
        if (mysqli_num_rows($konprobatu_emaila) > 0){
            echo '
            <script>
                alert("Ezin da erabiltzailea erregistratu. Email-a jadanik erregistratuta zegoen. Sartu beste email bat mesedez.");
                window.location = "../areaPertsonala.php";
            </script>
            ';
            exit();
        }
    }

    if ($resultNan != $nan){
        if (mysqli_num_rows($konprobatu_nan) > 0){
            echo '
            <script>
                alert("Ezin da erabiltzailea erregistratu. NAN-a jadanik erregistratuta zegoen. Sartu beste NAN bat mesedez.");
                window.location = "../areaPertsonala.php";
            </script>
            ';
            exit();
        }
    }

    //Erabiltzailea modifikatu

    $exekutatu = mysqli_query($konexioa, $query);

    if ($exekutatu){
        echo '
        <script>
            alert("Erabiltzailea modifikatu da!");
            window.location = "../hasiera.php";
        </script>
        ';
    }
    else{
        echo '
        <script>
            alert("Ezin da erabiltzailea modifikatu. Saiatu berriro geroago");
            window.location = "../areaPertsonala.php";
        </script>
        ';
    }

    mysqli_close($konexioa);

?>