<?php

function connect_db() {
    $server = "localhost";
    $user = "root";
    $pwd_db = "";
    $db = "proj_final";

    $conn = new PDO("mysql:host=$server;dbname=$db;charset=utf8",$user,$pwd_db);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $conn;
}

?>