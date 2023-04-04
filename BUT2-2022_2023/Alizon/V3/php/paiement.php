<?php 
    include("fonctions/Session.php");
    include("fonctions/fonctions.php");

    $result = infos_paiement($_COOKIE["id_panier"]);
    /** Tableau result contenant des tableau avec les param suivant :
     * [ID_Produit]
     * [ID_Panier]
     * [Quantite] 
     * [Prix_produit_commande_HT]
     * [Prix_produit_commande_TTC]
     * [Nom_produit]
     * [Prix_coutant]
     * [Prix_vente_HT]
     * [Prix_vente_TTC]
     * [Quantite_disponnible]
     * [Description_produit]
     * [images1]
     * [images2]
     * [images3]
     * [Moyenne_Note_Produit]
     * [Id_Sous_Categorie]
     * [ID_Vendeur]
     **/

    $prixTotal = prix_total_paiement($_COOKIE["id_panier"])["Prix_total_TTC"];
    
    $infos_cli = infos_cli($_SESSION["id_client"]);
    /**Tableau $infos_cli
     * [ID_Client]
     * [nom_client]
     * [prenom_client]
     * [adresse_livraison]
     * [adresse_facturation]
     * [date_de_naissance]
     * [email]
     * [mdp] 
     * [QuestionReponse]
     * [active]
    */
    
    $listeAdresse = listeAdresse($_SESSION['id_client']);
    $listeAdresseFacturation = array();
    $listeAdresseLivraison = array();
    foreach($listeAdresse as $adr){
        if($adr['adresse_facturation']!=0){
            array_push($listeAdresseFacturation,$adr);
        }else{
            array_push($listeAdresseLivraison,$adr);
        }
    }

    if(isset($_POST['cardnumber'])){
        header("Location: confirmation.php?ID_AdresseFacturation_Commande=".$_POST['ID_AdresseFacturation_Commande']."&ID_AdresseLivraison_Commande=".$_POST['ID_AdresseLivraison_Commande']);
    }

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réglement de la commande</title>

