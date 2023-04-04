<?php
    include('fonctions/Session.php');
    include('fonctions/fonctions.php');

    $produit = infos_produit($_GET['idProduit'])[0];
    /** Tableau $produit
     * ['nom_produit']
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

    /* changement de quantité d'un produit */
    if(!isset($_POST['qteSelect'])){
        $qteSelect = 1;
    }else{
        $qteSelect = $_POST['qteSelect'];
    }

    /* ajout d'un produit au panier via un form */
    if(isset($_POST['ajoutPanier'])) {
            ajoutPanier($_GET['idProduit'], $_GET['qteSelect']);
    }
    
    /* vérification et ajout d'une réponse à un commentaire */
    if(isset($_COOKIE['id_commentaire'] )){
    $tab_reponses_commentaire = verif_reponses_commentaires($_COOKIE['id_commentaire']);
        if(isset($_COOKIE['texte_reponse'])){
            if (sizeof($tab_reponses_commentaire) == 0){
                $id_comm = $_COOKIE['id_commentaire']; 
                $rep = $_COOKIE['texte_reponse'];
                ajoute_reponse($id_comm, $rep);
            }
        } 
    }
    
    /* vérification et ajout d'un commentaire */
    if(isset($_COOKIE['texte_commentaire'])){
        if (isset($_SESSION['id_client'])){
            if (sizeof(verif_commentaires($_SESSION['id_client'], $_GET['idProduit'])) == 0){
                ajout_comm($_SESSION['id_client'], $_GET['idProduit'], $_COOKIE['note'], $_COOKIE['texte_commentaire']);
            }
        }       
    }


    echo '<script> document.cookie = "id_commentaire=; expires=Thu, 01 Jan 1970 00:00:00 UTC" </script>';
    echo '<script> document.cookie = "texte_reponse=; expires=Thu, 01 Jan 1970 00:00:00 UTC" </script>';

    echo '<script> document.cookie = "note=; expires=Thu, 01 Jan 1970 00:00:00 UTC" </script>';
    echo '<script> document.cookie = "texte_commentaire=; expires=Thu, 01 Jan 1970 00:00:00 UTC" </script>';
    
    /* vérification et signalement d'un commentaire */
    if (isset($_SESSION['id_client'])){
        if(isset($_POST['CommentaireId'])) {
            $signaleur = SignaleurComment($_SESSION['id_client'],getID_Commentaire($_GET['idProduit'],$_POST["CommentaireId"])[0]["ID_Commentaire"]);
            if ($signaleur == null){
                $id_comm = getID_Commentaire($_GET['idProduit'],$_POST["CommentaireId"]);
                ajouteSignalement($_SESSION['id_client'],$id_comm[0]['ID_Commentaire']);
            }
            unset($_POST['CommentaireId']);
        }
    }

    /* vérification et suppression d'un commentaire */
    if (isset($_POST['SupprCommentaireId'])) {
        $sth = $dbh -> prepare("DELETE FROM Alizon._Reponse WHERE ID_Commentaire = ?");
        $sth->execute(array($_POST['SupprCommentaireId']));
        $sth = $dbh -> prepare("DELETE FROM Alizon._Avis WHERE ID_Commentaire = ?");
        $sth->execute(array($_POST['SupprCommentaireId']));

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
            <?php
                if (isset($_SESSION["id_admin"])){
                    echo "<header>";
                    include("header_admin.php");
                    echo "</header>";
                }
                else{
                    if (isset($_SESSION["vendeur"]))
                    {
                        include("header_vendeur.php");
                    }
                    else {
                        echo "<header>";
                        include('header.php');
                        echo "</header>";
                    }
                }
            ?>

        <?php if(isset($_GET["produitajouté"]))
        {
            echo "<div class='alert ajouterpanier'>";
            echo "<i class='fa-regular fa-circle-check fa-2x'></i>";
            echo "<p>Produit Ajouté</p>";
            echo "</div>";
        }
        ?>
        <main class="main_produit">
            <section class="produit-princ">
                <?php
                    echo "<h1> ".$produit['nom_produit']." </h1>";
                ?>
                <div class="stars-quantité">
                    <div class="stars">
                        <!--note du produit -->
                        <?php
                            for ($i = 0; $i < $produit['moyenne_note_produit'] ; $i++)
                            {
                                echo '<i class="fa fa-star gold"></i>';
                            }
                            for ($i = 0; $i < 5-$produit['moyenne_note_produit']; $i++){
                                echo '<i class="fa fa-star grey"></i>';
                            }
                        ?>
                        <p>(<?php echo sizeof(avis_produit($_GET['idProduit'])) ; ?>)</p>
                    </div>
                </div>
                <section class="infos_produit">
                    <div class="photos_prod">
                            <!-- images text -->
                        <div class="row">
                            <?php
                                $src=str_replace(' ', "_","../img/catalogue/Produits/".$produit['id_produit']."/");

                                if($produit['images1']!=NULL) {
                                    echo "<img class='demo photo_min_1' src='".str_replace(' ', "_",$src.$produit['images1'])."' alt='Image 1 Produit' title='Image 1'>";


                                    if($produit['images2']!=NULL) {
                                        echo "<img class='demo photo_min_2' src='".str_replace(' ', "_",$src.$produit['images2'])."' alt='Image 2 Produit' title='Image 2'>";

                                        if($produit['images3']!=NULL) {
                                            echo "<img class='demo photo_min_3' src='".str_replace(' ', "_",$src.$produit['images3'])."' alt='Image 3 Produit' title='Image 3'>";
                                        }
                                    }
                                }
                            ?>
                        </div>
                            <?php
                                
                                if($produit['images1']!=NULL) {
                                    echo "<div class='mySlides'>";
                                    echo "<img src='".str_replace(' ', "_",$src.$produit['images1'])."' alt='photo' class='photo-prod1'>";
                                    echo "</div>";
                                }

                            ?>
                        <!--<img src="../img/heart-circle-plus-solid.svg" alt="photo" class ="coeur">!-->
                    </div>
                    <article class="infos">

                        <aside>
                            
                            <div class="prix_ht_ttc">
                                <div class="TTC">
                                    <h3>Prix TTC</h3>
                                    <?php
                                        echo "<h2> ".$produit['prix_vente_ttc']. " €"." </h2>";
                                    ?>
                                </div>

                                <div class="HT">
                                    <h3>Prix HT</h3>
                                    <?php
                                        echo "<h2> ".$produit['prix_vente_ht']. " €"." </h2>"; 
                                    ?>
                                </div>
                            </div>

                                <!-- Bouton ajout au panier -->
                                <?php if (!isset($_SESSION["vendeur"])==true): ?>
                                <form class="ajoutPanierPageProduit" action="produit.php?idProduit=<?php echo $produit["id_produit"]; ?>&qteSelect=<?php echo $qteSelect; ?>&produitajouté" method="POST">
                                    <?php if($produit["quantite_disponnible"] >=1):?>
                                        <button name="ajoutPanier" type="submit" class="ajout">Ajouter au panier</button>
                                    <?php else:?>
                                        <button name="ajoutPanier" type="submit" class="ajout" style="background-color: #CB333B" disabled>Indisponible</button>
                                    <?php endif; ?>
                                </form>
                            <?php endif; ?>
                        </aside>

                        <?php if (!isset($_SESSION["vendeur"])==true): ?>
                            <div class="produit-quantité">
                                <?php if($produit["quantite_disponnible"] >=1):?>
                                <h5>Quantité</h5>
                                <?php endif;?>
                                <form action="produit.php?idProduit=<?php echo $produit["id_produit"]; ?>" method="POST" class="quantite_prod">
                                    <?php if($produit["quantite_disponnible"] >=1):?>
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
                                    <?php endif;?>
                                </form>

                            </div>
                        <?php endif; ?>
                                <p><?php echo $produit['description_produit']?> </p>
                                <?php if (isset($_SESSION["vendeur"])==true): ?>
                                    <p><?php echo "Quantité disponible :".$produit['quantite_disponnible']?> </p>
                                    
                                <?php endif; ?>
                                <?php if (!isset($_SESSION["vendeur"])==true): ?>
                                <?php $vendeur = infos_vendeur($produit["id_produit"])[0]; ?>
                                <a href="fiche_vendeur.php?id_vendeur=<?php echo $vendeur['ID_Vendeur']?>" class='expediteur produit'> Vendu et Expédié par <?php echo $vendeur['Nom_vendeur'] ?></a>
                                
                        <?php endif; ?>
                        <hr>
                        <?php if (!(isset($_SESSION["id_admin"])) && !(isset($_SESSION["vendeur"]))): ?>
                        <form action="produit.php?idProduit=<?php echo $produit["id_produit"]; ?>&qteSelect=<?php echo $qteSelect; ?>&produitajouté" method="POST" class="form_ajout_tel">
                            <?php if($produit["quantite_disponnible"] >=1):?>
                                <button name="ajoutPanier" type="submit" class="ajout-tel">Ajouter au panier</button>
                            <?php else:?>
                                <button name="ajoutPanier" type="submit" class="ajout-tel" style="background-color: #CB333B" disabled>Indisponible</button>
                                
                                    
                            <?php endif; ?>
                           <!-- changer le lien de quand la page info vendeur sera créer-->
                        </form>
                        <?php endif; ?>
                        <div class="logos-stock-livraison">
                        <?php if($produit["quantite_disponnible"] >=1):?>
                                <img src="../img/stock.png" alt="logo stock" class="logo_infos_stock"> 
                                <p style="color : rgb(0, 150, 0); font-weight : bold">Produit en stock</p>

                            <?php else:?>
                                <img src="../img/rupture-de-stock.png" alt="logo hors stock" class="logo_infos_stock"> 
                                <p style="color: #CB333B; font-weight : bold">Produit hors stock</p>

                            <?php endif; ?>

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
                </aside>
                    <?php // Nombre d'avis sur le produit
                        $tabComplet = avis_produit($_GET['idProduit']);
                        $nbrNotes = sizeof($tabComplet); 
                        if ($nbrNotes == 0) : 
                    ?>
                        <h2 class="premier_commentaire"> Soyez le premier à poster un commentaire</h2>
                    <?php endif;?>
                <?php
                    
                    
                    for ($i=0; $i < $nbrNotes; $i++):
                ?>
                <article class='carte_commentaire'>
                    <a id=Ancre_ID_Commentaire<?php echo $i;?> class="anchor-top"></a>
                    <aside class='carte_commentaire--bloc'>
                        <img src='../img/user-solid-noir.svg' alt='photo de profil' class='font'>
                        <h2> <?php echo $tabComplet[$i]['prenom_client']." ".$tabComplet[$i]['nom_client']; ?> </h2>
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
                        <?php echo $tabComplet[$i]['Commentaire'];?>
                    </p> 
                    <?php
                        $val = $tabComplet[$i]['ID_Client'].$tabComplet[$i]['ID_Produit'];
                    ?>
                    <?php if (isset($_SESSION['id_admin'])) : ?>
                        <!-- Bouton suppression d'un commentaire -->
                        <form action="produit.php?idProduit=<?php echo $produit["id_produit"]; ?>" method="POST" id="supprimer<?php echo $i+1; ?>" name="formSupprime">
                            <input type="hidden" id="commentaireId<?php echo $i ;?>" name="SupprCommentaireId" value=<?php echo $tabComplet[$i]['ID_Commentaire'];?>/>
                            <button class = "signaler" type = "submit" value=<?php echo $i; ?> name="Supprimer<?php echo $i; ?>">Supprimer</button> 
                        </form>
                    <?php else : ?>
                        <!-- Bouton signalement d'un commentaire -->
                        <?php if(!(isset($_SESSION["vendeur"]))):?>
                            <?php if (isset($_SESSION["id_client"])): ?>
                                <form action="produit.php?idProduit=<?php echo $produit["id_produit"]; ?>#Ancre_ID_Commentaire<?php echo $i;?>" method="post" id="signaler<?php echo $i+1; ?>" name="formSignale">
                                    <input type="hidden" id="commentaireId<?php echo $i ;?>"  name="CommentaireId" value=<?php echo $tabComplet[$i]['ID_Client'];?> /><!-- ici -->
                                    <button class = "signaler" type = "submit" id="signalementButton<?php echo $i; ?>" <?php if (SignaleurComment($_SESSION['id_client'], $tabComplet[$i]['ID_Commentaire']) != null){ echo "disabled style='background-color : #eee; color : #CB333B'"; }?>> 
                                        <?php if (SignaleurComment($_SESSION['id_client'], $tabComplet[$i]['ID_Commentaire']) == null){ echo "Signaler"; } else { echo "Déja signalé !";}  ?>
                                    </button>
                            </form>
                            <?php else:?>
                                <form action="connexion.php" method="post" id="signaler<?php echo $i+1; ?>" name="formSignale">
                                    <input type="hidden" id="commentaireId<?php echo $i ;?>"  name="CommentaireId" value=<?php echo $tabComplet[$i]['ID_Client'];?> /><!-- ici -->
                                    <button class = "signaler" type = "submit" id="signalementButton<?php echo $i; ?>" <?php if (SignaleurComment($_SESSION['id_client'], $tabComplet[$i]['ID_Commentaire']) != null){ echo "disabled style='background-color : #eee; color : #CB333B'"; }?>> 
                                        <?php if (SignaleurComment($_SESSION['id_client'], $tabComplet[$i]['ID_Commentaire']) == null){ echo "Signaler"; } else { echo "Déja signalé !";}  ?>
                                    </button>
                                </form>
                            <?php endif;?>
                        <?php endif;?>
                    <?php endif; ?>
                    <?php if ((isset($_SESSION["vendeur"]))&&(sizeof(verif_reponses_commentaires($tabComplet[$i]['ID_Commentaire'])) == 0)): ?>
                                    <!-- Bouton ajout d'une réponse à un commentaire -->                                <!-- id_commentaire -->
                        <button id = "réponse<?php echo $i; ?>" class = "réponse" type = "submit" type= "button" value=<?php echo $tabComplet[$i]['ID_Commentaire'];?> name="Répondre<?php echo $i; ?>">Répondre</button> 
                    <?php endif; ?>
                    <article class = "bloc-reponse">
                        <label id = "label-reponse<?php echo $i; ?>" class = "reponse-comm"> Réponse </label>
                        <input id = "texte-reponse<?php echo $i; ?>" class="reponse-comm-texte" type="text" name="reponse" required>
                        <button id = "btn-valider-reponse<?php echo $i; ?>" class= "reponse-comm-btn-valider" type="submit"> Envoyer </button>
                        <button id = "btn-annuler-reponse<?php echo $i; ?>" class= "reponse-comm-btn-annuler"> Annuler </button>
                    </article>
                    <?php   
                        $listeReponses = reponses_avis($tabComplet[$i]['ID_Commentaire']); 
                        $nbrReponses = sizeof($listeReponses); 
                    ?>
                    <?php for ($j=0; $j < $nbrReponses; $j++): ?>
                        <hr>
                        <h3> <?php echo "Réponse du Vendeur"; ?> </h3>
                        <h4> <?php echo $listeReponses[0]['Commentaire']; ?></h4>
                    <?php endfor; ?>
                </article>            
                <?php endfor;?>
                <?php if ((sizeof(verif_commentaires($_SESSION['id_client'],$_GET['idProduit'])) == 0) && (sizeof(verif_produit_commandé($_SESSION["id_client"],$_GET["idProduit"])) > 0)): ?>
                    <article id = "bloc-btn-ajouter-avis" class = "bloc-btn-ajouter-avis">
                        <!-- Bouton ajout d'un commentaire -->
                        <button class = "ajouter-avis"> + Ajouter un avis </button>
                    </article>
                <?php endif; ?>
                <article class = "bloc-ajout-commentaire">
                    <div class = "bloc-étoile">
                        <?php for ($cpt = 0; $cpt <5; $cpt++) : ?>
                            <i id = "etoile<?php echo $cpt; ?>" class='fa fa-star fa-star-ajout-commentaire gold'></i>
                        <?php endfor; ?>
                    </div>
                    <input id = "texte-commentaire" class="comm" type="text" name="commentaire" required>
                    <button id = "btn-valider-commentaire" class= "comm-btn" type="submit"> Envoyer </button>
                    <button id = "btn-annuler-commentaire" class= "comm-btn"> Annuler </button>
                </article>
            </section>
        </main>
        <footer>
            <?php
                if(!isset($_SESSION["vendeur"]))
                {
                    include('footer.php'); 
                }
            ?>
        </footer>

        <!-- permet de boucler sur toutes les notes -->
        <?php for ($i=0; $i < $nbrNotes; $i++): ?>
        <script>

        /** Passe le bloc de réponse en (display : flex) lors d'un clic sur le bouton Répondre */
        function Repondre(){ 
            var label_reponse = document.getElementById("label-reponse<?php echo $i; ?>"); /** recherche de chaque label via leur id php */
            var texte_reponse = document.getElementById("texte-reponse<?php echo $i; ?>"); /** recherche de chaque texte via leur id php */
            texte_reponse.style.display = "flex" ;
            btn_valider_reponse<?php echo $i; ?>.style.display = "flex" ;
            btn_annuler_reponse<?php echo $i; ?>.style.display = "flex" ;
            label_reponse.style.display = "flex";
            btn_valider_reponse<?php echo $i; ?>.style.fontFamily = 'montserrat' ;
            btn_annuler_reponse<?php echo $i; ?>.style.fontFamily = 'montserrat' ;
            texte_reponse.style.width = "60%";
        }

        /** Passe le bloc de réponse en (display : none) lors d'un clic sur le bouton Annuler */
        function AnnulerRepondre(){
            var label_reponse = document.getElementById("label-reponse<?php echo $i; ?>"); /** recherche de chaque label via leur id php */
            var texte_reponse = document.getElementById("texte-reponse<?php echo $i; ?>"); /** recherche de chaque texte via leur id php */
            texte_reponse.style.display = "none" ;
            btn_valider_reponse<?php echo $i; ?>.style.display = "none" ;
            btn_annuler_reponse<?php echo $i; ?>.style.display = "none" ;
            label_reponse.style.display = "none" ;
        }

        /** Stocke la valeur du champ réponse et de l'id_commentaire dans les cookies pour la réutiliser dans l'insertion dans la bdd  (cf haut de la page) et recharge la page pour relancer les fonctions du haut de la page*/
        function ValiderReponse(){

            var texte_reponse = document.getElementById("texte-reponse<?php echo $i; ?>"); /** recherche de chaque texte via leur id php */
            document.cookie = "texte_reponse = " + texte_reponse.value;
            document.cookie = "id_commentaire = " + btn_repondre<?php echo $i; ?>.value; /** conservation de la valeur de texte rentrée et de l'id_commentaire via un cookie pour la réutiliser en php (cf haut de la page)*/

            window.location.reload();
        }
        var btn_repondre<?php echo $i; ?> = document.getElementById("réponse<?php echo $i; ?>"); /** recherche de chaque bouton "Répondre" via leur id php */
        if (btn_repondre<?php echo $i?> != null){
            btn_repondre<?php echo $i; ?>.addEventListener('click', Repondre);

            var btn_annuler_reponse<?php echo $i; ?> = document.getElementById("btn-annuler-reponse<?php echo $i; ?>"); /** recherche de chaque bouton "Annuler" via leur id php */
            btn_annuler_reponse<?php echo $i; ?>.addEventListener('click', AnnulerRepondre);

            var btn_valider_reponse<?php echo $i; ?> = document.getElementById("btn-valider-reponse<?php echo $i; ?>"); /** recherche de chaque bouton "Envoyer" via leur id php */
            btn_valider_reponse<?php echo $i; ?>.addEventListener('click', ValiderReponse);
        }
        </script>
        <?php endfor; ?>

        <!-- compteur calculant la note donnée -->
        <?php
            for ($cpt2 = 0; $cpt2 <5; $cpt2++) : 
        ?>
        
        <script>

            /** conserve le numéro de l'étoile pour la passer en doré et passer toutes les étoiles ayant un numéro inférieur en doré aussi lors d'un hover*/
            function PassageEtoilesDorés<?php echo $cpt2; ?>(){
                <?php for ($a = $cpt2; $a >= 0; $a--) :?> 
                    etoile<?php echo $a; ?>.classList.add("gold", "nb");
                    etoile<?php echo $a; ?>.classList.remove("grey");
                <?php endfor; ?>
            }

             /** conserve le numéro de l'étoile pour la passer en doré et passer toutes les étoiles ayant un numéro inférieur en doré aussi de façon permanente après un click*/
            function PassageEtoilesDorésPermanent<?php echo $cpt2; ?>(){
                <?php for ($x = 4; $x > $cpt2; $x--) :?>
                    etoile<?php echo $x; ?>.classList.add("grey");
                    etoile<?php echo $x; ?>.classList.remove("gold", "nb");
                <?php endfor; ?>
                <?php for ($a = $cpt2; $a >= 0; $a--) :?> 
                    etoile<?php echo $a; ?>.classList.add("gold", "nb");
                    etoile<?php echo $a; ?>.classList.remove("grey");
                <?php endfor; ?>
                perm = <?php echo $cpt2; ?>;
            }

            var etoile<?php echo $cpt2; ?> = document.getElementById("etoile<?php echo $cpt2; ?>"); /** créer une variable pour chaque valeur d'étoile */
            etoile<?php echo $cpt2; ?>.addEventListener("mouseover",PassageEtoilesDorés<?php echo $cpt2; ?>); /** Event lors du passage de la souris sur une étoile */
            etoile<?php echo $cpt2; ?>.addEventListener("click",PassageEtoilesDorésPermanent<?php echo $cpt2; ?>); /** Event lors d'un click de la souris sur une étoile */
            var perm = 0;
            
        </script>

        <?php endfor; ?>

        <script>

        /** Passe les étoiles en grises et les repassent en dorées si elles ont été cliquées (avec le nombre d'étoiles passant en doré dépendant de l'étoile cliquée)*/
        function PassageEtoilesGrises(){
            <?php for ($b = 0; $b < 5; $b++) :?>
                etoile<?php echo $b; ?>.classList.add("grey");
                etoile<?php echo $b; ?>.classList.remove("gold", "nb");
            <?php endfor; ?>
            var variable = "etoile";
            var i = 0;
            var et = "";
            for (i = perm; i>=0; i--){
                et = variable + i;
                window[et].classList.add("gold", "nb");
                window[et].classList.remove("grey");
            }
        }

        /** Affiche le bloc contenant la zone de texte pour ajouter un commentaire, les étoiles et les boutons Envoyer et Annuler */
        function AfficheComm(){
            <?php if (isset($_SESSION['id_client'])){
                    echo 'bloc_comm.style.display = "flex";';
                }
                else {
                    echo 'document.location.href = "connexion.php";';
                }
            ?>
        }
        
        function ValiderCommentaire(){
            var nbetoiles = document.getElementsByClassName("nb").length;
            if (nbetoiles == 0){
                nbetoiles = 1;
            }
            document.cookie = "texte_commentaire= " + texte_commentaire.value;
            document.cookie = "note= " + nbetoiles;
            window.location.reload();
        }

        /** Fait disparaître le bloc contenant la zone de texte pour ajouter un commentaire, les étoiles et les boutons Envoyer et Annuler */
        function AnnulerCommentaire(){
            bloc_comm.style.display = "none";
        }

        /** Ajoute un signalement à un commentaire (cf haut de la page pour détection de commentaire déjà signalé) */
        function Signale(){
            if (signaler){
                document.getElementById("signaler").textContent = "Commentaire signalé !";
                signaler = false;
            }
        }

        /** Passe l'image cliquée en image centrale */
        function PassageImageCentrale1(){
            image_centrale.src = image1.src;
        }

        function PassageImageCentrale2(){
            image_centrale.src = image2.src;
        }

        function PassageImageCentrale3(){
            image_centrale.src = image3.src;
        }

        var bloc_comm = document.getElementsByClassName("bloc-ajout-commentaire")[0];

        var image_centrale = document.getElementsByClassName("photo-prod1")[0];

        if (document.getElementsByClassName("photo_min_1")[0] != null){
            var image1 = document.getElementsByClassName("photo_min_1")[0];
            image1.addEventListener("click", PassageImageCentrale1);
        }

        if (document.getElementsByClassName("photo_min_2")[0] != null){
            var image2 = document.getElementsByClassName("photo_min_2")[0];
            image2.addEventListener("click", PassageImageCentrale2);
        }

        if (document.getElementsByClassName("photo_min_3")[0] != null){
            var image3 = document.getElementsByClassName("photo_min_3")[0];
            image3.addEventListener("click", PassageImageCentrale3);
        }


        var btn_ajout_avis = document.getElementsByClassName("ajouter-avis")[0];
        if (btn_ajout_avis != null){
            btn_ajout_avis.addEventListener('click', AfficheComm);

            var bloc_etoile = document.getElementsByClassName("bloc-étoile")[0];
            bloc_etoile.addEventListener("mouseout", PassageEtoilesGrises);


            var texte_commentaire = document.getElementById("texte-commentaire");

            var btn_ajouter_commentaire = document.getElementById("btn-valider-commentaire");
            btn_ajouter_commentaire.addEventListener("click", ValiderCommentaire);

            var btn_annuler_commentaire = document.getElementById("btn-annuler-commentaire");
            btn_annuler_commentaire.addEventListener("click", AnnulerCommentaire);
        }
        </script>
    </body>
</html>


