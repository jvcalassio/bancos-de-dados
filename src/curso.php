<?php

session_start();
if(!isset($_SESSION['bd-session'])) {
    header('location: login.php');
    return;    
}

require('db/conn.php');

$conn = connect_db();
$myid = $_SESSION['bd-session'];

if(isset($_GET['id'])) {
    $curso_id = $_GET['id'];

    $smt = $conn->prepare("SELECT titulo FROM curso WHERE id = :c_id");
    $smt->bindParam(':c_id', $curso_id);
    $smt->execute();
    $row = $smt->fetch();
    if($row == "") {
        header('location: 404.php');
    }

    $curso_titulo = $row['titulo'];
    $curso_titulo_d = $curso_id . ' &#8212; ' . $curso_titulo;
} else {
    header('location: index.php');
}
?>
<!doctype html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="css/bootstrap.min.css">

    <title><?php echo $curso_titulo_d; ?></title>
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
        <li class="nav-item">
            <a class="nav-link" href="perfil.php">Perfil</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="logout.php">Sair</a>
        </li>
    </div>
    </nav>
    <div class="container my-5">
        <h1 class="my-5"><?php echo $curso_titulo_d; ?></h1>
        <?php
            $smt = $conn->prepare("SELECT count(*) FROM curso_usuario WHERE curso_id = :c_id AND usuario_id = :u_id");
            $smt->bindParam(':c_id', $curso_id);
            $smt->bindParam(':u_id', $myid);
            $smt->execute();

            $res = $smt->fetch()[0];

            if($res == 0) : // nao esta no curso
        ?>
                <h4>Você não está inscrito neste curso.</h4>
        <?php
            else: // esta no curso
                $smt = $conn->prepare(" SELECT id, titulo, DATE_FORMAT(data_inicio, '%d/%m/%Y') as data_inicio, DATE_FORMAT(data_final, '%d/%m/%Y') as data_final
                                        FROM secao
                                        WHERE curso_id = :c_id");
                $smt->bindParam(':c_id', $curso_id);
                $smt->execute();
                $sec_rows = $smt->fetchAll();

                foreach($sec_rows as $sec_row) :
                    if ($sec_row['data_inicio'] != '' && $sec_row['data_final'] != ''):
        ?>
                        <h4 class="mt-3"><?php echo $sec_row['titulo'] . ' &#8212; ' . $sec_row['data_inicio'] . ' a ' . $sec_row['data_final']; ?></h4>
        <?php       else:   ?>
                        <h4 class="mt-3"><?php echo $sec_row['titulo']; ?></h4>
        <?php       endif;
                    $smt = $conn->prepare(" SELECT titulo, 'Teste' as tipo, CONCAT('teste.php?id=', id) as link
                                            FROM teste
                                            WHERE secao_id = :s_id
                                            
                                            UNION ALL
                                            
                                            SELECT titulo, 'Trabalho' as tipo, CONCAT('trab.php?id=', id) as link
                                            FROM trabalho
                                            WHERE secao_id = :s_id
                                            
                                            UNION ALL
                                            
                                            SELECT titulo, 'Aula' as tipo, CONCAT('aula.php?id=', id) as link
                                            FROM aula
                                            WHERE secao_id = :s_id
                                            
                                            UNION ALL 
                                            
                                            SELECT titulo, 'Material de apoio' as tipo, CONCAT('ma.php?id=', id) as link
                                            FROM material_apoio
                                            WHERE secao_id = :s_id");

                    $smt->bindParam(':s_id', $sec_row['id']);
                    $smt->execute();

                    $content_rows = $smt->fetchAll();

                    foreach($content_rows as $content_row) :
        ?>
                        <p><a href="<?php echo $content_row['link']; ?>"><?php echo $content_row['tipo'] . ' &#8212; ' . $content_row['titulo']; ?></a></p>
        <?php
                    endforeach;
                endforeach;
        ?>
            <hr />
            <a href="sair_curso.php?id=<?php echo $curso_id; ?>" class="mt-5">Abandonar curso</a>
        <?php
            endif;
        ?>
    </div>

    <script src="js/jquery-3.5.1.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
  </body>
</html>