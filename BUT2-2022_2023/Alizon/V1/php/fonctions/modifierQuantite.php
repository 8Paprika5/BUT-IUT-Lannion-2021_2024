<?php include("../Session.php");

$modifQuantite = $dbh->prepare("UPDATE ALIZON._Contient_Produit_p set Quantite = ? where ID_Produit  = ? AND ID_Panier = ?");

if ($_POST['quantiteSelect']>99) {
    print "Erreur !: Le produit a atteint la quantiter max <br/>";

}elseif ($_POST['quantiteSelect']<1) {
    header("location: supprimerProduit.php?id_produit=".$_GET['id_produit']);
}
else {
    $upProduit = $dbh->prepare("UPDATE ALIZON._Contient_Produit_p set Quantite = ? where ID_Produit  = ? AND ID_Panier = ?");
    $upProduit->execute(array($_POST['quantiteSelect'],$_GET['id_produit'],$_SESSION['id_panier']));
    header("Location: ../panier.php");
    exit();
}

?>