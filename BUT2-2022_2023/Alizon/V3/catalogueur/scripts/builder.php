<?php
    /*#################### DATA DECODE ####################*/
    $options = getopt(null, array("file:"));
    $file = $options["file"];

    $data = file_get_contents('sample/'.$file.'.json');
    $data_decode = json_decode($data);
    //$src = "http://localhost:8080/";
    $src = "./";
    //$src = "host.docker.internal:8080/";

    /*print_r($options);
    echo '<pre>';
    print_r($data_decode[1]->vendeur);
    echo $src.$data_decode->vendeur[0]->lien_logo;
    echo '</pre>';*/

    

    
    /*#################### MODELE ####################*/
    
    
    $pageHtml = "<!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta http-equiv='X-UA-Compatible' content='IE=edge'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <link rel='stylesheet' type='text/css' href='../scripts/builder.css'>
        <title>Catalogue</title>
    </head>
    <body>";

    $headerListeproduits = "<section class='column_vendeur_produits'>
    <article>
        <h3 class = 'titre_produit'>Liste des produits</h3>   
    </article>";



    $separation = "<hr class='pageBreaker pageBreaker-after'/>";

    $footer = "</section>
            </main>
        </body>
    </html>";

    $NbVendeur = sizeof($data_decode);
    foreach ($data_decode as $key => $data) {
        $lien_logo = $src.$data->vendeur->lien_logo;
        $nomVendeur = $data->vendeur->nomVendeur;
        $raisonSociale = $data->vendeur->raisonSociale;
        $siretVendeur = $data->vendeur->siretVendeur;
        $TVA_Vendeur = $data->vendeur->TVA_Vendeur;
        
        $adresseVendeur = $data->vendeur->adresseVendeur;
        $emailVendeur = $data->vendeur->emailVendeur;
        $presentationVendeur = $data->vendeur->presentationVendeur;
        $noteVendeur = $data->vendeur->noteVendeur;
        $liste_Produit = $data->produits;

        /* ########## Infos vendeur ##########*/
        $vendeur = "<main class='main_fiche_vendeur'>
            <section class = 'BlockInfosVendeur'>
                <h3>Catalogue $nomVendeur</h3>
                <section class = 'DescriptionVendeur'>
                    <section class = 'vendeurPartieHaut'>
                        <div class = 'imgVendeur'>
                            <img src = '$lien_logo' alt='$lien_logo'>
                        </div>
                        <div class = 'infos_vendeur'>
                            <div class = 'informationsVenSansNote'>
                                <div class = 'block1infos'>
                                    <div class = 'infosVendeur'>
                                        <h4>$raisonSociale</h4>
                                    </div>
                                    <div class = 'infosVendeur'>
                                        <h4>N°Siret : $siretVendeur</h4>
                                    </div>
                                </div>
                                <div class = 'infosSupp'>
                                    <div class = 'infosVendeur'>
                                        <h4>Alpine@gmail.com</h4>
                                    </div>
                                    <div class = 'infosVendeur'>
                                        <h4>TVA $TVA_Vendeur</h4>
                                    </div>
                                </div>   
                                <div class = 'AdresseVendeur'>
                                    <div class ='Adresse'>
                                        <h4>Ecommoy</h4>
                                    </div>
                                    <div class ='Adresse'>
                                        <h4>72220</h4>
                                    </div>
                                    <div class ='Adresse'>
                                        <h4>Batiment D</h4>
                                    </div>
                                </div>
                            </div>
                            <div class = 'NoteVendeur'>
                                <p>$noteVendeur</p>
                            </div>
                        </div>
                </section>
                    <hr class = 'barre_jaune'>
                    <section class = 'vendeurDescription'>
                        <p>$presentationVendeur</p>
                    </section>
                </section>
            </section>
        </main>";

        $pageHtml.= $vendeur;
        $pageHtml.= $headerListeproduits;

        /* ########## Liste Produits ##########*/
        foreach ($liste_Produit as $key => $produit) {
            $nomProduit = $produit->Nom_produit;
            $lienImage = $src.$produit->lien_image;
            
            $description = $produit->Description_produit;
            $prixTTC = $produit->Prix_vente_TTC;
            $prixHT = $produit->Prix_vente_HT;

            $Produit = "<section class = 'produit_vendeur cursor'>
                <div class = 'infos_produit_vendeur'>
                    <img class='photo_min_2' src='$lienImage' alt='$lienImage'>
                    <section>
                        <div class = 'infos_produit1'>
                            <article>
                                <h4 class= 'info_produit_bleu_vendeur'>$nomProduit</h4>
                            </article>
                            <article class = 'vendeur_HT'>
                                <h4 class= 'info_produit_jaune_vendeur'>Prix HT : </h4></br>
                                <h4 class= 'info_produit_bleu_vendeur'>$prixHT €</h4>
                                
                            </article>
                            <article class = 'vendeur_TTC'>
                            <h4 class= 'info_produit_jaune_vendeur'>Prix TTC :</h4>
                            <h4 class= 'info_produit_bleu_vendeur'>$prixTTC €</h4>
                                
                            </article>
                        </div>
                        <div class = 'infos_produit2'>
                            <article>
                                <h5 class= 'info_produit_gris_vendeur'>$description</h5>
                            </article>
                        </div>
                    </section>
                </div>
            </section>";

            

            $pageHtml.=$Produit;
            
        };

        if($key< $NbVendeur){
            $pageHtml.= $separation;
        }
    }





$pageHtml.="</section> 
        </main>
    </body>
    </html>";

/*#################### BUILD HTML FILE ####################*/


$open = fopen('sample/'.$file.'.html','w');
fwrite($open,$pageHtml);
fclose($open);
?>