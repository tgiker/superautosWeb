<?php

    session_start();

    include 'konexioa_be.php';

    //Konprobatzen dugu POST metodoa erabili dela
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        //POST metodoarekin anti-CSRF token-a lortzen dugu.
        $tokenBidalita = isset($_POST['csrf_token']) ? $_POST['csrf_token'] : '';

        //erabiltzailea gordetako anti-CSRF token-a lortzen dugu.
        $tokenGordeta = isset($_SESSION['csrf_token']) ? $_SESSION['csrf_token'] : '';

        //Bi tokenak konparatu
        if (hash_equals($tokenGordeta, $tokenBidalita)) {
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

            unset($_SESSION['csrf_token']);

            exit();

        } else {
            //tokenak ez dira berdinak beraz CSRF eraso bat izan daiteke
            http_response_code(403);
            die('Ezin da prozesatu');
        }
    }

    

?>