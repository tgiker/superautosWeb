<?php

    include 'konexioa_be.php';

    //Autoaren datuak hartu

    $autoId = $_POST['autoId'];
    
    $query = "DELETE FROM autoak WHERE id = ?";

    $stmt = $konexioa->prepare($query);

    $stmt->bind_param("i", $autoId);

    $stmt->execute();

    //Autoa ezabatu

    if ($stmt){
        echo '
        <script>
            alert("Autoa ezabatu da!");
            window.location = "../hasiera.php";
        </script>
        ';
    }
    else{
        echo '
        <script>
            alert("Ezin da autoa ezabatu. Saiatu berriro geroago");
            window.location = "../autoaModifikatu.php";
        </script>
        ';
    }

    $stmt->close();
    $konexioa->close();

?>