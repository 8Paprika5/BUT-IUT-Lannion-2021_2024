<?php
    include("fonctions/fonctions.php");
    include("fonctions/carte_produit.php");
    include("fonctions/Session.php");
    if(isset($_POST["deco_vendeur"])) {
        $_SESSION['vendeur_deconnecte'] = true;
        header("Location: ./index.php");
        exit();
    } 
    include("fonctions/ChangementEtatCommande.php");

    if (isset($_SESSION["vendeur"])) {
        $test = $_SESSION["vendeur"];
        echo "<div class='alert'>";
        echo "<i class='fa-regular fa-circle-check fa-2x'></i>";
        echo "<p>Vous êtes connecté !</p>";
        echo "</div>";
     
    } else {
        echo "La clé 'ID_Vendeur' n'a pas été définie dans la session.";
        header("Location: connexion-vendeur.php");
        exit();
    }

    if(isset($_POST['annule'])) {
        echo "ANNULE";
        update_etat_commande($_GET['num_commande'], "annule");
    }
   

    if(isset($_POST['en_cours'])) {
        echo "EN COURS";
        update_etat_commande($_GET['num_commande'], "en cours");
    }

    if(isset($_POST['en_attente'])) {
        echo "ATTENTE";
        update_etat_commande($_GET['num_commande'], "en attente");
    }

    if(isset($_POST['finie'])) {
        echo "FINIE";
        update_etat_commande($_GET['num_commande'], "finie");
    }

    $commandes = afficher_commandes_vendeur();
    
    /** Tableau $commandes
     * ['ID_Client']
     * ['nom_client']
     * ['prenom_client']
     * ['date_commande']
     * ['etat_commande']
     * ['adresse_livraison']
     * ['prix_total']
     */

    $Nos_SousCategories = NosSousCategories();
    $Nos_Categorie= NosCategories();

    $produits = infos_produits($_SESSION["vendeur"][1]);
    
    /** Tableau $produits
     * ['ID_Produit']
     * ['Nom_produit']
     * ['Prix_vente_HT']
     * ['Prix_vente_TTC']
     * ['Quantite_disponnible']
     * ['Moyenne_Note_Produit']
     * ['ID_Produit
     * ['Nom_produit']
     * ['Prix_coutant']
     * ['Prix_vente_HT']
     * ['Prix_vente_TTC']
     * ['Quantite_disponnible']
     * ['Description_produit']
     * ['images1']
     * ['images2']
     * ['images3']
     * ['Moyenne_Note_Produit ']
     * ['Id_Sous_Categorie ']
     * ['ID_Vendeur']
     */
 
    $donneesVendeur = recupDonneeVendeur($_SESSION["vendeur"][1]);
    //$_SESSION["vendeur"] = true;
    
    if(isset($_POST["presentation"]))
    {
        modifier_infos_vendeur($_POST["presentation"], $_POST["note"], $_SESSION["vendeur"][1]);
        header('Location: ./vendeur.php#Mon_Compte');
    }

    if(isset($_POST["ConfirmationMdp"]))
    {
        if(verifier_infos_mdp($_POST["AncienMdp"], $_SESSION["vendeur"][1])){

            if($_POST["ConfirmerMdp"] === $_POST["mdp"]){
                
                update_mdp_vendeur($_POST["mdp"], $_SESSION["vendeur"][1]);
                $update = true;
            }else{
                $erreurmdp = "mdpdifferents";
            }
        }else {
            $erreurmdp = "ancienmdperreur";
        }
    }

    if(isset($_GET["idCom"], $_GET["idProd"])){
        $etat = recupEtatCommande($_GET["idCom"], $_GET["idProd"]);
        changementEtat($_GET["idCom"], $_GET["idProd"], $etat);
    }

