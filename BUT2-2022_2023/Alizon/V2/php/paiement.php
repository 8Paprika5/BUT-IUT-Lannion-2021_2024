<?php 
    include("fonctions/Session.php");
    include("fonctions/fonctions.php");

    $result = infos_paiement($_COOKIE["id_panier"]);

    $prixTotal = prix_total_paiement($_COOKIE["id_panier"])["Prix_total_TTC"];

    $infos_cli = infos_cli($_SESSION["id_client"]);
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
                    <aside><a href="monCompte.php">Modifier</a></aside>
                    <h2>Adresse de livraison</h2>
                    <p>Adresse de facturation :<?php echo $infos_cli["nom_client"] . "\n" . $infos_cli["prenom_client"]; ?></p>
                    <p><?php echo $infos_cli["adresse_facturation"] ; ?></p>
                    <p>Email : <?php echo $infos_cli["email"];?></p>
                </article>

                <article class="carte_methode_paiement">
                    <hr>
                    <h2>Méthodes de paiement</h2>
                    <!-- Replace "test" with your own sandbox Business account app client ID -->
                    <script src="https://www.paypal.com/sdk/js?client-id=AQkj6hJvkI1m0FOnkisTztVhXaHk_ZrTFfnUXNDZ3Au1uO4LwQH10yjHrXkuuXP9XkIg7jWgKT88a2X8&currency=EUR"></script>
                    <!-- Set up a container element for the button -->
                    <div id="paypal-button-container"></div>
                    
                    <script>
                    paypal.Buttons({
                        // Sets up the transaction when a payment button is clicked
                        createOrder: (data, actions) => {
                        return actions.order.create({
                            "purchase_units": [{
                                "amount": {
                                "currency_code": "EUR",
                                "value": "<?php echo $prixTotal;?>",
                                "breakdown": {
                                    "item_total": {  /* Required when including the items array */
                                    "currency_code": "EUR",
                                    "value": "<?php echo $prixTotal;?>"
                                    }
                                }
                                },
                                "items": [
                                    <?php 
                                    //$result = elems_panier();
                                    foreach($result as $produit)
                                    {
                                        ?>
                                        {
                                            "name": "<?php echo $produit["Nom_produit"] ; ?>", /* Shows within upper-right dropdown during payment approval */
                                            "description": "<?php echo $produit["Description_produit"] ; ?>", /* Item details will also be in the completed paypal.com transaction view */
                                            "unit_amount": {
                                            "currency_code": "EUR",
                                            "value": "<?php echo $produit["Prix_vente_TTC"] ; ?>"
                                            },
                                            "quantity": "<?php echo $produit["Quantite"] ; ?>"
                                        },
                                        <?php
                                    }
                                    ?>
                                ]
                            }]
                        });
                        },
                        // Finalize the transaction after payer approval
                        onApprove: (data, actions) => {
                        return actions.order.capture().then(function(orderData) {
                            // Successful capture! For dev/demo purposes:
                            console.log('Capture result', orderData, JSON.stringify(orderData, null, 2));
                            const transaction = orderData.purchase_units[0].payments.captures[0];
                            //alert(`Transaction ${transaction.status}: ${transaction.id}\n\nSee console for all available details`);
                            // When ready to go live, remove the alert and show a success message within this page. For example:
                            //const element = document.getElementById('paypal-button-container');
                            //element.innerHTML = '<h3>Thank you for your payment!</h3>';
                            // Or go to another URL:  actions.redirect('thank_you.html');
                            window.location.href = "./confirmation.php";
                        });
                        }
                    }).render('#paypal-button-container');
                    </script>
                </article>
            </section>

            <aside class="carte_commande">
                <h2>Récapitulatif de la commande</h2>
                <h3>Commande n°5655656</h3>
                <div class="carte_commande--recap">
                    <p><?php echo sizeof($result);?> articles</p>
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
    </footer>
</body>
</html>