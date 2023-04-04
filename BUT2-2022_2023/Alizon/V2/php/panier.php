<?php
    //error_reporting(0);
    include("./fonctions/Session.php");
    include("./fonctions/fonctions.php");
    
    $listeCategorie = liste_Categories();
    $func = infos_panier($_COOKIE['id_panier']);
    $nbproduit = $func['nbproduit'];
    $prixTotal = $func['prixTotal'];
    $listeArticle = $func['listeArticles'];
    $prix_ht = $func['prix_ht'];
    $tva_inclus = $prixTotal - $prix_ht;

    /*echo '<pre>';
    print_r($_GET);
    echo '</pre>';
    echo '<pre>';
    print_r($_POST);
    echo '</pre>';*/

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

    if(isset($_GET['viderpanier'])) {
        ViderPanier();
    }

    if(isset($_GET['supprimerProduit'])) {
        supprimerProduit($_GET['supprimerProduit']);
    }

    if(isset($_POST['quantiteSelect'])) {
        modifierQuantite($_POST['quantiteSelect'],$_GET['id_produit']);
    }
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Panier</title>
        <link rel='stylesheet' type='text/css' href='../css/style.css'>
    </head>
    <body>

        <header>
            <?php include("header.php"); ?>
        </header>

        <main class="main_panier">

            <?php if($nbproduit > 0 ): ?>

            <div class="div-panier">

                <div class="infos-panier-sticky-responsive">
                    <div class="block-nbr-prix">
                        <h2 class="titre-panier"><?php if($nbproduit > 1){echo "Votre Panier ($nbproduit produits)";}else{echo "Votre Panier ($nbproduit produit)";}?></h2>

                        <h2 class="prix-base_responsive"><?php echo "$prixTotal €"; ?></h2>
                    </div>

                    <div class="boutons-panier-responsive">
                        <form class="form-valider-responsive" action="paiement.php" method="POST">
                            <button class="valider-panier-resp" type="submit">Valider mon panier</button>
                        </form>

                        <button class="vider-panier-responsive open-button" onclick="openForm()">&nbsp; Vider le panier</button>
                    </div>
                </div>

                <?php for ($numArt=0; $numArt < $nbproduit; $numArt++): ?>

                    <section>   

                            <div class="Sous-titre-Panier">
                                <h3> <?php echo "Panier ".$numArt+1 ."/$nbproduit : vendu et expédié par {$listeArticle[$numArt]['vendeur']}";?> </h3>
                            </div>

                            <article class="Produit-Panier">

                                <div class="Produit-Global">

                                    <img src="../img/catalogue/Chat_image.png" alt="Chat_image">

                                    <!-- <img src="<?php echo str_replace(' ', "_","../img/catalogue/".$listeArticle[$numArt]['nom_categorie']."/".$listeArticle[$numArt]['nom_soucategorie']."/".$listeArticle[$numArt]['id_produit']."/".$listeArticle[$numArt]['images1']); ?>" alt="photo" class="photo-prod"/> -->
                                    <div class="Produit-Infos">

                                        <h3 class="Titre-Produit_Panier cursor" onclick='location.href="produit.php?idProduit=<?php echo $listeArticle[$numArt]['id_produit'];?>";'><?php echo "{$listeArticle[$numArt]['nom_produit']}";?></h3>
                                        <h5 class="Categorie-Produit_Panier"><?php echo "{$listeArticle[$numArt]['nom_categorie']}";?></h5>

                                        <?php if($listeArticle[$numArt]['quantite_disponnible'] > 0):?>
                                            <div class="stockage-panier">
                                                <h4 class="Stock-Produit_Panier">En stock</h4>
                                                <img src="../img/stock.png" alt="logo stock" title="Stock Indisponible" width="50" height="50">
                                            </div>
                                        <?php else:?>
                                            <div class="stockage-panier">
                                                <h4 class="Stock-Produit_Panier">Indisponible</h4>
                                                <img src="../img/rupture-de-stock.png" alt="Logo stock indisponible" title="Stock Indisponible" width="50" height="50">
                                            </div>
                                        <?php endif;?>

                                    </div>

                                </div>  

                                <div class="Prix_et_Quantites_et_Suppr">

                                    <div class="Prix_et_Quantites_block">
                                        <h2><?php echo $listeArticle[$numArt]['prix_produit_commande_ttc']." €"; ?></h2>

                                        <form action="./panier.php?id_produit=<?php echo $listeArticle[$numArt]['id_produit'] ?>" method="POST">
                                            <select class="quantites-produit" onchange="this.form.submit()" name="quantiteSelect" values="<?php echo $listeArticle[$numArt]['quantite']?>">
                                                <?php   
                                                    for ($i=0; $i <= 99; $i++) { 
                                                        if($i != $listeArticle[$numArt]['quantite']) {
                                                            echo "<option value='$i'>$i</option>";
                                                        } else {
                                                            echo "<option value='$i' selected>$i</option>";
                                                        }
                                                    }
                                                ?>
                                            </select>
                                        </form>
                                    </div>

                                    <div class="Supprimer_Produit">
                                        <a class="supprimer-article" href="./panier.php?supprimerProduit=<?php echo $listeArticle[$numArt]['id_produit'] ?>"> Supprimer</a>
                                    </div>

                                </div>

                            </article>
                        
                    </section>
                <?php endfor; ?>
            </div>

            <div class="recap">
                <h2>Récapitulatif</h2>
                <hr>
                <section>

                    <article class="panier-prix">
                        <h2 class="prix-panier-texte"><?php echo "Panier ($nbproduit)"; ?></h2>
                        <h2 class="prix-base"><?php echo "$prixTotal €"; ?></h2>
                    </article>

                    <article class="frais">
                        <h3 class="frais-livraison-texte">Frais de livraison</h3>
                            <div class="logo_et_texte_livraison">
                                <img src="../img/livraison_gratuite.png" alt="Logo Livrasion Gratuite" title="Livraison Gratuite" width="50" height="40">
                                <h3 class="frais-livraison">Gratuit</h3>
                            </div>
                    </article>

                    <hr>

                    <article class="calcul-prix">
                        <div class="Prix_totaux">
                            <div class="prix-ht">
                                <h2>Prix HT</h2>
                                <h2><?php echo "$prix_ht €"; ?></h2>
                            </div>

                            <div class="tva-incluse">
                                <h3>Taxes incluses</h3>
                                <h3><?php echo "$tva_inclus €"; ?></h3>
                            </div>

                            <div class="prix-ttc">
                                <h2 class="prix-total">Prix Total</h2>
                                <h2 class="prix-total"><?php echo "$prixTotal €"; ?></h2>
                            </div>
                        </div>
                    </article>
                    <?php if(isset($_SESSION['id_client'])):?>
                        <form action="paiement.php" method="POST">
                            <button class="valider-panier" type="submit">Valider mon panier</button>
                        </form>
                    <?php else:?>
                        <form action="connexion.php" method="POST">
                            <button class="valider-panier" type="submit">Valider mon panier</button>
                        </form>
                    <?php endif;?>

                </section>

                <button class="vider-panier open-button vider-panier-texte" onclick="openForm()"> &nbsp; Vider le panier</button>

                <p class="tva-texte">Prix TTC, TVA appliquée sur la base du pays : France (métropolitaine)</p>

            </div>

            <?php else: ?>
                <div class="block-panierVide">
                    <h1 class="titre-panierVide">Votre panier est tristement vide...</h1>
                    
                    <div class="block-texte_panierVide">
                        <img src="../img/panier_triste.png" alt="Logo Panier Triste" title="Panier triste" width="350" height="300px">
                        <h2 class="texte-panierVide">Consulter le <a href="index.php">catalogue</a> pour trouver des idées !</h2>
                    </div>
                </div>
            <?php endif; ?>

        </main>

        <!-- Pop-up Suppression du panier -->

        <div class="form-popup" id="popup-Form">
            <form action="panier.php" method="GET" class="form-container">
                <h2 class="message-suppr-panier">Cette action de suppression videra le panier de tous les articles actuellement présents. Souaitez-vous continuer ?</h2>
                <button type="submit" class="btn open-button" name="viderpanier">Vider mon panier</button>
                <button type="submit" class="btn cancel open-button" onclick="closeForm()">Annuler</button>
            </form>
        </div>

        <footer>
            <?php include("footer.php"); ?>
        </footer>

        <script>
            function openForm() {
                document.getElementById("popup-Form").style.display = "block";
                document.querySelector(".main_panier").style.filter = "blur(1.2px)";
                document.querySelector("header").style.filter = "blur(1.2px)";
                document.querySelector("footer").style.filter = "blur(1.2px)";
            }
            function closeForm() {
                document.getElementById("popup-Form").style.display = "none";
                document.querySelector(".main_panier").style.filter = "blur(0)";
                document.querySelector("header").style.filter = "blur(0)";
                document.querySelector("footer").style.filter = "blur(0)";
            }
        </script>

    </body>

</html>