<?php 
    include("../Session.php");
    $videPannier = $dbh->prepare("DELETE FROM ALIZON._Contient_Produit_p WHERE ID_Panier = ?");
    $videPannier->execute(array($_SESSION['id_panier']));
    header("Location: ../panier.php");
?>