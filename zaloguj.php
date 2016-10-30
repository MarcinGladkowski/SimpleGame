<?php
    
    session_start();

    require_once('connect.php');

    $polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);

    if($polaczenie->connect_errno!=0)
    {
        echo "Error ".$polaczenie->connect_errno;
    }
    else {
        
        $login = $_POST['login'];
        $haslo = $_POST['haslo'];
        
        $sql = "SELECT * FROM uzytkownicy WHERE user='$login' AND pass='$haslo'";
        
        if($rezultat = @$polaczenie->query($sql))
        {
            $ilu_userow = $rezultat->num_rows;
            if($ilu_userow>0){
                
                $wiersz = $rezultat->fetch_assoc();
                $_SESSION['user'] = $wiersz['user'];
                $_SESSION['drewno'] = $wiersz['drewno'];
                $_SESSION['kamien'] = $wiersz['kamien'];
                $_SESSION['zboze'] = $wiersz['zboze'];
                $_SESSION['email'] = $wiersz['email'];
                $_SESSION['dnipremium'] = $wiersz['dnipremium'];

                unset($_SESSION['blad']);

                $rezultat->free_result();

                header('Location:gra.php');
                
            } else {

            $_SESSION['blad'] = '<span>Nieprawidłowe login lub hasło</span>';

            header('location:index.php')
                
            }
        }
        
        $polaczenie->close();
    }

  
?>