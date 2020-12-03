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

    <title>Cursos</title>
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
        <li class="nav-item active">
            <a class="nav-link" href="#">Cursos <span class="sr-only">(current)</span></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="perfil.php">Perfil</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="logout.php">Sair</a>
        </li>
    </div>
    </nav>
    <div class="container my-5">
        <h1 class="my-5">Cursos</h1>
        <?php
            $conn = connect_db();

            $myid = $_SESSION['bd-session'];

            $smt = $conn->prepare("SELECT
                                        c.id, c.titulo, c.horas 
                                    FROM
                                        curso c
                                    WHERE c.id NOT IN (SELECT curso_id FROM curso_usuario WHERE usuario_id = :u_id)
                                    ORDER BY c.titulo");
            $smt->bindParam(':u_id', $myid);
            $smt->execute();
            $cursos = $smt->fetchAll();

            if(count($cursos) == 0):
        ?>
                <h4>Não há cursos disponíveis no momento.</h4>
        <?php
            else:
        ?>
            <div class="row">
        <?php
                foreach($cursos as $curso):
        ?>
            <div class="col-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $curso['id'] . ' &#8212; ' . $curso['titulo']; ?></h5>
                        <h6 class="card-subtitle mb-2 text-muted"><?php echo $curso['horas'] . ' horas'; ?></h6>
                        
                        <a href="inscrever_curso.php?id=<?php echo $curso['id']; ?>" class="card-link">Se inscrever</a>
                    </div>
                </div>
            </div>
        <?php
                endforeach;
        ?>
            </div>
        <?php
            endif;
        ?>
    </div>

    <script src="js/jquery-3.5.1.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
  </body>
</html>