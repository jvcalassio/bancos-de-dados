<?php

session_start();
if(!isset($_SESSION['bd-session'])) {
    header('location: login.php');
    return;    
}

require('db/conn.php');
?>
<!doctype html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="css/bootstrap.min.css">

    <title>Meus cursos</title>
    <style>
    .profile-pic {
        border-radius: 50%;
        margin-right: 40px;
        border: 1px solid #000;
    }
    </style>
  </head>
  <body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
        <li class="nav-item">
            <a class="nav-link" href="index.php">Meus cursos</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="lista_cursos.php">Cursos</a>
        </li>
        <li class="nav-item active">
            <a class="nav-link" href="perfil.php">Perfil</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="logout.php">Sair</a>
        </li>
    </div>
    </nav>
    <div class="container my-5">
        <?php
            $conn = connect_db();
            
            $myid = $_SESSION['bd-session'];

            if(isset($_GET['id'])) {
                $usr_id = $_GET['id'];
            } else {
                $usr_id = $myid;
            }
            
            $smt = $conn->prepare("SELECT
                                        nome, email, DATE_FORMAT(data_nascimento, '%d/%m/%Y') as data_nascimento, descricao, foto
                                    FROM
                                        usuario
                                    WHERE id = :u_id");
            $smt->bindParam(':u_id', $usr_id);
            $smt->execute();
            $usuario = $smt->fetch();
            
            if($usuario != ''):
        ?>
        <h2 class="my-5"><img src="<?php if($usuario['foto'] == '') : echo 'img/profile.png'; else: echo $usuario['foto']; endif; ?>" class="profile-pic" /><?php echo $usuario['nome']; ?></h2>
        <p><strong>Email:</strong> <?php echo $usuario['email']; ?></p>
        <p><strong>Data de nascimento:</strong> <?php echo $usuario['data_nascimento']; ?></p>
        <?php if($usuario['descricao'] != '') : ?>
            <p class="lead font-italic">&quot;<?php echo $usuario['descricao']; ?>&quot;</p>
        <?php endif; if($myid == $usr_id) : ?>
            <hr />
            <a href="editar_perfil.php">Editar perfil</a>
        <?php endif;
            else:
                header('location: 404.php');
            endif;
        ?>
    </div>

    <script src="js/jquery-3.5.1.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
  </body>
</html>