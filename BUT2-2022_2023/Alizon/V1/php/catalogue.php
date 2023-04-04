<?php
    include("Session.php");
    $catalogue = $dbh->prepare("SELECT * FROM ALIZON._Produit ");
    $catalogue->execute();
    $catalogue = $catalogue->fetchAll();
    // echo '<pre>';
    // print_r($catalogue);
    // echo '</pre>';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalogue</title>
    <link rel="stylesheet" href="style.css">
    <link rel='stylesheet' type='text/css' media='screen' href='../css/Catalogue.css'>
    <link rel='stylesheet' type='text/css' media='screen' href='../css/style.css'>
    <link rel='stylesheet' type='text/css' media='screen' href='../css/panier.css'>
</head>
    <body>
        <header>
            <?php include("header.php"); ?>
        </header>
        <main>
            <h1>Catalogue des produits</h1>
            <?php foreach ($catalogue as $produit):?> 
                <a class="carte_produit_ajout" href="produit.php?idProduit=<?php echo $produit["id_produit"]; ?>">
                <div class="carte_produit_ajout--infos">
                    <img src="../img/catalogue/Chat Image.png" alt="photo du produit">
                    <div class="carte_produit_ajout--infos__description">
                        <h2><?php echo $produit["nom_produit"]; ?></h2>
                        <p><?php echo $produit["description_produit"]; ?></p>
                    </div>
                </div>

                <div class="carte_produit_ajout--boutons">
                    <?php if($produit["quantite_disponnible"] > 0):?>
                        <h4>En stock</h4>
                        <img src="../img/stock.png" alt="logo disponibilité du produit" />
                    <?php else:?>
                        <h4>Indisponible</h4>
                        <img src="" alt="logo stock indisponible" />
                    <?php endif;?>
                </div>
                <div class="carte_produit_ajout--boutons__add">
                    <h3><?php echo $produit["prix_vente_ttc"]." €"; ?></h3>
                    <div class="carte_produit_ajout--boutons__panier">
                        <form action="./fonctions/AjoutPanier.php?id_produit=<?php echo $produit["id_produit"]; ?>" method="POST">
                            <button type='submit'>Ajouter au panier</button>
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
                </a>
            <?php endforeach; ?>
            

        </main>

        <footer>
            <?php include("footer.php"); ?>
        </footer>
    </body>
</html>
