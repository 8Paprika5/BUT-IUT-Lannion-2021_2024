<?php function carte_categorie($liste){
        $categorie = $liste[0];
        $SousCategorie = $liste[1];
        /** tableau $liste : 
         * [0] => Categorie 
         * [1] => Souscategorie
         */
        $chemin = "catalogue.php?categorie=".str_replace(' ', "_",$categorie)."&Scategorie=".str_replace(' ', "_",$SousCategorie);
        ?>
        <div class="carte_categorie"  onclick='location.href="<?php echo $chemin;?>"'>
            <div class="carte_categorie--img" >
                <img src="../img/catalogue/<?php echo str_replace(' ','_',"$categorie/$SousCategorie/$SousCategorie.jpg")?>" alt="Image de Catégorie" >
            </div>
            <h4><?php echo "$SousCategorie"?></h4>
        </div>
        <?php
    }
    ?>
