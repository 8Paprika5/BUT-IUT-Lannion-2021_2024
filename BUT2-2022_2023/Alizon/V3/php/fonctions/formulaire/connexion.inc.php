<?php
    include("./../Session.php");
    include("./../fonctions.php");

    // Si le mail existe
    if(isset($_POST["email"])) {

        $connected = connexion($_POST["email"], $_POST["pwd"]);
        
        print_r($connected);
        /**
         * Tableau $connected
         * ['res']
         * ['id_client']
         * ['statut_active']
         *
         */
        
        // Si l'identifiant est valide
        if ($connected[0]) {

            // Si le Compte n'est pas desactivé
            if($connected[2]==1) {
                $_SESSION["id_client"] = $connected[1];
                header("Location: ../../index.php?connected");
                exit();
            }
            else {
                header("Location: ../../connexion.php?error=userdisabled");
                exit();
            }
        }
        else {
            header("Location: ../../connexion.php?error=wrongid");
            exit();
        }
    }
    else {
        header("Location: ../../connexion.php?error=unknowerror");
        exit(); 
        
    }
    
?>