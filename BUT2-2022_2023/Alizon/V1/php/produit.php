<?php
    include('Session.php');
    $id_Produit = $_GET['idProduit'];

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
    <head>
        <meta charset="utf-8" />
        <title></title>
        <meta name="description" content="" />
        <meta name="keywords" content="" />
        <link rel="stylesheet" href="../css/style.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    </head>
    <body>
        <header>
            <?php include('header.php'); ?>
        </header>

        <main class="main_produit">
            <section>
                <?php
                    
                    $sth = $dbh -> prepare("SELECT nom_produit FROM $schema._Produit WHERE id_produit = '$id_Produit'");
                    $sth->execute();
                    $res = $sth->fetchAll();
                    echo "<h1> ".$res[0]['nom_produit']." </h1>";
                ?>
                <div>
                    <aside style="width: 99%;">
                        <img src="img/heart-circle-plus-solid.svg" alt="photo" class ="coeur" />
                        <div class="photos_prod">
                            <?php
                                $sth = $dbh -> prepare("SELECT images1,images2,images3 FROM $schema._Produit WHERE id_produit = '$id_Produit'");
                                $sth->execute();
                                $res = $sth->fetchAll();

                                if($res[0]['images1']!=NULL) {
                                    echo "<div class='mySlides'>";
                                    echo "<img src='$res' alt='photo' class='photo-prod'>";
                                    echo "</div>";

                                    if($res[0]['images2']!=NULL) {
                                        echo "<div class='mySlides'>";
                                        echo "<img src='$res' alt='photo' class='photo-prod'>";
                                        echo "</div>";

                                        if($res[0]['images3']!=NULL) {
                                            echo "<div class='mySlides'>";
                                            echo "<img src='$res' alt='photo' class='photo-prod'>";
                                            echo "</div>";
                                        }
                                    }
                                }

                            ?>

                            <!-- Next and previous buttons -->
                            <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
                            <a class="next" onclick="plusSlides(1)">&#10095;</a>

                            <!-- images text -->
                            <div class="row">
                                <?php
                                    $sth = $dbh -> prepare("SELECT images1,images2,images3 FROM $schema._Produit WHERE id_produit = '$id_Produit'");
                                    $sth->execute();
                                    $res = $sth->fetchAll();

                                    if($res[0]['images1']!=NULL) {
                                        echo "<div class='column'>";
                                        echo "<img class='demo cursor' src='$res' style='width:100%' onclick='currentSlide(1)' alt='chat'>";
                                        echo "</div>";


                                        if($res[0]['images2']!=NULL) {
                                            echo "<div class='column'>";
                                            echo "<img class='demo cursor' src='$res' style='width:100%' onclick='currentSlide(2)' alt='chat'>";
                                            echo "</div>";

                                            if($res[0]['images3']!=NULL) {
                                                echo "<div class='column'>";
                                                echo "<img class='demo cursor' src='$res' style='width:100%' onclick='currentSlide(3)' alt='chat'>";
                                                echo "</div>";
                                            }
                                        }
                                    }

                                ?>
                            </div>

                        </div>
                    </aside>
            <section>
                <div>
                    <article class="infos">
                        <aside>
                            <div>
                                <h4>Prix TTC</h4>
                                <?php
                                    
                                    $sth = $dbh -> prepare("SELECT prix_vente_ttc FROM $schema._Produit WHERE id_produit = '$id_Produit'");
                                    $res = $sth->execute();
                                    echo "<h2> ".$res." </h2>";
                                ?>
                            </div>

                            <div>
                                <div class="stars">
                                    <?php
                                        
                                        $sth = $dbh -> prepare("SELECT moyenne_note_produit FROM $schema._Produit WHERE id_produit = '$id_Produit'");
                                        $res = $sth->execute();

                                        for ($i = 0; $i < $res ; $i++)
                                        {
                                            echo '<img src="../img/star-solid.svg" alt="avis clients" class="fonts">';
                                            echo '<i class="fa fa-star gold"></i>';
                                        }
                                        for ($i = 0; $i < 5-$res; $i++){
                                            echo '<i class="fa fa-star grey"></i>';
                                        }
                                    ?>
                                </div>
                                <div>
                                    <h5>Quantité : </h5>
                                    <select class="quantite">
                                        <option value="0">1</option>
                                    </select>
                                </div>
                            </div>

                            <form action="">
                                <button class="ajout">Ajouter au panier</button>
                            </form>
                        </aside>
                        <?php
                            
                            $sth = $dbh -> prepare("SELECT description_produit FROM $schema._Produit WHERE id_produit = '$id_Produit'");
                            $res = $sth->execute();
                            echo "<p> ".$res." </p>";
                        ?>
                        <div>
                        <?php
                            
                            $sth = $dbh -> prepare("SELECT description_produit FROM $schema._Produit WHERE id_produit = '$id_Produit'");
                            $res = $sth->execute();
                            echo "<p> ".$res." </p>";
                        ?>
                            <img src="../img/stock_mieux.png" alt="logo stock" class="logo_infos" /> 
                            <p>Stock du produit</p>
                            <img src="../img/livraison-rapide (1) (1).png" alt="logo livraison" class="logo_infos" /> 
                            <p>Livraison gratuite et rapide en 5 minutes !</p>
                        </div>
                    </article>
                </div>
            </section>
            <section class="commentaires">
                <aside>
                    <h3>Commentaires</h3>
                    <select>
                        <option value="0">Trier par</option>
                    </select>
                </aside>
                <?php
                    

                    // Nombre d'avis sur le produit
                    $sth = $dbh -> prepare("SELECT count(*) FROM $schema._Avis NATURAL JOIN $schema._Produit WHERE id_produit = '$id_Produit'");
                    $sth->execute();
                    $nbrNotes = $sth->fetchAll();
                   
                    $sth = $dbh -> prepare("SELECT * FROM $schema._Avis NATURAL JOIN $schema._Produit WHERE id_produit = '$id_Produit'");
                    $sth->execute();
                    $tabComplet = $sth->fetchAll();
                    
                    for ($i=0; $i < $nbrNotes[0]['count']; $i++):?>
                        <article class='carte_commentaire'>
                            <aside class='carte_commentaire--bloc'>
                                <img src='../img/user-solid.svg' alt='photo de profil' class='font'>
                                <?php
                                    $sth = $dbh -> prepare("SELECT nom_client FROM $schema._Client NATURAL JOIN $schema._Avis WHERE id_client = {$tabComplet[$i][0]}");
                                    $nomClient = $sth->execute();
                                    ?>
                        <h2> $nomClient </h2>
                        <div class ='stars'>
                        <?php
                        for ($i=0; $i < $tabComplet[$i][2]; $i++) { 
                            echo "<i class='fa fa-star gold'></i>";
                        }
                        for ($i=0; $i < 5-$tabComplet[$i][2]; $i++) { 
                            echo "<i class='fa fa-star grey'></i>";
                        }
                        ?>
                        <p>
                            <?php echo $tabComplet[$i][3] ?>
                        </p> 
                            <a href=''  class='signaler'>Signaler</a>
                        </article>

                                 
                    <?php endfor;?>
            </section>
        </main>
        <footer>
            <?php include('footer.php'); ?>
        </footer>
    </body>

    <script src="menu-burger.js"></script>

</html>