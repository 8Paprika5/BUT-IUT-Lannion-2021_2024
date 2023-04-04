<?php
    include("fonctions/Session.php");
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Connexion</title>
    </head>

    <body>
        <header>
            <?php include("header.php");?>
        </header>

        <main class="main-connexion">

            <h1 class="titre-connexion">Identifiez-vous</h1>

            <div class="form-connexion">

                <form method="post" action="./fonctions/connexion.inc.php">
                    <div class="formulaire">

                        <div class="bloc-email">
                            <label>Votre e-mail</label>
                            <input class="champ_mail" type="text" name="email" required>
                        </div>

                        <div class="bloc-mdp">
                            <label>Votre mot de passe</label>
                            <input class="champ_mdp" type="password" name="pwd" minlength="8" required>
                        </div>

                        <div class="bloc_connexion_boutons">
                            <label><input type="checkbox" name="remember">Rester connecté</label>
                            <span><a class="lien_mdp_oubli" href="./recupererMdp.php">Mot de passe oublié ?</a></span>
                        </div>

                        <button class="btn-connecter" type="submit">Se connecter</button><br>

                        <?php 
                                if (isset($_GET["error"]) == "wrongid")
                                {

                                    if($_GET["error"] == "wrongid")
                                    {
                                        echo '<div class="erreur_co">';
                                        echo '<p class="texte_erreur_co">Identifiants incorrects. Vérifier votre e-mail et votre mot de passe.</p>';
                                        echo '</div>';
                                    }

                                    if ($_GET["error"] === "unknowerror")
                                    {
                                        echo '<div class="erreur_co">';
                                        echo '<p class="texte_erreur_co">Erreur inconnue. Veuillez réessayer.</p>';
                                        echo '</div>';
                                    }

                                 } 
                                


                        ?>
                    </div>
                </form>

                <hr>

                <h2 class="titre-nv_client">Nouveau client ?</h2>
                <form action="inscription.php" method="post">
                    <button class="btn-inscrire">Créer un compte</button>
                </form>

            </div>
            
        </main>

        <footer>
            <?php include("footer.php"); ?>
        </footer>
        
    </body>

</html>