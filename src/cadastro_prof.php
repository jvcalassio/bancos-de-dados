<?php

session_start();
if(isset($_SESSION['bd-session'])) {
    header("location: index.php");
    return;    
}
?>
<!doctype html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="css/bootstrap.min.css">

    <title>Cadastro &#8212; Professores</title>
  </head>
  <body>
    <div class="container mt-5 ">
      <h1 class="mb-5">Cadastro &#8212; Professores</h1>
      <?php
        if(isset($_POST['nome'])
            && isset($_POST['email'])
            && isset($_POST['dt-nasc'])
            && isset($_POST['pwd'])) {
        
                require('db/conn.php');

                $conn = connect_db();
                $nome = $_POST['nome'];
                $email = $_POST['email'];
                $dt_nasc = $_POST['dt-nasc'];
                $pwd = $_POST['pwd'];

                $pwd = password_hash($pwd, PASSWORD_BCRYPT);

                try {
                    $smt = $conn->prepare("INSERT INTO usuario (nome, email, data_nascimento, hash_pwd, cargo) VALUES (:nome, :email, :dt_nasc, :pwd, 'professor')");
                    $smt->bindParam(':nome', $nome);
                    $smt->bindParam(':email', $email);
                    $smt->bindParam(':dt_nasc', $dt_nasc);
                    $smt->bindParam(':pwd', $pwd);
                    $smt->execute();

                    $_SESSION['bd-session'] = $conn->lastInsertId();

                    header('location: index.php');
                } catch(Exception $e) {
        ?>
            <div class="alert alert-danger" role="alert">
                Ocorreu um erro ao realizar o seu cadastro.
            </div>
        <?php
                }
            }
        ?>
      <form method="post" action="cadastro_aluno.php">
        <div class="form-group">
          <label for="nome">Nome</label>
          <input type="text" class="form-control" id="nome" name="nome">
        </div>
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" class="form-control" id="email" name="email">
        </div>
        <div class="form-group">
          <label for="dt-nasc">Data de nascimento</label>
          <input type="date" class="form-control" id="dt-nasc" name="dt-nasc">
        </div>
        <div class="form-group">
          <label for="pwd">Senha</label>
          <input type="password" class="form-control" id="pwd" name="pwd">
        </div>
        <button type="submit" class="btn btn-primary">Cadastrar</button>
      </form>
    </div>

    <script src="js/jquery-3.5.1.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
  </body>
</html>