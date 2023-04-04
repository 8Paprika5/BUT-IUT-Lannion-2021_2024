<?php 

    function carte_produit($produit)
    {
    ?>
        <div class="carte_produit cursor" onclick='location.href="produit.php?idProduit=<?php echo $produit['id_produit'];?>";'>
        <!--
            <img src="../img/<?php //echo str_replace(' ', "_","../img/catalogue/".$produit['nom_categorie']."/".$produit['nom_souscategorie']."/".$produit['id_produit']."/".$produit['images1']);; ?>" alt="<?php echo $produit['nom_produit']; ?>" class="carte_produit--img"> 
        -->
        <img src="../img/Catalogue/Epicerie/Gateaux/1/Images1.jpg" alt="<?php echo $produit['nom_produit']; ?>" class="carte_produit--img">
        <h2><?php echo $produit['nom_produit']?></h2>

        <div class="carte_produit--bloc">
            <?php for( $i = 0 ; $i < $produit['moyenne_note_produit'] ; $i++)
            {
                echo "<i class='fa-solid fa-star gold'></i>";
            }
            ?>
            <?php for( $i = 0 ; $i < 5-$produit['moyenne_note_produit'] ; $i++)
            {
                echo "<i class='fa-solid fa-star'></i>";
            }
            ?>
            <div class="carte_produit--bloc__delivery">
                <img src="../img/image2vector.svg" alt="logo de livraison gratuite">
                <p>livraison gratuite</p>
            </div>
            
            <h3><?php echo $produit['prix_vente_ttc']."€ TTC"?></h3>
        </div>
    </div>
    <?php
    }
?>