</head>
<body>
    <?php include("header.php"); ?>

    <main class="paiement">
        <h1>Récapitulatif de paiement</h1>
        <div class="carte_paiement">
            <section>
                <article class="carte_livraison">
                    <hr>
                    <aside><a href="monCompte.php#Mes-adresses">Modifier mes adresses</a></aside>

                    <div class="carte_livraison_chooseAdresse">
                        <p for="adressFacturation-select">Adresse de facturation</p>
                        <select name="adress" id="adressFacturation-select">
                            <option value="" disabled> Choisissez une adresse de facturation</option>
                            <?php $i = 1; foreach ($listeAdresseFacturation as $key => $adr):
                                if($adr['complement'] != ''){
                                    $adresse = $adr['nom_de_rue'] . " " . $adr['complement'] . " " . $adr['code_postale'] . " " . $adr['ville'];
                                }else{
                                    $adresse = $adr['nom_de_rue'] . " " . $adr['code_postale'] . " " . $adr['ville'];
                                }?>

                                <?php if($i <= 1): ?>
                                    <option value=<?php echo $adr['ID_Adresse']?> selected> <?php echo $adresse?></option>
                                <?php else: ?>
                                    <option value=<?php echo $adr['ID_Adresse']?>> <?php echo $adresse?></option>
                                <?php endif; ?>
                                <?php $i++; ?>
                            <?php endforeach;?>
                        </select>
                    </div>

                    <div class="carte_livraison_chooseAdresse">
                        <p for="adressLivraison-select">Adresse de livraison</p>
                        <select name="adress" id="adressLivraison-select">
                            <option value="" disabled> Choisissez une adresse de livraison</option>
                            <?php $i = 1; foreach ($listeAdresse as $key => $adr):
                                if($adr['complement'] != ''){
                                    $adresse = $adr['nom_de_rue'] . " " . $adr['complement'] . " " . $adr['code_postale'] . " " . $adr['ville'];
                                }else{
                                    $adresse = $adr['nom_de_rue'] . " " . $adr['code_postale'] . " " . $adr['ville'];
                                }?>

                                <?php if($i <= 1): ?>
                                    <option value=<?php echo $adr['ID_Adresse']?> selected> <?php echo $adresse?></option>
                                <?php else: ?>
                                    <option value=<?php echo $adr['ID_Adresse']?>> <?php echo $adresse?></option>
                                <?php endif; ?>
                                <?php $i++; ?>
                            <?php endforeach;?>
                        </select>
                    </div>

                    <p>Destinataire : <?php echo $infos_cli["nom_client"] . "\n" . $infos_cli["prenom_client"]; ?></p>
                    <p>Email : <?php echo $infos_cli["email"];?></p>
                </article>

                <article class="carte_methode_paiement">
                    <hr>
                    <h2>Méthodes de paiement</h2>

                    <div class="btn-cb">
                        <i class="fa-solid fa-credit-card fa-xl"></i>
                        <p>Carte Bancaire</p>
                    </div>
                </article>

                <article class="CarteBancaire cache">
                    <div class="fermer-blocCB">
                        <i class="fa-solid fa-square-xmark fa-2x"></i>
                    </div>
                    <div class="col-75">
                        <div class="conteneur">
                            <form method="post" onsubmit="return VerificationCarteBleu();">
                                <div class="ligne">
                                    <div class="col-50">
                                    <div class="erreur_co">
                                        <p class="texte_erreur_co texte_erreur_cardnumber"></p>
                                        <p class="texte_erreur_co texte_erreur_dateCB"></p>
                                    </div>
                                        <h3>Paiement</h3>
                                        <label for="fname">Cartes acceptées</label>
                                        <div class="icon-conteneur">
                                            <i class="fa-brands fa-cc-visa"></i>
                                            <i class="fa-brands fa-cc-mastercard"></i>
                                            <i class="fa-brands fa-cc-stripe"></i>
                                            <i class="fa-brands fa-cc-jcb"></i>
                                            <i class="fa-brands fa-cc-discover"></i>
                                            <i class="fa-brands fa-cc-diners-club"></i>
                                            <i class="fa-brands fa-cc-amex"></i>
                                        </div>

                                        <label for="cname">Nom sur la carte</label>
                                        <input type="text" id="cname" name="cardname" placeholder="John More Doe" required>
                                    
                                        <label for="ccnum">Numéro de carte de crédit <i id="TypeCbPayement" class=""></i></label>
                                        <input type="text" id="ccnum" name="cardnumber" placeholder="1111-2222-3333-4444" maxlength="19" required>

                                        <label for="expdate">Date d'expiration</label>
                                        <input type="month" id="dateCB" name="dateCB" placeholder="01/2023" required>
                                        
                                        <label for="cvv">CVV</label>
                                        <input type="text" id="cvv" name="cvv" placeholder="352" required>
                                        <input type="hidden" name="ID_AdresseFacturation_Commande">
                                        <input type="hidden" name="ID_AdresseLivraison_Commande">
                                    </div>
                                </div>
                                
                                <input type="submit" id="BtnValiderPaiement" value="Valider le paiement" class="btn" >
                            </form>
                        </div>
                    </div>
                </article>
            </section>

            <aside class="carte_commande">
                <h2>Récapitulatif de la commande</h2>
                <h3><?php echo "Commande n°" . nb_commandes()+1; ?></h3>
                <div class="carte_commande--recap">
                    <p><?php echo sizeof($result);?> article(s)</p>
                    <p><?php echo $prixTotal." €";?></p>
                </div>
                <hr>
                <div class="carte_commande--recap">
                    <p>Livraison</p>
                    <p>offerte</p>
                </div>
                <hr>
                <div class="carte_commande--total">
                    <h4>Total : </h4>
                    <h4><?php echo $prixTotal." €";?></h4>
                </div>
                <aside>
                    En passant votre commande, Vous acceptez les <a href="#">Conditions générales de ventes</a> de Alizon.
                </aside>
            </aside>
        </div>
    </main>
    <footer>
        <?php include("footer.php"); ?>
        <script>
        /**######################################## ADRESSE DE LIVRAISON ########################################**/
        // RECUPERATION DES ELEMENTS HTML
        var AdresseFacturation_Commande = document.querySelector("input[name='ID_AdresseFacturation_Commande']");
        var AdresseLivraison_Commande = document.querySelector("input[name='ID_AdresseLivraison_Commande']");
        var adressFacturation_Select = document.getElementById("adressFacturation-select");
        var adressLivraison_Select = document.getElementById("adressLivraison-select");
        
        let selectedValue = adressFacturation_Select.options[adressFacturation_Select.selectedIndex].value;
        AdresseFacturation_Commande.value = selectedValue;
        selectedValue = adressLivraison_Select.options[adressLivraison_Select.selectedIndex].value;
        AdresseLivraison_Commande.value = selectedValue;

        adressFacturation_Select.addEventListener("change", function(event){    
            let selectedValue = adressFacturation_Select.options[adressFacturation_Select.selectedIndex].value;
            AdresseFacturation_Commande.value = selectedValue;
        });

        adressLivraison_Select.addEventListener("change", function(event){    
            let selectedValue = adressLivraison_Select.options[adressLivraison_Select.selectedIndex].value;
            AdresseLivraison_Commande.value = selectedValue;
        });

        /**######################################## CARTE BANCAIRE ########################################**/
        // RECUPERATION DES ELEMENTS HTML
        var btnCB = document.querySelector(".btn-cb");
        var blocPaiement = document.querySelector(".CarteBancaire");
        var CroixPaiement = document.querySelector(".fermer-blocCB");

        btnCB.addEventListener("click", ouvrirBlocPaiement);
        CroixPaiement.addEventListener("click", fermerBlocPaiement);

        function ouvrirBlocPaiement(){
            blocPaiement.classList.remove("cache");
            btnCB.classList.add("cache");
        }

        function fermerBlocPaiement(){
            blocPaiement.classList.add("cache");
            btnCB.classList.remove("cache");
        }

    </script>
        <script src="../js/paiement.js"></script>
    </footer>
</body>

</html>

