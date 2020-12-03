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

    <title>Login</title>
  </head>
  <body>
    <div class="container mt-5 ">
    <h1 class="mb-5">Login</h1>
    <?php
        if(isset($_POST['email'])
        && isset($_POST['pwd'])) {

            require('db/conn.php');

            $conn = connect_db();
            $email = $_POST['email'];
            $pwd = $_POST['pwd'];

            try {
                $smt = $conn->prepare("SELECT id, email, hash_pwd FROM usuario WHERE email = :email");
                $smt->bindParam(':email', $email);
                $smt->execute();
                $row = $smt->fetch();

                if(password_verify($pwd, $row['hash_pwd'])) {
                    $_SESSION['bd-session'] = $row['id'];
                    header('location: index.php');
                } else {
                    throw new Exception('Email/senha incorreto.');
                }
            } catch(Exception $e) {
    ?>
        <div class="alert alert-danger" role="alert">
            Ocorreu um erro ao fazer login. Verifique seu email/senha.
        </div>
    <?php
            }
        }
    ?>
    <form method="post" action="login.php">
      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" class="form-control" id="email" name="email">
      </div>
      <div class="form-group">
        <label for="pwd">Senha</label>
        <input type="password" class="form-control" id="pwd" name="pwd">
      </div>
      <button type="submit" class="btn btn-primary">Entrar</button>
      <a href="cadastro_aluno.php" class="btn btn-secondary">Cadastro aluno</a>
    </form>
    </div>

    <script src="js/jquery-3.5.1.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
  </body>
</html>