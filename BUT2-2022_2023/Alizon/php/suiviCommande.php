<?php
    include("fonctions/Session.php");
    include("fonctions/fonctions.php");
    
    // Déconnexion
    if(isset($_POST['deco'])) {
        $_SESSION['id_client'] = null;
        setcookie("id_panier","");
        header("Location: connexion.php");
        exit();  
    }

    // Désactiver le compte
    if(isset($_GET['supp'])) {
        desactiver_compte($_SESSION['id_client'], $_COOKIE['id_panier']);
        $_SESSION['id_client'] = null;
        setcookie("id_panier","");
        header("Location: ./inscription.php");
        exit();
    }
    
    $client = infos_Client($_SESSION['id_client']);
    /**Tableau $client
     * [ID_Client]
     * [nom_client]
     * [prenom_client]
     * [adresse_livraison]
     * [adresse_facturation]
     * [date_de_naissance]
     * [email]
     * [mdp] 
     * [QuestionReponse]
     * [active]
     * [ID_Panier]
     * [Prix_total_HT]
     * [Prix_total_TTC]
     * [derniere_modif] 
     */
    
    if(isset($_POST['verif_infos'])) {
        verif_infos($_POST['email'], $_POST['pwd']);
    }

    if(isset($_POST['ModificationMdp'])) {
        if($_POST['mdp'] == $_POST['ConfirmationMdp']){
            if(!update_mdp($_SESSION['id_client'],$_POST['mdp'], $_POST['AncienMdp'])){
                // False si l'ancien mot de passe est incorrect
                $_POST["VerifAncienMdp"] = False;
            }else{
                header("Location: monCompte.php"); 
            }
        }else{
            // Si le mot de passe est différent de la confirmation du mot de passe
            $_POST["VerifConfirmationMdp"] = False; 
        }
    }

    if(isset($_POST['coordonnees'])) {
        // Envoie des nouvelles information en cas de modification des informations du client
            update_information($_SESSION['id_client'],$_POST['prenom'],$_POST['nom'],$_POST['DateDeNaissance'], $_POST['adresse_facturation'],$_POST['adresse_facturation']);
            header("Location: monCompte.php");  
    }

    if(isset($_POST['suiviCommande'])){
        $commande = recupCommande($_POST['IdCommande']);
    }
    //Avoir l'état le plus petit des produits de la commande en cours
    $tabEtatCommande = [];
    $etatCommande = 0;
    foreach($commande as $key => $value){
        $etatCommande = 0;
        if($value["etat_produit_c"] == "en-charge"){
            $etatCommande = 1;
        }
        if($value["etat_produit_c"] == "en-cours"){
            $etatCommande = 2;
        }
        if($value["etat_produit_c"] == "finie"){
            $etatCommande = 3;
        }
        if($value["etat_produit_c"] == "annulee"){
            $etatCommande = -1;
        }
        array_push($tabEtatCommande,$etatCommande);
    }
    $etatCommande = min($tabEtatCommande);
    $tabEtatCommande[-1]="Commande Annulée";
    $tabEtatCommande[0]="Commande Acceptée";
    $tabEtatCommande[1]="Commande prise en charge";
    $tabEtatCommande[2]="Commande en cours de livraison";
    $tabEtatCommande[3]="Commande livré";
    $etatCommande = $tabEtatCommande[$etatCommande];

    
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Mes Informations</title>
        <link rel="stylesheet" href="../css/style.css">
    </head>
    <body>
        <header>
            <?php include('header.php'); ?>
        </header>

        <main class="main-Compte">

            <aside class="mesInfos">

                <div class="bloc_nom_compte">

                    <div class="nom_compte">
                        <i class="fa-solid fa-user"></i><?php echo strtoupper($client['nom_client'])." ".$client['prenom_client'];?>
                    </div>

                    <div class="bloc_deconnexion">
                        <form action="monCompte.php" method="post" id="Deconnexion">
                            <input class="deconnexion" name="deco" id="dec" type="submit" value="Se Déconnecter">
                        </form>
                    </div>

                </div>

                <div class="liens_compte">
                    <a class="infosClient" onClick="menu()" id="infos_personnelles_btn" href="monCompte.php#infos_personnelles"><i class="fa-solid fa-user"></i>Mes infos persos</a>
                    <a class="btn-commande" onClick="menu()" id="infos_commandes_btn" href="monCompte.php#infos_commandes"><i class="fa-sharp fa-solid fa-cart-shopping"></i>Mes commandes</a>
                </div>

                <div class="bloc_suppression">
                    <div id="Supprimer">
                        <button class="supprimer" name="supp" id="supp" onClick="verifDeco()">Désactiver le compte</button>
                    </div>
                    
                    <script>
                        function verifDeco() {
                            var mdp = "<?php echo $client['mdp'] ?>";
                            var verif = prompt("Entrer votre mot de passe pour désactiver votre compte ");
                            
                            if(verif==null) {
                                alert("Désactivation Annulée");
                            } else {
                                <?php include("./fonctions/chiffrement.php"); ?>
                                if (verif == "<?php echo dechiffrementMDP($client['mdp']) ; ?>") {
                                    alert("Votre compte a bien été désactivé !");
                                    // formSupp.submit();
                                    location.href="monCompte.php?supp";
                                } else {
                                    alert("Mot de passe incorrecte");
                                }
                            }
                        }
                    </script>
                </div>

            </aside>
            <section class = "block_commande">
                <section class = "block_suiviCommande">
                    <div class = "numCommande">
                        <h2>n°commande : <?php echo $_POST['IdCommande']; ?></h1>
                        <h1><?php echo $etatCommande; ?><h1>
                            
                    </div>
                    <div id="progression">
                        <span class="etape fait">1
                            <div class="desc">Commande accepté</div>
                        </span>
                        
                        <span class="ligne"></span>

                        <span class="etape ">2
                            <div class="desc">Commande prise en charge</div>
                        </span>

                        <span class="ligne "></span>

                        <span class="etape" >3

                            <div class="desc">Commande en cours de livraison</div>
                        </span>

                        <span class="ligne"></span>

                        <span class="etape ">4
                            <div class="desc">Commande livré</div>
                        </span>
                      
                        
                    </div>

            
                </section>
                <div class='commandes_cours affiche'> 
                    <div class="commande">
                        <aside>
                            <div class="date_commande">
                                <h3>Commande passée le </h3>
                                <h3 class="bleu"><?php echo dateFr($commande[0]['date_commande']);?></h3>
                            </div>
                            <div class="prix_commande">
                                <h3 class="nb_articles_commande"><?php 
                                    $s = "";
                                    if($commande['nb']>1){
                                        $s ="s";
                                    }
                                    echo $commande['nb']." article".$s; 
                                ?></h3>
                                <h3><?php echo $commande[0]['prix_total'];?> €</h3>
                            </div>
                            <div class="adresse_livraison">
                                <h3>Livraison à </h3>
                                
                                <h3 class="bleu"><?php echo $commande[0]["nom_de_rue_livraison"].", ".$commande[0]["ville_livraison"].", ".$commande[0]["code_postale_livraison"];?></h3>
                                
                                
                                
                            </div>
                            <div>
                                <h3>Livraison prévue le <?php echo dateFr($commande[0]['date_livraison']); ?></h3>
                                <h3><a href="#" class="no_deco facture">Facture</a></h3>
                            </div>
                                    <!-- <form class="form-recommander" action="panier.php" method="POST">
                                        <input type="hidden" id="commandeID<?php echo $commande[0]['ID_Commande'];?>" name="commandeID" value="<?php echo $commande['ID_Commande'];?>" />

                                        <button type="submit" id="button-recommander<?php echo $commande[0]['ID_Commande'];?>" name="button-recommander">Recommander</button>
                                        </form>-->
                        </aside>
                            <div class="liste_produits_commande">
                                <div class="produit_commande">
                                    <?php for ($i=0; $i < count($commande); $i++):
                                        $src=str_replace(' ', "_","../img/catalogue/Produits/".$commande["$i"]['ID_Produit']."/");
                                    ?>
                                    <div class="infos_produit_commande">
                                        
                                        <img src='<?php echo str_replace(' ', "_",$src.$commande["$i"]['images1']); ?>' alt="Image Produit" title="Image Produit"class="img_commande">
                                        <div class="produit_commande-description">
                                            <h4><?php echo $commande["$i"]['Nom_produit']; ?></h4>
                                            <div class="rachat_compl"><a href="produit.php?idProduit=<?php echo $commande[$i]['ID_Produit'];?>">Acheter à nouveau</a></div>
                                            <div class="rachat_plus"><a href="produit.php?idProduit=<?php echo $commande[$i]['ID_Produit'];?>">+</a></div>
                                        </div>
                                    
                                        <div>
                                            <h3><?php echo $commande["$i"]['Prix_vente_TTC']; ?> €</h3>
                                                <h4>Quantité : <?php echo $commande[$i]['Quantite']; ?></h4>
                                        </div>
                                    </div>
                                    </br>
                                    <?php endfor;?>
                                </div>
                            </div>  
                        </div>   
                        <a href="monCompte.php#infos_commandes"><- Revenir en arrière</a>
                </div>
            </section>
        </main>

        <footer>
            <?php include('footer.php'); ?>
        </footer>
            
    </body>
</html>                    

<script>

    // Je stock l'étape de la commande dans une variable
    const etape = "<?php echo $etatCommande; ?>"; 
    console.log(etape);

    //Je prend les étapes du html
    const etapes = document.querySelectorAll('.etape');
    console.log(etapes);
    const etape1 = etapes[0];
    const etapes2 = etapes[1];
    const etapes3 = etapes[2];
    const etapes4 = etapes[3];
    
    if (etape == "Commande prise en charge"){
        etapes2.classList.add('fait');
        
    }else if( etape == "Commande en cours de livraison"){
        etapes2.classList.add('fait');
        etapes3.classList.add('fait');
    }else if(etape == "Commande livré"){
        etapes2.classList.add('fait');
        etapes3.classList.add('fait');
        etapes4.classList.add('fait');
    }else if (etape == "Commande Annulée"){
        etape1.classList.remove("fait");
        etape1.classList.add("EtatCommandeAnnulee");
    }


    var elem2_produit = document.getElementsByClassName("infos_produit_commande");
          
    let x = 0;

    while (x < elem2_produit.length){
        if (x % 2 == 0){
            elem2_produit[x].style.backgroundColor = "#f2f2f2";
        }
        console.log(elem2_produit);
            x++;
    }
  


</script>


