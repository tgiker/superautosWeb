<?php

    //Erabiltzailearen saioa ixteko
    session_start();
    session_destroy();
    header("location: ../hasiera.php");

?>