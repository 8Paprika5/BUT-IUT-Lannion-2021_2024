<?php function carte_produit($produit){
    /** Tableau $produit :
     * [id_produit]
     * [nom_produit]
     * [prix_vente_ht]
     * [prix_vente_ttc]
     * [quantite_disponnible]
     * [description_produit]
     * [images1]
     * [images2]
     * [images3]
     * [nom_souscategorie]
     * [nom_categorie]
     * [moyenne_note_produit]
     */
?>

<div class="carte_produit cursor" onclick="location.href='produit.php?idProduit=<?php echo $produit['id_produit'];?>'";>

    <div class="image_titre_note">
        <img src="../img/Catalogue/Produits/<?php echo $produit['id_produit']."/".$produit['images1'];?>"
            alt="<?php echo $produit['nom_produit']; ?>" class="carte_produit--img">
        <h2><?php echo $produit['nom_produit']?></h2>
        <div class="carte_produit--bloc">
            <?php for( $i = 0 ; $i < $produit['moyenne_note_produit'] ; $i++){
                echo "<i class='fa-solid fa-star gold'></i>";
            }
            ?>
            <?php for( $i = 0 ; $i < 5-$produit['moyenne_note_produit'] ; $i++){
                echo "<i class='fa-solid fa-star'></i>";
            }
            ?>
        </div>
    </div>
    <div class="stock_prix">
        <?php if($produit["quantite_disponnible"]>=1):?>
        <h4 class="produit_enStock">En Stock</h4>
        <?php else: ?>
        <h4 class="produit_horsStock">Hors Stock</h4>
        <?php endif;?>
        <h3><?php echo $produit['prix_vente_ttc']."€ TTC"?></h3>
    </div>
</div>
<?php
    }
?>