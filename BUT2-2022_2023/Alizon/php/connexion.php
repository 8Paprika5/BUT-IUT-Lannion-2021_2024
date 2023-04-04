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

        <?php 
        if(isset($_SESSION['deco'])){
            $_SESSION['deco'] = null;
        }
        ?>

        <main class="main-connexion">

            <h1 class="titre-connexion">Identifiez-vous</h1>

            <div class="form-connexion">

                <form method="post" action="./fonctions/formulaire/connexion.inc.php">
                    <div class="formulaire">

                        <!-- Champ Email -->
                        <div class="bloc-email">
                            <label>Votre e-mail</label>
                            <input class="champ_mail" type="text" name="email" required>
                        </div>

                        <!-- Champ Mot de Passe -->
                        <div class="bloc-mdp">
                            <label>Votre mot de passe</label>
                            <input class="champ_mdp" type="password" name="pwd" minlength="8" required>
                        </div>

                        <!-- Bouton Resté Connecté et Mdp oublié -->
                        <div class="bloc_connexion_boutons">
                            <label><input type="checkbox" name="remember">Rester connecté</label>
                            <span><a class="lien_mdp_oubli" href="./recupererMdp.php">Mot de passe oublié ?</a></span>
                        </div>

                        <button class="btn-connecter" type="submit">Se connecter</button><br>

                        <?php 
                            if (isset($_GET["error"]) == "wrongid") {

                                // Erreur Identifiant ou Mot de Passe incorrects
                                if($_GET["error"] == "wrongid") {
                                    echo '<div class="erreur_co">';
                                    echo '<p class="texte_erreur_co">Identifiants incorrects. Vérifier votre e-mail et votre mot de passe.</p>';
                                    echo '</div>';
                                }

                                // Erreur Compte désactivé
                                if($_GET["error"] == "userdisabled") {
                                    echo '<div class="erreur_co">';
                                    echo '<p class="texte_erreur_co">Ce compte est désactivé.</p>';
                                    echo '</div>';
                                }

                                // Erreur Inconnue (si ça arrive)
                                if ($_GET["error"] === "unknowerror") {
                                    echo '<div class="erreur_co">';
                                    echo '<p class="texte_erreur_co">Erreur inconnue. Veuillez réessayer.</p>';
                                    echo '</div>';
                                }

                            }
                        ?>
                    </div>

                </form>

                <hr>

                <!-- Lien vers Inscription -->
                <h2 class="titre-nv_client">Nouveau client ?</h2>
                <form action="inscription.php" method="post">
                    <button class="btn-inscrire">Créer un compte</button>
                </form>

                <br>
                <hr>
                <!-- Lien vers Inscription -->
                <p> Se Connecter (en tant que Vendeur) ?        <a href="connexion-vendeur.php"> Se connecter </a> <p>
            </div>
            
        </main>

        <footer>
            <?php include("footer.php"); ?>
        </footer>
        
    </body>

</html>