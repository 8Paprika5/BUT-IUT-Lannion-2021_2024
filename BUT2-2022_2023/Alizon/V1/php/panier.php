<?php
    include("Session.php");
    
    try {
        
        $sth = $dbh->prepare("SELECT * FROM $schema.panier WHERE id_panier = {$_SESSION['id_panier']}");
        $sth->execute();
        $listeArticle = $sth->fetchAll(); //tableau de '$nbproduit' produits contenus dans le panier
        $nbproduit = sizeof($listeArticle);
        
        $sth = $dbh->prepare("SELECT * FROM $schema.panier WHERE id_panier = {$_SESSION['id_panier']}");
        $sth->execute();

        if($nbproduit >0){
            $RecapPanier = $sth->fetchAll()[0];
            $prixTotal = $RecapPanier['prix_total_ttc'];
        }
        //echo '<pre>';
        //print_r($listeArticle);
        //echo '</pre>';
        /** liste des valeurs a récupérer pour chaque article (Exemple de representation pour l'article 1 du panier):
         * $listeArticle[0]['vendeur']      
         * $listeArticle[0]['nom_produit']          
         * $listeArticle[0]['nom_categorie']
         * $listeArticle[0]['stock']
         * $listeArticle[0]['prix_total_ttc']
         * $listeArticle[0]['prix_produit_commande_ttc']
         * $listeArticle[0]['quantite']
         * $listeArticle[0]['id_produit']
         */
       
    } catch (PDOException $e) {
        print "Erreur !: " . $e->getMessage() . "<br/>";
    }


?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel='stylesheet' type='text/css' media='screen' href='../css/style.css'>
        <link rel='stylesheet' type='text/css' media='screen' href='../css/panier.css'>
        <title>Panier</title>
    </head>
    <body>
        <header>
            <?php include("header.php"); ?>
        </header>

        <?php if($nbproduit > 0 ): ?>
        <div class="div-panier">
            <h2><?php if($nbproduit > 1){echo "Panier ($nbproduit produits)";}else{echo "Panier ($nbproduit produit)";}?></h2>

            <?php for ($numArt=0; $numArt < $nbproduit; $numArt++): ?>
                <section>
                    
                        <aside>
                            <h3> <?php echo "Panier ".$numArt+1 ."/$nbproduit : vendu et expédié par {$listeArticle[$numArt]['vendeur']}";?> </h3>
                        </aside>
                        <article>
                            <img src="img/CHAT.jpg" alt="photo" class="photo-prod"/>
                            <aside>
                                <h3><?php echo "{$listeArticle[$numArt]['nom_produit']}";?></h3>
                                <h5><?php echo "{$listeArticle[$numArt]['nom_categorie']}";?></h5>

                                <?php if($listeArticle[$numArt]['quantite_disponnible'] > 0):?>
                                    <h4>En stock</h4>
                                    <img src="" alt="logo stock" />
                                <?php else:?>
                                    <h4>Indisponible</h4>
                                    <img src="" alt="logo stock indisponible" />
                                <?php endif;?>

                            </aside>
                            <aside>
                                <h2><?php echo $listeArticle[$numArt]['prix_produit_commande_ttc']." €"; ?></h2>
                                
                                <form action="./fonctions/modifierQuantite.php?id_produit=<?php echo $listeArticle[$numArt]['id_produit'] ?>" method="POST">
                                    <select onchange="this.form.submit()" name="quantiteSelect" values="<?php echo $listeArticle[$numArt]['quantite']?>">
                                        <?php   
                                            for ($i=0; $i <= 99; $i++) { 
                                                if($i != $listeArticle[$numArt]['quantite']){
                                                    echo "<option value='$i'>$i</option>";
                                                }else{
                                                    echo "<option value='$i' selected>$i</option>";
                                                }
                                            }
                                        ?>
                                    </select>
                                </form>

                                <a href="./fonctions/supprimerProduit.php?id_produit=<?php echo $listeArticle[$numArt]['id_produit'] ?>"> Suprimmer</a>
                                
                            </aside>
                        </article>
                    
                </section>
            <?php endfor; ?>
        </div>


        <div class="recap">
            <h2>Récapitulatif</h2>
            <hr/>
            <section>
                <article>
                    <h4 class="coloGris"><?php echo "Panier ($nbproduit)"; ?></h4>
                    <h3><?php echo "$prixTotal €"; ?></h3>
                </article>
                <article>
                    <h4 class="coloGris">Frais de livraison</h4>
                    <h4 style="color: #080854;">Gratuit</h4>
                </article>
                <hr/>
                <article>
                    <aside>
                        <h2 style="font-family: 'montserrat';margin-bottom:7px;color: #080854;">Total</h2>
                        <h5 class="coloGris">(TVA incluse)</h5>
                    </aside>
                    <h2 class="prix"><?php echo "$prixTotal €"; ?></h2>
                </article>
                
                <form action="paiement.php" method="POST">
                    <button type="submit">Valider mon panier</button>
                </form>
            </section>
            <form action="fonctions/ViderPanier.php" method="POST">
                <button><i class="fa fa-trash"> Vider le panier</i></button>
            </form>
        </div>

        <?php else: ?>
            <div>
                    <h1>Votre panier est vide</h1>
                    <h2><a href="index.php">Constuler le catalogue...</a></h2>
            </div>
        <?php endif; ?>

        <footer>
            <?php include("footer.php"); ?>
        </footer>

    </body>
    <script src="../js/menu-burger.js"></script>
    <script src="../js/squelette.js"></script>

</html>

