<?php
    include("fonctions/Session.php");
    include("fonctions/fonctions.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Récupérer son mot de passe</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
    <body>
        <header>   
            <?php include("header.php"); ?>
        </header>

        <main class="main-renitialiser">
            <?php if(isset($_POST["email"])):
                if(email_valide($_POST["email"]) == true):?>
                    <div class="main-renitialiser--bloc">
                        <h1>Rénitialiser son mot de passe</h1>
                        
                        <form class="champ_infos_persos" method="post" action="./fonctions/recuperer.inc.php?email=<?php echo $_POST["email"] ; ?>">
                            <div class="question_secrete">
                                <label><?php echo question_secrete($_POST["email"])["Question"];?></label>
                                <input type="search" name="reponse" required>
                            </div>
                            <div class="nv_mdp">
                                <label>Nouveau Mot de passe</label>
                                <input type="password" name="nvmdp"  minlength="8" required>
                            </div>
                            <input type="submit" value="Confirmer" id="confirmer">
                        </form>
                    </div>
                <?php else: ?>
                    <div class="main-renitialiser--bloc">
                        <h1>Rénitialiser son mot de passe</h1>

                        <form class="champ_infos_persos" method="post" action="./recupererMdp.php">
                            <div class="adresse_mail">
                                <label>Renseignez votre adresse Email</label>
                                <input type="email" name="email" required>
                            </div>
                            <input type="submit" value="Confirmer" id="confirmer">
                        </form>
                        <div class="erreur_co">
                            <p class="texte_erreur_co">L'email renseigné n'est pas valide.</p>
                        </div>
                    </div>
                <?php endif;?>
            <?php else: ?>
                <div class="main-renitialiser--bloc">
                    <h1>Rénitialiser son mot de passe</h1>

                    <form class="champ_infos_persos" method="post" action="./recupererMdp.php">
                        <div class="adresse_mail">
                            <label>Renseignez votre adresse Email</label>
                            <input type="email" name="email" required>
                        </div>
                        <input type="submit" value="Confirmer" id="confirmer">
                    </form>

                    <?php 
                        if(isset($_GET["error"]))
                        {
                            if($_GET["error"] == "errorquestion")
                            {
                                echo '<div class="erreur_co">';
                                echo '<p class="texte_erreur_co">Réponse à la question secrète erronée.</p>';
                                echo '</div>';
                            }
                        }

                        if(isset($_GET["pwdswitched"]))
                        {
                            echo '<div class="erreur_co switch">';
                            echo '<p class="texte_erreur_co">Le mot de passe a été modifié avec succès.</p>';
                            echo '</div>';
                        }
                    ?>
                </div>
            <?php endif;?>
            
        </main>

        <footer>
            <?php include("footer.php"); ?>
        </footer>
    </body>
</html>