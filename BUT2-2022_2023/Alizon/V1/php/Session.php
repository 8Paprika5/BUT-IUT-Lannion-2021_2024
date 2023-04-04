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

    if(!isset($_SESSION['id_panier'])){
        try {
            
            $sth = $dbh->prepare("SELECT * from $schema._panier ORDER BY id_panier DESC LIMIT 1");
            $sth->execute();
            $_SESSION['id_panier'] = ($sth->fetchAll())[0]['id_panier']+1;
            $sth = $dbh->prepare("INSERT INTO Alizon._panier(ID_Panier,Prix_total_HT,Prix_total_TTC)VALUES(?,?,?);");
            $sth->execute(array($_SESSION['id_panier'],0,0));   

        } catch (PDOException $e) {
            print "Erreur !: " . $e->getMessage() . "<br/>";
        }
    }

    if(!isset($_SESSION["login"])){
        $_SESSION["login"] = null;
    }
?>