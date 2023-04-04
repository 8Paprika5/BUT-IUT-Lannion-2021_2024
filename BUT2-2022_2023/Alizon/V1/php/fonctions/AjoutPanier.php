<?php include("../Session.php");

$cttc = $dbh->prepare("SELECT Prix_vente_TTC FROM ALIZON._Produit  where ID_Produit = ?");
$ttc = $cttc->execute(array($_GET['id_produit']));

$cht = $dbh->prepare("SELECT Prix_vente_HT FROM ALIZON._Produit  where ID_Produit  = ?");
$ht = $cht->execute(array($_GET['id_produit']));

$cverif = $dbh->prepare("SELECT COUNT(*) FROM ALIZON._Contient_Produit_p where ID_Produit  = ? AND ID_Panier = ?");
$cverif->execute(array($_GET['id_produit'],$_SESSION['id_panier']));
$verif = $cverif->fetchAll();

$addProduit = $dbh->prepare("INSERT INTO ALIZON._Contient_Produit_p(ID_Panier,ID_Produit,Quantite)VALUES(?,?,?);");
$upProduit = $dbh->prepare("UPDATE ALIZON._Contient_Produit_p set Quantite = ? where ID_Produit  = ? AND ID_Panier = ?");

if ($verif[0]["count"]<1) {
    $addProduit->execute(array($_SESSION['id_panier'],$_GET['id_produit'],$_POST['nbProduits'])); 
}
else {
    $cquantite = $dbh->prepare("SELECT Quantite FROM ALIZON._Contient_Produit_p where ID_Produit  = ? AND ID_Panier = ?");
    $quantite = $cquantite->execute(array($_GET['id_produit'],$_SESSION['id_panier']));
    if ($quantite + $_POST['nbProduits']<99) {
        $upProduit->execute(array($quantite+$_POST['nbProduits'],$_GET['id_produit'],$_SESSION['id_panier']));
    }
    else {
        print "Erreur !: Le produit a atteint la quantiter max <br/>";
    }

}
header("Location: ../panier.php");



?>