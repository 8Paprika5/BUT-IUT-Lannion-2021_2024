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
            <?php include("header.php"); ?>
        </header>

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
                        <img src="../img/crepes.png" alt="Image crêpes" class="d-block" >
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

            <h2>LA SELECTION ALIZON</h2>
            <hr>

            <div class="selec_categories">
                <?php 
                    $listeCategories = liste_Categories();

                    foreach($listeCategories[0] as $SousCategorie => $categorie){
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

            <h2>LES PLUS RECHERCHEES</h2>
            <hr>
            
            <div class="plus_recherchees">
                
                <?php
                    $listeProduits_plus_recherchees = listeProduits_plus_recherchees();
                   
                    foreach($listeProduits_plus_recherchees as $produit){
                        carte_produit($produit);
                    }
                ?>
            </div>
        </main>

        <footer>
            <?php include("footer.php"); ?>
        </footer>
    </body>
    
</html>
