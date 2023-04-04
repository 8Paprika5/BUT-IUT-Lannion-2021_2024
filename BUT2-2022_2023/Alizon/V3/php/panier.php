<?php
    include("./fonctions/Session.php");
    include("./fonctions/fonctions.php");

    $func = infos_panier($_COOKIE['id_panier']);
    /** Tableau $func
     * ['listeArticles']
     * ['nbproduit']
     * ['prixTotal']
     * ['prix_ht']
     * 
     ** Tableau $func['listreArticle']
     * ['vendeur']      
     * ['nom_produit']          
     * ['nom_categorie']
     * ['stock']
     * ['prix_total_ttc']
     * ['prix_produit_commande_ttc']
     * ['quantite']
     * ['id_produit']
     */

    $listeArticle = $func['listeArticles'];
    $nbproduit = $func['nbproduit'];
    $prixTotal = $func['prixTotal'];
    $prix_ht = $func['prix_ht'];
    $tva_inclus = $prixTotal - $prix_ht;

    // Si l'utilisateur veut vider son panier
    if(isset($_GET['viderpanier'])) {
        ViderPanier();
    }

    // Si l'utilisateur veut supprimer un produit du panier
    if(isset($_GET['supprimerProduit'])) {
        supprimerProduit($_GET['supprimerProduit']);
    }

    // Si l'utilisateur veut modifier une quantité d'un produit du panier
    if(isset($_POST['quantiteSelect'])) {
        modifierQuantite($_POST['quantiteSelect'],$_GET['id_produit']);
    }


    // Commander à nouveau
    if(isset($_POST['commandeID'])) {
        $lCommandesCours = details_commande($_SESSION['id_client']);
        
        $i = 0;
        $trouve = false;
        while ($i < sizeof($lCommandesCours['listeCommandeTotale']) && !$trouve) {
            $trouve = ($lCommandesCours['listeCommandeTotale'][$i]['ID_Commande']==$_POST['commandeID']);
            if(!$trouve){
                $i++;
            }
        }
        recommander($lCommandesCours['listeCommandeTotale'][$i]);
        $_POST['commandeID'] = null;
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
        
        <!-- Pop-up : Produit retiré -->
        <?php if(isset($_GET["produitretiré"])) {
            echo "<div class='alert retirerpanier'>";
            echo "<i class='fa-regular fa-circle-check fa-2x'></i>";
            echo "<p>Produit retiré</p>";
            echo "</div>";
        }
        ?>

        <main class="main_panier">

            <?php if($nbproduit > 0 ): ?>

            <div class="div-panier">

                <!-- Bloc pour Valider ou Supprimer son panier Affichant en format Tablette et Téléphone -->
                <div class="infos-panier-sticky-responsive">
                    <div class="block-nbr-prix">
                        <h2 class="titre-panier"><?php if($nbproduit > 1){echo "Votre Panier ($nbproduit produits)";}else{echo "Votre Panier ($nbproduit produit)";}?></h2>

                        <h2 class="prix-base_responsive"><?php echo "$prixTotal €"; ?></h2>
                    </div>

                    <div class="boutons-panier-responsive">
                        <form class="form-valider-responsive" action="paiement.php" method="POST">
                            <button class="valider-panier-resp" type="submit">Valider mon panier</button>
                        </form>

                        <button class="vider-panier-responsive open-button">&nbsp; Vider le panier</button>
                    </div>
                </div>

                <div class="blockPanier">

                    <?php for ($numArt=0; $numArt < $nbproduit; $numArt++): ?>

                        <section>

                            <article class="Produit-Panier">

                                <div class="Produit-Global">

                                    <!-- Image du Produit -->
                                    <img src="<?php echo str_replace(' ', "_","../img/catalogue/Produits/".$listeArticle[$numArt]['id_produit']."/".$listeArticle[$numArt]['images1']); ?>" alt="photo" class="photo-prod"/>
                                    
                                    <div class="Produit-Infos">

                                        <!-- Nom du Produit -->
                                        <h3 class="Titre-Produit_Panier cursor" onclick='location.href="produit.php?idProduit=<?php echo $listeArticle[$numArt]['id_produit'];?>";'><?php echo "{$listeArticle[$numArt]['nom_produit']}";?></h3>

                                        <!-- Catégorie Produit -->
                                        <h5 class="Categorie-Produit_Panier"><?php echo "{$listeArticle[$numArt]['nom_categorie']}";?></h5>

                                        <!-- Logo Disponible Produit -->
                                        <div class="stockage-panier">
                                            <h4 class="Stock-Produit_Panier">En stock</h4>
                                            <img src="../img/stock.png" alt="logo stock" title="Stock Indisponible" width="50" height="50">
                                        </div>

                                    </div>

                                </div>  

                                <div class="Prix_et_Quantites_et_Suppr">

                                    <div class="Prix_et_Quantites_block">

                                        <!-- Prix TTC du Produit -->
                                        <h2><?php echo $listeArticle[$numArt]['prix_produit_commande_ttc']." €"; ?></h2>

                                        <!-- Quantité de Produit qu'on peut changer -->
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

                                    <!-- Bouton Supprimer le produit du Panier -->
                                    <div class="Supprimer_Produit">
                                        <a class="supprimer-article" href="./panier.php?supprimerProduit=<?php echo $listeArticle[$numArt]['id_produit']?>"> Supprimer</a>
                                    </div>

                                </div>

                            </article>
                            
                        </section>
                    <?php endfor; ?>

                </div>

            </div>

            <div class="recap">

                <h2>Récapitulatif</h2>
                <hr>

                <section>

                    <!-- Nombre de produits et le prix total TTC -->
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

                            <!-- Prix HT -->
                            <div class="prix-ht">
                                <h2>Prix HT</h2>
                                <h2><?php echo "$prix_ht €"; ?></h2>
                            </div>

                            <!-- TVA incluse dans le prix HT -->
                            <div class="tva-incluse">
                                <h3>TVA</h3>
                                <h3><?php echo "$tva_inclus €"; ?></h3>
                            </div>
                        
                            <!-- Prix TTC avec TVA -->
                            <div class="prix-ttc">
                                <h2 class="prix-total">Prix Total</h2>
                                <h2 class="prix-total"><?php echo "$prixTotal €"; ?></h2>
                            </div>

                        </div>
                    </article>

                    <!-- Vérification si le client est connecté pour Valider le Panier -->
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

                <!-- Vider tout le panier affichant une Pop Up de confirmation de suppression -->
                <button class="vider-panier open-button vider-panier-texte"> &nbsp; Vider le panier</button>

                <p class="tva-texte">Prix TTC, TVA appliquée sur la base du pays : France (métropolitaine)</p>

            </div>

            <!-- S'il n'y a pas de produit dans le panier => Page Panier vide -->
            <?php else: ?>
                <div class="block-panierVide">
                    <h1 class="titre-panierVide">Votre panier est tristement vide...</h1>
                    
                    <div class="block-texte_panierVide">
                        <img src="../img/panier_triste.png" alt="Logo Panier Triste" title="Panier triste" width="350" height="300px">
                        <h2 class="texte-panierVide">Consulter le <a href="catalogue.php">catalogue</a> pour trouver des idées !</h2>
                    </div>
                </div>
            <?php endif; ?>

        </main>

        <!-- Pop-up Suppression du panier -->

        <div class="fond_opacity"></div>

        <div class="form-popup" id="popup-Form">
            <form action="panier.php" method="GET" class="form-container">
                <h2 class="message-suppr-panier">Cette action de suppression videra le panier de tous les articles actuellement présents. Souhaitez-vous continuer ?</h2>
                <button type="submit" class="btn open-button" name="viderpanier">Vider mon panier</button>
                <button type="submit" class="btn cancel open-button" onclick="closeForm()">Annuler</button>
            </form>
        </div>

        <footer>
            <?php include("footer.php"); ?>
        </footer>

        <script>
            /* ######################################################################################################### */
            /* ######################################### POP UP PANIER VIDER ########################################### */
            /* ######################################################################################################### */

            var popUP_Vider = document.getElementById("popup-Form");
            var btn_Vider = document.querySelector(".vider-panier");
            var fond_opacity = document.querySelector(".fond_opacity");
            var btn_Vider2 = document.querySelector(".vider-panier-responsive");

            const isHidden1 = () => popUP_Vider.classList.contains("form-popup2--affiche");

            popUP_Vider.addEventListener("transitionend", function () {
                if (isHidden1()) {
                    popUP_Vider.style.display = "block";
                    fond_opacity.style.display = "block";
                }
            });

            btn_Vider.addEventListener("click", function (e) {
                if (isHidden1()) {
                    popUP_Vider.style.removeProperty("display");
                    setTimeout(() => popUP_Vider.classList.remove("form-popup2--affiche"), 0);
                    fond_opacity.style.removeProperty("display");
                    setTimeout(() => popUP_Vider.classList.remove("fond_opacity--affiche"), 0);
                } 
                else {
                    popUP_Vider.classList.add("form-popup2--affiche");
                    fond_opacity.classList.add("fond_opacity--affiche");
                }
            });

            btn_Vider2.addEventListener("click", function (e) {
                if (isHidden1()) {
                    popUP_Vider.style.removeProperty("display");
                    setTimeout(() => popUP_Vider.classList.remove("form-popup2--affiche"), 0);
                    fond_opacity.style.removeProperty("display");
                    setTimeout(() => popUP_Vider.classList.remove("fond_opacity--affiche"), 0);
                } 
                else {
                    popUP_Vider.classList.add("form-popup2--affiche");
                    fond_opacity.classList.add("fond_opacity--affiche");
                }
            });

            function closeForm() {
                /**
                 * DESCRIPTION : Ferme la Pop up de confirmation de suppression
                 */
                if (isHidden1()) {
                    popUP_Vider.style.removeProperty("display");
                    setTimeout(() => popUP_Vider.classList.remove("form-popup2--affiche"), 0);
                } else {
                    popUP_Vider.classList.add("form-popup2--affiche");
                }
            }
        </script>

    </body>

</html>
