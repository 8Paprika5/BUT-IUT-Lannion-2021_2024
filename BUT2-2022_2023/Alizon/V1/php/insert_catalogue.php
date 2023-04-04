<?php
error_reporting(0);
include('Session.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/style.css">
  <title>Document</title>
</head>
<body>
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

               $sql = "INSERT INTO Alizon._Produit(Nom_produit, Prix_coutant, Prix_vente_HT, Quantite_disponnible, Description_produit, images1, images2, images3, Id_Sous_Categorie, ID_Vendeur) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
              
               $stmt = $dbh->prepare($sql);

               $insert = array($ligne_fic[0], $ligne_fic[1], $ligne_fic[2], $ligne_fic[3], $ligne_fic[4], $ligne_fic[5], $ligne_fic[6], $ligne_fic[7], $ligne_fic[8], $ligne_fic[9]);

               $stmt->execute($insert);
               $importValide = true;

           }
        }

        $sth = $dbh->prepare("SELECT * from Alizon._Produit");
        $sth->execute();
        $result = $sth->fetchAll();

        echo "<pre>";
        print_r($result);
        echo "</pre>";
    }
} else {
  $importValide = false;
}

?>

<a class="revenirImport" href="ajoutCatalogue.php">Revenir à la page précédente</a>
  
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