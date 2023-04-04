<?php
    include("fonctions/Session.php");
    include("fonctions/fonctions.php");
    if (infos_vendeur2($_GET["id_vendeur"])!= null){
        $vendeur = infos_vendeur2($_GET["id_vendeur"])[0];

        $liste_produit_vendeur = liste_produit_vendeur($_GET["id_vendeur"]);
        $nbrProduits = sizeof($liste_produit_vendeur);
    }
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Connexion</title>
    </head>

    <body>
        <header>
            <?php include("header.php");?>
        </header>
        <?php if (infos_vendeur2($_GET["id_vendeur"])!= null): ?>
        <main class="main_fiche_vendeur">
        <main class="main_fiche_vendeur">
        <section class = "BlockInfosVendeur">
            <h2><?php echo $vendeur["Nom_vendeur"]?></h2>
            <section class = "DescriptionVendeur">
                <section class = "vendeurPartieHaut">
                    <div class = "imgVendeur">
                        
                        <?php
                            echo "<img class='lien_vendeur' src='../img/vendeur/".$vendeur['ID_Vendeur']."/img1.webp' alt='marche stp'>";
                        ?>
                    </div>
                    <div class = "infos_vendeur">
                        <div class = "informationsVenSansNote">
                            <div class = "block1infos">
                                <div class = "infosVendeur">
                                    <h4><?php echo $vendeur["Raison_sociale"]?></h4>
                                </div>
                                <div class = "infosVendeur">
                                    <h4><?php echo $vendeur["Siret"]?></h4>
                                </div>
                            </div>
                            <div class = "infosSupp">
                                <div class = "infosVendeur">
                                    <h4><?php echo $vendeur["Email"]?></h4>
                                </div>
                                <div class = "infosVendeur">
                                    <h4><?php echo $vendeur["Siret"]?></h4>
                                </div>
                            </div>   
                            <div class = "AdresseVendeur">
                                <div class ="Adresse">
                                    <h4><?php echo $vendeur["ville"]?></h4>
                                </div>
                                <div class ="Adresse">
                                    <h4><?php echo $vendeur["code_postale"]?></h4>
                                </div>
                                <div class ="Adresse">
                                    <h4>
                                <?php 
                                    if ($vendeur["complement"] != null){
                                        echo $vendeur["complement"]; 
                                    }
                                    if($vendeur["nom_de_rue"] != null){
                                        echo $vendeur["nom_de_rue"];
                                    }
                                    
                                ?>
                                    </h4>
                                </div>
                            </div>
                        </div>
                        <div class = "NoteVendeur">
                           <p><?php echo $vendeur['note']?></p>
                        </div>
                    </div>
            </section>
                <hr class = "barre_jaune">
                <section class = "vendeurDescription">
                    <p><?php echo $vendeur['texte_Presentation']?></p>
                </section>
            </section>
        </section>
       
            <section class="column_vendeur_produits">
                <?php if ($nbrProduits > 0 ): ?>
                    <article>
                        <h3 class = "titre_produit">Liste des produits</h3>   
                    </article>
                    <?php for ($i=0; $i < $nbrProduits; $i++): ?>
                    <section class = "produit_vendeur cursor">
                        <div class = "infos_produit_vendeur">
                            <?php $src = str_replace(' ', "_","../img/catalogue/Produits/".$liste_produit_vendeur[$i]['ID_Produit']."/"); ?>
                            <img class='photo_min_2' src='<?php echo(str_replace(' ', "_",$src.$liste_produit_vendeur[$i]['images1']));?>' alt='image_produit'>
                            <section>
                                <div class = "infos_produit1">
                                    <article>
                                        <h4 class= "info_produit_bleu_vendeur"><?php echo $liste_produit_vendeur[$i]["Nom_produit"] ?></h4>
                                    </article>
                                    <article class = "vendeur_HT">
                                        <h4 class= "info_produit_jaune_vendeur">prix HT : &nbsp </h4></br>
                                        <h4 class= "info_produit_bleu_vendeur"><?php echo $liste_produit_vendeur[$i]["Prix_vente_HT"] ?>€</h4>
                                        
                                    </article>
                                    <article class = "vendeur_TTC">
                                    <h4 class= "info_produit_jaune_vendeur">prix TTC : &nbsp</h4>
                                    <h4 class= "info_produit_bleu_vendeur"><?php echo $liste_produit_vendeur[$i]["Prix_vente_TTC"] ?>€</h4>
                                        
                                    </article>
                                </div>
                                <div class = "infos_produit2">
                                    <article>
                                        <h5 class= "info_produit_gris_vendeur"><?php echo $liste_produit_vendeur[$i]["Description_produit"] ?></h5>
                                    </article>
                                </div>
                            </section>
                        </div>
                        
                    </section>
                    <?php endfor; ?>
                </section> 
                <?php else: ?>
                        <h3 class="entreprise_sans_produit">Cette entreprise ne vend pas de produits.</h3>
                    <?php endif; ?>
            </section>
        </main>
        <?php endif; ?>
        <footer>
            <?php include("footer.php");?>
        </footer>
    </body>
    <script>
        var liste_produit = document.getElementsByClassName("produit_vendeur");
        <?php for($i = 0; $i < $nbrProduits; $i++):?>
            function redirectionPageProduit<?php echo $i;?>(){
                <?php $string = "'produit.php?idProduit=".$liste_produit_vendeur[$i]['ID_Produit']."';" ?>
                <?php echo "document.location.href = $string";?>
                console.log("aaaaa");
            }
        <?php endfor;?>

        <?php for($i = 0; $i < $nbrProduits; $i++):?>
            console.log(liste_produit);
            liste_produit[<?php echo $i;?>].addEventListener('click',redirectionPageProduit<?php echo $i;?>);
        <?php endfor;?>
    </script>
</html>
