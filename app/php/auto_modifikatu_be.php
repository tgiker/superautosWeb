<?php

    include 'konexioa_be.php';

    //Autoaren datuak hartu

    $autoId = $_POST['autoId'];
    $irudia = $_POST['irudia'];
    $marka = $_POST['marka'];
    $izena = $_POST['izena'];
    $potentzia = $_POST['potentzia'];
    $prezioa = $_POST['prezioa'];
    
    $query = "UPDATE autoak SET irudia = '$irudia', marka = '$marka', izena = '$izena', potentzia = '$potentzia', prezioa = '$prezioa' WHERE id = $autoId";

    //Autoa modifikatu

    $exekutatu = mysqli_query($konexioa, $query);

    if ($exekutatu){
        echo '
        <script>
            alert("Autoa modifikatu da!");
            window.location = "../hasiera.php";
        </script>
        ';
    }
    else{
        echo '
        <script>
            alert("Ezin da autoa modifikatu. Saiatu berriro geroago");
            window.location = "../autoaModifikatu.php";
        </script>
        ';
    }

    mysqli_close($konexioa);

?>