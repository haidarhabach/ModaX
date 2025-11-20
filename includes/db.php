<?php
    $DB_HOST = 'localhost';
    $DB_USER = 'root';
    $DB_PASS = '';
    $DB_NAME = 'ModaX';

    $connect = mysqli_connect($DB_HOST,$DB_USER,$DB_PASS,$DB_NAME);

    if(!$connect){
        die("DB connect error: " . mysqli_connect_error());
    }

?>