<?php

    session_start();
    ob_start();

    include "config.php";

    session_unset();
    session_destroy();
    header("location: ../index.php");
?>
