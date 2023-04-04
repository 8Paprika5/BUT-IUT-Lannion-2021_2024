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
        <title>Connexion</title>
    </head>

    <body>
        <header>
            <?php include("header.php"); ?>
        </header>

        <main class="main-connexion">

            <h1 class="container">Identifiez-vous</h1>

            <form method="post" action="connexion.php">
                <div class="formulaire">
                    <input type="text" placeholder="Votre email" style="font-size: 1em" name="email" required><br>

                    <input type="password" placeholder="Votre mot de passe" style="font-size: 1em" name="pwd" required><br>

                    <span><a href="#">Mot de passe oublié</a></span><br>

                    <button class="btn-connecter" type="submit">Se connecter</button><br>
                    <?php
                        if(isset($_POST["email"])) {
                            $email = $_POST["email"];
                            $mdp =  $_POST["pwd"];
                            $sth = $dbh->prepare("SELECT count(ID_Client) FROM Alizon._Client WHERE email = '$email' AND mdp = '$mdp'");
                            $sth->execute();
                            $result = $sth->fetchAll();
                            if ($result[0]['count'] == 1) {
                                $sth2 = $dbh -> prepare("SELECT ID_Client FROM Alizon._Client WHERE email = '$email' and mdp = '$mdp'");
                                $sth2->execute();
                                $_SESSION['id_client'] = $sth2 -> fetchAll();
                                
                                header("Location : panier.php");
                                exit();
                                
                                
                            }
                            else {
                                echo 'Erreur Connexion. Veuillez réessayer de vous connecter.</br>';
                            }
                            $dbh = null;
                        }
                    ?>
                    <label><input type="checkbox" name="remember">Rester connecté ?</label>
                </div>
            </form>

            <h2 class="container_2">Nouveau client ?</h2>
            <form action="inscription.php" method="post">
                <button class="btn-inscrire">Créer un compte</button>
            </form>

            
        </main>

        <footer>
            <?php include("footer.php"); ?>
        </footer>
        
    </body>
    <script src="../js/menu-burger.js"></script>
    <script src="../js/squelette.js"></script>

</html>