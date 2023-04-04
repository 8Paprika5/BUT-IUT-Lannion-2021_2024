<?php
    include("fonctions/Session.php");
    include("fonctions/fonctions.php");
    include("./fonctions/chiffrement.php");

    ob_start();

    // header("Location: ./inscription.php");
    // Déconnexion
    if(isset($_POST['deco'])) {
        $_SESSION['id_client'] = null;
        setcookie("id_panier","");
        $_SESSION['deco'] = $_POST['deco'];
        header("Location: connexion.php");
        exit();
    }

    $client = infos_Client($_SESSION['id_client']);
    $listeAdresse = listeAdresse($_SESSION['id_client']);
    $listeAdresseFacturation = array();
    $listeAdresseLivraison = array();
    foreach($listeAdresse as $adr){
        if($adr['adresse_facturation']!=0){
            array_push($listeAdresseFacturation,$adr);
        }else{
            array_push($listeAdresseLivraison,$adr);
        }
    }

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
            header("Location: monCompte.php?info_modifiées=true");
    }

    if(isset($_POST['mode'])){
        if($_POST['mode'] == "Ajouter"){
            if(empty($_POST['complementAdresse'])){
                if(isset($_POST['checkbox_facturation'])){
                    ajouterAdresse($_SESSION['id_client'],$_POST['adr'], NULL, $_POST['ville'], $_POST['codePostal'],TRUE);
                }else{
                    ajouterAdresse($_SESSION['id_client'],$_POST['adr'], NULL, $_POST['ville'], $_POST['codePostal'],FALSE);
                }
                
            }else{
                if(isset($_POST['checkbox_facturation'])){
                    ajouterAdresse($_SESSION['id_client'],$_POST['adr'], $_POST['complementAdresse'], $_POST['ville'], $_POST['codePostal'],TRUE);
                }else{
                    ajouterAdresse($_SESSION['id_client'],$_POST['adr'], $_POST['complementAdresse'], $_POST['ville'], $_POST['codePostal'],FALSE);
                }
                
            }
            header('Location: ./monCompte.php#Mes-adresses');
            
        }else{
            if($_POST['mode'] == "Modifier"){
                if(empty($_POST['complementAdresse'])){
                    if(isset($_POST['checkbox_facturation'])){
                        modifierAdresse($_POST['ID_Adresse'],$_POST['adr'],NULL,$_POST['ville'],$_POST['codePostal'],TRUE);
                    }else{
                        modifierAdresse($_POST['ID_Adresse'],$_POST['adr'],NULL,$_POST['ville'],$_POST['codePostal'],FALSE);
                    }
                }else{
                    if(isset($_POST['checkbox_facturation'])){
                        modifierAdresse($_POST['ID_Adresse'],$_POST['adr'],$_POST['complementAdresse'],$_POST['ville'],$_POST['codePostal'],TRUE);
                    }else{
                        modifierAdresse($_POST['ID_Adresse'],$_POST['adr'],$_POST['complementAdresse'],$_POST['ville'],$_POST['codePostal'],FALSE);
                    }
                }
                header('Location: ./monCompte.php#Mes-adresses');
            }else{
                if($_POST['mode'] == "Supprimer cette adresse"){
                    SupprimerAdresse($_POST["ID_Adresse"]);
                    header("Location: ./monCompte.php#Mes-adresses");
                }
            }
        }
    }
    $lCommandesCours = details_commande($_SESSION['id_client']);
    
    /**Tableau $lCommandesCours contenant des tableau :
     * [listeCommandeTotale]
            * [ID_Commande]
            * [ID_Client]
            * [etat_commande]
            * [adresse_livraison]
            * [date_commande]
            * [date_livraison]
            * [Duree_maximale_restante]
            * [prix_total]
            * [nb]  

            ET contient pour chaque produit les informations suivantes :
                    * [ID_Produit]
                    * [ID_Commande]
                    * [ID_Client
                    * [etat_commande]
                    * [adresse_livraison]
                    * [date_commande]
                    * [date_livraison]
                    * [Duree_maximale_restante]
                    * [prix_total]
                    * [Quantite]
                    * [Prix_produit_commande_HT]
                    * [Prix_produit_commande_TTC]
                    * [Nom_produit]
                    * [Prix_coutant]
                    * [Prix_vente_HT]
                    * [Prix_vente_TTC]
                    * [Quantite_disponnible]
                    * [Description_produit]
                    * [images1]
                    * [images2]
                    * [images3]
                    * [Moyenne_Note_Produit]
                    * [Id_Sous_Categorie]
                    * [ID_Vendeur]
     */





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
            <?php if(isset($_GET["info_modifiées"])){
                echo "<div class='alert'>";
                echo "<i class='fa-regular fa-circle-check fa-2x' style='margin-top : 13px;'></i>";
                echo "<p> Informations modifiées !</p>";
                echo "</div>";
            }
            unset($_GET["info_modifiées"]);
            ?>    
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
                    <a class="infosClient" id="infos_personnelles_btn" href="#infos_personnelles"><i class="fa-solid fa-user"></i>Mes infos persos</a>
                    <a class="btn-commande" id="infos_commandes_btn" href="#infos_commandes"><i class="fa-sharp fa-solid fa-cart-shopping"></i>Mes commandes</a>
                </div>

                <div class="bloc_suppression">
                    <div id="Supprimer">
                        <button class="supprimer" name="supprimer" id="supp">Désactiver le compte</button>
                    </div>
                </div>

            </aside>

            <!-- Informations Compte -->

            <div id="infos_personnelles_block" class="bloc_infos_client affiche">
                <div class="bloc_infos_persos_client">
                    <section>
                        <h1 class="titre_infos_client">Mes informations Personnelles</h1>
                        <div class="coordonnees">

                            <form class="form_infos_compte" method="POST" action="monCompte.php">

                                <div class="formulaire">

                                    <div class="champ_infos_persos">
                                        <label for="fname">Nom</label>
                                        <input id="fname" type="text" name="nom" placeholder=<?php echo str_replace(" ","-",$client['nom_client']);?>>
                                    </div>

                                    <div class="champ_infos_persos">
                                        <label for="fname">Prénom</label>
                                        <input type="text" name="prenom" placeholder=<?php echo str_replace(" ","-",$client['prenom_client']);?>>
                                    </div>
                                    <div class="champ_infos_persos">
                                        <label for="fname">Email <i class="fa-solid fa-triangle-exclamation" style="color: red;" title="Impossible de modifier l'adresse mail"></i></label>
                                        <input type="email" name="email" readonly="readonly" title="Impossible de modifier l'adresse mail" value=<?php echo str_replace(" ","-",$client['email']); ?>>
                                    </div>

                                    <div class="champ_infos_persos">
                                        <label for="fname">Date de naissance</label>
                                        <input type="date" name="DateDeNaissance" value=<?php echo str_replace(" ","-",$client['date_de_naissance']);?>>
                                    </div>

                                </div>

                                <div class="bloc_confirmer" id="Mes-adresses">
                                    <input type="submit" id="confirmer2" name="coordonnees" value="Confirmer">
                                </div>
                            </form>

                        </div>
                    </section>
                </div>

                <div class="bloc_infos_persos_client AdressesClient">
                    <section>
                        <h1 class="titre_infos_client" >Mes adresses</h1>
                        <div class="coordonnees form_infos_adresse">
                            <h3>Adresses de facturation et livraison</h3>     
                            
                            <?php foreach($listeAdresseFacturation as $adr):
                                if($adr['complement'] != ''){
                                    $adresse = $adr['nom_de_rue'] . " " . $adr['complement'] . " " . $adr['code_postale'] . " " . $adr['ville'];
                                }else{
                                    $adresse = $adr['nom_de_rue'] . " " . $adr['code_postale'] . " " . $adr['ville'];
                                }?>
                                <div>
                                    <input class="champ_adresse" type="text" value="<?php echo $adresse;?>" disabled>
                                    
                                    <form action="./monCompte.php#Mes-adresses" method="POST">
                                        <button class="modifier" name="ModifierAdresse" type='submit'>Modifier</button>
                                        <input type="hidden" name="adr" value="<?php echo $adr['nom_de_rue'];?>">
                                        <input type="hidden" name="complementAdresse" value="<?php echo $adr['complement'];?>">
                                        <input type="hidden" name="ville" value="<?php echo $adr['ville'];?>">
                                        <input type="hidden" name="codePostal" value="<?php echo $adr['code_postale'];?>">
                                        <input type="hidden" name="ID_Adresse" value="<?php echo $adr['ID_Adresse'];?>">
                                        <input name="checkbox_facturation" type="hidden" value="<?php echo $adr['adresse_facturation'];?>">
                                    </form>

                                    <form action="#Mes-adresses" method="POST">
                                        <?php if(sizeof($listeAdresseFacturation) <= 1):?>
                                            <button class="supprimer" name="SupprimmerAdresse" value="<?php echo $adr['ID_Adresse'];?>" type='submit' title="Vous devez obligatoirement avoir une adresse de facturation minimum" disabled>Supprimer</button>
                                        <?php else:?>
                                            <button class="supprimer" name="SupprimmerAdresse" value="SupprimmerAdresse" type='submit'>Supprimer</button>
                                            <input type="hidden" name="adr" value="<?php echo $adr['nom_de_rue'];?>">
                                            <input type="hidden" name="complementAdresse" value="<?php echo $adr['complement'];?>">
                                            <input type="hidden" name="ville" value="<?php echo $adr['ville'];?>">
                                            <input type="hidden" name="codePostal" value="<?php echo $adr['code_postale'];?>">
                                            <input type="hidden" name="ID_Adresse" value="<?php echo $adr['ID_Adresse'];?>">
                                        <?php endif;?>
                                    </form>
                                </div>
                            <?php endforeach;?>
                            <?php if(sizeof($listeAdresseLivraison)>0):?>
                                <h3>Adresses de livraison</h3>
                            <?php endif;?>
                            <?php foreach($listeAdresseLivraison as $adr):
                                if($adr['complement'] != ''){
                                    $adresse = $adr['nom_de_rue'] . " " . $adr['complement'] . " " . $adr['code_postale'] . " " . $adr['ville'];
                                }else{
                                    $adresse = $adr['nom_de_rue'] . " " . $adr['code_postale'] . " " . $adr['ville'];
                                }?>
                                <div>
                                    <input class="champ_adresse" type="text" value="<?php echo $adresse;?>" disabled>
                                    
                                    <form action="#Mes-adresses" method="POST">
                                        <button class="modifier" name="ModifierAdresse" type='submit'>Modifier</button>
                                        <input type="hidden" name="adr" value="<?php echo $adr['nom_de_rue'];?>">
                                        <input type="hidden" name="complementAdresse" value="<?php echo $adr['complement'];?>">
                                        <input type="hidden" name="ville" value="<?php echo $adr['ville'];?>">
                                        <input type="hidden" name="codePostal" value="<?php echo $adr['code_postale'];?>">
                                        <input type="hidden" name="ID_Adresse" value="<?php echo $adr['ID_Adresse'];?>">
                                        <input name="checkbox_facturation" type="hidden" value="<?php echo $adr['adresse_facturation'];?>">
                                    </form>

                                    <form action="#Mes-adresses" method="POST">
                                        <button class="supprimer" name="SupprimmerAdresse" value="SupprimmerAdresse" type='submit'>Supprimer</button>
                                        <input type="hidden" name="adr" value="<?php echo $adr['nom_de_rue'];?>">
                                        <input type="hidden" name="complementAdresse" value="<?php echo $adr['complement'];?>">
                                        <input type="hidden" name="ville" value="<?php echo $adr['ville'];?>">
                                        <input type="hidden" name="codePostal" value="<?php echo $adr['code_postale'];?>">
                                        <input type="hidden" name="ID_Adresse" value="<?php echo $adr['ID_Adresse'];?>">
                                    </form>
                                </div>
                            <?php endforeach;?>

                            <div class="bloc_confirmer">
                                <form action="#Mes-adresses" method="POST">
                                    <input type="submit" name="AjouterAdresse" class="ajouter" value="Ajouter">
                                </form>
                            </div>
                        </div>
                    </section>
                </div>

                <form class="bloc_infos_persos_client ChangerAdresse" method="POST" action="monCompte.php#Mes-adresses">
                    <h1 class="titre_infos_client" >Nouvelle Adresse</h1>
                    <input type="hidden" name="ID_Adresse" value="<?php if(isset($_POST['ModifierAdresse']) && isset($_POST['ID_Adresse']) ){echo $_POST['ID_Adresse'];}?>" required >
                    
                    <label>Adresse postale</label>
                    <input class="champ_adresse" type="text" name="adr" value="<?php if(isset($_POST['ModifierAdresse']) && isset($_POST['adr']) ){echo $_POST['adr'];}?>" required />
                    
                    <label>Complément d'adresse</label>
                    <input class="champ_adresse" type="text" name="complementAdresse" value="<?php if(isset($_POST['ModifierAdresse']) && isset($_POST['complementAdresse']) ){echo $_POST['complementAdresse'];}?>"/>
                    
                    <div class="bloc-flexAdresse">
                        <div>
                            <label>Ville</label>
                            <input class="champ_ville" type="text" name="ville" value="<?php if(isset($_POST['ModifierAdresse']) && isset($_POST['ville']) ){echo $_POST['ville'];}?>" required >
                        </div>

                        <div>
                            <label>Code postal</label>
                            <input class="champ_codePostal" type="number" name="codePostal" value=<?php if(isset($_POST['ModifierAdresse']) && isset($_POST['codePostal']) ){echo $_POST['codePostal'];}?> required>
                        </div>
                    </div>

                    
                    <?php if(isset($_POST['ModifierAdresse']) && isset($_POST['checkbox_facturation']) && $_POST['checkbox_facturation'] == 1 && sizeof($listeAdresseFacturation) <= 1):?>
                        <div class="Block_checkbox_Adressefacturation_disbaled">
                        <input name="checkbox_facturation" type="checkbox" checked disabled><label>Utiliser comme adresse de facturation</label> <label class="texte_erreur_adr">(Vous devez obligatoirement avoir une adresse de facturation minimum)</label>
                        <input type="hidden" name="checkbox_facturation" checked> 
                    <?php else:?>
                        <div>
                        <input name="checkbox_facturation" type="checkbox" <?php if(isset($_POST['ModifierAdresse']) && isset($_POST['checkbox_facturation']) &&  $_POST['checkbox_facturation'] == 1){echo "checked";}?>><label>Utiliser comme adresse de facturation</label>
                    <?php endif;?>
                    </div>

                    <div class="bloc_confirmer">
                        <input type="submit" class="ajouter" name="mode" value="Ajouter">
                        <a href="#Mes-adresses" class="supprimer">Annuler</a>    
                    </div>
                </form>

                <form class="bloc_infos_persos_client SupprimerAdresse" method="POST" action="monCompte.php#Mes-adresses">
                    <h1 class="titre_infos_client" >Supprimer cette adresse</h1>
                    <input type="hidden" name="ID_Adresse" value="<?php if(isset($_POST['SupprimmerAdresse']) && isset($_POST['ID_Adresse']) ){echo $_POST['ID_Adresse'];}?>" required >
                    <?php 
                        if(isset($_POST["SupprimmerAdresse"])):
                            if(isset($_POST['complementAdresse']) && $_POST['complementAdresse'] != ''){
                                $adresse = $_POST['adr'] . " " . $_POST['complementAdresse'] . " " . $_POST['codePostal'] . " " . $_POST['ville'];
                            }else{
                                $adresse = $_POST['adr'] . " " . $_POST['codePostal'] . " " . $_POST['ville'];
                            }
                        ?>
                        <input class="champ_adresse" type="text" name="adr" value="<?php echo $adresse;?>" required/>
                        <?php endif;?>
                    
                    <div class="bloc_confirmer">
                        <input type="submit" class="ajouter" name="mode" value="Supprimer cette adresse">
                        <a href="#Mes-adresses" class="supprimer">Annuler</a>    
                    </div>
                </form>

                <script> 
                    var bloc_AdressesClient = document.querySelector(".AdressesClient");
                    var button_Ajouter = document.querySelector(".form_infos_adresse .bloc_confirmer input");
                    var button_Modifier = document.querySelectorAll(".form_infos_adresse button.modifier");
                    var button_Supprimer = document.querySelectorAll(".form_infos_adresse button.supprimer");

                    var bloc_ChangerAdresse = document.querySelector(".ChangerAdresse");
                    var bloc_SupprimerAdresse = document.querySelector(".SupprimerAdresse");
                    var button_Valider = document.querySelector(".ChangerAdresse .bloc_confirmer input.ajouter");
                    var Titre_ChangerAdresse = document.querySelector(".ChangerAdresse h1");

                    var champs_ChangerAdresse_adr = document.querySelector(".ChangerAdresse input[name='adr']");
                    var champs_ChangerAdresse_complementAdresse = document.querySelector(".ChangerAdresse input[name='complementAdresse']");
                    var champs_ChangerAdresse_ville = document.querySelector(".ChangerAdresse input[name='ville']");
                    var champs_ChangerAdresse_codePostal = document.querySelector(".ChangerAdresse input[name='codePostal']");

                    var button_Annuler_ChangerAdresse = document.querySelector(".ChangerAdresse a.supprimer");
                    var button_Annuler_SupprimerAdresse = document.querySelector(".SupprimerAdresse a.supprimer");
                    
                    bloc_ChangerAdresse.style['display'] = "None";
                    bloc_SupprimerAdresse.style['display'] = "None";

                    
                    <?php if(isset($_POST["ModifierAdresse"])):?>
                        bloc_ChangerAdresse.style['display'] = "block";
                        bloc_AdressesClient.style['display'] = "None";
                        Titre_ChangerAdresse.textContent = "Modifier une adresse";
                        button_Valider.setAttribute('value', "Modifier");
                    <?php endif;?>
                       
                    <?php if(isset($_POST["AjouterAdresse"])):?>
                        bloc_ChangerAdresse.style['display'] = "block";
                        bloc_AdressesClient.style['display'] = "None";
                        Titre_ChangerAdresse.textContent = "Ajouter une adresse";
                        button_Valider.setAttribute('value', "Ajouter");
                        button_Valider.classList.remove("modifier");
                        button_Valider.classList.add("ajouter");
                        
                        champs_ChangerAdresse_adr.setAttribute('value', "");
                        champs_ChangerAdresse_complementAdresse.setAttribute('value', "");
                        champs_ChangerAdresse_ville.setAttribute('value', "");
                        champs_ChangerAdresse_codePostal.setAttribute('value', "");
                    <?php endif;?>

                    <?php if(isset($_POST["SupprimmerAdresse"])):?>
                        bloc_SupprimerAdresse.style['display'] = "block";
                        bloc_AdressesClient.style['display'] = "None";
                    <?php endif;?>
                        

                    button_Annuler_ChangerAdresse.addEventListener('click', function(){
                        bloc_ChangerAdresse.style['display'] = "None";
                        bloc_AdressesClient.style['display'] = "Block";
                    });

                    button_Annuler_SupprimerAdresse.addEventListener('click', function(){
                        bloc_SupprimerAdresse.style['display'] = "None";
                        bloc_AdressesClient.style['display'] = "Block";
                        
                    });

                    /*button_Modifier.forEach(function(element){
                            element.addEventListener('click', function(){
                            button_Valider.classList.remove("ajouter");
                            button_Valider.classList.add("modifier");
                            let adresse = element.value.split('/');
                            champs_ChangerAdresse_adr.setAttribute('value',adresse[0]);
                            champs_ChangerAdresse_complementAdresse.setAttribute('value',adresse[1]);
                            champs_ChangerAdresse_ville.setAttribute('value',adresse[2]);
                            champs_ChangerAdresse_codePostal.setAttribute('value',adresse[4]);
                        });
                    })*/


                    
                </script>
                <div class="bloc_mdp_client">

                    <section>

                        <h1 class="titre_mdp_client">Modifier mon mot de passe </h1>

                        <div class="motDePasse">

                            <form class="form_mdp_compte" method="post" action="monCompte.php">

                                <div class="formulaire">

                                    <div class="champ_mdp_compte">
                                        <label for="fname">Ancien mot de passe</label>
                                        <div class="mdp">
                                            <input type="password" id="pass1" name="AncienMdp" required>
                                            <img src="../img/oeilOuvert.png" id="eye1" onClick="changer1()">
                                        </div>
                                    </div>

                                    <div class="champ_mdp_compte">
                                        <label for="fname">Nouveau mot de passe</label>
                                        <div class="mdp">
                                            <input type="password" id="pass2" name="mdp" minlength="8" required>
                                            <img src="../img/oeilOuvert.png" id="eye2" onClick ="changer2()">
                                        </div>
                                    </div>

                                    <div class="champ_mdp_compte">
                                        <label for="fname">Confirmation mot de passe</label>
                                        <div class = "mdp">
                                            <input type="password" id="pass3" name="ConfirmationMdp" minlength="8" required>
                                            <img src="../img/oeilOuvert.png" id="eye3" onClick="changer3()">
                                        </div>
                                    </div>

                                    <?php if(isset($_POST["VerifConfirmationMdp"])){echo "<p>Mot de passe incohérent</p>";} ?>
                                    <?php if(isset($_POST["VerifAncienMdp"])){echo "<p>Ancien mot de passe incorrect</p>";} ?>

                                </div>

                                <div>
                                    <input type="submit" id="confirmer1" name="ModificationMdp" value="Confirmer">
                                </div>

                            </form>

                        </div>

                    </section>

                </div>

            </div>

            <!-- Commandes Compte -->

            <section id="infos_commandes_block" class="mesCommandes cache">

                <nav>
                    <div>
                        <h2><a onClick="changeCommandes()" id="CommandesCours" href="#enCours" class="no_deco bleu carter">Commandes en cours</a></h2>
                        <hr class="commande_nav_barre-cours affiche"/>
                    </div>
                    <div>
                        <h2><a onClick="changeCommandes()" id="CommandesArch" href="#effectuees" class="no_deco gris carter">Commandes archivées</a></h2>
                        <hr class="commande_nav_barre-archi cache"/>
                    </div>
                </nav>
                <div class='commandes_cours affiche'>
                    <?php if($lCommandesCours['nbListeCommande'] > 0) :
                    
                            foreach ($lCommandesCours['listeCommande'] as $laCommande):
                        ?>

                        <div class="commande">
                            <aside>
                                <div class="date_commande">
                                    <h3>Commande passée le </h3>
                                    <h3 class="bleu"><?php echo dateFr($laCommande['date_commande']);?></h3>
                                </div>
                                <div class="prix_commande">
                                    <h3 class="nb_articles_commande"><?php
                                            $s = "";
                                            if($laCommande['nb']>1){
                                                $s ="s";
                                            }
                                            echo $laCommande['nb']." article".$s;
                                        ?></h3>
                                    <h3><?php echo $laCommande['prix'];?> €</h3>
                                </div>
                                <div class="adresse_livraison">
                                    <h3>Livraison à </h3>
                                    <h3 class="bleu"><?php echo $laCommande["nom_de_rue_livraison"].", ".$laCommande["ville_livraison"].", ".$laCommande["code_postale_livraison"];?></h3>
                                </div>
                                <div>
                                    <h3>Livraison prévue le <?php echo dateFr($laCommande['date_livraison']); ?></h3>
                                    <h3><a href="#" class="no_deco facture">Facture</a></h3>
                                </div>
                                <!-- <form class="form-recommander" action="panier.php" method="POST">
                                    <input type="hidden" id="commandeID<?php echo $laCommande['ID_Commande'];?>" name="commandeID" value="<?php echo $laCommande['ID_Commande'];?>" />

                                    <button type="submit" id="button-recommander<?php echo $laCommande['ID_Commande'];?>" name="button-recommander">Recommander</button>
                                    </form>-->
                            </aside>
                            <div>
                                <h2 class="bleu">Commande n°<?php echo $laCommande['ID_Commande'];?></h2>
                                <button class="details-cours" id="<?php echo $laCommande['ID_Commande'];?>">Détails</button>
                                <form id = "formSuiviCommande<?php echo $laCommande['ID_Commande'];?>" name = "suiviCommande"action="suiviCommande.php?<?php echo 'numCommande='.$laCommande["ID_Commande"]?>" method="POST" >
                                    <input type="hidden" name ="IdCommande" value = <?php echo $laCommande['ID_Commande']; ?> ></label>
                                    <button type='submit' class = "suiviCommande" name='suiviCommande'>Suivre votre colis</button>
                                </form>
                            </div>
                            <div class="liste_produits_commande<?php echo $laCommande['ID_Commande'];?>">
                                <?php for ($i=0; $i < $laCommande['nb']; $i++):
                                        $src=str_replace(' ', "_","../img/catalogue/Produits/".$laCommande["$i"]['ID_Produit']."/");
                                ?>
                                <div class="produit_commande<?php echo $laCommande['ID_Commande'];?>">
                                    <div class="infos_produit_commande">
                                        <img src='<?php echo str_replace(' ', "_",$src.$laCommande["$i"]['images1']); ?>' alt="Image Produit" title="Image Produit"class="img_commande">
                                        <div class="produit_commande-description">
                                            <h4><?php echo $laCommande["$i"]['Nom_produit']; ?></h4>
                                            <div class="rachat_compl"><a href="produit.php?idProduit=<?php echo $laCommande[$i]['ID_Produit'];?>">Acheter à nouveau</a></div>
                                            <div class="rachat_plus"><a href="produit.php?idProduit=<?php echo $laCommande[$i]['ID_Produit'];?>">+</a></div>
                                        </div>
                                        <div>
                                            <h3><?php echo $laCommande["$i"]['Prix_vente_TTC']; ?> €</h3>
                                            <h4>Quantité : <?php echo $laCommande[$i]['Quantite']; ?></h4>
                                        </div>
                                    </div>
                                    <!-- bouton avis pas important dans commandes en cours
                                    <aside class="produit_commande-boutons">

                                        <a>Donner un avis</a>
                                    </aside>
                                    -->
                                </div>
                                <?php if($i < $laCommande['nb']-1): ?>

                                <?php endif;
                                    endfor;
                                ?>
                            </div>
                        </div>
                    <?php
                        endforeach;
                    ?>
                    <?php else :?>
                        <div>
                            <h2>Vous n'avez aucune commande en cours</h2>
                        </div>
                    <?php endif;?>
                </div>

                <div class='commandes_archivees cache'>
                    <?php if($lCommandesCours['nbCommandeArch'] > 0) :
                        foreach ($lCommandesCours['listeCommandeArch'] as $laCommande):
                    ?>

                        <div class="commande">
                            <aside>
                                <div class="date_commande">
                                    <h3>Commande passée le </h3>
                                    <h3 class="bleu"><?php echo dateFr($laCommande['date_commande']);?></h3>
                                </div>
                                <div class="prix_commande">
                                    <h3 class="nb_articles_commande"><?php
                                            $s = "";
                                            if($laCommande['nb']>1){
                                                $s ="s";
                                            }
                                            echo $laCommande['nb']." article".$s;
                                        ?></h3>
                                    <h3><?php echo $laCommande['prix'];?> €</h3>
                                </div>
                                <div class="adresse_livraison">
                                    <h3>Livraison à </h3>
                                    
                                    <h3 class="bleu"><?php echo $laCommande["nom_de_rue_livraison"].", ".$laCommande["ville_livraison"].", ".$laCommande["code_postale_livraison"];?></h3>
                                </div>
                                <div>
                                    <h3>Commande n°<?php echo $laCommande['ID_Commande']; ?></h3>
                                    <h3><a href="#" class="no_deco facture">Facture</a></h3>
                                </div>
                                <form class="form-recommander" action="panier.php" method="POST">
                                    <input class='cache' type="cache" id="commandeID<?php echo $laCommande['ID_Commande'];?>" name="commandeID" value="<?php echo $laCommande['ID_Commande'];?>" />

                                    <button type="submit" id="button-recommander<?php echo $laCommande['ID_Commande'];?>" name="button-recommander">Acheter à nouveau</button>
                                </form>
                            </aside>
                            <div>
                                <h2 class="bleu">Livraison terminée</h2>
                                <button class="details-archiv" id="<?php echo $laCommande['ID_Commande'];?>">Détails</button>
                            </div>
                            <!--<div class="liste_produits_commande<?php echo $laCommande['nb'];?>">
                                <?php for ($i=0; $i < $laCommande['nb']; $i++) {
                                        $src=str_replace(' ', "_","../img/catalogue/Produits/".$laCommande["$i"]['ID_Produit']."/");
                                ?>
                                <div class="produit_commande<?php echo $laCommande['nb'];?>">
                                    <div class="infos_produit_commande">
                                        <img src='<?php echo str_replace(' ', "_",$src.$laCommande["$i"]['images1']); ?>' alt="Image Produit" title="Image Produit"class="img_commande">
                                        <div class="produit_commande-description">
                                            <h4><?php echo $laCommande["$i"]['Nom_produit']; ?></h4>
                                            <div class="rachat_compl"><a href="produit.php?idProduit=<?php echo $laCommande[$i]['ID_Produit'];?>">Acheter à nouveau</a></div>
                                            <div class="rachat_plus"><a href="produit.php?idProduit=<?php echo $laCommande[$i]['ID_Produit'];?>">+</a></div>
                                        </div>
                                        <div>
                                            <h3><?php echo $laCommande["$i"]['Prix_vente_TTC']; ?> €</h3>
                                            <h4>Quantité : <?php echo $laCommande[$i]['Quantite']; ?></h4>
                                        </div>
                                    </div>

                                    <aside class="produit_commande-boutons">
                                        <a href="produit.php?idProduit=<?php echo $laCommande[$i]['ID_Produit']; ?>#bloc-btn-ajouter-avis">Donner un avis</a>
                                    </aside>
                                </div>
                                <?php if($i < $laCommande['nb']-1){ ?>
                                <?php } } ?>
                            </div>-->
                            <div class="liste_produits_commande<?php echo $laCommande['ID_Commande'];?>">
                                <?php for ($i=0; $i < $laCommande['nb']; $i++):
                                        $src=str_replace(' ', "_","../img/catalogue/Produits/".$laCommande["$i"]['ID_Produit']."/");
                                ?>
                                <div class="produit_commande<?php echo $laCommande['ID_Commande'];?>">
                                    <div class="infos_produit_commande">
                                        <img src='<?php echo str_replace(' ', "_",$src.$laCommande["$i"]['images1']); ?>' alt="Image Produit" title="Image Produit"class="img_commande">
                                        <div class="produit_commande-description">
                                            <h4><?php echo $laCommande["$i"]['Nom_produit']; ?></h4>
                                            <div class="rachat_compl"><a href="produit.php?idProduit=<?php echo $laCommande[$i]['ID_Produit'];?>">Acheter à nouveau</a></div>
                                            <div class="rachat_plus"><a href="produit.php?idProduit=<?php echo $laCommande[$i]['ID_Produit'];?>">+</a></div>
                                        </div>
                                        <div>
                                            <h3><?php echo $laCommande["$i"]['Prix_vente_TTC']; ?> €</h3>
                                            <h4>Quantité : <?php echo $laCommande[$i]['Quantite']; ?></h4>
                                        </div>
                                        <aside class="produit_commande-boutons">
                                            <a href="produit.php?idProduit=<?php echo $laCommande[$i]['ID_Produit']; ?>#bloc-btn-ajouter-avis">Donner un avis</a>
                                        </aside>
                                    </div>
                                    
                                    
                                    
                                </div>
                                <?php if($i < $laCommande['nb']-1): ?>

                                <?php endif;
                                    endfor;
                                ?>
                            </div>
                        </div>
                    <?php
                        endforeach;
                    ?>
                    <?php else :?>
                        <div>
                            <h2>Vous n'avez aucune commande archivée</h2>
                        </div>
                    <?php endif;?>
                </div>
            </section>

            <!-- Pop-up Désactiver son compte -->

            <div class="fond_opacity"></div>

            <div class="form-popup2" id="popup-Form">
                <form action="moncompte.php" method="GET" class="form-container2">

                    <h2 class="message-desac-compte">Entrez votre mot de passe pour désactiver votre compte :</h2>

                    <input type="password" placeholder="Votre mot de passe..." name="mdp_desac">

                    <p class="texte_erreur_co">Mot de passe incorrecte</p>
                    <p class="texte_erreur_imp">Impossible : Vous avez une commande en cours</p>
                    <p class="texte_erreur_non_saisi">Saisissez votre mot de passe</p>

                    <button type="submit" class="btn-desac open-button">Désactiver mon compte</button>

                </form>

                <div class="bouton_cancel">
                    <button class="btn-cancel open-button" onclick="closeForm()">Annuler</button>
                </div>

            </div>

        </main>

        <footer>
            <?php include('footer.php'); ?>
        </footer>

        <!-- Désactivation du compte -->
        <?php if((isset($_GET['mdp_desac']))):?>

            <?php 
                $mdp = $_GET['mdp_desac'];
                $verif = verif_mdp($_SESSION['id_client']);
            ?>

            <?php if ($mdp == ""):?>
                <script>
                    var popUP_Desactive = document.getElementById("popup-Form");
                    var fond_opacity = document.querySelector(".fond_opacity");
                    var texte_erreur_non_saisi = document.querySelector(".texte_erreur_non_saisi");

                    popUP_Desactive.classList.add("form-popup2--affiche");
                    fond_opacity.classList.add("fond_opacity--affiche");
                    texte_erreur_non_saisi.style.display = "block";
                </script>
            <?php endif;?>

            <?php if ($verif[0]['mdp'] == chiffrementMDP($mdp)):?>
                
                <?php 
                    $res = desactiver_compte($_SESSION['id_client'], $_COOKIE['id_panier']);
                    print_r($res);
                ?>
                    
                    <?php if($res): ?>
                        <?php
                            $_SESSION['id_client'] = null;
                            setcookie("id_panier","");
                            $_SESSION['desac'] = 1;
                            $verif2 = true;
                            header("Location: ./inscription.php");
                            ob_end_flush();
                            exit();
                        ?>

                    <?php else:?>
                        <script>
                            var popUP_Desactive = document.getElementById("popup-Form");
                            var fond_opacity = document.querySelector(".fond_opacity");
                            var texte_erreur_imp = document.querySelector(".texte_erreur_imp");

                            popUP_Desactive.classList.add("form-popup2--affiche");
                            fond_opacity.classList.add("fond_opacity--affiche");
                            texte_erreur_imp.style.display = "block";
                        </script>
                    <?php endif;?>

            <?php elseif (($verif[0]['mdp'] != chiffrementMDP($mdp)) && ($mdp!="")):?>
                <script>
                    var popUP_Desactive = document.getElementById("popup-Form");
                    var fond_opacity = document.querySelector(".fond_opacity");
                    var texte_erreur_co = document.querySelector(".texte_erreur_co");

                    popUP_Desactive.classList.add("form-popup2--affiche");
                    fond_opacity.classList.add("fond_opacity--affiche");
                    texte_erreur_co.style.display = "block";
                </script>

            <?php endif;?>
        <?php endif;?>

        <script>
            var infos_cliBtn = document.getElementById("infos_personnelles_btn");
            var infos_cli = document.getElementById("infos_personnelles_block");
            var infos_comBtn = document.getElementById("infos_commandes_btn");
            var infos_com = document.getElementById("infos_commandes_block");
            var elem_aff = document.getElementsByClassName("affiche")[0];

            var coursBtn = document.getElementById("CommandesCours");
            var barreCours = document.getElementsByClassName("commande_nav_barre-cours")[0];
            var cours = document.getElementsByClassName("commandes_cours")[0];
            var archiveBtn = document.getElementById("CommandesArch");
            var barreArch = document.getElementsByClassName("commande_nav_barre-archi")[0];
            var archive = document.getElementsByClassName("commandes_archivees")[0];


            var e1 = true;
            var e2 = true;
            var e3 = true;

            function changer1() {
                /**
                 * DESCRIPTION
                 *  permet d'afficher ou cacher l'Ancien mot de passe
                 *
                 * PARAMETRES
                 *  None
                 *
                 * RETURN
                 *  None
                 *
                 **/
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
                /**
                 * DESCRIPTION
                 *  permet d'afficher ou cacher le Nouveau mot de passe
                 *
                 * PARAMETRES
                 *  None
                 *
                 * RETURN
                 *  None
                 *
                 **/
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
                /**
                 * DESCRIPTION
                 *  permet d'afficher ou cacher la Confirmation mot de passe
                 *
                 * PARAMETRES
                 *  None
                 *
                 * RETURN
                 *  None
                 *
                 **/
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


                /**
                 * DESCRIPTION
                 *  permet de changer l'apparence de la barre de navigation des commandes (en cours ou archivées)
                 *
                 * PARAMETRES
                 *  None
                 *
                 * RETURN
                 *  None
                 *
                 **/
                coursBtn.addEventListener('click', function(){
                    cours.classList.replace("cache", "affiche");
                    archive.classList.replace("affiche", "cache");

                    coursBtn.classList.replace("gris", "bleu");
                    archiveBtn.classList.replace("bleu", "gris");

                    barreCours.classList.replace("cache", "affiche");
                    barreArch.classList.replace("affiche", "cache");
                });

                archiveBtn.addEventListener('click', function(){
                    archive.classList.replace("cache", "affiche");
                    cours.classList.replace("affiche", "cache");

                    archiveBtn.classList.replace("gris", "bleu");
                    coursBtn.classList.replace("bleu", "gris");

                    barreArch.classList.replace("cache", "affiche");
                    barreCours.classList.replace("affiche", "cache");
                });
                let x = 0;

            <?php foreach($lCommandesCours['listeCommandeTotale'] as $laCommande): ?>

                function affichageBlocCommande<?php echo $laCommande['ID_Commande']; ?>(){
                    let i = 0;
                    while (i < elem_produit<?php echo $laCommande['ID_Commande'];?>.length){
                        if (affiche<?php echo $laCommande['ID_Commande']; ?>){
                            elem_produit<?php echo $laCommande['ID_Commande'];?>[i].style.display = "flex";
                        }
                        else{
                            elem_produit<?php echo $laCommande['ID_Commande'];?>[i].style.display = "none";
                        }
                        i++;
                    }
                    console.log(clique_produit<?php echo $laCommande['ID_Commande']; ?>);
                    affiche<?php echo $laCommande['ID_Commande']; ?> = !(affiche<?php echo $laCommande['ID_Commande']; ?>);
                }
            var elem_produit<?php echo $laCommande['ID_Commande'];?> = document.getElementsByClassName("liste_produits_commande<?php echo $laCommande['ID_Commande'];?>");
            var elem2_produit<?php echo $laCommande['ID_Commande'];?> = document.getElementsByClassName("produit_commande<?php echo $laCommande['ID_Commande'];?>");
            var clique_produit<?php echo $laCommande['ID_Commande']; ?> = document.getElementById("<?php echo $laCommande['ID_Commande'];?>");
            
            clique_produit<?php echo $laCommande['ID_Commande']; ?>.addEventListener("click", affichageBlocCommande<?php echo $laCommande['ID_Commande']; ?>)
            clique_produit<?php echo $laCommande['ID_Commande']; ?>.style.cursor = "pointer";
            var affiche<?php echo $laCommande['ID_Commande']; ?> = true;
            

            
            x = 0;
            elem_produit<?php echo $laCommande['ID_Commande'];?>[0].style.display = "none";
            elem_produit<?php echo $laCommande['ID_Commande'];?>[0].style.flexDirection = "column";
            while (x < elem2_produit<?php echo $laCommande['ID_Commande'];?>.length){
                elem2_produit<?php echo $laCommande['ID_Commande'];?>[x].style.padding = "7px";
                elem2_produit<?php echo $laCommande['ID_Commande'];?>[x].style.paddingLeft = "20px";
                elem2_produit<?php echo $laCommande['ID_Commande'];?>[x].style.justifyContent = "space-between";
                elem2_produit<?php echo $laCommande['ID_Commande'];?>[x].style.width = "95%";
                if (x % 2 == 0){
                    elem2_produit<?php echo $laCommande['ID_Commande'];?>[x].style.backgroundColor = "#f2f2f2";
                }
                
                x++;
            }
            <?php endforeach; ?>

            /* ############################################################################################################## */
            /* ######################################### POP UP COMPTE DESACTIVER ########################################### */
            /* ############################################################################################################## */

            var popUP_Desactive = document.getElementById("popup-Form");
            var btn_Desactive = document.querySelector(".supprimer");
            var fond_opacity = document.querySelector(".fond_opacity");

            const isHidden2 = () => popUP_Desactive.classList.contains("form-popup2--affiche");

            popUP_Desactive.addEventListener("transitionend", function () {
                if (isHidden2()) {
                    popUP_Desactive.style.display = "block";
                    fond_opacity.style.display = "block";
                }
            });

            btn_Desactive.addEventListener("click", function (e) {
                if (isHidden2()) {
                    popUP_Desactive.style.removeProperty("display");
                    setTimeout(() => popUP_Desactive.classList.remove("form-popup2--affiche"), 0);
                    fond_opacity.style.removeProperty("display");
                    setTimeout(() => fond_opacity.classList.remove("fond_opacity--affiche"), 0);
                } 
                else {
                    popUP_Desactive.classList.add("form-popup2--affiche");
                    fond_opacity.classList.add("fond_opacity--affiche");
                }
            });

            function closeForm() {
                /**
                 * DESCRIPTION : Ferme la Pop up de confirmation de suppression
                 */
                if (isHidden2()) {
                    popUP_Desactive.style.removeProperty("display");
                    setTimeout(() => popUP_Desactive.classList.remove("form-popup2--affiche"), 0);
                    fond_opacity.style.removeProperty("display");
                    setTimeout(() => fond_opacity.classList.remove("fond_opacity--affiche"), 0);
                } 
            }

        </script>







    </body>
</html> 