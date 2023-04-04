<?php            
    include("fonctions/Session.php");
    include("fonctions/fonctions.php");
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Inscription</title>
    </head>
    <body>
        
        <header>
            <?php include('header.php'); ?>
        </header>

        <main class="main-inscription">

            <h1 class="titre-inscription">Créer un compte</h1>

            <form class="form-inscription" action="fonctions/inscription.inc.php" method="post">

                <div class="formulaire">

                    <div class="identifiants">

                        <?php
                                if(isset($_GET["error"]))
                                {
                                    if($_GET["error"] === "pwderror")
                                    {
                                        echo '<div class="erreur_co">';
                                        echo '<p class="text_erreur_co">Les deux mots de ne passe ne correspondent pas.</p>';
                                        echo '</div>';
                                    }
                                    elseif($_GET["error"] === "emailerror")
                                    {
                                        echo '<div class="erreur_co">';
                                        echo '<p class="text_erreur_co">Cet Email est déjà utilisé. Veuillez réessayer.</p>';
                                        echo '</div>';
                                    }
                                }
                        ?>

                        <p class="obligatoire_indication">* champs obligatoires</p>

                        <h2>Vos Identifiants</h2>

                        <div class="bloc-email">
                            <label>Email <span class="obligatoire">*</span></label>
                            <input class="champ_mail" type="email" name="email" required>
                        </div>

                        <div class="bloc-mdp">
                            <label>Mot de passe <span class="obligatoire">*</span></label>
                            <input class="champ_mdp" type="password" name="mdp" minlength="8" required>
                        </div>

                        <div class="bloc-cmdp">
                            <label>Confirmer votre mot de
                            passe <span class="obligatoire">*</span></label>
                            <input class="champ_mdp_conf" type="password" name="cmdp" minlength="8" required>
                        </div>

                    </div>

                    <div class="infos-persos">

                        <h2>Vos informations personnelles</h2>

                        <div class="bloc-prenom">
                            <label>Prénom</label>
                            <input class="champ_nom" type="text" name="prenom" required>
                        </div>

                        <div class="bloc-nom">
                            <label>Nom</label>
                            <input class="champ_prenom" type="text" name="nom" required>
                        </div>

                        <div class="bloc-ddn">
                            <label>Date de naissance</label>
                            <input class="champ_date_naissance" type="date" name="ddn" required>
                        </div>

                        <div class="bloc-adresse">
                            <label>Adresse</label>
                            <input class="champ_adresse" type="text" name="adr" required>
                        </div>

                    </div>

                   <div class="Securite">

                        <h2>Votre question confidentielle</h2> 
                        
                        <div class="bloc-question">
                        <label>Question secrete <span class="obligatoire">*</span></label>
                        <select id="cars" class="champ_question" name="question">
                            <option value="" selected disabled >Choisissez une question</option>
                            <?php foreach(liste_question() as $key => $question): ?>
                                <option value="<?php echo $question['ID_QuestionSecrete']; ?>"><?php echo $question['question']; ?></option>
                            <?php endforeach ?>

                        </select>
                        </div>

                        <div class="bloc-reponse">
                            <label>Reponse <span class="obligatoire">*</span></label>
                            <input class="champ_reponse" type="text" name="reponse" required>
                        </div>



                    </div>

                    <p class="obligatoire_indication">* champs obligatoires</p>
                    <label class="conditions">
                        <input type="checkbox" name="conditions"
                        style="margin-bottom:15px" required>J'accepte les
                        conditions
                        générales
                    </label>
                        
                    <div class="clearfix">
                        <button type="submit" class="btn-inscrire">S'inscrire</button>
                    </div>

                    <p class="deja_inscrit">Déjà inscrit ?<a class="se_connecter" href="connexion.php">Se connecter</a></p>

                    <p class="txt_prevention">En créant un compte, vous acceptez les conditions générales de vente d’Alizon. Veuillez consulter notre notice Protection de vos Informations Personnelles, notre notice Cookies et notre notice Annonces publicitaires basées sur vos centres d’intérêt.
                    </p>

                </div>
            </form>
        </main>



        <footer>
            <?php include('footer.php'); ?>
        </footer>

    </body>
</html>