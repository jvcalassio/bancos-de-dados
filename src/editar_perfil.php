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
        <li class="nav-item">
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
            
            if( isset($_POST['nome'])
                && isset($_POST['email']) 
                && isset($_POST['dt-nasc'])
                && isset($_POST['pwd'])
                && isset($_POST['descricao'])
                && isset($_POST['foto']) ) {
                
                $nome = $_POST['nome'];
                $email = $_POST['email'];
                $dt_nasc = $_POST['dt-nasc'];
                $pwd = $_POST['pwd'];
                $descricao = $_POST['descricao'];
                $foto = $_POST['foto'];

                try {
                    if($pwd == '') { // nao alterou senha
                        $smt = $conn->prepare(" UPDATE usuario 
                                                SET nome = :nome, 
                                                    email = :email, 
                                                    data_nascimento = :dt_nasc,
                                                    descricao = :descricao,
                                                    foto = :foto
                                                WHERE id = :u_id");
                        $smt->bindParam(':nome', $nome);
                        $smt->bindParam(':email', $email);
                        $smt->bindParam(':dt_nasc', $dt_nasc);
                        $smt->bindParam(':descricao', $descricao);
                        $smt->bindParam(':foto', $foto);
                        $smt->bindParam(':u_id', $myid);
                    } else { // alterou senha
                        $pwd = password_hash($pwd, PASSWORD_BCRYPT);
                        $smt = $conn->prepare(" UPDATE usuario 
                                                SET nome = :nome, 
                                                    email = :email, 
                                                    data_nascimento = :dt_nasc,
                                                    descricao = :descricao,
                                                    foto = :foto,
                                                    hash_pwd = :pwd
                                                WHERE id = :u_id");
                        $smt->bindParam(':nome', $nome);
                        $smt->bindParam(':email', $email);
                        $smt->bindParam(':dt_nasc', $dt_nasc);
                        $smt->bindParam(':descricao', $descricao);
                        $smt->bindParam(':foto', $foto);
                        $smt->bindParam(':pwd', $pwd);
                        $smt->bindParam(':u_id', $myid);
                    }
                    $smt->execute();
                    
                    header('location: perfil.php');
                } catch(Exception $e) {
        ?>
            <div class="alert alert-danger" role="alert">
                Ocorreu um erro alterar seu dados.
            </div>
        <?php
                }
            }
            
            $smt = $conn->prepare("SELECT
                                        nome, email, data_nascimento, descricao, foto
                                    FROM
                                        usuario
                                    WHERE id = :u_id");
            $smt->bindParam(':u_id', $myid);
            $smt->execute();
            $usuario = $smt->fetch();
            
            if($usuario != ''):
        ?>
            <h2 class="my-5">Editar perfil</h2>
            <form method="post" action="editar_perfil.php">
                <div class="form-group">
                    <label for="nome">Nome:</label>
                    <input type="text" class="form-control" id="nome" name="nome" value="<?php echo $usuario['nome']; ?>">
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo $usuario['email']; ?>">
                </div>
                <div class="form-group">
                    <label for="dt-nasc">Data de nascimento:</label>
                    <input type="date" class="form-control" id="dt-nasc" name="dt-nasc" value="<?php echo $usuario['data_nascimento']; ?>">
                </div>
                <div class="form-group">
                    <label for="pwd">Senha:</label>
                    <input type="password" class="form-control" id="pwd" name="pwd" placeholder="Insira sua nova senha, caso queira mudar">
                </div>
                <div class="form-group">
                    <label for="descricao">Descrição:</label>
                    <textarea class="form-control" id="descricao" name="descricao"><?php echo $usuario['descricao']; ?></textarea>
                </div>
                <div class="form-group">
                    <label for="foto">Foto (URL):</label>
                    <input type="text" class="form-control" id="foto" name="foto" value="<?php echo $usuario['foto']; ?>">
                </div>
                <button type="submit" class="btn btn-primary">Atualizar dados</button>
            </form>
        <?php endif; ?>
    </div>

    <script src="js/jquery-3.5.1.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
  </body>
</html>