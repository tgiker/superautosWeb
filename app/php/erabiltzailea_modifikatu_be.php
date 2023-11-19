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

            $izena_abizenak = $_POST['izen_abizenak'];
            $nan = $_POST['nan'];
            $telefonoa = $_POST['telefonoa'];
            $jaiotze_data = $_POST['jaiotze_data'];
            $emaila = $_POST['emaila'];
            $pasahitza = $_POST['pasahitza'];
            $erabiltzaileIzena = $_POST['erabId'];

            //pasahitza laburtuko (hash) dugu
            $pasahitza_hash = password_hash($pasahitza, PASSWORD_BCRYPT);

            //kontsulta prestatuko dugu
            
            $query = "UPDATE erabiltzaileak SET izen_abizenak = ?, nan = ?, 
            telefonoa = ?, jaiotze_data = ?, 
            email = ?, pasahitza = ? 
            WHERE erabiltzaileIzena = ?";

            //Konprobatu email-a eta NAN ez direla errepikatzen datu basean

            $konprobatu_emaila_q = "SELECT email FROM erabiltzaileak WHERE erabiltzaileIzena = ?";

            $konprobatu_emaila_stmt = $konexioa->prepare($konprobatu_emaila_q);
            $konprobatu_emaila_stmt->bind_param("s", $erabiltzaileIzena);
            $konprobatu_emaila_stmt->execute();
            $konprobatu_emaila_stmt->bind_result($resultEmail);    

            if ($konprobatu_emaila_stmt->fetch())
            {
                if ($resultEmail != $emaila)
                {
                    $konprobatu_emaila_stmt->close();

                    $konprobatu_emaila_q = "SELECT * FROM erabiltzaileak WHERE email = ?";

                    $konprobatu_emaila_stmt = $konexioa->prepare($konprobatu_emaila_q);
                    $konprobatu_emaila_stmt->bind_param("s", $emaila);
                    $konprobatu_emaila_stmt->execute();
                    $konprobatu_emaila = $konprobatu_emaila_stmt->get_result();

                    $konprobatu_emaila_stmt->close();

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
            }
            else
            {
                echo '
                <script>
                    alert("Ezin da erabiltzailea erregistratu. Saiatu geroago mesedez.");
                    window.location = "../areaPertsonala.php";
                </script>
                ';
                exit();
            }

            $konprobatu_emaila_stmt->close();

            //Orain NAN konprobatu
            
            $konprobatu_nan_q = "SELECT nan FROM erabiltzaileak WHERE erabiltzaileIzena = ?";

            $konprobatu_nan_stmt = $konexioa->prepare($konprobatu_nan_q);
            $konprobatu_nan_stmt->bind_param("s", $erabiltzaileIzena);
            $konprobatu_nan_stmt->execute();
            $konprobatu_nan_stmt->bind_result($resultNan);

            if ($konprobatu_nan_stmt->fetch())
            {
                if ($resultNan != $nan)
                {
                    $konprobatu_nan_stmt->close();

                    $konprobatu_nan_q = "SELECT * FROM erabiltzaileak WHERE nan = ?";

                    $konprobatu_nan_stmt = $konexioa->prepare($konprobatu_nan_q);
                    $konprobatu_nan_stmt->bind_param("s", $nan);
                    $konprobatu_nan_stmt->execute();
                    $konprobatu_nan = $konprobatu_nan_stmt->get_result();

                    $konprobatu_nan_stmt->close();

                    if (mysqli_num_rows($konprobatu_nan) > 0)
                    {
                        echo '
                        <script>
                            alert("Ezin da erabiltzailea erregistratu. NAN-a jadanik erregistratuta zegoen. Sartu beste NAN bat mesedez.");
                            window.location = "../areaPertsonala.php";
                        </script>
                        ';
                        exit();
                    }
                }
            }
            else
            {
                echo '
                <script>
                    alert("Ezin da erabiltzailea erregistratu. Saiatu geroago mesedez.");
                    window.location = "../areaPertsonala.php";
                </script>
                ';
                exit();
            }

            $konprobatu_nan_stmt->close();
            
            //Erabiltzailea modifikatu

            $stmt = $konexioa->prepare($query);

            $stmt->bind_param("sssssss", $izena_abizenak, $nan, $telefonoa, $jaiotze_data, $emaila, $pasahitza_hash, $erabiltzaileIzena);

            $stmt->execute();

            if ($stmt){
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