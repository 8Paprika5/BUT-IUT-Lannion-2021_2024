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

        <?php 
        if(isset($_SESSION['desac'])){
            echo "<div class='alert deco'>";
            echo "<i class='fa-regular fa-circle-check fa-2x'></i>";
            echo "<p>Compté désactivé</p>";
            echo "</div>";
            $_SESSION['desac'] = null;
        }
        ?>

        <main class="main-inscription">

            <h1 class="titre-inscription">Créer un compte</h1>

            <form class="form-inscription" action="fonctions/formulaire/inscription.inc.php" method="post">

                <div class="formulaire">

                    <div class="identifiants">

                        <p class="obligatoire_indication">* champs obligatoires</p>

                        <h2>Vos Identifiants</h2>

                        <?php
                            if(isset($_GET["error"])) {

                                //  Affichage de l'erreur : Mail déjà existant
                                if($_GET["error"] === "emailerror") {
                                    echo '<div class="texte_erreur_co_mail">';
                                    echo '<p class="text_erreur_co">Cet email est déjà utilisé. Veuillez réessayer.</p>';
                                    echo '</div>';
                                }

                                //  Affichage de l'erreur : Mot de passe différent
                                else if($_GET["error"] === "mdperror") {
                                    echo '<div class="texte_erreur_co_mail">';
                                    echo '<p class="text_erreur_co">Mots de passe différents</p>';
                                    echo '</div>';
                                }
                            }
                        ?>

                        <!-- Dans chaque champ (sauf Question secrète et Réponse), les données sont conservées si l'utilisateur se trompe ou abandonne, au lieu de tout réécrire -->

                        <!-- Champ email -->
                        <div class="bloc-email">
                            <label>Email <span class="obligatoire">*</span></label>
                            <input class="champ_mail" type="email" name="email" 
                            value="<?php
                                if(isset($_SESSION['email_champ'])){ 
                                    echo $_SESSION['email_champ'];}
                                ?>" required>
                        </div>

                        <script>
                            let mail_champ = document.querySelector(".champ_mail");
                            <?php 
                                if(isset($_GET["error"])) :
                                    if($_GET["error"] === "emailerror") :?>
                                        mail_champ.classList.toggle("erreur_inscr");
                                    <?php endif;?>
                                <?php endif;?>
                        </script>

                        <!-- Champ Mot de passe -->
                        <div class="bloc-mdp">
                            <label>Mot de passe <span class="obligatoire">*</span></label>
                            <input class="champ_mdp" type="password" name="mdp" minlength="8" 
                            value="<?php 
                                if(isset($_SESSION['mdp_champ'])){
                                    echo $_SESSION['mdp_champ'];
                                }
                                ?>" required>
                        </div>

                        <p class="mdp_d cache">Mots de passe différents</p>

                        <!-- Champ Confirmation Mot de Passe -->
                        <div class="bloc-cmdp">
                            <label>Confirmer votre mot de
                            passe <span class="obligatoire">*</span></label>
                            <input class="champ_mdp_conf" type="password" name="cmdp" minlength="8" 
                            value="<?php
                                if(isset($_SESSION['cmdp_champ'])){
                                    echo $_SESSION['cmdp_champ'];
                                }
                                ?>" required>
                        </div>

                        <!-- Message Erreur de la différence de mot de passe -->
                        <p id="err-mdp-diff" class="texte_erreur_co erreur_inscr">
                            Erreur : Mots de passe différents.
                        </p>

                        <script>
                            let mdp_diff = document.querySelector(".mdp_d");
                            function afficheSiDifférent() {
                                /**
                                 * DESCRIPTION : Vérifie en temps réel à partir de 8 caractères si le mot de passe et sa confirmation sont les mêmes.
                                 */

                                // Si les 2 champs sont différents et vide et supérieur à 8 caractères
                                if ((mdp.value != cmdp.value) && (mdp.value != "") && (cmdp.value != "") && (cmdp.value.length >= mdp.value.length)) {
                                    
                                    //errmdp.style.display = 'block'
                                    cmdp.classList.add("erreur_inscr");
                                    mdp_diff.classList.add("affiche");
                                    mdp_diff.classList.add("mdp_diff");
                                    //cmdp.classList.toggle("erreur_inscr");
                                }
                                else if((cmdp.value.length <= mdp.value.length)) {
                                    //errmdp.style.display = 'none';
                                    cmdp.classList.remove("erreur_inscr");
                                    mdp_diff.classList.remove("affiche");
                                    // mdp.classList.toggle("erreur_inscr");
                                    // cmdp.classList.toggle("erreur_inscr");
                                }
                            }
                            var mdp = document.getElementsByClassName("champ_mdp")[0];
                            var cmdp = document.getElementsByClassName("champ_mdp_conf")[0];
                            var errmdp = document.getElementById("err-mdp-diff");
                            mdp.addEventListener("input", afficheSiDifférent);
                            cmdp.addEventListener("input", afficheSiDifférent);
                        </script>

                    </div>

                    <div class="infos-persos">

                        <h2>Vos informations personnelles</h2>

                        <!-- Champ Prénom -->
                        <div class="bloc-prenom">
                            <label>Prénom</label>
                            <input class="champ_nom" type="text" name="prenom" 
                            value="<?php
                                if(isset($_SESSION['prenom_champ'])){
                                    echo $_SESSION['prenom_champ'];}
                                ?>" required>
                        </div>

                        <!-- Champ Nom -->
                        <div class="bloc-nom">
                            <label>Nom</label>
                            <input class="champ_prenom" type="text" name="nom" 
                            value="<?php 
                                if(isset($_SESSION['nom_champ'])){
                                    echo $_SESSION['nom_champ'];
                                }
                                ?>" required>
                        </div>

                        <!-- Champ Date de Naissance -->
                        <div class="bloc-ddn">
                            <label>Date de naissance</label>
                            <input class="champ_date_naissance" type="date" name="ddn" 
                            value="<?php 
                                if(isset($_SESSION['ddn_champ'])){
                                    echo $_SESSION['ddn_champ'];
                                }
                                ?>" required>
                        </div>

                        <!-- Champ Adresse de Facturation -->
                        <div class="bloc-adresse">
                            <h3>Adresse de facturation</h3>
                            <label>Adresse postale</label>
                            <input class="champ_adresse" type="text" name="adr_facturation" value="<?php if(isset($_SESSION['adr_facturation_champ'])){echo $_SESSION['adr_facturation_champ'];}?>" required>

                            <label>Complément d'adresse</label>
                            <input class="champ_adresse" type="text" name="complementAdresse_facturation" value="<?php if(isset($_SESSION['complementAdresse_facturation_champ'])){echo $_SESSION['complementAdresse_facturation_champ'];}?>">
                            <div class="bloc-flexAdresse">
                                <div>
                                    <label>Ville</label>
                                    <input class="champ_ville" type="text" name="ville_facturation" value="<?php if(isset($_SESSION['ville_facturation_champ'])){echo $_SESSION['ville_facturation_champ'];}?>">
                                </div>
                                <div>
                                    <label>Code postal</label>
                                    <input class="champ_codePostal" type="number" name="codePostal_facturation" value="<?php if(isset($_SESSION['codePostal_facturation_champ'])){echo $_SESSION['codePostal_facturation_champ'];}?>" required>
                                </div>
                            </div>
                            
                            <label><input name="checkBox_LivraisonAdresse" id="checkBox_LivraisonAdresse" type="checkbox" checked>Utiliser la même adresse pour la livraison</label>
                            
                            <div id="bloc-LivraisonAdresse">
                                <h3>Adresse de livraison</h3>
                                <label>Adresse postale</label>
                                <input class="champ_adresse" type="text" name="adr_livraison" value="<?php if(isset($_SESSION['adr_livraison_champ'])){echo $_SESSION['adr_livraison_champ'];}?>" >
                                
                                <label>Complément d'adresse</label>
                                <input class="champ_adresse" type="text" name="complementAdresse_livraison" value="<?php if(isset($_SESSION['complementAdresse_livraison_champ'])){echo $_SESSION['complementAdresse_livraison_champ'];}?>">

                                <div class="bloc-flexAdresse">
                                    <div>
                                        <label>Ville</label>
                                        <input class="champ_ville" type="text" name="ville_livraison" value="<?php if(isset($_SESSION['ville_livraison_champ'])){echo $_SESSION['ville_livraison_champ'];}?>" >
                                    </div>

                                    <div>
                                        <label>Code postal</label>
                                        <input class="champ_codePostal" type="number" name="codePostal_livraison" value="<?php if(isset($_SESSION['codePostal_livraison_champ'])){echo $_SESSION['codePostal_livraison_champ'];}?>" >
                                    </div>
                                    
                                </div>

                            </div>
                        </div>

                    </div>

                   <div class="Securite">

                        <h2>Votre question confidentielle</h2> 
                        
                        <div class="bloc-question">

                            <label>Question secrete <span class="obligatoire">*</span></label>
                            
                            <!-- Choix de la Question Secrète -->
                            <select id="cars" class="champ_question" name="question">
                                <option value="" selected disabled >Choisissez une question</option>
                                <?php 
                                    $tab_question = array("Nom de jeune fille de la mère?","Dans quelle ville êtes-vous né ?","Quel est le nom de votre animal de compagnie préféré ?","Quel lycée as-tu fréquenté ?",
                                    "Quel était le nom de votre école primaire ?","Quel était le fabricant de votre première voiture ?","Quelle était votre nourriture préférée quand vous étiez enfant ?");
                                ?>
                                <?php foreach($tab_question as $key => $question): ?>
                                    <option><?php echo $question; ?></option>
                                <?php endforeach ?>
                            </select>

                        </div>

                        <!-- Champ Réponse à la Question Secrète -->
                        <div class="bloc_rep">
                            <label>Reponse <span class="obligatoire">*</span></label>
                            <input class="champ_reponse" type="text" name="reponse" required>
                        </div>

                    </div>

                    <!-- Case à cocher pour accepter les conditions générales -->
                    <p class="obligatoire_indication">* champs obligatoires</p>
                    <label class="conditions">
                        <input type="checkbox" name="conditions" style="margin-bottom:15px" required>J'accepte les conditions générales
                    </label>
                        
                    <div class="clearfix">
                        <button type="submit" class="btn-inscrire">S'inscrire</button>
                    </div>

                    <!-- Lien vers Se connecter -->
                    <p class="deja_inscrit">Déjà inscrit ?<a class="se_connecter" href="connexion.php">Se connecter</a></p>

                    <p class="txt_prevention">En créant un compte, vous acceptez les conditions générales de vente d’Alizon. Veuillez consulter notre notice Protection de vos Informations Personnelles, notre notice Cookies et notre notice Annonces publicitaires basées sur vos centres d’intérêt.
                    </p>

                </div>

            </form>

        </main>

        <footer>
            <script>
                /**######################################## ADRESSE DE LIVRAISON ########################################**/
                // RECUPERATION DES ELEMENTS HTML
                var checkBoxLivraisonAdress = document.getElementById("checkBox_LivraisonAdresse");
                var blocLivraisonAdresse = document.getElementById("bloc-LivraisonAdresse");
                
                if(document.querySelector("#bloc-LivraisonAdresse input").value != ''){
                    blocLivraisonAdresse.style.display = "block";
                    document.querySelector("#checkBox_LivraisonAdresse").checked = false;
                }else{
                    // CHAMPS D'ADRESSE CACHE PAR DEFAULT
                    blocLivraisonAdresse.style.display = "none";
                }
                
                checkBoxLivraisonAdress.addEventListener("change", function(event){    
                    if (checkBoxLivraisonAdress.checked){
                        //Bloc découvert si l'adresse de livraison est saisie manuellement
                        blocLivraisonAdresse.style.display = "none";
                        ParametresObligatoires(false);
                    } else {
                        //Bloc caché si la meme adresse de facturation est utilisé
                        blocLivraisonAdresse.style.display = "block";
                        ParametresObligatoires(true);

                    }
                });

                function ParametresObligatoires(required) {
                    if(required){
                        document.querySelector('[name="adr_livraison"]').setAttribute('required', '');
                        document.querySelector('[name="ville_livraison"]').setAttribute('required', '');
                        document.querySelector('[name="codePostal_livraison"]').setAttribute('required', '');
                    }else{
                        document.querySelector('[name="adr_livraison"]').removeAttribute('required');
                        document.querySelector('[name="ville_livraison"]').removeAttribute('required');
                        document.querySelector('[name="codePostal_livraison"]').removeAttribute('required');
                    } 
                }

                mail_champ.addEventListener('focus', function() {
                    mail_champ.classList.remove("erreur_inscr");
                });

            </script>
            <?php include('footer.php'); ?>
        </footer>

    </body>
    
</html>