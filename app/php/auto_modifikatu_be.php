<?php

    include 'konexioa_be.php';

    //Autoaren datuak hartu

    $autoId = $_POST['autoId'];
    $irudia = $_POST['irudia'];
    $marka = $_POST['marka'];
    $izena = $_POST['izena'];
    $potentzia = $_POST['potentzia'];
    $prezioa = $_POST['prezioa'];
    
    $query = "UPDATE autoak SET irudia = ?, marka = ?, izena = ?, potentzia = ?, prezioa = ? WHERE id = ?";

    $stmt = $konexioa->prepare($query);

    $stmt->bind_param("sssiii", $irudia, $marka, $izena, $potentzia, $prezioa, $autoId);

    $stmt->execute();

    //Autoa modifikatu

    if ($stmt){
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

    $stmt->close();
    $konexioa->close();

?>