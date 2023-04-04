<?php            
    include("Session.php");
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel='stylesheet' type='text/css' href='../css/style.css'>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <title>Inscription</title>
    </head>
    <body>
        
        <header>
            <?php include('header.php'); ?>
        </header>

        <main class="main-inscription">

            <h1 class="container">Créer un compte</h1>

            <form action="inscription.php" method="post" style="border:0px solid #ccc">
                <div class="formulaire">
                    <div class="nomination">
                        <div class="Nom">
                            <label for="fname">Nom</label><br>
                            <input type="text" name="nom" required>
                            <br/>
                        </div>

                        <div class="Prenom">
                            <label for="fname">Prénom</label><br>
                            <input type="text" name="prenom" required>
                            <br/>
                        </div>
                    </div>

                    <div class="Securiter">
                        <div class="mdp">
                            <label for="fname">Mot de passe</label><br>
                            <input type="password" name="mdp" required>
                            <br/>
                        </div>

                        <div class="cmdp">
                            <label for="fname">Confirmer votre mot de
                            passe</label><br>
                            <input type="password" name="cmdp" required>
                            <br/>
                        </div>
                    </div>

                    <div class="infosup">
                        <div class="email">
                            <label for="fname">Email</label><br>
                            <input type="email" name="email" required>
                            <br/>
                        </div>

                        <div class="ddn">
                            <label for="fname">Date de naissance</label><br>
                            <input type="date" name="ddn" required>
                            <br/>
                            </div>
                        </div>

                        <div class="adresse">
                            <label for="fname">Adresse</label><br>
                            <input type="text" name="adr" required>
                            <br/>
                        </div>
                    </div>

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

                    <p class="txt_prevention">En créant un compte, vous acceptez les conditions générales de vente d’Alizon. Veuillez consulter notre notice Protection de vos Informations Personnelles, notre notice Cookies et notre notice Annonces publicitairesbasées sur vos centres d’intérêt.
                    </p>
                </div>
            </form>
        </main>

        <?php
            
            try {
                if(isset($_POST['mdp'])) {
                    $dbh->beginTransaction(); 
                    $email = $_POST['email'];
                    $sth = $dbh -> prepare("SELECT ID_Client FROM Alizon._Client WHERE email = '$email'");
                    $sth->execute();
                    $tabverif = $sth->fetchAll();
                    $verif = 0;
                    foreach ($tabverif as $row){
                        $verif+=1;
                    }
                    if ($verif >= 1){
                        print('Erreur : Cet email est déjà existante.');
                    }
                    else {
                        if ($_POST['mdp'] != $_POST['cmdp']) {
                        print('Erreur : Mots de passe incohérents.');
                        }
                        else {
                            // INSERTION DU NOUVEAU CLIENT DANS LA TABLE CLIENT
                            $nom = $_POST['nom'];
                            $prenom = $_POST['prenom'];
                            $adr = $_POST['adr'];
                            $ddn = $_POST['ddn'];
                            $mdp = $_POST['mdp'];
                            
                            $dbh -> exec("INSERT INTO Alizon._Client (nom_client,prenom_client,adresse_facturation,date_de_naissance,email,mdp) VALUES ('$nom','$prenom','$adr','$ddn','$email','$mdp')");
                            $dbh -> commit();

                            
                            $_SESSION['id_client'] = $dbh -> exec("SELECT ID_Client from Alizon._Client where email = '$email'");
                            $id_client_session = $_SESSION['id_client'];
                            $id_panier_session = $_SESSION['id_panier'];
                            $dbh -> exec("UPDATE Alizon._Panier SET ID_Client = '$id_client_session' WHERE id_panier = '$id_panier_session'");

                            // LIBERATION DE LA MEMOIRE
                            $dbh = null;

                            // REDIRECTION VERS LA PAGE INDEX
                            header("Location : panier.php");
                            exit();
                        }
                    }
                }
            } catch (PDOException $e) {
                print("Erreur : " . $e->getMessage());
            }
        ?>

        <footer>
            <?php include('footer.php'); ?>
        </footer>

    </body>
</html>