if(isset($_GET["finalisation"])){
    if($_GET["finalisation"] == "Valider"){
        update_etat_commande($_GET["Change_idCom"], $_GET["Change_idProd"], $_GET["etat"]);
    }
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendeur</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
</head>
<body>
<header class="header-vendeur">
            <!-- TITRE ALIZON -->
            <a href="index.php" title="Accueil" class="logo">
                <img src="../img/logo2.0.png" alt="Logo Alizon" title="Logo Alizon" width='200' class="img_logo">
            </a>

            <nav class="nav-vendeur">
                <div class="dashboard-vendeur">
                    <i class="fa-solid fa-table-columns fa-xl"></i>
                    <p>Dashboard</p>
                </div>
                <hr>
                <div class="commandes-vendeur active">
                    <i class="fa-solid fa-bag-shopping fa-xl"></i>
                    <p>Commandes</p>
                </div>

                <div class="catalogue-vendeur">
                <i class="fa-solid fa-folder-open fa-xl"></i>
                    <p>Catalogue</p>
                </div>

                <div class="compte-vendeur">
                    <i class="fa-solid fa-user fa-xl"></i>
                    <p>Mon compte</p>
                </div>

                <div class="ajout-produit">
                    <i class="fa-sharp fa-solid fa-file-circle-plus fa-xl"></i>
                    <p>Ajout Produit</p>
                </div>

                <div class="exporter-catalogue">
                    <i class="fa-solid fa-file-export fa-xl"></i>
                    <p>Exporter catalogue</p>
                </div>
                
                <form action="vendeur.php" method="post">
                    <div class="deconnexion-vendeur">
                        <i class="fa-solid fa-right-from-bracket fa-xl"></i>
                        <input type="submit" value="Se déconnecter" name="deco_vendeur">
                        
                    </div>
                </form>
                <?php
                
            ?>
            </nav>
            <div class="row_vendeur">
            <?php
                    
                    echo "<img class='lien_vendeur' src='../img/vendeur/".$donneesVendeur[0]['ID_Vendeur']."/img1.webp' alt='logo vendeur'>";
                    echo "<h4 class='nom_vendeur'>" .$donneesVendeur[0]['Nom_vendeur']. "</h4>";
                    
                ?>
            </div>
          

    </header> 

    <main class="main-vendeur">
    
        <!--<section>
            <h1>Aperçu Général</h1>
            <p>Nombre de ventes</p>
            <p>Chiffre d'affaires d'Alizon</p>
            <p>Nombre de produits</p>
            <p>Ventes de la semaine</p>
            <p>Produits épuisés</p>
        </section>!-->
        <section class="commandes affiche">

            <?php //changementEtat(1, "en-charge"); ?>

            <h1>Commandes</h1>
            <hr>
            <!--<div class=filtreCommande>
                <a class="tous active" onclick="myFunctionTous()" href="javascript:void(0);">Tous (4)</a>
                <a class="encours" onclick="myFunctionEnCours()" href="javascript:void(0);">En Cours (1)</a>
                <a class="attente" onclick="myFunctionEnAttente()" href="javascript:void(0);">En Attente (1)</a>
                <a class="terminé" onclick="myFunctionTerminer()" href="javascript:void(0);">Terminée (1)</a>
                <a class="annuler" onclick="myFunctionAnnuler()" href="javascript:void(0);">Annulée (1)</a>
            </div>-->

            <table id="myTable">
                <tr class="en-tete" id="en-tete">
                    <th><a  onclick="reset()" href="javascript:void(0);">N° </a></th>
                    <th><a  onclick="sortName()" href="javascript:void(0);">Client <i id="Nameicon" class="fas fa-sort"></i></a></th>
                    <th><a  onclick="sortDate()" href="javascript:void(0);">Date <i id="Dateicon" class="fas fa-sort"></i></a></th>
                    <!--<th><a  onclick="sortEtat()" href="javascript:void(0);">Etats <i id="Etaticon" class="fas fa-sort"></i></a></th>-->
                    <th>Facturation</th>
                    <th><a  onclick="sortPrice()"  href="javascript:void(0);">Total <i id="priceicon" class="fas fa-sort"></i></a></th>
                    
                </tr>

                <?php
                    for($i = 0 ; $i < count($commandes) ; $i++)
                    {
                            $commande = $commandes[$i];
                            
                            echo "<tr class=cursor onclick=detailProduit(".($i+1).") href=javascript:void(0)". " id=c".($i+1).">";
                            echo "<td class=sort>#0" . $i+1 . "</th>";
                            echo "<td class=sort>" . "\n" . $commande["prenom_client"]. "\n" . $commande["nom_client"] . "</tf>";
                            echo "<td class=sort>" . dateFr($commande["date_commande"]) . "</th>";
                            echo "<td class=sort>" . $commande["nom_de_rue_facturation"] . ",\n" . $commande["ville_facturation"] . "\n" . $commande["code_postale_facturation"] . "</th>";
                            echo "<td class='prixtab sort'>" . "<p>" . $commande["prix_total"] . "</p>" . "\n€" . "</th>";
                            echo "</tr>";
                    }
         ?>
                
            </table>
            <table id="tableauP">
            <?php
                
                
                    echo "<tr id=headerP"." class='Detailcache en-teteP'>";
                    echo "<th>ID Produit</th>";
                    echo "<th>Nom produit</th>";
                    echo "<th>Etats</th>";
                    echo "<th>Quantité</th>";
                    echo "<th class='prixtab'>Prix</th>";
                    echo "<th></th>";
                    echo "</tr>";
                    for($i = 0 ; $i < count($commandes) ; $i++)
                    {
                        $produit = afficher_produit_vendeur($i+1);
                        /** Tableau $produit
                        * ['ID_Commande']
                        * ['ID_Produit']
                        * ['Nom_produit']
                        * ['etat_produit_c']
                        * ['Quantite']
                        * ['Prix_produit_commande_TTC']
                        */
                        

                        for($j = 0 ; $j< count($produit) ; $j++)
                        {
                        echo "<tr id=commande".($i+1)." class='Detailcache'>";
                        echo "<td>".$produit[$j]["ID_Produit"]."</td>";
                        echo "<td>".$produit[$j]["Nom_produit"]."</td>";
                        if($produit[$j]["etat_produit_c"] == "finie"){
                            echo "<td><div class='Etats-".$produit[$j]['etat_produit_c']."'>Terminée</div></td>";
                        }
                        elseif($produit[$j]["etat_produit_c"] == "annulee"){
                            echo "<td><div class='Etats-".$produit[$j]['etat_produit_c']."'>Annulée</div></td>";
                        }
                        elseif($produit[$j]["etat_produit_c"] == "acceptee"){
                            echo "<td><div class='Etats-".$produit[$j]['etat_produit_c']."'>Acceptée</div></td>";
                        }
                        elseif($produit[$j]["etat_produit_c"] == "en-charge"){
                            echo "<td><div class='Etats-".$produit[$j]['etat_produit_c']."'>En charge</div></td>";
                        }
                        elseif($produit[$j]["etat_produit_c"] == "en-cours"){
                            echo "<td><div class='Etats-".$produit[$j]['etat_produit_c']."'>En cours</div></td>";
                        }
                        echo "<td>".$produit[$j]["Quantite"]."</td>";
                        echo "<td class='prixtab'>".$produit[$j]["Prix_produit_commande_TTC"]."€"."</td>";
                        echo "<td>";
                        if ($produit[$j]["etat_produit_c"] != "finie" && $produit[$j]["etat_produit_c"] != "annulee"){
                            echo "<button class='changer-etat-button' onclick='changer_etat(".$i+1 .", ".$produit[$j]["ID_Produit"].")' >Changer état</button>";
                        }
                        echo "</td>";
                        echo "</tr>";
                        }
            
                    }
            ?>
            </table>

        </section>

        <?php if (isset($_GET["import"])) : ?>
            
            <div class="fond_opacity fond_opacity--affiche"></div>

            <?php if($_GET["import"] == "validated") : ?>

                <div class="alert-import-csv -affiche">
                    <i class="fa-solid fa-xmark"></i>
                    <div>
                        <i class="fa-solid fa-circle-check"></i>
                        <h3>Votre catalogue a été importé avec succès !</h3>
                    </div>

                    <button class="btn-produits" onclick="afficherProduits()">Voir les produits ajoutés</button>

                    <table id="myTable produits">
                        <tr class="en-tete">
                            <th>
                                <a  onclick="SortNameProduits()"  href="javascript:void(0);" class="nom_produit">Nom</a>
                                <i class="fa-solid fa-sort"></i>
                            </th>
                            <th>
                                <a  onclick="SortPrice(2)" href="javascript:void(0);" class="prixHT_produit">Prix HT</a>
                                <i class="fa-solid fa-sort"></i>
                            </th>
                            <th>
                                <a  onclick="SortPrice(3)" href="javascript:void(0);" class="prixTTC_produit">Prix TTC</a>
                                <i class="fa-solid fa-sort"></i>
                            </th>
                            <th>
                                <a onclick="SortPrice(4)" href="javascript:void(0);" class="stock_produit">Stock</a>
                                <i class="fa-solid fa-sort"></i>
                            </th>

                        </tr>
                        
                        <?php 
                            foreach($_SESSION["produits_importes"] as $produit){
                                
                                echo "<tr>";
                                echo "<td>" .  $produit[0] ."</a> </td>";
                                echo "<td>" . "<p>" . $produit[1] . "</p>" . " €" . "</td>";
                                echo "<td>" . "<p>" . $produit[2] . "</p>" ." €" . "</td>";
                                echo "<td>" . "<p>" . $produit[4] . "</p>" . "</td>";
                                echo "</tr>";
                            }
                        ?>
                        </table>
                </div>
                <?php elseif($_GET["import"] == "unsupportedcsv") : ?>

                    <div class="alert-import-csv -affiche">
                        <i class="fa-solid fa-xmark"></i>
                        <div>
                            <i class="fa-solid fa-circle-xmark"></i>
                            <h3>Ce fichier csv n'est pas supporté, veuillez utiliser le fichier csv type ci-dessous pour importer un catalogue.</h3>
                        </div>

                        <a href="../csv/import.csv" class="downloadcsv">Télécharger un modèle</a>
                    </div>
                
                <?php elseif($_GET["import"] == "unsupportedfile") : ?>

                    <div class="alert-import-csv -affiche">
                        <i class="fa-solid fa-xmark"></i>
                        <div>
                            <i class="fa-solid fa-circle-xmark"></i>
                            <h3>L'import a échoué, veuillez utiliser un fichier de type csv pour importer un catalogue.</h3>
                        </div>
                    </div>

                <?php endif ; ?>
        
        <?php endif ; ?>

        <section class="catalogue cache">
            <h1>Importer un catalogue</h1>
            <hr>
            <div class="block-ajoutCatalogue">
                <h3>Déposez un fichier csv</h3>
                <div id="drop-area">
                    <form enctype="multipart/form-data" action="insert_catalogue.php" method="post">
                        <div class="input-row">
                            <input class="importCSV" type="file" name="file" id="file" accept=".csv" pattern="\.csv" onchange="handleFiles(this.files)">
                            <div id="gallery"></div>
                            <button type="submit" id="submit" name="import">Importer</button>
                        </div>
                    </form>
                </div>
            </div>
            <h1>Liste des produits</h1>
            <hr>

            <table id="myTable produits">
                <tr class="en-tete">
                    <th>
                        <a  onclick="SortTableProduitsTri()" href="javascript:void(0);" class="id_produit">N°</a>
                        <i class="fa-solid fa-sort"></i>
                    </th>
                    <th>
                        <a  onclick="SortNameProduits()"  href="javascript:void(0);" class="nom_produit">Nom</a>
                        <i class="fa-solid fa-sort"></i>
                    </th>
                    <th>
                        <a  onclick="SortPrice(2)" href="javascript:void(0);" class="prixHT_produit">Prix HT</a>
                        <i class="fa-solid fa-sort"></i>
                    </th>
                    <th>
                        <a  onclick="SortPrice(3)" href="javascript:void(0);" class="prixTTC_produit">Prix TTC</a>
                        <i class="fa-solid fa-sort"></i>
                    </th>
                    <th>
                        <a onclick="SortPrice(4)" href="javascript:void(0);" class="stock_produit">Stock</a>
                        <i class="fa-solid fa-sort"></i>
                    </th>
                    <th>
                        <a  onclick="SortPrice(5)" href="javascript:void(0);" class="avis_produit">Avis (Sur 5)</a>
                    </th>
                </tr>
                
                <?php 
                    $index = 1;
                    foreach($produits as $produit){
                        
                        echo "<tr>";
                        if ($index< 10){
                            echo "<td>0" . $index . "</td>";
                        }
                        else
                        {
                            echo "<td>" . $index . "</td>";
                        }
                        echo "<td>" . "<a href =produit.php?idProduit=" . $produit['ID_Produit']. ">" . $produit["Nom_produit"] ."</a> </td>";
                        echo "<td>" . "<p>" . $produit["Prix_vente_HT"] . "</p>" . " €" . "</td>";
                        echo "<td>" . "<p>" . $produit["Prix_vente_TTC"] . "</p>" ." €" . "</td>";
                        echo "<td>" . "<p>" . $produit["Quantite_disponnible"] . "</p>" . "</td>";
                        if ($produit["Moyenne_Note_Produit"] == ''){
                            echo "<td> Aucun Avis </td>";
                        }else{
                            echo "<td>" . $produit["Moyenne_Note_Produit"] . "</td>";
                        }

                        echo "</tr>";
                        $index = $index+1;
                    }
                ?>
            </table>                

        </section>

        <section class="compte cache">
                
            <h1>Mon compte vendeur</h1>
            <hr>
            <div class="bloc_infos_persos_vendeur">
                        <h2>Informations de l'entreprise</h2>
                        <div class="coordonnees">

                            <form class="form_infos_compte" method="post" action="vendeur.php">

                                <div class="formulaire">

                                    <div class="champ_infos_persos un">
                                        <label for="fname">Nom de l'entreprise<i class="fa-solid fa-lock" title="Impossible de modifier le nom de l'entreprise"></i></label>
                                        <input id="fname" type="text" name="nom" readonly="readonly" title="Impossible de modifier le nom de l'entreprise" value="<?php echo $donneesVendeur[0]["Nom_vendeur"]; ?>">
                                    </div>

                                    <div class="adresse">
                                        <h3>Adresse</h3>
                                        <div class="blocs">
                                            <div class="champ_infos_persos">
                                                <label for="fname">Nom de rue<i class="fa-solid fa-lock" title="Impossible de modifier le nom de rue"></i></label>
                                                <input type="text" name="rue" readonly="readonly" title="Impossible de modifier le nom de rue" value="<?php echo $donneesVendeur[0]["nom_de_rue"]; ?>">
                                            </div>

                                            <div class="champ_infos_persos">
                                                <label for="fname">Ville<i class="fa-solid fa-lock" title="Impossible de modifier la ville"></i></label>
                                                <input type="text" name="ville" readonly="readonly" title="Impossible de modifier la ville" value="<?php echo $donneesVendeur[0]["ville"]; ?>">
                                            </div>

                                            <div class="champ_infos_persos">
                                                <label for="fname">Code Postal <i class="fa-solid fa-lock" title="Impossible de modifier le code postal l'entreprise"></i></label>
                                                <input type="text" name="code_postal" readonly="readonly" title="Impossible de modifier le code postal" value="<?php echo $donneesVendeur[0]["code_postale"]; ?>">
                                            </div>
                                        </div>

                                    </div>
                                
                                    <div class="blocs">
                                        <div class="champ_infos_persos">
                                            <label for="fname">Email de l'entreprise<i class="fa-solid fa-lock" title="Impossible de modifier l'adresse mail"></i></label>
                                            <input type="email" name="email" readonly="readonly" title="Impossible de modifier l'adresse mail" value="<?php echo $donneesVendeur[0]["Email"]; ?>">
                                        </div>

                                        <div class="champ_infos_persos">
                                            <label for="fname">TVA de l'entreprise <i class="fa-solid fa-lock" title="Impossible de modifier la TVA de l'entreprise"></i></label>
                                            <input type="text" name="tva" readonly="readonly" title="Impossible de modifier la TVA de l'entreprise" value="<?php echo $donneesVendeur[0]["TVA"]; ?>">
                                        </div>

                                        <div class="champ_infos_persos">
                                            <label for="fname">Siret de l'entreprise <i class="fa-solid fa-lock" title="Impossible de modifier le SIRET de l'entreprise"></i></label>
                                            <input type="text" name="Siret" readonly="readonly" title="Impossible de modifier le SIRET de l'entreprise" value="<?php echo $donneesVendeur[0]["Siret"]; ?>">
                                        </div>
                                    </div>

                                    <div class="champ_infos_persos2">
                                        <label for="fname">Présentation</label>
                                        <textarea name="presentation" cols="110" rows="6" required><?php echo $donneesVendeur[0]["texte_Presentation"]; ?></textarea>
                                    </div>

                                    <div class="champ_infos_persos2">
                                        <label for="fname">Note</label>
                                        <textarea name="note" cols="110" rows="6" required><?php echo $donneesVendeur[0]["note"]; ?></textarea>
                                    </div>
                                </div>

                                <input class="confirmer" type="submit" name="coordonnees" value="Confirmer">

                            </form>

                        </div>
                </div>

                <div class="bloc_mdp_vendeur">

                    <section>

                        <h2>Modifier mon mot de passe </h2>

                        <div class="motDePasse">

                            <form class="form_mdp_compte" method="post" action="vendeur.php#Mon_Compte">

                                <div class="formulaire">

                                    <div class="champ_mdp_compte">
                                        <label for="fname">Ancien mot de passe</label>
                                        <div class="mdp">
                                            <input type="password" id="pass1" name="AncienMdp" required>
                                        </div>
                                    </div>

                                    <div class="champ_mdp_compte">
                                        <label for="fname">Nouveau mot de passe</label>
                                        <div class="mdp">
                                            <input type="password" id="pass2" name="mdp" minlength="8" required>
                                        </div>
                                    </div>

                                    <div class="champ_mdp_compte">
                                        <label for="fname">Confirmation mot de passe</label>
                                        <div class = "mdp">
                                            <input type="password" id="pass3" name="ConfirmerMdp" minlength="8" required>
                                        </div>
                                    </div>

                                    <?php if(isset($_POST["VerifConfirmationMdp"])){echo "<p>Mot de Passe incohérent</p>";} ?>
                                    <?php if(isset($_POST["VerifAncienMdp"])){echo "<p>Ancien mot de passe incorrect</p>";} ?>

                                </div>

                                <div>
                                    <input type="submit" name="ConfirmationMdp" class="confirmer" value="Confirmer">
                                </div>

                                <?php
                                if(isset($erreurmdp)) {

                                    if($erreurmdp === "mdpdifferents"){

                                        echo '<div class="erreur_co">';
                                        echo '<p class="texte_erreur_co">Les mots de passes renseignées sont différents.</p>';
                                        echo '</div>';

                                    } else if ($erreurmdp === "ancienmdperreur"){

                                        echo '<div class="erreur_co">';
                                        echo '<p class="texte_erreur_co">L\' ancien mot de passe renseigné n\'est pas valide.</p>';
                                        echo '</div>';

                                    }

                                }

                                if(isset($update)){

                                    if($update){

                                        echo '<div class="erreur_co switch">';
                                        echo '<p class="texte_erreur_co">Le mot de passe a été modifié avec succès.</p>';
                                        echo '</div>';
                                    }
                                }
                                ?>
                            </form>

                        </div>

                    </section>

                    </div>

                    </div>
        </section>

        <section class="exporter_catalogue cache">
            <h1>Exporter mon catalogue</h1>
            <hr>

            <table id="myTable produits">
                <tr class="en-tete">
                    <th>
                        <p>Exporter un produit</p>
                    </th>
                    <th>
                        <a  onclick="SortTableProduitsTri()" href="javascript:void(0);" class="id_produit">N°</a>
                        <i class="fa-solid fa-sort"></i>
                    </th>
                    <th>
                        <a  onclick="SortNameProduits()"  href="javascript:void(0);" class="nom_produit">Nom</a>
                        <i class="fa-solid fa-sort"></i>
                    </th>
                    <th>
                        <a  onclick="SortPrice(2)" href="javascript:void(0);" class="prixHT_produit">Prix HT</a>
                        <i class="fa-solid fa-sort"></i>
                    </th>
                    <th>
                        <a  onclick="SortPrice(3)" href="javascript:void(0);" class="prixTTC_produit">Prix TTC</a>
                        <i class="fa-solid fa-sort"></i>
                    </th>
                    <th>
                        <a onclick="SortPrice(4)" href="javascript:void(0);" class="stock_produit">Stock</a>
                        <i class="fa-solid fa-sort"></i>
                    </th>
                    <th>
                        <a  onclick="SortPrice(5)" href="javascript:void(0);" class="avis_produit">Avis (Sur 5)</a>
                    </th>
                </tr>
                <form action="vendeur.php#exporter-catalogue" method="post">
                    <?php 
                        // $index = 1;
                        foreach($produits as $produit){
                            
                            echo "<tr>";

                            echo "<td class='check'><input type='checkbox'   class='checkbox' name=" . $produit['ID_Produit'] . "></td>";
                            if ($index< 10){
                                echo "<td>0" . $index . "</td>";
                            }
                            else
                            {
                                echo "<td>" . $index . "</td>";
                            }
                            echo "<td>" . "<a href =produit.php?idProduit=" . $produit['ID_Produit']. ">" . $produit["Nom_produit"] ."</a> </td>";
                            echo "<td>" . "<p>" . $produit["Prix_vente_HT"] . "</p>" . " €" . "</td>";
                            echo "<td>" . "<p>" . $produit["Prix_vente_TTC"] . "</p>" ." €" . "</td>";
                            echo "<td>" . "<p>" . $produit["Quantite_disponnible"] . "</p>" . "</td>";
                            if ($produit["Moyenne_Note_Produit"] == ''){
                                echo "<td> Aucun Avis </td>";
                            }else{
                                echo "<td>" . $produit["Moyenne_Note_Produit"] . "</td>";
                            }

                            echo "</tr>";
                            $index = $index+1;
                        }

                    ?>

                    <input type="submit" value="Exporter le catalogue" class="confirmer" disabled>
                </form>
            </table>

            <?php 
                    if (isset($_POST) && count($_POST) > 0){

                        //print_r($_POST);
                        $tab_produits_a_exporter = ["nomVendeur" => $donneesVendeur[0]["Nom_vendeur"], "raisonSociale" => $donneesVendeur[0]["Raison_sociale"], "siretVendeur" => $donneesVendeur[0]["Siret"], "lien_logo" => "img/vendeur/" . $donneesVendeur[0]["ID_Vendeur"] . "/img1.webp",  "emailVendeur" =>  $donneesVendeur[0]["Email"], "TVA_Vendeur" => $donneesVendeur[0]["TVA"], "nom_de_rue" => $donneesVendeur[0]["nom_de_rue"], "ville" => $donneesVendeur[0]["ville"], "code_postal" => $donneesVendeur[0]["code_postale"], "presentationVendeur" => $donneesVendeur[0]["texte_Presentation"], "noteVendeur" => $donneesVendeur[0]["note"]];
                        $produits_exportes = [];
                        $catalogue = [];
                        
                        foreach($produits as $produit){

                            foreach(array_keys($_POST) as $idP){

                                if($produit["ID_Produit"] == $idP){

                                    $produits =  array("Nom_produit" => $produit["Nom_produit"], "Prix_vente_HT" => $produit["Prix_vente_HT"], "Prix_vente_TTC" => $produit["Prix_vente_TTC"], "Description_produit" => $produit["Description_produit"], "lien_image" => "img/catalogue/Produits/".$produit['ID_Produit']."/img1.jpg");
                                    array_push($produits_exportes, $produits);
                                }
                            }
                        }
                        //print_r($produits_exportes);
                        array_push($catalogue, array("vendeur" => $tab_produits_a_exporter, "produits" => $produits_exportes));

                        $catalogueV = json_encode($catalogue, JSON_UNESCAPED_UNICODE);

                        file_put_contents("../catalogue/mono.json", $catalogueV);
                        // recupérér les infos des pays avec une fonction php 

                        echo '<div class="erreur_co switch">';
                        echo '<p class="texte_erreur_co">Le catalogue a été exporté avec succès.</p>';
                        echo '</div>';
                    }

            ?>
        </section>
        <?php
         if(isset($_POST["nomProduit"])) {
            inserer_produit(array($_POST["nomProduit"],$_POST["prixCT"] ,$_POST["prixHT"], $_POST["quantite"], $_POST["description"],null,null,null, getID_Souscategorie($_POST["souscategorie"]),$_SESSION["vendeur"][1]));
         }
         unset($_POST);
           ?>
         <section class="creeProduit cache">
            <h1>Ajout d'un nouveau produit</h1>
            <hr>
            <form action="vendeur.php" method="POST">
               <article class="nomenclatureProduit">
                  <h2>Donnée produit -</h2>
                  <input type="text" id="nomProduit" name="nomProduit" placeholder="Nom du Produit">
               </article>
               <hr>
               <section class="debcritionProduit">
                  <textarea id="myTextarea" name="description" placeholder="Description..."></textarea>
               </section>
               <section class="Categorie">
                  <input type="text" id="categorie" name="categorie" placeholder="Categorie">
                  <datalist id="categorie-list">     
                     <?php
                        foreach ($Nos_Categorie as $Categorie){
                           echo "<option value='".str_replace(" ","_",$Categorie)."'>";
                        }
                        ?>
                  </datalist>
                  <?php
                     ?>
                  <input type="text" id="souscategorie" name="souscategorie" placeholder="Sous-Categorie">
                  <datalist id="souscategorie-list" data-parent="categorie" autocomplete = "off">
                     <?php
                        foreach ($Nos_SousCategories as $key => $SousCategorie){
                           echo "<option class='".str_replace(" ","_",$SousCategorie)."' value='".$key."'>";
                        }
                        ?>  
                  </datalist>
               </section>
               <section class="recette">
                  <input type="number" name="quantite" min="1" max="999" placeholder="Quantiter">
                  <input type="number" name="prixHT" min="0.01" max="999999.99" step=0.01 placeholder="Prix HT" id="prixHTInput">
                  <input type="number" name="prixCT" min="0.1" max="999999.99" step=0.01 placeholder="Prix Coutant">
               </section>
               <hr>
               <section class="imageProduit">
                  <label for="file-input" id="drop-zone" class="drop-zone">
                     <input type="file" id="file-input" multiple accept="image/*">
                     <div id="drop-label">Cliquez ici pour ajouter des images ou faites-les glisser dans cette zone.</div>
                     <div id="image-preview"></div>
                  </label>
               </section>
               <button type="submit" name="submit">Envoyer</button>
            </form>
         </section>  
    </main>
</body>
<script>
      // Initialiser TinyMCE pour l'élément textarea
      tinymce.init({
        selector: "#myTextarea",
        plugins: "lists",
        toolbar: "bold italic  bullist numlist",
        branding: false,
        menubar: false,
        elementpath:false
      });
</script>
<script src="../js/filtrerCommandes.js"></script>


<script src="https://momentjs.com/downloads/moment.min.js"></script>
<script src="../js/filtrerCommandes.js"></script>
<script src="../js/feedback_catalogue.js"></script>
<script>
    function changer_etat(idCom, idProd) {
        document.location.href=("vendeur.php?idCom="+idCom+"&idProd="+idProd);
    }
</script>


</html>

