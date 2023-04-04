<?php
    include("./Session.php");
    include("./fonctions.php");

    if(isset($_POST["nvmdp"]))
    {

        $res = recuperer_mdp($_POST["reponse"], $_POST["nvmdp"], $_GET["email"]);

        if($res === "unknowemail")
        {
            header("Location: ../recupererMdp.php?error=unknowemail");
            exit;
        }
        elseif($res === "errorquestion")
        {
            header("Location: ../recupererMdp.php?error=errorquestion");
            exit;
        }
        else
        {
            header("Location: ../recupererMdp.php?pwdswitched");
            exit;
        }
    }
?>