<?php
    include("fonctions/Session.php");
    include("fonctions/fonctions.php");
   
    if(isset($_POST['dec'])) {
        $_SESSION['id_client'] = null;
        header("Location: connexion.php");
        exit();
        echo "coucou";
    }
    $client = infos_Client($_SESSION['id_client']);

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
        update_information($_SESSION['id_client'],$_POST['prenom'],$_POST['nom'],$_POST['DateDeNaissance'], $_POST['email'], $_POST['adresse']);
        header("Location: monCompte.php");  
    }


    $lCommandesCours = details_commande($_SESSION['id_client']);
    #print_r($lCommandesCours['listeCommande']);
    
?>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
    <head>
        <meta charset="utf-8" />
        <title></title>
        <meta name="description" content="" />
        <meta name="keywords" content="" />
        
    </head>
    <body>
        <header>
            <?php include('header.php'); ?>
        </header>

        <main class = "main-Compte">    
            <aside class = "mesInfos">
                <section>
                    <ul class = "infosClient">
                        <li><a href="#home"><i class="fa-solid fa-user"></i>&nbsp Mes infos persos</a></li>
                    </ul>
                    <ul class = "commandes">
                        <li><a class="active" href="#commandes"><i class="fa-sharp fa-solid fa-cart-shopping"></i> Mes commandes</a></li>
                    </ul>
                    <ul class = "liste">
                        <li><a href="#contact"><i class="fa-solid fa-heart"></i> Mes listes</a></li>
                    </ul>
                </section> 
                <div>
                    <form action="monCompte.php" method="post" id ="Deconnexion">
                        <input class = "deconnexion" id ="dec" type = "submit" value = "Se Deconnecter">
                    </form>
                    <?php
                        if(isset($_POST['deco'])) {
                            $_SESSION['id_client'] = null;
                            header("Location: connexion.php");
                            exit();
                        }
                    ?>
                </div>    
                
                <div>
                    <ul class ="NomCli">
                        <li><i class="fa-solid fa-user"></i><?php echo $client['nom_client']." ".$client['prenom_client'];?></li>
                    </ul>
                </section>
            </aside>
            <section class="mesCommandes">
                <nav>
                    <h2><a href="#enCours" class="no_deco bleu carter">Commandes en cours</a></h2>
                    <h2><a href="#effectuees" class="no_deco gris carter">Commandes effectuées</a></h2>
                    <h2><a href="#passees" class="no_deco gris carter">Commandes passées</a></h2>
                </nav>
                <?php if($lCommandesCours['nbCommandeCours'] > 0) :
                        foreach ($lCommandesCours['listeCommande'] as $laCommande){

                        
                    ?>
                    
                    <div class="commande">
                        <aside>
                            <div class="date_commande">
                                <h3>Commande passée le</h3>
                                <h3 class="bleu"><?php echo $laCommande['date_commande']?></h3>
                            </div>
                            <h3><?php echo $laCommande['Prix_produit_commande_TTC']?> €</h3>
                            <div class="adresse_livraison">
                                <h3>Livraison à</h3>
                                <h3 class="bleu"><?php echo $laCommande['adresse_livraison']?></h3>
                            </div>
                            <div>
                                <h3>N° de commande <?php echo $laCommande['ID_Commande'] ?></h3>
                                <h3><a href="#" class="no_deco facture">Facture</a></h3>
                            </div>
                            <button>Acheter à nouveau</button>
                        </aside>
                        <article class="produit_commande">
                            <div>
                                <h2 class="bleu">Livraison prévue le <?php echo $laCommande['date_livraison'] ?></h2>
                                <article class="infos_produit">
                                    <img src="../img/catalogue/Epicerie/Gateaux/1/Images1.jpg" class="img_commande" />
                                    <div class="produit_commande-description">
                                        <h4><?php echo $laCommande['Nom_produit'] ?></h4>
                                        <button>Acheter à nouveau</button>
                                    </div>
                                    <div>
                                        <h3><?php echo $laCommande['Prix_vente_TTC'] ?> €</h3>
                                        <h4>Quantité : <?php echo $laCommande['Quantite'] ?></h4>
                                    </div>
                                </article>
                            </div>
                            <aside class="produit_commande-boutons">
                                <button>Suivre votre colis</button>
                                <button>Renvoyer l'article</button>
                                <button>Donner un avis</button>
                            </aside>
                        </article>
                    </div>
                <?php
                    }
                ?>
                <?php else :?>
                    <div>
                        <h2>Vous n'avez aucune commande en cours</h2>
                    </div>
                <?php endif;?>
            </section>
            
            

        </main>

        <footer>
            <?php include('footer.php'); ?>
        </footer>
        
    </body>
</html>
