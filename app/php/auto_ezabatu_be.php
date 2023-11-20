<?php

    include 'konexioa_be.php';

    //Autoaren datuak hartu

    $autoId = $_POST['autoId'];
    
    $query = "DELETE FROM autoak WHERE id = $autoId";

    //Autoa modifikatu

    $exekutatu = mysqli_query($konexioa, $query);

    if ($exekutatu){
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

    mysqli_close($konexioa);

?>