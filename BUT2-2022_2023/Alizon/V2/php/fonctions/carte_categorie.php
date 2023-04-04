<?php 

    function carte_categorie($liste){
        $categorie = $liste[0];
        $SousCategorie = $liste[1];
        ?>
        <div class="carte_categorie"  onclick='location.href="catalogue.php?terme=<?php echo $SousCategorie;?>";'>
            <div class="carte_categorie--img" >
                <img src="../img/catalogue/<?php echo str_replace(' ','_',"$categorie/$SousCategorie/$SousCategorie.jpg")?>" alt="photo de bière" >
            </div>
            <h4><?php echo "$SousCategorie"?></h4>
        </div>
        <?php
    }
    ?>
