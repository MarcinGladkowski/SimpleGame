<?php
    session_start();

    if(isset($_POST['email'])){
      //good validation
      $wszystko_OK = true;

      //nickname
      $nick = $_POST['nick'];
      // length of nick
      if((strlen($nick)<3) || (strlen($nick)>20)){
        $wszystko_OK = false;
        $_SESSION['e_nick'] = 'nick musi posiadać od 3 do 20 znaków';
      }

      if(ctype_alnum($nick)==false){
        $wszystko_OK = false;
        $_SESSION['e_nick'] = 'nick może składać się tylko z liter i cyfr(bez polskich znaków)';
      }
      //email
      $email = $_POST['email'];
      $emailB = filter_var($email, FILTER_SANITIZE_EMAIL);

      if((filter_var($emailB, FILTER_SANITIZE_EMAIL)==false) || ($emailB != $email)){
        $wszystko_OK = false;
        $_SESSION['e_email'] = 'Podaj poprawny adres e-mail';
      }

      //passwords
      $haslo1 = $_POST['haslo1'];
      $haslo2 = $_POST['haslo2'];

      if((strlen($haslo1)<8) || (strlen($haslo1)>20)){
        $wszystko_OK = false;
        $_SESSION['e_haslo'] = 'haslo musi posiadać od 8 do 20 znaków';
      }
      if($haslo1 != $haslo2){
         $wszystko_OK = false;
          $_SESSION['e_haslo'] = 'hasła muszą być takie same!';
      }

      $haslo_hash = password_hash($haslo1, PASSWORD_DEFAULT);

      //checkbox

      if(!isset($_POST['regulamin'])){
        $wszystko_OK = false;
        $_SESSION['e_regulamin'] = 'Potwierdź akceptację regulaminu';
      }

      //bot or not
      $sekret = '6LcWqgoUAAAAACIS2QC0cnNkWxiNMMDdN3rpNFA7';

      $sprawdz = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$sekret.'&response='.$_POST['g-recaptcha-response']);

      $odpowiedz = json_decode($sprawdz);

      if($odpowiedz->success==false){
        $wszystko_OK = false;
        $_SESSION['e_bot'] = 'Potwierdź, że nie jesteś botem';
      }

      //remember data
      $_SESSION['fr_nick'] = $nick;
      $_SESSION['fr_email'] = $email;
      $_SESSION['fr_haslo1'] = $haslo1;
      $_SESSION['fr_haslo2'] = $haslo2;

      if(isset($_POST['regulamin'])){
        $_SESSION['fr_regulamin'] = true;
      }

      require_once('connect.php');
      mysqli_report(MYSQLI_REPORT_STRICT);

      try{
          $polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
          if($polaczenie->connect_errno!=0)
          {
              throw new Exception(mysqli_connect_errno());
          } else {
            //is email exists?
            $rezultat = $polaczenie->query("SELECT id FROM uzytkownicy WHERE email='$email'");

              if(!$rezultat) {
                throw new Exception($polaczenie->error);
              }

              $ile_takich_maili = $rezultat->num_rows;
                if($ile_takich_maili>0){
                  $wszystko_OK = false;
                  $_SESSION['e_email'] = 'Istnieje już konto o takim email';
                }

             //is nick exists?
            $rezultat = $polaczenie->query("SELECT id FROM uzytkownicy WHERE user='$nick'");

              if(!$rezultat) {
                throw new Exception($polaczenie->error);
              }

              $ile_takich_nickow = $rezultat->num_rows;
                if($ile_takich_nickow>0){
                  $wszystko_OK = false;
                  $_SESSION['e_nick'] = 'Istnieje już konto o takim nicku';
                }

              if($wszystko_OK==true){
                  if($polaczenie->query("INSERT INTO uzytkownicy VALUES(NULL, '$nick', '$haslo_hash', '$email', 100, 100, 100, 14)")){
                    $_SESSION['udanarejestracja']=true;
                    header('Location:witamy.php');
                  } else {
                    throw new Exception($polaczenie->error);
                  }
              }

            $polaczenie->close();
          }
      }
      catch(Exception $e){
        echo '<div class="error">Błąd sewera</div>';
        echo '</br>Informacja developerska'.$e;
      }

    

    }

      

?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
  <meta charset="utf-8"/>
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
  <title>Osadnicy - załóż darmowe konto</title>
  <script src='https://www.google.com/recaptcha/api.js'></script>

  <style>
    .error {
      color: red;
      margin-top: 10px;
      margin-bottom: 10px;
    }

  </style>

</head>
    <body>
         
        <form method="POST">
          Nickname: </br><input type="text" value="<?php
            if(isset($_SESSION['fr_nick'])){
              echo $_SESSION['fr_nick'];
              unset($_SESSION['fr_nick']);
            }
          ?>" name="nick" /></br>

          <?php
            if(isset($_SESSION['e_nick'])){
              echo '<div class="error">'.$_SESSION['e_nick'].'</div>';
              unset($_SESSION['e_nick']);
            }
          ?>

          E-mail: </br><input value="<?php
            if(isset($_SESSION['fr_email'])){
              echo $_SESSION['fr_email'];
              unset($_SESSION['fr_email']);
            }
          ?>

          " type="text" name="email" /></br>

          <?php
            if(isset($_SESSION['e_email'])){
              echo '<div class="error">'.$_SESSION['e_email'].'</div>';
              unset($_SESSION['e_email']);
            }
          ?>

          Twoje hasło: </br><input value="<?php
            if(isset($_SESSION['fr_haslo1'])){
              echo $_SESSION['fr_haslo1'];
              unset($_SESSION['fr_haslo1']);
            }
          ?>

          "type="password" name="haslo1" /></br>

          <?php
            if(isset($_SESSION['e_haslo'])){
              echo '<div class="error">'.$_SESSION['e_haslo'].'</div>';
              unset($_SESSION['e_haslo']);
            }
          ?>

          Powtórz hasło: </br><input value="<?php
            if(isset($_SESSION['fr_haslo2'])){
              echo $_SESSION['fr_haslo2'];
              unset($_SESSION['fr_haslo2']);
            }
          ?>

          " type="password" name="haslo2" /></br>

          <label>
            <input type="checkbox" name="regulamin" value="<?php
            if(isset($_SESSION['fr_regulamin'])){
              echo "checked";
              unset($_SESSION['fr_regulamin']);
            }
          ?>

            "/>Akceptuję regulamin
          </label>

          <?php
            if(isset($_SESSION['e_regulamin'])){
              echo '<div class="error">'.$_SESSION['e_regulamin'].'</div>';
              unset($_SESSION['e_regulamin']);
            }
          ?>
          
          <div class="g-recaptcha" data-sitekey="6LcWqgoUAAAAAN47kWUp5cbEb8DgKB3Txei2eBl9"></div>

           <?php
            if(isset($_SESSION['e_bot'])){
              echo '<div class="error">'.$_SESSION['e_bot'].'</div>';
              unset($_SESSION['e_bot']);
            }
          ?>

          </br>

          <input type="submit" value="Zarejestruj się">

        </form>

    </body>
</html>