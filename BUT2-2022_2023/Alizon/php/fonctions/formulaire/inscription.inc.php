<?php
    include("./../Session.php");
    include("./../fonctions.php");
    
    // Si le champ du mot de passe existe
    if(isset($_POST['mdp'])) {

        $_SESSION['email_champ'] = $_POST['email'];
        $_SESSION['mdp_champ'] = $_POST['mdp'];
        $_SESSION['cmdp_champ'] = $_POST['cmdp'];
        $_SESSION['prenom_champ'] = $_POST['prenom'];
        $_SESSION['nom_champ'] = $_POST['nom'];
        $_SESSION['ddn_champ'] = $_POST['ddn'];
        $_SESSION['adr_facturation_champ'] = $_POST['adr_facturation'];
        $_SESSION['ville_facturation_champ'] = $_POST['ville_facturation'];
        $_SESSION['codePostal_facturation_champ'] = $_POST['codePostal_facturation'];
        
        if(isset($_POST['complementAdresse_facturation'])){
            $_SESSION['complementAdresse_facturation_champ'] = $_POST['complementAdresse_facturation'];
        }else{
            $_POST['complementAdresse_facturation'] = NULL;
        }

        if(!isset($_POST['checkBox_LivraisonAdresse'])){

            if(isset($_POST['complementAdresse_livraison'])){
                $_SESSION['complementAdresse_livraison_champ'] = $_POST['complementAdresse_livraison'];
            }else{
                $_POST['complementAdresse_livraison_champ'] = NULL;
            }

            $_SESSION['adr_livraison_champ'] = $_POST['adr_livraison'];
            $_SESSION['ville_livraison_champ'] = $_POST['ville_livraison'];
            $_SESSION['codePostal_livraison_champ'] = $_POST['codePostal_livraison'];
            
            // $res : Indicateur de l'inscription 
            $res = inscription($_POST["email"], $_POST['nom'], $_POST['prenom'], $_POST['ddn'], $_POST['mdp'], $_POST["cmdp"], $_POST['reponse'],
            $_POST['adr_facturation'],$_POST['complementAdresse_facturation'],$_POST['ville_facturation'],$_POST['codePostal_facturation'],
            $_POST['adr_livraison'],$_POST['complementAdresse_livraison'],$_POST['ville_livraison'],$_POST['codePostal_livraison']);
            
        
        }else{
            $res = inscription($_POST["email"], $_POST['nom'], $_POST['prenom'], $_POST['ddn'], $_POST['mdp'], $_POST["cmdp"], $_POST['reponse'],
            $_POST['adr_facturation'],$_POST['complementAdresse_facturation'],$_POST['ville_facturation'],$_POST['codePostal_facturation']);
        }

        /**
         * Tableau $_SESSION
         * ['email_champ']
         * ['mdp_champ']
         * ['cmdp_champ']
         * ['prenom_champ']
         * ['nom_champ']
         * ['ddn_champ']
         * ['adr_champ']
         */
        
        // Si l'email existe déjà, cela affiche l'erreur du mail déjà existant
        
        if($res === "Erroremail") {
            header("Location: ../../inscription.php?error=emailerror");
            exit;
        }
        else if ($res == "ErrorMdp") {
            header("Location: ../../inscription.php?error=mdperror");
            exit;
        }
        else {
            $_SESSION['email_champ'] = null;
            $_SESSION['mdp_champ'] = null;
            $_SESSION['cmdp_champ'] = null;
            $_SESSION['prenom_champ'] = null;
            $_SESSION['nom_champ'] = null;
            $_SESSION['ddn_champ'] = null;
            $_SESSION['adr_champ'] = null;
            header("Location: ../../connexion.php");
            exit;
        }

    }

?>