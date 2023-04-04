<?php include("Session.php")?>

<?php
$rechercheProduit = $dbh->("SELECT * FROM ALIZON._Produit  where ID_Produit  = ?");
$infoProduit = $rechercheProduit->execute(array($_POST['id_produit']));
?>