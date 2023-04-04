<?php
    include("fonctions/Session.php");
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Connexion Vendeur</title>
    </head>

    <body>
        <header>
            <?php include("header.php");?>
        </header>

        <main class="main-connexion">

            <h1 class="titre-connexion">Identifiez-vous en tant que Vendeur</h1>
            <div class="form-connexion">

                <form method="post" action="./fonctions/formulaire/connexion.vendeur.inc.php">
                    <div class="formulaire">

                        <!-- Champ Email -->
                        <div class="bloc-email">
                            <label>Votre e-mail vendeur</label>
                            <input class="champ_mail" type="text" name="email" required>
                        </div>

                        <!-- Champ Mot de Passe -->
                        <div class="bloc-mdp">
                            <label>Votre mot de passe</label>
                            <input class="champ_mdp" type="password" name="pwd" minlength="8" required>
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
                                if($_GET["error"] == "vendeurdisabled") {
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
                <h3> Retour à la page de connexion client</h3>
                <form action="connexion.php" method="post">
                    <button class="btn-inscrire">Retour Compte Client</button>
                </form>
            </div>
            
        </main>
    
        <footer>
            <?php include("footer.php"); ?>
        </footer>
            
    </body>
    
</html>