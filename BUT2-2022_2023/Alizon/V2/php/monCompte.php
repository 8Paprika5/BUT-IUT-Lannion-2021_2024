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
    //Supprimer le compte
    if(isset($_POST['supp'])) {
        supprimer_compte($_SESSION['id_client'], $_COOKIE['id_panier']);
        header("Location: ./inscription.php");
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
        $email = $_POST['email'];
        $sth = $dbh -> prepare("SELECT ID_Client FROM Alizon._Client WHERE email = '$email'");
        $sth->execute();
        $tabverif = $sth->fetchAll();
        $verif = 0;
        foreach ($tabverif as $row){
            $verif+=1;
        }
        if ($verif >= 1) {
            echo "Erreur : Cet email est déjà existante.";
        }else{
            update_information($_SESSION['id_client'],$_POST['prenom'],$_POST['nom'],$_POST['DateDeNaissance'], $_POST['email'], $_POST['adresse']);
            header("Location: monCompte.php");  
        }
        
    }

    $lCommandesCours = details_commande($_SESSION['id_client']);
    
?>

<!-- <!DOCTYPE html> -->
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
                        <i class="fa-solid fa-user"></i><?php echo $client['nom_client']." ".$client['prenom_client'];?>
                    </div>

                    <div class="bloc_deconnexion">
                        <form action="monCompte.php" method="post" id="Deconnexion">
                            <input class="deconnexion" name="deco" id="dec" type="submit" value="Se Déconnecter">
                        </form>
                    </div>

                </div>

                <div class="liens_compte">
                    <a class="infosClient" onClick="menu()" id="infos_personnelles_btn" href="#infos_personnelles"><i class="fa-solid fa-user"></i>Mes infos persos</a>
                    <a class="btn-commande" onClick="menu()" id="infos_commandes_btn" href="#infos_commandes"><i class="fa-sharp fa-solid fa-cart-shopping"></i>Mes commandes</a>
                    <a class="liste" href="#contact"><i class="fa-solid fa-heart"></i>Mes listes</a>
                </div>

                <div class="bloc_suppression">
                    <form action="monCompte.php" method="post" id="Supprimer" name="formSupp">
                        <input class="supprimer" name="supp" id="supp" onClick="verifDeco()" value="Supprimer le compte">
                    </form>
                    <script>
                        function verifDeco(){
                            var mdp = "<?php echo $client['mdp'] ?>";
                            var verif = prompt("Entrer votre mot de passe pour supprimer votre compte ");

                            if(verif==null) {
                                alert("Suppression Annulée");
                            } else {
                                if (verif == "<?php echo $client['mdp'] ?>") {
                                    alert("Votre compte a bien été supprimé !");
                                    formSupp.submit();
                                } else {
                                    alert("Mot de passe incorrecte");
                                }
                            }
                        }
                    </script>
                </div>

            </aside>

            <!-- Informations Compte -->

            <div id="infos_personnelles_block" class="bloc_infos_client affiche">
                
                <div class="bloc_infos_persos_client">
                        
                    <section>  

                        <h1 class="titre_infos_client">Mes informations Personnelles</h1>

                        <article class="coordonnees">

                            <form class="form_infos_compte" method="post" action="monCompte.php">

                                <section class="formulaire">    

                                    <article class="champ_infos_persos">
                                        <label for="fname">Nom</label><br>
                                        <input type="text" name="nom" placeholder=<?php echo str_replace(" ","-",$client['nom_client']);?>>
                                    </article>

                                    <article class="champ_infos_persos">
                                        <label for="fname">Prénom</label><br>
                                        <input type="text" name="prenom" placeholder=<?php echo str_replace(" ","-",$client['prenom_client']);?>>
                                    </article>              
                                    <article class="champ_infos_persos">
                                        <label for="fname">Email</label><br>
                                        <input type="email" name="email" placeholder=<?php echo str_replace(" ","-",$client['email']);?>>
                                    </article>

                                    <article class="champ_infos_persos">
                                        <label for="fname">Date de naissance</label><br>
                                        <input type="date" name="DateDeNaissance" value=<?php echo str_replace(" ","-",$client['date_de_naissance']);?>>
                                    </article>

                                    <article class="champ_infos_persos">
                                        <label for="fname">Adresse</label><br>
                                        <input type="text" name="adresse" placeholder=<?php echo str_replace(" ","-",$client['adresse_facturation']);?>>
                                    </article>
                                    
                                </section>

                                <article class="bloc_confirmer">
                                        <input type="submit"id = "confirmer2" name="coordonnees" value="Confirmer"/>
                                </article>
                                
                            </form>
                            
                        </article>

                    </section>
                
                </div>

                <div class="bloc_mdp_client">

                    <section>

                        <h1 class="titre_mdp_client">Modifier mon mot de passe </h1>

                        <article class="motDePasse">

                            <form class="form_mdp_compte" method="post" action="monCompte.php">

                                <section class="formulaire">

                                    <article class="champ_mdp_compte">
                                        <label for="fname">Ancien mot de passe</label>
                                        <article class="mdp">  
                                            <input type="password" id="pass1" name="AncienMdp" required>   
                                            <img src="../img/oeilOuvert.png" id="eye1" onClick="changer1()"/>
                                        </article>     
                                    </article>

                                    <article class="champ_mdp_compte">
                                        <label for="fname">Nouveau mot de passe</label>
                                        <article class="mdp">  
                                            <input type="password" id="pass2" name="mdp" minlength="8" required>
                                            <img src="../img/oeilOuvert.png" id="eye2" onClick ="changer2()"/>
                                        </article>                        
                                    </article>

                                    <article class="champ_mdp_compte">
                                        <label for="fname">Confirmation mot de passe</label>
                                            <article class = "mdp">                 
                                                <input type="password" id="pass3" name="ConfirmationMdp" minlength="8" required>
                                                <img src="../img/oeilOuvert.png" id="eye3" onClick="changer3()"/>
                                            </article>                                      
                                    </article> 
                                    
                                    <?php if(isset($_POST["VerifConfirmationMdp"])){echo "<p>Mot de Passe incohérent</p>";} ?>
                                    <?php if(isset($_POST["VerifAncienMdp"])){echo "<p>Ancien mot de passe incorrect</p>";} ?>
                                    
                                </section>

                                <article>
                                    <input type="submit" id="confirmer1" name="ModificationMdp" value="Confirmer"/>
                                </article>

                            </form>
                            
                        </article>

                    </section>

                </div>

            </div>

            <!-- Commandes Compte -->

            <section id="infos_commandes_block" class="mesCommandes cache">

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

            <!-- Liste Favoris Compte -->

        </main>

        <footer>
            <?php include('footer.php'); ?>
        </footer>

        <script>

            var infos_cliBtn = document.getElementById("infos_personnelles_btn");
            var infos_cli = document.getElementById("infos_personnelles_block");
            var infos_comBtn = document.getElementById("infos_commandes_btn");
            var infos_com = document.getElementById("infos_commandes_block");
            var elem_aff = document.getElementsByClassName("affiche")[0];

            var e1 = true;
            var e2 = true;
            var e3 = true;
                            
            function changer1() {
                if (e1){
                    document.getElementById("pass1").setAttribute("type","text");
                    document.getElementById("eye1").src="../img/oeilFerme.png";
                    e1=false;
                }else{
                    document.getElementById("pass1").setAttribute("type","password");
                    document.getElementById("eye1").src="../img/oeilOuvert.png";
                    e1=true;
                }
            }
            function changer2() {
                if (e2){
                    document.getElementById("pass2").setAttribute("type","text");
                    document.getElementById("eye2").src="../img/oeilFerme.png";
                    e2=false;
                }else{
                    document.getElementById("pass2").setAttribute("type","password");
                    document.getElementById("eye2").src="../img/oeilOuvert.png";
                    e2=true;
                }
            }
            function changer3() {
                if (e3){
                    document.getElementById("pass3").setAttribute("type","text");
                    document.getElementById("eye3").src="../img/oeilFerme.png";
                    e3=false;
                }else{
                    document.getElementById("pass3").setAttribute("type","password");
                    document.getElementById("eye3").src="../img/oeilOuvert.png";
                    e3=true;
                }
            }

            function menu() {
                infos_cliBtn.addEventListener('click', function() {
                    elem_aff = document.getElementsByClassName("affiche")[0];
                    elem_aff.classList.replace("affiche", "cache");
                    infos_cli.classList.replace("cache", "affiche");

                    infos_cliBtn.style.background = "white";
                    infos_comBtn.style.background = "var(--jaune)";
                    
                });

                infos_comBtn.addEventListener('click', function() {
                    elem_aff = document.getElementsByClassName("affiche")[0];
                    elem_aff.classList.replace("affiche", "cache");
                    infos_com.classList.replace("cache", "affiche");

                    infos_comBtn.style.background = "white";
                    infos_cliBtn.style.background = "var(--jaune)";
                });
            }

        </script>
    </body>
</html>                    