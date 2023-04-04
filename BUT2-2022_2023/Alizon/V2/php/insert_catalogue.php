<?php
error_reporting(0);
include("fonctions/Session.php");
include("fonctions/fonctions.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>

<header>
  <?php include('header.php'); ?>
</header>
<?php
if ($_FILES['file']['size'] != 0 )
{
  if (isset($_POST["import"])) {
    $fileName = $_FILES["file"]["tmp_name"];
    $file = fopen($fileName, "r");

      while(!feof($file))
       {
           $ligne_fic = fgetcsv($file, null, ';');
           
           if($ligne_fic[0] != NULL)
           {
              inserer_catalogue(array($ligne_fic[0], $ligne_fic[1], $ligne_fic[2], $ligne_fic[3], $ligne_fic[4], $ligne_fic[5], $ligne_fic[6], $ligne_fic[7], $ligne_fic[8], $ligne_fic[9]));
              $importValide = true;

           }
        }
        foreach (donnees_catalogue() as $produit):?> 
          <div class="carte_produit_ajout">

              <a class="carte_produit_lien" href="produit.php?idProduit=<?php echo $produit["id_produit"]; ?>">
              
              <div class="carte_produit_ajout--image">
                  <img src="<?php echo str_replace(' ', "_","../img/catalogue/".$produit['nom_categorie']."/".$produit['nom_souscategorie']."/".$produit['id_produit']."/".$produit['images1']); ?>" alt="photo du produit">
              </div>

              <div class="carte_produit_ajout--infos">
                  <div class="carte_produit_ajout--image__description">
                      <h2><?php echo $produit["nom_produit"]; ?></h2>
                      <p><?php echo $produit["description_produit"]; ?></p>
                  </div>
              </div>

              </a>

              <div class="carte_produit_ajout--boutons">
                  <?php if($produit["quantite_disponnible"] > 0):?>
                      <h4>En stock</h4>
                      <img src="../img/stock.png" alt="logo disponibilité du produit" />
                  <?php else:?>
                      <h4>Indisponible</h4>
                      <img src="" alt="logo stock indisponible" />
                  <?php endif;?>
              </div>
          </div>

      <?php endforeach; 
    }
} else {
  $importValide = false;
}

?>

<a href="ajoutCatalogue.php"><button class="revenirImport">Revenir à la page précédente</button></a>

<footer>
  <?php include('footer.php'); ?>
</footer>
  
</body>
  <script>
    var valide = <?php echo $importValide?>;
    if(valide) {
      alert("Votre catalogue a bien été importé.");
    } else {
      alert("Le fichier est vide ou comporte une erreur")
    }
  </script>
</html>