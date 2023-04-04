<?php
error_reporting(0);
include("fonctions/Session.php");
include("fonctions/fonctions.php");

$extension_fic = substr($_FILES['file']['name'], -3);
if( $extension_fic == "csv"){
  //echo "Un fichier de type " . $extension_fic . " n'est pas accepté, Veuillez utiliser un fichier de type csv.";
  $cp_lignes_fic = 0;

  //Permet avec l'ajout d'un fichier excel de les insérer dans la bdd
  if ($_FILES['file']['size'] != 0 )//On regarde si la taille du fichier n'est pas vide
  {
    if (isset($_POST["import"])) {

      $fileName = $_FILES["file"]["tmp_name"];
      $file = fopen($fileName, "r");

      $verif_csv = true;
      $tabimport = [];

        while(!feof($file) && $verif_csv == true)
        {

            $ligne_fic = fgetcsv($file, null, ';');//Délimiteur

            if ($cp_lignes_fic != 0){ 

                if($ligne_fic[0] != NULL)//Tant que la ligne n'est pas nulle on insère
                {
                    $array_produits = array($ligne_fic[0], $ligne_fic[1], $ligne_fic[2], $ligne_fic[3], $ligne_fic[4], $ligne_fic[5], $ligne_fic[6], $ligne_fic[7], $ligne_fic[8], $ligne_fic[9], $ligne_fic[10], $ligne_fic[11]);
                    array_push($tabimport, $array_produits);
                    inserer_catalogue($array_produits);
                    $importValide = true;
                    //echo $ligne_fic[0] . " " . $ligne_fic[1];
                }
            }
            else{ // vérifier que l'entete du csv est bon

              $verif_csv = verifier_entete_csv($ligne_fic);
              
              if($verif_csv == false)
              {
                  $importValide = "EnteteInvalide";
              }
            }

            $cp_lignes_fic++;

          }
          //Permet au vendeur de visualiser tous les produits qu'il vient d'insérer avec son catalogue.

    }
  } else {
    $importValide = "extensionFichierInvalide";
  }
}


if($importValide === true){

  $_SESSION["produits_importes"] = $tabimport;
  header("Location: vendeur.php?import=validated");
  exit();

} elseif($importValide === "EnteteInvalide"){

  header("Location: vendeur.php?import=unsupportedcsv");
  exit();

}else{

  header("Location: vendeur.php?import=unsupportedfile");
  exit();
}

?>

