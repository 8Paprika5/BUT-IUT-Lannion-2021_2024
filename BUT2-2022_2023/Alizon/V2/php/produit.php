<script>
    function avertissementCommentaireSignale(){
        alert("Commentaire signalé !");
    }
    function avertissementCommentaireDejaSignale(){
        alert("Vous avez déjà signalé ce commentaire !");
        //Pour changer le texte mais pour le moment ça marche pas
        //var el = document.getElementById('signalementButton' + <?php echo $_POST['CommentaireId'] ?>);
        //el.textContent='Authentification N°1';
    }
    

</script>


<?php
    include('fonctions/Session.php');
    include('fonctions/fonctions.php');
    $produit = infos_produit($_GET['idProduit'])[0];
    
    if(!isset($_POST['qteSelect'])){
        $qteSelect = 1;
    }else{
        $qteSelect = $_POST['qteSelect'];
    }

    

    if(isset($_POST['ajoutPanier'])) {
            ajoutPanier($_GET['id_produit'], $_GET['qteSelect']);
    }









    if(isset($_POST['CommentaireId'])) {
        $signaleur = SignaleurComment($_SESSION['id_client'],$_POST['CommentaireId']);
        if ($signaleur == null){
            echo "<script> avertissementCommentaireSignale(); </script>";
            ajouteSignalement($_SESSION['id_client'],$_POST['CommentaireId']);
        }else{
            echo "<script> avertissementCommentaireDejaSignale(); </script>";
            
        }
        $_POST['CommentaireId'] = null;
       
    }
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
    <head>
        <meta charset="utf-8" >
        <title>
            <?php
                echo $produit['nom_produit'];
            ?>
        </title>
        <meta name="description" content="" >
        <meta name="keywords" content="" >

    </head>
    <body>
        <header>
            <?php include('header.php'); ?>
        </header>

        <main class="main_produit">
            <section class="produit-princ">
                <?php
                        echo "<h1> ".$produit['nom_produit']." </h1>";
                ?>
                <section>
                    <div>
                        <aside>
                            <div class="photos_prod">
                                <?php
                                    $src=str_replace(' ', "_","../img/catalogue/".$produit['nom_categorie']."/".$produit['nom_souscategorie']."/".$produit['id_produit']."/");
                                    
                                    if($produit['images1']!=NULL) {
                                        echo "<div class='mySlides'>";
                                        echo "<img src='".str_replace(' ', "_",$src.$produit['images1'])."' alt='photo' class='photo-prod1'>";
                                        echo "</div>";
                                    }

                                ?>
                                <img src="../img/heart-circle-plus-solid.svg" alt="photo" class ="coeur">

                                <!-- images text -->
                                <div class="row">
                                    <?php
                                        if($produit['images1']!=NULL) {
                                            echo "<img class='demo cursor photo_min_1' src='".str_replace(' ', "_",$src.$produit['images1'])."' onclick='currentSlide(1)' alt='chat'>";


                                            if($produit['images2']!=NULL) {
                                                echo "<img class='demo cursor photo_min_2' src='".str_replace(' ', "_",$src.$produit['images2'])."' onclick='currentSlide(2)' alt='chat'>";

                                                if($produit['images3']!=NULL) {
                                                    echo "<img class='demo cursor photo_min_3' src='".str_replace(' ', "_",$src.$produit['images3'])."' onclick='currentSlide(3)' alt='chat'>";
                                                }
                                            }
                                        }
                                    ?>
                                </div>
                            </div>
                        </aside>
                    </div>
                    <article class="infos">
                        <aside>
                            <div>
                                <h4>Prix TTC</h4>
                                <?php
                                    echo "<h2> ".$produit['prix_vente_ttc']." </h2>";
                                ?>
                            </div>

                            <div class="stars-quantité">
                                <div class="stars">
                                    <?php
                                        for ($i = 0; $i < $produit['moyenne_note_produit'] ; $i++)
                                        {
                                            echo '<i class="fa fa-star gold"></i>';
                                        }
                                        for ($i = 0; $i < 5-$produit['moyenne_note_produit']; $i++){
                                            echo '<i class="fa fa-star grey"></i>';
                                        }
                                    ?>
                                </div>
                                
                                <div class="produit-quantité">
                                    <h5>Quantité</h5>
                                <form action="produit.php?idProduit=<?php echo $produit["id_produit"]; ?>" method="POST">
                                    <select type="submit" class="quantite" name="qteSelect" onchange='this.form.submit()'>
                                        <?php
                                            for ($i=1; $i <= $produit["quantite_disponnible"]; $i++) { 
                                                if($i == $qteSelect){
                                                    echo "<option value='$i' selected>$i</option>";
                                                }else{
                                                    echo "<option value='$i'>$i</option>";
                                                }
                                            }
                                        ?>
                                    </select>
                                </form>

                                </div>
                            </div>
                            <form class="ajoutPanierPageProduit" action="produit.php?id_produit=<?php echo $produit["id_produit"]; ?>&qteSelect=<?php echo $qteSelect; ?>" method="POST">
                                <button name="ajoutPanier" type="submit" class="ajout">Ajouter au panier</button>
                            </form>
                        </aside>
                        <?php
                            echo "<p> ".$produit['description_produit']." </p>";
                        ?>
                        <form action="catalogue.php?id_produit=<?php echo $produit["id_produit"]; ?>" method="POST">
                            <button name="ajoutPanier" type="submit" class="ajout-tel">Ajouter au panier</button>
                        </form>
                        <div class="logos-stock-livraison">
                            <img src="../img/stock.png" alt="logo stock" class="logo_infos_stock"> 
                            <p>Stock du produit</p>
                            <img src="../img/livraison-rapide.png" alt="logo livraison" class="logo_infos_livraison"> 
                            <p>Livraison gratuite et rapide en 5 minutes !</p>
                        </div>
                    </article>
                </section>
            </section>
            <section class="commentaires">
                <hr>
                <aside>
                    <h3>Commentaires</h3>
                    <select>
                        <option value="0">Trier par</option>
                    </select>
                </aside>
                <?php
                    // Nombre d'avis sur le produit
                    $tabComplet = avis_produit($_GET['idProduit']);
                    $nbrNotes = sizeof($tabComplet);
                    
                    
                    for ($i=0; $i < $nbrNotes; $i++):
                ?>
                <article class='carte_commentaire'>
                    <aside class='carte_commentaire--bloc'>
                        <img src='../img/user-solid-noir.svg' alt='photo de profil' class='font'>
                        <h2> <?php echo $tabComplet[$i]['nom_client']." ".$tabComplet[$i]['prenom_client']; ?> </h2>
                        <div class ='stars'>
                            <?php
                                for ($j=0; $j < $tabComplet[$i]['Note_Produit']; $j++) { 
                                    echo "<i class='fa fa-star gold'></i>";
                                }
                                for ($j=0; $j < 5-$tabComplet[$i]['Note_Produit']; $j++) { 
                                    echo "<i class='fa fa-star grey'></i>";
                                }
                            ?>
                        </div>
                    </aside>
                    <p>
                        <?php echo $tabComplet[$i]['Commentaire'] ?>
                    </p> 
                    <?php
                        $val = $tabComplet[$i]['ID_Client'].$tabComplet[$i]['ID_Produit'];
                        
                    ?>
                    <form action="produit.php?idProduit=<?php echo $produit["id_produit"]; ?>" method="post" id="signaler<?php echo $i+1; ?>" name="formSignale">
                       <input type="hidden" id="commentaireId<?php echo $i ;?>" name="CommentaireId" value="<?php echo $i+1 ;?>" /><!-- ici -->
                       
                        <button class = "signaler" type = "submit"id="signalementButton<?php echo $i; ?>" type="button" onclick="return a1_onclick('<?php echo $i+1; ?>')">Signaler</button>
                    </form>
                </article>            
                <?php endfor;?>
            </section>
        </main>
        <footer>
            <?php include('footer.php'); ?>
        </footer>


        <script>
        function Signale(){
            if (signaler){
                document.getElementById("signaler").textContent = "Commentaire signalé !";
                signaler = false;
            }
        }

        function PassageImageCentrale1(){
            image_centrale.src = image1.src;
        }

        function PassageImageCentrale2(){
            image_centrale.src = image2.src;
        }

        function PassageImageCentrale3(){
            image_centrale.src = image3.src;
        }

        var image_centrale = document.getElementsByClassName("photo-prod1")[0];

        console.log(image_centrale);

        var image1 = document.getElementsByClassName("photo_min_1")[0];
        image1.addEventListener('click', PassageImageCentrale1);

        var image2 = document.getElementsByClassName("photo_min_2")[0];
        image2.addEventListener('click', PassageImageCentrale2);

        var image3 = document.getElementsByClassName("photo_min_3")[0];
        image3.addEventListener('click', PassageImageCentrale3);

        </script>



    </body>

</html>


