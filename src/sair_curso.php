<?php

session_start();
if(!isset($_SESSION['bd-session'])) {
    header('location: login.php');
    return;    
}

if(isset($_GET['id'])) {
    require('db/conn.php');

    $conn = connect_db();
    $myid = $_SESSION['bd-session'];
    $curso_id = $_GET['id'];

    $smt = $conn->prepare("DELETE FROM curso_usuario WHERE curso_id = :curso_id AND usuario_id = :usr_id");
    $smt->bindParam(':curso_id', $curso_id);
    $smt->bindParam(':usr_id', $myid);
    $smt->execute();
}
header('location: index.php');
?>