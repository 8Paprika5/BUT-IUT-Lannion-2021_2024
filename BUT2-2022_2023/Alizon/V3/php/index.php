<?php 
    include("fonctions/Session.php");
    include("fonctions/fonctions.php");
    
    include("fonctions/carte_categorie.php");
    include("fonctions/carte_produit.php");
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <header>
        <?php
            if(isset($_SESSION['no_admin'])) {
                include('header_admin.php');
            }
            else { 
                include('header.php');
                $_SESSION['no_admin'] = null;
            }
            // suppression variable de session vendeur quand redirection sur index
            unset($_SESSION["vendeur"]);
        ?>
    </header>
    <?php
    
        if(!isset($_SESSION["deco"])){

            if(isset($_GET["connected"])){
                echo "<div class='alert'>";
                echo "<i class='fa-regular fa-circle-check fa-2x'></i>";
                echo "<p>Vous êtes connecté !</p>";
                echo "</div>";
            }
               
            if(isset($_SESSION['admin_deconnecte'])){
                echo "<div class='alert deco no_bootstrap'>";
                echo "<i class='fa-regular fa-circle-check fa-2x'></i>";
                echo "<p>Vous êtes déconnecté </p>";
                echo "</div>";
                $_SESSION['admin_deconnecte'] = null;
            }
        }
        if(isset($_SESSION['vendeur_deconnecte'])){
            echo "<div class='alert deco no_bootstrap'>";
            echo "<i class='fa-regular fa-circle-check fa-2x'></i>";
            echo "<p>Vous êtes déconnecté </p>";
            echo "</div>";
            $_SESSION['vendeur_deconnecte'] = null;
            
        }
        

    ?>
    <main class="main-index">

        <!-- Carousel -->
        <div id="demo" class="carousel slide" data-bs-ride="carousel">
            <!-- Indicators/dots -->
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#demo" data-bs-slide-to="0" class="active"></button>
                <button type="button" data-bs-target="#demo" data-bs-slide-to="1"></button>
                <button type="button" data-bs-target="#demo" data-bs-slide-to="2"></button>
            </div>

            <!-- The slideshow/carousel -->
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="../img/palets_bretons.png" alt="Image palets bretons" class="d-block">
                    <div class="carousel-caption">
                        <h3>BON PLAN</h3>
                        <h4>Palets Bretons</h4>
                    </div>
                </div>

                <div class="carousel-item">
                    <img src="../img/crepes.png" alt="Image crêpes" class="d-block">
                    <div class="carousel-caption second">
                        <h3>Offre Spéciale Crêpes</h3>
                        <h4>-50%</h4>
                    </div>
                </div>

                <div class="carousel-item">
                    <img src="../img/palets_bretons.png" alt="Image palets bretons" class="d-block">
                </div>
            </div>

            <!-- Left and right controls/icons -->
            <button class="carousel-control-prev" type="button" data-bs-target="#demo" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#demo" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>

        <h2>LA SÉLECTION ALIZON</h2>
        <hr>

        <div class="selec_categories">
            <?php 
                $listeCategories = liste_Categories();
                /** Tableau $listeCategories
                 * [Gateaux]
                 * [Déjeuner]
                 * [Pull]
                 * [Pantalons]
                 * [Vêtements de pluie]
                 * [Poster]
                 * . . .
                 */
                
                foreach($listeCategories[0] as $SousCategorie => $categorie){
                    //affichage de la catégorie sous la forme d'une carte
                    carte_categorie(array($categorie,$SousCategorie));
                }
            ?>
        </div>

        <aside class="en_plus">
            <div class="en_plus--origine">
                <div class="en_plus--origine__txt">
                    <h3>PRODUITS</h3>
                    <h4>100% Bretons</h4>
                </div>

                <img src="../img/hermine.svg" alt="logo Hermine">
            </div>

            <div class="en_plus--livraison">
                <img src="../img/money-euro-circle-fill.svg" alt="logo euro">
                <h3>Livraison offerte</h3>
                <h4>Partout en Bretagne</h4>
            </div>

            <div class="en_plus--sav">
                <i class="fa-solid fa-screwdriver-wrench fa-4x"></i>
                <h4>SAV disponible partout en Bretagne</h4>
            </div>
        </aside>

        <h2>LES PLUS RECHERCHÉES</h2>
        <hr>

        <div class="plus_recherchees">

            <?php
                $listeProduits_plus_recherchees = listeProduits_plus_recherchees();
                /** Tableau $listeProduits_plus_recherchees, pour chaque produit :
                * ['id_produit']      
                * ['nom_produit']          
                * ['prix_vente_ht']      
                * ['prix_vente_ttc']      
                * ['quantite_disponnible']
                * ['description_produit']
                * ['images1']
                * ['images2']
                * ['images3']
                * ['nom_souscategorie']
                * ['nom_categorie']
                * ['moyenne_note_produit']
                */
                foreach($listeProduits_plus_recherchees as $produit){
                    carte_produit($produit);
                }
            ?>
        </div>

    </main>

    <!-- Si le cookie de panier n'existe pas, cela affiche la pop-up d'acceptation des cookies -->
    <?php 
        if(!isset($_COOKIE['id_panier'])) {
            bloc_cookies();
        }
    ?>

    <footer>
        <?php include("footer.php"); ?>
    </footer>
</body>

</html>