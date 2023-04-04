<?php
    include("./Session.php");
    include("./fonctions.php");
    
    if(isset($_POST['mdp'])) {

        $res = inscription($_POST["email"], $_POST['nom'], $_POST['prenom'], $_POST['adr'], $_POST['ddn'], $_POST['mdp'], $_POST["cmdp"],  $_POST['question'], $_POST['reponse']);

        if($res === "Errormdp")
        {
            header("Location: ../inscription.php?error=pwderror");
            exit;
        }
        elseif($res === "Erroremail")
        {
            header("Location: ../inscription.php?error=emailerror");
            exit;
        }
        else
        {
            header("Location: ../connexion.php");
            exit;
        }

    }

?>