<?php
    include("./Session.php");
    include("./fonctions.php");
    if(isset($_POST["email"])) {

        $connected = connexion($_POST["email"], $_POST["pwd"]);

        if ($connected[0])
        {
            $_SESSION["id_client"] = $connected[1];
            header("Location: ../index.php");
            exit();

        }
        else
        {
            header("Location: ../connexion.php?error=wrongid");
            exit();
        }
    }
    else 
    {
        header("Location: ../connexion.php?error=unknowerror");
        exit(); 
    }
    
?>