<?php
    include("fonctions/Session.php");
    include("fonctions/fonctions.php");

    $catalogue = null;
    if(isset($_POST['ajoutPanier'])) {
        ajoutPanier($_GET['id_produit'], $_POST['nbProduits']);
    }

    // Recherche par Mot Clés
    $terme = $_GET["terme"];
    
    if(isset($terme) AND (strlen($terme) != 0)) {
        $terme = strtolower($terme);
        if(strlen($terme) <= 3) {
            $terme = ucfirst($terme);
        }
        $catalogue = donnees_catalogue_mot_cles($terme);
    } else {
        // A changer à la fin du projet, cela renverra vers l'index
        $catalogue = donnees_catalogue($terme);
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalogue</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
    <body>
        <header>
            <?php include("header.php"); ?>
        </header>

        <main class="main-catalogue">
            <?php 
            if(sizeof($catalogue) == 0) { 
                echo "<p class='erreur'>Aucun résultat pour ".str_replace('_',' ',$terme)."</p>"; 
            }
            else { 
                if(sizeof($catalogue) == 1) {
                    echo "<p class='trouve'>1 résultat pour ".str_replace('_',' ',$terme)."</p>";
                } else {
                    echo "<p class='trouve'>".sizeof($catalogue)." résultats pour ".str_replace('_',' ',$terme)."</p>";
                }
            }
            echo "<hr>";
            foreach ($catalogue as $produit):?> 
            
                <div class="carte_produit_ajout">

                    <a class="carte_produit_lien" href="produit.php?idProduit=<?php echo $produit["id_produit"]; ?>">
                    
                        <div class="carte_produit_ajout--image">
                            <!-- <img src="<?php echo str_replace(' ', "_","../img/catalogue/".$produit['nom_categorie']."/".$produit['nom_souscategorie']."/".$produit['id_produit']."/".$produit['images1']); ?>" alt="photo du produit"> -->
                            <img src="../img/catalogue/Chat_Image.png" alt="chat">
                        </div>

                        <div class="carte_produit_ajout--infos">
                            <div class="carte_produit_ajout--image__description">
                                <h2><?php echo $produit["nom_produit"]; ?></h2>
                                <p class="description_produit-catalogue"><?php echo $produit["description_produit"]; ?></p>
                            </div>
                        </div>

                    </a>

                    <div class="carte_produit_ajout--boutons">

                        <div class="carte_produit_ajout--boutons-stock">
                            <?php if($produit["quantite_disponnible"] > 0):?>
                                <h4 class="Texte-stock">Disponiblité</h4>
                                <img src="../img/stock.png" alt="Logo disponible" title="En Stock">
                            <?php else:?>
                                <h4 class="Texte-stock">Disponiblité</h4>
                                <img src="../img/rupture-de-stock.png" alt="Logo Indisponible" title="Hors Stock">
                            <?php endif;?>
                        </div>

                        <div class="carte_produit--boutons-ajout">  
                            <h3 class="prix-produit_catalogue"><?php echo $produit["prix_vente_ht"]." € HT"; ?></h3>
                            <h3 class="prix-produit_catalogue"><?php echo $produit["prix_vente_ttc"]." € TTC"; ?></h3>
                            <div class="carte_produit_ajout--boutons__panier">
                                <form action="catalogue.php?id_produit=<?php echo $produit["id_produit"]; ?>" method="POST">
                                    <button type='submit' name='ajoutPanier'>Ajouter au panier</button>
                                    <select name="nbProduits">
                                        <?php   
                                            for ($i=1; $i <= $produit["quantite_disponnible"]; $i++) { 
                                                echo "<option value='$i'>$i</option>";
                                            }
                                        ?>
                                    </select>
                                    
                                </form>
                            </div>
                        </div>

                    </div>

                </div>
                
            <?php endforeach; ?>

        </main>

        <footer>
            <?php include("footer.php"); ?>
        </footer>
    </body>
</html>