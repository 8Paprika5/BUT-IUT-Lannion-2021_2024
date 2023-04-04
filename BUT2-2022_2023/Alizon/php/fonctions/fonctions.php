<?php 
    include('databaseConnexion.php');
    
    /* ######################################## GESTION DES COMMENTAIRES ######################################## */
    function avis_produit($id_produit){
        global $schema;
        global $dbh;

        $sth = $dbh -> prepare("SELECT ID_Commentaire, _Avis.ID_Client, nom_client, prenom_client, ID_Produit, Note_Produit, Commentaire, Image_Avis FROM $schema._Avis INNER JOIN $schema._Client on _Avis.ID_Client = _Client.ID_Client WHERE ID_Produit = ?");
        $sth->execute(array($id_produit));
        
        return $sth->fetchAll();
    }

    function reponses_avis($id_commentaire){
        global $schema;
        global $dbh;

        $sth = $dbh -> prepare("SELECT Commentaire FROM $schema._Reponse WHERE ID_Commentaire = ?");
        $sth->execute(array($id_commentaire));
        
        return $sth->fetchAll();
    }

    function verif_produit_commandé($id_client,$id_produit){
        global $schema;
        global $dbh;

        $sth = $dbh -> prepare("SELECT _Commande.ID_Commande FROM $schema._Commande inner join $schema._Client on _Commande.ID_Client = _Client.ID_Client inner join $schema._contient_produit_c on _Commande.ID_Commande = _contient_produit_c.ID_Commande WHERE _Client.ID_Client = ? and ID_Produit = ?");
        $sth->execute(array($id_client,$id_produit));
        
        return $sth->fetchAll();
    }

    function ajoute_reponse($id_commentaire, $commentaire){
        global $schema;
        global $dbh;

        $sth = $dbh -> prepare("INSERT INTO $schema._Reponse (ID_Commentaire,Commentaire) VALUES (?,?);");

        $sth->execute(array($id_commentaire,$commentaire)); 
    }

    function verif_reponses_commentaires($id_commentaire){
        global $schema;
        global $dbh;

        $sth = $dbh -> prepare("SELECT _Reponse.ID_Commentaire FROM $schema._Reponse WHERE ID_Commentaire = ?");

        $sth->execute(array($id_commentaire)); 

        return $sth->fetchAll();
    }

    function nom_client_produit($id_client){
        global $schema;
        global $dbh;

        $sth = $dbh -> prepare("SELECT nom_client FROM $schema._Client NATURAL JOIN $schema._Avis WHERE id_client = ?");
        $sth->execute(array($id_client));
        $infosClient = $sth->fetchAll();

        return $infosClient;
    }

    function infos_cli($id_client){
        global $schema;
        global $dbh;

        $sth = $dbh -> prepare("SELECT * FROM $schema._Client WHERE id_client = ?");
        $sth->execute(array($id_client));
        $infosClient = $sth->fetchAll();

        return $infosClient[0];
    }

    function signaleurComment($id_client,$id_commentaire){
        global $schema;
        global $dbh;

        $sth = $dbh -> prepare("SELECT  * FROM $schema._signaler WHERE ID_Signaleur = ? and ID_Commentaire = ?");
        $sth->execute(array($id_client,$id_commentaire));
        $infosClient = $sth->fetchAll();
        return $infosClient;
    }

    function getID_Commentaire($id_produit,$id_posteur){
        global $schema;
        global $dbh;

        $sth = $dbh -> prepare("SELECT ID_Commentaire FROM $schema._avis WHERE ID_Produit = ? and ID_Client= ?");
        $sth->execute(array($id_produit,$id_posteur));
        $infoID_Commentaire = $sth->fetchAll();
        return $infoID_Commentaire;


    }

    function getID_Souscategorie($nomsouscate){
        global $schema;
        global $dbh;

        $sth = $dbh -> prepare("SELECT Id_Sous_Categorie FROM $schema._sous_categorie WHERE nom = ?");
        $sth->execute(array($nomsouscate));
        $infoID_Souscategorie = $sth->fetchAll();
        return $infoID_Souscategorie[0]['Id_Sous_Categorie'];


    }

    function ajouteSignalement($id_client,$id_commentaire){
        global $schema;
        global $dbh;

        
        $sth = $dbh -> prepare("INSERT INTO $schema._signaler (ID_Signaleur,ID_Commentaire) VALUES (?,?);");

        $sth->execute(array($id_client,$id_commentaire)); 

    }

    function verif_commentaires($id_client,$id_produit){
        global $schema;
        global $dbh;

        $sth = $dbh -> prepare("SELECT ID_Commentaire FROM $schema._Avis WHERE ID_Client = ? and ID_Produit = ?");

        $sth->execute(array($id_client,$id_produit)); 

        return $sth->fetchAll();
    }

    function ajout_comm($id_client, $id_produit, $note, $commentaire){
        global $schema;
        global $dbh;

        
        $sth = $dbh -> prepare("INSERT INTO $schema._Avis (ID_Client, ID_Produit, Note_Produit, Commentaire) VALUES (?,?,?,?);");

        $sth->execute(array($id_client, $id_produit, $note, $commentaire)); 
    }

    /* ######################################## GESTION DES IMAGES ######################################## */
    function CreationSousCategories(){
        global $schema;
        global $dbh;
        
        $sth = $dbh -> prepare("SELECT id_produit, nom_produit, nom_categorie as categorieSup, _sous_categorie.nom as Souscategorie FROM $schema._categorie 
        INNER JOIN $schema._sous_categorie ON _sous_categorie.id_categorie_sup = _categorie.id_categorie
        INNER JOIN $schema._produit ON _produit.id_sous_categorie = _sous_categorie.id_sous_categorie");
        $sth->execute();
        $listeproduits = $sth->fetchAll();

        $sth = $dbh -> prepare("SELECT nom_categorie as categorieSup, _sous_categorie.nom as Souscategorie FROM $schema._categorie INNER JOIN $schema._sous_categorie ON _sous_categorie.id_categorie_sup = _categorie.id_categorie");
        $sth->execute();
        $listeCategories = $sth->fetchAll();
        


        // Remplacement des espaces par des '_' afin de limiter la casse lors de la création de dossier et fichiers
        for($i = 0 ; $i < sizeof($listeCategories); $i++){
            $listeCategories[$i]['categorieSup'] = str_replace(' ', "_", $listeCategories[$i]['categorieSup']);
            $listeCategories[$i]['Souscategorie'] = str_replace(' ', "_", $listeCategories[$i]['Souscategorie']);
            
        }

        for($i = 0 ; $i < sizeof($listeproduits); $i++){
            
            $listeproduits[$i]['categorieSup'] = str_replace(' ', "_", $listeproduits[$i]['categorieSup']);
            $listeproduits[$i]['Souscategorie'] = str_replace(' ', "_", $listeproduits[$i]['Souscategorie']);
            $listeproduits[$i]['nom_produit'] = str_replace(' ', "_", $listeproduits[$i]['nom_produit']);
        }

        /*echo '<pre>';
        print_r($listeCategories);
        echo '</pre>';*/
        
        // le chemin du dossier à créer
        $url = "../img/catalogue/";

        foreach($listeCategories as $categorie){

            // Creation d'un dossier par catégorie
            if(!file_exists($url.$categorie['categorieSup'])){
                mkdir($url.$categorie['categorieSup']);
            }

            // Creation d'un dossier par sous-catégorie
            if(!file_exists($url.$categorie['categorieSup']."/".$categorie['Souscategorie'])){
                mkdir($url.$categorie['categorieSup']."/".$categorie['Souscategorie']);
            }
        }
        
        foreach($listeproduits as $produits){
            // Creation d'un dossier par produits
            if(!file_exists($url.$produits['categorieSup']."/".$produits['Souscategorie']."/".$produits['id_produit'])){
                mkdir($url.$produits['categorieSup']."/".$produits['Souscategorie']."/".$produits['id_produit']);
               
            }

            // Creation d'un dossier commentaire par produits
            if(!file_exists($url.$produits['categorieSup']."/".$produits['Souscategorie']."/".$produits['id_produit']."/Commentaires")){
                mkdir($url.$produits['categorieSup']."/".$produits['Souscategorie']."/".$produits['id_produit']."/Commentaires");
            }
        }
    }

    function liste_Categories(){
        global $schema;
        global $dbh;

        $sth = $dbh -> prepare("SELECT nom_categorie, nom as nom_Souscategorie FROM $schema._categorie INNER JOIN $schema._sous_categorie ON id_categorie_sup = id_categorie");
        $sth->execute();
        $res = $sth->fetchAll();
        $liste_SousCategories = array();
        foreach($res as $categorie){
            $liste_SousCategories[$categorie['nom_Souscategorie']] = $categorie['nom_categorie'];
        }

        $sth = $dbh -> prepare("SELECT nom_categorie FROM $schema._categorie");
        $sth->execute();
        $res = $sth->fetchAll();
        $liste_Categories = array();
        foreach($res as $categorie){
            array_push($liste_Categories,$categorie['nom_categorie']);
        }
        return array($liste_SousCategories,$liste_Categories);
    }

    function NosSousCategories(){
        global $schema;
        global $dbh;

        $sth = $dbh -> prepare("SELECT nom_categorie, nom as nom_Souscategorie FROM $schema._categorie INNER JOIN $schema._sous_categorie ON id_categorie_sup = id_categorie");
        $sth->execute();
        $res = $sth->fetchAll();
        $liste_SousCategories = array();
        foreach($res as $categorie){
            $liste_SousCategories[$categorie['nom_Souscategorie']] = $categorie['nom_categorie'];
        }

        return $liste_SousCategories;
    }

    function NosCategories(){
        global $schema;
        global $dbh;

        $sth = $dbh -> prepare("SELECT nom_categorie FROM $schema._categorie");
        $sth->execute();
        $res = $sth->fetchAll();
        $liste_Categories = array();
        foreach($res as $categorie){
            array_push($liste_Categories,$categorie['nom_categorie']);
        }
        return $liste_Categories;
    }

    /* ######################################## FONCTIONNALITE DU CATALOGUE ######################################## */
    function prix_min(){
        global $schema;
        global $dbh;

        $prix = $dbh->prepare("SELECT min(Prix_vente_TTC) FROM $schema._Produit");
        $prix->execute();

        return $prix->fetch();
    }

    function prix_max(){
        global $schema;
        global $dbh;

        $prix = $dbh->prepare("SELECT max(Prix_vente_TTC) FROM $schema._Produit");
        $prix->execute();

        return $prix->fetch();
    }
    
    function infos_vendeur($id_produit){
        global $schema;
        global $dbh;

        $vendeur = $dbh->prepare("SELECT * FROM $schema._Vendeur inner join $schema._Produit on _Vendeur.ID_Vendeur = _Produit.ID_Vendeur where ID_Produit = ?");
        $vendeur->execute(array($id_produit));

        return $vendeur->fetchAll();
    }

    function liste_Categories_Only(){
        global $schema;
        global $dbh;

        $categorie = $dbh->prepare("SELECT * FROM $schema._Categorie");
        $categorie->execute();

        return $categorie->fetchAll();
    }

    function liste_Sous_Categories_Only(){
        global $schema;
        global $dbh;

        $sous_categorie = $dbh->prepare("SELECT * FROM $schema._Sous_Categorie");
        $sous_categorie->execute();

        return $sous_categorie->fetchAll();
    }

    function inserer_catalogue($donnees_csv){
        global $schema;
        global $dbh;

        $sql = "INSERT INTO $schema._Produit(Nom_produit, Prix_coutant, Prix_vente_HT, Prix_vente_TTC, Quantite_disponnible, Description_produit, images1, images2, images3, Moyenne_Note_Produit, Id_Sous_Categorie, ID_Vendeur) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                
        $sth = $dbh->prepare($sql);
        $sth->execute($donnees_csv);
    }

    function inserer_produit($donnees_form){
        global $schema;
        global $dbh;

        $sql = "INSERT INTO $schema._Produit(Nom_produit, Prix_coutant, Prix_vente_HT, Quantite_disponnible, Description_produit, images1, images2, images3, Id_Sous_Categorie, ID_Vendeur) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                
        $sth = $dbh->prepare($sql);
        $sth->execute($donnees_form);
    }

    function verifier_entete_csv($ligne_csv){

        $verif = false;

        if($ligne_csv[0] == "Nom_produit" && $ligne_csv[1] == "Prix_coutant" && $ligne_csv[2] == "Prix_vente_HT" && $ligne_csv[3] == "Prix_vente_TTC"){

            $verif = true;
        }
        return $verif;
    }

    function donnees_catalogue(){
        global $schema;
        global $dbh;

        $catalogue = $dbh->prepare("SELECT * FROM $schema.catalogue inner join $schema._Vendeur on _Vendeur.ID_Vendeur = catalogue.ID_Vendeur");
        $catalogue->execute();
        $catalogue = $catalogue->fetchAll();
        return $catalogue;
    }

    function catalogue_donnees_vendeur() {
        global $schema;
        global $dbh;

        $catalogue = $dbh->prepare("SELECT id_produit, Nom_vendeur FROM $schema._Vendeur inner join $schema.catalogue on _Vendeur.ID_Vendeur = catalogue.ID_Vendeur");
        $catalogue->execute();
        $catalogue = $catalogue->fetchAll();
        return $catalogue;
    }
    
    function listeProduits_plus_recherchees(){
        global $schema;
        global $dbh;

        $catalogue = $dbh->prepare("SELECT * FROM $schema.catalogue LIMIT 10");
        $catalogue->execute();
        $catalogue = $catalogue->fetchAll();
        return $catalogue;
    }

    function nbr_produits() {
        global $schema;
        global $dbh;

        $sth = $dbh->prepare("SELECT Count(*) FROM $schema.catalogue");
        $sth->execute();
        $res = $sth->fetchAll();
        return $res;
    }

    function donnees_catalogue_mot_cles($mots) {
        global $schema;
        global $dbh;

        $catalogue_produits = $dbh->prepare("SELECT * FROM $schema.catalogue inner join $schema._Vendeur on _Vendeur.ID_Vendeur = catalogue.ID_Vendeur WHERE nom_produit LIKE '%$mots%' OR description_produit LIKE '$mots%' OR nom_souscategorie LIKE '$mots%' OR nom_categorie LIKE '$mots%'");
        $catalogue_produits->execute();
        $catalogue_produits = $catalogue_produits->fetchAll();
        return $catalogue_produits;
    }

    function infos_produits_paiement($id_panier){
        global $schema;
        global $dbh;

        $stmt = $dbh->prepare("SELECT * FROM $schema._contient_produit_p NATURAL JOIN $schema._produit  WHERE id_panier = ?");
        $stmt->execute(array($id_panier));
        $result = $stmt->fetchAll();
    
        $sth = $dbh->prepare("SELECT * FROM $schema._panier WHERE id_panier = ?");
        $sth->execute(array($id_panier));
        $RecapPanier = $sth->fetchAll()[0];
        $prixTotal = $RecapPanier['prix_total_ttc'];
        return array($RecapPanier, $prixTotal);
    }

    function infos_produit($id_produit){
        global $schema;
        global $dbh;

        $sth = $dbh->prepare("SELECT * FROM $schema.catalogue WHERE id_produit = ?");
        $sth->execute(array($id_produit));
        $res = $sth->fetchAll();
        return $res;
    }

    
    function filtre_prix(){
        global $schema;
        global $dbh;
        
        $sth = $dbh->prepare("SELECT * FROM $schema.catalogue");
        $sth->execute();
        $res = $sth->fetchAll();
        return $res;
    }

    /* ######################################## FONCTIONNALITE DU PANIER ######################################## */
    function ViderPanier(){
        global $schema;
        global $dbh;

        $videPannier = $dbh->prepare("DELETE FROM $schema._Contient_Produit_p WHERE ID_Panier = ?");
        $videPannier->execute(array($_COOKIE['id_panier']));
        header("Location: ./panier.php");
        exit();
    }

    function supprimerProduit($id_produit){
        global $schema;
        global $dbh;

        $supprimerProduit = $dbh->prepare("DELETE FROM $schema._Contient_Produit_p WHERE ID_Panier = ? and ID_Produit  = ?");
        $supprimerProduit->execute(array($_COOKIE['id_panier'],$id_produit));
        header("Location: ./panier.php?produitretiré");
        exit();
    }

    function modifierQuantite($quantiteSelect,$id_produit){
        global $schema;
        global $dbh;
        

        $modifQuantite = $dbh->prepare("UPDATE $schema._Contient_Produit_p set Quantite = ? where ID_Produit  = ? AND ID_Panier = ?");

        if ($quantiteSelect>99) {
            print "Erreur !: Le produit a atteint la quantiter max <br/>";

        }elseif ($quantiteSelect<1) {
            supprimerProduit($id_produit);
        }
        else {
            $upProduit = $dbh->prepare("UPDATE $schema._Contient_Produit_p set Quantite = ? where ID_Produit  = ? AND ID_Panier = ?");
            $upProduit->execute(array($quantiteSelect,$id_produit,$_COOKIE['id_panier']));
            header("Location: ./panier.php");
            exit();
        }
    }

    function infos_panier($id_panier){
        global $schema;
        global $dbh;

        $sth = $dbh->prepare("SELECT * FROM $schema.panier WHERE id_panier = ?");
        $sth->execute(array($id_panier));
        $listeArticles = $sth->fetchAll(); //tableau de '$nbproduit' produits contenus dans le panier
        
        $nbproduit = sizeof($listeArticles);
        
        $sth = $dbh->prepare("SELECT * FROM $schema._panier WHERE id_panier =  ?");
        $sth->execute(array($id_panier));

        $sth2 = $dbh->prepare("SELECT * FROM $schema._panier WHERE id_panier =  ?");
        $sth2->execute(array($id_panier));
        $listeArticles2 = $sth2->fetchAll(); //tableau de '$nbproduit' produits contenus dans le panier

        $prix_ht = null;
        if($nbproduit != 0) {
            $prix_ht = $listeArticles2[0]['Prix_total_HT'];
        } else {
            $prix_ht = 0;
        }
        

        if($nbproduit >0){
            $RecapPanier = $sth->fetchAll()[0];
            $prixTotal = $RecapPanier['Prix_total_TTC']; // prix total déjà dans recapPanier
            return array("listeArticles" => $listeArticles, "nbproduit" => $nbproduit, "prixTotal" => $prixTotal, "prix_ht" => $prix_ht); 
        }else{
            return array("listeArticles" => $listeArticles, "nbproduit" => $nbproduit, "prixTotal" => 0, "prix_ht" => $prix_ht); 
        }
    }

    function ajoutPanier($id_produit, $nbProduits){
        global $schema;
        global $dbh;
       
        $cttc = $dbh->prepare("SELECT Prix_vente_TTC FROM $schema._Produit  where ID_Produit = ?");
        $ttc = $cttc->execute(array($id_produit));

        $cht = $dbh->prepare("SELECT Prix_vente_HT FROM $schema._Produit  where ID_Produit  = ?");
        $ht = $cht->execute(array($id_produit));

        $cverif = $dbh->prepare("SELECT COUNT(*) FROM $schema._Contient_Produit_p where ID_Produit  = ? AND ID_Panier = ?");
        $cverif->execute(array($id_produit,$_COOKIE['id_panier']));
        $verif = $cverif->fetchAll();

        $addProduit = $dbh->prepare("INSERT INTO $schema._Contient_Produit_p(ID_Panier,ID_Produit,Quantite)VALUES(?,?,?);");
        $upProduit = $dbh->prepare("UPDATE $schema._Contient_Produit_p set Quantite = ? where ID_Produit  = ? AND ID_Panier = ?");

        if ($verif[0]["COUNT(*)"]<1) {
            $addProduit->execute(array($_COOKIE['id_panier'],$id_produit,$nbProduits)); 
        }
        else {
            $cquantite = $dbh->prepare("SELECT Quantite FROM $schema._Contient_Produit_p where ID_Produit  = ? AND ID_Panier = ?");
            $quantite = $cquantite->execute(array($id_produit,$_COOKIE['id_panier']));
            if ($quantite + $nbProduits<99) {
                $upProduit->execute(array($quantite+$nbProduits,$id_produit,$_COOKIE['id_panier']));
            }
            else {
                print "Erreur !: Le produit a atteint la quantiter max <br/>";
            }

        }
    }


    /* ######################################## FONCTIONNALITE DU COMPTE CLIENT ######################################## */
    function update_information($id, $prenom, $nom, $date, $adresse_f, $adresse_l) {
        global $schema;
        global $dbh;
        
        //Mettre a jour les information du profil Client
        if(!empty($prenom)) {
            $UpdatePrenom = $dbh->prepare("UPDATE $schema._Client SET prenom_client=? where id_client=?");
            $executePrenom = $UpdatePrenom->execute(array($prenom,$id));
        }

        if(!empty($nom)) {
            $UpdateNom = $dbh->prepare("UPDATE $schema._Client SET nom_client=? where id_client=?");
            $executeNom = $UpdateNom->execute(array($nom,$id));
        }

        if(!empty($date)) {
            $UpdateDate = $dbh->prepare("UPDATE $schema._Client SET date_de_naissance=? where id_client=?");
            $executeDate = $UpdateDate->execute(array($date,$id));
        }

        if(!empty($adresse_f)) {
            $UpdateAdresse = $dbh->prepare("UPDATE $schema._Client SET adresse_facturation=? where id_client=?");
            $executeAdresse = $UpdateAdresse->execute(array($adresse_f,$id));
        }
        if(!empty($adresse_l)) {
            $UpdateAdresse = $dbh->prepare("UPDATE $schema._Client SET adresse_livraison=? where id_client=?");
            $executeAdresse = $UpdateAdresse->execute(array($adresse_l,$id));
        }
    }

    function update_mdp($id,$mdp, $LastMdp) {
        global $schema;
        global $dbh;

        $sth = $dbh->prepare("SELECT mdp FROM $schema._Client WHERE id_client = ?");
        $sth->execute(array($id));
        if($LastMdp == $sth->fetchAll()[0]['mdp']){
            $update = $dbh->prepare("UPDATE $schema._Client SET mdp=? where id_client=?");
            $executeUpdate = $update->execute(array($mdp, $id));
            return True;
        }
        return False;
    }

    function details_commande($id) {
        global $schema;
        global $dbh;
        //afficher la liste des commandes en cours
        $sth = $dbh->prepare("SELECT * from $schema._commande natural join $schema._contient_produit_c natural join $schema._produit where ID_Client = ? "); # and etat_commande = 'Commande en cours de livraison' OR etat_commande = 'Commande prise en charge' OR etat_commande = 'en cours' OR etat_commande = 'Commande livré'
        $sth->execute(array($id));
        $commandeCours = $sth->fetchall();

        $nb = sizeof($commandeCours);

        # tentative bug :

        $sth = $dbh->prepare("SELECT * from $schema._commande where ID_Client = ? ");
        $sth->execute(array($id));
        $temp = $sth->fetchall();

        $commandes = array();

        
        foreach($temp as $com){
            $cours = FALSE;
            $nb_produit_c = 0;
            $com['nb'] = $nb_produit_c;
            $total_prix = 0;
            $com['prix'] = $total_prix;
            foreach ($commandeCours as $produit) { 
                if($com['ID_Commande'] == $produit['ID_Commande']){
                    array_push($com, $produit);
                    $nb_produit_c++;
                    $total_prix+= $produit['Prix_vente_TTC']*$produit['Quantite'];
                    if($produit['etat_produit_c']!='annulee' and $produit['etat_produit_c']!='finie'){
                        $cours = TRUE;
                    }
                }
            }
            $com['nb'] = $nb_produit_c;
            $com['prix'] = $total_prix;
            if($cours){
                array_push($commandes, $com);
            }
        }


        // Commandes archivées

        $sth = $dbh->prepare("SELECT * from $schema._commande natural join $schema._contient_produit_c natural join $schema._produit where ID_Client = ?"); # and etat_commande = 'Commande livré' OR etat_commande = 'Commande archivé'
        $sth->execute(array($id));
        $commande = $sth->fetchall();

        $nb = sizeof($commande);

        # tentative bug :

        $sth = $dbh->prepare("SELECT * from $schema._commande where ID_Client = ? ");
        $sth->execute(array($id));
        $temp = $sth->fetchall();

        $commandesArch = array();

        
        foreach($temp as $com){
            $nb_produit_c = 0;
            $arch = TRUE;
            $com['nb'] = $nb_produit_c;
            $total_prix = 0;
            $com['prix'] = $total_prix;
            foreach ($commande as $produit) { 
                if($com['ID_Commande'] == $produit['ID_Commande']){
                    array_push($com, $produit);
                    $nb_produit_c++;
                    $total_prix+= $produit['Prix_vente_TTC']*$produit['Quantite'];
                    if($produit['etat_produit_c']!='annulee' and $produit['etat_produit_c']!='finie'){
                        $arch = FALSE;
                    }
                }
            }
            $com['nb'] = $nb_produit_c;
            $com['prix'] = $total_prix;
            if($arch){
                array_push($commandesArch, $com);
            }
        }
        
        // Commandes totales

        $sth = $dbh->prepare("SELECT * from $schema._commande natural join $schema._contient_produit_c natural join $schema._produit where ID_Client = ?");
        $sth->execute(array($id));
        $commandeToute = $sth->fetchall();

        $nb = sizeof($commandeToute);

        # tentative bug :

        $sth = $dbh->prepare("SELECT * from $schema._commande where ID_Client = ?");
        $sth->execute(array($id));
        $temp = $sth->fetchall();

        $commandesToute = array();

        
        foreach($temp as $com){
            $nb_produit_c = 0;
            $com['nb'] = $nb_produit_c;
            $total_prix = 0;
            $com['prix'] = $total_prix;
            foreach ($commandeToute as $produit) { 
                if($com['ID_Commande'] == $produit['ID_Commande']){
                    array_push($com, $produit);
                    $nb_produit_c++;
                    $total_prix+= $produit['Prix_vente_TTC']*$produit['Quantite'];
                }
            }
            $com['nb'] = $nb_produit_c;
            $com['prix'] = $total_prix;
            array_push($commandesToute, $com);
        }

        // echo "<pre>";
        // print_r($commandes);
        // echo "</pre>";

        // $commande = $sth->fetchall()[0];

        #$sth = $dbh->prepare("SELECT COUNT(*) from $schema._commande natural join $schema._contient_produit_c where ID_Client = ?");
        #$nbrCommande = $sth->fetchAll();

        return array('listeCommandeTotale'=> $commandesToute, 'listeCommande'=> $commandes, 'listeCommandeArch'=> $commandesArch, 'nbCommandeArch'=>sizeof($commandesArch), 'nbListeCommande' => sizeof($commandes));
    }

    function recupEtatCommande($idCom, $idProd){
        global $schema;
        global $dbh;
        $sth = $dbh->prepare("SELECT etat_produit_c from $schema._contient_produit_c where ID_Commande = $idCom and ID_Produit = $idProd");
        $sth->execute();
        $etat = $sth->fetchall();

        return $etat[0]["etat_produit_c"];
    }

    function recommander($commande){
        header("Location: panier.php");
        // Prend les infos de la commande (produits, quantités, etc.) et les remets dans le panier
        for ($i=0; $i < $commande['nb'] ; $i++) { 
            ajoutPanier($commande[$i]['ID_Produit'], $commande[$i]['Quantite']);
        }
    }
    
    function infos_Client($idClient) {
        global $schema;
        global $dbh;
        
        $sth = $dbh->prepare("SELECT * from $schema._Client INNER JOIN $schema._panier on _Client.ID_Client = _panier.ID_Client where _Client.ID_Client = ?");
        $sth->execute(array($idClient));
        return $sth->fetchAll()[0];
    }

    function listeAdresse($idClient) {
        global $schema;
        global $dbh;
        
        $sth = $dbh->prepare("SELECT * from $schema._Adresse where _Adresse.ID_Client = ?");
        $sth->execute(array($idClient));
        return $sth->fetchAll();
    }

    function liste_Client() {
        global $schema;
        global $dbh;
        
        $sth = $dbh->prepare("SELECT * from $schema._Client ORDER BY nom_client, prenom_client");
        $sth->execute();
        return $sth->fetchAll();
    }

    function liste_Client_2($mots) {
        global $schema;
        global $dbh;
        
        $sth = $dbh->prepare("SELECT * from $schema._Client WHERE nom_client LIKE '%$mots%' OR prenom_client LIKE '%$mots%' AND active=1 ORDER BY nom_client, prenom_client");
        $sth->execute();
        return $sth->fetchAll();
    }

    function verif_email($email) {
        global $schema;
        global $dbh;
        $sth = $dbh->prepare("SELECT count(*) FROM $schema._Client WHERE email = ?");
        $execute = $sth->execute(array($email));
        return $execute->fetchAll()[0]['count'];
    }
    
    function desactiver_compte($id_client, $id_panier){
        global $schema;
        global $dbh;
        $res = false;

        $checkCommande = $dbh->prepare("select * from $schema._commande where ID_Client = ?");
        $commande = $checkCommande->execute(array($id_client));
        $commande = $checkCommande->fetchAll();
        $nbCommande = sizeof($commande);
        
        if ($nbCommande==0) {
            $suppProduit = $dbh ->prepare("DELETE from $schema._contient_produit_p where ID_Panier = ?");
            $suppProduit -> execute(array($id_panier));

            $suppPanier  = $dbh ->prepare("DELETE from $schema._panier where ID_Client = ?");
            $suppPanier ->execute(array($id_client));
            
            $suppCompte = $dbh->prepare("UPDATE $schema._Client SET active = 0 WHERE ID_Client = ?");
            $suppCompte->execute(array($id_client));

            $res = true;
        }

        return $res;
    }

    function liste_question(){
        global $schema;
        global $dbh;

        $sth = $dbh->prepare("SELECT * FROM $schema._questionsecrete");
        $sth->execute();
        $res = $sth->fetchAll();
        return $res;
    }

    function recupCommande($idCommande){
        global $schema;
        global $dbh;

        $sth = $dbh->prepare("SELECT * from $schema._commande natural join $schema._contient_produit_c natural join $schema._produit where ID_Commande = $idCommande");
        $sth->execute();
        $res = $sth->fetchAll();
        return $res;

    }

    /* ######################################## FONCTIONNALITE QUOTIDIENNE ######################################## */

    function SupressionPanierInactif(){
        global $schema;
        global $dbh;

        $sth = $dbh->prepare("SELECT ID_Panier, derniere_modif FROM $schema._panier WHERE ID_Client is null");
        $sth->execute();
        $listePanier = $sth->fetchAll(); //tableau de '$nbproduit' produits contenus dans le panier
        foreach ($listePanier as $panier) {
            if(date("Y-m-d") > date('Y-m-d', strtotime($panier['derniere_modif']. ' + 1 days'))){
                $sth = $dbh->prepare("DELETE FROM $schema._panier WHERE ID_Panier = ?");
                $sth->execute(array($panier['ID_Panier']));

                if(isset($_COOKIE['id_panier'])){
                    if($_COOKIE['id_panier'] == $panier['ID_Panier']){
                        setcookie("id_panier", "", "", "/");
                    }
                }
            }
        }

    }

    function connexion($email, $mdp)
    {
        global $schema;
        global $dbh;

        // CHIFFREMENT DU MOT DE PASSE
        include("chiffrement.php");
        $mdp = chiffrementMDP($mdp);
        
        $sth = $dbh->prepare("SELECT count(ID_Client) FROM Alizon._Client WHERE email = '$email' AND mdp = '$mdp'");
        $sth->execute();
        $result = $sth->fetchAll();

        $res = false;

        if ($result[0]['count(ID_Client)'] == 1) 
        {

            $sth2 = $dbh -> prepare("SELECT ID_Client FROM Alizon._Client WHERE email = '$email' and mdp = '$mdp'");
            $sth2->execute();
            $sth3 = $dbh -> prepare("SELECT active FROM Alizon._Client WHERE email = '$email' and mdp = '$mdp'");
            $sth3->execute();

            $id_client = $sth2 -> fetchAll()[0]['ID_Client'];
            $statut_active = $sth3 -> fetchAll()[0]['active'];
            $res = true;
        }

        return array($res, $id_client, $statut_active);
    }

    function connexion_vendeur($email, $mdp)
    {
        global $schema;
        global $dbh;

        // CHIFFREMENT DU MOT DE PASSE
        include("chiffrement.php");
        $mdp = chiffrementMDP($mdp);

        $sth = $dbh->prepare("SELECT count(ID_Vendeur) FROM Alizon._Vendeur WHERE email = '$email' AND mdp = '$mdp'");
        $sth->execute();
        $result = $sth->fetchAll();

        $res = false;

        if ($result[0]['count(ID_Vendeur)'] == 1) 
        {

            $sth2 = $dbh -> prepare("SELECT ID_Vendeur FROM Alizon._Vendeur WHERE email = '$email' and mdp = '$mdp'");
            $sth2->execute();
            $id_client = $sth2 -> fetchAll()[0]['ID_Vendeur'];
            $res = true;
        }

        return array($res, $id_client);
    }
    function recupDonneeVendeur($id){
        global $schema;
        global $dbh;

        $sth = $dbh->prepare("SELECT * FROM Alizon._vendeur WHERE ID_Vendeur = '$id'");
        $sth->execute();
        $res = $sth->fetchAll();
        return $res;
    }


    function ajouterAdresse($id_client,$adr, $complementAdresse, $ville, $codePostal,$facturation){
        global $schema;
        global $dbh;

        if(empty($complementAdresse)){
            $sth = $dbh -> prepare("INSERT INTO Alizon._Adresse (ID_Client,nom_de_rue,ville,code_postale,adresse_facturation) VALUES (?,?,?,?,?)");
            $sth->execute(array($id_client,$adr,$ville,$codePostal,$facturation));
        }else{
            $sth = $dbh -> prepare("INSERT INTO Alizon._Adresse (ID_Client,nom_de_rue,complement,ville,code_postale,adresse_facturation) VALUES (?,?,?,?,?,?)");
            $sth->execute(array($id_client,$adr,$complementAdresse,$ville,$codePostal,$facturation));
        }
    }

    function modifierAdresse($id_adresse,$adr, $complementAdresse, $ville, $codePostal,$facturation){
        global $schema;
        global $dbh;

        if(empty($complementAdresse)){
            $sth = $dbh -> prepare("UPDATE $schema._Adresse SET nom_de_rue = ? , complement = ? , ville = ? ,code_postale = ? , adresse_facturation = ?  WHERE ID_Adresse = ? ;");
            $sth->execute(array($adr,NULL,$ville,$codePostal,$facturation,$id_adresse));
        }else{
            $sth = $dbh -> prepare("UPDATE $schema._Adresse SET nom_de_rue = ? , complement = ? , ville = ? ,code_postale = ? , adresse_facturation = ? WHERE ID_Adresse = ? ;");
            $sth->execute(array($adr,$complementAdresse,$ville,$codePostal,$facturation,$id_adresse));
        }
    }
    function infosAdresse($id_adresse){
        global $schema;
        global $dbh;

        $sth = $dbh->prepare("SELECT * FROM $schema._Adresse WHERE ID_Adresse = ?");
        $sth->execute(array($id_adresse));
        $result = $sth->fetchAll();
        return $result;
    }

    function SupprimerAdresse($ID_Adresse){
        global $schema;
        global $dbh;

        $sth = $dbh -> prepare("DELETE FROM Alizon._Adresse WHERE ID_Adresse = ? ;");
        $sth->execute(array($ID_Adresse));
    }


    function inscription($email, $nom, $prenom, $ddn, $mdp, $cmdp, $rep,$adr_facturation, $complementAdresse_facturation, $ville_facturation, $codePostal_facturation, $adr_livraison = NULL, $complementAdresse_livraison= NULL, $ville_livraison= NULL, $codePostal_livraison= NULL)
    {
        global $schema;
        global $dbh;

        $sth = $dbh -> prepare("SELECT ID_Client FROM Alizon._Client WHERE email = ?");
        $sth->execute(array($email));
        $tabverif = $sth->fetchAll();
        $verif = 0;

        $res = "Errormdp";
        foreach ($tabverif as $row){
            $verif+=1;
        }

        if ($verif >= 1) {
            $res = "Erroremail";
        }else{
            if($mdp === $cmdp){
                // INSERTION DU NOUVEAU CLIENT DANS LA TABLE CLIENT
                $nom = $_POST['nom'];
                $prenom = $_POST['prenom'];
                
                $ddn = $_POST['ddn'];

                //CHIFFREMENT DU MOT DE PASSE
                include("chiffrement.php");
                $mdp = chiffrementMDP($mdp);

                $question = $_POST['question'];
                $reponse = $_POST['reponse'];
                
                $sth = $dbh -> prepare("INSERT INTO Alizon._Client (nom_client,prenom_client,date_de_naissance,email,mdp,QuestionReponse) VALUES ('$nom','$prenom','$ddn','$email','$mdp','$reponse')");
                $sth->execute();
                
                
                // RECUPERATION DE L'ID CLIENT
                $sth = $dbh -> prepare("SELECT ID_Client from Alizon._Client WHERE email = ?");
                $sth->execute(array($email));
                $id_client = $sth->fetchAll()[0]['ID_Client'];
                
                $sth = $dbh -> prepare("UPDATE Alizon._panier SET ID_Client = ? WHERE id_panier = ?");
                $sth->execute(array($id_client,$_COOKIE['id_panier']));
                $res = true;

                
                // AJOUT DES ADRESSES 
                ajouterAdresse($id_client,$adr_facturation,$complementAdresse_facturation,$ville_facturation,$codePostal_facturation,TRUE);

                if($adr_livraison != '' || $adr_livraison != NULL){
                    ajouterAdresse($id_client,$adr_livraison,$complementAdresse_livraison,$ville_livraison,$codePostal_livraison,FALSE);
                }
            }
            else {
                $res = "ErrorMdp";
            }
        }
        return $res;
    }

    function verif_mdp($id)
    {
        global $schema;
        global $dbh;

        $stmt = $dbh->prepare("SELECT mdp FROM $schema._Client WHERE ID_Client = ?");
        $stmt->execute(array($id));
        $result = $stmt->fetchAll();

        return $result;
    }
    

    function infos_paiement($id_panier)
    {
        global $schema;
        global $dbh;

        $stmt = $dbh->prepare("SELECT * FROM $schema._contient_produit_p NATURAL JOIN $schema._produit  WHERE id_panier = ?");
        $stmt->execute(array($id_panier));
        $result = $stmt->fetchAll();

        return $result;
    }


    function prix_total_paiement($id_panier)
    {
        global $schema;
        global $dbh;

        $sth = $dbh->prepare("SELECT Prix_total_TTC FROM $schema._panier WHERE id_panier = ?");
        $sth->execute(array($id_panier));
        $RecapPanier = $sth->fetchAll()[0];

        return $RecapPanier;
    }
    

    function recuperer_mdp($rep, $mdp, $email)
    {
        global $schema;
        global $dbh;

        $sth = $dbh->prepare("SELECT count(*) FROM $schema._Client WHERE email = ?");
        $sth->execute(array($email));
        $result = $sth->fetchAll();

        $res = "unknowemail";

        if ($result[0]['count(*)'] == 1) 
        {
            $sth2 = $dbh -> prepare("SELECT QuestionReponse FROM $schema._Client WHERE email = ?");
            $sth2->execute(array($email));
            $rep_cli = $sth2 -> fetchAll()[0]['QuestionReponse'];
            $res = "errorquestion";

            if($rep_cli === $rep)
            {
                include("chiffrement.php");
                $mdp = chiffrementMDP($mdp);
                $sth = $dbh->prepare("UPDATE $schema._Client SET mdp = ? WHERE email = ?");
                $sth->execute(array($mdp, $email));
                $res = 0;
            }
        }

        return $res;
    }

    function email_valide($email)
    {
        global $schema;
        global $dbh;

        $sth = $dbh->prepare("SELECT count(*) FROM $schema._Client WHERE email = ?");
        $sth->execute(array($email));
        $result = $sth->fetchAll();

        $res = false;
        if ($result[0]['count(*)'] == 1)
        {
            $res = true;
        }
        return $res;
    }

    function question_secrete($email)
    {
        global $schema;
        global $dbh;

        $sth = $dbh->prepare("SELECT Question FROM $schema._Client NATURAL JOIN $schema._questionsecrete WHERE email = ?");
        $sth->execute(array($email));
        $result = $sth->fetchAll()[0];

        return $result;
    }

    function nb_articles_panier($id_panier)
    {
        global $schema;
        global $dbh;

        $stmt = $dbh->prepare("SELECT COUNT(*) FROM $schema._contient_produit_p NATURAL JOIN $schema._produit  WHERE id_panier = ?");
        $stmt->execute(array($id_panier));
        $result = $stmt->fetchAll()[0]["COUNT(*)"];

        return $result;
    }

    function creer_commande($id_client, $ID_AdresseLivraison_Commande, $ID_AdresseFacturation_Commande)
    {
        $adresseLivraison = infosAdresse($ID_AdresseLivraison_Commande)[0];
        $adresseFacturation = infosAdresse($ID_AdresseFacturation_Commande)[0];
        global $schema;
        global $dbh;
        
        $sth = $dbh->prepare("INSERT INTO alizon._commande(ID_Client, nom_de_rue_livraison, complement_livraison, ville_livraison, code_postale_livraison, nom_de_rue_facturation, complement_facturation, ville_facturation, code_postale_facturation)VALUES(?,?,?,?,?,?,?,?,?)");
        if(empty($adresseLivraison['complement']) || empty($adresseLivraison['complement'])){
            if(empty($adresseLivraison['complement'])){
                if(empty($adresseFacturation['complement'])){
                    $sth->execute(array($id_client, $adresseLivraison['nom_de_rue'],NULL,$adresseLivraison['ville'],$adresseLivraison['code_postale'],$adresseFacturation['nom_de_rue'],NULL,$adresseFacturation['ville'],$adresseFacturation['code_postale']));
                }else{
                    $sth->execute(array($id_client, NULL,$adresseLivraison['complement'],$adresseLivraison['ville'],$adresseLivraison['code_postale'],$adresseFacturation['nom_de_rue'],$adresseFacturation['complement'],$adresseFacturation['ville'],$adresseFacturation['code_postale']));
                }
            if(empty($adresseFacturation['complement']) && isset($adresseLivraison['complement'])){
                $sth->execute(array($id_client, $adresseLivraison['nom_de_rue'],$adresseLivraison['complement'],$adresseLivraison['ville'],$adresseLivraison['code_postale'],$adresseFacturation['nom_de_rue'],NULL,$adresseFacturation['ville'],$adresseFacturation['code_postale']));
            }
        }else{
            $sth->execute(array($id_client, $adresseLivraison['nom_de_rue'],$adresseLivraison['complement'],$adresseLivraison['ville'],$adresseLivraison['code_postale'],$adresseFacturation['nom_de_rue'],$adresseFacturation['complement'],$adresseFacturation['ville'],$adresseFacturation['code_postale']));
        }
    }
    }

    function dateFr($date)
    {
        return strftime('%d/%m/%Y',strtotime($date));
    }

    function afficher_produit_vendeur($vendeur)
    {
        global $schema;
        global $dbh;

        $sth = $dbh->prepare("select ID_Commande,_contient_produit_c.ID_Produit,Nom_produit,Quantite, etat_produit_c,Prix_produit_commande_TTC from _contient_produit_c inner join _produit on _contient_produit_c.ID_Produit = _produit.ID_Produit where ID_Commande=$vendeur;");
        $sth->execute();
        $result = $sth->fetchAll();

        return $result;
    }

    function afficher_commandes_vendeur()
    {
        global $schema;
        global $dbh;

        $sth = $dbh->prepare("SELECT _client.ID_Client, nom_client, prenom_client, date_commande, prix_total, nom_de_rue_facturation, ville_facturation, code_postale_facturation  from $schema._client INNER JOIN $schema._commande ON _client.ID_Client = _commande.ID_Client ;");
        $sth->execute();
        $result = $sth->fetchAll();

        return $result;
    }

    function update_etat_commande($idCom, $idProd, $etat) {
        global $schema;
        global $dbh;

        $sth = $dbh->prepare("UPDATE alizon._contient_produit_c SET etat_produit_c = '$etat' WHERE ID_Commande= $idCom AND id_produit = $idProd");
        $sth->execute();
        $result = $sth->fetchAll();

        return $result;
    }

    function nb_commandes()
    {
        global $schema;
        global $dbh;

        $sth = $dbh->prepare("SELECT count(*) from $schema._commande;");
        $sth->execute();
        $result = $sth->fetchAll()[0]["count(*)"];

        return $result;
    }

    function infos_produits($id){

        global $schema;
        global $dbh;

        $sth = $dbh->prepare("SELECT * from $schema._Produit where ID_Vendeur = $id;");
        $sth->execute();
        $result = $sth->fetchAll();

        return $result;  
    }

    function infos_all_produits(){

        global $schema;
        global $dbh;

        $sth = $dbh->prepare("SELECT * from $schema._Produit");
        $sth->execute();
        $result = $sth->fetchAll();

        return $result;  
    }

    function infos_vendeur_cat($nom) {

        global $schema;
        global $dbh;

        $sth = $dbh->prepare("SELECT ID_Vendeur from $schema._Vendeur where Nom_vendeur = '$nom';");
        $sth->execute();
        $result = $sth->fetchAll();

        return $result;  
    }

    function all_vendeurs() {

        global $schema;
        global $dbh;

        $sth = $dbh->prepare("SELECT * from $schema._Vendeur");
        $sth->execute();
        $result = $sth->fetchAll();

        return $result;  
    }

    /* ######################################## FICHE VENDEUR ######################################## */

    function infos_vendeur2($id_vendeur){
        global $schema;
        global $dbh;

        $vendeur = $dbh->prepare("SELECT * FROM $schema._Vendeur where ID_Vendeur = ?");
        $vendeur->execute(array($id_vendeur));

        return $vendeur->fetchAll();
    }

    function liste_produit_vendeur($id_vendeur){
        global $schema;
        global $dbh;

        $liste_produit = $dbh->prepare("SELECT * FROM $schema._Produit where ID_Vendeur = ?");
        $liste_produit->execute(array($id_vendeur));

        return $liste_produit->fetchAll();
    }

    function modifier_infos_vendeur($texte, $note, $id){

        global $schema;
        global $dbh;


        $sth = $dbh->prepare("UPDATE $schema._Vendeur SET texte_Presentation = ?, note = ? WHERE ID_Vendeur = ?");
        $sth->execute(array($texte, $note, $id));
        $result = $sth->fetchAll();
    }

    function verifier_infos_mdp($ancienmdp, $id){

        global $schema;
        global $dbh;

        include("chiffrement.php");
        $mdp = chiffrementMDP($ancienmdp);

        $sth = $dbh->prepare("SELECT count(ID_Vendeur) FROM $schema._vendeur WHERE ID_Vendeur = ? AND mdp = ?");
        $sth->execute(array($id, $mdp));
        $result = $sth->fetchAll();

        $res = false;

        if ($result[0]['count(ID_Vendeur)'] == 1) {

            $res = true;
        }

        return $res;
    }

    function update_mdp_vendeur($mdp, $id){

        global $schema;
        global $dbh;

        $mdp = chiffrementMDP($mdp);

        $sth = $dbh->prepare("UPDATE $schema._Vendeur SET mdp = ? WHERE ID_Vendeur = ?");
        $sth->execute(array($mdp, $id));
        $result = $sth->fetchAll();
    }
?>
