<?php
    include('databaseConnexion.php');
    
    session_start();
    /**Attributs de SESSION :
     * id_client
     * id_panier
     * login
     */

    if(!isset($_SESSION["id_client"])){
        $_SESSION["id_client"] = null;
    }
    
    if(!isset($_COOKIE['id_panier'])){
        // Si le visiteur n'a pas de panier, on en créer un nouveau
        try {
            $i =1;
            $idPanier = 1 ;
            // On recherche l'ID Panier maximum
            $sth = $dbh->prepare("SELECT max(ID_Panier) FROM alizon._Panier");
            $sth->execute();   
            $max = $sth->fetchAll()[0]['max(ID_Panier)'];
    
            // On recherche un id panier inutilisé entre 1 et le max afin de l'affecter
            while ($i < $max && $idPanier == 0){
                $sth = $dbh->prepare("SELECT count(*) from alizon._Panier where Id_Panier = ?");
                $sth->execute(array($i));   
                $id = $sth->fetchAll()[0]['count(*)'];
                if (($id) == 0){
                    $idPanier = $i;
                }
                    $i += 1;
            }

            //On créer un nouveau panier et l'associe au visiteur actuel
            $sth = $dbh->prepare("INSERT INTO Alizon._panier(derniere_modif,Prix_total_HT,Prix_total_TTC)VALUES(?,?,?);");
            $sth->execute(array(date("Y-m-d"),0,0));   
            setcookie("id_panier", $idPanier);
            

        } catch (PDOException $e) {
            print "Erreur !: " . $e->getMessage() . "<br/>";
        }
    }

    if(!isset($_SESSION["login"])){
        $_SESSION["login"] = null;
    }

    //SupressionPanierInactif();

?>