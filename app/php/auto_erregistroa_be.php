<?php

    include 'konexioa_be.php';

    //Autoaren datuak hartu

    $irudia = $_POST['irudia'];
    $marka = $_POST['marka'];
    $izena = $_POST['izena'];
    $potentzia = $_POST['potentzia'];
    $prezioa = $_POST['prezioa'];
    
    $query = "INSERT INTO autoak(irudia, marka, izena, prezioa, potentzia) 
              VALUES('$irudia', '$marka', '$izena', '$prezioa', '$potentzia')";

    //Konprobatu autoaren marka eta izena ez direla errepikatzen datu basean

    $konprobatu_markaIzena = mysqli_query($konexioa, "SELECT * FROM autoak WHERE marka='$marka' and izena='$izena' ");

    if (mysqli_num_rows($konprobatu_markaIzena) > 0){
        echo '
        <script>
            alert("Ezin da autoa modifikatu. Autoa jadanik erregistratuta zegoen.");
            window.location = "../autoaSartu.php";
        </script>
        ';
        exit();
    }

    //Autoa erregistratu

    $exekutatu = mysqli_query($konexioa, $query);

    if ($exekutatu){
        echo '
        <script>
            alert("Autoa erregistratu da!");
            window.location = "../hasiera.php";
        </script>
        ';
    }
    else{
        echo '
        <script>
            alert("Ezin da autoa erregistratu. Saiatu berriro geroago");
            window.location = "../login.php";
        </script>
        ';
    }

    mysqli_close($konexioa);

?>