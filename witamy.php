<?php
    session_start();

    if(!isset($_SESSION['udanarejestacja'])){

      header('Location:index.php');
      exit();
    } else {
      unset($_SESSION['udanarejestracja']);
    }
?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
  <meta charset="utf-8"/>
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
  <title>Osadnicy - gra przeglądarkowa</title>
</head>
    <body>

    Dziękujemy za rejestrację w serwisie! Możesz już zalogować się na swoje konto </br>

    <a href="index.php">Zaloguj się</a>
         
    </body>
</html>