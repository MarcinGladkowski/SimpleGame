<?php
    session_start();
?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
  <meta charset="utf-8"/>
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
  <title>Osadnicy - załóż darmowe konto</title>
</head>
    <body>
         
        <form method="POST">
          Nickname: </br><input type="text" name="nick" /></br>

          E-mail: </br><input type="text" name="email" /></br>

          Twoje hasło: </br><input type="password" name="haslo1" /></br>

          Powtórz hasło: </br><input type="password" name="haslo2" /></br>
          <label>
            <input type="checkbox" name="regulamin" />Akceptuję regulamin
          </label>
          


        </form>

    </body>
</html>