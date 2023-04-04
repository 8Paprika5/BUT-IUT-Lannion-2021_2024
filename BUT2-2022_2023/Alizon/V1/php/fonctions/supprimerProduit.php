<?php include("../Session.php");
    $supprimerProduit = $dbh->prepare("DELETE FROM ALIZON._Contient_Produit_p WHERE ID_Panier = ? and ID_Produit  = ?");
    $supprimerProduit->execute(array($_SESSION['id_panier'],$_GET['id_produit']));
    header("Location: ../panier.php");
?>