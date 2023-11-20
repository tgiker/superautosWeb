<?php

    session_start();

    include 'konexioa_be.php';

	//X-Frame-Options konfigurazioa
	header('X-Frame-Options: DENY');

    //Konprobatzen dugu POST metodoa erabili dela
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        //POST metodoarekin anti-CSRF token-a lortzen dugu.
        $tokenBidalita = isset($_POST['csrf_token']) ? $_POST['csrf_token'] : '';

        //erabiltzailea gordetako anti-CSRF token-a lortzen dugu.
        $tokenGordeta = isset($_SESSION['csrf_token']) ? $_SESSION['csrf_token'] : '';

        //Bi tokenak konparatu
        if (hash_equals($tokenGordeta, $tokenBidalita)) {

            //Erabiltzailearen datuak hartu

            $izena_abizenak = $_POST['izen_abizenak'];
            $nan = $_POST['nan'];
            $telefonoa = $_POST['telefonoa'];
            $jaiotze_data = $_POST['jaiotze_data'];
            $emaila = $_POST['emaila'];
            $pasahitza = $_POST['pasahitza'];
            $erabiltzaileIzena = $_POST['erabiltzaileIzena'];

            //nonce sortu
            $nonce = base64_encode(random_bytes(16));

            //CSP konfigurazioa
	        header("Content-Security-Policy: script-src 'self' 'nonce-$nonce'; style-src 'self' 'nonce-$nonce' https://fonts.googleapis.com; frame-ancestors 'self'; form-action 'self'; img-src 'self' data: https://*; connect-src 'self'; frame-src 'self'; font-src 'self' https://fonts.googleapis.com https://fonts.gstatic.com; media-src 'self'; object-src 'self'; manifest-src 'self';");

            //pasahitza laburtuko (hash) dugu
            $pasahitza_hash = password_hash($pasahitza, PASSWORD_BCRYPT);

            //kontsulta prestatu
            
            $query = "INSERT INTO erabiltzaileak(izen_abizenak, nan, telefonoa, jaiotze_data, email, pasahitza, erabiltzaileIzena) 
                    VALUES(?, ?, ?, ?, ?, ?, ?)";

            //Konprobatu erabiltzaile izena, email-a eta NAN ez direla errepikatzen datu basean

            $konprobatu_erabiltzaileIzena_q = "SELECT * FROM erabiltzaileak WHERE erabiltzaileIzena = ?";

            $konprobatu_erabiltzaileIzena_stmt = $konexioa->prepare($konprobatu_erabiltzaileIzena_q);
            $konprobatu_erabiltzaileIzena_stmt->bind_param("s", $erabiltzaileIzena);
            $konprobatu_erabiltzaileIzena_stmt->execute();
            $konprobatu_erabiltzaileIzena = $konprobatu_erabiltzaileIzena_stmt->get_result();

            if (mysqli_num_rows($konprobatu_erabiltzaileIzena) > 0){
                echo "
                <script nonce='$nonce'>
                    alert('Ezin da erabiltzailea erregistratu. Erabiltzaile izena jadanik erregistratuta zegoen. Sartu beste erabiltzaile izen bat mesedez.');
                    window.location = '../login.php';
                </script>
                ";
                exit();
            }
            
            $konprobatu_erabiltzaileIzena_stmt->close();
            

            $konprobatu_emaila_q = "SELECT * FROM erabiltzaileak WHERE email = ?";

            $konprobatu_emaila_stmt = $konexioa->prepare($konprobatu_emaila_q);
            $konprobatu_emaila_stmt->bind_param("s", $emaila);
            $konprobatu_emaila_stmt->execute();
            $konprobatu_emaila = $konprobatu_emaila_stmt->get_result();

            if (mysqli_num_rows($konprobatu_emaila) > 0){
                echo "
                <script nonce='$nonce'>
                    alert('Ezin da erabiltzailea erregistratu. Email-a jadanik erregistratuta zegoen. Sartu beste email bat mesedez.');
                    window.location = '../login.php';
                </script>
                ";
                exit();
            }

            $konprobatu_emaila_stmt->close();

            $konprobatu_nan_q = "SELECT * FROM erabiltzaileak WHERE nan = ?";

            $konprobatu_nan_stmt = $konexioa->prepare($konprobatu_nan_q);
            $konprobatu_nan_stmt->bind_param("s", $nan);
            $konprobatu_nan_stmt->execute();
            $konprobatu_nan = $konprobatu_nan_stmt->get_result();

            if (mysqli_num_rows($konprobatu_nan) > 0){
                echo "
                <script nonce='$nonce'>
                    alert('Ezin da erabiltzailea erregistratu. NAN-a jadanik erregistratuta zegoen. Sartu beste NAN bat mesedez.');
                    window.location = '../login.php';
                </script>
                ";
                exit();
            }

            $konprobatu_nan_stmt->close();  

            //Erabiltzailea erregistratu

            $stmt = $konexioa->prepare($query);

            $stmt->bind_param("sssssss", $izena_abizenak, $nan, $telefonoa, $jaiotze_data, $emaila, $pasahitza_hash, $erabiltzaileIzena);

            $stmt->execute();

            if ($stmt){
                echo "
                <script nonce='$nonce'>
                    alert('Erabiltzailea erregistratu da!');
                    window.location = '../hasiera.php';
                </script>
                ";
            }
            else{
                echo "
                <script nonce='$nonce'>
                    alert('Ezin da erabiltzailea erregistratu. Saiatu berriro geroago');
                    window.location = '../login.php';
                </script>
                ";
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