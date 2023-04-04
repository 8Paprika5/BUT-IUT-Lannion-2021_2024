<?php

    include("fonctions/Session.php");
    include("fonctions/fonctions.php");

    $produits = infos_all_produits();

    $vendeurs = all_vendeurs();

    // echo "<pre>";
    // print_r($vendeurs);
    // echo "</pre>"; 
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Exporter un catalogue</title>
    </head>
    <body>

        <header>
            <?php include('header_admin.php'); ?>
        </header>

        <main class="exporter_catalogue_admin catalogue">

            <h1>Exporter un catalogue</h1>
            <hr>
            <?php
                if (isset($_POST) && count($_POST) > 0){

                        echo '<div class="erreur_co switch">';
                        echo '<p class="texte_erreur_co">Le catalogue a été exporté avec succès.</p>';
                        echo '</div>';
                }
            ?>
            <table id="myTable produits">
                <tr class="en-tete">
                    <th>
                        <p>Exporter un produit</p>
                    </th>
                    <th>
                        <a  onclick="SortTableProduitsTri2()" href="javascript:void(0);" class="id_produit">N°</a>
                        <i class="fa-solid fa-sort"></i>
                    </th>
                    <th>
                        <a  onclick="SortNameProduits2()"  href="javascript:void(0);" class="nom_produit">Nom</a>
                        <i class="fa-solid fa-sort"></i>
                    </th>
                    <th>
                        <a  onclick="SortPrice2(2)" href="javascript:void(0);" class="prixHT_produit">Prix HT</a>
                        <i class="fa-solid fa-sort"></i>
                    </th>
                    <th>
                        <a  onclick="SortPrice2(3)" href="javascript:void(0);" class="prixTTC_produit">Prix TTC</a>
                        <i class="fa-solid fa-sort"></i>
                    </th>
                    <th>
                        <a onclick="SortPrice2(4)" href="javascript:void(0);" class="stock_produit">Stock</a>
                        <i class="fa-solid fa-sort"></i>
                    </th>
                    <th>
                        <a  onclick="SortPrice2(5)" href="javascript:void(0);" class="avis_produit">Avis (Sur 5)</a>
                    </th>
                </tr>
                <form action="ExporterCatalogueAdmin.php" method="post">
                    <?php 
                        $index = 1;
                        foreach($produits as $produit){
                            
                            echo "<tr>";

                            echo "<td class='check'><input type='checkbox'   class='checkbox' name=" . $produit['ID_Produit'] . "></td>";
                            if ($index< 10){
                                echo "<td>0" . $index . "</td>";
                            }
                            else
                            {
                                echo "<td>" . $index . "</td>";
                            }
                            echo "<td>" . "<a href =produit.php?idProduit=" . $produit['ID_Produit']. ">" . $produit["Nom_produit"] ."</a> </td>";
                            echo "<td>" . "<p>" . $produit["Prix_vente_HT"] . "</p>" . " €" . "</td>";
                            echo "<td>" . "<p>" . $produit["Prix_vente_TTC"] . "</p>" ." €" . "</td>";
                            echo "<td>" . "<p>" . $produit["Quantite_disponnible"] . "</p>" . "</td>";
                            if ($produit["Moyenne_Note_Produit"] == ''){
                                echo "<td> Aucun Avis </td>";
                            }else{
                                echo "<td>" . $produit["Moyenne_Note_Produit"] . "</td>";
                            }

                            echo "</tr>";
                            $index = $index+1;
                        }

                    ?>
                    <input type="submit" value="Exporter le catalogue" class="confirmer" disabled>
                </form>
            </table>

            <?php 
                    if (isset($_POST) && count($_POST) > 0){

                        //print_r($_POST);
                        $tab_vendeur = [];
                        $produits_exportes = [];

                        // recupérer tous les produits exportés
                        foreach($produits as $produit){

                            foreach(array_keys($_POST) as $idP){
    
                                if($produit["ID_Produit"] == $idP){
        
                                    $produits =  array("Nom_produit" => $produit["Nom_produit"], "Prix_vente_HT" => $produit["Prix_vente_HT"], "Prix_vente_TTC" => $produit["Prix_vente_TTC"], "Description_produit" => $produit["Description_produit"], "lien_image" => "img/catalogue/Produits/".$produit['ID_Produit']."/img1.jpg", "ID_vendeur" => $produit["ID_Vendeur"]);
                                    array_push($produits_exportes, $produits);
                                }
                            }
                        }
                        
                        $produits_vendeur = [];
                        $tab_final_vendeur = [];
                        $catalogue_final = [];
                        // trier en fonction des vendeurs
                        foreach($vendeurs as $vendeur){
                            
                            $tab_vendeur = ["nomVendeur" => $vendeur["Nom_vendeur"], "raisonSociale" => $vendeur["Raison_sociale"], "siretVendeur" => $vendeur["Siret"], "lien_logo" => "img/vendeur/" . $vendeur["ID_Vendeur"] . "/img1.webp",  "emailVendeur" =>  $vendeur["Email"], "TVA_Vendeur" => $vendeur["TVA"], "nom_de_rue" => $vendeur["nom_de_rue"], "ville" => $vendeur["ville"], "code_postal" => $vendeur["code_postale"], "presentationVendeur" => $vendeur["texte_Presentation"], "noteVendeur" => $vendeur["note"]];
                            $produits_vendeur = [];
                            foreach($produits_exportes as $produit){

                                if($vendeur["ID_Vendeur"] == $produit["ID_vendeur"]){

                                    array_push($produits_vendeur, $produit);
                                }
                            }

                            if(count($produits_vendeur) != 0){

                                $tab_final_vendeur = array("vendeur" => $tab_vendeur, "produits" => $produits_vendeur);
                                array_push($catalogue_final, $tab_final_vendeur);
                            }
                        }

                        $catalogueV = json_encode($catalogue_final, JSON_UNESCAPED_UNICODE);

                        file_put_contents("../catalogue/multi.json", $catalogueV);
                    }                    
            ?>

        </main>

        <footer>
            <?php include('footer.php'); ?>
        </footer>
    </body>
</html>

<script>

    const checkbox = document.querySelectorAll('.checkbox');
    let nb_checkbox_checked = 0

    checkbox.forEach((checkbox) => {
    checkbox.addEventListener('change', function() {

        if (this.checked) {
            nb_checkbox_checked+= 1
        }
        else{
            nb_checkbox_checked-= 1
        }
        available_export(nb_checkbox_checked)
    });
    });


    function available_export(nb_checkbox_checked){


        let btn_export = document.querySelector(".exporter_catalogue_admin .confirmer")

        if(nb_checkbox_checked >= 1){

            btn_export.disabled = false;
            btn_export.classList.add("available")
        } else {

            btn_export.disabled = true;
            btn_export.classList.remove("available")
        }
    }

    function cocher_all_checkbox(){

        let checkbox = document.querySelectorAll('.checkbox');

        console.log(checkbox)

        for (let index = 0; index < checkbox.length; index++) {
           
           checkbox[index].checked = true
           available_export() 
        }
    }
</script>

<script src="https://momentjs.com/downloads/moment.min.js"></script>
<script src="../js/FiltrerProduitsAdmin.js"></script>
<script src="../js/feedback_catalogue.js"></script>