<?php 
    include('databaseConnexion.php');
    
    /* ######################################## GESTION DES COMMENTAIRES ######################################## */
    function avis_produit($id_produit){
        global $schema;
        global $dbh;

        $sth = $dbh -> prepare("SELECT _Avis.ID_Client, nom_client, prenom_client, ID_Produit, Note_Produit, Commentaire, Image_Avis FROM $schema._Avis INNER JOIN $schema._Client on _Avis.ID_Client = _Client.ID_Client WHERE ID_Produit = ?");
        $sth->execute(array($id_produit));
        
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

    function ajouteSignalement($id_client,$id_commentaire){
        global $schema;
        global $dbh;

        
        $sth = $dbh -> prepare("INSERT INTO $schema._signaler (ID_Signaleur,ID_Commentaire) VALUES (?,?);");

        $sth->execute(array($id_client,$id_commentaire)); 

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

    /* ######################################## FONCTIONNALITE DU CATALOGUE ######################################## */
    function inserer_catalogue($donnees_csv){
        global $schema;
        global $dbh;

        $sql = "INSERT INTO $schema._Produit(Nom_produit, Prix_coutant, Prix_vente_HT, Quantite_disponnible, Description_produit, images1, images2, images3, Id_Sous_Categorie, ID_Vendeur) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                
        $sth = $dbh->prepare($sql);
        $sth->execute($donnees_csv);
    }

    function donnees_catalogue(){
        global $schema;
        global $dbh;

        $catalogue = $dbh->prepare("SELECT * FROM $schema.catalogue");
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

    

    function donnees_catalogue_mot_cles($mots) {
        global $schema;
        global $dbh;

        $catalogue_produits = $dbh->prepare("SELECT * FROM $schema.catalogue WHERE nom_produit LIKE '%$mots%' OR description_produit LIKE '$mots%' OR nom_souscategorie LIKE '$mots%' OR nom_categorie LIKE '$mots%'");
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
        header("Location: ./panier.php");
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
        header("Location: ./panier.php");
    }


    /* ######################################## FONCTIONNALITE DU COMPTE CLIENT ######################################## */
    function update_information($id, $prenom, $nom, $date, $email, $adresse_f) {
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
        if(!empty($email)) {
            $UpdateEmail = $dbh->prepare("UPDATE $schema._Client SET email=? where id_client=?");
            $executeEmail = $UpdateEmail->execute(array($email,$id));
        }
        if(!empty($adresse_f)) {
            $UpdateAdresse = $dbh->prepare("UPDATE $schema._Client SET adresse_facturation=? where id_client=?");
            $executeAdresse = $UpdateAdresse->execute(array($adresse_f,$id));
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

    function details_commande($id ) {
        global $schema;
        global $dbh;
        //afficher la liste des commandes en cours et effectuées
        $sth = $dbh->prepare("SELECT * from $schema._commande natural join $schema._contient_produit_c natural join $schema._produit where ID_Client = ? and etat_commande = 'en cours'");
        $sth->execute(array($id));
        $commande = $sth->fetchall();
        // $commande = $sth->fetchall()[0];

        #$sth = $dbh->prepare("SELECT COUNT(*) from $schema._commande natural join $schema._contient_produit_c where ID_Client = ?");
        #$nbrCommande = $sth->fetchAll();

        return array('listeCommande'=> $commande, 'nbCommandeCours'=>sizeof($commande));
    }
    
    function infos_Client($id) {
        global $schema;
        global $dbh;
        
        $sth = $dbh->prepare("SELECT * from $schema._Client INNER JOIN $schema._panier on _Client.ID_Client = _panier.ID_Client where _Client.ID_Client = ?");
        $sth->execute(array($id));
        return $sth->fetchAll()[0];
    }

    function liste_Client() {
        global $schema;
        global $dbh;
        
        $sth = $dbh->prepare("SELECT * from $schema._Client");
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
    
    function supprimer_compte($id_client, $id_panier){
        global $schema;
        global $dbh;

        $checkCommande = $dbh->prepare("select * from $schema._commande where ID_Client = ?");
        $commande = $checkCommande->execute(array($id_client));
        $commande = $checkCommande->fetchAll();
        $nbCommande = sizeof($commande);
        
        if ($nbCommande==0){
            $suppProduit = $dbh ->prepare("DELETE from $schema._contient_produit_p where ID_Panier = ?");
            $suppProduit -> execute(array($id_panier));

            $suppPanier  = $dbh ->prepare("DELETE from $schema._panier where ID_Client = ?");
            $suppPanier ->execute(array($id_client));
            
            $suppCompte = $dbh->prepare("DELETE FROM $schema._Client WHERE ID_Client = ?");
            $suppCompte->execute(array($id_client));
        }
    }

    function liste_question(){
        global $schema;
        global $dbh;

        $sth = $dbh->prepare("SELECT * FROM $schema._questionsecrete");
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

        $sth = $dbh->prepare("SELECT count(ID_Client) FROM Alizon._Client WHERE email = '$email' AND mdp = '$mdp'");
        $sth->execute();
        $result = $sth->fetchAll();

        $res = false;

        if ($result[0]['count(ID_Client)'] == 1) 
        {
            $sth2 = $dbh -> prepare("SELECT ID_Client FROM Alizon._Client WHERE email = '$email' and mdp = '$mdp'");
            $sth2->execute();
            $id_client = $sth2 -> fetchAll()[0]['ID_Client'];
            $res = true;
        }

        return array($res, $id_client);
    }

    function inscription($email, $nom, $prenom, $adr, $ddn, $mdp, $cmdp,  $question, $rep)
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
        }
        else
        {
            if($mdp === $cmdp)
            {
                // INSERTION DU NOUVEAU CLIENT DANS LA TABLE CLIENT
                $nom = $_POST['nom'];
                $prenom = $_POST['prenom'];
                $adr = $_POST['adr'];
                $ddn = $_POST['ddn'];
                $mdp = $_POST['mdp'];
                $question = $_POST['question'];
                $reponse = $_POST['reponse'];
                
                $sth = $dbh -> prepare("INSERT INTO Alizon._Client (nom_client,prenom_client,adresse_facturation,date_de_naissance,email,mdp,ID_QuestionSecrete,QuestionReponse) VALUES ('$nom','$prenom','$adr','$ddn','$email','$mdp','$question','$reponse')");
                $sth->execute();
                
                $sth = $dbh -> prepare("SELECT ID_Client from Alizon._Client WHERE email = ?");
                $sth->execute(array($email));
                $id_client = $sth->fetchAll()[0]['ID_Client'];
                
                $sth = $dbh -> prepare("UPDATE Alizon._panier SET ID_Client = ? WHERE id_panier = ?");
                $sth->execute(array($id_client,$_COOKIE['id_panier']));
                $res = true;
            }
        }
        return $res;
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
?>
